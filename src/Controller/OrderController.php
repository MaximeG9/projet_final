<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commandes', name: 'order_')]
class OrderController extends AbstractController
{
    #[Route('/ajout', name: 'add')]
    public function add(SessionInterface $session, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $panier = $session->get('panier', []);

        if ($panier === []) {
            $this->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('app_home');
        }

        // Le panier n'est pas vide, on crée la commande
        $order = new Order();

        // On remplit la commande
        $order->setReference(uniqid());

        // On parcourt le panier pour créer les détails de commande
        foreach($panier as $item => $quantity) {
            $orderDetails = new OrderDetails();

            // On va chercher le produit
            $product = $productRepository->find($item);
            
            $price = $product->getPrice();

            // On crée le detail de commande
            $orderDetails->setProducts($product);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            $order->addOrderDetail($orderDetails);
        }

        // ON persiste et on flush
        $entityManager->persist($order);
        $entityManager->flush();

        $session->remove('panier');

        $this->addFlash('message', 'Votre commande a été validée');
        return $this->redirectToRoute('app_home');
    }
}
