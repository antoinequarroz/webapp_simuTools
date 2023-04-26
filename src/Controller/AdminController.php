<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Material;
use App\Form\MaterialType;
use App\Repository\MaterialRepository;


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
        $material = new Material();
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($material);
            $entityManager->flush();

            $this->addFlash('success', 'Le materiel a bien été enregistré.');

            return $this->redirectToRoute('admin_materials_list');
        }

        return $this->render('admin/admin_materials.html.twig', [
            'form' => $form->createView(),
            'material' => $material,
        ]);
    }

    /**
     * @Route("/materials/delete/{id}", name="materials_delete")
     */
    public function deleteMaterial(Material $material, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($material);
        $entityManager->flush();

        $this->addFlash('success', 'Le materiel a bien été supprimé');

        return $this->redirectToRoute('admin_materials_list');
    }


    /**
     * @Route("/materials", name="materials")
     */
    public function adminMaterials(Request $request, EntityManagerInterface $entityManager)
    {
        $material = new Material();
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le fichier téléchargé
            $file = $form->get('image')->getData();
            if ($file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    // Gérer l'exception si quelque chose se passe mal lors du téléchargement du fichier
                }

                $material->setImagePath($fileName);
            }

            $entityManager->persist($material);
            $entityManager->flush();


            return $this->redirectToRoute('admin_materials_list');
        }


            return $this->render('admin/admin_materials.html.twig', [
            'form' => $form->createView(),
            'material' => $material,
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
