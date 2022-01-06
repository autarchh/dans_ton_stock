<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Storage;
use App\Form\CategoryType;
use App\Form\StorageType;
use App\Repository\CategoryRepository;
use App\Repository\StorageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/manage", name="manage_")
 */
class ManageController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(StorageRepository $storageRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('manage/index.html.twig', [
            'storages' => $storageRepository->findAll(),
            'categories' => $categoryRepository->findAll()
        ]);
    }

/************************** STORAGE ***********************************/

    /**
     * @Route("/storage/new", name="storage_new"), methods={"GET", "POST"}
     */
    public function newStorage(Request $request, EntityManagerInterface $em): Response
    {
        $storage = new Storage;
        $form = $this->createForm(StorageType::class, $storage);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($storage);
            $em->flush();
            
            $this->addFlash('success', sprintf('%s a bien été créé.', $storage->getName()));
            return $this->redirectToRoute('manage_index');
        }
        
        return $this->renderForm('manage/storage/new.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("/storage/{id<[0-9]+>}", name="storage_show", methods={"GET"})
     */
    public function show(?Storage $storage): Response
    {
        return $this->render('manage/storage/show.html.twig', compact('storage'));
    }

    /**
     * @Route("/storage/edit/{id<[0-9]+>}", name="storage_edit", methods={"GET", "PUT"})
     */
    public function edit(?Storage $storage, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(StorageType::class, $storage, [
            'method' => 'PUT',
            'action' => $this->generateUrl('manage_storage_edit', [
                'id' => $storage->getId()
                ])
            ]);
            
            $form->handleRequest($request);
            
            if($form->isSubmitted() && $form->isValid()) {
                $em->flush();
                
                $this->addFlash('success', sprintf('%s a bien été modifié.', $storage->getName()));
                return $this->redirectToRoute('manage_storage_show', [
                    'id' => $storage->getId()
                ]);
            }
            
            return $this->renderForm('manage/storage/edit.html.twig', [
                'storage' => $storage,
                'form' => $form
            ]);
        }
        
        /**
         * @Route("/storage/{id<[0-9]+>}", name="storage_delete", methods={"DELETE"})
         */
        public function delete(?Storage $storage, Request $request, EntityManagerInterface $em): Response
        {
            if($this->isCsrfTokenValid('storage_deletion_' . $storage->getId(), $request->request->get('csrf_token'))){
                $em->remove($storage);
                $em->flush();
                
                $this->addFlash('success', sprintf('%s a bien été supprimé.', $storage->getName()));
            }
            
            return $this->redirectToRoute('manage_index');
        }
        
/************************** CATEGORY  ***********************************/

    /**
     * @Route("/category/new", name="category_new"), methods={"GET", "POST"}
     */
    public function newCategory(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            
            $this->addFlash('success', sprintf('%s a bien été créé.', $category->getName()));
            return $this->redirectToRoute('manage_index');
        }
        
        return $this->renderForm('manage/category/new.html.twig', [
            'form' => $form
        ]);
    }

     /**
     * @Route("/category/{id<[0-9]+>}", name="category_show", methods={"GET"})
     */
    public function categoryShow(?Category $category): Response
    {
        return $this->render('manage/category/show.html.twig', compact('category'));
    }


}
