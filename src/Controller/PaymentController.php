<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{

    #[Route('/order/create-session-stripe', name: 'payment_stripe')]
    public function stripeCheckout(SessionInterface $sessionInterface, UrlGeneratorInterface $generator): Response
    {

        $productStripe = [];

        /**
         * @var Order $order
         */
        $order = $sessionInterface->get('order');
        
        if (!$order) {
            return $this->redirectToRoute('cart_index');
        }

        foreach ($order->getOrderDetails()->getValues() as $orderDetails) {
            
            $productData = $orderDetails->getProducts();
           
            
            $productStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $productData->getPrice(),
                    'product_data' => [
                        'name' => $productData->getName(),
                    ]
                ],
                'quantity' => $orderDetails->getQuantity(),
            ];
        }

        $secretKey = 'sk_test_51O2CKAJQTr0siZTbssjD7ULRqp7MQzn48PotY72uzOKtMJi1CmizxxdLzS8O4UXAdBjjZbrH2FEQgRk2wpDHtJzU00Oy6wsqK9';
        Stripe::setApiKey($secretKey);

        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $productStripe,
            'mode' => 'payment',
            'success_url' => $generator->generate('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $generator->generate('payment_error', [], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);

        return new RedirectResponse($checkout_session->url);
    }


    #[Route('/order/success', name: 'payment_success')]
    public function stripeSuccess(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $order = $session->get('order');

        // On persiste et on flush
        $entityManager->persist($order);
        $entityManager->flush();

        $session->remove('panier');

        return $this->render('order/success.html.twig');
    }


    #[Route('/order/error', name: 'payment_error')]
    public function stripeError(): Response
    {
        return $this->render('order/error.html.twig');
    }
}
