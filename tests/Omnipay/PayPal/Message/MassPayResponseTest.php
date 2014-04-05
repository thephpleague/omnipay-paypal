<?php

namespace Omnipay\PayPal\Message;

use DateTime;
use Omnipay\Tests\TestCase;

class MassPayResponseTest extends TestCase
{
    public function testConstruct()
    {
        // response should decode URL format data
        $response = new MassPayResponse($this->getMockRequest(), 'example=value&foo=bar');

        $this->assertEquals(array('example' => 'value', 'foo' => 'bar'), $response->getData());
    }

    public function testMassPaySuccess()
    {
        $httpResponse = $this->getMockHttpResponse('MassPaySuccess.txt');
        $request = $this->getMockRequest();
        $response = new MassPayResponse($request, $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(new DateTime('2014-04-04T14:41:50Z'), $response->getTimestamp());
        $this->assertSame('54a7c4e9c9f55', $response->getCorrelationId());
        $this->assertSame('112.0', $response->getVersion());
        $this->assertSame(10433064, $response->getVersionBuild());
        $this->assertSame(0, $response->getCode());
        $this->assertNull($response->getMessage());
    }

    public function testMassPayFailure()
    {
        $httpResponse = $this->getMockHttpResponse('MassPayFailure.txt');
        $request = $this->getMockRequest();
        $response = new MassPayResponse($request, $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(new DateTime('2014-04-04T10:53:47Z'), $response->getTimestamp());
        $this->assertSame('dbb9305372721', $response->getCorrelationId());
        $this->assertSame('112.0', $response->getVersion());
        $this->assertSame(10433064, $response->getVersionBuild());
        $this->assertSame(10321, $response->getCode());
        $this->assertSame('The account does not have sufficient funds to do this masspay', $response->getMessage());
    }
}
