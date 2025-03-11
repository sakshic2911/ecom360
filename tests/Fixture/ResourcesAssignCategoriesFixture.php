<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ResourcesAssignCategoriesFixture
 */
class ResourcesAssignCategoriesFixture extends TestFixture
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
                'resources_id' => 1,
                'category_id' => 1,
                'ranking' => 1,
                'is_deleted' => 1,
                'created_at' => 1741255215,
                'updated_at' => 1741255215,
            ],
        ];
        parent::init();
    }
}
