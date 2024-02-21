<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;

class PaymentController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/order/create-session-stripe/{reference}', name: 'payment_stripe')]
    public function stripeCheckout($reference): RedirectResponse
    {

        $order = $this->em->getRepository(Order::class)->findOneBy(['reference' => $reference]);
        dd($order);


        // Stripe::setApiKey('sk_test_51O2CKAJQTr0siZTbssjD7ULRqp7MQzn48PotY72uzOKtMJi1CmizxxdLzS8O4UXAdBjjZbrH2FEQgRk2wpDHtJzU00Oy6wsqK9');

        // $checkout_session = \Stripe\Checkout\Session::create([
        //     'line_items' => [[
        //         # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
        //         'price' => '{{PRICE_ID}}',
        //         'quantity' => 1,
        //     ]],
        //     'mode' => 'payment',
        //     'success_url' => $YOUR_DOMAIN . '/success.html',
        //     'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        // ]);
    }
}
