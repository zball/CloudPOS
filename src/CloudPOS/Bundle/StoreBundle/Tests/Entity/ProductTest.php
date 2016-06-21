<?php 
namespace CloudPOS\Bundle\StoreBundle\Tests\Entity;

use CloudPOS\Bundle\StoreBundle\Entity\Product;
use Symfony\Component\Validator\Validation;

class ProductTest extends \PHPUnit_Framework_TestCase 
{
    
    public function testCanSetProperties()
    {
        $product = (new Product)
                        ->setName('Widget')
                        ->setUnitPrice(999);
                        
        $this->assertInstanceOf('CloudPOS\Bundle\StoreBundle\Entity\Product', $product);
        $this->assertEquals('Widget', $product->getName());
        $this->assertEquals(999, $product->getUnitPrice());
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testExceptionThrownOnNonIntegerForUnitPrice()
    {
        $cartItem = new Product;
        $cartItem->setUnitPrice(5.50);
    }
}