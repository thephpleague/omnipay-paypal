<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Helper;
use Omnipay\Common\Exception\InvalidRequestException;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * PayPal Mass Pay Recipient
 *
 * @author Joao Dias <joao.dias@cherrygroup.com>
 * @copyright 2014 Cherry Ltd.
 * @license http://opensource.org/licenses/mit-license.php MIT
 * @version 112.0 PayPal Merchant API
 */
class MassPayRecipient
{
    /**
     * @var ParameterBag
     */
    protected $parameters;

    /**
     * Create a new MassPayRecipient object using the specified parameters.
     *
     * @param array $parameters An array of parameters to set on the new object.
     */
    public function __construct($parameters = null)
    {
        $this->initialize($parameters);
    }

    /**
     * Initialize the object with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param  array $parameters An associative array of parameters.
     * @return self
     */
    public function initialize($parameters = null)
    {
        $this->parameters = new ParameterBag();
        Helper::initialize($this, $parameters);
        return $this;
    }

    /**
     * Get the value of a parameter.
     *
     * @param  mixed $key parameter key
     * @return mixed      parameter value
     */
    protected function getParameter($key)
    {
        return $this->parameters->get($key);
    }

    /**
     * Set the value of a parameter.
     *
     * @param  mixed $key   parameter key
     * @param  mixed $value parameter value
     * @return self
     */
    protected function setParameter($key, $value)
    {
        $this->parameters->set($key, $value);
        return $this;
    }

    /**
     * Get the email address of recipient.
     *
     * @return string email
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * Set the email address of recipient.
     *
     * @param  string $value email
     * @return self
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    /**
     * Get the phone number of recipient.
     *
     * @return string phone
     */
    public function getPhone()
    {
        return $this->getParameter('phone');
    }

    /**
     * Set the phone number of recipient.
     *
     * @param  string $value phone
     * @return self
     */
    public function setPhone($value)
    {
        return $this->setParameter('phone', $value);
    }

    /**
     * Get the unique PayPal customer account number.
     *
     * @return string payer id
     */
    public function getPayerId()
    {
        return $this->getParameter('payerId');
    }

    /**
     * Set the unique PayPal customer account number.
     *
     * This value corresponds to the value of PayerId returned by GetTransactionDetails.
     *
     * @param  string $value payer id
     * @return self
     */
    public function setPayerId($value)
    {
        return $this->setParameter('payerId', $value);
    }

    /**
     * Get the payment amount.
     *
     * @return string amount
     */
    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    /**
     * Set the payment amount.
     *
     * @param  string $value amount
     * @return self
     */
    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    /**
     * Get the transaction-specific identification number for tracking in an accounting
     * system.
     *
     * @return string transaction id
     */
    public function getTransactionId()
    {
        return $this->getParameter('transactionId');
    }

    /**
     * Set the transaction-specific identification number for tracking in an accounting
     * system.
     *
     * @param  string $value transaction id
     * @return self
     */
    public function setTransactionId($value)
    {
        return $this->setParameter('transactionId', $value);
    }

    /**
     * Get the custom note for each recipient.
     *
     * @return string note
     */
    public function getNote()
    {
        return $this->getParameter('note');
    }

    /**
     * Set the custom note for each recipient.
     *
     * @param  string $value note
     * @return self
     */
    public function setNote($value)
    {
        return $this->setParameter('note', $value);
    }

    /**
     * Make sure that the required fields are set.
     *
     * @throws InvalidRequestException if a required parameter is not set.
     */
    public function validate()
    {
        $email = $this->getEmail();
        $phone = $this->getPhone();
        $payerId = $this->getPayerId();
        if (empty($email) && empty($phone) && empty($payerId)) {
            throw new InvalidRequestException('One of the \'email\', \'phone\' or \'payerId\' parameters is required');
        }

        $amount = $this->getAmount();
        if (empty($amount)) {
            throw new InvalidRequestException('The \'amount\' parameter is required');
        }
    }
}
