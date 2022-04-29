<?php
/**
 * PayPal Subscriptions Create Plan Request
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal Subscriptions Create Plan Request
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
 * @link https://developer.paypal.com/docs/api/#create-a-plan
 * @see Omnipay\PayPal\RestGateway
 */
class SubscriptionsCreatePlanRequest extends AbstractRestRequest
{
    /**
     * Get the plan name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getParameter('name');
    }

    /**
     * Set the plan name
     *
     * @param string $value
     * @return SubscriptionsCreatePlanRequest provides a fluent interface.
     */
    public function setName($value)
    {
        return $this->setParameter('name', $value);
    }

    /**
     * Get the product id
     *
     * @return string
     */
    public function getProductId()
    {
        return $this->getParameter('productId');
    }

    /**
     * Set the plan name
     *
     * @param string $value
     * @return SubscriptionsCreatePlanRequest provides a fluent interface.
     */
    public function setProductId($value)
    {
        return $this->setParameter('productId', $value);
    }

    /**
     * Get the billing cycles
     *
     * @return array
     */
    public function getBillingCycles()
    {
        return $this->getParameter('billing_cycles');
    }

    /**
     * Set the billing cycles
     *
     * @param array $value
     * @return SubscriptionsCreatePlanRequest provides a fluent interface.
     */
    public function setBillingCycles(array $value)
    {
        return $this->setParameter('billing_cycles', $value);
    }

    /**
     * Get the payment preferences
     *
     * @return array
     */
    public function getPaymentPreferences()
    {
        return $this->getParameter('payment_preferences');
    }

    /**
     * Set the payment preferences
     *
     * @param array $value
     * @return SubscriptionsCreatePlanRequest provides a fluent interface.
     */
    public function setPaymentPreferences(array $value)
    {
        return $this->setParameter('payment_preferences', $value);
    }

    /**
     * Get the taxes
     *
     * @return array
     */
    public function getTaxes()
    {
        return $this->getParameter('taxes');
    }

    /**
     * Set the taxes
     *
     * @param array $value
     * @return SubscriptionsCreatePlanRequest provides a fluent interface.
     */
    public function setTaxes(array $value)
    {
        return $this->setParameter('taxes', $value);
    }

    /**
     * Get the quantity supported
     *
     * @return bool
     */
    public function getQuantitySupported()
    {
        return $this->getParameter('quantity_supported');
    }

    /**
     * Set the quantity supported
     *
     * @param bool $value
     * @return SubscriptionsCreatePlanRequest provides a fluent interface.
     */
    public function setQuantitySupported($value)
    {
        return $this->setParameter('quantity_supported', $value);
    }

    /**
     * Get the plan period
     *
     * @return string
     */
    public function getPeriod()
    {
        return $this->getParameter('period');
    }

    /**
     * Set the plan period
     *
     * @param string $value
     * @return SubscriptionsCreatePlanRequest provides a fluent interface.
     */
    public function setPeriod($value)
    {
        return $this->setParameter('period', $value);
    }

    /**
     * Get the plan cost
     *
     * @return string
     */
    public function getCost()
    {
        return $this->getParameter('cost');
    }

    /**
     * Set the plan cost
     *
     * @param string $value
     * @return SubscriptionsCreatePlanRequest provides a fluent interface.
     */
    public function setCost($value)
    {
        return $this->setParameter('cost', $value);
    }

    public function getData()
    {
        $this->validate('name');

        $data = [
            'product_id' => $this->getProductId(),
            'name'       => $this->getName(),
            'billing_cycles' => $this->getBillingCycles() ?: [
                [
                    'frequency' => [
                        'interval_unit' => $this->getPeriod(),
                    ],
                    'tenure_type' => 'REGULAR',
                    'sequence' => 1,
                    'total_cycles' => 0,
                    'pricing_scheme' => [
                        'fixed_price' => [
                            'value' => $this->getCost(),
                            'currency_code' => "USD",
                        ]
                    ]
                ]
            ],
            'payment_preferences' => $this->getPaymentPreferences() ?: [
                'auto_bill_outstanding' => true,
                'payment_failure_threshold' => 1
            ],
            'taxes' => $this->getTaxes() ?: [
                'percentage' => '0',
                'inclusive' => true
            ],
            'quantity_supported' => $this->getQuantitySupported() ?: false,
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
        return parent::getEndpoint() . '/billing/plans';
    }
}
