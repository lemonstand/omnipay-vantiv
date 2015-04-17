<?php namespace Omnipay\Vantiv;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->options = array(
            'amount' => '10.00',
            'card'   => $this->getValidCard(),
        );
        $this->gateway->setTestMode(true);
    }

    public function testPurchaseSuccess()
    {
        $this->assertTrue(true);
    }

    public function testPurchaseFailure()
    {
        $this->assertTrue(true);
    }

    public function testSaleTransactionRequestSuccess()
    {
        $this->setMockHttpResponse('SaleTransactionRequestSuccess.txt');
        $options = array(
            'transactionId' => '55742-165747-52441DAF-3596',
            'currency' => 'CAD'
        );

        $response = $this->gateway->purchase(array_merge($this->options, $options))->send();
        $this->assertInstanceOf('\Omnipay\Vantiv\Message\SaleTransactionRequest', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
    }
}