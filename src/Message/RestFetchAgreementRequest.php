<?php
/**
 * PayPal REST Fetch Transaction Request
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST Fetch Agreement Request
 *
 * To get details of Agreement created by a create subscription request, PayPal provides the /billing-agreements
 *
 * Example -- note this example assumes that the subscription creation request has been successful
 * and that there is already an agreement recorded in Paypal
 *
 * <code>
 *   // Fetch the transaction so that details can be found for refund, etc.
 *   $transaction = $gateway->fetchAgreement();
 *   $transaction->setAgreementId($agreementId);
 *   $response = $transaction->send();
 *   $data = $response->getData();
 *   echo "Gateway fetchAgreement response data == " . print_r($data, true) . "\n";
 * </code>
 *
 * @see RestCreateSubscriptionRequest
 * @see Omnipay\PayPal\RestGateway
 * @link https://developer.paypal.com/docs/api/payments.billing-agreements#agreement_get
 */
class RestFetchAgreementRequest extends AbstractRestRequest
{
    public function getData()
    {
        $this->validate('agreementId');
        return array();
    }

    /**
     * Get the agreement id
     *
     * @return mixed
     */
    public function getAgreementId()
    {
        return $this->getParameter('agreementId');
    }

    /**
     * Set the agreement id
     *
     * @param $value
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setAgreementId($value)
    {
        return $this->setParameter('agreementId', $value);
    }

    /**
     * Get HTTP Method.
     *
     * The HTTP method for fetchTransaction requests must be GET.
     * Using POST results in an error 500 from PayPal.
     *
     * @return string
     */
    protected function getHttpMethod()
    {
        return 'GET';
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/payments/billing-agreement/' . $this->getAgreementId();
    }
}
