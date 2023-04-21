<?php

namespace App\Controller;

use App\Repository\MaterialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaterialController extends AbstractController
{
    /**
     * @Route("/materiel", name="materiel")
     * @param MaterialRepository $materialRepository
     * @return Response
     */
    public function index(MaterialRepository $materialRepository): Response
    {
        // Récupérer les matériels de la base de données
        $materials = $materialRepository->findAll();

        // Transmettre les matériels au fichier Twig
        return $this->render('material/index.html.twig', [
            'materials' => $materials,
        ]);
    }
}
