<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST Authorize Request
 */
class RestAuthorizeRequest extends AbstractRestRequest
{
    public function getData()
    {
        $data = array(
            'intent' => 'authorize',
            'payer' => array(
                'payment_method' => 'credit_card',
                'funding_instruments' => array()
            ),
            'transactions' => array(
                array(
                    'description' => $this->getDescription(),
                    'amount' => array(
                        'total' => $this->getAmount(),
                        'currency' => $this->getCurrency(),
                    ),
                )
            )
        );

        if ($this->getCardReference()) {
            $this->validate('amount');

            $data['payer']['funding_instruments'][] = array(
                'credit_card_token' => array(
                    'credit_card_id' => $this->getCardReference(),
                ),
            );
        } else {
            $this->validate('amount', 'card');
            $this->getCard()->validate();

            $data['payer']['funding_instruments'][] = array(
                'credit_card' => array(
                    'number' => $this->getCard()->getNumber(),
                    'type' => $this->getCard()->getBrand(),
                    'expire_month' => $this->getCard()->getExpiryMonth(),
                    'expire_year' => $this->getCard()->getExpiryYear(),
                    'cvv2' => $this->getCard()->getCvv(),
                    'first_name' => $this->getCard()->getFirstName(),
                    'last_name' => $this->getCard()->getLastName(),
                    'billing_address' => array(
                        'line1' => $this->getCard()->getAddress1(),
                        //'line2' => $this->getCard()->getAddress2(),
                        'city' => $this->getCard()->getCity(),
                        'state' => $this->getCard()->getState(),
                        'postal_code' => $this->getCard()->getPostcode(),
                        'country_code' => strtoupper($this->getCard()->getCountry()),
                    )
                )
            );

            // There's currently a quirk with the REST API that requires line2 to be
            // non-empty if it's present. Jul 14, 2014
            $line2 = $this->getCard()->getAddress2();
            if (!empty($line2)) {
                $data['payer']['funding_instruments'][0]['credit_card']['billing_address']['line2'] = $line2;
            }
        }

        return $data;
    }

    /**
     * The REST API does not currently have support for passing an invoice number
     * or transaction ID.
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

    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/payments/payment';
    }
}
