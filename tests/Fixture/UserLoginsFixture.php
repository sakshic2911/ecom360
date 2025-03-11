<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UserLoginsFixture
 */
class UserLoginsFixture extends TestFixture
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
                'user_id' => 1,
                'log_in' => 1741245373,
                'log_out' => 1741245373,
                'cretaed_at' => 1741245373,
                'updated_at' => 1741245373,
            ],
        ];
        parent::init();
    }
}
