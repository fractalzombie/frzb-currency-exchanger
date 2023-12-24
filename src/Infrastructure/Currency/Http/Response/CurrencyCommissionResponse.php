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

namespace App\Infrastructure\Currency\Http\Response;

use App\Domain\Currency\Exchanger\ValueObject\ExchangedAmount;
use App\Domain\Currency\Exchanger\ValueObject\ExchangedError;
use Fp\Collections\ArrayList;

class CurrencyCommissionResponse
{
    public static function fromExchangedContext(ExchangedAmount|ExchangedError ...$exchangedContext): array
    {
        return ArrayList::collect($exchangedContext)
            ->map(static fn (ExchangedAmount|ExchangedError $exchangedContext) => match ($exchangedContext::class) {
                ExchangedAmount::class => [
                    'bin' => $exchangedContext->context->exchangeAmount->bin,
                    'amount' => $exchangedContext->amount,
                ],
                ExchangedError::class => [
                    'bin' => $exchangedContext->context->exchangeAmount->bin,
                    'error' => $exchangedContext->error,
                ],
            })
            ->toList()
        ;
    }
}
