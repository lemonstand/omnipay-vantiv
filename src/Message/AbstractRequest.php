<?php namespace Omnipay\Vantiv\Message;

use Omnipay\Common\CreditCard;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * Test Endpoint URL
     *
     * @var string URL
     */
    protected $testEndpoint = 'https://www.testlitle.com/sandbox/communicator/online';

    /**
     * Live Endpoint URL
     *
     * @var string URL
     */
    protected $liveEndpoint = 'https://www.litle.com/communicator/online';

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getCustomerId()
    {
        return $this->getParameter('customerId');
    }

    public function setCustomerId($value)
    {
        return $this->setParameter('customerId', $value);
    }

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * Get Content Type.
     *
     * This is nearly always 'text/xml; charset=utf-8' but can be over-ridden in sub classes.
     *
     * @return string
     */
    public function getContentType()
    {
        return 'text/xml; charset=utf-8';
    }

    /**
     * Get API endpoint URL
     *
     * @return string
     */
    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function getAuthorizationData()
    {

    }

    public function getBillingData()
    {

    }

    public function getData()
    {
        $this->validate('amount', 'card');

        $card = $this->getCard();
        $card->validate();

        $data = new \SimpleXMLElement('<litleOnlineRequest version="9.03" xmlns="http://www.litle.com/schema" />');
        $data->addAttribute('merchantId', $this->getMerchantId());

        $authentication = $data->addChild('authentication');
        $authentication->addChild('user', $this->getUsername());
        $authentication->addChild('password', $this->getPassword());

        $authorization = $data->addChild('authorization');
        $authorization->addAttribute('id', $this->getTransactionId());
        $authorization->addAttribute('customerId', $this->getCustomerId());
        $authorization->addChild('orderId', $this->getOrderId());

        // The amount is sent as cents but as a string
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
                CreditCard::BRAND_VISA        => 'VI'
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
        // don't throw exceptions for 4xx errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->isClientError()) {
                    $event->stopPropagation();
                }
            }
        );

        $httpRequest = $this->httpClient->createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            null,
            $data->asXML()
        );

        $httpResponse = $httpRequest
            ->setHeader('Content-Type', $this->getContentType())
            ->send()
            ->xml();

        return $this->createResponse($httpResponse);
    }
}