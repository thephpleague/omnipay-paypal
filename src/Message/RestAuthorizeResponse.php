<?php
namespace Omnipay\PayPal\Message;


class RestAuthorizeResponse extends RestResponse {
    public function isSuccessful()
    {
        return empty($this->data['error']) && $this->getCode() == 201;
    }

    public function isRedirect()
    {
        return $this->getRedirectUrl() !== null;
    }

    public function getRedirectUrl()
    {
        $redirectUrl = null;
        if (isset($this->data['links'][1]) && $this->data['links'][1]['rel'] == 'approval_url') {
            $redirectUrl = $this->data['links'][1]['href'];
        }

        return $redirectUrl;
    }
}