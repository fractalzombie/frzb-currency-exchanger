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

namespace App\Command\Currency;

use App\Domain\Common\Enum\AL3\Currency;
use App\Domain\Currency\Exchanger\CurrencyRateExchangerInterface;
use App\Domain\Currency\Exchanger\ValueObject\ExchangedAmount;
use App\Domain\Currency\Exchanger\ValueObject\ExchangedError;
use App\Domain\Currency\ValueObject\ExchangeAmount;
use Fp\Collections\ArrayList;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand('currency:exchange')]
class CurrencyExchangeCommand extends Command
{
    private const array SERIALIZER_CONTEXT = [
        'disable_type_enforcement' => true,
        'collect_denormalization_errors' => true,
    ];

    public function __construct(
        private readonly CurrencyRateExchangerInterface $currencyRateService,
        private readonly DenormalizerInterface&SerializerInterface $serializer,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Command for currency exchange')
            ->addArgument('file_path', InputArgument::OPTIONAL, 'Path to file with data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $rawContent = file_get_contents($input->getArgument('file_path'));

            $exchangeAmountList = match (true) {
                json_validate($rawContent) => $this->whenJsonValid($rawContent),
                !json_validate($rawContent) => $this->whenJsonInvalid($rawContent),
            };

            foreach ($this->currencyRateService->exchangeAll(Currency::Euro, ...$exchangeAmountList) as $exchanged) {
                match ($exchanged::class) {
                    ExchangedAmount::class => $io->success(sprintf('BIN: %s exchanged: %s', $exchanged->context->exchangeAmount->bin, $exchanged->amount)),
                    ExchangedError::class => $io->error($exchanged->getExchangedInfo()),
                };
            }

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $io->error($e->getMessage());

            return self::FAILURE;
        }
    }

    private function whenJsonValid(string $rawContent): iterable
    {
        return ArrayList::collect(json_decode($rawContent, true))
            ->filter(static fn (array $row) => !empty($row))
            ->map(fn (array $row) => $this->serializer->denormalize($row, ExchangeAmount::class, JsonEncoder::FORMAT, self::SERIALIZER_CONTEXT))
            ->toList()
        ;
    }

    private function whenJsonInvalid(string $rawContent): iterable
    {
        return ArrayList::collect(explode(\PHP_EOL, $rawContent))
            ->filter(static fn (string $row) => !empty($row))
            ->map(trim(...))
            ->map(fn (string $row) => $this->serializer->deserialize($row, ExchangeAmount::class, JsonEncoder::FORMAT, self::SERIALIZER_CONTEXT))
            ->toList()
        ;
    }
}
