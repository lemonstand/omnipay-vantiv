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

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Vantiv\Message\AuthorizeRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Vantiv\Message\PurchaseRequest', $parameters);
    }
}
