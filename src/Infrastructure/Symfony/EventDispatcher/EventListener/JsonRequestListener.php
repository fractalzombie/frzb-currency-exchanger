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

namespace App\Infrastructure\Symfony\EventDispatcher\EventListener;

use App\Domain\HttpFoundation\Enum\Header;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher\IsJsonRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\MethodRequestMatcher;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::REQUEST, priority: 24)]
final readonly class JsonRequestListener
{
    private const array ALLOWED_CONTENT_TYPES = ['application/json'];

    private const array ALLOWED_HTTP_METHODS = [
        Request::METHOD_GET,
        Request::METHOD_PUT,
        Request::METHOD_POST,
        Request::METHOD_PATCH,
        Request::METHOD_DELETE,
    ];

    private const array EXCEPTION_HEADERS = [
        Header::CONTENT_TYPE => 'application/json',
        Header::ACCEPT => 'application/json',
    ];

    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if ($this->isHttpMethodNotAllowed($request) || $this->isContentTypeNotAllowed($request)) {
            return;
        }

        if ($this->isJsonNotValid($request)) {
            throw new BadRequestHttpException('Json request is malformed', headers: self::EXCEPTION_HEADERS);
        }

        try {
            $payload = json_decode($request->getContent(), true, 512, \JSON_THROW_ON_ERROR);
            $request->request = new InputBag($payload ?? []);
        } catch (\JsonException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e, (int) $e->getCode(), self::EXCEPTION_HEADERS);
        }
    }

    private function isJsonNotValid(Request $request): bool
    {
        return !(new IsJsonRequestMatcher())->matches($request);
    }

    private function isHttpMethodNotAllowed(Request $request): bool
    {
        return !(new MethodRequestMatcher(self::ALLOWED_HTTP_METHODS))->matches($request);
    }

    private function isContentTypeNotAllowed(Request $request): bool
    {
        return !\in_array($request->headers->get(Header::CONTENT_TYPE), self::ALLOWED_CONTENT_TYPES, true);
    }
}
