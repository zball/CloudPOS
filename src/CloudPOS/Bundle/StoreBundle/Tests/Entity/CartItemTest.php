<?php 
namespace CloudPOS\Bundle\StoreBundle\Tests\Entity;

use CloudPOS\Bundle\StoreBundle\Entity\CartItem;
use CloudPOS\Bundle\StoreBundle\Entity\Cart;
use CloudPOS\Bundle\StoreBundle\Entity\Product;

class CartItemTest extends \PHPUnit_Framework_TestCase 
{
    
    public function testCanSetProperties()
    {
        $cartItem = (new CartItem)
                        ->setUnitPrice( 999 )
                        ->setCart( new Cart )
                        ->setProduct( new Product );
                        
        $this->assertInstanceOf( 'CloudPOS\Bundle\StoreBundle\Entity\CartItem', $cartItem );
        $this->assertEquals( 999, $cartItem->getUnitPrice() );
        $this->assertInstanceOf( 'CloudPOS\Bundle\StoreBundle\Entity\Cart', $cartItem->getCart() );
        $this->assertInstanceOf( 'CloudPOS\Bundle\StoreBundle\Entity\Product', $cartItem->getProduct() );
        $this->assertInstanceOf( 'DateTime', $cartItem->getCreatedAt() );
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testExceptionThrownOnNonIntegerForUnitPrice()
    {
        $cartItem = new CartItem;
        $cartItem->setUnitPrice(5.50);
    }
}