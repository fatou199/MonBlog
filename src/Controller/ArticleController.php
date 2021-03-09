<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

// /**
// * @Route("/admin")
// */
class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function showAll()
    {
        // on utilise une fonction qu'on crée dans le ArticleRepository "findAllOrderByDate()"
        $allArticles = $this->getDoctrine()->getRepository(Article::class)->findAllOrderByDate();

        // on aurait pu ecrire :

        // $allArticles = $this->getDoctrine()->getRepository(Article::class)->findBy([],["dateCreation" => "DESC"]);

        return $this->render('article/showAll.html.twig', [
            'allArticles' => $allArticles,
        ]);
    }


    public function show($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    

    public function create(Request $request)
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        // si le formulaire a été soumis
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setDateCreation(new DateTime("now"));

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            /** Cette méthode signale à Doctrine qu'il faut garder l'entité en mémoire */
            $em->flush();
            /**Enregistre toutes les entités qui t'ont été données dans le flush*/
            return $this->redirectToRoute('monblog_article_showAll');
        }
        return $this->render('article/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    public function update($id, Request $request){
        $em= $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)->find($id);

        $form= $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($article);
            $em->flush();
    }
    return $this->render('article/create.html.twig',[

        "form" => $form->createView()
    ]);
    }

    public function delete($id){

        $em= $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)->find($id);
        
        $em->remove($article);
        $em->flush();

        //** rediriger vers la route admin */
        return $this->redirectToRoute('monblog_article_showAll');
    }

}
