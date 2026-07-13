<?php

declare(strict_types=1);

/*
 * This file is part of the "Locate" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Team YD <dev@Leuchtfeuer.com>, Leuchtfeuer Digital Marketing
 */

namespace Leuchtfeuer\Locate\Tests\Unit\Middleware;

use Leuchtfeuer\Locate\Middleware\LanguageRedirectMiddleware;
use ReflectionClass;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class LanguageRedirectMiddlewareTest extends UnitTestCase
{
    /**
     * @test
     */
    public function shouldExcludeRequestByHeaderReturnsTrueForConfiguredRequestHeader(): void
    {
        $request = (new ServerRequest())->withHeader('X-Tx-Solr-Iq', 'indexing-request');

        self::assertTrue($this->callShouldExcludeRequestByHeader($request, 'X-Internal-Request, X-Tx-Solr-Iq'));
    }

    /**
     * @test
     */
    public function shouldExcludeRequestByHeaderReturnsFalseWhenNoConfiguredRequestHeaderExists(): void
    {
        $request = (new ServerRequest())->withHeader('X-Other-Header', 'value');

        self::assertFalse($this->callShouldExcludeRequestByHeader($request, 'X-Internal-Request, X-Tx-Solr-Iq'));
    }

    private function callShouldExcludeRequestByHeader(ServerRequest $request, string $excludedHeaders): bool
    {
        $reflection = new ReflectionClass(LanguageRedirectMiddleware::class);
        $subject = $reflection->newInstanceWithoutConstructor();
        $method = $reflection->getMethod('shouldExcludeRequestByHeader');

        return $method->invoke($subject, $request, $excludedHeaders);
    }
}
