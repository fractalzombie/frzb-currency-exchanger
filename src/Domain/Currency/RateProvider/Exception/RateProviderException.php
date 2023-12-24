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

namespace App\Domain\Currency\RateProvider\Exception;

use App\Domain\Currency\Enum\ProviderType;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
class RateProviderException extends \LogicException
{
    public static function fromProviderAndParameter(ProviderType $provider, string $parameter, ?\Throwable $previous = null): self
    {
        $message = sprintf('Parameter: "%s" is null or not exist', $parameter);

        return new self($message, $previous?->getCode(), $previous);
    }

    public static function fromThrowable(\Throwable $e): static
    {
        return new self($e->getMessage(), $e->getCode(), $e);
    }
}
