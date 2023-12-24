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

namespace App\Domain\Currency\Exchanger\ValueObject;

use App\Domain\Common\Enum\AL2\Country;
use App\Domain\Common\Enum\AL3\Currency;
use App\Domain\Currency\Exchanger\ExchangedContextInterface;

readonly class ExchangedAmount implements ExchangedContextInterface
{
    public function __construct(
        public float $amount,
        public Currency $currency,
        public Country $country,
        public ExchangeContext $context,
    ) {}

    public function __toString(): string
    {
        return (string) $this->getExchangedInfo();
    }

    #[\Override]
    public function getExchangedInfo(): float|string
    {
        return $this->amount;
    }
}
