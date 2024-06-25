<?php

namespace App\Controller;

use App\Entity\Structure;
use App\Entity\Camping; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StructureController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/structures", name="structure_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        // Récupérer l'utilisateur connecté (Camping)
        $camping = $this->getUser();

        // Vérifier si l'utilisateur est connecté
        if (!$camping instanceof Camping) {
            return new JsonResponse(['error' => 'Utilisateur non connecté ou n\'est pas un camping.'], Response::HTTP_UNAUTHORIZED);
        }

        // Récupérer les structures associées à ce camping
        $structures = $this->entityManager->getRepository(Structure::class)->findBy(['camping' => $camping]);

        // Si la requête est AJAX, retourner le contenu du tableau en JSON
        if ($request->isXmlHttpRequest()) {
            return $this->render('structure/structure_index.html.twig', [
                'structures' => $structures,
                'nomCamping' => $camping->getNomCamping(), // Utilisation de getNomCamping() pour obtenir le nom du camping
            ]);
        }

        // Sinon, rendre la vue complète avec le layout
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/structure/new", name="structure_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        // Récupérer les données envoyées via AJAX
        $libelle = $request->request->get('libelleStructure');
        $nbStructure = $request->request->get('nbStructure');

        // Créer une nouvelle entité Structure
        $structure = new Structure();
        $structure->setLibelleStructure($libelle);
        $structure->setNbStructure($nbStructure);

        // Récupérer l'utilisateur actuel et associer la structure au camping approprié
        $camping = $this->getUser();

        // Vérifier si le camping existe
        if (!$camping instanceof Camping) {
            return new JsonResponse(['error' => 'Camping non trouvé pour cet utilisateur.'], Response::HTTP_NOT_FOUND);
        }

        // Associer la structure au camping récupéré
        $structure->setCamping($camping);

        // Persister l'entité en base de données
        $this->entityManager->persist($structure);
        $this->entityManager->flush();

        // Retourner une réponse JSON pour indiquer le succès de l'opération
        return new JsonResponse(['message' => 'Structure ajoutée avec succès!', 'id' => $structure->getId()]);
    }

    /**
     * @Route("/structure/{id}/edit", name="structure_edit", methods={"POST"})
     */
    public function edit(Request $request, Structure $structure): Response
    {
        // Vérifier si la requête est AJAX
        if (!$request->isXmlHttpRequest()) {
            // Retourner une erreur si ce n'est pas une requête AJAX
            return new JsonResponse(['error' => 'Accès non autorisé.'], Response::HTTP_FORBIDDEN);
        }

        // Récupérer les données du formulaire
        $libelle = $request->request->get('libelleStructure');
        $nbStructure = $request->request->get('nbStructure');

        // Mettre à jour l'entité Structure
        $structure->setLibelleStructure($libelle);
        $structure->setNbStructure($nbStructure);

        // Persister les changements en base de données
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Structure mise à jour avec succès!']);
    }

    /**
     * @Route("/structure/{id}/delete", name="structure_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Structure $structure): Response
    {
        // Vérifier si la requête est AJAX
        if (!$request->isXmlHttpRequest()) {
            // Retourner une erreur si ce n'est pas une requête AJAX
            return new JsonResponse(['error' => 'Accès non autorisé.'], Response::HTTP_FORBIDDEN);
        }

        // Supprimer l'entité Structure
        $this->entityManager->remove($structure);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Structure supprimée avec succès!']);
    }
}
