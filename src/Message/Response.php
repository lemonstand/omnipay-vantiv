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
            return ((string) $this->data->$element->message === "Approved");
        }

        return false;
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
        } else if (isset($this->data->attributes()->response)) {
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
        } else if (isset($this->data->attributes()->message)) {
            return (string) $this->data->attributes()->message;
        } else {
            return null;
        }
    }
}