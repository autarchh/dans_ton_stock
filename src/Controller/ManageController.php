<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Storage;
use App\Entity\User;
use App\Form\CategoryType;
use App\Form\StorageType;
use App\Form\UserType;
use App\Repository\CategoryRepository;
use App\Repository\StorageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/manage", name="manage_")
 */
class ManageController extends AbstractController
{
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(StorageRepository $storageRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('manage/index.html.twig', [
            'storages' => $storageRepository->findById($this->security->getUser()->getId()),
            'categories' => $categoryRepository->findAll()
        ]);
    }

/************************** USER ***********************************/

    /**
     * @Route("/profile/{id<[0-9]+>}", name="profile")
     */
    public function profile(?User $user): Response
    {
        return $this->render('manage/profile/profile.html.twig', compact('user'));
    }

    /**
     * @Route("/profile/edit/{id<[0-9]+>}", name="profile_edit", methods={"GET", "PUT"})
     */
    public function editProfile(?User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'method' => 'PUT',
            'action' => $this->generateUrl('manage_profile_edit', [
                'id' => $user->getId()
                ])
            ]);
            
            $form->handleRequest($request);
            
            if($form->isSubmitted() && $form->isValid()) {
                $em->flush();
                
                $this->addFlash('success', sprintf('Les modifications sur %s ont été enregistré.', $user->getAlias()));
                return $this->redirectToRoute('manage_profile', [
                    'id' => $user->getId()
                ]);
            }
            
            return $this->renderForm('manage/profile/edit.html.twig', [
                'user' => $user,
                'form' => $form
            ]);
    }

    



/************************** STORAGE ***********************************/

    /**
     * @Route("/storage/new", name="storage_new"), methods={"GET", "POST"}
     */
    public function newStorage(Request $request, EntityManagerInterface $em): Response
    {
        $storage = new Storage;
        $form = $this->createForm(StorageType::class, $storage, [
            'action' => $this->generateUrl('manage_storage_new')
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $storage->setUser($this->security->getUser());
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

}
