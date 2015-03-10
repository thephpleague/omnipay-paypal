<?php
namespace Omnipay\PayPal\Message;


use Omnipay\Common\Message\RedirectResponseInterface;

class RestAuthorizeResponse extends RestResponse implements RedirectResponseInterface
{
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

    /**
     * Get the required redirect method (either GET or POST).
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * Gets the redirect form data array, if the redirect method is POST.
     */
    public function getRedirectData()
    {
        return null;
    }
}
