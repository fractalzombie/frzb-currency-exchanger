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

namespace App\Infrastructure\Currency\Exchanger\Locator;

use App\Domain\Currency\Exchanger\ExchangerInterface;
use App\Domain\Currency\Exchanger\Locator\ExchangerLocatorInterface;
use App\Domain\Currency\Exchanger\ValueObject\ExchangeContext;
use Fp\Collections\ArrayList;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

#[AsAlias(ExchangerLocatorInterface::class)]
final readonly class ExchangerLocator implements ExchangerLocatorInterface
{
    /** @var ArrayList<ExchangerInterface> */
    private ArrayList $exchangerList;

    public function __construct(
        #[TaggedIterator(ExchangerInterface::class)]
        iterable $exchangerList
    ) {
        $this->exchangerList = ArrayList::collect($exchangerList);
    }

    #[\Override]
    public function get(ExchangeContext $context): ExchangerInterface
    {
        return $this->exchangerList
            ->first(fn (ExchangerInterface $exchanger) => $exchanger->canExchange($context))
            ->get()
        ;
    }
}
