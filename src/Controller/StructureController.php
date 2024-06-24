<?php

namespace App\Controller;

use App\Entity\Structure;
use App\Form\StructureFormType;
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
        $structures = $this->entityManager->getRepository(Structure::class)->findAll();

        // Si la requête est AJAX, retourner le contenu du tableau en JSON
        if ($request->isXmlHttpRequest()) {
            return $this->render('structure/structure_index.html.twig', [
                'structures' => $structures,
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
        $user = $this->getUser(); // Récupère l'utilisateur actuellement connecté (basé sur votre entité Camping)
        
        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non connecté.'], Response::HTTP_UNAUTHORIZED);
        }

        // Récupérer le camping associé à l'utilisateur
        $camping = $user; // l'entité Camping est utilisée pour la gestion des utilisateurs

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
     * @Route("/structure/{id}", name="structure_delete", methods={"DELETE"})
     */
    public function delete(Structure $structure): Response
    {
        // Supprimer l'entité Structure
        $this->entityManager->remove($structure);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Structure supprimée avec succès!']);
    }
}
