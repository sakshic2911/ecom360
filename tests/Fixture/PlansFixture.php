<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PlansFixture
 */
class PlansFixture extends TestFixture
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
                'plan_name' => 'Lorem ipsum dolor sit amet',
                'parent_affiliate' => 1,
                'is_parent_numeric' => 1,
                'affiliate_manager_commission' => 1,
                'is_manager_numeric' => 1,
                'dsd' => 1,
                'is_dsd_numeric' => 1,
                'created_at' => 1741263531,
                'updated_at' => 1741263531,
            ],
        ];
        parent::init();
    }
}
