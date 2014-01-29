<?php

namespace Memeoirs\PaymillExampleBundle\Controller;

use Memeoirs\PaymillExampleBundle\Entity\Order;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Payment\CoreBundle\PluginController\Result;

class OrdersController extends Controller
{
    /**
     * Render the credit card form and handle POSTed data.
     */
    public function checkoutAction ()
    {
        $em = $this->getDoctrine()->getManager();

        // In a real world app, instead of instantiating an Order, you will
        // probably retrieve it from the database
        $order = new Order;
        $order->setAmount(50);
        $order->setCurrency('EUR');

        $form = $this->get('form.factory')->create('jms_choose_payment_method', null, array(
            'allowed_methods' => array('paymill'),
            'default_method'  => 'paymill',
            'amount'          => $order->getAmount(),
            'currency'        => $order->getCurrency(),
            'predefined_data' => array(
                'paymill' => array(
                    'client' => array(
                        'email'       => 'user2@example.com',
                        'description' => 'John Doe',
                    ),
                    'description' => 'Two baskets of apples'
                ),
            ),
        ));

        if ('POST' === $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                // Create a PaymentInstruction and associate it with the order

                $ppc = $this->get('payment.plugin_controller');
                $ppc->createPaymentInstruction($instruction = $form->getData());

                $order->setPaymentInstruction($instruction);
                $em->persist($order);
                $em->flush($order);

                return $this->redirect($this->get('router')->generate('orders_complete', array(
                    'id' => $order->getId(),
                )));
            }
        }

        return $this->render('MemeoirsPaymillExampleBundle:Orders:checkout.html.twig', array(
            'order' => $order,
            'form'  => $form->createView(),
        ));
    }

    /**
     * Complete a payment by creating a transaction using Paymill's API, i.e.
     * call JMSPaymentCore's approveAndDeposit method.
     *
     * @param  integer $id Order id
     */
    public function completeAction ($id)
    {
        $order       = $this->getOrder($id);
        $instruction = $order->getPaymentInstruction();
        $ppc         = $this->get('payment.plugin_controller');

        if (null === $pendingTransaction = $instruction->getPendingTransaction()) {
            $amount = $instruction->getAmount() - $instruction->getDepositedAmount();
            $payment = $ppc->createPayment($instruction->getId(), $amount);
        } else {
            $payment = $pendingTransaction->getPayment();
        }

        $result = $ppc->approveAndDeposit($payment->getId(), $payment->getTargetAmount());
        if (Result::STATUS_SUCCESS === $result->getStatus()) {
            // payment was successful
            return $this->redirect($this->get('router')->generate('orders_thankyou', array(
                'id' => $order->getId(),
            )));
        } else {
            throw new \RuntimeException('Transaction was not successful: '.$result->getReasonCode());
        }
    }

    /**
     * Display the "Thank You" page.
     *
     * @param  integer $id Order id
     */
    public function thankYouAction ($id)
    {
        return $this->render('MemeoirsPaymillExampleBundle:Orders:thankyou.html.twig', array(
            'order' => $this->getOrder($id),
        ));
    }

    /**
     * Retrieve an Order from the database.
     *
     * @param  integer $id Order id
     * @return Order
     */
    private function getOrder ($id)
    {
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MemeoirsPaymillExampleBundle:Order');
        if (!$order = $repository->find($id)) {
            throw $this->createNotFoundException("Order $id not found");
        }

        return $order;
    }
}
