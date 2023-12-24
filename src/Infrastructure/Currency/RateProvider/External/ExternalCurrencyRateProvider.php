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

namespace App\Infrastructure\Currency\RateProvider\External;

use App\Domain\Common\Enum\AL3\Currency;
use App\Domain\Currency\RateProvider\CurrencyRateProviderInterface;
use App\Domain\Currency\RateProvider\Exception\RateProviderException;
use App\Domain\Currency\RateProvider\Response\ExternalCurrencyRateProviderResponse;
use App\Domain\Currency\ValueObject\ExchangeAmount;
use App\Domain\HttpFoundation\Enum\HttpMethod;
use Fp\Collections\ArrayList;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class ExternalCurrencyRateProvider implements CurrencyRateProviderInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private SerializerInterface $serializer,
        #[Autowire(env: 'PROVIDER_EXTERNAL_URL')]
        private string $url,
    ) {}

    #[\Override]
    public function getRates(Currency $baseCurrency, ExchangeAmount ...$currencies): ExternalCurrencyRateProviderResponse
    {
        try {
            return $this->serializer->deserialize(
                $this->httpClient->request(HttpMethod::GET, $this->url, $this->getOptions($baseCurrency, ...$currencies))?->getContent(),
                ExternalCurrencyRateProviderResponse::class,
                JsonEncoder::FORMAT,
            );
        } catch (\Throwable $e) {
            throw RateProviderException::fromThrowable($e);
        }
    }

    private function getOptions(Currency $baseCurrency, ExchangeAmount ...$currencies): array
    {
        // API from External don't work with base parameter, but here can be another provider
        return [
            'query' => [
                'base' => $baseCurrency->value,
                'symbols' => ArrayList::collect($currencies)
                    ->map(fn (ExchangeAmount $cc) => $cc->currency->value)
                    ->mkString(),
            ],
        ];
    }
}
