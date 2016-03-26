<?php namespace Omnipay\Vantiv\Message;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
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

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseRequestSuccess.txt');
        $response = $this->request->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('PurchaseRequestFailure.txt');
        $response = $this->request->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Valid Format', $response->getMessage());
    }

    public function testPreLiveMode()
    {
        $this->assertSame($this->request, $this->request->setPreLiveMode(true));
        $this->assertSame(true, $this->request->getPreLiveMode());
    }

    public function testReportGroup()
    {
        $this->assertSame($this->request, $this->request->setReportGroup('test-group-1'));
        $this->assertSame('test-group-1', $this->request->getReportGroup());
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame("1200", (string) $data->sale->amount);
    }

    public function testGetPreliveEndpoint()
    {
        $this->assertSame($this->request, $this->request->setPreLiveMode(true));
        $this->assertSame('https://transact-prelive.litle.com/vap/communicator/online', $this->request->getEndpoint());
    }

    public function testTestEndpoint()
    {
        $this->assertSame($this->request, $this->request->setTestMode(true));
        $this->assertSame('https://www.testlitle.com/sandbox/communicator/online', $this->request->getEndpoint());
    }

    public function testPreLiveModeEndpointPrecedence()
    {
        $this->assertSame($this->request, $this->request->setPreLiveMode(true));
        $this->assertSame($this->request, $this->request->setTestMode(true));
        $this->assertSame('https://transact-prelive.litle.com/vap/communicator/online', $this->request->getEndpoint());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The card parameter is required
     */
    public function testCardRequired()
    {
        $this->request->setCard(null);
        $this->request->getData();
    }

    public function testDataWithCard()
    {
        $card = $this->getValidCard();
        $this->request->setCard($card);
        $data = $this->request->getData();
        $this->assertSame($card['number'], (string) $data->sale->card->number);
    }
}
