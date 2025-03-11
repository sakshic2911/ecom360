<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CommentNote Entity
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property string $comment_notes
 * @property int $type
 * @property int $seen
 * @property int $category
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $updated_at
 *
 * @property \App\Model\Entity\Ticket $ticket
 * @property \App\Model\Entity\User $user
 */
class CommentNote extends Entity
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
        'comment_notes' => true,
        'type' => true,
        'seen' => true,
        'category' => true,
        'created_at' => true,
        'updated_at' => true,
        'ticket' => true,
        'user' => true,
    ];
}
