<?php

declare(strict_types=1);

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 *
 * Copyright (c) 2023 Mykhailo Shtanko fractalzombie@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE.MD
 * file that was distributed with this source code.
 */

namespace App\Domain\Currency\Helper;

use App\Domain\Currency\Exchanger\ValueObject\ExchangeContext;

final readonly class CommissionHelper
{
    private const float COMMISSION_EUROPE = 0.01;
    private const float COMMISSION_NOT_EUROPE = 0.02;

    private function __construct() {}

    public static function getCommissionByContext(ExchangeContext $context): float
    {
        return $context->binInfo->countryInfo->alpha2->isEurope()
            ? self::COMMISSION_EUROPE
            : self::COMMISSION_NOT_EUROPE;
    }
}
