<?php 
namespace CloudPOS\Bundle\StoreBundle\Tests\Manager;

use CloudPOS\Bundle\StoreBundle\Entity\Cart;
use CloudPOS\Bundle\StoreBundle\Entity\CartItem;
use CloudPOS\Bundle\StoreBundle\Entity\Product;
use CloudPOS\Bundle\StoreBundle\Manager\CartManager;
use CloudPOS\Component\Testing\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Mockery;
use CloudPOS\Component\Validator\Validator;
use Doctrine\ORM\EntityManager;

class CartManagerTest extends KernelTestCase
{
    private $cart;
    private $cartManager;
    
    public function setUp()
    {
        $this->cart = new Cart;

        $validator = Mockery::mock(Validator::class);
        $validator->shouldReceive('validate')->once()->andReturn(true);

        self::bootKernel();

        $em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->cartManager = new CartManager( $em, $validator );
        
        $this->cartManager->setCart( $this->cart );
    }
    
    public function testSetCart()
    {
        $this->assertInstanceOf( 'CloudPOS\Bundle\StoreBundle\Entity\Cart', $this->cartManager->getCart() );
        
    }
    
    public function testAddItem()
    {
        $item = ( new CartItem )->setUnitPrice( 1000 )->setQuantity(1);
            
        $this->cartManager->addItem( $item );
        
        $this->assertCount( 1, $this->cart->getCartItems() );
        $this->assertEquals( 1000, $this->cart->getCartTotal() );
    }

    public function testAddMultipleItems()
    {
        $product = ( new Product )->setName( 'Foo' )->setUnitPrice( 500 );
        $product2 = ( new Product )->setName( 'Bar' )->setUnitPrice( 100 );

        $item = ( new CartItem )->setUnitPrice( $product->getUnitPrice() )->setProduct($product)->setQuantity(1);
        $item2 = ( new CartItem )->setUnitPrice( $product2->getUnitPrice() )->setProduct($product2)->setQuantity(2);

        $this->cartManager->addItem( $item );
        $this->cartManager->addItem( $item2 );

        $this->assertCount( 2, $this->cart->getCartItems() );
        $this->assertEquals( 700, $this->cart->getCartTotal() );
    }

    public function testCartItemUpdatedOnDuplicateProduct()
    {
        $product = ( new Product )->setName( 'Foo' )->setUnitPrice( 500 );
        $item = ( new CartItem )->setUnitPrice( $product->getUnitPrice() )->setProduct($product)->setQuantity(1);
        $item2 = ( new CartItem )->setUnitPrice( $product->getUnitPrice() )->setProduct($product)->setQuantity(2);

        $this->cartManager->addItem( $item );
        $this->cartManager->addItem( $item2 );

        $this->assertCount( 1, $this->cart->getCartItems() );
        $this->assertEquals(3, $this->cart->getCartItems()[0]->getQuantity());
        $this->assertEquals(1500, $this->cart->getCartTotal());
    }
    
    public function testRemoveItem()
    {
        $product = ( new Product )->setName( 'Foo' )->setUnitPrice( 500 );
        $product2 = ( new Product )->setName( 'Bar' )->setUnitPrice( 100 );

        $item = ( new CartItem )->setUnitPrice( $product->getUnitPrice() )->setProduct($product)->setQuantity(1);
        $item2 = ( new CartItem )->setUnitPrice( $product2->getUnitPrice() )->setProduct($product2)->setQuantity(2);
        
        $this->cartManager->addItem( $item );
        $this->cartManager->addItem( $item2 );

        $this->cartManager->removeItem( $item );

        $this->assertCount( 1, $this->cart->getCartItems() );
        $this->assertEquals( 200, $this->cart->getCartTotal() );
    }
    
    public function testEmptyCart()
    {
        $product = new Product();
        $product2 = new Product();

        $item = ( new CartItem )->setUnitPrice( 1000 )->setProduct( $product );
        $item2 = ( new CartItem )->setUnitPrice( 500 )->setProduct( $product2 );
        
        $this->cartManager->addItem( $item );
        $this->cartManager->addItem( $item2 );
        
        $this->cartManager->emptyCart();
        
        $this->assertCount( 0, $this->cart->getCartItems() );
        
    }

    public function testCreateCart()
    {
        $cart = $this->cartManager->create( new Request );

        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertGreaterThanOrEqual(0, $cart->getCartTotal());
    }
}