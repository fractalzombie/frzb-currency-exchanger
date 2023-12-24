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

use App\Domain\Bin\Provider\BinProviderInterface;
use App\Domain\Common\Enum\AL3\Currency;
use App\Domain\Currency\Exchanger\Locator\ExchangerLocatorInterface;
use App\Domain\Currency\Exchanger\ValueObject\ExchangeContext;
use App\Domain\Currency\Exchanger\ValueObject\ExchangedAmount;
use App\Domain\Currency\RateProvider\CurrencyRateProviderInterface;
use App\Domain\Currency\ValueObject\ExchangeAmount;
use Fp\Collections\ArrayList;

readonly class CurrencyRateExchanger implements CurrencyRateExchangerInterface
{
    public function __construct(
        private CurrencyRateProviderInterface $currencyProvider,
        private ExchangerLocatorInterface $exchangerLocator,
        private BinProviderInterface $binProvider,
    ) {}

    #[\Override]
    public function exchange(Currency $baseCurrency, ExchangeAmount $currencyCommission): ExchangedAmount
    {
        throw new \LogicException('Not yet implemented');
    }

    #[\Override]
    public function exchangeAll(Currency $baseCurrency, ExchangeAmount ...$currencyCommissions): iterable
    {
        // This can be cached
        $rateList = $this->currencyProvider->getRates($baseCurrency, ...$currencyCommissions);

        return ArrayList::collect($currencyCommissions)
            ->map(fn (ExchangeAmount $exchangeAmount) => new ExchangeContext(
                $exchangeAmount,
                $this->binProvider->getInfo($exchangeAmount->getBin()),
                $rateList->getRate($exchangeAmount->currency),
            ))
            ->map(fn (ExchangeContext $context) => $this->exchangerLocator->get($context)->exchange($context))
            ->toList()
        ;
    }
}
