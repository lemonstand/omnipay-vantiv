<?php namespace Omnipay\Vantiv\Message;

/**
 * Netaxept Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    public function getReportGroup()
    {
        return $this->getParameter('reportGroup');
    }

    public function setReportGroup($value)
    {
        return $this->setParameter('reportGroup', $value);
    }

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

        $sale = $data->addChild('sale');
        $sale->addAttribute('id', $this->getTransactionId());
        $sale->addAttribute('customerId', $this->getCustomerId());
        $sale->addAttribute('reportGroup', $this->getReportGroup());
        $sale->addChild('orderId', $this->getOrderId());

        // The amount is sent as cents but as a string
        $sale->addChild('amount', (string) $this->getAmountInteger());
        $sale->addChild('orderSource', 'ecommerce');

        if ($card) {
            $billToAddress = $sale->addChild('billToAddress');
            $billToAddress->addChild('name', $card->getBillingFirstName());
            $billToAddress->addChild('addressLine1', $card->getBillingAddress1());
            $billToAddress->addChild('city', $card->getBillingCity());
            $billToAddress->addChild('state', $card->getBillingState());
            $billToAddress->addChild('zip', $card->getBillingPostcode());
            $billToAddress->addChild('country', $card->getBillingCountry());
            $billToAddress->addChild('email', $card->getEmail());
            $billToAddress->addChild('phone', $card->getBillingPhone());

            $cc = $sale->addChild('card');
            $cc->addChild('type', $this->getCreditType($card->getBrand()));
            $cc->addChild('number', $card->getNumber());
            $cc->addChild('expDate', $card->getExpiryDate('m') . $card->getExpiryDate('Y'));
            $cc->addChild('cardValidationNum', $card->getCvv());
        }

        return $data;
    }

    protected function createResponse($response)
    {
        return $this->response = new PurchaseResponse($this, $response);
    }
}