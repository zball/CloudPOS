<?php

namespace CloudPOS\Bundle\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="CloudPOS\Bundle\StoreBundle\Repository\ProductRepository")
 */
class Product implements Manageable
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
     * @ORM\Column(type="text")
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="product")
     */
    protected $menu;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $unitPrice;

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
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set unitPrice
     *
     * @param integer $unitPrice
     *
     * @return Product
     */
    public function setUnitPrice($unitPrice)
    {
        if (!is_int($unitPrice)) {
            throw new \InvalidArgumentException('Unit Price must be an integer. Provided: ' . $unitPrice);
        }
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return integer
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }
}
