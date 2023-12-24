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
use App\Domain\Bin\Model\BinInfoResponse;
use App\Domain\Bin\Provider\Exception\BinProviderException;
use App\Domain\Bin\Provider\HttpBinProviderInterface;
use App\Domain\HttpFoundation\Enum\HttpMethod;
use Illuminate\Support\Arr;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsAlias(HttpBinProviderInterface::class)]
readonly class HttpBinProvider implements HttpBinProviderInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private SerializerInterface $serializer,
        #[Autowire(env: 'PROVIDER_BINLIST_HTTP_URL')]
        private string $url,
    ) {}

    #[\Override]
    public function getInfo(BinInterface $bin): BinInfo
    {
        return $this->serializer->denormalize(
            $this->getInfoForBin($bin),
            BinInfo::class,
        );
    }

    public function isExist(BinInterface $bin): BinInfoResponse
    {
        $binInfo = $this->getInfoForBin($bin);

        return match (true) {
            !Arr::exists($binInfo, 'number') => BinInfoResponse::fromExist(false, $binInfo),
            !Arr::exists($binInfo, 'scheme') => BinInfoResponse::fromExist(false, $binInfo),
            !Arr::exists($binInfo, 'type') => BinInfoResponse::fromExist(false, $binInfo),
            !Arr::exists($binInfo, 'brand') => BinInfoResponse::fromExist(false, $binInfo),
            !Arr::exists($binInfo, 'country') => BinInfoResponse::fromExist(false, $binInfo),
            !Arr::exists($binInfo, 'bank') => BinInfoResponse::fromExist(false, $binInfo),
            default => BinInfoResponse::fromExistAndInfo(true, $binInfo),
        };
    }

    private function getInfoForBin(BinInterface $bin): array
    {
        try {
            $rawBinInfo = $this->httpClient->request(HttpMethod::GET, "{$this->url}/{$bin}")?->getContent();

            return json_validate($rawBinInfo) ? json_decode($rawBinInfo, true) : [];
        } catch (TransportExceptionInterface $e) {
            throw BinProviderException::fromThrowable($e);
        } catch (ClientExceptionInterface $e) {
            throw BinProviderException::fromThrowable($e);
        } catch (RedirectionExceptionInterface $e) {
            throw BinProviderException::fromThrowable($e);
        } catch (ServerExceptionInterface $e) {
            throw BinProviderException::fromThrowable($e);
        } catch (\Throwable $e) {
            throw BinProviderException::fromThrowable($e);
        }
    }
}
