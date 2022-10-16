<?php

namespace App\Controller;

use App\Entity\Administrateur;
use App\Form\PersonneType;
use App\Repository\AdministrateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdministrateurController extends AbstractController
{
    #[Route('/afficheAdmin/{id<\d+>}', name: 'afficherAdmin')]
    public function afficherAdmin(AdministrateurRepository $AdministrateurRepository, Administrateur $admin)
    {

        $admin =$AdministrateurRepository->find($admin);

        if ($this->getUser()==null) {
            return $this->redirectToRoute("authentification");
        }
        return $this->render('Administrateur/afficheAdmin.html.twig', [

            'admin'=>$admin,

        ]);


    }


    #[Route('/ajoutAdmin', name: 'ajouterAdmin')]

    public function addAdministrateur(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $admin = new Administrateur();

        $form=$this->createForm(PersonneType::class, $admin);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $hashdePassword = $passwordHasher->hashPassword($admin, $admin->getPassword());
        
            $em -> persist($admin);
            $admin -> setPassword($hashdePassword);
            $admin -> setRoles(["ROLE_ADMIN"]);
            $admin -> setCreerPar($this->getUser()->getUserIdentifier());
            $date = new \DateTime('@'.strtotime('now'));
            $admin -> setCreerLe($date);
            
            $em ->flush();

            return $this->redirectToRoute('administrateur');
  
        }
        return $this->render('Administrateur/addAdmin.html.twig',array(
            'form'=>$form->createView(), 

        ));
    }

    #[Route("modifieAdmin/{id<\d+>}", name: "modifierAdmin")]

    public function modifierAdmin(Request $request, Administrateur $admin, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
       
        $form=$this->createForm(PersonneType::class, $admin);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

         
            $hashdePassword = $passwordHasher->hashPassword($admin, $admin->getPassword());
            $admin -> setPassword($hashdePassword);
            $admin -> setModifierPar($this->getUser()->getUserIdentifier());
            $date = new \DateTime('@'.strtotime('now'));
            $admin -> setModifierLe($date);
            $em->flush();

           return $this->redirectToRoute('administrateur');
  
        }

        return $this->render('Administrateur/updateAdmin.html.twig',array(
            'form'=>$form->createView(),
            

        ));
    }

    #[Route("/supprimerAdmin/{id<\d+>}", name : "suppressionAdmin")]

    public function supprimerAdmin(Request $request, Administrateur $admin, EntityManagerInterface $em)
    {
          $em ->remove($admin);
          $em ->flush();

          return $this->redirectToRoute('administrateur');

       
    }

    #[Route('/administrateur', name: 'administrateur')]
    public function listeAdmin(Request $request, AdministrateurRepository $AdministrateurRepository)
    {

        if ($this->getUser()==null) {
            return $this->redirectToRoute("authentification");
        }
        $admins=$AdministrateurRepository->findAll();

        return $this->render('Administrateur/listAdmin.html.twig', [

          'admins'=>$admins,

            ]);
        }

    }
    

