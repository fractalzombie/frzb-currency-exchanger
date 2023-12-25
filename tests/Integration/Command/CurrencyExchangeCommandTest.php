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

namespace App\Tests\Integration\Command;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface as HttpClient;

class CurrencyExchangeCommandTest extends WebTestCase
{
    private Application $application;
    private MockHttpClient $mockHttpClient;

    private string $resourcesDirectory;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        self::bootKernel();

        $this->mockHttpClient = new MockHttpClient();
        self::getContainer()->set(HttpClient::class, $this->mockHttpClient);

        $this->application = new Application(self::$kernel);
        $projectDirectory = self::getContainer()->getParameter('kernel.project_dir');
        $this->resourcesDirectory = "{$projectDirectory}/tests/Resources";
    }

    #[DataProvider('exchangeInputDataProvider')]
    public function testExecuteCommand(string $fileName, iterable $expectedOutputs): void
    {
        $this->mockHttpClient->setResponseFactory([
            $this->getCurrencyProviderMockResponse(),
            ...$this->getBinInfoProviderMockResponses(),
        ]);

        $command = $this->application->find('currency:exchange');
        $commandTester = new CommandTester($command);
        $filePath = "{$this->resourcesDirectory}/{$fileName}";

        $commandTester->execute(['file_path' => $filePath]);

        foreach ($expectedOutputs as $output) {
            self::assertStringContainsString($output, $commandTester->getDisplay());
        }

        $commandTester->assertCommandIsSuccessful();
    }

    public static function exchangeInputDataProvider(): iterable
    {
        yield 'exchange input json data' => [
            'file_name' => 'exchange_input_data.json',
            'expected_outputs' => [
                '[OK] BIN: 45717360 exchanged: 1',
                '[OK] BIN: 516793 exchanged: 0.44285763632708',
                '[OK] BIN: 45417360 exchanged: 1.5282343940505',
                '[ERROR] BIN info is not provided by external service for "41417360"',
                '[OK] BIN: 4745030 exchanged: 23.942289505376',
            ],
        ];

        yield 'exchange input txt data' => [
            'file_name' => 'exchange_input_data.txt',
            'expected_outputs' => [
                '[OK] BIN: 45717360 exchanged: 1',
                '[OK] BIN: 516793 exchanged: 0.44285763632708',
                '[OK] BIN: 45417360 exchanged: 1.5282343940505',
                '[ERROR] BIN info is not provided by external service for "41417360"',
                '[OK] BIN: 4745030 exchanged: 23.942289505376',
            ],
        ];
    }

    private function getCurrencyProviderMockResponse(): MockResponse
    {
        return new MockResponse(
            file_get_contents("{$this->resourcesDirectory}/currency_exchange_provider_response.json"),
        );
    }

    private function getBinInfoProviderMockResponses(): iterable
    {
        return [
            new MockResponse(file_get_contents("{$this->resourcesDirectory}/bin/45717360.json")),
            new MockResponse(file_get_contents("{$this->resourcesDirectory}/bin/516793.json")),
            new MockResponse(file_get_contents("{$this->resourcesDirectory}/bin/45417360.json")),
            new MockResponse(file_get_contents("{$this->resourcesDirectory}/bin/41417360.json")),
            new MockResponse(file_get_contents("{$this->resourcesDirectory}/bin/4745030.json")),
        ];
    }
}
