<?php

namespace Omnipay\PayPal\Message;

use DateTime;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * PayPal Response
 *
 * @author Adrian Macneil <adrian@adrianmacneil.com>
 * @author Joao Dias <joao.dias@cherrygroup.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class Response extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        parse_str($data, $this->data);
    }

    public function isSuccessful()
    {
        return isset($this->data['ACK'])
            && in_array($this->data['ACK'], array('Success', 'SuccessWithWarning'));
    }

    public function getTransactionReference()
    {
        foreach (array('REFUNDTRANSACTIONID', 'TRANSACTIONID', 'PAYMENTINFO_0_TRANSACTIONID') as $key) {
            if (isset($this->data[$key])) {
                return $this->data[$key];
            }
        }
    }

    /**
     * Get the correlation ID, which uniquely identifies the transaction to PayPal.
     *
     * @return string correlation id
     */
    public function getCorrelationId()
    {
        return $this->data['CORRELATIONID'];
    }

    /**
     * Get the date and time that the requested API operation was performed.
     *
     * @return DateTime timestamp
     */
    public function getTimestamp()
    {
        return new DateTime($this->data['TIMESTAMP']);
    }

    /**
     * Get the version of the API.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->data['VERSION'];
    }

    /**
     * Get the sub-version of the API.
     *
     * @return int
     */
    public function getVersionBuild()
    {
        return (int) $this->data['BUILD'];
    }

    /**
     * Get the payment error code.
     *
     * @return int
     */
    public function getCode()
    {
        // @todo parse list
        foreach (array('L_ERRORCODE0', 'PAYMENTREQUESTINFO_0_ERRORCODE') as $key) {
            if (isset($this->data[$key])) {
                return (int) $this->data[$key];
            }
        }
        return 0;
    }

    public function getMessage()
    {
        return isset($this->data['L_LONGMESSAGE0']) ? $this->data['L_LONGMESSAGE0'] : null;
    }
}
