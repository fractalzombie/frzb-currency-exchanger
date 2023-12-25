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

namespace App\Infrastructure\Currency\Http\Request;

use App\Domain\Currency\ValueObject\ExchangeAmount;
use JetBrains\PhpStorm\Immutable;
use Symfony\Component\Validator\Constraints as Assert;

#[Immutable]
readonly class CurrencyExchangeRequest
{
    public function __construct(
        /** @var ExchangeAmount[] */
        #[Assert\Valid]
        #[Assert\Type('array')]
        #[Assert\All(
            new Assert\Type(ExchangeAmount::class)
        )]
        public array $amountList,
    ) {}
}
