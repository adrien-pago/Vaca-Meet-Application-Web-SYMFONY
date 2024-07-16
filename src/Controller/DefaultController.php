<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// première page quand on arrive sur le site internet
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/register", name="app_register", methods={"GET", "POST"})
     */
    public function register(): Response
    {
        // Pas besoin de rediriger, simplement rendre le formulaire d'inscription
        return $this->render('registration/register.html.twig');
    }
}
