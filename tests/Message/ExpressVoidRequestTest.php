<?php

namespace Omnipay\PayPal\Message;

use League\Omnipay\Common\CreditCard;
use League\Omnipay\Tests\TestCase;

class ExpressVoidRequestTest extends TestCase
{
    /**
     * @var ExpressVoidRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new ExpressVoidRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'transactionReference' => 'ASDFASDFASDF',
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('ASDFASDFASDF', $data['AUTHORIZATIONID']);
        $this->assertSame('DoVoid', $data['METHOD']);
    }
}
