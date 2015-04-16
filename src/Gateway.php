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
}
