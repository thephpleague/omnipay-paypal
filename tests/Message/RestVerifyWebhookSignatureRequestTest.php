<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Tests\TestCase;

final class RestVerifyWebhookSignatureRequestTest extends TestCase
{
    /**
     * @var RestVerifyWebhookSignatureRequest
     */
    private $request;

    public function setUp() : void
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();
        $this->request = new RestVerifyWebhookSignatureRequest($client, $request);
    }

    public function testGetData()
    {
        $data = [
            'transmission_id' => 'foo',
            'auth_algo' => 'bar',
            'cert_url' => 'baz',
            'transmission_sig' => 'qux',
            'transmission_time' => 'foobar',
            'webhook_event' => ['bar' => 'baz'],
            'webhook_id' => 'barbaz',
        ];

        $this->request->initialize($data);

        $this->assertEquals($data, $this->request->getData());
    }

    public function testGettersAndSetters() {
        $authAlgo = 'foo';
        $certUrl = 'bar';
        $transmissionId = 'baz';
        $transmissionTime = 'qux';
        $transmissionSig = 'foobar';
        $webhookEvent = ['bar' => 'baz'];
        $webhookId = 'barfoo';

        $this->request->setAuthAlgo($authAlgo);
        $this->request->setCertUrl($certUrl);
        $this->request->setTransmissionId($transmissionId);
        $this->request->setTransmissionTime($transmissionTime);
        $this->request->setTransmissionSig($transmissionSig);
        $this->request->setWebhookEvent($webhookEvent);
        $this->request->setWebhookId($webhookId);

        $this->assertEquals($authAlgo, $this->request->getAuthAlgo());
        $this->assertEquals($certUrl,$this->request->getCertUrl());
        $this->assertEquals($transmissionId, $this->request->getTransmissionId());
        $this->assertEquals($transmissionTime, $this->request->getTransmissionTime());
        $this->assertEquals($transmissionSig, $this->request->getTransmissionSig());
        $this->assertEquals($webhookEvent, $this->request->getWebhookEvent());
        $this->assertEquals($webhookId, $this->request->getWebhookId());
    }
}
