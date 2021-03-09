<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    // on cherche le dernier article
    
    public function main(): Response{
        $dernierArticle = $this->getDoctrine()->getRepository(Article::class)->findOneBy([],["dateCreation" => "DESC"]);

        return $this->render('home/main.html.twig',[
            "dernierArticle" => $dernierArticle
        ]); 
        //** rendu de la vue */
       }
}   
