<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Form\AuteurType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuteurController extends AbstractController
{
    
    public function show($id)
    {
        $auteur = $this->getDoctrine()->getRepository(Auteur::class)->find($id);

        return $this->render('auteur/show.html.twig', [
            'auteur' => $auteur,
        ]);
    }

    public function showAll(){
        $allAuteur= $this->getDoctrine()->getRepository(Auteur:: class)->findAll();

        return $this->render('auteur/showAll.html.twig', [
            'allAuteur' => $allAuteur,
        ]);
    }

    public function detail($id)
    {
        $auteurDetail = $this->getDoctrine()->getRepository(Auteur::class)->find($id);

        return $this->render('auteur/detail.html.twig', [
            'auteurDetail' => $auteurDetail,
        ]);
    }

    public function createAuteur(Request $request)
    {
        $auteur = new Auteur();

        $form = $this->createForm(AuteurType::class, $auteur);

        $form->handleRequest($request);

        // si le formulaire a été soumis
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($auteur);
            /** Cette méthode signale à Doctrine qu'il faut garder l'entité en mémoire */
            $em->flush();
            /**Enregistre toutes les entités qui t'ont été données dans le flush*/
            return $this->redirectToRoute('monblog_auteur_showAll');
        }
        return $this->render('auteur/createAuteur.html.twig', [
            "form" => $form->createView()
        ]);
    }

    public function updateAuteur($id, Request $request){
        $em= $this->getDoctrine()->getManager();
        $auteur = $em->getRepository(Auteur::class)->find($id);

        $form= $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($auteur);
            $em->flush();
    }
    return $this->render('auteur/createAuteur.html.twig',[

        "form" => $form->createView()
    ]);
    }

    public function deleteAuteur($id){

        $em= $this->getDoctrine()->getManager();
        $auteur = $em->getRepository(Auteur::class)->find($id);
        
        $em->remove($auteur);
        $em->flush();

        //** rediriger vers la route admin */
        return $this->redirectToRoute('monblog_auteur_showAll');
    }
}
