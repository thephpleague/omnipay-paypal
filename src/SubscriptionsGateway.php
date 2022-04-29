<?php
/**
 * PayPal Pro Class using REST API
 */

namespace Omnipay\PayPal;

class SubscriptionsGateway extends RestGateway
{
    public function getName()
    {
        return 'PayPal Subscriptions';
    }

    public function getDefaultParameters()
    {
        return array(
            'clientId'     => '',
            'secret'       => '',
            'token'        => '',
            'productId'    => '',
            'testMode'     => false,
        );
    }

    public function getProductId()
    {
        return $this->getParameter('productId');
    }

    public function setProductId($value)
    {
        return $this->setParameter('productId', $value);
    }

    public function createProduct(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\PayPal\Message\SubscriptionsCreateProductRequest', $parameters);
    }

    public function createPlan(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\PayPal\Message\SubscriptionsCreatePlanRequest', $parameters);
    }

    public function createSubscription(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\PayPal\Message\SubscriptionsCreateSubscriptionRequest', $parameters);
    }

    public function getSubscription(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\PayPal\Message\SubscriptionsGetSubscriptionRequest', $parameters);
    }
}
