<?php namespace Omnipay\Vantiv;

use Omnipay\Common\AbstractGateway;

/**
 * Vantiv Gateway
 *
 * @link https://litleco.github.io/
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Vantiv';
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }
    
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Vantiv\Message\AuthorizeRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Vantiv\Message\PurchaseRequest', $parameters);
    }
}
