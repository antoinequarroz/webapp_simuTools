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
use Symfony\Component\HttpFoundation\File\File;



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

            // Gestion de l'image
            $imageFile = $form['filename']->getData();
            if ($imageFile) {
                // Copie temporaire de l'image
                $tempImagePath = tempnam(sys_get_temp_dir(), 'temp_image');
                file_put_contents($tempImagePath, file_get_contents($imageFile->getPathname()));

                // Créer un objet UploadedFile à partir du fichier temporaire et supprimez-le après l'avoir utilisé
                $tempImageFile = new UploadedFile($tempImagePath, 'temp_image', null, null, true);

                // Maintenant, passez un objet File à la méthode setImageFile()
                $material->setImageFile($tempImageFile);
            }

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

        if ($form->isSubmitted() && $form->isValid()) {

            // Gestion de l'image
            $imageFile = $form['filename']->getData();
            if ($imageFile) {
                // Copie temporaire de l'image
                $tempImagePath = tempnam(sys_get_temp_dir(), 'temp_image');
                file_put_contents($tempImagePath, file_get_contents($imageFile->getPathname()));

                // Créer un objet UploadedFile à partir du fichier temporaire et supprimez-le après l'avoir utilisé
                $tempImageFile = new UploadedFile($tempImagePath, 'temp_image', null, null, true);

                // Maintenant, passez un objet File à la méthode setImageFile()
                $material->setImageFile($tempImageFile);
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
    // ...
}