<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserPermission Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $menu_id
 * @property int $permission
 * @property string $type
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime|null $updated_at
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Menu $menu
 */
class UserPermission extends Entity
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
        'user_id' => true,
        'menu_id' => true,
        'permission' => true,
        'type' => true,
        'created_at' => true,
        'updated_at' => true,
        'user' => true,
        'menu' => true,
    ];
}
