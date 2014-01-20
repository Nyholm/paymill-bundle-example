<?php

namespace Memeoirs\PaymillExampleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="orders")
 * @ORM\Entity
 */
class Order
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Associated PaymentIntruction instance
     * @var PaymentIntruction
     *
     * @ORM\OneToOne(targetEntity="JMS\Payment\CoreBundle\Entity\PaymentInstruction")
     * @ORM\JoinColumn(name="payment_instruction_id", referencedColumnName="id", nullable=true)
     */
    private $paymentInstruction;

    /**
     * @var string
     * Currency used for the transaction
     *
     * @ORM\Column(type="string", length=3, nullable=false)
     */
    private $currency;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=2, nullable=false)
     */
    private $amount;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Order
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set paymentInstruction
     *
     * @param \JMS\Payment\CoreBundle\Entity\PaymentInstruction $paymentInstruction
     * @return Order
     */
    public function setPaymentInstruction(\JMS\Payment\CoreBundle\Entity\PaymentInstruction $paymentInstruction)
    {
        $this->paymentInstruction = $paymentInstruction;
        return $this;
    }

    /**
     * Get paymentInstruction
     *
     * @return \JMS\Payment\CoreBundle\Entity\PaymentInstruction
     */
    public function getPaymentInstruction()
    {
        return $this->paymentInstruction;
    }

    /**
     * Set currency
     *
     * @param string $currency
     * @return Order
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}