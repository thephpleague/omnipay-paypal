<?php
/**
 * PayPal REST Execute Request
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST Execute Request
 *
 * This is used to execute payment requests for payer.payment_method = paypal.
 *
 * When the user approves the payment, PayPal redirects the user to the return_url
 * that was specified when the payment was created. A payer Id and payment Id
 * are appended to the return URL, as PayerID and paymentId:
 *
 * http://<return_url>?paymentId=PAY-6RV70583SB702805EKEYSZ6Y&token=EC-60U79048BN7719609&PayerID=7E7MGXCWTTKK2
 *
 * The token value appended to the return URL is not needed when you execute
 * the payment.
 *
 * To execute the payment after the user’s approval, make a /payment/execute/ call.
 * In the body of the request, use the payer_id value that was appended to the
 * return URL. In the header, use the access token that you used when you created
 * the payment.
 *
 * Example:
 *
 * <code>
 *   $my_payment_id = $_GET['paymentId'];
 *   $my_payer_id = $_GET['PayerID'];
 *   $transaction = $gateway->execute()
 *       ->setPaymentId($my_payment_id)
 *       ->setPayerId($my_payer_id);
 *   $response = $transaction->send();
 *   $data = $response->getData();
 *   echo "Gateway execute response data == " . print_r($data, true) . "\n";
 * </code>
 *
 * TODO: I'm not sure how this works for PayPal authorizations.
 *
 * TODO: Need a test case.
 *
 * @link https://developer.paypal.com/docs/integration/direct/capture-payment/#authorize-the-payment
 * @link https://developer.paypal.com/docs/api/#execute-an-approved-paypal-payment
 * @see RestAuthorizeRequest
 * @see RestPurchaseRequest
 */
class RestExecuteRequest extends AbstractRestRequest
{
    public function getData()
    {
        // The only data required to be sent with the execute request is
        // the payer ID.  The payment ID is part of the execute request URL.
        $this->validate('payerId');
        $data = array('payer_id' => $this->getPayerId());

        return $data;
    }

    /**
     * Get Payer ID
     *
     * A payer Id is appended to the return URL, as PayerID
     *
     * @return string
     */
    public function getPayerId()
    {
        return $this->getParameter('payerId');
    }

    /**
     * Set Payer ID
     *
     * A payer Id is appended to the return URL, as PayerID
     *
     * @return RestExecuteRequest provides a fluent interface.
     */
    public function setPayerId($value)
    {
        return $this->setParameter('payerId', $value);
    }

    /**
     * Get Payment ID
     *
     * A payment Id is appended to the return URL, as paymentID
     *
     * @return string
     */
    public function getPaymentId()
    {
        return $this->getParameter('paymentId');
    }

    /**
     * Set Payment ID
     *
     * A payment Id is appended to the return URL, as paymentID
     *
     * @return RestExecuteRequest provides a fluent interface.
     */
    public function setPaymentId($value)
    {
        return $this->setParameter('paymentId', $value);
    }

    /**
     * Get transaction description.
     *
     * The REST API does not currently have support for passing an invoice number
     * or transaction ID.
     *
     * @return string
     */
    public function getDescription()
    {
        $id = $this->getTransactionId();
        $desc = parent::getDescription();

        if (empty($id)) {
            return $desc;
        } elseif (empty($desc)) {
            return $id;
        } else {
            return "$id : $desc";
        }
    }

    /**
     * Get transaction endpoint.
     *
     * Authorization of payments is done using the /payment resource.
     *
     * @return string
     */
    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/payments/payment/' . $this->getPaymentId() . '/execute';
    }
}
