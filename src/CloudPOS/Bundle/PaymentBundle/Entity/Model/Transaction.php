<?php
namespace CloudPOS\Bundle\PaymentBundle\Entity\Model;

class Transaction implements TransactionInterface
{
    protected $amount;

    protected $currency;

    protected $details;

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     * @return Transaction
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param mixed $details
     * @return Transaction
     */
    public function setDetails($details)
    {
        $this->details = $details;
        return $this;
    }
}
