<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class RestAuthorizeRequestTest extends TestCase
{
    /**
     * @var ProPurchaseRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new RestAuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '10.00',
                'currency' => 'USD',
                'card' => $this->getValidCard(),
            )
        );
    }

    public function testGetData()
    {
        $card = new CreditCard($this->getValidCard());
        $card->setStartMonth(1);
        $card->setStartYear(2000);

        $this->request->setCard($card);
        $this->request->setTransactionId('abc123');
        $this->request->setDescription('Sheep');
        $this->request->setClientIp('127.0.0.1');

        $data = $this->request->getData();

        $this->assertSame('authorize', $data['intent']);
        $this->assertSame('credit_card', $data['payer']['payment_method']);
        $this->assertSame('10.00', $data['transactions'][0]['amount']['total']);
        $this->assertSame('USD', $data['transactions'][0]['amount']['currency']);
        $this->assertSame('abc123 : Sheep', $data['transactions'][0]['description']);

        $this->assertSame($card->getNumber(), $data['payer']['funding_instruments'][0]['credit_card']['number']);
        $this->assertSame($card->getBrand(), $data['payer']['funding_instruments'][0]['credit_card']['type']);
        $this->assertSame($card->getExpiryMonth(), $data['payer']['funding_instruments'][0]['credit_card']['expire_month']);
        $this->assertSame($card->getExpiryYear(), $data['payer']['funding_instruments'][0]['credit_card']['expire_year']);
        $this->assertSame($card->getCvv(), $data['payer']['funding_instruments'][0]['credit_card']['cvv2']);

        $this->assertSame($card->getFirstName(), $data['payer']['funding_instruments'][0]['credit_card']['first_name']);
        $this->assertSame($card->getLastName(), $data['payer']['funding_instruments'][0]['credit_card']['last_name']);
        $this->assertSame($card->getAddress1(), $data['payer']['funding_instruments'][0]['credit_card']['billing_address']['line1']);
        $this->assertSame($card->getAddress2(), $data['payer']['funding_instruments'][0]['credit_card']['billing_address']['line2']);
        $this->assertSame($card->getCity(), $data['payer']['funding_instruments'][0]['credit_card']['billing_address']['city']);
        $this->assertSame($card->getState(), $data['payer']['funding_instruments'][0]['credit_card']['billing_address']['state']);
        $this->assertSame($card->getPostcode(), $data['payer']['funding_instruments'][0]['credit_card']['billing_address']['postal_code']);
        $this->assertSame($card->getCountry(), $data['payer']['funding_instruments'][0]['credit_card']['billing_address']['country_code']);
    }

    public function testDescription()
    {
        $this->request->setTransactionId('');
        $this->request->setDescription('');
        $this->assertEmpty($this->request->getDescription());

        $this->request->setTransactionId('');
        $this->request->setDescription('Sheep');
        $this->assertEquals('Sheep', $this->request->getDescription());

        $this->request->setTransactionId('abc123');
        $this->request->setDescription('');
        $this->assertEquals('abc123', $this->request->getDescription());

        $this->request->setTransactionId('abc123');
        $this->request->setDescription('Sheep');
        $this->assertEquals('abc123 : Sheep', $this->request->getDescription());
    }
}
