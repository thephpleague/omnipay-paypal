<?php
/**
 * PayPal REST Complete Purchase Request
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST Complete Purchase Request
 *
 * Use this message to execute (complete) a PayPal payment that has been
 * approved by the payer. You can optionally update transaction information
 * when executing the payment by passing in one or more transactions.
 *
 * This call only works after a buyer has approved the payment using the
 * provided PayPal approval URL.
 *
 * Example -- note this example assumes that the purchase has been successful
 * and that the payer ID returned from the callback after the purchase is held
 * in $payer_id, and that the transaction ID returned by the initial payment is
 * held in $sale_id
 * See RestPurchaseRequest for the first part of this example transaction:
 *
 * <code>
 *   // Once the transaction has been approved, we need to complete it.
 *   $transaction = $gateway->completePurchase(array(
 *       'payer_id'             => $payer_id,
 *       'transactionReference' => $sale_id,
 *   ));
 *   $response = $transaction->send();
 * </code>
 *
 * @see RestPurchaseRequest
 * @link https://developer.paypal.com/docs/api/#execute-an-approved-paypal-payment
 */
class RestCompletePurchaseRequest extends AbstractRestRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('transactionReference', 'payerId');

        $data = array(
            'payer_id' => $this->getPayerId()
        );

        return $data;
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/payments/payment/' . $this->getTransactionReference() . '/execute';
    }
}
