<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST Capture Request
 */
class RestCaptureRequest extends AbstractRestRequest
{
    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        return array(
            'amount' => array(
                'currency' => $this->getCurrency(),
                'total' => $this->getAmount(),
            ),
            'is_final_capture' => true,
        );
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/payments/authorization/' . $this->getTransactionReference() . '/capture';
    }
}
