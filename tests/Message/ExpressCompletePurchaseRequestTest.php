<?php

namespace Omnipay\PayPal\Message;

use Omnipay\PayPal\Message\ExpressCompletePurchaseRequest;
use League\Omnipay\Tests\TestCase;

class ExpressCompletePurchaseRequestTest extends TestCase
{
    /**
     * @var \Omnipay\PayPal\Message\ExpressCompletePurchaseRequest
     */
    private $request;

    public function setUp()
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();

        $query = $request->getQueryParams();
        $query['PayerID'] = 'Payer-1234';
        $query['token'] = 'TOKEN1234';
        $request = $request->withQueryParams($query);

        $this->request = new ExpressCompletePurchaseRequest($client, $request);
    }

    public function testGetData()
    {
        $this->request->setAmount('1.23');
        $this->request->setCurrency('USD');
        $this->request->setTransactionId('ABC-123');
        $this->request->setUsername('testuser');
        $this->request->setPassword('testpass');
        $this->request->setSignature('SIG');
        $this->request->setSubject('SUB');
        $this->request->setDescription('DESC');
        $this->request->setNotifyUrl('https://www.example.com/notify');
        $this->request->setMaxAmount('0.00');
        $this->request->setTaxAmount('0.00');
        $this->request->setShippingAmount('0.00');
        $this->request->setHandlingAmount('0.00');
        $this->request->setShippingDiscount('0.00');
        $this->request->setInsuranceAmount('0.00');

        $expected = array();
        $expected['METHOD'] = 'DoExpressCheckoutPayment';
        $expected['PAYMENTREQUEST_0_PAYMENTACTION'] = 'Sale';
        $expected['PAYMENTREQUEST_0_AMT'] = '1.23';
        $expected['PAYMENTREQUEST_0_CURRENCYCODE'] = 'USD';
        $expected['PAYMENTREQUEST_0_INVNUM'] = 'ABC-123';
        $expected['PAYMENTREQUEST_0_DESC'] = 'DESC';
        $expected['PAYMENTREQUEST_0_NOTIFYURL'] = 'https://www.example.com/notify';
        $expected['USER'] = 'testuser';
        $expected['PWD'] = 'testpass';
        $expected['SIGNATURE'] = 'SIG';
        $expected['SUBJECT'] = 'SUB';
        $expected['VERSION'] = ExpressCompletePurchaseRequest::API_VERSION;
        $expected['TOKEN'] = 'TOKEN1234';
        $expected['PAYERID'] = 'Payer-1234';
        $expected['MAXAMT'] = '0.00';
        $expected['PAYMENTREQUEST_0_TAXAMT'] = '0.00';
        $expected['PAYMENTREQUEST_0_SHIPPINGAMT'] = '0.00';
        $expected['PAYMENTREQUEST_0_HANDLINGAMT'] = '0.00';
        $expected['PAYMENTREQUEST_0_SHIPDISCAMT'] = '0.00';
        $expected['PAYMENTREQUEST_0_INSURANCEAMT'] = '0.00';

        $this->assertEquals($expected, $this->request->getData());
    }

    public function testGetDataWithItems()
    {
        $this->request->setAmount('50.00');
        $this->request->setCurrency('USD');
        $this->request->setTransactionId('ABC-123');
        $this->request->setUsername('testuser');
        $this->request->setPassword('testpass');
        $this->request->setSignature('SIG');
        $this->request->setSubject('SUB');
        $this->request->setDescription('DESC');

        $this->request->setItems(array(
            array('name' => 'Floppy Disk', 'description' => 'MS-DOS', 'quantity' => 2, 'price' => 10),
            array('name' => 'CD-ROM', 'description' => 'Windows 95', 'quantity' => 1, 'price' => 40),
        ));

        $data = $this->request->getData();
        $this->assertSame('Floppy Disk', $data['L_PAYMENTREQUEST_0_NAME0']);
        $this->assertSame('MS-DOS', $data['L_PAYMENTREQUEST_0_DESC0']);
        $this->assertSame(2, $data['L_PAYMENTREQUEST_0_QTY0']);
        $this->assertSame('10.00', $data['L_PAYMENTREQUEST_0_AMT0']);

        $this->assertSame('CD-ROM', $data['L_PAYMENTREQUEST_0_NAME1']);
        $this->assertSame('Windows 95', $data['L_PAYMENTREQUEST_0_DESC1']);
        $this->assertSame(1, $data['L_PAYMENTREQUEST_0_QTY1']);
        $this->assertSame('40.00', $data['L_PAYMENTREQUEST_0_AMT1']);
    }
}
