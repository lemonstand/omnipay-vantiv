# Omnipay: Vantiv

**Vantiv payment processing driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/lemonstand/omnipay-vantiv.svg)](https://travis-ci.org/lemonstand/omnipay-vantiv) [![Coverage Status](https://coveralls.io/repos/github/lemonstand/omnipay-vantiv/badge.svg?branch=master)](https://coveralls.io/github/lemonstand/omnipay-vantiv?branch=master) [![Latest Stable Version](https://poser.pugx.org/lemonstand/omnipay-vantiv/v/stable.svg)](https://packagist.org/packages/lemonstand/omnipay-vantiv) [![Total Downloads](https://poser.pugx.org/lemonstand/omnipay-vantiv/downloads)](https://packagist.org/packages/lemonstand/omnipay-vantiv) [![Latest Unstable Version](https://poser.pugx.org/lemonstand/omnipay-vantiv/v/unstable.svg)](https://packagist.org/packages/lemonstand/omnipay-vantiv)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements vantiv Payments support for Omnipay. Please see the full [Vantiv documentation](https://github.com/LitleCo/litle-xml/blob/master/reference_guides/Vantiv_LitleXML_Reference_Guide_XML9.4_V1.7.pdf) for more information.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "lemonstand/omnipay-vantiv": "dev-master"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* Purchase (Sale)
* Authorize

```php
	$gateway = Omnipay::create('Vantiv');
	$gateway->setMerchantId($merchantId);
	$gateway->setUsername($username);
	$gateway->setPassword($password);

	// Test mode hits the sandbox endpoint, and pre-live mode hits that preLive endpoint
	// If both are set the pre-live endpoint takes precedence
	$gateway->setTestMode($testMode);
	$gateway->setPreLiveMode($preLiveMode);

    try {
        $params = [
            'transactionId' => $transactionId,
            'orderId'       => $orderId,
            'customerId'    => $customerId,
            'reportGroup'   => $reportGroup,
            'amount'        => $amount,
            'currency'      => $currency,
            'card'          => $validCard,
            'description'   => $description
        ];

        $response = $gateway->purchase($params)->send();

        if ($response->isSuccessful()) {
            // successfull
        } else {
            throw new ApplicationException($response->getMessage());
        }
    } catch (ApplicationException $e) {
        throw new ApplicationException($e->getMessage());
    }

```

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/lemonstand/omnipay-vantiv/issues),
or better yet, fork the library and submit a pull request.
