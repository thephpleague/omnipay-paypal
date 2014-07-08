<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class RestUpdateCardRequestTest extends RestCreateCardRequestTest
{
    /** @var RestUpdateCardRequest */
    protected $request;

    /** @var CreditCard */
    protected $card;

    public function setUp()
    {
        parent::setUp();

        $this->request = new RestUpdateCardRequest($this->getHttpClient(), $this->getHttpRequest());

        $card = $this->getValidCard();
        $this->card = new CreditCard($card);

        $this->request->initialize(array(
            'card' => $card,
            'cardReference' => 'CARD-TEST123',
        ));
    }

    public function testEndpoint()
    {
        $this->assertStringEndsWith('/vault/credit-card/CARD-TEST123', $this->request->getEndpoint());
    }
}
