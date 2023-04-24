<?php

namespace App\Controller;

use App\Repository\MaterialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Cocur\Slugify\Slugify;
use App\Entity\Material;
use Gedmo\Sluggable\Util\Urlizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


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
     * @Route("/materials/add", name="material_add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $material = new Material();

        $form = $this->createForm(MaterialType::class, $material);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $material->setSlug(Urlizer::urlize($material->getTitre()));
            $entityManager->persist($material);
            $entityManager->flush();

            $this->addFlash('success', 'Le matériel a bien été ajouté.');

            return $this->redirectToRoute('material_index');
        }

        return $this->render('material/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/material/{slug}", name="material_details")
     */
    public function materialDetails($slug, MaterialRepository $materialRepository)
    {
        $material = $materialRepository->findOneBy(['slug' => $slug]);

        if (!$material) {
            throw $this->createNotFoundException('Le matériel n\'existe pas');
        }

        return $this->render('material/details.html.twig', [
            'material' => $material,
        ]);
    }


    /**
     * @Route("/materials/edit/{id}", name="materials_edit")
     */
    public function editMaterial(Request $request, Material $material, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ...

            // Generate a slug for the material
            $slugify = new Slugify();
            $material->setSlug($slugify->slugify($material->getTitle()));

            // ...

            $entityManager->flush();

            $this->addFlash('success', 'Material updated successfully.');

            return $this->redirectToRoute('admin_materials_list');
        }

        return $this->render('admin/admin_materials.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
