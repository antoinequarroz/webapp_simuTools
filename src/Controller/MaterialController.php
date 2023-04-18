<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaterialController extends AbstractController
{
    /**
     * @Route("/materiel", name="property.index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('material/index.html.twig');
    }
}