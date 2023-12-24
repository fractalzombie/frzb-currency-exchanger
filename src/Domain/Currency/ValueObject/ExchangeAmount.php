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

namespace App\Domain\Currency\ValueObject;

use App\Domain\Bin\Contract\BinInterface;
use App\Domain\Bin\Model\Bin;
use App\Domain\Common\Enum\AL3\Currency;
use JetBrains\PhpStorm\Immutable;
use Symfony\Component\Validator\Constraints as Assert;

#[Immutable]
readonly class ExchangeAmount
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        #[Assert\Regex('/^\d{6,18}/')]
        public int $bin,
        #[Assert\NotBlank]
        #[Assert\Positive]
        public float $amount,
        #[Assert\Choice(callback: [Currency::class, 'cases'])]
        #[Assert\NotBlank]
        public Currency $currency,
    ) {}

    public function getBin(): BinInterface
    {
        return new Bin($this->bin);
    }
}
