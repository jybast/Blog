<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Gestion des pages statiques de l'application
 * page d'accueil, de contact, RGPD, à propos
 * 
 * @IsGranted("ROLE_USER", message="Accès non autorisé")
 */
class PageController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     * 
     * @return Response
     */
    public function accueil(): Response
    {
        return $this->render('page/accueil.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

     /**
     * @Route("/apropos", name="apropos")
     * 
     * @return Response
     */
    public function apropos(): Response
    {
        return $this->render('page/apropos.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

     /**
     * @Route("/contact", name="contact")
     * 
     * @return Response
     */
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

     /**
     * @Route("/rgpd", name="rgpd")
     * 
     * @return Response
     */
    public function rgpd(): Response
    {
        return $this->render('page/rgpd.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    /**
     * Gestion du profil de l'utilisateur
     * @Route("/profil", name="profil")
     * 
     * @return Response
     */
    public function profil(): Response
    {
        return $this->render('page/profil.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
}
