<?php
/**
 * PayPal Subscriptions Create Subscription Request
 */

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Exception\RuntimeException;

/**
 * PayPal Subscriptions Create Subscription Request
 *
 *
 * ### Request Data
 *
 *
 * ### Example
 *
 *
 * ### Request Sample
 *
 * ### Response Sample
 *
 *
 * @link https://developer.paypal.com/docs/api/#create-a-subscription
 * @see Omnipay\PayPal\RestGateway
 */
class SubscriptionsGetSubscriptionRequest extends AbstractRestRequest
{
    protected $subscriptionId;

    public function getData()
    {
        return [];
    }

    public function initialize(array $parameters = [])
    {
        if (!empty($parameters['subscription_id'])) {
            $this->subscriptionId = $parameters['subscription_id'];
        }

        return parent::initialize($parameters);
    }

    /**
     * Get transaction endpoint.
     *
     * Billing plans are created using the /billing/plans resource.
     *
     * @return string
     */
    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/billing/subscriptions/' . $this->subscriptionId; 
    }

    protected function getHttpMethod()
    {
        return 'GET';
    }
}
