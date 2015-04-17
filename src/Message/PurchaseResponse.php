<?php namespace Omnipay\Vantiv\Message;

use Omnipay\Common\Message\AbstractResponse;

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