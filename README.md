Legatus Validator
========================

An HTTP validation library for PSR-7

[![Build Status](https://drone.mnavarro.dev/api/badges/legatus/validator/status.svg)](https://drone.mnavarro.dev/legatus/validator)

## Installation
You can install the Validator component using [Composer][composer]:

```bash
composer require legatus/validator
```

## Quick Start

```php
<?php

$validator = Validator::build($psr7Request)
    ->body('id')->required()->uuid()
    ->body('name.first')->string()->required()
    ->body('name.middle')->string()
    ->body('name.last')->string()->required()
    ->body('password')->string()->required()->min(6)->password()->max(30)
    ->body('birthdate')->string()->date()->before('-18years')
    ->body('promo_code')->custom(new PromoCode()) // And instance of rule or a callable
    ->body('emails.*')->email()
    ->body('addresses.*.lineOne')->required()->string()
    ->body('addresses.*.lineTwo')->string()
    ->body('addresses.*.city')->required()->string()
    ->body('addresses.*.zip')->string()
    ->body('addresses.*.country')->enum(['CL', 'GB', 'US'])
    ->getValidator();

try {
    $data = $validator->validate();
} catch (ValidationFailed $e) {
    // $errors = $e->getErrors();
    // $data = $e->getData();
}

[$id, $name, $password, $birthDate, $promoCode, $emails, $addresses] = $data->values();
```

For more details you can check the [online documentation here][docs].

## Community
We still do not have a community channel. If you would like to help with that, you can let me know!

## Contributing
Read the contributing guide to know how can you contribute to Legatus.

## Security Issues
Please report security issues privately by email and give us a period of grace before disclosing.

## About Legatus
Legatus is a personal open source project led by Matías Navarro Carter and developed by contributors.

[composer]: https://getcomposer.org/
[docs]: https://legatus.mnavarro.dev/components/validator