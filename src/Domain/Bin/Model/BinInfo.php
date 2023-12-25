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

use App\Domain\Bin\Enum\BinType;
use Symfony\Component\Serializer\Attribute\SerializedName;

final readonly class BinInfo
{
    public function __construct(
        public string $scheme,
        public BinType $type,
        public string $brand,
        #[SerializedName('country')]
        public CountryInfo $countryInfo,
        #[SerializedName('bank')]
        public BankInfo $bankInfo,
        public ?bool $prepaid = null,
        #[SerializedName('number')]
        public ?NumberInfo $numberInfo = null,
    ) {}

    public static function fromEmpty(): self
    {
        return new self(
            'empty',
            BinType::Empty,
            'empty',
            CountryInfo::fromEmpty(),
            BankInfo::fromEmpty(),
        );
    }
}
