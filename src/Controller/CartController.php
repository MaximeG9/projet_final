<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $panier = $session->get('panier', []);

        //On initialise des variables
        $data = [];
        $total = 0;
        // $session->set('panier', []);

        foreach($panier as $id => $quantity){
            $product = $productRepository->find($id);

            $data[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity; 
        }

        return $this->render('cart/index.html.twig', [
            'data' => $data,
            'total' => $total
        ]);
    }


    #[Route('/add/{id}', name: 'add')]
    public function add(Product $product, SessionInterface $session): Response
    {
        // Récupérer l'id du produit
        $id = $product->getId();

        //On récupère le panier existant
        $panier = $session->get('panier', []);

        //On ajoute le produit dans le panier s'il n'y est pas encore
        //Sinon on incrémente sa quantité
        if (empty($panier[$id])) {
            $panier[$id] = 1;
        } else {
            $panier[$id] ++;
        }

        $session->set('panier', $panier);

        //On redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }


    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Product $product, SessionInterface $session): Response
    {
        // Récupérer l'id du produit
        $id = $product->getId();

        //On récupère le panier existant
        $panier = $session->get('panier', []);

        //On ajoute le produit du panier s'il n'y a qu'un exemplaire
        //Sinon on décrémente sa quantité
        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }

        $session->set('panier', $panier);

        //On redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }


    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Product $product, SessionInterface $session): Response
    {
        // Récupérer l'id du produit
        $id = $product->getId();

        //On récupère le panier existant
        $panier = $session->get('panier', []);

        //On ajoute le produit du panier s'il n'y a qu'un exemplaire
        //Sinon on décrémente sa quantité
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        //On redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }


    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session): Response
    {
        $session->remove('panier');

        return $this->redirectToRoute('cart_index');
    }
}
