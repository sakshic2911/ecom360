<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OverridePartnersFixture
 */
class OverridePartnersFixture extends TestFixture
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
                'override_partner' => 1,
                'created_at' => 1741263722,
                'updated_at' => 1741263722,
            ],
        ];
        parent::init();
    }
}
