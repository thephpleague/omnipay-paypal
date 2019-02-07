<?php

namespace Omnipay\PayPal\Message;

/**
 * https://developer.paypal.com/docs/api/webhooks/#verify-webhook-signature
 */
final class RestVerifyWebhookSignatureResponse extends RestResponse
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        if (!parent::isSuccessful()) {
            return false;
        }

        return 'SUCCESS' === $this->getVerificationStatus();
    }

    /**
     * The status of the signature verification. Value is `SUCCESS` or `FAILURE`.
     *
     * @return string
     */
    public function getVerificationStatus()
    {
        return $this->data['verification_status'];
    }
}
