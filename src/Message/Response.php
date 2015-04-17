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
     * Check for successful payment
     *
     * This is used after createCard to get the credit card token to be
     *
     * @return string
     */
    public function isSuccessful()
    {
        if (isset($this->data->attributes()->response)) {
            return ((string) $this->data->attributes()->response === "0");
        }

        return false;
    }

    /**
     * Get Card Token
     *
     * This is used after createCard to get the credit card token to be
     * used in future transactions.
     *
     * @return string
     */
    public function getResponseCode()
    {
        if (isset($this->data->attributes()->response)) {
            return (string) $this->data->attributes()->response;
        }
    }

    public function getMessage()
    {
        if ($this->isSuccessful()) {

        } else {
            if (isset($this->data->attributes()->message)) {
                return (string) $this->data->attributes()->message;
            }
        }
    }
}