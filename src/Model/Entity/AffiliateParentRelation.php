<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AffiliateParentRelation Entity
 *
 * @property int $id
 * @property int $client_id
 * @property int $affiliate_id
 * @property int $parent_affiliate_id
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $updated_at
 */
class AffiliateParentRelation extends Entity
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
        'client_id' => true,
        'affiliate_id' => true,
        'parent_affiliate_id' => true,
        'created_at' => true,
        'updated_at' => true,
    ];
}
