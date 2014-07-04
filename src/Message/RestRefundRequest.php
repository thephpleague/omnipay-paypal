<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST Refund Request
 * TODO: There might be a problem here, in that refunding a capture requires a different URL.
 */
class RestRefundRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('transactionReference');

        if ($this->getAmount() > 0) {
            return array(
                'amount' => array(
                    'currency' => $this->getCurrency(),
                    'total' => $this->getAmount(),
                ),
                'description' => $this->getDescription(),
            );
        } else {
            return array();
        }
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/payments/sale/' . $this->getTransactionReference() . '/refund';
    }
}
