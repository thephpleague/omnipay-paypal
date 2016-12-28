<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Refund Request
 */
class RefundRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('transactionReference');

        $data = $this->getBaseData();
        $data['METHOD'] = 'RefundTransaction';
        $data['TRANSACTIONID'] = $this->getTransactionReference();
        $data['REFUNDTYPE'] = 'Full';
        if ($this->getAmount() && $this->getAmount()->getInteger() > 0) {
            $data['REFUNDTYPE'] = 'Partial';
            $data['AMT'] = $this->getAmount()->getFormatted();
            $data['CURRENCYCODE'] = $this->getCurrency();
        }

        return $data;
    }
}
