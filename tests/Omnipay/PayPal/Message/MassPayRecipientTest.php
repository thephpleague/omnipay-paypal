<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Tests\TestCase;

class MassPayRecipientTest extends TestCase
{
    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     */
    public function testMissingPayerInfo()
    {
        $recipient = new MassPayRecipient(array(
            'amount' => '12.34',
        ));
        $recipient->validate();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     */
    public function testMissingAmount()
    {
        $recipient = new MassPayRecipient(array(
            'payerId' => 'payer-id',
        ));
        $recipient->validate();
    }
}
