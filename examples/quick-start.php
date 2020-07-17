<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../vendor/autoload.php';

use Legatus\Http\Validator\Tests\PromoCode;
use Legatus\Http\Validator\ValidationFailed;
use Legatus\Http\Validator\Validator;

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
