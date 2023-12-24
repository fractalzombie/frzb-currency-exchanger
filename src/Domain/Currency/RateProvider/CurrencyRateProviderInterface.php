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

namespace App\Domain\Currency\RateProvider;

use App\Domain\Common\Enum\AL3\Currency;
use App\Domain\Currency\RateProvider\Response\ExternalCurrencyRateProviderResponse;
use App\Domain\Currency\ValueObject\ExchangeAmount;

interface CurrencyRateProviderInterface
{
    public function getRates(Currency $baseCurrency, ExchangeAmount ...$currencies): ExternalCurrencyRateProviderResponse;
}
