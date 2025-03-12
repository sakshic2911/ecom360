<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\RolesTable&\Cake\ORM\Association\BelongsTo $Roles
 * @property \App\Model\Table\DeactivationTemplatesTable&\Cake\ORM\Association\BelongsTo $DeactivationTemplates
 * @property \App\Model\Table\UserTokensTable&\Cake\ORM\Association\HasOne $UserTokens
 * @property \App\Model\Table\ActivityLogsTable&\Cake\ORM\Association\HasMany $ActivityLogs
 * @property \App\Model\Table\BuybackReceiveInventoryTemporaryTable&\Cake\ORM\Association\HasMany $BuybackReceiveInventoryTemporary
 * @property \App\Model\Table\ClientWeeklyMeetingTable&\Cake\ORM\Association\HasMany $ClientWeeklyMeeting
 * @property \App\Model\Table\CommentNotesTable&\Cake\ORM\Association\HasMany $CommentNotes
 * @property \App\Model\Table\EmailLogsTable&\Cake\ORM\Association\HasMany $EmailLogs
 * @property \App\Model\Table\ExportJobsTable&\Cake\ORM\Association\HasMany $ExportJobs
 * @property \App\Model\Table\ManageNotificationTable&\Cake\ORM\Association\HasMany $ManageNotification
 * @property \App\Model\Table\NotificationTable&\Cake\ORM\Association\HasMany $Notification
 * @property \App\Model\Table\StaffNotificationSettingTable&\Cake\ORM\Association\HasMany $StaffNotificationSetting
 * @property \App\Model\Table\TicketRatingTable&\Cake\ORM\Association\HasMany $TicketRating
 * @property \App\Model\Table\TicketsActivitiesTable&\Cake\ORM\Association\HasMany $TicketsActivities
 * @property \App\Model\Table\UserLoginTable&\Cake\ORM\Association\HasMany $UserLogin
 * @property \App\Model\Table\UserOnboardingTable&\Cake\ORM\Association\HasMany $UserOnboarding
 * @property \App\Model\Table\UserPermissionsTable&\Cake\ORM\Association\HasMany $UserPermissions
 * @property \App\Model\Table\UserWalmartOnboardingTable&\Cake\ORM\Association\HasMany $UserWalmartOnboarding
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('DeactivationTemplates', [
            'foreignKey' => 'deactivation_template_id',
            'joinType' => 'INNER',
        ]);
        $this->hasOne('UserTokens', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('ActivityLogs', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('BuybackReceiveInventoryTemporary', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('ClientWeeklyMeeting', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('CommentNotes', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('EmailLogs', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('ExportJobs', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('ManageNotification', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Notification', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('StaffNotificationSetting', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('TicketRating', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('TicketsActivities', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('UserLogin', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('UserOnboarding', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('UserPermissions', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('UserWalmartOnboarding', [
            'foreignKey' => 'user_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 50)
            ->requirePresence('first_name', 'create')
            ->notEmptyString('first_name');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 50)
            ->allowEmptyString('last_name');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('password')
            ->requirePresence('password', 'create')
            ->allowEmptyString('password');

        $validator
            ->integer('assign_manager')
            ->notEmptyString('assign_manager');

        $validator
            ->scalar('contact_no')
            ->maxLength('contact_no', 20)
            ->allowEmptyString('contact_no');

        $validator
            ->integer('role_id')
            ->notEmptyString('role_id');

        $validator
            ->integer('parent_role')
            ->notEmptyString('parent_role');

        $validator
            ->scalar('image')
            ->maxLength('image', 100)
            ->allowEmptyFile('image');

        $validator
            ->scalar('address')
            ->allowEmptyString('address');

        $validator
            ->scalar('city')
            ->maxLength('city', 20)
            ->allowEmptyString('city');

        $validator
            ->scalar('state')
            ->maxLength('state', 50)
            ->allowEmptyString('state');

        $validator
            ->integer('zip_code')
            ->allowEmptyString('zip_code');

        $validator
            ->scalar('country')
            ->maxLength('country', 50)
            ->allowEmptyString('country');

        $validator
            ->scalar('citizenship')
            ->maxLength('citizenship', 50)
            ->allowEmptyString('citizenship');

        $validator
            ->scalar('birth_country')
            ->maxLength('birth_country', 50)
            ->allowEmptyString('birth_country');

        $validator
            ->date('dob')
            ->allowEmptyDate('dob');

        $validator
            ->scalar('organisation_name')
            ->maxLength('organisation_name', 100)
            ->allowEmptyString('organisation_name');

        $validator
            ->integer('affiliate_client')
            ->notEmptyString('affiliate_client');

        $validator
            ->integer('client_plan')
            ->notEmptyString('client_plan');

        $validator
            ->scalar('ssn')
            ->maxLength('ssn', 100)
            ->allowEmptyString('ssn');

        $validator
            ->integer('parent_affiliate')
            ->notEmptyString('parent_affiliate');

        $validator
            ->integer('referrer_affiliate')
            ->notEmptyString('referrer_affiliate');

        $validator
            ->integer('override_partner')
            ->notEmptyString('override_partner');

        $validator
            ->numeric('override_percentage')
            ->notEmptyString('override_percentage');

        $validator
            ->integer('status')
            ->notEmptyString('status');

        $validator
            ->integer('deactivation_template_id')
            ->notEmptyString('deactivation_template_id');

        $validator
            ->scalar('custom_message')
            ->allowEmptyString('custom_message');

        $validator
            ->scalar('onboarding_status')
            ->notEmptyString('onboarding_status');

        $validator
            ->integer('user_type')
            ->requirePresence('user_type', 'create')
            ->notEmptyString('user_type');

        $validator
            ->integer('issue_type')
            ->allowEmptyString('issue_type');

        $validator
            ->integer('store_permission')
            ->notEmptyString('store_permission');

        $validator
            ->scalar('manager_bio')
            ->maxLength('manager_bio', 255)
            ->allowEmptyString('manager_bio');

        $validator
            ->integer('store_capacity')
            ->notEmptyString('store_capacity');

        $validator
            ->scalar('embed_code')
            ->allowEmptyString('embed_code');

        $validator
            ->integer('delete_user')
            ->notEmptyString('delete_user');

        $validator
            ->dateTime('last_login')
            ->notEmptyDateTime('last_login');

        $validator
            ->scalar('firebaseid')
            ->maxLength('firebaseid', 255)
            ->allowEmptyString('firebaseid');

        $validator
            ->scalar('devicename')
            ->maxLength('devicename', 255)
            ->allowEmptyString('devicename');

        $validator
            ->integer('is_appointment')
            ->notEmptyString('is_appointment');

        $validator
            ->scalar('calender_id')
            ->maxLength('calender_id', 255)
            ->allowEmptyString('calender_id');

        $validator
            ->scalar('appointment_id')
            ->maxLength('appointment_id', 255)
            ->allowEmptyString('appointment_id');

        $validator
            ->scalar('event_address')
            ->maxLength('event_address', 255)
            ->allowEmptyString('event_address');

        $validator
            ->notEmptyString('password_change_required');

        $validator
            ->notEmptyString('amazon');

        $validator
            ->notEmptyString('walmart');

        $validator
            ->scalar('secret')
            ->maxLength('secret', 255)
            ->allowEmptyString('secret');

        $validator
            ->dateTime('token_expiration')
            ->allowEmptyDateTime('token_expiration');

        $validator
            ->scalar('device_id')
            ->maxLength('device_id', 255)
            ->allowEmptyString('device_id');

        $validator
            ->integer('remember_me')
            ->notEmptyString('remember_me');

        $validator
            ->scalar('session_login')
            ->maxLength('session_login', 255)
            ->allowEmptyString('session_login');

        $validator
            ->integer('termination_access_days')
            ->allowEmptyString('termination_access_days');

        $validator
            ->date('termination_date')
            ->allowEmptyDate('termination_date');

        $validator
            ->date('internal_hold_date')
            ->allowEmptyDate('internal_hold_date');

        $validator
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('updated_at')
            ->notEmptyDateTime('updated_at');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);
        $rules->add($rules->existsIn('role_id', 'Roles'), ['errorField' => 'role_id']);
        $rules->add($rules->existsIn('deactivation_template_id', 'DeactivationTemplates'), ['errorField' => 'deactivation_template_id']);

        return $rules;
    }
}
