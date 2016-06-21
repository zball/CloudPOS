<?php

namespace CloudPOS\Bundle\StoreBundle\Controller;

use CloudPOS\Bundle\StoreBundle\Entity\Product;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    /**
     * @Route("/products")
     * @Method({"GET"})
     *
     * @return array|\CloudPOS\Bundle\StoreBundle\Entity\Product[]
     */
    public function listAction()
    {
        return $this->getDoctrine()->getRepository('CloudPOSStoreBundle:Product')->findAll();
    }

    /**
     * @Route("/products")
     * @Method({"POST", "OPTIONS"})
     *
     * @param Request $request
     * @return Product
     */
    public function createAction(Request $request)
    {
        $productManager = $this->get('cloud_pos_store.product_manager');
        $product = $productManager->create($request);

        $productManager->save($product);

        return $product;
    }

    /**
     * @Route("/products/{id}")
     * @Method({"GET"})
     *
     * @param int $id
     * @return Product
     *
     * @throws NotFoundHttpException
     */
    public function getAction($id)
    {
        if (!$product = $this->getDoctrine()->getManager()->getRepository('CloudPOSStoreBundle:Product')->find($id))
            throw new NotFoundHttpException;
        return $product;
    }

    /**
     * @Route("/products/{id}")
     * @Method({"PUT", "OPTIONS"})
     *
     * @param Request $request
     * @param int $id
     * @return Product
     *
     * @throws NotFoundHttpException
     */
    public function updateAction(Request $request, $id)
    {
        $productManager = $this->get('cloud_pos_store.product_manager');

        if (!$product = $this->getDoctrine()->getManager()->getRepository('CloudPOSStoreBundle:Product')->find($id))
            throw new NotFoundHttpException;

        $product = $productManager->update($request, $product);
        $productManager->save($product);

        return $product;
    }

    /**
     * @Route("/products/{id}")
     * @Method({"DELETE", "OPTIONS"})
     *
     * @param int $id
     * @return Product
     *
     * @throws NotFoundHttpException
     */
    public function deleteAction($id)
    {
        $productManager = $this->get('cloud_pos_store.product_manager');

        if (!$product = $this->getDoctrine()->getManager()->getRepository('CloudPOSStoreBundle:Product')->find($id))
            throw new NotFoundHttpException;

        $productManager->delete($product);

        return Response::HTTP_OK;
    }
}
