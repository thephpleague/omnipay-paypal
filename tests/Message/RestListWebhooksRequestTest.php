<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Tests\TestCase;

final class RestListWebhooksRequestTest extends TestCase
{
    /**
     * @var RestListWebhooksRequest
     */
    private $request;

    public function setUp() : void
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();
        $this->request = new RestListWebhooksRequest($client, $request);
    }

    public function testEndpoint()
    {
        $this->assertStringEndsWith('/notifications/webhooks', $this->request->getEndpoint());
    }

    public function testGetData()
    {
        $this->assertEmpty($this->request->getData());
    }
}
