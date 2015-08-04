<?php namespace Omnipay\Vantiv\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Vantiv Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{
    protected $transactionType = 'authorization';

    public function getData()
    {
        $this->validate('amount');

        $card = $this->getCard();
        $token = $this->getToken();

        if (!$card && !$token) {
            $param  = (!$card) ? 'card' : 'token';
            throw new InvalidRequestException("The $param parameter is required");
        }

        $data = new \SimpleXMLElement('<litleOnlineRequest xmlns="http://www.litle.com/schema" />');
        $data->addAttribute('version', $this->getVersion());
        $data->addAttribute('merchantId', $this->getMerchantId());

        $authentication = $data->addChild('authentication');
        $authentication->addChild('user', $this->getUsername());
        $authentication->addChild('password', $this->getPassword());

        $transaction = $data->addChild($this->transactionType);
        $transaction->addAttribute('id', $this->getTransactionId());
        $transaction->addAttribute('customerId', $this->getCustomerId());
        $transaction->addAttribute('reportGroup', $this->getReportGroup());
        $transaction->addChild('orderId', $this->getOrderId());

        // The amount is sent as cents
        $transaction->addChild('amount', (string) $this->getAmountInteger());
        $transaction->addChild('orderSource', 'ecommerce');

        if ($card) {
            $billToAddress = $transaction->addChild('billToAddress');
            $billToAddress->addChild('name', $card->getBillingName());
            $billToAddress->addChild('addressLine1', $card->getBillingAddress1());
            $billToAddress->addChild('city', $card->getBillingCity());
            $billToAddress->addChild('state', $card->getBillingState());
            $billToAddress->addChild('zip', $card->getBillingPostcode());
            $billToAddress->addChild('country', $card->getBillingCountry());
            $billToAddress->addChild('email', $card->getEmail());
            $billToAddress->addChild('phone', $card->getBillingPhone());

            $cc = $transaction->addChild('card');
            $cc->addChild('type', $this->getCreditType($card->getBrand()));
            $cc->addChild('number', $card->getNumber());
            $cc->addChild('expDate', $card->getExpiryDate('m') . $card->getExpiryDate('y'));
            $cc->addChild('cardValidationNum', $card->getCvv());
        }

        if ($token) {
            $tokenElement = $transaction->addChild('token');
            $tokenElement->addChild('litleToken', $token);
        }

        return $data;
    }

    protected function createResponse($response)
    {
        return $this->response = new AuthorizeResponse($this, $response);
    }
}
