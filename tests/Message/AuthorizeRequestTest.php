<?php namespace Omnipay\Vantiv\Message;

use Omnipay\Tests\TestCase;

class AuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '12.00',
                'currency' => 'USD',
                'card' => $this->getValidCard(),
                'description' => 'Order #42',
                'orderId' => '42',
                'customerId' => '1'
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame("1200", (string) $data->authorization->amount);
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockHttpResponse('AuthorizeRequestSuccess.txt');
        $response = $this->request->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testAuthorizeInsufficentFunds()
    {
        $this->setMockHttpResponse('AuthorizeRequestInsufficientFunds.txt');
        $response = $this->request->send();
        
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Insufficient Funds', $response->getMessage());
        $this->assertSame('110', $response->getResponseCode());
    }
}