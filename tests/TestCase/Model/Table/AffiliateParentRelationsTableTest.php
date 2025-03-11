<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AffiliateParentRelationsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AffiliateParentRelationsTable Test Case
 */
class AffiliateParentRelationsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AffiliateParentRelationsTable
     */
    protected $AffiliateParentRelations;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.AffiliateParentRelations',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('AffiliateParentRelations') ? [] : ['className' => AffiliateParentRelationsTable::class];
        $this->AffiliateParentRelations = $this->getTableLocator()->get('AffiliateParentRelations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->AffiliateParentRelations);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\AffiliateParentRelationsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
