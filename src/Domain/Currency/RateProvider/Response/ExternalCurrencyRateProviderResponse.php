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

namespace App\Domain\Currency\RateProvider\Response;

use App\Domain\Common\Enum\AL3\Currency;
use Symfony\Component\Serializer\Attribute\SerializedName;

final readonly class ExternalCurrencyRateProviderResponse
{
    public function __construct(
        #[SerializedName('date')]
        public \DateTimeImmutable $actualDate,
        #[SerializedName('base')]
        public Currency $baseCurrency,
        /** @var array<Currency, float> */
        public array $rates,
    ) {}

    public function getRate(Currency $currency): ?float
    {
        return $this->rates[$currency->value] ?? null;
    }

    public function hasRate(Currency $currency): bool
    {
        return \array_key_exists($currency->value, $this->rates);
    }
}
