<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TicketWatchersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TicketWatchersTable Test Case
 */
class TicketWatchersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TicketWatchersTable
     */
    protected $TicketWatchers;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.TicketWatchers',
        'app.Tickets',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('TicketWatchers') ? [] : ['className' => TicketWatchersTable::class];
        $this->TicketWatchers = $this->getTableLocator()->get('TicketWatchers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->TicketWatchers);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\TicketWatchersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\TicketWatchersTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
