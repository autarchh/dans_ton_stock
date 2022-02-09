<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin", name="admin_")
 * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas les droits d'accès.")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/category", name="category_index")
     */
    public function indexCategory(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

/************************** CATEGORIES ***********************************/


     /**
     * @Route("/category/new", name="category_new"), methods={"GET", "POST"}
     */
    public function newCategory(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category, [
            'action' => $this->generateUrl('admin_category_new')
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            
            $this->addFlash('success', sprintf('%s a bien été créé.', $category->getName()));
            return $this->redirectToRoute('admin_category_index');
        }
        
        return $this->renderForm('admin/category/new.html.twig', [
            'form' => $form
        ]);
    }

     /**
     * @Route("/category/{id<[0-9]+>}", name="category_show", methods={"GET"})
     */
    public function categoryShow(?Category $category): Response
    {
        return $this->render('admin/category/show.html.twig', compact('category'));
    }

     /**
     * @Route("/category/edit/{id<[0-9]+>}", name="category_edit", methods={"GET", "PUT"})
     */
    public function edit(?Category $category, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, $category, [
            'method' => 'PUT',
            'action' => $this->generateUrl('admin_category_edit', [
                'id' => $category->getId()
                ])
            ]);
            
            $form->handleRequest($request);
            
            if($form->isSubmitted() && $form->isValid()) {
                $em->flush();
                
                $this->addFlash('success', sprintf('%s a bien été modifié.', $category->getName()));
                
                return $this->redirectToRoute('admin_category_index');
            }
            
            return $this->renderForm('admin/category/edit.html.twig', [
                'category' => $category,
                'form' => $form
            ]);
    }

    /**
     * @Route("/category/delete/{id<[0-9]+>}", name="category_delete", methods={"DELETE"})
     */
    public function delete(?Category $category, Request $request, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid("category_deletion_" . $category->getId(), $request->request->get('csrf_token'))) {
            $em->remove($category);
            $em->flush();

            $this->addFlash('success', sprintf('%s a bien été supprimé.', $category->getName()));
        }
        return $this->redirectToRoute('admin_category_index');
    }

/************************** USER ***********************************/
    
    /**
     * @Route("/", name="user_index")
     */
    public function indexUser(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    /**
     * @Route("/user/delete/{id<[0-9]+>}", name="user_delete", methods={"DELETE"})
     */
    public function deleteUser(?User $user, Request $request, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid('user_deletion_' . $user->getId(), $request->request->get('csrf_token'))){
            $em->remove($user);
            $em->flush();
            
            $this->addFlash('success', sprintf('%s a bien été supprimé.', $user->getAlias()));
        }
        
        return $this->redirectToRoute('admin_user_index');
    }
}
