<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LoginFormType;
use App\Form\RegistrationFormType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\Bundle\DoctrineBundle\Registry;



class SecurityController extends AbstractController
{

    /**
 * @Route("/login", name="app_login")
 */
public function login(Request $request, AuthenticationUtils $authenticationUtils, UserPasswordHasherInterface $passwordHasher): Response
{
    // Récupérer les erreurs de connexion, le dernier email utilisé, etc.
    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();

    // Créer le formulaire de connexion en passant le dernier email utilisé
    $loginForm = $this->createForm(LoginFormType::class, ['email' => $lastUsername]);

    // Gérer la soumission du formulaire
    $loginForm->handleRequest($request);

    // Si le formulaire est soumis et valide
    if ($loginForm->isSubmitted() && $loginForm->isValid()) {
        // Récupérer les données soumises
        $formData = $loginForm->getData();

        // Rechercher l'utilisateur dans la base de données par son email
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $formData['email']]);


        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if (!$user || !$passwordHasher->isPasswordValid($user, $formData['password'])) {
            // Si l'utilisateur n'existe pas ou si le mot de passe est incorrect, rediriger avec un message d'erreur
            $this->addFlash('error', 'Identifiants invalides.');
            return $this->redirectToRoute('app_login');
        }

        // Authentifier l'utilisateur
        // (vous pouvez ajouter ici toute logique d'authentification supplémentaire nécessaire)

        // Redirection vers la page d'accueil
        return $this->redirectToRoute('home');
    }

    // Rendre la vue avec le formulaire de connexion et les éventuelles erreurs
    return $this->render('security/login.html.twig', [
        'loginForm' => $loginForm->createView(),
        'last_username' => $lastUsername,
        'error' => $error,
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
