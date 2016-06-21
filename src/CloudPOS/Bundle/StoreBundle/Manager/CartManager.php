<?php

namespace CloudPOS\Bundle\StoreBundle\Manager;

use CloudPOS\Bundle\StoreBundle\Entity\Cart;
use CloudPOS\Bundle\StoreBundle\Entity\CartItem;
use CloudPOS\Bundle\StoreBundle\Entity\Manageable;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CartManager
 * @package CloudPOS\Bundle\StoreBundle\Manager
 */
class CartManager extends BaseManager{

    /**
     * @var
     */
    private $cart;

    /**
     * @param Cart $cart
     *
     * @return CartManager
     */
    public function setCart(Cart $cart )
    {
        $this->cart = $cart;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param Request $request
     * @param Manageable $manageable
     */
    public function update(Request $request, Manageable $manageable)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param Request $request
     * @return Cart
     */
    public function create(Request $request )
    {
        $cart = new Cart;
        return $this->isValid($cart) ? $cart : $this->getErrors();
    }

    /**
     * @param CartItem $cartItem
     *
     * @return CartManager
     */
    public function addItem(CartItem $cartItem )
    {

        if($duplicate = $this->findDuplicateProduct($cartItem->getProduct())){
            $duplicate->setQuantity($duplicate->getQuantity() + $cartItem->getQuantity());
        }else{
            $this->cart->addCartItem( $cartItem );
        }

        $this->updateCartTotal();
        return $this;
    }

    /**
     * @param $product
     * @return bool
     */
    public function findDuplicateProduct($product)
    {
        foreach($this->getCart()->getCartItems() as $ci){
            if($product == $ci->getProduct())
                return $ci;
        }
        return false;
    }

    /**
     * @param CartItem $cartItem
     * @return CartManager
     */
    public function removeItem(CartItem $cartItem )
    {
        $duplicate = $this->findDuplicateProduct($cartItem->getProduct());

        $this->cart->removeCartItem($duplicate);
        $this->delete($duplicate);

        $this->updateCartTotal();
        return $this;
    }

    /**
     *
     */
    public function updateCartTotal()
    {
        $cartTotal = 0;
        foreach($this->cart->getCartItems() as $ci){
            $cartTotal += ($ci->getQuantity() * $ci->getUnitPrice());
        }
        $this->cart->setCartTotal($cartTotal);
    }

    /**
     *
     */
    public function emptyCart()
    {
        $cartItems = $this->cart->getCartItems();
        foreach( $cartItems as $cartItem ){
            $this->removeItem( $cartItem );
        }
    }

    public function saveCart()
    {
        parent::save($this->cart);
        return $this;
    }
    
}
