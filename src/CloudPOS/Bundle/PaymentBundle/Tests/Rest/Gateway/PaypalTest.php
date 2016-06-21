<?php
namespace CloudPOS\Bundle\PaymentBundle\Tests\Rest\Gateway;

use CloudPOS\Bundle\PaymentBundle\Entity\Model\PaymentInterface;
use CloudPOS\Bundle\PaymentBundle\Entity\Model\TransactionInterface;
use CloudPOS\Bundle\PaymentBundle\Rest\Gateway\Paypal;
use CloudPOS\Component\Testing\TestCase;

class PaypalTest extends TestCase
{
    /**
     * @var Paypal
     */
    protected $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = $this->container->get('cloud_pos_payment.rest_gateway.paypal');
    }

    public function testCanObtainAccessToken()
    {
        $token = $this->client->getToken();

        $this->assertEquals(97, strlen($token));
    }

    public function testAuthorizeIsApprovedWithValidData()
    {
        $response = $this->client->authorize($this->validPayload());

        $data = json_decode($response->getBody());

        $this->assertEquals($data->state, 'approved');
    }

    public function testAuthorizationsCanBeCaptured()
    {
        $authorize = $this->client->authorize($this->validPayload());

        $authBody = json_decode($authorize->getBody());

        $capture = $this->client->capture(
            $authBody->transactions[0]->related_resources[0]->authorization->id,
            $authBody->transactions[0]->amount->total,
            $authBody->transactions[0]->amount->currency
        );
    }

    public function testPurchaseIsApprovedWithValidData()
    {
        $response = $this->client->purchase($this->validPayload());

        $data = json_decode($response->getBody());

        $this->assertEquals($data->state, 'approved');
    }

    protected function validPayload()
    {
        return function (TransactionInterface $transaction, PaymentInterface $payment) {

            $transaction->setAmount(1.00);
            $transaction->setCurrency('USD');
            $transaction->setDetails('Testing...');

            $payment->setNumber(4242424242424242);
            $payment->setType('visa');
            $payment->setExpireMonth(11);
            $payment->setExpireYear(2018);
            $payment->setCvv2(123);

            // Or

            // $payment->setType('gateway');
        };
    }
}
