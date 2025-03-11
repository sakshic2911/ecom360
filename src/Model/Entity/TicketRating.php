<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TicketRating Entity
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property int $user_type
 * @property string $rating
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime|null $updated_at
 *
 * @property \App\Model\Entity\Ticket $ticket
 * @property \App\Model\Entity\User $user
 */
class TicketRating extends Entity
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
        'ticket_id' => true,
        'user_id' => true,
        'user_type' => true,
        'rating' => true,
        'created_at' => true,
        'updated_at' => true,
        'ticket' => true,
        'user' => true,
    ];
}
