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

use App\Domain\Bin\Model\BinInfo;
use App\Domain\Currency\ValueObject\ExchangeAmount;

readonly class ExchangeContext
{
    public function __construct(
        public ExchangeAmount $exchangeAmount,
        public BinInfo $binInfo,
        public float $rate,
    ) {}
}
