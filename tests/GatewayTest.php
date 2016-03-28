<?php namespace Omnipay\Vantiv;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->options = array(
            'amount'    => '10.00',
            'card'      => $this->getValidCard(),
            'currency'  => 'USD'
        );
    }

    public function testPreLiveMode()
    {
        $this->assertSame($this->gateway, $this->gateway->setPreLiveMode(true));
        $this->assertSame(true, $this->gateway->getPreLiveMode());
    }

    public function testAuthorize()
    {
        $request = $this->gateway->authorize($this->options);
        $this->assertInstanceOf('Omnipay\Vantiv\Message\AuthorizeRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
        $this->assertSame('POST', $request->getHttpMethod());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase($this->options);
        $this->assertInstanceOf('Omnipay\Vantiv\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
        $this->assertSame('POST', $request->getHttpMethod());
    }
}
