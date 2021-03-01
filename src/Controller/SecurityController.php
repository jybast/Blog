<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="blog_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         // Si on trouve un utilisateur  autorisé - redirection sur la page accueil  
         if ($this->getUser()) {
             return $this->redirectToRoute('accueil');
         }

        // Récupère l'erreur si il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Dernier identifiant entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error]
        );
    }

    /**
     * @Route("/logout", name="blog_logout")
     */
    public function logout()
    {
        throw new \LogicException('Cette méthode peut rester vide - Elle sera interceptée par le paramètre de logout du firewall.');
    }
}
