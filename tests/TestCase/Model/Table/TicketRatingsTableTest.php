<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TicketRatingsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TicketRatingsTable Test Case
 */
class TicketRatingsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TicketRatingsTable
     */
    protected $TicketRatings;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.TicketRatings',
        'app.Tickets',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('TicketRatings') ? [] : ['className' => TicketRatingsTable::class];
        $this->TicketRatings = $this->getTableLocator()->get('TicketRatings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->TicketRatings);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\TicketRatingsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\TicketRatingsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
