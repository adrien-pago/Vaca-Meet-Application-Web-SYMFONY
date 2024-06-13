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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\VarDumper\VarDumper;

class SecurityController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
    
        $form = $this->createForm(LoginFormType::class, [
            'email' => $lastUsername,
        ]);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
           
            // Récupérer les données du formulaire
            $formData = $form->getData();
            
            
            // Récupérer le camping correspondant à l'email saisi
            $camping = $this->entityManager->getRepository(Camping::class)->findOneBy(['email' => $formData['email']]);
    
            if (!$camping) {
                // Gérer l'erreur si le camping n'est pas trouvé
                throw new CustomUserMessageAuthenticationException('Email could not be found.');
            }
    
            // Récupérer le mot de passe à partir du formulaire
            $password = $form->get('password')->getData();
    
            
            if (empty($password)) {
                // Gérer l'échec de l'authentification si le mot de passe est vide
                throw new CustomUserMessageAuthenticationException('Password cannot be empty.');
            }
    
            // Vérifier si le mot de passe est correct en utilisant UserPasswordHasherInterface
            if ($passwordHasher->isPasswordValid($camping, $password)) {
                // Authentification réussie, rediriger l'utilisateur vers une page sécurisée
                return $this->redirectToRoute('home');
            } else {
                // Gérer l'échec de l'authentification
                throw new CustomUserMessageAuthenticationException('Invalid credentials.');
            }
        }
    
        return $this->render('security/login.html.twig', [
            'loginForm' => $form->createView(),
            'error' => $error,
        ]);
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): Response
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

            $this->entityManager->persist($camping);
            $this->entityManager->flush();

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
