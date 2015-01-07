<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST Delete Card Request
 */
class RestDeleteCardRequest extends AbstractRestRequest
{
    public function getHttpMethod()
    {
        return 'DELETE';
    }

    public function getData()
    {
        $this->validate('cardReference');
        return array();
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/vault/credit-card/' . $this->getCardReference();
    }
}
