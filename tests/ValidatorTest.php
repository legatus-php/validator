<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Validator\Tests;

use Legatus\Http\Validator\ValidationFailed;
use Legatus\Http\Validator\Validator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ValidatorTest.
 */
class ValidatorTest extends TestCase
{
    public function testItPerformsDecentValidation(): void
    {
        $requestMock = $this->createMock(ServerRequestInterface::class);
        $requestMock->expects(self::atLeastOnce())
            ->method('getParsedBody')
            ->willReturn([
                'id' => 'a02abd04-0519-40aa-b46a-dd6940b3a83a',
                'name' => [
                    'first' => 'John',
                    'last' => 'Doe',
                ],
                'password' => 'Super@Strong#Pass2020',
                'birthdate' => '1988-04-05T00:00:00.000Z',
                'promo_code' => PromoCode::VALID,
                'emails' => ['jdoe@example.com', 'jdoe2@example.com'],
                'addresses' => [
                    [
                        'lineOne' => '90 Union St',
                        'city' => 'Coleraine',
                        'zip' => 'BT52 1XB',
                        'country' => 'GB',
                    ],
                ],
            ]);

        $validator = Validator::build($requestMock)
            ->body('id')->required()->uuid()
            ->body('name.first')->string()->required()
            ->body('name.middle')->string()
            ->body('name.last')->string()->required()
            ->body('password')->string()->required()->min(6)->password()->max(30)
            ->body('birthdate')->string()->date()->before('-18years')
            ->body('promo_code')->custom(new PromoCode())
            ->body('emails.*')->email()
            ->body('addresses.*.lineOne')->required()->string()
            ->body('addresses.*.lineTwo')->string()
            ->body('addresses.*.city')->required()->string()
            ->body('addresses.*.zip')->string()
            ->body('addresses.*.country')->enum(['CL', 'GB', 'US'])
            ->getValidator();

        $validated = $validator->validate();

        [$id, $name, $password, $birthDate, $promoCode, $emails, $addresses] = $validated->values();

        self::assertSame('a02abd04-0519-40aa-b46a-dd6940b3a83a', $id);
        self::assertSame(['first' => 'John', 'middle' => null, 'last' => 'Doe'], $name);
        self::assertSame('Super@Strong#Pass2020', $password);
        self::assertSame(PromoCode::VALID, $promoCode);
        self::assertInstanceOf(\DateTimeInterface::class, $birthDate);
        self::assertSame('jdoe@example.com', $emails[0]);
        self::assertSame('90 Union St', $addresses[0]['lineOne']);
    }

    public function testItFailsWithProperErrorMessages(): void
    {
        $requestMock = $this->createMock(ServerRequestInterface::class);
        $requestMock->expects(self::atLeastOnce())
            ->method('getParsedBody')
            ->willReturn([
                'user' => [
                    'id' => '3532532523',
                ],
            ]);

        try {
            Validator::build($requestMock)
                ->body('user.id')->uuid()
                ->getValidator()->validate();
        } catch (ValidationFailed $e) {
            $errors = $e->getErrors();
            self::assertArrayHasKey('user.id', $errors);
        }
    }
}
