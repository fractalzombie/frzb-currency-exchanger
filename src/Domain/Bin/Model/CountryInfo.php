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

namespace App\Domain\Bin\Model;

use App\Domain\Common\Enum\AL2\Country;
use App\Domain\Common\Enum\AL3\Currency;

final readonly class CountryInfo
{
    public function __construct(
        public string $numeric,
        public Country $alpha2,
        public string $name,
        public string $emoji,
        public Currency $currency,
        public int $latitude,
        public int $longitude,
    ) {}

    public static function fromEmpty(): self
    {
        return new self(
            'empty',
            Country::Empty,
            'empty',
            'empty',
            Currency::Empty,
            0,
            0
        );
    }
}
