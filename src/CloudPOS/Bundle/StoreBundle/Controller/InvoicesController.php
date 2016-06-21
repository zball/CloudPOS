<?php

namespace CloudPOS\Bundle\StoreBundle\Controller;

use CloudPOS\Bundle\StoreBundle\Entity\Invoice;
use Doctrine\Common\Collections\Criteria;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class InvoicesController extends Controller
{

    /**
     * @Route("/invoices")
     * @QueryParam(name="status", requirements="[a-zA-Z]+", description="Invoices filtered by status.")
     * @QueryParam(name="user", requirements="[0-9]+", description="Invoices filtered by user_id.")
     * @Method({"GET"})
     *
     * @param ParamFetcherInterface $paramFetcher
     * @return array
     */
    public function listAction(ParamFetcherInterface $paramFetcher, Request $request)
    {
        // @todo switch to the paramfetcher
        $critera = [];

        if($userId = $request->get('user')) {
            $critera['user'] = $userId;
        }

        if($status = $request->get('status')) {
            $critera['status'] = $status;
        }

        return $this->getDoctrine()->getRepository('CloudPOSStoreBundle:Invoice')->findBy($critera);

    }

    /**
     * @Route("/invoices/{id}")
     * @Method({"GET"})
     *
     * @param int $id
     * @return Invoice
     */
    public function getAction($id)
    {
        return $this->getDoctrine()->getRepository('CloudPOSStoreBundle:Invoice')->find($id);

    }

    /**
     * @Route("/invoices/{id}/close")
     * @Method({"PUT"})
     *
     * @param int
     * @return Invoice
     */
    public function closeAction($id)
    {
        $invoice = $this->getDoctrine()->getRepository('CloudPOSStoreBundle:Invoice')->find($id);

        $invoice->setStatus('closed');

        $em = $this->getDoctrine()->getManager();
        $em->persist($invoice);
        $em->flush();

        return $invoice;

    }

}
