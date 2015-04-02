<?php

namespace Omnipay\PayPal\Message;

class ExpressVoidRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('transactionReference');

        $data = $this->getBaseData();
        $data['METHOD'] = 'DoVoid';
        $data['TRANSACTIONID'] = $this->getTransactionReference();

        return $data;
    }
}
