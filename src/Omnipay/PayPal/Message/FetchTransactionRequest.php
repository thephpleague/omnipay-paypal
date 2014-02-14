<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Fetch Transaction Request
 */
class FetchTransactionRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->getBaseData('GetTransactionDetails');

        $this->validate('transactionReference');

        $data['TRANSACTIONID'] = $this->getTransactionReference();

        return $data;
    }
}
