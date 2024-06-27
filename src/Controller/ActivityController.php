<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Camping;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    //////////////////récupérer les info Activité déjà exitante //////////////////
    #[Route('/activity', name: 'activity_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        // Récupérer l'utilisateur connecté (Camping)
        $camping = $this->getUser();

        // Vérifier si l'utilisateur est connecté et est un camping
        if (!$camping instanceof Camping) {
            return new JsonResponse(['error' => 'Utilisateur non connecté ou n\'est pas un camping.'], Response::HTTP_UNAUTHORIZED);
        }

        // Récupérer les Activité associées à ce camping
        $activity = $this->entityManager->getRepository(Activity::class)->findBy(['camping' => $camping]);

        // Si la requête est AJAX, retourner le contenu du tableau en JSON
        if ($request->isXmlHttpRequest()) {
            return $this->render('activity/activity.html.twig', [
                'activity' => $activity,
                'nomCamping' => $camping->getNomCamping(), // Utilisation de getNomCamping() pour obtenir le nom du camping
            ]);
        }

        // Sinon, rendre la vue complète avec le layout
        return $this->render('home/index.html.twig');
    }

    ////////////////// Rajouter une Activité //////////////////
    #[Route('/activity/new', name: 'activity_new', methods: ['POST'])]
    public function new(Request $request): Response
    {
        // Récupérer les données envoyées via AJAX
        $libelle = $request->request->get('libelleActivitye');
     

        // Créer une nouvelle entité Activité
        $activity = new Activity();
        $activity->setLibelle($libelle);
     

        // Récupérer l'utilisateur actuel et associer la activité au camping approprié
        $camping = $this->getUser();

        // Vérifier si le camping existe
        if (!$camping instanceof Camping) {
            return new JsonResponse(['error' => 'Camping non trouvé pour cet utilisateur.'], Response::HTTP_NOT_FOUND);
        }

        // Associer la activité au camping récupéré
        $activity->setCamping($camping);

        // Persister l'entité en base de données
        $this->entityManager->persist($activity);
        $this->entityManager->flush();

        // Retourner une réponse JSON pour indiquer le succès de l'opération
        return new JsonResponse(['message' => 'Activité ajoutée avec succès!', 'idActivity' => $activity->getIdActivity()]);
    }

    ////////////////// Modifier une activité //////////////////
    #[Route('/activity/{id}/edit', name: 'activity_edit', methods: ['POST'])]
    public function edit(Request $request, Activity $activity): Response
    {
        // Vérifier si la requête est AJAX
        if (!$request->isXmlHttpRequest()) {
            // Retourner une erreur si ce n'est pas une requête AJAX
            return new JsonResponse(['error' => 'Accès non autorisé.'], Response::HTTP_FORBIDDEN);
        }

        // Récupérer les données du formulaire
        $libelle = $request->request->get('libelleStructure');
  
        // Mettre à jour l'entité activité
        $activity->setLibelle($libelle);
     

        // Persister les changements en base de données
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'activité mise à jour avec succès!']);
    }

    ////////////////// Supprimer une activité //////////////////
    #[Route('/activity/{id}/delete', name: 'activity_delete', methods: ['DELETE'])]
    public function delete(Request $request, Activity $activity): Response
    {
        // Vérifier si la requête est AJAX
        if (!$request->isXmlHttpRequest()) {
            // Retourner une erreur si ce n'est pas une requête AJAX
            return new JsonResponse(['error' => 'Accès non autorisé.'], Response::HTTP_FORBIDDEN);
        }

        // Supprimer l'entité Activité
        $this->entityManager->remove($activity);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Activité supprimée avec succès!']);
    }
}
