<?php

namespace Omnipay\PayPal\Message;

use Omnipay\PayPal\Message\VoidRequest;
use Omnipay\Tests\TestCase;

class VoidRequestTest extends TestCase
{
    /**
     * @var \Omnipay\PayPal\Message\VoidRequest
     */
    private $request;

    public function setUp()
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();
        $this->request = new VoidRequest($client, $request);
    }

    public function testGetData()
    {
        $this->request->setTransactionReference('ABC-123');
        $this->request->setAmount('1.23');
        $this->request->setCurrency('USD');
        $this->request->setUsername('testuser');
        $this->request->setPassword('testpass');
        $this->request->setSignature('SIG');
        $this->request->setSubject('SUB');

        $expected = array();
        $expected['METHOD'] = 'DoVoid';
        $expected['AUTHORIZATIONID'] = 'ABC-123';
        $expected['USER'] = 'testuser';
        $expected['PWD'] = 'testpass';
        $expected['SIGNATURE'] = 'SIG';
        $expected['SUBJECT'] = 'SUB';
        $expected['VERSION'] = VoidRequest::API_VERSION;

        $this->assertEquals($expected, $this->request->getData());
    }
}
