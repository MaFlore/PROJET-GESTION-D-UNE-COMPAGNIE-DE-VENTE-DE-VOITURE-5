<?php

namespace App\Controller;

use App\Entity\Gerant;
use App\Form\PersonneType;
use App\Repository\GerantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class GerantController extends AbstractController
{
    #[Route('/afficheGerant/{id<\d+>}', name: 'afficherGerant')]
    public function afficherGerant(GerantRepository $gerantRepository, Gerant $gerant)
    {

        $gerant =$gerantRepository->find($gerant);

        if ($this->getUser()==null) {
            return $this->redirectToRoute("authentification");
        }
        return $this->render('Gerant/afficheGerant.html.twig', [

            'gerant'=>$gerant

        ]);


    }


    #[Route('/ajoutGerant', name: 'ajouterGerant')]

    public function addGerant(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $gerant = new Gerant();

        $form=$this->createForm(PersonneType::class, $gerant);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $hashdePassword = $passwordHasher->hashPassword($gerant, $gerant->getPassword());
        
            $em -> persist($gerant);
            $gerant -> setRoles(["ROLE_GERANT"]);
            $gerant -> setCreerPar($this->getUser()->getUserIdentifier());
            $date = new \DateTime('@'.strtotime('now'));
            $gerant -> setCreerLe($date);
            $gerant -> setPassword($hashdePassword);
            $em ->flush();

            return $this->redirectToRoute('gerant');
  
        }
        return $this->render('Gerant/addGerant.html.twig',array(
            'form'=>$form->createView(), 

        ));
    }

    #[Route("modifieGerant/{id<\d+>}", name: "modifierGerant")]

    public function modifierGerant(Request $request, Gerant $gerant, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {

        $form=$this->createForm(PersonneType::class, $gerant);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           
            $hashdePassword = $passwordHasher->hashPassword($gerant, $gerant->getPassword());
            $gerant -> setPassword($hashdePassword);
            $gerant -> setModifierPar($this->getUser()->getUserIdentifier());
            $date = new \DateTime('@'.strtotime('now'));
            $gerant -> setModifierLe($date);
            $em->flush();

            return $this->redirectToRoute('gerant');
  
        }

        return $this->render('Gerant/updateGerant.html.twig',[
            'form'=>$form->createView(),
            

        ]);
    }

    #[Route("/supprimerGerant/{id<\d+>}", name : "suppressionGerant")]

    public function supprimerGerant(Request $request, Gerant $gerant, EntityManagerInterface $em)
    {
          $em ->remove($gerant);
          $em ->flush();

          return $this->redirectToRoute('gerant');

       
    }

    #[Route('/gerant', name: 'gerant')]
    public function listeGerant(Request $request, GerantRepository $gerantRepository)
    {
        if ($this->getUser()==null) {
            return $this->redirectToRoute("authentification");
        }
        $gerants=$gerantRepository->findAll();

        return $this->render('Gerant/listGerant.html.twig', [

            'gerants'=>$gerants
            ]);
        }

    }
    

