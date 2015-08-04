<?php namespace Omnipay\Vantiv\Message;

/**
 * Netaxept Purchase Request
 */
class PurchaseRequest extends AuthorizeRequest
{
    protected $transactionType = 'sale';

    public function getData()
    {
        return parent::getData();
    }

    protected function createResponse($response)
    {
        return $this->response = new PurchaseResponse($this, $response);
    }
}
