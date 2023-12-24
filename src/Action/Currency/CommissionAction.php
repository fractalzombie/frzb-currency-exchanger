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

namespace App\Action\Currency;

use App\Domain\Common\Enum\AL3\Currency;
use App\Domain\Currency\Exchanger\CurrencyRateExchangerInterface;
use App\Domain\HttpFoundation\Enum\StatusCode;
use App\Infrastructure\Currency\Http\Request\CurrencyCommissionRequest;
use App\Infrastructure\Currency\Http\Response\CurrencyCommissionResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final readonly class CommissionAction
{
    public function __construct(
        private CurrencyRateExchangerInterface $currencyRateService,
    ) {}

    #[Route(methods: Request::METHOD_POST)]
    public function __invoke(#[MapRequestPayload] CurrencyCommissionRequest $request): Response
    {
        $exchangedList = $this->currencyRateService->exchangeAll(Currency::Euro, ...$request->commissions);

        return new JsonResponse(CurrencyCommissionResponse::fromExchangedContext(...$exchangedList), StatusCode::OK);
    }
}
