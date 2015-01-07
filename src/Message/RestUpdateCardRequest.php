<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST Update Card Request
 */
class RestUpdateCardRequest extends RestCreateCardRequest
{
    public function getEndpoint()
    {
        return parent::getEndpoint() . '/vault/credit-card/' . $this->getCardReference();
    }

    public function getHttpMethod()
    {
        return 'PATCH';
    }
}
