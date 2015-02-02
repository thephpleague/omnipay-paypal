<?php
/**
 * PayPal REST Refund Request
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST Refund Request
 *
 * To get details about completed payments (sale transaction) created by a payment request
 * or to refund a direct sale transaction, PayPal provides the /sale resource and related
 * sub-resources.
 *
 * TODO: There might be a problem here, in that refunding a capture requires a different URL.
 *
 * TODO: Yes I know. The gateway doesn't yet support looking up or refunding captured
 * transactions.  That will require adding additional message classes because the URLs
 * are all different.
 *
 * A non-zero amount can be provided for the refund using setAmount(), if this is not
 * provided (or is zero) then a full refund is made.
 *
 * TODO: There is a bug here.  Not providing a refund amount fails with a MALFORMED_REQUEST
 * error from the gateway.  This is because empty JSON data ([]) causes the Guzzle HTTP
 * client to emit a file called [] rather than actually sending the data along with the
 * HTTP POST request, causing an error at the PayPal gateway.
 */
class RestRefundRequest extends AbstractRestRequest
{
    public function getData()
    {
        $this->validate('transactionReference');

        if ($this->getAmount() > 0) {
            return array(
                'amount' => array(
                    'currency' => $this->getCurrency(),
                    'total' => $this->getAmount(),
                ),
                'description' => $this->getDescription(),
            );
        } else {
            return array();
        }
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/payments/sale/' . $this->getTransactionReference() . '/refund';
    }
}
