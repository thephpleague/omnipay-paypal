<?php

namespace Omnipay\PayPal;

use Omnipay\Tests\GatewayTestCase;

class ExpressGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new ExpressGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'amount' => '10.00',
            'returnUrl' => 'https://www.example.com/return',
            'cancelUrl' => 'https://www.example.com/cancel',
        );
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

    public function testGetDetailsSuccess()
    {
        $this->setMockHttpResponse('GetExpressCheckoutDetailsSuccess.txt');

        $response = $this->gateway->getDetails(array(
            'username' => 'phpunit',
            'password' => 'password',
            'token'    => 'EC-6CG32037X9158254D',
        ))->send();

        $this->assertInstanceOf('Omnipay\PayPal\Message\GetExpressCheckoutDetailsResponse', $response);
        $this->assertTrue($response->isSuccessful());
    }

    public function testPayoutFailure()
    {
        $this->setMockHttpResponse('MassPayFailure.txt');

        $response = $this->gateway->payout(array(
            'username' => 'phpunit',
            'password' => 'password',
            'currency' => 'SEK',
            'recipients' => array(new Message\MassPayRecipient(array(
                'phone'  => '987654321',
                'amount' => '12.34',
            ))),
        ))->send();

        $this->assertInstanceOf('Omnipay\PayPal\Message\MassPayResponse', $response);
        $this->assertFalse($response->isSuccessful());
    }
}
