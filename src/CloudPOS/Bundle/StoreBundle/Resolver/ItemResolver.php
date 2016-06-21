<?php

namespace CloudPOS\Bundle\StoreBundle\Resolver;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CloudPOS\Bundle\StoreBundle\Entity\CartItem;
use CloudPOS\Component\Validator\Validator;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class ItemResolver
{
    private $validator;
    private $productRepository;
    
    public function __construct(Validator $validator, EntityRepository $productRepository)
    {
        $this->validator = $validator;
        $this->productRepository = $productRepository;
    }
    
    
    public function resolveItem(Request $request)
    {
        if( !$product = $this->productRepository->find($request->get('productId')) )
            throw new NotFoundHttpException('No Product by that ID could be located.');

        $cartItem = ( new CartItem )
            ->setProduct( $product )
            ->setUnitPrice( $product->getUnitPrice() )
            ->setQuantity($request->get('quantity', 1) );

        if(true !== $errors = $this->validator->validate($cartItem)) {
            return $errors;
        }
        
        return $cartItem;
    }
    
}