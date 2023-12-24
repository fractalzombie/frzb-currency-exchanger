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
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand('currency:commission:rate')]
class CurrencyCommissionCommand extends Command
{
    private const array SERIALIZER_CONTEXT = [
        'disable_type_enforcement' => true,
        'collect_denormalization_errors' => true,
    ];

    public function __construct(
        private readonly CurrencyRateExchangerInterface $currencyRateService,
        private readonly SerializerInterface $serializer,
        #[Autowire(param: 'kernel.project_dir')]
        private readonly string $projectDirectory,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Command for commission rate')
            ->addArgument('file_name', InputArgument::OPTIONAL, 'File name for rate', 'test.txt')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $fileName = $input->getArgument('file_name');

            $content = file_get_contents("{$this->projectDirectory}/{$fileName}");

            if (!json_validate($content)) {
                $rows = ArrayList::collect(explode(\PHP_EOL, $content))
                    ->filter(static fn (string $row) => !empty($row))
                    ->map(fn (string $row) => $this->serializer->deserialize($row, ExchangeAmount::class, JsonEncoder::FORMAT, self::SERIALIZER_CONTEXT))
                    ->toList()
                ;

                foreach ($this->currencyRateService->exchangeAll(Currency::Euro, ...$rows) as $exchanged) {
                    match ($exchanged::class) {
                        ExchangedAmount::class => $io->success(sprintf('BIN: %s exchanged: %s', $exchanged->context->exchangeAmount->bin, $exchanged->amount)),
                        ExchangedError::class => $io->error($exchanged->getExchangedInfo()),
                    };
                }
            }

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $io->error($e->getMessage());

            return self::FAILURE;
        }
    }
}
