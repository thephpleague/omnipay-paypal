<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST List Webhooks request
 *
 * @link https://developer.paypal.com/docs/api/webhooks/#webhooks_get-all
 */
final class RestListWebhooksRequest extends AbstractRestRequest
{
    /**
     * @inheritDoc
     */
    public function getData()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint()
    {
        return parent::getEndpoint().'/notifications/webhooks';
    }

    /**
     * Get HTTP Method.
     *
     * @return string
     */
    protected function getHttpMethod()
    {
        return 'GET';
    }
}
