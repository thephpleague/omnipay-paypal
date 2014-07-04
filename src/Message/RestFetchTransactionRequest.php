<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST Fetch Transaction Request
 */
class RestFetchTransactionRequest extends AbstractRestRequest
{
    public function getData()
    {
        $this->validate('transactionReference');
        return array();
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/payments/sale/' . $this->getTransactionReference();
    }
}
