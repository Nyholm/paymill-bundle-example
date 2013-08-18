<?php

namespace Fm\PaymillExampleBundle\Entity;

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
    private $total_price;

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
     * Set total_price
     *
     * @param float $totalPrice
     * @return Order
     */
    public function setTotalPrice($totalPrice)
    {
        $this->total_price = $totalPrice;
        return $this;
    }

    /**
     * Get total_price
     *
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->total_price;
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