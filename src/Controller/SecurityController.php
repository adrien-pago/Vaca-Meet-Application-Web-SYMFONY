<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LoginType;
use App\Form\LoginFormType;
use App\Form\RegistrationFormType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
var_dump('test2'); // debug
class SecurityController extends AbstractController
{
    


/**
 * @Route("/login", name="app_login")
*/
// SecurityController.php


public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
{
    $form = $this->createForm(LoginType::class);
    $form->handleRequest($request);
   

    if ($form->isSubmitted() && $form->isValid()) {
        $email = $form->get('email')->getData();
       
        if (empty($email)) {
            throw new \InvalidArgumentException("L'email ne peut pas être vide.");
        }

        // Continuer avec le traitement de la connexion
        return $this->redirectToRoute('home');
    }

    // Affichage du formulaire avec des messages d'erreur le cas échéant
    return $this->render('security/login.html.twig', [
        'loginForm' => $form->createView(),
        'error' => $authenticationUtils->getLastAuthenticationError(),
    ]);
}

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $registrationForm = $this->createForm(RegistrationFormType::class);
        $registrationForm->handleRequest($request);
    
        if ($registrationForm->isSubmitted()) {
    
            if ($registrationForm->isValid()) {
                $userData = $registrationForm->getData();
                $user = new User();
                $user->setEmail($userData['email']);
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $userData['password']
                );
                $user->setPassword($hashedPassword);
                $user->setNom($userData['nom']);
                $user->setPrenom($userData['prenom']);
    
                try {
                    $entityManager->persist($user);
                    $entityManager->flush();
                    
                    // Ajoutez des messages de débogage pour vérifier si l'utilisateur est bien enregistré en base de données
                    $this->addFlash('success', 'User registered successfully');
                } catch (\Exception $e) {
                    // Gérez les erreurs ici
                    $this->addFlash('error', 'Error registering user: ' . $e->getMessage());
                }
    
                return $this->redirectToRoute('app_login');
            }
        } 
        return $this->render('security/register.html.twig', [
            'registrationForm' => $registrationForm->createView(),
        ]);
    }  
}
