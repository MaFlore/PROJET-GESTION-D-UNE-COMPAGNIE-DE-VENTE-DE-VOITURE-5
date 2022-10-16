<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClientRepository;
use App\Repository\VenteRepository;
use App\Repository\VoitureRepository;

class TableauDeBordController extends AbstractController
{
    #[Route('/tableau_de_bord', name: 'tableau_de_bord')]
    public function index(VoitureRepository $voitureRepository, VenteRepository $venteRepository,ClientRepository $clientRepository): Response
    {
        if($this->isGranted("ROLE_CLIENT"))
        {
            return $this->render('client/index.html.twig', [
                'controller_name' => 'TableauDeBordController']);
        }

        else {
            $voitures = $voitureRepository->findAll();
            $resultatVoiture = count($voitures);
            $ventes = $venteRepository->findAll();
            $resultatVente = count($ventes);
            $clients = $clientRepository ->findAll();
            $resultatClient = count(($clients));
        
            return $this->render('tableau_de_bord/index.html.twig', [
            'controller_name' => 'TableauDeBordController',
            'resultatVoiture' => $resultatVoiture,
            'resultatVente' => $resultatVente,
            'resultatClient'=> $resultatClient,
            ]);
        }
    }

}
