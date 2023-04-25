<?php

namespace App\Controller;

use App\Repository\MaterialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param MaterialRepository $materialRepository
     * @return Response
     */
    public function index(MaterialRepository $materialRepository): Response
    {
        // Récupérer les 6 derniers matériels de la base de données
        $latestMaterials = $materialRepository->findBy([], ['id' => 'DESC'], 8);

        // Transmettre les 6 derniers matériels au fichier Twig
        return $this->render('home/index.html.twig', [
            'latestMaterials' => $latestMaterials,
        ]);
    }
}
