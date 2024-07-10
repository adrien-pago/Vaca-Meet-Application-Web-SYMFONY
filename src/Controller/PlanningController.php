<?php

namespace App\Controller;

use App\Repository\PlanningRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlanningController extends AbstractController
{
    #[Route('/planning', name: 'planning_index', methods: ['GET'])]
    public function index(PlanningRepository $planningRepository): Response
    {
        return $this->render('planning/planning_index.html.twig');
    }

    #[Route('/api/planning', name: 'planning_data', methods: ['GET'])]
    public function getPlanningData(PlanningRepository $planningRepository): JsonResponse
    {
        $planning = $planningRepository->findAll();
        $data = [];

        foreach ($planning as $entry) {
            $data[] = [
                'id' => $entry->getIdPlanning(),
                'libelleActivity' => $entry->getLibelleActivity(),
                'dateDebut' => $entry->getDateDebut()->format('Y-m-d H:i:s'),
                'dateFin' => $entry->getDateFin()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data);
    }
}
