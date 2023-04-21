<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Material;
use App\Form\MaterialType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use function stream_get_contents;



/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    /**
     * @Route("/materials/list", name="materials_list")
     */
    public function listMaterials(MaterialRepository $materialRepository): Response
    {
        $materials = $materialRepository->findAll();

        return $this->render('admin/materials_list.html.twig', [
            'materials' => $materials,
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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'Material updated successfully.');

            return $this->redirectToRoute('admin_materials_list');
        }

        return $this->render('admin/admin_materials.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/materials/delete/{id}", name="materials_delete")
     */
    public function deleteMaterial(Material $material, EntityManagerInterface $entityManager): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($material);
        $entityManager->flush();

        $this->addFlash('success', 'Material deleted successfully.');

        return $this->redirectToRoute('admin_materials_list');
    }


    /**
     * @Route("/materials", name="materials")
     */
    public function adminMaterials(Request $request, EntityManagerInterface $entityManager): Response
    {
        $material = new Material();
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        $stream = null; // Ajouter cette ligne pour initialiser la variable $stream

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UploadedFile $imageFile */
                $imageFile = $form->get('image')->getData();

                if ($imageFile) {
                    try {
                        $imageContent = file_get_contents($imageFile->getPathname());
                        $material->setImage($imageContent);
                    } catch (FileException $e) {
                        // Gérer l'exception si l'image ne peut pas être lue
                    }
                }
            }

            $entityManager->persist($material);
            $entityManager->flush();

            $this->addFlash('success', 'Material added successfully.');

            return $this->redirectToRoute('admin_materials');
        }


            return $this->render('admin/admin_materials.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/scenarios", name="scenarios")
     */
    public function adminScenarios(): Response
    {
        // Implémentez la logique de gestion des scénarios ici
        return $this->render('admin/scenarios.html.twig');
    }

    /**
     * @Route("/calendrier", name="calendar")
     */
    public function adminCalendar(): Response
    {
        // Implémentez la logique de gestion du calendrier ici
        return $this->render('admin/calendar.html.twig');
    }
}
