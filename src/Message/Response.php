<?php namespace Omnipay\Vantiv\Message;

/**
 * Vantiv Response
 *
 * This is the response class for all Vantiv REST requests.
 *
 * @see \Omnipay\Pin\Gateway
 */
class Response extends \Omnipay\Common\Message\AbstractResponse
{
    /**
     * Check for successful response
     *
     * @return bool
     */
    public function isSuccessful()
    {
        $element = $this->element;

        if (isset($this->data->$element->message)) {
            return ($this->getMessage() === 'Approved' && $this->getResponseCode() === '000');
        }

        return false;
    }


    public function getTransactionReference()
    {
        $element = $this->element;

        if (isset($this->data->$element->litleTxnId)) {
            return ((string) $this->data->$element->litleTxnId);
        }

        return null;
    }

    public function getOrderId()
    {
        $element = $this->element;

        if (isset($this->data->$element->orderId)) {
            return ((string) $this->data->$element->orderId);
        }

        return null;
    }

    public function getAuthCode()
    {
        $element = $this->element;

        if (isset($this->data->$element->authCode)) {
            return ((string) $this->data->$element->authCode);
        } else {
            return null;
        }
    }

    /**
     * Get the response code
     *
     * If the transaction is successful the code is embedded in the body,
     * if not, then it is on the root element.
     *
     * @return string
     */
    public function getResponseCode()
    {
        $element = $this->element;

        if (isset($this->data->$element->response)) {
            return ((string) $this->data->$element->response);
        } elseif (isset($this->data->attributes()->response)) {
            return (string) $this->data->attributes()->response;
        } else {
            return null;
        }
    }

    public function getMessage()
    {
        $element = $this->element;

        if (isset($this->data->$element->message)) {
            return (string) $this->data->$element->message;
        } elseif (isset($this->data->attributes()->message)) {
            return (string) $this->data->attributes()->message;
        } else {
            return null;
        }
    }

    public function getAvsResult()
    {
        $element = $this->element;

        if (isset($this->data->$element->fraudResult->avsResult)) {
            return ((string) $this->data->$element->fraudResult->avsResult);
        }

        return null;
    }

    public function getCardValidationResult()
    {
        $element = $this->element;

        if (isset($this->data->$element->fraudResult->cardValidationResult)) {
            return ((string) $this->data->$element->fraudResult->cardValidationResult);
        }

        return null;
    }
}
