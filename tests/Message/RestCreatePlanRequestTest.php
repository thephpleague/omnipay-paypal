<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Tests\TestCase;

class RestCreatePlanRequestTest extends TestCase
{
    /** @var \Omnipay\PayPal\Message\RestFetchTransactionRequest */
    private $request;

    public function setUp()
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();
        $this->request = new RestCreatePlanRequest($client, $request);

        $this->request->initialize(array(
            'name'                  => 'Super Duper Billing Plan',
            'type'                  => \Omnipay\PayPal\RestGateway::BILLING_PLAN_TYPE_FIXED,
            'paymentDefinitions'    => array(
                array(
                    'name'                  => 'Monthly Payments',
                    'type'                  => \Omnipay\PayPal\RestGateway::PAYMENT_REGULAR,
                    'frequency'             => \Omnipay\PayPal\RestGateway::BILLING_PLAN_FREQUENCY_MONTH,
                    'frequency_interval'    => 1,
	                'cycles'                => 12,
	                'amount'                => array(
                        'value'     => 10.00,
                        'currency'  => 'USD',
                    )
                )
            ),
            'merchantPrefrences'    => array(),
        ));
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertEquals('Super Duper Billing Plan', $data['name']);
        $this->request->setTransactionReference('ABC-123');
        $this->assertStringEndsWith('/payments/sale/ABC-123', $this->request->getEndpoint());
    }

    public function testGetEndpoint()
    {
        $endpoint = $this->request->getEndpoint();
        $this->assertStringEndsWith('/payments/billing-plans', $endpoint);
    }
}
