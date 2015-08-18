<?php
/**
 * PayPal REST Authorize Response
 */

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * PayPal REST Authorize Response
 */
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
        if (isset($this->data['links']) && is_array($this->data['links'])) {
            foreach ($this->data['links'] as $key => $value) {
                if ($value['rel'] == 'approval_url') {
                    return $value['href'];
                }
            }
        }

        return null;
    }

    /**
     * Get the required redirect method (either GET or POST).
     *
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * Gets the redirect form data array, if the redirect method is POST.
     *
     * @return null
     */
    public function getRedirectData()
    {
        return null;
    }
}
