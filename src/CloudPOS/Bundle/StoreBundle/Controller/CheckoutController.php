<?php

namespace CloudPOS\Bundle\StoreBundle\Controller;

use CloudPOS\Bundle\StoreBundle\Entity\Invoice;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CheckoutController extends Controller
{

    /**
     * @Route("/checkout")
     * @Method({"POST", "OPTIONS"})
     *
     * @param Request $request
     * @return Invoice
     */
    public function indexAction(Request $request)
    {
        $cart = $this->getDoctrine()->getRepository('CloudPOSStoreBundle:Cart')->find($request->get('cart_id'));
        $cart->setStatus('closed');

        $address = $this->getDoctrine()->getRepository('CloudPOSStoreBundle:Address')->findOneBy(['default' => true]);

        $invoice = (new Invoice())
            ->setCart($cart)
            ->setStatus('processing')
            ->setUser($cart->getUser());

        if ($address) {
            $invoice->setAddress($address);
        }

        $em = $this->getDoctrine()->getManager();

        $em->persist($invoice);
        $em->persist($cart);
        $em->flush();

        return $invoice;
    }
}
