<?php

namespace App\Controller;

use DateTime;
use App\Entity\Command;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommandFormType; 
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class CartController extends AbstractController
{

    /**
     * @Route("/cart", name="cart")
     */
    public function index( ProductRepository $productRepository, SessionInterface $session, Request $request, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $products = $productRepository->findAll();
        $cart = $session->get('panier', []);

        $command = new Command();
        $commandForm = $this->createForm(CommandFormType::class, $command);

        $commandForm->handleRequest($request);

        if ($commandForm->isSubmitted()) {
            $command->setCreatedAt(new DateTime());
            foreach ($cart as $key=>$productCart)
                $command->addProduct($products[$key]);
            $em->persist($command);
            $em->flush();
        }

        return $this->render('cart/index.html.twig', [
            'cart'        => $cart,
            'products'    => $products,
            'commandForm' => $commandForm->createView() 
    ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cartAdd")
     */
    public function add($id, ProductRepository $productRepository, SessionInterface $session)
    {
        if($productRepository->find($id)) {
            $products = $productRepository->findAll();
            $cart = $session->get('panier', []);
            $cart[$id] = 1;
            $session->set('panier', $cart);

            $command = new Command();
            $commandForm = $this->createForm(CommandFormType::class, $command);
            //ajouter alerte
            return $this->render('cart/index.html.twig', [
                'cart'        => $cart,
                'products'    => $products,
                'commandForm' => $commandForm->createView() 
            ]);
        } else {
            throw $this->createNotFoundException('The product does not exist');
        }
    }

    /**
     * @Route("/cart/delete/{id}", name="cartDelete")
     */
    public function delete($id, ProductRepository $productRepository, SessionInterface $session)
    {
        $cart = $session->get('panier', []);
        if(!empty($cart[$id])) {
            $products = $productRepository->findAll();
            unset($cart[$id]);
            $session->set('panier', $cart);

            $command = new Command();
            $commandForm = $this->createForm(CommandFormType::class, $command);
            //ajouter message de suppression réussi
            echo 'Produit "'.$products[$id]->getName().'" supprimé.';
            return $this->render('cart/index.html.twig', [
                'cart'        => $cart,
                'products'    => $products,
                'commandForm' => $commandForm->createView() 
            ]);
        } else {
            throw $this->createNotFoundException('The product does not exist in ths cart');
        }
    }

}
