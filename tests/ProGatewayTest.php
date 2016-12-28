<?php

namespace Omnipay\PayPal;

use League\Omnipay\Tests\GatewayTestCase;
use League\Omnipay\Common\CreditCard;

class ProGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new ProGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'amount' => '10.00',
            'currency' => 'USD',
            'card' => new CreditCard(array(
                'number' => '4111111111111111',
                'expiryMonth' => '12',
                'expiryYear' => '2016',
                'cvv' => '123',
            )),
            'customer' => array(
                'firstName' => 'Example',
                'lastName' => 'User',
            )
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
}
