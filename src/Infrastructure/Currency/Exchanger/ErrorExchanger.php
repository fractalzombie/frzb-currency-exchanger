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

namespace App\Infrastructure\Currency\Exchanger;

use App\Domain\Currency\Exchanger\ExchangerInterface;
use App\Domain\Currency\Exchanger\ValueObject\ExchangeContext;
use App\Domain\Currency\Exchanger\ValueObject\ExchangedAmount;
use App\Domain\Currency\Exchanger\ValueObject\ExchangedError;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(ExchangerInterface::class)]
class ErrorExchanger implements ExchangerInterface
{
    private const string TEMPLATE_NO_BIN_INFO_PROVIDED = 'BIN info is not provided by external service for "%s"';

    #[\Override]
    public function exchange(ExchangeContext $context): ExchangedAmount|ExchangedError
    {
        return new ExchangedError(
            sprintf(self::TEMPLATE_NO_BIN_INFO_PROVIDED, $context->exchangeAmount->bin),
            $context->exchangeAmount->currency,
            $context->binInfo->countryInfo->alpha2,
            $context,
        );
    }

    #[\Override]
    public function canExchange(ExchangeContext $context): bool
    {
        return $context->exchangeAmount->currency->isEmpty()
            || $context->binInfo->countryInfo->alpha2->isEmpty();
    }
}
