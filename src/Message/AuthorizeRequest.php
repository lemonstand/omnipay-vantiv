<?php namespace Omnipay\Vantiv\Message;

use Omnipay\Common\CreditCard;

/**
 * Netaxept Purchase Request
 */
class AuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'card');

        $card = $this->getCard();

        $data = new \SimpleXMLElement('<litleOnlineRequest version="9.03" xmlns="http://www.litle.com/schema" />');
        $data->addAttribute('merchantId', $this->getMerchantId());

        $authentication = $data->addChild('authentication');
        $authentication->addChild('user', $this->getUsername());
        $authentication->addChild('password', $this->getPassword());

        $authorization = $data->addChild('authorization');
        $authorization->addAttribute('id', $this->getTransactionId());
        $authorization->addAttribute('customerId', $this->getCustomerId());
        $authorization->addChild('orderId', $this->getOrderId());
        $authorization->addChild('amount', (string) $this->getAmountInteger());
        $authorization->addChild('orderSource', 'ecommerce');

        if ($card) {
            $billToAddress = $authorization->addChild('billToAddress');
            $billToAddress->addChild('name', $card->getBillingFirstName());
            $billToAddress->addChild('addressLine1', $card->getBillingAddress1());
            $billToAddress->addChild('city', $card->getBillingCity());
            $billToAddress->addChild('state', $card->getBillingState());
            $billToAddress->addChild('zip', $card->getBillingPostcode());
            $billToAddress->addChild('country', $card->getBillingCountry());
            $billToAddress->addChild('email', $card->getEmail());
            $billToAddress->addChild('phone', $card->getBillingPhone());

            $cc = $authorization->addChild('card');

            $codes = array(
                CreditCard::BRAND_AMEX        => 'AMEX',
                CreditCard::BRAND_DANKORT     => 'DANKORT',
                CreditCard::BRAND_DINERS_CLUB => 'DINERS',
                CreditCard::BRAND_DISCOVER    => 'DI',
                CreditCard::BRAND_JCB         => 'JCB',
                CreditCard::BRAND_LASER       => 'LASER',
                CreditCard::BRAND_MAESTRO     => 'MAESTRO',
                CreditCard::BRAND_MASTERCARD  => 'MC',
                CreditCard::BRAND_VISA        => 'VI'
            );
            $cc->addChild('type', $codes[$card->getBrand()]);

            $cc->addChild('number', $card->getNumber());
            $cc->addChild('expDate', $card->getExpiryDate('m') . $card->getExpiryDate('Y'));
            $cc->addChild('cardValidationNum', $card->getCvv());
        }

        return $data;
    }

    protected function createResponse($response)
    {
        return $this->response = new AuthorizeResponse($this, $response);
    }
}