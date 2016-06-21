<?php
namespace CloudPOS\Bundle\PaymentBundle\Rest\Gateway;

use Closure;
use CloudPOS\Bundle\PaymentBundle\Entity\Model\Payment;
use CloudPOS\Bundle\PaymentBundle\Entity\Model\PaymentInterface;
use CloudPOS\Bundle\PaymentBundle\Entity\Model\Transaction;
use CloudPOS\Bundle\PaymentBundle\Entity\Model\TransactionInterface;

class Paypal extends Gateway
{
    /**
     * @var
     */
    protected $token;

    /**
     * @var
     */
    protected $tokenExpires;

    /**
     * @param Closure $closure
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function authorize(Closure $closure)
    {
        $closure($transaction = new Transaction, $payment = new Payment);

        return $this->post(
            'https://api.sandbox.paypal.com/v1/payments/payment',
            $this->payload(['intent' => 'authorize'], $transaction, $payment)
        );
    }

    /**
     * @param $token
     * @param $amount
     * @param $currency
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function capture($token, $amount, $currency)
    {
        return $this->post(
            sprintf('https://api.sandbox.paypal.com/v1/payments/authorization/%s/capture', $token), [
                'is_final_capture' => true,
                'amount' => [
                    'currency' => $currency,
                    'total' => $amount
                ]
            ]
        );
    }

    /**
     * @param Closure $closure
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function purchase(Closure $closure)
    {
        $closure($transaction = new Transaction, $payment = new Payment);

        return $this->post(
            'https://api.sandbox.paypal.com/v1/payments/payment',
            $this->payload(['intent' => 'sale'], $transaction, $payment)
        );
    }

    /**
     * @param $token
     * @param $id
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function execute($token, $id)
    {
        return $this->post(
            sprintf('https://api.sandbox.paypal.com/v1/payments/payment/%s/execute', $token),
            ['payer_id' => $id]
        );
    }

    /**
     * @param PaymentInterface $payment
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @internal param array $data
     */
    public function createCard(PaymentInterface $payment)
    {
        return $this->post('api/authorize', $data);
    }

    /**
     * @param array $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function refund(array $data = [])
    {
        return $this->post('api/authorize', $data);
    }

    /**
     * Set OAuth 2.0 access token.
     *
     * @param string $value
     * @return RestGateway provides a fluent interface
     */
    public function setToken($value)
    {
        $this->token = $value;

        return $this;
    }

    /**
     * Get OAuth 2.0 access token expiry time.
     *
     * @return integer
     */
    public function getTokenExpires()
    {
        return $this->tokenExpires;
    }

    /**
     * Set OAuth 2.0 access token expiry time.
     *
     * @param integer $value
     * @return RestGateway provides a fluent interface
     */
    public function setTokenExpires($value)
    {
        $this->tokenExpires = $value;

        return $this;
    }

    /**
     * Is there a bearer token and is it still valid?
     *
     * @return bool
     */
    public function hasToken()
    {
        $expires = $this->getTokenExpires();
        if (!empty($expires) && !is_numeric($expires)) {
            $expires = strtotime($expires);
        }

        return !empty($this->token) && time() < $expires;
    }

    /**
     * @param bool $create
     * @return mixed
     */
    public function getToken($create = true)
    {
        if ($create && !$this->hasToken()) {
            $token = $this->createToken();
            $this->setToken($token->access_token);
            $this->setTokenExpires(time() + $token->expires_in);
        }

        return $this->token;
    }

    /**
     * @return mixed
     */
    public function createToken()
    {
        $response = $this->client->request('POST', 'https://api.sandbox.paypal.com/v1/oauth2/token', [
            'auth' => ['AS3jAxAu2gvTyXsGCvtfHGGOJAEZT4lpCB0oaTh9n_VRFn8SpyQ-xc4QLm6A', 'EM_iEBDG7ieAhAqpo7cAikqHfHqUFCERXuuc-ozsXeil3QYiV0dSCYLLwBF8', 'basic'],
            'body' => 'grant_type=client_credentials',
            'headers' => [
                'Accept-Language' => 'en_US',
                'Accept' => 'application/json'
            ]
        ]);

        return json_decode($response->getBody());
    }

    /**
     * @param $method
     * @param $endpoint
     * @param array $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function createRequest($method, $endpoint, $data = [])
    {
        if (!$this->hasToken()) {
            $this->getToken(true);
        }

        $_data = array_merge(['json' => $data], [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ]
        ]);

        return parent::createRequest($method, $endpoint, $_data);
    }

    /**
     * @param $params
     * @param TransactionInterface $transaction
     * @param PaymentInterface $payment
     * @return mixed
     */
    protected function payload($params, TransactionInterface $transaction, PaymentInterface $payment)
    {
        return array_merge($params, ['payer' => [
            'payment_method' => 'credit_card',
            'funding_instruments' => [$payment->toArray()],
        ],
            "transactions" => [
                [
                    "amount" => [
                        "total" => $transaction->getAmount(),
                        "currency" => $transaction->getCurrency(),
                        "details" => [
                            "subtotal" => $transaction->getAmount(),
                        ]
                    ],
                    "description" => $transaction->getDetails()
                ]
            ]
        ]);
    }
}
