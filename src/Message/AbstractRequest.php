<?php namespace Omnipay\Vantiv\Message;

use Omnipay\Common\CreditCard;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $version = '9.4';

    /**
     * Test Endpoint URL
     *
     * @var string URL
     */
    protected $testEndpoint = 'https://www.testlitle.com/sandbox/communicator/online';

    /**
     * Pre-Live Endpoint URL
     *
     * @var string URL
     */
    protected $preLiveEndpoint = 'https://transact.vantivprelive.com/vap/communicator/online';

    /**
     * Live Endpoint URL
     *
     * @var string URL
     */
    protected $liveEndpoint = 'https://transact.vantivcnp.com/vap/communicator/online';

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
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

    public function getReportGroup()
    {
        return $this->getParameter('reportGroup');
    }

    public function setReportGroup($value)
    {
        return $this->setParameter('reportGroup', $value);
    }

    public function getCustomerId()
    {
        return $this->getParameter('customerId');
    }

    public function setCustomerId($value)
    {
        return $this->setParameter('customerId', $value);
    }

    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getPreLiveMode()
    {
        return $this->getParameter('preLiveMode');
    }

    public function setPreLiveMode($value)
    {
        return $this->setParameter('preLiveMode', $value);
    }

    /**
     * Get API endpoint URL
     *
     * If test mode and pre-live mode are both set, then
     * pre-live mode will take precedence.
     *
     * @return string
     */
    public function getEndpoint()
    {
        if ($this->getPreLiveMode()) {
            return $this->preLiveEndpoint;
        } elseif ($this->getTestMode()) {
            return $this->testEndpoint;
        } else {
            return $this->liveEndpoint;
        }
    }

    /**
     * Get HTTP Method
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
     * Get Content Type
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
     * Get Credit Type
     *
     * Match the brand up to the supported card format, throwd exception on unsupported card.
     *
     * @return string
     */
    public function getCreditType($brand)
    {
        $codes = array(
            CreditCard::BRAND_AMEX        => 'AX',
            CreditCard::BRAND_DINERS_CLUB => 'DC',
            CreditCard::BRAND_DISCOVER    => 'DI',
            CreditCard::BRAND_JCB         => 'JC',
            CreditCard::BRAND_MASTERCARD  => 'MC',
            CreditCard::BRAND_VISA        => 'VI'
        );

        if (isset($codes[$brand])) {
            return $codes[$brand];
        }

        return null;
    }

    /**
     * Send Data
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
