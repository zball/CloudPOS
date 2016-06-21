<?php

namespace CloudPOS\Bundle\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CartItem
 *
 * @ORM\Table(name="cart_item")
 * @ORM\Entity(repositoryClass="CloudPOS\Bundle\StoreBundle\Repository\CartItemRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CartItem implements Manageable
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
     * @var int
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $unitPrice;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Need a quantity for the Cart Item.")
     */
    private $quantity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cart", inversedBy="cartItems")
     */
    private $cart;
    
    /**
     * @ORM\ManyToOne(targetEntity="Product")
     */
    private $product;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }

    /** @ORM\PrePersist */
    public function updatedAt()
    {
        $this->setUpdatedAt(new \DateTime('now'));
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set unitPrice
     *
     * @param integer $unitPrice
     *
     * @return CartItem
     */
    public function setUnitPrice($unitPrice)
    {
        if(!is_int($unitPrice)){
            throw new \InvalidArgumentException('Unit Price must be an integer. Provided: ' . $unitPrice);
        }
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return int
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Set cart
     *
     * @param \CloudPOS\Bundle\StoreBundle\Entity\Cart $cart
     *
     * @return CartItem
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
     * Set product
     *
     * @param \CloudPOS\Bundle\StoreBundle\Entity\Product $product
     *
     * @return CartItem
     */
    public function setProduct(\CloudPOS\Bundle\StoreBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \CloudPOS\Bundle\StoreBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return CartItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CartItem
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return CartItem
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
