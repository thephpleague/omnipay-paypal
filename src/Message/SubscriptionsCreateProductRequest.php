<?php
/**
 * PayPal Subscriptions Create Product Request
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal Subscriptions Create Product Request
 *
 *
 * ### Request Data
 *
 *
 * ### Example
 *
 *
 * ### Request Sample
 *
 *
 * ### Response Sample
 *
 *
 * @link https://developer.paypal.com/docs/api/#create-a-product
 * @see Omnipay\PayPal\RestGateway
 */
class SubscriptionsCreateProductRequest extends AbstractRestRequest
{
    /**
     * Get the plan name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getParameter('name');
    }

    /**
     * Set the plan name
     *
     * @param string $value
     * @return SubscriptionsCreateProductRequest provides a fluent interface.
     */
    public function setName($value)
    {
        return $this->setParameter('name', $value);
    }

    /**
     * Get the product description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getParameter('description');
    }

    /**
     * Set the product description
     *
     * @param string $value
     * @return SubscriptionsCreateProductRequest provides a fluent interface.
     */
    public function setDescription($value)
    {
        return $this->setParameter('description', $value);
    }

    /**
     * Get the product type
     *
     * @return string
     */
    public function getType()
    {
        return $this->getParameter('type');
    }

    /**
     * Set the product type
     *
     * @param string $value
     * @return SubscriptionsCreateProductRequest provides a fluent interface.
     */
    public function setType($value)
    {
        return $this->setParameter('type', $value);
    }

    /**
     * Get the product category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->getParameter('category');
    }

    /**
     * Set the product category
     *
     * @param string $value
     * @return SubscriptionsCreateProductRequest provides a fluent interface.
     */
    public function setCategory($value)
    {
        return $this->setParameter('category', $value);
    }

    /**
     * Get the product image url
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->getParameter('image_url');
    }

    /**
     * Set the product image url
     *
     * @param string $value
     * @return SubscriptionsCreateProductRequest provides a fluent interface.
     */
    public function setImageUrl($value)
    {
        return $this->setParameter('image_url', $value);
    }

    /**
     * Get the product home url
     *
     * @return string
     */
    public function getHomeUrl()
    {
        return $this->getParameter('home_url');
    }

    /**
     * Set the product home url
     *
     * @param string $value
     * @return SubscriptionsCreateProductRequest provides a fluent interface.
     */
    public function setHomeUrl($value)
    {
        return $this->setParameter('home_url', $value);
    }

    public function getData()
    {
        $this->validate('name', 'description');
        $data = array(
            'name'              => $this->getName(),
            'description'       => $this->getDescription(),
            'type'              => $this->getType(),
            'category'          => $this->getCategory(),
            'image_url'         => $this->getImageUrl(),
            'home_url'          => $this->getHomeUrl(),
        );

        return $data;
    }

    /**
     * Get transaction endpoint.
     *
     * Billing plans are created using the /billing/plans resource.
     *
     * @return string
     */
    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/catalogs/products';
    }
}

