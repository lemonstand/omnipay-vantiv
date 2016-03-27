<?php namespace Omnipay\Vantiv\Message;

use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseRequestSuccess.txt');
        $response = new PurchaseResponse($this->getMockRequest(), $httpResponse->xml());
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Approved', $response->getMessage());
        $this->assertSame('372127157386039000', $response->getTransactionReference());
        $this->assertSame('110299471', $response->getOrderId());
        $this->assertSame('000', $response->getResponseCode());
        $this->assertSame('72555', $response->getAuthCode());
        $this->assertSame('00', $response->getAvsResult());
        $this->assertSame('M', $response->getCardValidationResult());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseRequestFailure.txt');
        $response = new PurchaseResponse($this->getMockRequest(), $httpResponse->xml());
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Valid Format', $response->getMessage());
        $this->assertSame('1', $response->getResponseCode());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getOrderId());
        $this->assertNull($response->getAuthCode());
        $this->assertNull($response->getCardValidationResult());
        $this->assertNull($response->getAvsResult());
    }
}
