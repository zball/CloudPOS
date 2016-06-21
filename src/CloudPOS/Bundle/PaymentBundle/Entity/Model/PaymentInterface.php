<?php
namespace CloudPOS\Bundle\PaymentBundle\Entity\Model;

interface PaymentInterface
{
    public function setNumber($number);

    public function getNumber();

    public function setType($type);

    public function getType();

    public function setExpireMonth($month);

    public function getExpireMonth();

    public function setExpireYear($year);

    public function getExpireYear();

    public function setCvv2($cvv2);

    public function getCvv2();

    public function toArray();
}
