<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\PromotAdminType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // permet de passer un utilisateur en admin
    public function promotToAdmin($id, Request $request){
        $secret = "azerty";

        $form = $this->createForm(PromotAdminType::class);
        $form->handleRequest($request);
        // permet de gérer le traitement de la saisie du formulaire
        $em = $this->getDoctrine()->getManager();

        $utilisateur = $em->getRepository(Utilisateur::class)->find($id);
        if (!$utilisateur){
            $this->addFlash('erreur', 'L\'utilisateur n\'existe pas');
            // throw $this->createNotFoundException("Impossible de trouver l'utilisateur avec l'id : $id");
        }
        
        if ($form->isSubmitted() && $form->isValid()) { 
            if ($form->get("secret")->getData() != $secret){
                throw $this->createNotFoundException("Vous n'avez pas le bon code, vous êtes un intrus");
            }
            $utilisateur->setRoles(["ROLE_ADMIN"]);
            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('monblog_home');
        }

        return $this->render('security/promoteAdmin.html.twig',[
            "utilisateur" => $utilisateur,
            "form" => $form->createView()
        ]);
    }
}
