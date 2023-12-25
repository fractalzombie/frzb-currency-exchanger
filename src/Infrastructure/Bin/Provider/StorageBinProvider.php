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
use App\Domain\Bin\Provider\Exception\BinProviderException;
use App\Domain\Bin\Provider\StorageBinProviderInterface;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

#[AsAlias(StorageBinProviderInterface::class)]
readonly class StorageBinProvider implements StorageBinProviderInterface
{
    private const string DEFAULT_FILE_EXTENSION = 'json';

    public function __construct(
        #[Autowire(service: 'bin.storage')]
        private FilesystemOperator $binStorage,
        private SerializerInterface $serializer,
        private ClockInterface $clock,
        #[Autowire(env: 'PROVIDER_BINLIST_LOCAL_DATETIME_CACHE_FORMAT')]
        private string $dateFormat,
    ) {}

    #[\Override]
    public function getInfo(BinInterface $bin): BinInfo
    {
        $currentFileName = $this->getCurrentFileName($bin);

        try {
            $rawBinInfo = $this->binStorage->read($currentFileName);
        } catch (FilesystemException $e) {
            throw BinProviderException::fromThrowable($e);
        }

        return $this->serializer->deserialize($rawBinInfo, BinInfo::class, JsonEncoder::FORMAT);
    }

    #[\Override]
    public function store(BinInterface $bin, BinInfo $binInfo): BinInfo
    {
        $rawBinInfo = $this->serializer->serialize($binInfo, JsonEncoder::FORMAT);

        try {
            $this->binStorage->write($this->getCurrentFileName($bin), $rawBinInfo);

            return $binInfo;
        } catch (FilesystemException $e) {
            throw BinProviderException::fromThrowable($e);
        }
    }

    #[\Override]
    public function isExist(BinInterface $bin, bool $throws = false): bool
    {
        try {
            return $this->binStorage->fileExists(
                $this->getCurrentFileName($bin),
            );
        } catch (FilesystemException $e) {
            return $throws ? throw BinProviderException::fromThrowable($e) : false;
        }
    }

    private function getCurrentFileName(BinInterface $bin): string
    {
        $currentDate = $this->getCurrentDate();
        $currentBin = (string) $bin;
        $fileExtension = self::DEFAULT_FILE_EXTENSION;

        return "{$currentBin}_{$currentDate}.{$fileExtension}";
    }

    private function getCurrentDate(): string
    {
        return $this->clock->now()->format($this->dateFormat);
    }
}
