<?php namespace Omnipay\Vantiv\Message;

/**
 * Vantiv Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'card');

        $card = $this->getCard();

        $data = new \SimpleXMLElement('<litleOnlineRequest xmlns="http://www.litle.com/schema" />');
        $data->addAttribute('version', $this->getVersion());
        $data->addAttribute('merchantId', $this->getMerchantId());

        $authentication = $data->addChild('authentication');
        $authentication->addChild('user', $this->getUsername());
        $authentication->addChild('password', $this->getPassword());

        $authorization = $data->addChild('authorization');
        $authorization->addAttribute('id', $this->getTransactionId());
        $authorization->addAttribute('customerId', $this->getCustomerId());
        $authorization->addAttribute('reportGroup', $this->getReportGroup());
        $authorization->addChild('orderId', $this->getOrderId());
        $authorization->addChild('amount', (string) $this->getAmountInteger());
        $authorization->addChild('orderSource', 'ecommerce');

        if ($card) {
            $billToAddress = $authorization->addChild('billToAddress');
            $billToAddress->addChild('name', $card->getBillingName());
            $billToAddress->addChild('addressLine1', $card->getBillingAddress1());
            $billToAddress->addChild('city', $card->getBillingCity());
            $billToAddress->addChild('state', $card->getBillingState());
            $billToAddress->addChild('zip', $card->getBillingPostcode());
            $billToAddress->addChild('country', $card->getBillingCountry());
            $billToAddress->addChild('email', $card->getEmail());
            $billToAddress->addChild('phone', $card->getBillingPhone());

            $cc = $authorization->addChild('card');
            $cc->addChild('type', $this->getCreditType($card->getBrand()));
            $cc->addChild('number', $card->getNumber());
            $cc->addChild('expDate', $card->getExpiryDate('m') . $card->getExpiryDate('y'));
            $cc->addChild('cardValidationNum', $card->getCvv());
        }

        return $data;
    }

    protected function createResponse($response)
    {
        return $this->response = new AuthorizeResponse($this, $response);
    }
}
