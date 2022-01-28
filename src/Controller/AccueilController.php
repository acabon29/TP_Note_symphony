<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        function sortByPrice ($tab) {
            $tabCopy = array_slice($tab, 0);
            for ($key = 0 ;$key < count($tabCopy); $key++) {
                for ($i = $key; $i < count($tabCopy); $i++) {
                    if ($tabCopy[$i]->getPrice() < $tabCopy[$key]->getPrice()) {
                        $temp = $tabCopy[$key];
                        $tabCopy[$key] = $tabCopy[$i];
                        $tabCopy[$i] = $temp;
                    }
                }
            }
            return $tabCopy;
        }
        function sortByDate ($tab) {
            $tabCopy = array_slice($tab, 0);
            for ($key = 0 ;$key < count($tabCopy); $key++) {
                for ($i = $key; $i < count($tabCopy); $i++) {
                    if ($tabCopy[$i]->getCreatedAt() < $tabCopy[$key]->getCreatedAt()) {
                        $temp = $tabCopy[$key];
                        $tabCopy[$key] = $tabCopy[$i];
                        $tabCopy[$i] = $temp;
                    }
                }
            }
            return $tabCopy;
        }

        // function sortPrice ($a, $b) {
        //     return $a->price - $b->price;
        // }

        return $this->render('accueil/index.html.twig', [
            'productsDate'    => array_slice(sortByDate($products), 0, 5),
            'productsPrice'   => array_slice(sortByPrice($products), 0, 5)
        ]);
        // return $this->render('accueil/index.html.twig', [
        //     'productsDate'    => array_slice(uasort($products, 'sortPrice'), 0, 5),
        //     'productsPrice'    => array_slice(uasort($products, 'sortPrice'), 0, 5)
        // ]);
    }
}






// <td>
//                 {% for p in productsDate %}
//                     <tr>
//                         <td><a href="{{ path('product.show', {id : p.id}) }}">{{p.name}}</a></td>
//                         <td>{{p.createdAt|date('d-m-Y')}}</td>
//                     </tr> 
//                 {% endfor %}
//             </td>
//             <td>
//                 {% for p in productsPrice %}
//                     <tr>
//                         <td><a href="{{ path('product.show', {id : p.id}) }}">{{p.name}}</a></td>
//                         <td>{{p.price}}€</td>
//                     </tr> 
//                 {% endfor %}
//             </td>