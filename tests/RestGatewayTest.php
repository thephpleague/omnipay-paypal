<?php

namespace Omnipay\PayPal;

use Omnipay\Tests\GatewayTestCase;
use Omnipay\Common\CreditCard;

class RestGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new RestGateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setToken('TEST-TOKEN-123');
        $this->gateway->setTokenExpires(time() + 600);

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

    public function testBearerToken()
    {
        $this->gateway->setToken('');
        $this->setMockHttpResponse('RestTokenSuccess.txt');

        $this->assertFalse($this->gateway->hasToken());
        $this->assertEquals('A015GQlKQ6uCRzLHSGRliANi59BHw6egNVKEWRnxvTwvLr0', $this->gateway->getToken()); // triggers request
        $this->assertEquals(time() + 28800, $this->gateway->getTokenExpires());
        $this->assertTrue($this->gateway->hasToken());
    }

    public function testBearerTokenReused()
    {
        $this->setMockHttpResponse('RestTokenSuccess.txt');
        $this->gateway->setToken('MYTOKEN');
        $this->gateway->setTokenExpires(time() + 60);

        $this->assertTrue($this->gateway->hasToken());
        $this->assertEquals('MYTOKEN', $this->gateway->getToken());
    }

    public function testBearerTokenExpires()
    {
        $this->setMockHttpResponse('RestTokenSuccess.txt');
        $this->gateway->setToken('MYTOKEN');
        $this->gateway->setTokenExpires(time() - 60);

        $this->assertFalse($this->gateway->hasToken());
        $this->assertEquals('A015GQlKQ6uCRzLHSGRliANi59BHw6egNVKEWRnxvTwvLr0', $this->gateway->getToken());
    }

    public function testAuthorize()
    {
        $this->setMockHttpResponse('RestPurchaseSuccess.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('44E89981F8714392Y', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testPurchase()
    {
        $this->setMockHttpResponse('RestPurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('44E89981F8714392Y', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testFetchTransaction()
    {
        $request = $this->gateway->fetchTransaction(array('transactionReference' => 'abc123'));

        $this->assertInstanceOf('\Omnipay\PayPal\Message\RestFetchTransactionRequest', $request);
        $this->assertSame('abc123', $request->getTransactionReference());
    }

    public function testCreateCard()
    {
        $this->setMockHttpResponse('RestCreateCardSuccess.txt');

        $response = $this->gateway->createCard($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('CARD-70E78145XN686604FKO3L6OQ', $response->getCardReference());
        $this->assertNull($response->getMessage());
    }

    public function testPayWithSavedCard()
    {
        $this->setMockHttpResponse('RestCreateCardSuccess.txt');
        $response = $this->gateway->createCard($this->options)->send();
        $cardRef = $response->getCardReference();

        $this->setMockHttpResponse('RestPurchaseSuccess.txt');
        $response = $this->gateway->purchase(array('amount'=>'10.00', 'cardReference'=>$cardRef))->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('44E89981F8714392Y', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

}
