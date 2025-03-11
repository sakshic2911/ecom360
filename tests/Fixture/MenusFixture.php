<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MenusFixture
 */
class MenusFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'menus';
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
                'menu_name' => 'Lorem ipsum dolor sit amet',
                'icon' => 'Lorem ipsum dolor ',
                'url' => 'Lorem ipsum dolor sit amet',
                'user' => 1,
                'status' => 'Lorem ipsum dolor sit amet',
                'folder' => 'Lorem ipsum dolor sit amet',
                'sequence' => 1,
                'sub_sequence' => 1,
                'parent' => 'Lorem ipsum dolor sit amet',
                'created_at' => 1741245255,
                'updated_at' => 1741245255,
            ],
        ];
        parent::init();
    }
}
