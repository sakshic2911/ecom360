<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $password
 * @property int $assign_manager
 * @property string|null $contact_no
 * @property int $role_id
 * @property int $parent_role
 * @property string|null $image
 * @property string|null $address
 * @property string|null $city
 * @property string|null $state
 * @property int|null $zip_code
 * @property string|null $country
 * @property string|null $citizenship
 * @property string|null $birth_country
 * @property \Cake\I18n\FrozenDate|null $dob
 * @property string|null $organisation_name
 * @property int $affiliate_client
 * @property int $client_plan
 * @property string|null $ssn
 * @property int $parent_affiliate
 * @property int $referrer_affiliate
 * @property int $override_partner
 * @property float $override_percentage
 * @property int $status
 * @property int $deactivation_template_id
 * @property string|null $custom_message
 * @property string $onboarding_status
 * @property int $has_store
 * @property int $user_type
 * @property int|null $issue_type
 * @property int $store_permission
 * @property string|null $manager_bio
 * @property int $store_capacity
 * @property string|null $embed_code
 * @property int $delete_user
 * @property \Cake\I18n\FrozenTime $last_login
 * @property string|null $firebaseid
 * @property string|null $devicename
 * @property int $is_appointment
 * @property string|null $calender_id
 * @property string|null $appointment_id
 * @property string|null $event_address
 * @property int $password_change_required
 * @property int $amazon
 * @property int $walmart
 * @property string|null $secret
 * @property \Cake\I18n\FrozenTime|null $token_expiration
 * @property string|null $device_id
 * @property int $remember_me
 * @property string|null $session_login
 * @property int|null $termination_access_days
 * @property \Cake\I18n\FrozenDate|null $termination_date
 * @property \Cake\I18n\FrozenDate|null $internal_hold_date
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $updated_at
 *
 * @property \App\Model\Entity\Role $role
 * @property \App\Model\Entity\DeactivationTemplate $deactivation_template
 * @property \App\Model\Entity\UserToken $user_token
 * @property \App\Model\Entity\ActivityLog[] $activity_logs
 * @property \App\Model\Entity\BuybackReceiveInventoryTemporary[] $buyback_receive_inventory_temporary
 * @property \App\Model\Entity\ClientWeeklyMeeting[] $client_weekly_meeting
 * @property \App\Model\Entity\CommentNote[] $comment_notes
 * @property \App\Model\Entity\EmailLog[] $email_logs
 * @property \App\Model\Entity\ExportJob[] $export_jobs
 * @property \App\Model\Entity\ManageNotification[] $manage_notification
 * @property \App\Model\Entity\Notification[] $notification
 * @property \App\Model\Entity\StaffNotificationSetting[] $staff_notification_setting
 * @property \App\Model\Entity\TicketRating[] $ticket_rating
 * @property \App\Model\Entity\TicketsActivity[] $tickets_activities
 * @property \App\Model\Entity\UserLogin[] $user_login
 * @property \App\Model\Entity\UserOnboarding[] $user_onboarding
 * @property \App\Model\Entity\UserPermission[] $user_permissions
 * @property \App\Model\Entity\UserWalmartOnboarding[] $user_walmart_onboarding
 */
class User extends Entity
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
        'first_name' => true,
        'last_name' => true,
        'email' => true,
        'password' => true,
        'assign_manager' => true,
        'contact_no' => true,
        'role_id' => true,
        'parent_role' => true,
        'image' => true,
        'address' => true,
        'city' => true,
        'state' => true,
        'zip_code' => true,
        'country' => true,
        'citizenship' => true,
        'birth_country' => true,
        'dob' => true,
        'organisation_name' => true,
        'affiliate_client' => true,
        'client_plan' => true,
        'ssn' => true,
        'parent_affiliate' => true,
        'referrer_affiliate' => true,
        'override_partner' => true,
        'override_percentage' => true,
        'status' => true,
        'deactivation_template_id' => true,
        'custom_message' => true,
        'onboarding_status' => true,
        'has_store' => true,
        'user_type' => true,
        'issue_type' => true,
        'store_permission' => true,
        'manager_bio' => true,
        'store_capacity' => true,
        'embed_code' => true,
        'delete_user' => true,
        'last_login' => true,
        'firebaseid' => true,
        'devicename' => true,
        'is_appointment' => true,
        'calender_id' => true,
        'appointment_id' => true,
        'event_address' => true,
        'password_change_required' => true,
        'amazon' => true,
        'walmart' => true,
        'secret' => true,
        'token_expiration' => true,
        'device_id' => true,
        'remember_me' => true,
        'session_login' => true,
        'termination_access_days' => true,
        'termination_date' => true,
        'internal_hold_date' => true,
        'created_at' => true,
        'updated_at' => true,
        'role' => true,
        'deactivation_template' => true,
        'user_token' => true,
        'activity_logs' => true,
        'buyback_receive_inventory_temporary' => true,
        'client_weekly_meeting' => true,
        'comment_notes' => true,
        'email_logs' => true,
        'export_jobs' => true,
        'manage_notification' => true,
        'notification' => true,
        'staff_notification_setting' => true,
        'ticket_rating' => true,
        'tickets_activities' => true,
        'user_login' => true,
        'user_onboarding' => true,
        'user_permissions' => true,
        'user_walmart_onboarding' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'password',
    ];
}
