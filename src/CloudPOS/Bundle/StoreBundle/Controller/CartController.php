<?php
namespace CloudPOS\Bundle\StoreBundle\Controller;

use CloudPOS\Bundle\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use CloudPOS\Bundle\StoreBundle\Entity\Cart;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends Controller
{

    /**
     * @Route("/carts")
     * @Method({"GET"})
     *
     * @return array
     */
    public function listAction()
    {
        return $this->getDoctrine()->getRepository('CloudPOSStoreBundle:Cart')->findAll();
    }

    /**
     * @Route("/carts")
     * @Method({"POST", "OPTIONS"})
     *
     * @param Request $request
     * @return Cart
     */
    public function createAction( Request $request )
    {
        $cartManager = $this->get( 'cloud_pos_store.cart_manager' );
        $cart = $cartManager->create( $request );

        $user = $this->get('security.token_storage')->getToken()->getUser();

        if($user instanceof User) {
            $cart->setUser($user);
        }

        $cartManager->save( $cart );

        return $cart;
    }

    /**
     * @Route("/carts/{id}")
     * @Method({"GET"})
     *
     * @param int $id
     * @return Cart
     *
     * @throws NotFoundHttpException
     */
    public function getAction($id)
    {
        if (!$cart = $this->getDoctrine()->getManager()->getRepository('CloudPOSStoreBundle:Cart')->find($id))
            throw new NotFoundHttpException;
        return $cart;
    }

    /**
     * @Route("/carts/{cartId}/products/{productId}")
     * @Method({"POST", "OPTIONS"})
     *
     * @param Request $request
     * @param int $cartId Cart ID
     * @param int $productId ProductID
     * @return Cart
     *
     * @throws NotFoundHttpException
     */
    public function addAction(Request $request, $cartId, $productId)
    {
        if (!$cart = $this->getDoctrine()->getManager()->getRepository('CloudPOSStoreBundle:Cart')->find($cartId))
            throw new NotFoundHttpException;

        $item = $this->get('cloud_pos_store.item_resolver')
            ->resolveItem($request)
            ->setCart($cart);

        $this->get('cloud_pos_store.cart_manager')
            ->setCart($cart)
            ->addItem($item)
            ->saveCart();

        return $cart;
    }

    /**
     * @Route("/carts/{cartId}/products/{productId}")
     * @Method({"DELETE"})
     *
     * @param Request $request
     * @param int $cartId Cart ID
     * @param int $productId ProductID
     * @return Cart
     *
     * @throws NotFoundHttpException
     */
    public function removeAction(Request $request, $cartId, $productId)
    {
        if (!$cart = $this->getDoctrine()->getManager()->getRepository('CloudPOSStoreBundle:Cart')->find($cartId))
            throw new NotFoundHttpException;

        $item = $this->get('cloud_pos_store.item_resolver')
            ->resolveItem($request)
            ->setCart($cart);

        $this->get('cloud_pos_store.cart_manager')
            ->setCart($cart)
            ->removeItem($item)
            ->saveCart();

        return $cart;
    }

    /**
     * @Route("/carts/{cartId}/products/{productId}")
     * @Method({"PUT", "OPTIONS"})
     *
     * @param Request $request
     * @param int $cartId Cart ID
     * @param int $productId ProductID
     * @return Cart
     *
     * @throws NotFoundHttpException
     */
    public function updateAction(Request $request, $cartId, $productId)
    {
        if (!$cart = $this->getDoctrine()->getManager()->getRepository('CloudPOSStoreBundle:Cart')->find($cartId))
            throw new NotFoundHttpException;

        $item = $this->get('cloud_pos_store.item_resolver')
            ->resolveItem($request)
            ->setCart($cart);

        $cartManager = $this->get('cloud_pos_store.cart_manager');

        $cartManager->setCart($cart);
        $duplicate = $cartManager->findDuplicateProduct($item->getProduct());

        if($duplicate){
            $duplicate->setQuantity($item->getQuantity());
        }else{
            $cart->addCartItem($item);
        }

        $cartManager->updateCartTotal();
        $cartManager->saveCart();

        return $cart;
    }
}
