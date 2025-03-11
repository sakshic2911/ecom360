<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OverridePartnersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OverridePartnersTable Test Case
 */
class OverridePartnersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OverridePartnersTable
     */
    protected $OverridePartners;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.OverridePartners',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('OverridePartners') ? [] : ['className' => OverridePartnersTable::class];
        $this->OverridePartners = $this->getTableLocator()->get('OverridePartners', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->OverridePartners);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\OverridePartnersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
