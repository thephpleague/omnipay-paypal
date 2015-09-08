<?php
/**
 * PayPal REST List Purchase Request
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST List Purchase Request
 *
 * Use this call to get a list of payments in any state (created, approved,
 * failed, etc.). The payments returned are the payments made to the merchant
 * making the call.
 *
 * ### Example
 *
 * See RestPurchaseRequest for the first part of this example transaction:
 *
 * <code>
 *   // List the transaction so that details can be found for refund, etc.
 *   $transaction = $gateway->listPurchase();
 *   $response = $transaction->send();
 *   $data = $response->getData();
 *   echo "Gateway listPurchase response data == " . print_r($data, true) . "\n";
 * </code>
 *
 * @see RestPurchaseRequest
 * @link https://developer.paypal.com/docs/api/#list-payment-resources
 */
class RestListPurchaseRequest extends AbstractRestRequest
{
    /**
     * Get the request count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->getParameter('count');
    }

    /**
     * Set the request count
     *
     * @param integer $value
     * @return AbstractRestRequest provides a fluent interface.
     */
    public function setCount($value)
    {
        return $this->setParameter('count', $value);
    }

    /**
     * Get the request startId
     *
     * @return string
     */
    public function getStartId()
    {
        return $this->getParameter('startId');
    }

    /**
     * Set the request startId
     *
     * @param string $value
     * @return AbstractRestRequest provides a fluent interface.
     */
    public function setStartId($value)
    {
        return $this->setParameter('startId', $value);
    }

    /**
     * Get the request startIndex
     *
     * @return integer
     */
    public function getStartIndex()
    {
        return $this->getParameter('startIndex');
    }

    /**
     * Set the request startIndex
     *
     * @param integer $value
     * @return AbstractRestRequest provides a fluent interface.
     */
    public function setStartIndex($value)
    {
        return $this->setParameter('startIndex', $value);
    }

    /**
     * Get the request startTime
     *
     * @return string
     */
    public function getStartTime()
    {
        return $this->getParameter('startTime');
    }

    /**
     * Set the request startTime
     *
     * @param string $value
     * @return AbstractRestRequest provides a fluent interface.
     */
    public function setStartTime($value)
    {
        return $this->setParameter('startTime', $value);
    }

    /**
     * Get the request endTime
     *
     * @return string
     */
    public function getEndTime()
    {
        return $this->getParameter('endTime');
    }

    /**
     * Set the request endTime
     *
     * @param string $value
     * @return AbstractRestRequest provides a fluent interface.
     */
    public function setEndTime($value)
    {
        return $this->setParameter('endTime', $value);
    }

    public function getData()
    {
        return array(
            'count'             => $this->getCount(),
            'start_id'          => $this->getStartId(),
            'start_index'       => $this->getStartIndex(),
            'start_time'        => $this->getStartTime(),
            'end_time'          => $this->getEndTime(),
        );
    }

    /**
     * Get HTTP Method.
     *
     * The HTTP method for listPurchase requests must be GET.
     *
     * @return string
     */
    protected function getHttpMethod()
    {
        return 'GET';
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/payments/payment';
    }
}
