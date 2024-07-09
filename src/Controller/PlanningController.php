<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlanningController extends AbstractController
{
    #[Route('/planning', name: 'planning_index')]
    public function showWeek(Request $request): Response
    {
        // Logique pour récupérer les données du planning (par exemple, pour la semaine en cours)
        $plannings = []; // Remplacez ceci avec votre logique pour récupérer les plannings de la semaine

        return $this->render('planning/index.html.twig', [
            'plannings' => $plannings,
        ]);
    }

    #[Route('/planning/{week}', name: 'planning_week')]
    public function showSpecificWeek(Request $request, int $week): Response
    {
        // Logique pour récupérer les données du planning pour une semaine spécifique
        $plannings = []; // Remplacez ceci avec votre logique pour récupérer les plannings pour la semaine $week

        return $this->render('planning/index.html.twig', [
            'plannings' => $plannings,
            'week' => $week,
        ]);
    }

    #[Route('/planning/{id}/edit', name: 'planning_edit')]
    public function edit(Request $request, int $id): Response
    {
        // Logique pour éditer un planning spécifique avec l'id $id
        return new Response('Editing planning with ID ' . $id);
    }

    #[Route('/planning/{id}/delete', name: 'planning_delete')]
    public function delete(Request $request, int $id): Response
    {
        // Logique pour supprimer un planning spécifique avec l'id $id
        return new Response('Deleting planning with ID ' . $id);
    }

    // Méthodes pour gérer l'ajout d'activités dans le planning
    #[Route('/planning/activity/add', name: 'planning_add_activity')]
    public function addActivity(Request $request): Response
    {
        // Logique pour ajouter une activité à un planning
        return new Response('Adding activity to planning');
    }
}
