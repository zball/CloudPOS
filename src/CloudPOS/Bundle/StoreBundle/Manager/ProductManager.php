<?php

namespace CloudPOS\Bundle\StoreBundle\Manager;

use CloudPOS\Bundle\StoreBundle\Entity\Manageable;
use CloudPOS\Bundle\StoreBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductManager
 * @package CloudPOS\Bundle\StoreBundle\Manager
 */
class ProductManager extends BaseManager
{

    /**
     * @param Request $request
     * @return Product
     */
    public function create( Request $request )
    {
        return $this->build($request, new Product );
    }

    /**
     * @param Request $request
     * @param Manageable $product
     *
     * @return bool|Product
     * @throws \Exception
     */
    public function update(Request $request, Manageable $product )
    {
        if(!$product instanceof Product)
            throw new \InvalidArgumentException;

        return $this->build($request, $product);
    }

    /**
     * @param Request $request
     * @param Product $product
     *
     * @return bool|Product
     * @throws \Exception
     */
    public function build(Request $request, Product $product)
    {
        $product
            ->setName( $request->get('name') )
            ->setUnitPrice( (int)$request->get('unit_price') );

        return $this->isValid($product) ? $product : $this->getErrors();
    }
}
