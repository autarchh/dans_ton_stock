<?php

namespace App\Controller;

use OpenFoodFacts\Api;
use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\StorageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/product", name="product_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/{id<[0-9]+>}", name="index")
     */
    public function index(User $user,ProductRepository $productRepository, StorageRepository $storageRepository): Response
    {
        if(!$storageRepository->findById($this->getUser()->getId())){
            $this->addFlash('notice', "Veuillez d'abord créer un lieu de stockage.");
            return $this->redirectToRoute('manage_index');
        }
        $products = $productRepository->findAllWithTotalDays($user->getId());
        $productOneWeek = [];
        $productOneMonth = [];
        $productTwoMonth = [];
        $productMore = [];
        $productExpired = [];
        $productNow = [];
        foreach ($products as $product) {
            if($product['restTime'] < 0) {
                $productExpired [] = $product;
            }
            if($product['restTime'] == 0) {
                $productNow [] = $product;
            }
            if($product['restTime'] < 7 && $product['restTime'] > 0) {
                $productOneWeek [] = $product;
            }
            if($product['restTime'] >= 7 && $product['restTime'] < 28 ) {
                $productOneMonth [] = $product;
            }  
            if($product['restTime'] >= 28 && $product['restTime'] < 56 ) {
                $productTwoMonth [] = $product;
            }  
            if($product['restTime'] >= 56 ) {
                $productMore [] = $product;
            }  
        }
        // dd($productsMore);
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'productsOneWeek' => $productOneWeek,
            'productsOneMonth' => $productOneMonth,
            'productsTwoMonth' => $productTwoMonth,
            'productsMore' => $productMore,
            'productsExpired' => $productExpired,
            'productsNow' => $productNow,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // On récupère le code barre s'il existe
        $ean = intval($request->query->get('ean'));
        // On créé un objet Product
        $product = new Product();
        // S'il y a un code barre on passe par l'API pour récupérer les infos
        if ($ean) {
            // On créé une instance du produit récupéré avec l'API
            $prodAPI = new Api('food','fr'); 
            // On récupère les données et on les assigne à l'objet. 
            $product = new Product($prodAPI->getProduct($ean)->getData());

        }
            // Création du formulaire soit vide, soit prérempli avec les données de l'API
            $form = $this->createForm(ProductType::class, $product, [
                'action' => $this->generateUrl('product_new'),
                'id' => $this->getUser()->getId()
            ]);
            
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($product);
                $entityManager->flush();
                
                $this->addFlash('success', sprintf('%s a bien été ajouté à %s', $product->getName(), $product->getStorage()));
                return $this->redirectToRoute('product_index', [
                    'id' => $this->getUser()->getId()
                ]);
            }
            
        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

     /**
     * @Route("/show/{id<[0-9]+>}", name="show", methods={"GET"})
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
            ]),
            'id' => $this->getUser()->getId()
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
     * @Route("/delete/{id<[0-9]+>}", name="delete", methods={"DELETE"})
     */
    public function delete(?Product $product,Request $request, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid('product_deletion_' . $product->getId(), $request->request->get('csrf_token'))){
            $em->remove($product);
            $em->flush();

            $this->addFlash('success', sprintf('%s a bien été supprimé de %s.', $product->getName(), $product->getStorage()));
        }

        return $this->redirectToRoute('product_index', [
            'id' => $this->getUser()->getId()
        ] );
    }
}

   
