<?php

namespace Omnipay\PayPal\Message;

use DateTime;
use Omnipay\Tests\TestCase;

class GetExpressCheckoutDetailsResponseTest extends TestCase
{
    public function testConstruct()
    {
        // response should decode URL format data
        $response = new GetExpressCheckoutDetailsResponse($this->getMockRequest(), 'example=value&foo=bar');

        $this->assertEquals(array('example' => 'value', 'foo' => 'bar'), $response->getData());
    }

    public function testGetDetailsSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('GetExpressCheckoutDetailsSuccess.txt');
        $request = $this->getMockRequest();
        $response = new GetExpressCheckoutDetailsResponse($request, $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame(0, $response->getCode());

        $this->assertSame('EC-6CG32037X9158254D', $response->getToken());
        $this->assertSame('PaymentActionNotInitiated', $response->getCheckoutStatus());
        $this->assertEquals(new DateTime('2014-04-03T11:20:35Z'), $response->getTimestamp());
        $this->assertSame('8f3b132a50bfc', $response->getCorrelationId());
        $this->assertSame('112.0', $response->getVersion());
        $this->assertSame(10433064, $response->getVersionBuild());
        $this->assertSame('payments.test.de@cherrygroup.com', $response->getEmail());
        $this->assertSame('LWKQVB7EWEDJC', $response->getPayerId());
        $this->assertSame('verified', $response->getPayerStatus());
        $this->assertSame('Cherry', $response->getFirstName());
        $this->assertSame('Germany', $response->getLastName());
        $this->assertSame('DE', $response->getCountryCode());
        $this->assertSame('Cherry Germany', $response->getShipToName());
        $this->assertSame('ESpachstr. 1', $response->getShipToStreet());
        $this->assertSame('Freiburg', $response->getShipToCity());
        $this->assertSame('Empty', $response->getShipToState());
        $this->assertSame('79111', $response->getShipToZip());
        $this->assertSame('DE', $response->getShipToCountryCode());
        $this->assertSame('Unconfirmed', $response->getAddressStatus());
        $this->assertSame('None', $response->getAddressNormalizationStatus());
        $this->assertSame('20.12', $response->getAmount());
        $this->assertSame('SEK', $response->getCurrency());
        $this->assertSame('0.00', $response->getShippingAmount());
        $this->assertSame('0.00', $response->getInsuranceAmount());
        $this->assertSame('0.00', $response->getShippingDiscountAmount());
        $this->assertSame(false, $response->getInsuranceOptionOffered());
        $this->assertSame('0.00', $response->getHandlingAmount());
        $this->assertSame('0.00', $response->getTaxAmount());
        $this->assertSame('payp-payp277094', $response->getTransactionId());
    }
}
