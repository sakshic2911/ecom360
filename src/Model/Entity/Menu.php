<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Menu Entity
 *
 * @property int $id
 * @property string $menu_name
 * @property string|null $icon
 * @property string|null $url
 * @property int $user
 * @property string $status
 * @property string|null $folder
 * @property int $sequence
 * @property int $sub_sequence
 * @property string|null $parent
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $updated_at
 */
class Menu extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'menu_name' => true,
        'icon' => true,
        'url' => true,
        'user' => true,
        'status' => true,
        'folder' => true,
        'sequence' => true,
        'sub_sequence' => true,
        'parent' => true,
        'created_at' => true,
        'updated_at' => true,
    ];
}
