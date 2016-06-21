<?php
/**
 * Created by PhpStorm.
 * User: zacball
 * Date: 1/21/16
 * Time: 12:26 AM
 */

namespace CloudPOS\Bundle\StoreBundle\Tests\Manager;

use CloudPOS\Bundle\StoreBundle\Entity\Cart;
use CloudPOS\Bundle\StoreBundle\Manager\ProductManager;
use CloudPOS\Component\Testing\TestCase;
use Doctrine\ORM\EntityManager;
use Mockery;
use Symfony\Component\HttpFoundation\Request;
use CloudPOS\Component\Validator\Validator;

class ProductManagerTest extends TestCase {

    public function testCreateProduct()
    {
        $data = [
            'name' => 'foo',
            'unit_price' => '999'
        ];

        $request = new Request([], $data);

        $validator = Mockery::mock(Validator::class);
        $validator->shouldReceive('validate')->once()->andReturn(true);

        $em = Mockery::mock( EntityManager::class );

        $productManager = new ProductManager( $em, $validator );
        $product = $productManager->create( $request );

        $this->assertInstanceOf( 'CloudPOS\Bundle\StoreBundle\Entity\Product', $product );
        $this->assertEquals( 'foo', $product->getName() );
        $this->assertEquals( 999, $product->getUnitPrice() );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testThrowsExceptionOnUpdateWithWithWrongEntity()
    {
        $data = [
            'name' => 'foo',
            'unit_price' => '999'
        ];

        $request = new Request([], $data);

        $validator = Mockery::mock(Validator::class);
        $validator->shouldReceive('validate')->once()->andReturn(true);

        $em = Mockery::mock( EntityManager::class );

        $productManager = new ProductManager( $em, $validator );
        $productManager->update($request, new Cart() );
    }
}
