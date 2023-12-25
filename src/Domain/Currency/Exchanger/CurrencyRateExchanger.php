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
use App\Domain\Currency\Exchanger\ValueObject\ExchangedError;
use App\Domain\Currency\RateProvider\CurrencyRateProviderInterface;
use App\Domain\Currency\RateProvider\Response\ExternalCurrencyRateProviderResponse;
use App\Domain\Currency\ValueObject\ExchangeAmount;
use Fp\Collections\ArrayList;

class CurrencyRateExchanger implements CurrencyRateExchangerInterface
{
    private ExternalCurrencyRateProviderResponse $rateList;

    public function __construct(
        private readonly CurrencyRateProviderInterface $currencyProvider,
        private readonly ExchangerLocatorInterface $exchangerLocator,
        private readonly BinProviderInterface $binProvider,
    ) {}

    #[\Override]
    public function exchange(Currency $baseCurrency, ExchangeAmount $exchangeAmount): ExchangedAmount|ExchangedError
    {
        $rateList = $this->getRates($baseCurrency, $exchangeAmount);
        $context = new ExchangeContext(
            $exchangeAmount,
            $this->binProvider->getInfo($exchangeAmount->getBin()),
            $rateList->getRate($exchangeAmount->currency),
        );

        return $this->exchangerLocator->get($context)->exchange($context);
    }

    #[\Override]
    public function exchangeAll(Currency $baseCurrency, ExchangeAmount ...$exchangeAmountList): iterable
    {
        return ArrayList::collect($exchangeAmountList)
            ->map(fn (ExchangeAmount $exchangeAmount) => $this->exchange($baseCurrency, $exchangeAmount))
            ->toList()
        ;
    }

    private function getRates(Currency $baseCurrency, ExchangeAmount ...$exchangeAmountList): ExternalCurrencyRateProviderResponse
    {
        return $this->rateList ??= $this->currencyProvider->getRates($baseCurrency, ...$exchangeAmountList);
    }
}
