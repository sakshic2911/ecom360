<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
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
                'first_name' => 'Lorem ipsum dolor sit amet',
                'last_name' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'assign_manager' => 1,
                'contact_no' => 'Lorem ipsum dolor ',
                'role_id' => 1,
                'parent_role' => 1,
                'image' => 'Lorem ipsum dolor sit amet',
                'address' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'city' => 'Lorem ipsum dolor ',
                'state' => 'Lorem ipsum dolor sit amet',
                'zip_code' => 1,
                'country' => 'Lorem ipsum dolor sit amet',
                'citizenship' => 'Lorem ipsum dolor sit amet',
                'birth_country' => 'Lorem ipsum dolor sit amet',
                'dob' => '2025-03-06',
                'organisation_name' => 'Lorem ipsum dolor sit amet',
                'affiliate_client' => 1,
                'client_plan' => 1,
                'ssn' => 'Lorem ipsum dolor sit amet',
                'parent_affiliate' => 1,
                'referrer_affiliate' => 1,
                'override_partner' => 1,
                'override_percentage' => 1,
                'status' => 1,
                'deactivation_template_id' => 1,
                'custom_message' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'onboarding_status' => 'Lorem ipsum dolor sit amet',
                'has_store' => 1,
                'user_type' => 1,
                'issue_type' => 1,
                'store_permission' => 1,
                'manager_bio' => 'Lorem ipsum dolor sit amet',
                'store_capacity' => 1,
                'embed_code' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'delete_user' => 1,
                'last_login' => 1741244975,
                'firebaseid' => 'Lorem ipsum dolor sit amet',
                'devicename' => 'Lorem ipsum dolor sit amet',
                'is_appointment' => 1,
                'calender_id' => 'Lorem ipsum dolor sit amet',
                'appointment_id' => 'Lorem ipsum dolor sit amet',
                'event_address' => 'Lorem ipsum dolor sit amet',
                'password_change_required' => 1,
                'amazon' => 1,
                'walmart' => 1,
                'secret' => 'Lorem ipsum dolor sit amet',
                'token_expiration' => '2025-03-06 07:09:35',
                'device_id' => 'Lorem ipsum dolor sit amet',
                'remember_me' => 1,
                'session_login' => 'Lorem ipsum dolor sit amet',
                'termination_access_days' => 1,
                'termination_date' => '2025-03-06',
                'internal_hold_date' => '2025-03-06',
                'created_at' => 1741244975,
                'updated_at' => 1741244975,
            ],
        ];
        parent::init();
    }
}
