<?php
declare(strict_types=1);

namespace App\Test\TestCase\Middleware;

use App\Middleware\TwofactorMiddleware;
use Cake\TestSuite\TestCase;

/**
 * App\Middleware\TwofactorMiddleware Test Case
 */
class TwofactorMiddlewareTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Middleware\TwofactorMiddleware
     */
    protected $Twofactor;

    /**
     * Test process method
     *
     * @return void
     * @uses \App\Middleware\TwofactorMiddleware::process()
     */
    public function testProcess(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
