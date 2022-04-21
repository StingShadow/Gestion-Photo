<?php

namespace App\Controller;

use App\Entity\Campagne;
use App\Repository\CampagneRepository;
use App\Repository\MessageRepository;
use App\Repository\ThemeRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {

        return $this->render('home/index.html.twig', [

        ]);
    }

    #[Route('/profil', name: 'app_profil')]
    public function profil(): Response
    {
       
        
        return $this->render('profil.html.twig', [
        ]);
    }
}
