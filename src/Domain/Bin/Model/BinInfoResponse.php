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

use JetBrains\PhpStorm\Immutable;

#[Immutable]
final readonly class BinInfoResponse
{
    public function __construct(
        public bool $isExist,
        public array $binInfo = [],
        public ?\Throwable $exception = null,
    ) {}

    public static function fromExistAndInfo(bool $isExist, array $binInfo): self
    {
        return new self($isExist, $binInfo);
    }

    public static function fromExist(bool $isExist, array $binInfo = [], ?\Throwable $exception = null): self
    {
        return new self($isExist, $binInfo, $exception);
    }
}
