<?php

namespace CloudPOS\Bundle\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Invoice
 *
 * @ORM\Table(name="invoice")
 * @ORM\Entity()
 */
class Invoice
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text")
     * @Assert\Choice(choices = {"processing", "closed"}, message = "Choose a valid status.")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="\CloudPOS\Bundle\UserBundle\Entity\User", inversedBy="users")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Address", inversedBy="address")
     */
    private $address;

    /**
     * @ORM\OneToOne(targetEntity="Cart")
     */
    private $cart;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Invoice
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Invoice
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     * @return Invoice
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Set cart
     *
     * @param \CloudPOS\Bundle\StoreBundle\Entity\Cart $cart
     *
     * @return Invoice
     */
    public function setCart(\CloudPOS\Bundle\StoreBundle\Entity\Cart $cart = null)
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * Get cart
     *
     * @return \CloudPOS\Bundle\StoreBundle\Entity\Cart
     */
    public function getCart()
    {
        return $this->cart;
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
     * @return Invoice
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
}
