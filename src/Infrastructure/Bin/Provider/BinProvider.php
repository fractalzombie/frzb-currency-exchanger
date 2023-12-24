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

namespace App\Infrastructure\Bin\Provider;

use App\Domain\Bin\Contract\BinInterface;
use App\Domain\Bin\Model\BinInfo;
use App\Domain\Bin\Provider\BinProviderInterface;
use App\Domain\Bin\Provider\HttpBinProviderInterface;
use App\Domain\Bin\Provider\StorageBinProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(BinProviderInterface::class)]
readonly class BinProvider implements BinProviderInterface
{
    public function __construct(
        private HttpBinProviderInterface $httpProvider,
        private StorageBinProviderInterface $storageProvider,
    ) {}

    #[\Override]
    public function getInfo(BinInterface $bin): BinInfo
    {
        return $this->getFromProvider($bin);
    }

    private function getFromProvider(BinInterface $bin): BinInfo
    {
        return match (true) {
            $this->storageProvider->isExist($bin) => $this->getFromLocalProvider($bin),
            $this->httpProvider->isExist($bin)->isExist => $this->getFromHttpProvider($bin),
            default => $this->storageProvider->store($bin, BinInfo::fromEmpty()),
        };
    }

    private function getFromLocalProvider(BinInterface $bin): BinInfo
    {
        return $this->storageProvider->getInfo($bin);
    }

    private function getFromHttpProvider(BinInterface $bin): BinInfo
    {
        return $this->storageProvider->store($bin, $this->httpProvider->getInfo($bin));
    }
}
