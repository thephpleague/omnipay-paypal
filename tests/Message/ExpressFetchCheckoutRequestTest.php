<?php

namespace Omnipay\PayPal\Message;

use Omnipay\PayPal\Message\ExpressFetchCheckoutRequest;
use Omnipay\Tests\TestCase;

class ExpressFetchCheckoutRequestTest extends TestCase
{
    /**
     * @var \Omnipay\PayPal\Message\ExpressFetchCheckoutRequest
     */
    private $request;

    public function setUp()
    {
        $client = $this->getHttpClient();

        $request = $this->getHttpRequest();
        $request->query->set('token', 'TOKEN1234');

        $this->request = new ExpressFetchCheckoutRequest($client, $request);
    }

    public function testGetData()
    {
        $this->request->setUsername('testuser');
        $this->request->setPassword('testpass');
        $this->request->setSignature('SIG');

        $expected = array();
        $expected['METHOD'] = 'GetExpressCheckoutDetails';
        $expected['USER'] = 'testuser';
        $expected['PWD'] = 'testpass';
        $expected['SIGNATURE'] = 'SIG';
        $expected['SUBJECT'] = null;
        $expected['VERSION'] = ExpressCompletePurchaseRequest::API_VERSION;
        $expected['TOKEN'] = 'TOKEN1234';

        $this->assertEquals($expected, $this->request->getData());
    }


}
