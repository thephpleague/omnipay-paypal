<?php

namespace Omnipay\PayPal;

/**
 * PayPal Express Gateway
 *
 * @author Adrian Macneil <adrian@adrianmacneil.com>
 * @author Joao Dias <joao.dias@cherrygroup.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class ExpressGateway extends ProGateway
{
    public function getName()
    {
        return 'PayPal Express';
    }

    public function getDefaultParameters()
    {
        $settings = parent::getDefaultParameters();
        $settings['solutionType'] = array('Sole', 'Mark');
        $settings['landingPage'] = array('Billing', 'Login');
        $settings['brandName'] = '';
        $settings['headerImageUrl'] = '';

        return $settings;
    }

    public function getSolutionType()
    {
        return $this->getParameter('solutionType');
    }

    public function setSolutionType($value)
    {
        return $this->setParameter('solutionType', $value);
    }

    public function getLandingPage()
    {
        return $this->getParameter('landingPage');
    }

    public function setLandingPage($value)
    {
        return $this->setParameter('landingPage', $value);
    }

    public function getBrandName()
    {
        return $this->getParameter('brandName');
    }

    public function setBrandName($value)
    {
        return $this->setParameter('brandName', $value);
    }

    public function getHeaderImageUrl()
    {
        return $this->getParameter('headerImageUrl');
    }

    /**
     * Header Image URL (Optional)
     *
     * URL for the image you want to appear at the top left of the payment page.
     * The image has a maximum size of 750 pixels wide by 90 pixels high.
     * PayPal recommends that you provide an image that is stored on a secure (https) server.
     * If you do not specify an image, the business name displays.
     * Character length and limitations: 127 single-byte alphanumeric characters
     */
    public function setHeaderImageUrl($value)
    {
        return $this->setParameter('headerImageUrl', $value);
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\ExpressAuthorizeRequest', $parameters);
    }

    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\ExpressCompleteAuthorizeRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->authorize($parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\ExpressCompletePurchaseRequest', $parameters);
    }

    /**
     * Get the details of a checkout.
     *
     * @param  array                                    $parameters options
     * @return Message\GetExpressCheckoutDetailsRequest
     */
    public function getDetails(array $parameters = array())
    {
        return $this->createRequest('Omnipay\PayPal\Message\GetExpressCheckoutDetailsRequest', $parameters);
    }

    /**
     * Make a payment to one or more PayPal account holders.
     *
     * @param  array                  $parameters parameters
     * @return Message\MassPayRequest
     */
    public function payout(array $parameters = array())
    {
        return $this->createRequest('Omnipay\PayPal\Message\MassPayRequest', $parameters);
    }
}
