<?php namespace Omnipay\Vantiv\Message;

class AuthorizationResponse extends Response
{
    /**
     * Check for successful authorization
     *
     * This is used after createCard to get the credit card token to be
     *
     * @return string
     */
    public function isSuccessful()
    {
        if (isset($this->data->saleResponse->message)) {
            return ((string) $this->data->saleResponse->message === "Approved");
        }

        return false;
    }
}