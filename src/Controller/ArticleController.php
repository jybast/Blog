<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Gestion des articles
 * 
 * @Route("/article")
 * 
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_lister", methods={"GET"})
     *
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function lister(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/lister.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_AUTEUR", message="Accès non autorisé")
     * @Route("/ajouter", name="article_ajouter", methods={"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function ajouter(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        // si les infos sont valides et envoyées
        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère les images transmises ('medias' = paramètre dans ArticleType)
            $images = $form->get('medias')->getData();

            // On boucle sur les images
           foreach($medias as $media){
               // On génère un nouveau nom de fichier
               $fichier = md5(uniqid()).'.'.$media->guessExtension();

               // On copie le fichier de l'image physique dans le dossier uploads
               $media->move(
                $this->getParameter('images_directory'),   // destination
                $fichier                                   // le fichier 
                );

                // On crée l'enregistrement de l'image (son nom) dans la base de données
               $img = new Media();

               $img->setNom($fichier);
               $article->addMedia($img);
           }

            // instance d'entitymanager
            $em = $this->getDoctrine()->getManager();
            // 
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article_lister');
        }

        return $this->render('article/ajouter.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/afficher/{id}", name="article_afficher", methods={"GET", "POST"})
     */
    public function afficher(Article $article): Response
    {
        return $this->render('article/afficher.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @IsGranted("ROLE_AUTEUR", message="Accès non autorisé")
     * @Route("/modifier/{id}", name="article_modifier", methods={"GET", "POST"})
     */
    public function modifier(Request $request, Article $article): Response
    {
        // Génère un objet formulaire, en lui passant l'instance
        $form = $this->createForm(ArticleType::class, $article);

        // Traitement des infos reçues dans la $request
        $form->handleRequest($request);

        // si les infos sont valides et envoyées
        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère les images transmises ('medias' = paramètre dans ArticleType)
            $images = $form->get('medias')->getData();

            // On boucle sur les images
           foreach($medias as $media){
               // On génère un nouveau nom de fichier
               $fichier = md5(uniqid()).'.'.$media->guessExtension();

               // On copie le fichier de l'image physique dans le dossier uploads
               $media->move(
                $this->getParameter('images_directory'),   // destination
                $fichier                                   // le fichier 
                );

                // On crée l'enregistrement de l'image (son nom) dans la base de données
               $img = new Media();

               $img->setNom($fichier);
               $article->addMedia($img);
           }

            // instance d'entitymanager
            $em = $this->getDoctrine()->getManager();
            // 
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article_lister');
        }

        return $this->render('article/modifier.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Cette méthode sera appelée en Ajax en utilisant la méthode "DELETE" 
     * @Route("/supprime/image/{id}", name="article_supprime_image", methods={"DELETE"})
     */
public function supprimeImage(Media $media, Request $request){
    $data = json_decode($request->getContent(), true);

    // On vérifie si le token est valide
    if($this->isCsrfTokenValid('delete'.$media->getId(), $data['_token'])){
        // On récupère le nom de l'image
        $nom = $media->getName();
        // On supprime le fichier
        unlink($this->getParameter('images_directory').'/'.$nom);

        // On supprime l'entrée de la base
        $em = $this->getDoctrine()->getManager();
        $em->remove($media);
        $em->flush();

        // On répond en json
        return new JsonResponse(['success' => 1]);
    }else{
        return new JsonResponse(['error' => 'Token Invalide'], 400);
    }
}

}
