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

namespace App\Domain\Currency\Exchanger;

use App\Domain\Common\Enum\AL3\Currency;
use App\Domain\Currency\Exchanger\ValueObject\ExchangedAmount;
use App\Domain\Currency\Exchanger\ValueObject\ExchangedError;
use App\Domain\Currency\ValueObject\ExchangeAmount;

interface CurrencyRateExchangerInterface
{
    public function exchange(Currency $baseCurrency, ExchangeAmount $currencyCommission): ExchangedAmount;

    /** @return ExchangedAmount[]|ExchangedError[] */
    public function exchangeAll(Currency $baseCurrency, ExchangeAmount ...$currencyCommissions): iterable;
}
