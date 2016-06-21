<?php
namespace CloudPOS\Bundle\PaymentBundle\Controller;

use CloudPOS\Bundle\PaymentBundle\Entity\Model\PaymentInterface;
use CloudPOS\Bundle\PaymentBundle\Entity\Model\TransactionInterface;
use CloudPOS\Bundle\PaymentBundle\Rest\Gateway\Gateway;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;

/**
 * @Route(service="cloud_pos_payment.payment_controller")
 */
class PaymentController extends Controller
{
    /**
     * @var Gateway
     */
    protected $client;

    /**
     * PaymentController constructor.
     * @param Gateway $gateway
     */
    public function __construct(Gateway $gateway)
    {
        $this->client = $gateway;
    }

    /**
     * @Route("payments/purchase")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function purchase(Request $request)
    {
        // @Todo needs validation
        $response = $this->client->purchase(
            function (TransactionInterface $transaction, PaymentInterface $payment) use ($request) {

                $input = $request->request->all();

                $transaction->setAmount($input['transaction']['amount']);
                $transaction->setCurrency($input['transaction']['currency']);
                $transaction->setDetails($input['transaction']['currency']);

                $payment->setNumber($input['card']['number']);
                $payment->setType($input['card']['type']);
                $payment->setExpireMonth($input['card']['expire_month']);
                $payment->setExpireYear($input['card']['expire_year']);
                $payment->setCvv2($input['card']['cvv2']);
            });

        return (new HttpFoundationFactory)->createResponse($response);
    }

    /**
     * @Route("payments/execute")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function execute(Request $request)
    {
        // @Todo needs validation
        return (new HttpFoundationFactory)
            ->createResponse(
                $this->client->execute($request->get('token'), $request->get('payer_id'))
            );
    }

    /**
     * @Route("payments/authorize")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function authorize(Request $request)
    {
        // @Todo needs validation
        $response = $this->client->authorize(
            function (TransactionInterface $transaction, PaymentInterface $payment) use ($request) {

                $input = $request->request->all();

                $transaction->setAmount($input['transaction']['amount']);
                $transaction->setCurrency($input['transaction']['currency']);
                $transaction->setDetails($input['transaction']['currency']);

                $payment->setNumber($input['card']['number']);
                $payment->setType($input['card']['type']);
                $payment->setExpireMonth($input['card']['expire_month']);
                $payment->setExpireYear($input['card']['expire_year']);
                $payment->setCvv2($input['card']['cvv2']);
            });

        return (new HttpFoundationFactory)->createResponse($response);
    }

    /**
     * @Route("payments/capture")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function capture(Request $request)
    {
        // @Todo needs validation
        return (new HttpFoundationFactory)
            ->createResponse(
                $this->client->capture($request->get('token'), $request->get('total'), $request->get('currency'))
            );
    }
}
