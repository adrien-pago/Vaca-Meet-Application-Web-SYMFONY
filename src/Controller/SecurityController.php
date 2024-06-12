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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface; // New version à partir de la 5.3 symfony

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
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager): Response
    {
        $camping = new Camping();
        $form = $this->createForm(RegistrationFormType::class, $camping);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode le mot de passe avant de le sauvegarder
            $camping->setPassword($passwordEncoder->encodePassword($camping, $form->get('password')->getData())); // Utilisez la méthode encodePassword

            // Vous pouvez aussi initialiser d'autres valeurs par défaut ici, par exemple isActive, rgpdAccepted, etc.
            $camping->setIsActive(true);
            $camping->setRgpdAccepted(true);

            $entityManager->persist($camping);
            $entityManager->flush();

            // Redirige l'utilisateur vers la page de login après l'inscription
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
