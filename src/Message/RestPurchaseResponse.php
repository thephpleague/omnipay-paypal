<?php
namespace Omnipay\PayPal\Message;

class RestPurchaseResponse extends RestAuthorizeResponse
{
    public function isSuccessful()
    {
        return false;
    }
}
