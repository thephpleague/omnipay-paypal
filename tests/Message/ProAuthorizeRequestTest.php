<?php

namespace Omnipay\PayPal\Message;

use League\Omnipay\Common\CreditCard;
use League\Omnipay\Common\Customer;
use League\Omnipay\Tests\TestCase;

class ProAuthorizeRequestTest extends TestCase
{
    /**
     * @var ProAuthorizeRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new ProAuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '10.00',
                'currency' => 'USD',
                'card' => $this->getValidCard(),
                'customer' => $this->getCustomer(),
            )
        );
    }

    public function testGetData()
    {
        $card = new CreditCard($this->getValidCard());
        $card->setStartMonth(1);
        $card->setStartYear(2000);

        $customer = new Customer($this->getCustomer());

        $this->request->setCard($card);
        $this->request->setCustomer($customer);
        $this->request->setTransactionId('abc123');
        $this->request->setDescription('Sheep');
        $this->request->setClientIp('127.0.0.1');

        $data = $this->request->getData();

        $this->assertSame('DoDirectPayment', $data['METHOD']);
        $this->assertSame('Authorization', $data['PAYMENTACTION']);
        $this->assertSame('10.00', $data['AMT']);
        $this->assertSame('USD', $data['CURRENCYCODE']);
        $this->assertSame('abc123', $data['INVNUM']);
        $this->assertSame('Sheep', $data['DESC']);
        $this->assertSame('127.0.0.1', $data['IPADDRESS']);

        $this->assertSame($card->getNumber(), $data['ACCT']);
        $this->assertSame($card->getBrand(), $data['CREDITCARDTYPE']);
        $this->assertSame($card->getExpiryDate('mY'), $data['EXPDATE']);
        $this->assertSame('012000', $data['STARTDATE']);
        $this->assertSame($card->getCvv(), $data['CVV2']);
        $this->assertSame($card->getIssueNumber(), $data['ISSUENUMBER']);

        $this->assertSame($customer->getFirstName(), $data['FIRSTNAME']);
        $this->assertSame($customer->getLastName(), $data['LASTNAME']);
        $this->assertSame($customer->getEmail(), $data['EMAIL']);
        $this->assertSame($customer->getAddress1(), $data['STREET']);
        $this->assertSame($customer->getAddress2(), $data['STREET2']);
        $this->assertSame($customer->getCity(), $data['CITY']);
        $this->assertSame($customer->getState(), $data['STATE']);
        $this->assertSame($customer->getPostcode(), $data['ZIP']);
        $this->assertSame($customer->getCountry(), $data['COUNTRYCODE']);
    }
}
