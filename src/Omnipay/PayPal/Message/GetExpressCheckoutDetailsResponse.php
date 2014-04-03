<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Get Express Checkout Details Response
 *
 * @author Joao Dias <joao.dias@cherrygroup.com>
 * @copyright 2014 Cherry Ltd.
 * @license http://opensource.org/licenses/mit-license.php MIT
 * @version 112.0 PayPal Merchant API
 */
class GetExpressCheckoutDetailsResponse extends Response
{
    /**
     * Get the timestamped token value that was returned by SetExpressCheckout response
     * and passed on GetExpressCheckoutDetails request.
     *
     * @return string token
     */
    public function getToken()
    {
        return $this->data['TOKEN'];
    }

    /**
     * Get the status of the checkout session.
     *
     * If payment is completed, the transaction identification number of the resulting
     * transaction is returned.
     *
     * @return string checkout status
     */
    public function getCheckoutStatus()
    {
        return $this->data['CHECKOUTSTATUS'];
    }

    /**
     * Get the email address of buyer.
     *
     * @return string buyer email
     */
    public function getEmail()
    {
        return $this->data['EMAIL'];
    }

    /**
     * Get the unique PayPal Customer Account identification number.
     *
     * @return string payer id
     */
    public function getPayerId()
    {
        return $this->data['PAYERID'];
    }

    /**
     * Get the status of buyer.
     *
     * @return string payer status
     */
    public function getPayerStatus()
    {
        return $this->data['PAYERSTATUS'];
    }

    /**
     * Get the buyer's country of residence in the form of ISO standard 3166 two-character
     * country codes.
     *
     * @return string country code
     */
    public function getCountryCode()
    {
        return $this->data['COUNTRYCODE'];
    }

    /**
     * Get the buyer's first name.
     *
     * @return string first name
     */
    public function getFirstName()
    {
        return $this->data['FIRSTNAME'];
    }

    /**
     * Get the buyer's last name.
     *
     * @return string last name
     */
    public function getLastName()
    {
        return $this->data['LASTNAME'];
    }

    /**
     * Get the person's name associated with this shipping address.
     *
     * @return string ship to name
     */
    public function getShipToName()
    {
        // @todo parse list
        return $this->data['PAYMENTREQUEST_0_SHIPTONAME'];
    }

    /**
     * Get the first street address.
     *
     * @return string ship to street
     */
    public function getShipToStreet()
    {
        // @todo parse list
        return $this->data['PAYMENTREQUEST_0_SHIPTOSTREET'];
    }

    /**
     * Get the name of city.
     *
     * @return string ship to city
     */
    public function getShipToCity()
    {
        // @todo parse list
        return $this->data['PAYMENTREQUEST_0_SHIPTOCITY'];
    }

    /**
     * Get the state or province.
     *
     * @return string ship to state
     */
    public function getShipToState()
    {
        // @todo parse list
        return $this->data['PAYMENTREQUEST_0_SHIPTOSTATE'];
    }

    /**
     * Get the U.S. ZIP code or other country-specific postal code.
     *
     * @return string ship to zip
     */
    public function getShipToZip()
    {
        // @todo parse list
        return $this->data['PAYMENTREQUEST_0_SHIPTOZIP'];
    }

    /**
     * Get the country code.
     *
     * @return string ship to country code
     */
    public function getShipToCountryCode()
    {
        // @todo parse list
        return $this->data['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'];
    }

    /**
     * Get the status of street address on file with PayPal.
     *
     * @return string address status
     */
    public function getAddressStatus()
    {
        // @todo parse list
        return $this->data['PAYMENTREQUEST_0_ADDRESSSTATUS'];
    }

    /**
     * Get the PayPal address normalization status for Brazilian addresses.
     *
     * @return string address normalization status
     */
    public function getAddressNormalizationStatus()
    {
        // @todo parse list
        return $this->data['PAYMENTREQUEST_0_ADDRESSNORMALIZATIONSTATUS'];
    }

    /**
     * Get the total cost of the transaction to the buyer.
     *
     * If shipping cost (not applicable to digital goods) and tax charges are known,
     * include them in this value. If not, this value should be the current sub-total of
     * the order. If the transaction includes one or more one-time purchases, this field
     * must be equal to the sum of the purchases. This field is set to 0 if the
     * transaction does not include a one-time purchase such as when you set up a billing
     * agreement for a recurring payment that is not immediately charged.
     * Purchase-specific fields are ignored.
     *
     * @return string amount
     */
    public function getAmount()
    {
        // @todo parse list
        return $this->data['PAYMENTREQUEST_0_AMT'];
    }

    /**
     * Get a 3-character currency code.
     *
     * @return string currency code
     */
    public function getCurrency()
    {
        // @todo parse list
        return isset($this->data['PAYMENTREQUEST_0_CURRENCYCODE'])
            ? $this->data['PAYMENTREQUEST_0_CURRENCYCODE']
            : 'USD';
    }

    /**
     * Get the total shipping costs for this order.
     *
     * @return string shipping amount
     */
    public function getShippingAmount()
    {
        // @todo parse list
        return $this->data['PAYMENTREQUEST_0_SHIPPINGAMT'];
    }

    /**
     * Get the total shipping insurance costs for this order.
     *
     * @return string insurance amount
     */
    public function getInsuranceAmount()
    {
        // @todo parse list
        return $this->data['PAYMENTREQUEST_0_INSURANCEAMT'];
    }

    /**
     * Get the shipping discount for this order, specified as a negative number.
     *
     * @return string shipping discount amount
     */
    public function getShippingDiscountAmount()
    {
        // @todo parse list
        return $this->data['PAYMENTREQUEST_0_SHIPDISCAMT'];
    }

    /**
     * Get whether insurance is available as an option the buyer can choose on the PayPal
     * pages.
     *
     * @return bool insurance option offered
     */
    public function getInsuranceOptionOffered()
    {
        // @todo parse list
        return $this->data['PAYMENTREQUEST_0_INSURANCEOPTIONOFFERED'] === 'true';
    }

    /**
     * Get the total handling costs for this order.
     *
     * @return string handling amount
     */
    public function getHandlingAmount()
    {
        // @todo parse list
        return $this->data['PAYMENTREQUEST_0_HANDLINGAMT'];
    }

    /**
     * Get the sum of tax for all items in this order.
     *
     * @return string tax amount
     */
    public function getTaxAmount()
    {
        // @todo parse list
        return $this->data['PAYMENTREQUEST_0_TAXAMT'];
    }

    /**
     * Get the merchant invoice or tracking number.
     *
     * @return string transaction id
     */
    public function getTransactionId()
    {
        // @todo parse list
        return $this->data['PAYMENTREQUEST_0_INVNUM'];
    }
}
