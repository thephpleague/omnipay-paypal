<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Express Complete Payment Response
 */
class ExpressCompletePurchaseResponse extends ExpressAuthorizeResponse
{
    public function isRedirect()
    {
        return isset($this->data['L_ERRORCODE0']) && in_array($this->data['L_ERRORCODE0'], array('10486'));
    }
}
