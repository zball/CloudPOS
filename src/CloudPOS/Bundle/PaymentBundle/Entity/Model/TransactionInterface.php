<?php
namespace CloudPOS\Bundle\PaymentBundle\Entity\Model;

interface TransactionInterface
{
    public function setAmount($amount);

    public function getAmount();

    public function setCurrency($currency);

    public function getCurrency();

    public function setDetails($details);

    public function getDetails();
}
