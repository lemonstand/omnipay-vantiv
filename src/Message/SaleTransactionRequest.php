<?php namespace Omnipay\Vantiv\Message;

use Omnipay\Common\CreditCard;

/**
 * Netaxept Purchase Request
 */
class SaleTransactionRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'card');
        $this->getCard()->validate();

        $data = new \SimpleXMLElement('<litleOnlineRequest version="8.10" xmlns="http://www.litle.com/schema" />');
        $data->addAttribute('merchantId', $this->getMerchantId());

        $authentication = $data->addChild('authentication');
        $authentication->addChild('user', $this->getUsername());
        $authentication->addChild('password', $this->getPassword());

        $authorization = $data->addChild('authorization');
        $authorization->addAttribute('id', $this->getTransactionId());
        $authorization->addAttribute('customerId', $this->getCustomerId());
        $authorization->addChild('orderId', $this->getOrderId());
        $authorization->addChild('amount', $this->getAmount());
        $authorization->addChild('orderSource', 'ecommerce');

        if ($card = $this->getCard()) {
            $billToAddress = $authorization->addChild('billToAddress');
            $billToAddress->addChild('name', $card->getBillingFirstName());
            $billToAddress->addChild('addressLine1', $card->getBillingAddress1());
            $billToAddress->addChild('city', $card->getBillingCity());
            $billToAddress->addChild('state', $card->getBillingState());
            $billToAddress->addChild('zip', $card->getBillingPostcode());
            $billToAddress->addChild('country', $card->getBillingCountry());
            $billToAddress->addChild('email', $card->getEmail());
            $billToAddress->addChild('phone', $card->getBillingPhone());

            $cc = $billToAddress->addChild('card');

            $codes = array(
                CreditCard::BRAND_AMEX        => 'AMEX',
                CreditCard::BRAND_DANKORT     => 'DANKORT',
                CreditCard::BRAND_DINERS_CLUB => 'DINERS',
                CreditCard::BRAND_DISCOVER    => 'DISCOVER',
                CreditCard::BRAND_JCB         => 'JCB',
                CreditCard::BRAND_LASER       => 'LASER',
                CreditCard::BRAND_MAESTRO     => 'MAESTRO',
                CreditCard::BRAND_MASTERCARD  => 'MC',
                CreditCard::BRAND_VISA        => 'VISA'
            );
            $cc->addChild('type', $codes[$card->getBrand()]);

            $cc->addChild('number', $card->getNumber());
            $cc->addChild('expDate', $card->getExpiryDate('m') . $card->getExpiryDate('Y'));
            $cc->addChild('cardValidationNum', $card->getCvv());
        }

        return $data;
    }

    /**
     * Send data
     *
     * @param \SimpleXMLElement $data Data
     *
     * @access public
     * @return RedirectResponse
     */
    public function sendData($data)
    {
        $headers = array(
            'Content-Type'  => 'text/xml; charset=utf-8'
        );

        $httpResponse = $this->httpClient
            ->post($this->getEndpoint(), $headers, $data->asXML())
            //->setAuth($this->getUsername(), $this->getPassword())
            ->send();

        return $this->createResponse($httpResponse);
    }
}