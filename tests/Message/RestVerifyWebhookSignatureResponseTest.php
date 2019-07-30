<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Tests\TestCase;

final class RestVerifyWebhookSignatureResponseTest extends TestCase
{
    public function testGetVerificationStatus()
    {
        $response = new RestVerifyWebhookSignatureResponse(
            $this->getMockRequest(),
            ['verification_status' => 'SUCCESS']
        );

        $this->assertSame('SUCCESS', $response->getVerificationStatus());
    }

    public function testIsSuccessfulWillReturnFalseIfParentCheckIsSuccesfullButVerificationFailed()
    {
        $response = new RestVerifyWebhookSignatureResponse(
            $this->getMockRequest(),
            ['verification_status' => 'FAILED']
        );

        $this->assertFalse($response->isSuccessful());
    }

    public function testIsSuccessfulWillReturnFalseIfParentCheckIsUnsuccesfull()
    {
        $response = new RestVerifyWebhookSignatureResponse(
            $this->getMockRequest(),
            ['verification_status' => 'foobar'],
            400
        );

        $this->assertFalse($response->isSuccessful());
    }

    public function testIsSuccessfulWillReturnTrueIfEverythingIsOk()
    {
        $response = new RestVerifyWebhookSignatureResponse(
            $this->getMockRequest(),
            ['verification_status' => 'SUCCESS']
        );

        $this->assertTrue($response->isSuccessful());
    }
}
