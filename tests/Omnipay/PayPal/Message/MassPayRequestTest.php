<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Tests\TestCase;

class MassPayRequestTest extends TestCase
{
    /**
     * @var MassPayRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new MassPayRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetDataOneRecipient()
    {
        $expectedData = array(
            'username'     => 'phpunit',
            'password'     => 'password',

            'emailSubject' => 'email subject',
            'currency'     => 'USD',
            'receiverType' => 'UserID',
        );
        $this->request->initialize($expectedData);

        $expectedRecipient = new MassPayRecipient(array(
            'payerId'       => '1337',
            'amount'        => '12.34',
            'transactionId' => 'transaction-id',
            'note'          => 'note',
        ));
        $this->request->setRecipient($expectedRecipient);

        $data = $this->request->getData();

        $this->assertSame($expectedData['emailSubject'], $data['EMAILSUBJECT']);
        $this->assertSame($expectedData['currency'], $data['CURRENCYCODE']);
        $this->assertSame($expectedData['receiverType'], $data['RECEIVERTYPE']);

        $this->assertSame($expectedRecipient->getPayerId(), $data['L_RECEIVERID0']);
        $this->assertSame($expectedRecipient->getAmount(), $data['L_AMT0']);
        $this->assertSame($expectedRecipient->getTransactionId(), $data['L_UNIQUEID0']);
        $this->assertSame($expectedRecipient->getNote(), $data['L_NOTE0']);
    }
}
