<?php

namespace Omnipay\PayPal;

use Omnipay\Tests\GatewayTestCase;
use Omnipay\Common\CreditCard;

class ProGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new ProGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'amount' => '10.00',
            'card' => new CreditCard(array(
                'firstName' => 'Example',
                'lastName' => 'User',
                'number' => '4111111111111111',
                'expiryMonth' => '12',
                'expiryYear' => '2016',
                'cvv' => '123',
            )),
        );
    }

    public function testAuthorize()
    {
        $this->setMockHttpResponse('ProPurchaseSuccess.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('96U93778BD657313D', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testPurchase()
    {
        $this->setMockHttpResponse('ProPurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('96U93778BD657313D', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testFetchTransaction()
    {
        $request = $this->gateway->fetchTransaction(array('transactionReference' => 'abc123'));

        $this->assertInstanceOf('\Omnipay\PayPal\Message\FetchTransactionRequest', $request);
        $this->assertSame('abc123', $request->getTransactionReference());
    }

    public function testPayoutFailure()
    {
        $this->setMockHttpResponse('MassPayFailure.txt');

        $response = $this->gateway->payout(array(
            'username' => 'phpunit',
            'password' => 'password',
            'currency' => 'SEK',
            'recipients' => array(new Message\MassPayRecipient(array(
                'email'  => 'phpunit@paypal.com',
                'amount' => '12.34',
            ))),
        ))->send();

        $this->assertInstanceOf('Omnipay\PayPal\Message\MassPayResponse', $response);
        $this->assertFalse($response->isSuccessful());
    }
}
