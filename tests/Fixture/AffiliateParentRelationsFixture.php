<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AffiliateParentRelationsFixture
 */
class AffiliateParentRelationsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'client_id' => 1,
                'affiliate_id' => 1,
                'parent_affiliate_id' => 1,
                'created_at' => 1741688023,
                'updated_at' => 1741688023,
            ],
        ];
        parent::init();
    }
}
