<?php

namespace CloudPOS\Bundle\StoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cart
 *
 * @ORM\Table(name="cart")
 * @ORM\Entity(repositoryClass="CloudPOS\Bundle\StoreBundle\Repository\CartRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Cart implements Manageable
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
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiresAt", type="datetime")
     */
    private $expiresAt;

    /**
     * @var int
     *
     * @ORM\Column(name="cartTotal", type="integer")
     */
    private $cartTotal = 0;

    /**
     * @ORM\Column(type="text")
     * @Assert\Choice(choices = {"open", "closed"}, message = "Choose a valid status.")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="CartItem", mappedBy="cart", cascade={"PERSIST"}, fetch="EAGER")
     */
    private $cartItems;

    /**
     * @ORM\ManyToOne(targetEntity="CloudPOS\Bundle\UserBundle\Entity\User", inversedBy="cart")
     */
    private $user;

    public function __construct()
    {
        $datetime = new \DateTime('now');

        $this->createdAt = $datetime;
        $this->expiresAt = $datetime->modify("+30 days");
        $this->cartItems = new ArrayCollection();

        $this->setStatus('open');
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Cart
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
     * Set cartTotal
     *
     * @param integer $cartTotal
     *
     * @return Cart
     */
    public function setCartTotal($cartTotal)
    {
        if (!is_int($cartTotal)) {
            throw new \InvalidArgumentException('Cart Total must be an integer. Provided: ' . $cartTotal);
        }

        $this->cartTotal = $cartTotal;

        return $this;
    }

    /**
     * Get cartTotal
     *
     * @return integer
     */
    public function getCartTotal()
    {
        return $this->cartTotal;
    }

    /**
     * Set expiresAt
     *
     * @param \DateTime $expiresAt
     *
     * @return Cart
     */
    public function setExpiresAt($expiresAt)
    {
        if (!$expiresAt instanceof \DateTime)
            throw new \InvalidArgumentException('ExpiresAt must be of type: DateTime.');

        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt
     *
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Add cartItem
     *
     * @param \CloudPOS\Bundle\StoreBundle\Entity\CartItem $cartItem
     *
     * @return Cart
     */
    public function addCartItem(\CloudPOS\Bundle\StoreBundle\Entity\CartItem $cartItem)
    {
        $this->cartItems[] = $cartItem;

        return $this;
    }

    /**
     * Remove cartItem
     *
     * @param \CloudPOS\Bundle\StoreBundle\Entity\CartItem $cartItem
     */
    public function removeCartItem(\CloudPOS\Bundle\StoreBundle\Entity\CartItem $cartItem)
    {
        $this->cartItems->removeElement($cartItem);
    }

    /**
     * Get cartItems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCartItems()
    {
        return $this->cartItems;
    }

    /**
     * Set user
     *
     * @param \CloudPOS\Bundle\UserBundle\Entity\User $user
     *
     * @return Cart
     */
    public function setUser(\CloudPOS\Bundle\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \CloudPOS\Bundle\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Cart
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Cart
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
