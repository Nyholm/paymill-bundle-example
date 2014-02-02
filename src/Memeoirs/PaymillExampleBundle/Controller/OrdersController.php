<?php

namespace Memeoirs\PaymillExampleBundle\Controller;

use Memeoirs\PaymillExampleBundle\Entity\Order;
use Memeoirs\PaymillBundle\Controller\PaymillController;

class OrdersController extends PaymillController
{
    /**
     * GET: Render the credit card form
     * POST: Create a Transaction with Paymill
     */
    public function checkoutAction ()
    {
        $em = $this->getDoctrine()->getManager();

        // In a real world app, instead of instantiating an Order, you will
        // probably retrieve it from the database
        $order = new Order;
        $order->setAmount(50);
        $order->setCurrency('EUR');

        $form = $this->getPaymillForm($order->getAmount(), $order->getCurrency(), array(
            'client' => array(
                'email' => 'user2@example.com',
                'description' => 'John Doe',
            ),
            'description' => 'Two baskets of apples'
        ));

        if ('POST' === $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $instruction = $this->createPaymentInstruction($form);
                $order->setPaymentInstruction($instruction);
                $em->persist($order);
                $em->flush($order);

                return $this->completePayment($instruction, 'orders_thankyou', array(
                    'id' => $order->getId()
                ));
            }
        }

        return $this->render('MemeoirsPaymillExampleBundle:Orders:checkout.html.twig', array(
            'order' => $order,
            'form'  => $form->createView(),
        ));
    }

    /**
     * Display the "Thank You" page.
     *
     * @param integer $id Order id
     */
    public function thankYouAction ($id)
    {
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MemeoirsPaymillExampleBundle:Order');
        if (!$order = $repository->find($id)) {
            throw $this->createNotFoundException("Order $id not found");
        }

        return $this->render('MemeoirsPaymillExampleBundle:Orders:thankyou.html.twig', array(
            'order' => $order,
        ));
    }
}
