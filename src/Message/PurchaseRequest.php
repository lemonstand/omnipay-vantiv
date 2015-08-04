<?php namespace Omnipay\Vantiv\Message;

/**
 * Netaxept Purchase Request
 */
class PurchaseRequest extends AuthorizeRequest
{
    public function getData()
    {
        $this->transactionType = 'sale';
        return parent::getData();
    }

    protected function createResponse($response)
    {
        return $this->response = new PurchaseResponse($this, $response);
    }
}
