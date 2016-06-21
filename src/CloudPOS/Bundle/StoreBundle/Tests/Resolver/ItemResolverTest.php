<?php 
namespace CloudPOS\Bundle\StoreBundle\Tests\Resolver;

use CloudPOS\Bundle\StoreBundle\Resolver\ItemResolver;
use CloudPOS\Bundle\StoreBundle\Entity\Product;
use CloudPOS\Component\Validator\Validator;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;

class ItemResolverTest extends \PHPUnit_Framework_TestCase
{

    public function testResolvesCartItem()
    {

        $validator = $this->getMockBuilder(Validator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $validator->expects($this->once())
            ->method('validate')
            ->will($this->returnValue(true));


        $product = $this->getMock(Product::class);
        $product->expects($this->once())
            ->method('getUnitPrice')
            ->will($this->returnValue(1000));


        $productRepository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productRepository->expects($this->once())
            ->method('find')
            ->will($this->returnValue($product));

        $itemResolver = new ItemResolver($validator, $productRepository);

        $request = new Request([], [
            'quantity' => 3,
            'productId' => 1]);
        
        $cartItem = $itemResolver->resolveItem( $request );
        
        $this->assertInstanceOf( 'CloudPOS\Bundle\StoreBundle\Entity\CartItem', $cartItem );
        $this->assertEquals(3, $cartItem->getQuantity());
    }

    /**
    * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
    */
    public function test404()
    {
        $validator = $this->getMockBuilder(Validator::class)
            ->disableOriginalConstructor()
            ->getMock();


        $productRepository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productRepository->expects($this->once())
            ->method('find')
            ->will($this->returnValue(false));

        $itemResolver = new ItemResolver($validator, $productRepository);

        $request = new Request([], [
            'quantity' => 3,
            'productId' => 1]);

        $itemResolver->resolveItem($request);
    }
    
}