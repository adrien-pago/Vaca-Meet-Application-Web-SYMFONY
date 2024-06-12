<?php

namespace App\Controller;

use App\Entity\Camping;
use App\Form\LoginFormType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginFormType::class, [
            'email' => $lastUsername,
        ]);

        return $this->render('security/login.html.twig', [
            'loginForm' => $form->createView(),
            'error' => $error,
        ]);
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $camping = new Camping();
        $form = $this->createForm(RegistrationFormType::class, $camping);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password securely
            $hashedPassword = $passwordHasher->hashPassword($camping, $form->get('password')->getData());
            $camping->setPassword($hashedPassword);

            // You can also set other default values here, such as isActive, rgpdAccepted, etc.
            $camping->setIsActive(true);
            $camping->setRgpdAccepted(true);

            $entityManager->persist($camping);
            $entityManager->flush();

            // Redirect the user to the login page after registration
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
    
}
