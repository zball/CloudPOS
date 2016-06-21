<?php
namespace CloudPOS\Bundle\PaymentBundle\Entity\Model;

class Payment implements PaymentInterface
{
    protected $number;

    protected $type;

    protected $expireMonth;

    protected $expireYear;

    protected $cvv2;

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     * @return Payment
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Payment
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getExpireMonth()
    {
        return $this->expireMonth;
    }

    /**
     * @param mixed $expireMonth
     * @return Payment
     */
    public function setExpireMonth($expireMonth)
    {
        $this->expireMonth = $expireMonth;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpireYear()
    {
        return $this->expireYear;
    }

    /**
     * @param mixed $expireYear
     * @return Payment
     */
    public function setExpireYear($expireYear)
    {
        $this->expireYear = $expireYear;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCvv2()
    {
        return $this->cvv2;
    }

    /**
     * @param mixed $cvv2
     * @return Payment
     */
    public function setCvv2($cvv2)
    {
        $this->cvv2 = $cvv2;
        return $this;
    }

    public function toArray()
    {
        $data = [];

        foreach ($this as $key => $value) {

            $data[ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $key)), '_')] = $value;
        }

        return [
            'credit_card' => $data
        ];
    }
}
