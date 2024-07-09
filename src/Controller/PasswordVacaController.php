<?php

namespace App\Controller;

use App\Entity\Camping;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class PasswordVacaController extends AbstractController
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/password_vaca', name: 'password_vaca_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $camping = $this->getUser();

        if (!$camping instanceof Camping) {
            return new JsonResponse(['error' => 'Utilisateur non connecté ou n\'est pas un camping.'], Response::HTTP_UNAUTHORIZED);
        }

        if ($request->isMethod('POST')) {
            $newPassword = $request->request->get('newPassword');
            $confirmPassword = $request->request->get('confirmPassword');

            if ($newPassword !== $confirmPassword) {
                return new JsonResponse(['error' => 'Les mots de passe ne correspondent pas.'], Response::HTTP_BAD_REQUEST);
            }

            $hashedPassword = $this->passwordHasher->hashPassword($camping, $newPassword);
            $camping->setMdpVacancier($hashedPassword);

            $this->entityManager->persist($camping);
            $this->entityManager->flush();

            return new JsonResponse(['success' => 'Le mot de passe a été mis à jour avec succès.']);
        }

        $currentPassword = $camping->getMdpVacancier();
        $message = $currentPassword === null ? 'Première modification du mot de passe' : 'Veuillez saisir un nouveau mot de passe';

        return $this->render('passwordVaca/password_vaca_index.html.twig', [
            'message' => $message,
        ]);
    }
}
