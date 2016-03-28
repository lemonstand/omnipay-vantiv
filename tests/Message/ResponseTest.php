<?php namespace Omnipay\Vantiv\Message;

use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testPurchaseNoMessageNoResponseCode()
    {
        $httpResponse = $this->getMockHttpResponse('NoMessageNoResponseCodeFailure.txt');
        $response = new PurchaseResponse($this->getMockRequest(), $httpResponse->xml());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getResponseCode());
    }

    public function testAuthorizeNoMessageNoResponseCode()
    {
        $httpResponse = $this->getMockHttpResponse('NoMessageNoResponseCodeFailure.txt');
        $response = new AuthorizeResponse($this->getMockRequest(), $httpResponse->xml());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getResponseCode());
    }
}
