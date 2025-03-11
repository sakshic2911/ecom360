<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersIssuesFixture
 */
class UsersIssuesFixture extends TestFixture
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
                'staff_id' => 1,
                'issue_type' => 1,
                'created_at' => 1741695084,
                'updated_at' => 1741695084,
            ],
        ];
        parent::init();
    }
}
