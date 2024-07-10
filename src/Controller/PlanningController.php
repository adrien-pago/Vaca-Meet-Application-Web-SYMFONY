<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Entity\Camping;
use App\Repository\PlanningRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use DateTime;

class PlanningController extends AbstractController
{
    private $entityManager;
    private $planningRepository;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, PlanningRepository $planningRepository, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->planningRepository = $planningRepository;
        $this->serializer = $serializer;
    }

    //Affichage de la page de planning
    #[Route('/planning', name: 'planning_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('planning/planning_index.html.twig');
    }

    //Récupération des activités planifiées
    #[Route('/api/planning', name: 'planning_data', methods: ['GET'])]
    public function getPlanningData(Request $request): JsonResponse
    {
        $startDate = new DateTime($request->query->get('start'));
        $endDate = new DateTime($request->query->get('end'));

        $plannings = $this->planningRepository->findByDateRange($startDate, $endDate);
        $data = $this->serializer->serialize($plannings, 'json', ['ignored_attributes' => ['camping']]);

        return new JsonResponse($data, 200, [], true);
    }

    //Ajout d'une nouvelle activité
    #[Route('/api/planning', name: 'planning_add', methods: ['POST'])]
    public function addPlanning(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $planning = new Planning();
        $planning->setLibelleActivity($data['libelle']);
        $planning->setDateDebut(new DateTime($data['dateDebut']));
        $planning->setDateFin(new DateTime($data['dateFin']));

        // Récupération du camping actuellement connecté
        $camping = $this->getUser();
        if ($camping instanceof Camping) {
            $planning->setCamping($camping);
        } else {
            return new JsonResponse(['status' => 'Camping not found'], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($planning);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Activity created!'], Response::HTTP_CREATED);
    }
}