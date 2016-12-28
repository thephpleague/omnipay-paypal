<?php

namespace Omnipay\PayPal\Message;

use League\Omnipay\Common\CreditCard;
use League\Omnipay\Common\Customer;
use League\Omnipay\Tests\TestCase;

class RestCreateCardRequestTest extends TestCase
{
    /** @var RestCreateCardRequest */
    protected $request;

    /** @var CreditCard */
    protected $card;

    /** @var Customer */
    protected $customer;

    public function setUp()
    {
        parent::setUp();

        $this->request = new RestCreateCardRequest($this->getHttpClient(), $this->getHttpRequest());

        $card = $this->getValidCard();
        $this->card = new CreditCard($card);
        $this->customer = new Customer($this->getCustomer());

        $this->request->initialize(array('card' => $card, 'customer' => $this->customer));
    }

    public function testGetData()
    {
        $card = $this->card;
        $customer = $this->customer;
        $data = $this->request->getData();

        $this->assertSame($card->getNumber(), $data['number']);
        $this->assertSame($card->getBrand(), $data['type']);
        $this->assertSame($card->getExpiryMonth(), $data['expire_month']);
        $this->assertSame($card->getExpiryYear(), $data['expire_year']);
        $this->assertSame($card->getCvv(), $data['cvv2']);

        $this->assertSame($customer->getFirstName(), $data['first_name']);
        $this->assertSame($customer->getLastName(), $data['last_name']);
        $this->assertSame($customer->getAddress1(), $data['billing_address']['line1']);
        $this->assertSame($customer->getAddress2(), $data['billing_address']['line2']);
        $this->assertSame($customer->getCity(), $data['billing_address']['city']);
        $this->assertSame($customer->getState(), $data['billing_address']['state']);
        $this->assertSame($customer->getPostcode(), $data['billing_address']['postal_code']);
        $this->assertSame($customer->getCountry(), $data['billing_address']['country_code']);
    }
}
