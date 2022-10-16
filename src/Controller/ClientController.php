<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/readClient/{id<\d+>}', name: 'ReadClient')]
    public function afficherClient(ClientRepository $clientRepository, Client $client){

        $client=$clientRepository->find($client);

        if ($this->getUser()==null) {
            return $this->redirectToRoute("authentification");
        }

        return $this->render('client/afficheClient.html.twig', [

            'client'=>$client

        ]);


    }

    #[Route('/client', name: 'client')]
    public function allClient(Request $request, ClientRepository $clientRepository){


        if ($this->getUser()==null) {
            return $this->redirectToRoute("authentification");
        }
            if ($request->isMethod(method:"POST"))
            {
                $selectrecherche = $request->get(key:'selectDate');
                $searchclient = $request->get(key:'searchclient');
                $client=$clientRepository->findBy(array($selectrecherche=>$searchclient));

                return $this->render('client/rechercheClient.html.twig',[

                    'clients'=>$client
        
                ]);
            }
            else{
                $clients=$clientRepository->findAll();

                return $this->render('client/listClient.html.twig', [

            'clients'=>$clients

                ]);
            }

    }

    #[Route('/ajoutClient', name: 'addClient')]

    public function addClient(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $client = new Client();
        
        $form=$this->createForm(ClientType::class, $client);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           $hashdePassword = $passwordHasher->hashPassword($client, $client->getPassword());
           
        
           $em ->persist($client);
           $client -> setRoles(["ROLE_CLIENT"]);
           $client -> setCreerPar($this->getUser()->getUserIdentifier());
           $date = new \DateTime('@'.strtotime('now'));
           $client -> setCreerLe($date);
           
           $client -> setPassword($hashdePassword);
           $em ->flush();
           
           return $this->redirectToRoute('client');
  
        }
        return $this->render('client/addClient.html.twig',array(
            'form'=>$form->createView(), 

        ));
    }

    #[Route("/editClient/{id<\d+>}", name: "updateClient")]

    public function updateClient(Request $request, Client $client, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
       
        $form=$this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

           $hashdePassword = $passwordHasher->hashPassword($client, $client->getPassword());
           $client -> setPassword($hashdePassword);
           $client -> setModifierPar($this->getUser()->getUserIdentifier());
           $date = new \DateTime('@'.strtotime('now'));
           $client -> setModifierLe($date);
           $em->flush();

           return $this->redirectToRoute('client');
  
        }

        return $this->render('client/updateClient.html.twig',array(
            'form'=>$form->createView(),
            

        ));
    }

    #[Route("/deleteClient/{id<\d+>}", name : "deleteClient")]

    public function deleteClient(Request $request, Client $client, EntityManagerInterface $em)
    {
          $em ->remove($client);
          $em ->flush();

          return $this->redirectToRoute('client');

       
    }
    
}

