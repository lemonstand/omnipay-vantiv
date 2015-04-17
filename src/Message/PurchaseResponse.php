<?php namespace Omnipay\Vantiv\Message;

class PurchaseResponse extends Response
{
    /**
     * Check for successful sale transaction
     *
     * @return bool
     */
    public function isSuccessful()
    {
        if (isset($this->data->authorizationResponse->message)) {
            return ((string) $this->data->authorizationResponse->message === "Approved");
        }

        return false;
    }
}