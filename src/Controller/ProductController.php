<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product", name="product_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', sprintf('%s a bien été ajouté à votre stock', $product->getName()));
            return $this->redirectToRoute('product_index');
        }

        return $this->renderForm('product/new.html.twig', [
            'form' => $form
        ]);
    }

     /**
     * @Route("/{id<[0-9]+>}", name="show", methods={"GET"})
     */
    public function show(?Product $product): Response
    {
        return $this->render('product/show.html.twig', compact('product'));
    }

     /**
     * @Route("/edit/{id<[0-9]+>}", name="edit", methods={"GET", "PUT"})
     */
    public function edit(?Product $product, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProductType::class, $product, [
            'method' => 'PUT',
            'action' => $this->generateUrl('product_edit', [
                'id' => $product->getId()
                ])
            ]);
            
            $form->handleRequest($request);
            
            if($form->isSubmitted() && $form->isValid()) {
                $em->flush();
                
                $this->addFlash('success', sprintf('%s a bien été modifié.', $product->getName()));
                return $this->redirectToRoute('product_show', [
                    'id' => $product->getId()
                ]);
            }
            
            return $this->renderForm('product/edit.html.twig', [
                'product' => $product,
                'form' => $form
            ]);
    }

    /**
     * @Route("/{id<[0-9]+>}", name="delete", methods={"DELETE"})
     */
    public function delete(?Product $product,Request $request, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid('product_deletion_' . $product->getId(), $request->request->get('csrf_token'))){
            $em->remove($product);
            $em->flush();

            $this->addFlash('success', sprintf('%s a bien été supprimé.', $product->getName()));
        }

        return $this->redirectToRoute('product_index');
    }
}

   
