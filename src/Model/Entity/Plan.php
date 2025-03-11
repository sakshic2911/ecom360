<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Plan Entity
 *
 * @property int $id
 * @property string $plan_name
 * @property int $parent_affiliate
 * @property int $is_parent_numeric
 * @property int $affiliate_manager_commission
 * @property int $is_manager_numeric
 * @property int $dsd
 * @property int $is_dsd_numeric
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $updated_at
 */
class Plan extends Entity
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
        'plan_name' => true,
        'parent_affiliate' => true,
        'is_parent_numeric' => true,
        'affiliate_manager_commission' => true,
        'is_manager_numeric' => true,
        'dsd' => true,
        'is_dsd_numeric' => true,
        'created_at' => true,
        'updated_at' => true,
    ];
}
