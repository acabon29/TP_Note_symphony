<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index(ProductRepository $productRepository, Request $request): Response
    {
        $products = $productRepository->findAll();
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products'        => $products,
        ]);
    }

    /**
     * @Route("/product/{id}", name="product.show")
     */
    public function show($id): Response
    {
        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        $product = $productRepository->find($id);
        if (!$product)
        {
            throw $this->createNotFoundException('The product does not exist');
        }
        return $this->render('product/show.html.twig', [
            'controller_name' => 'Guest',
            'product'        => $product,
        ]);
    }
}
