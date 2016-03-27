<?php namespace Omnipay\Vantiv\Message;

use Omnipay\Tests\TestCase;

class AuthorizeResponseTest extends TestCase
{
    public function testAuthorizeSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('AuthorizeRequestSuccess.txt');
        $response = new AuthorizeResponse($this->getMockRequest(), $httpResponse->xml());
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Approved', $response->getMessage());
        $this->assertSame('161516686798915000', $response->getTransactionReference());
        $this->assertSame('1', $response->getOrderId());
        $this->assertSame('000', $response->getResponseCode());
        $this->assertSame('31186', $response->getAuthCode());
        $this->assertSame('00', $response->getAvsResult());
        $this->assertSame('M', $response->getCardValidationResult());
    }

    public function testAuthorizeFailure()
    {
        $httpResponse = $this->getMockHttpResponse('AuthorizeRequestFailure.txt');
        $response = new AuthorizeResponse($this->getMockRequest(), $httpResponse->xml());
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Valid Format', $response->getMessage());
        $this->assertSame('1', $response->getResponseCode());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getOrderId());
        $this->assertNull($response->getAuthCode());
        $this->assertNull($response->getCardValidationResult());
        $this->assertNull($response->getAvsResult());
    }

    public function testAuthorizeInsufficentFunds()
    {
        $httpResponse = $this->getMockHttpResponse('AuthorizeRequestInsufficientFunds.txt');
        $response = new AuthorizeResponse($this->getMockRequest(), $httpResponse->xml());
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Insufficient Funds', $response->getMessage());
        $this->assertSame('110', $response->getResponseCode());
    }
}
