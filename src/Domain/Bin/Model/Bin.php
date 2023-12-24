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

use App\Domain\Bin\Contract\BinInterface;

final readonly class Bin implements BinInterface
{
    private string $bin;

    public function __construct(int|string $value)
    {
        $this->bin = (string) $value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    #[\Override]
    public function getValue(): string
    {
        return $this->bin;
    }
}
