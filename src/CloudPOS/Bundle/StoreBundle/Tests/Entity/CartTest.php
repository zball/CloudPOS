<?php 
namespace CloudPOS\Bundle\StoreBundle\Tests\Entity;

use CloudPOS\Bundle\StoreBundle\Entity\Cart;
use CloudPOS\Bundle\StoreBundle\Entity\CartItem;
use CloudPOS\Bundle\UserBundle\Entity\User;

class CartTest extends \PHPUnit_Framework_TestCase 
{
    private $cart;
    
    public function setUp(){
        $this->cart = new Cart();
    }
    
    public function testCartDefaults()
    {
        $this->assertInstanceOf('DateTime', $this->cart->getCreatedAt());
        $this->assertInstanceOf('DateTime', $this->cart->getExpiresAt());
        $this->assertEquals(0, $this->cart->getCartTotal());
        $this->assertEquals($this->cart->getCreatedAt()->modify("+30 days"), $this->cart->getExpiresAt());
        $this->assertEquals('open', $this->cart->getStatus());
     }
     
     public function testAllPropertiesCanBeFilled()
     {
         $createdAt = new \DateTime('now');
         
         $this->cart
            ->setCreatedAt($createdAt)
            ->setCartTotal(1050)
            ->addCartItem( new CartItem )
            ->setUser( new User );
            
        $this->assertEquals($createdAt, $this->cart->getCreatedAt());
        $this->assertInstanceOf('DateTime', $this->cart->getCreatedAt()); 
        $this->assertEquals(1050, $this->cart->getCartTotal());
        $this->assertInstanceOf('CloudPOS\Bundle\StoreBundle\Entity\CartItem', $this->cart->getCartItems()[0]);
        $this->assertInstanceOf('CloudPOS\Bundle\UserBundle\Entity\User', $this->cart->getUser());
     }
     
     public function testCanRemoveCartItem(){
         $cartItem = new CartItem;
         $this->cart->addCartItem( $cartItem );
         $this->cart->removeCartItem($cartItem);
         
         $this->assertCount(0, $this->cart->getCartItems());
     }
     
     /**
     * @expectedException InvalidArgumentException
     */
    public function testExceptionThrownOnNonIntegerForCartTotal()
    {
        $this->cart->setCartTotal(5.50);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testExceptionThrownOnNonDateTimeForExpiresAt()
    {
        $this->cart->setExpiresAt('12/12/12');
    }
}