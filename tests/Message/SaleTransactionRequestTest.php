<?php namespace Omnipay\Vantiv\Message;

use Omnipay\Tests\TestCase;

// class SaleTransactionRequest extends TestCase
// {
//     public function setUp()
//     {
//         $this->request = new SaleTransactionRequest($this->getHttpClient(), $this->getHttpRequest());
//         $this->request->initialize(
//             array(
//                 'amount' => '12.00',
//                 'customerId' => 'cust-id',
//                 'card' => $this->getValidCard()
//             )
//         );
//     }
//     public function testGetData()
//     {
//         $data = $this->request->getData();
//         $this->assertSame('AUTH_CAPTURE', $data['x_type']);
//         $this->assertSame('10.0.0.1', $data['x_customer_ip']);
//         $this->assertSame('cust-id', $data['x_cust_id']);
//         $this->assertArrayNotHasKey('x_test_request', $data);
//     }
// }