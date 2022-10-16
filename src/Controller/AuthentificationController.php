<?php

namespace App\Controller;

use App\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PersonneType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthentificationController extends AbstractController
{
    #[Route('/', name: 'authentification')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        if($this->getUser()!=null){
            return $this->redirectToRoute("tableau_de_bord");
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        return $this->render('authentification/index.html.twig',[
            'controller_name' => 'AuthentificationController',
            'error'=>$error,
            'last_username'=>$last_username
        ]);
        /*if($request->request->get('username')!=null ){
            $username= $request->request->get('username');
            $password= $request->request->get('password');
            $personne=new Personne();
            $personne=$personneRepository->findOneBy([
                'nomUtilisateur'=>$username,
                'motDePasse'=>$password
            ]);
            if($personne!=null && $personne->getId()>0){
                $session = $requestStack->getSession();
                $session->set('nom', $personne->getNom());
                $session->set('prenom', $personne->getPrenom());
                $session->set('nomUtilisateur', $personne->getNomUtilisateur());
                $session->set('nomRole', $personne->getNomRole());
                
                //$foo = $session->get('foo');

                // the second argument is the value returned when the attribute doesn't exist
                //$filters = $session->get('filters', []);
                return $this->redirectToRoute('tableau_de_bord');
            }

        }
        return $this->render('authentification/index.html.twig', [
            'controller_name' => 'AuthentificationController'
        ]);*/
    }

    #[Route('/deconnexion', name: 'deconnexion')]
    public function seDeconnecter()
    {
        // $session = $requestStack->getSession();
        // $session->set('nom', null);
        // $session->set('prenom', null);
        // $session->set('nomUtilisateur', null);
        // return $this->redirectToRoute('authentification');
        $this->getUser().session_destroy();
        return $this->redirectToRoute('authentification');

    }

    #[Route('/inscription', name: 'inscription')]
    public function creerCompte(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $personne = new Personne();
        $form=$this->createForm(PersonneType::class, $personne);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){ 
           $passwordHashed = $passwordHasher->hashPassword($personne, $personne->getPassword());
           $em ->persist($personne);
           $personne -> setPassword($passwordHashed);
           $personne -> setRoles(["ROLE_ADMIN"]);
           $personne -> setCreerPar(1);
           $personne -> setCreerLe(new \DateTime('@'.strtotime('now')));
           $em ->flush();

           return $this->redirectToRoute('authentification');
  
        }
        return $this->render('authentification/inscription.html.twig',[
            'form'=>$form->createView(), 

        ]);
    }
    
}
