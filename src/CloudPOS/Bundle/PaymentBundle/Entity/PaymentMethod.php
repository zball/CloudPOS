<?php
namespace CloudPOS\Bundle\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="payment_method")
 * @ORM\Entity
 */
class PaymentMethod
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $gateway;

    /**
     * @ORM\Column(type="string")
     */
    protected $reference;

    /**
     * @ORM\ManyToOne(targetEntity="CloudPOS\Bundle\UserBundle\Entity\User")
     */
    protected $user;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $default;

    /**
     * @return mixed
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param mixed $gateway
     * @return PaymentMethod
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return PaymentMethod
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     * @return PaymentMethod
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return PaymentMethod
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     * @return PaymentMethod
     */
    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }
}
