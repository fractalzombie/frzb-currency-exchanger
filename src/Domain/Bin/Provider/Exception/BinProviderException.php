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

namespace App\Domain\Bin\Provider\Exception;

use App\Domain\Bin\Contract\BinInterface;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class BinProviderException extends \LogicException
{
    private const string TEMPLATE_BIN_NOT_EXIST = 'Bin "%s" not exist or not valid in any provider';

    public static function fromThrowable(\Throwable $e): self
    {
        return new self($e->getMessage(), $e->getCode(), $e);
    }

    public static function binNotExist(BinInterface $bin, ?\Throwable $previous = null): self
    {
        $message = sprintf(self::TEMPLATE_BIN_NOT_EXIST, (string) $bin);

        return new self($message, previous: $previous);
    }
}
