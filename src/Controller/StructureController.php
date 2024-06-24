<?php

namespace App\Controller;

use App\Entity\Structure;
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
    public function index(): Response
    {
        $structures = $this->entityManager->getRepository(Structure::class)->findAll();

        return $this->render('structure/index.html.twig', [
            'structures' => $structures,
        ]);
    }

    /**
     * @Route("/structure/{id}/edit", name="structure_edit", methods={"POST"})
     */
    public function edit(Request $request, Structure $structure): Response
    {
        // Récupérer les données du formulaire
        $libelle = $request->request->get('libelleStructure');
        $nbStructure = $request->request->get('nbStructure');
        $etat = $request->request->get('etatStructure');

        // Mettre à jour l'entité Structure
        $structure->setLibelleStructure($libelle);
        $structure->setNbStructure($nbStructure);
        $structure->setEtatStructure($etat);

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
