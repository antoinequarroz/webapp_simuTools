<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Material;
use App\Form\MaterialType;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;


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
            // Si une image a été téléchargée
            if ($uploadedFile = $form['image']->getData()) {
                // Générer un nom de fichier unique
                $newFilename = uniqid() . '.' . $uploadedFile->getClientOriginalExtension();

                // Déplacez le fichier dans le dossier de téléchargement
                try {
                    $uploadedFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'erreur si quelque chose ne va pas pendant le téléchargement
                }

                // Mettre à jour la propriété imagePath de l'entité Material avec le nouveau nom de fichier
                $material->setImagePath($newFilename);
            }

            // Persistez et sauvegardez l'entité Material
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($material);
            $entityManager->flush();

            $this->addFlash('success', 'Le materiel a bien été ajouté');

            // Redirigez vers la liste des matériaux ou la page souhaitée
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
