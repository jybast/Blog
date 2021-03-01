<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Administration du site
 * 
 * @IsGranted("ROLE_ADMIN", message="Accès non autorisé")
 * @Route("/admin")
 * 
 */
class AdminController extends AbstractController
{
   
    /**
     * @Route("/", name="home", methods={"GET", "POST"})
     */
    public function home(Request $request)
    {
        return $this->render('admin/home.html.twig');
       
    }


    /**
     *
     * @Route("/supprimer/{id}", name="article_supprimer", methods={"DELETE"})
     */
    public function supprimer(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();

            $this->addFlash('message', 'Article supprimé avec succès.');
        }

        // renvoie vers la liste des articles
        return $this->redirectToRoute('article_lister');
    }

     /**
     * Route d'activation des articles
     * @Route("/activer/{id}", name="article_activer")
     */
    public function activerArticle(Article $article)
    {
        // on vérifie l'état actif ou non de l'article
        // si elle est active on la désative, sinon on l'active
        $article->setValide( ($article->getValide()) ? false : true );

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return new Response("true");

    }
}
