<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Mass Pay Request
 *
 * The MassPay API operation makes a payment to one or more PayPal account holders.
 * @see https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/MassPay_API_Operation_NVP/
 *
 * @author Joao Dias <joao.dias@cherrygroup.com>
 * @copyright 2014 Cherry Ltd.
 * @license http://opensource.org/licenses/mit-license.php MIT
 * @version 112.0 PayPal Merchant API
 */
class MassPayRequest extends AbstractRequest
{
    public function getOperation()
    {
        return 'MassPay';
    }

    public function getVersion()
    {
        return '112.0';
    }

    /**
     * Get the subject line of the email that PayPal sends when the transaction completes.
     *
     * @return string email subject
     */
    public function getEmailSubject()
    {
        return $this->getParameter('emailSubject');
    }

    /**
     * Set the subject line of the email that PayPal sends when the transaction completes.
     *
     * The subject line is the same for all recipients.
     *
     * @param  string $value email subject
     * @return self
     */
    public function setEmailSubject($value)
    {
        return $this->setParameter('emailSubject', $value);
    }

    /**
     * Get how the merchant identify the recipients of payments in this call to MassPay.
     *
     * @return string receiver type
     */
    public function getReceiverType()
    {
        return $this->getParameter('receiverType');
    }

    /**
     * Set how the merchant identify the recipients of payments in this call to MassPay.
     *
     * It is one of the following values: EmailAddress, UserID, PhoneNumber.
     * It is the same for all recipients.
     *
     * @param  string $value receiver type
     * @return self
     */
    public function setReceiverType($value)
    {
        return $this->setParameter('receiverType', $value);
    }

    /**
     * Get the recipients.
     *
     * @return MassPayRecipient[] recipients
     */
    public function getRecipients()
    {
        return $this->getParameter('recipients');
    }

    /**
     * Set the recipients.
     *
     * @param  MassPayRecipient[] $value recipients
     * @return self
     */
    public function setRecipients(array $value)
    {
        return $this->setParameter('recipients', $value);
    }

    /**
     * Set the recipient for MassPay in case there is only one.
     *
     * @param  MassPayRecipient $value recipient
     * @return self
     */
    public function setRecipient(MassPayRecipient $value)
    {
        return $this->setRecipients(array($value));
    }

    public function getData()
    {
        $data = parent::getData();

        $this->validate('currency', 'recipients');

        $data['EMAILSUBJECT'] = $this->getEmailSubject();
        $data['CURRENCYCODE'] = $this->getCurrency();
        $data['RECEIVERTYPE'] = $this->getReceiverType();

        $recipients = $this->getRecipients();
        for ($i = 0; $i < count($recipients); $i++) {
            $recipients[$i]->validate();
            $data['L_EMAIL' . $i] = $recipients[$i]->getEmail();
            $data['L_RECEIVERPHONE' . $i] = $recipients[$i]->getPhone();
            $data['L_RECEIVERID' . $i] = $recipients[$i]->getPayerId();
            $data['L_AMT' . $i] = $recipients[$i]->getAmount();
            $data['L_UNIQUEID' . $i] = $recipients[$i]->getTransactionId();
            $data['L_NOTE' . $i] = $recipients[$i]->getNote();
        }

        return $data;
    }

    /**
     * @return MassPayResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new MassPayResponse($this, $data);
    }
}
