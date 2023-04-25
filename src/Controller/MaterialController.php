<?php

namespace App\Controller;

use App\Entity\Material;
use App\Form\MaterialType;
use App\Repository\MaterialRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class MaterialController extends AbstractController
{
    /**
     * @Route("/material", name="material")
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

    /**
     * @Route("/material/add", name="material_add")
     */
    public function add(Request $request): Response
    {
        $material = new Material();

        $form = $this->createForm(MaterialType::class, $material);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $slug = $slugify->slugify($material->getTitre());
            $material->setSlug($slug);

            $this->addFlash('success', 'Le matériel a bien été ajouté.');

            return $this->redirectToRoute('material_index');
        }

        return $this->render('material/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/material/{id}", name="material_details")
     */
    public function materialDetails(string $id, MaterialRepository $materialRepository): Response
    {
        $material = $materialRepository->find($id);

        if (!$material) {
            throw $this->createNotFoundException('Le matériel demandé n\'existe pas.');
        }

        return $this->render('material/details.html.twig', [
            'material' => $material,
        ]);
    }

    /**
     * @Route("/material/edit/{id}", name="materials_edit")
     */
    public function editMaterial(Request $request, Material $material, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $material->setSlug($slugify->slugify($material->getTitre()));

            $entityManager->flush();

            $this->addFlash('success', 'Material updated successfully.');

            return $this->redirectToRoute('admin_materials_list');
        }

        return $this->render('admin/admin_materials.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
