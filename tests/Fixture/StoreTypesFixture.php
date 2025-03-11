<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StoreTypesFixture
 */
class StoreTypesFixture extends TestFixture
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
                'store_name' => 'Lorem ipsum dolor sit amet',
                'view' => 1,
                'created_at' => 1741260189,
                'updated_at' => 1741260189,
            ],
        ];
        parent::init();
    }
}
