<?php

namespace App\Controller;

use App\Entity\Camping;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GestionCompteController extends AbstractController
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    //////// Récupérer les informations du compte camping actuellement connecté ////////
    #[Route('/gestion-compte', name: 'gestion_compte')]
    public function index(): Response
    {
        // Récupérer les informations du camping actuellement connecté
        $camping = $this->getUser();

        return $this->render('gestion_compte/gestion_compte_index.html.twig', [
            'camping' => $camping,
        ]);
    }

    //////// Mettre à jour les informations du compte camping actuellement connecté ////////
    #[Route('/gestion-compte/update/{id}', name: 'gestion_compte_update', methods: ['POST'])]
    public function update(Request $request, Camping $camping): Response
    {
        // Valider et enregistrer les modifications
        $camping->setNomCamping($request->request->get('nomCamping'));
        $camping->setSiret($request->request->get('siret'));
        $camping->setEmail($request->request->get('email'));

        $this->entityManager->flush();

        // Rediriger vers la page de gestion du compte avec un message de confirmation
        $this->addFlash('success', 'Les informations du compte ont été mises à jour.');

        return $this->redirectToRoute('gestion_compte');
    }

     //////// Mettre à jour le mot de passe du compte camping actuellement connecté ////////
     #[Route('/gestion-compte/update-password/{id}', name: 'gestion_compte_update_password', methods: ['POST'])]
     public function updatePassword(Request $request, Camping $camping): Response
     {
         $newPassword = $request->request->get('newPassword');
         $confirmPassword = $request->request->get('confirmPassword');
 
         if ($newPassword === $confirmPassword) {
             $encodedPassword = $this->passwordHasher->hashPassword($camping, $newPassword);
             $camping->setPassword($encodedPassword);
 
             $this->entityManager->flush();
 
             $this->addFlash('success', 'Le mot de passe a été mis à jour.');
             return $this->redirectToRoute('home'); // Rediriger vers la page d'accueil
         } else {
             $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
         }
 
         return $this->redirectToRoute('gestion_compte');
     }
 }
