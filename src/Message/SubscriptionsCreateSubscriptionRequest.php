<?php
/**
 * PayPal Subscriptions Create Subscription Request
 */

namespace Omnipay\PayPal\Message;

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
class SubscriptionsCreateSubscriptionRequest extends AbstractRestRequest
{
    /**
     * Get the subscription plan id
     *
     * @return string
     */
    public function getPlanId()
    {
        return $this->getParameter('plan_id');
    }

    /**
     * Set the subscription plan id
     *
     * @param string $value
     * @return SubscriptionsCreateSubscriptionRequest provides a fluent interface.
     */
    public function setPlanId($value)
    {
        return $this->setParameter('plan_id', $value);
    }

    /**
     * Get the subscriber
     *
     * @return array
     */
    public function getSubscriber()
    {
        return $this->getParameter('subscriber');
    }

    /**
     * Set the subscriber
     *
     * @param array $value
     * @return SubscriptionsCreateSubscriptionRequest provides a fluent interface.
     */
    public function setSubscriber(array $value)
    {
        return $this->setParameter('subscriber', $value);
    }

    /**
     * Get the application context
     *
     * @return array
     */
    public function getApplicationContext()
    {
        return $this->getParameter('application_context');
    }

    /**
     * Set the application context
     *
     * @param array $value
     * @return SubscriptionsCreateSubscriptionRequest provides a fluent interface.
     */
    public function setApplicationContext(array $value)
    {
        return $this->setParameter('application_context', $value);
    }

    /**
     * Get the subscriber email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * Set the subscriber email
     *
     * @param string $value
     * @return SubscriptionsCreateSubscriptionRequest provides a fluent interface.
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    /**
     * Get the subscription brand name
     *
     * @return string
     */
    public function getBrandName()
    {
        return $this->getParameter('brand_name');
    }

    /**
     * Set the subscription brand name
     *
     * @param string $value
     * @return SubscriptionsCreateSubscriptionRequest provides a fluent interface.
     */
    public function setBrandName($value)
    {
        return $this->setParameter('brand_name', $value);
    }

    /**
     * Get the return url
     *
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->getParameter('return_url');
    }

    /**
     * Set the return url
     *
     * @param string $value
     * @return SubscriptionsCreateSubscriptionRequest provides a fluent interface.
     */
    public function setReturnUrl($value)
    {
        return $this->setParameter('return_url', $value);
    }

    /**
     * Get the cancel url
     *
     * @return string
     */
    public function getCancelUrl()
    {
        return $this->getParameter('cancel_url');
    }

    /**
     * Set the cancel url
     *
     * @param string $value
     * @return SubscriptionsCreateSubscriptionRequest provides a fluent interface.
     */
    public function setCancelUrl($value)
    {
        return $this->setParameter('cancel_url', $value);
    }

    public function getData()
    {
        $data = [
            'plan_id'             => $this->getPlanId(),
            'subscriber'          => $this->getSubscriber() ?: [
                'email_address'   => $this->getEmail(),
            ],
            'application_context' => $this->getApplicationContext() ?: [
                'brand_name' => $this->getBrandName(),
                'shipping_preferences' => 'NO_SHIPPING',
                'user_action' => 'SUBSCRIBE_NOW',
                'payment_method' => [
                    'payer_selected' => 'PAYPAL',
                    'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED',
                ],
                'return_url' => $this->getReturnUrl(),
                'cancel_url' => $this->getCancelUrl(),
            ],
        ];

        return $data;
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
        return parent::getEndpoint() . '/billing/subscriptions';
    }

    protected function createResponse($data, $statusCode)
    {
        return $this->response = new SubscriptionsAuthorizeResponse($this, $data, $statusCode);
    }
}

