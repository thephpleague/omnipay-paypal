<?php

namespace Omnipay\PayPal;

use Omnipay\Tests\GatewayTestCase;

class ExpressGatewayTest extends GatewayTestCase
{
    /**
     * @var \Omnipay\PayPal\ExpressGateway
     */
    protected $gateway;
    /**
     * @var \Omnipay\PayPal\ExpressGateway
     */
    protected $gatewayWithToken;
    /**
     * @var array
     */
    protected $options;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new ExpressGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'amount' => '10.00',
            'returnUrl' => 'https://www.example.com/return',
            'cancelUrl' => 'https://www.example.com/cancel',
        );

        $requestWithToken = clone $this->getHttpRequest();
        $requestWithToken->query->set('token', 'TOKEN1234');

        $this->gatewayWithToken = new ExpressGateway($this->getHttpClient(), $requestWithToken);
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockHttpResponse('ExpressPurchaseSuccess.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertInstanceOf('\Omnipay\PayPal\Message\ExpressAuthorizeResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://www.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=EC-42721413K79637829', $response->getRedirectUrl());
    }

    public function testAuthorizeFailure()
    {
        $this->setMockHttpResponse('ExpressPurchaseFailure.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('This transaction cannot be processed. The amount to be charged is zero.', $response->getMessage());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('ExpressPurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertInstanceOf('\Omnipay\PayPal\Message\ExpressAuthorizeResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://www.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=EC-42721413K79637829', $response->getRedirectUrl());
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('ExpressPurchaseFailure.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('This transaction cannot be processed. The amount to be charged is zero.', $response->getMessage());
    }

    public function testFetchCheckoutSuccess()
    {
        $this->setMockHttpResponse('ExpressFetchCheckoutSuccess.txt');

        $options = array();

        $response = $this->gatewayWithToken->fetchCheckout($options)->send();

        $this->assertInstanceOf('\Omnipay\PayPal\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
    }
}
