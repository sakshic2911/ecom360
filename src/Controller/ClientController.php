<?php
declare(strict_types=1);

namespace App\Controller;
use Authentication\PasswordHasher\DefaultPasswordHasher;

/**
 * Client Controller
 *
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClientController extends AppController
{
    private $session;
    private $userTbl;
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('admin_dashboard');
        $this->session = $this->request->getSession();
        $this->userTbl = $this->getTableLocator()->get('Users');
        $this->DeactivationTemplates = $this->getTableLocator()->get('DeactivationTemplates');
        $this->planTbl = $this->getTableLocator()->get('Plans');
        $this->override_partner = $this->getTableLocator()->get('OverridePartners');
        $this->AffiliateParentRelations = $this->getTableLocator()->get('AffiliateParentRelations');
        $this->menu = $this->getTableLocator()->get('Menus');

    }

    public function index()
    {
        $this->session->write('activeUrl', 'master');
        $user = $this->session->read('user');
        if(!$user)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        if($user->user_type==2)
        {
            return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
        }
        else if(in_array($user->user_type,[0]))
        {
            $menu = $this->session->read('menu');
            $permission = 3; $lgPermission = 3;
            foreach($menu as $m)
            {
                if($m->folder == 'Clients & Stores')
                {
                    foreach($m->main['Clients & Stores'] as $ml) {
    
                        if($ml->url=='client' && $user->user_type==1)
                        { 
                            $permission = $ml->permission;
                            break;
                        }
                    }
                }
                if($m->folder == 'Extra')
                {
                    foreach($m->main['Extra'] as $ml) {
    
                        if($ml->menu_name=='Login as Client' && $ml->permission != 3)
                        { 
                            $lgPermission = 1;
                            break;
                        }
                    }
                }                
            }
           
            $condition = ['Users.user_type' => 2, 'Users.delete_user' => 0, 'Users.email !=' => 'houselead@actiknow.com'];
        
            $fromDt = $toDt = '';
            $holdCondition = [];
            if ($this->request->is('post')) {
                $fromDt = $this->request->getData('from_date');
                $toDt = $this->request->getData('to_date');

                $fromDate = !empty($fromDt) ? date('Y-m-d H:i:s', strtotime($fromDt)) : null;
                $toDate = !empty($toDt) ? date('Y-m-d H:i:s', strtotime($toDt)) : null;
                
                if (!empty($fromDate) && !empty($toDate)) {
                    $condition['DATE_SUB(Users.last_login, INTERVAL 4 HOUR) >='] = $fromDate;
                    $condition['DATE_SUB(Users.last_login, INTERVAL 4 HOUR) <='] = $toDate;
                } elseif (!empty($fromDate)) {
                    $condition['DATE_SUB(Users.last_login, INTERVAL 4 HOUR) >='] = $fromDate;
                } elseif (!empty($toDate)) {
                    $condition['DATE_SUB(Users.last_login, INTERVAL 4 HOUR) <='] = $toDate;
                }

            }
            $masterClients =  $this->userTbl->find()
                ->select(['Users.id', 'Users.first_name', 'Users.last_name', 'Users.email', 'Users.contact_no', 'Users.organisation_name', 'Users.status', 'Users.affiliate_client', 'Users.override_partner', 'Users.parent_affiliate', 'Users.onboarding_status', 'Users.is_appointment', 'last_login'=>'DATE_FORMAT(DATE_SUB(Users.last_login, INTERVAL 4 HOUR), "%m/%d/%Y %H:%i:%s")','manager'=>'CONCAT(Client.first_name," ",Client.last_name)',
                'internal_hold_date'=>'DATE_FORMAT(Users.internal_hold_date, "%m/%d/%Y")'])
                ->join([
                    'Client' => [
                        'table' => 'users',
                        'type' => 'LEFT',
                        'conditions' => 'Client.id = Users.assign_manager'
                    ],                    
                ])
                ->DISTINCT(['Users.id'])
                ->where($condition)
                ->where($holdCondition)
                 ->limit(5)
                ->toArray();
    
            $affiliateClients =  $this->userTbl->find()
                ->select(['id', 'first_name', 'last_name', 'affiliate_client'])
                ->where(['user_type' => 2, 'delete_user' => 0, 'affiliate_client' => 1])
                ->toArray();
            
            $overrideClients =  $this->userTbl->find()
                ->select(['id', 'first_name', 'last_name'])
                ->where(['user_type' => 3, 'delete_user' => 0])
                ->toArray();    
    
            $plan = $this->planTbl->find()
                ->select(['id', 'plan_name'])
                ->toArray();
                $client_status = 0;
    
            $deactivation_templates = $this->DeactivationTemplates->find('all')->toArray();

            $this->set(compact('masterClients','user','fromDt','toDt','deactivation_templates','permission','affiliateClients','plan','overrideClients'));
        }
        else
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
    }

    public function changeLoginStatus()
    {
        // $this->userTbl

        if ($this->request->is('post')) {

            $template_id = $this->request->getData('deactivation_reason');
            if($template_id == 5)
            {
                $set = [
                    'status' => 0,
                    'deactivation_template_id' => 5,
                    'custom_message' => $this->request->getData('custom_reason')
                ];
            }
            else{
                $set = [
                    'status' => 0,
                    'deactivation_template_id' => $template_id
                ];
            }
            $this->userTbl->query()
            ->update()
            ->set($set)
            ->where(['id' => $this->request->getData('client_id')])->execute();

            
            $this->Flash->success('Client login status is updated successfully.', ['key' => 'clientData']);
            return $this->redirect(['controller' => 'Client', 'action' => 'index']);
        }
    }
   
    public function showDeactivation()
    {
        if ($this->request->is('GET')) {
            $id = $this->request->getQuery('id');
            $condition = ['user_type' => 2, 'delete_user' => 0, 'email !=' => 'houselead@actiknow.com','Users.id'=>$id];

            $masterClientsData =  $this->userTbl->find()
            ->select(['deactivation_template_id','custom_message','templates'=>'DeactivationTemplates.description'])
            ->join([
                'DeactivationTemplates' => [
                    'table' => 'deactivation_templates',
                    'type' => 'LEFT',
                    'conditions' => 'Users.deactivation_template_id = DeactivationTemplates.id'
                ]
            ])
            ->where($condition)
            ->toArray();
          
            echo json_encode($masterClientsData);
            die;
        }
    }

    public function editMasterClient($id = null)
    {
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        if($loginUser->user_type==2)
        {
            return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
        }
        $masterClients =  $this->userTbl->find()
            ->select(['id', 'first_name', 'last_name', 'email', 'contact_no', 'organisation_name', 'status', 'affiliate_client', 'override_partner', 'override_percentage', 'referrer_affiliate', 'parent_affiliate','has_store'])
            ->where(['user_type' => 2, 'delete_user' => 0])
            ->toArray();

        $plan = $this->planTbl->find()
            ->select(['id', 'plan_name'])
            ->toArray();

        if ($this->request->is('GET')) {

            $this->viewBuilder()->setLayout('ajax');

            $id = $this->request->getQuery('id');
            $editMasterClientsData = $this->userTbl->find()
                ->select(['id', 'first_name', 'last_name', 'email', 'contact_no', 'address', 'organisation_name', 'affiliate_client', 'client_plan', 'ssn', 'referrer_affiliate', 'override_partner', 'override_percentage','has_store','onboarding_status'])
                ->where(['id' => $id])
                ->toArray();

            $editData = $this->override_partner->find()->select(['override_partner'])->where(['client_id' => $id])->toArray();
            $editOverridePartnerData = [];
            foreach($editData as $d)
            {
                $editOverridePartnerData[] = $d->override_partner;
            }
            $overrideClients =  $this->userTbl->find()
            ->select(['id', 'first_name', 'last_name'])
            ->where(['user_type' => 3, 'delete_user' => 0])
            ->toArray();

            $this->set(compact('editMasterClientsData', 'masterClients', 'plan', 'editOverridePartnerData','overrideClients'));
        } else if ($this->request->is(['post', 'put', 'patch'])) {
           
            if ($this->request->getData('referrer_affiliate') != 0) {
                $refAffiliateId = (int) $this->request->getData('referrer_affiliate');

                //update store table if record exist
                $store = $this->storeTbl->query();
                $store->update()
                        ->set(['referring_affiliate' => $refAffiliateId])
                        ->where(['clients' => $this->request->getData('editId')]);
                $store->execute();


                if ($this->userTbl->get($this->request->getData('referrer_affiliate'))->referrer_affiliate != 0)
                {
                    $parentAffiliate = $this->userTbl->get($this->request->getData('referrer_affiliate'))->referrer_affiliate;

                    //update store table if record exist
                    $store = $this->storeTbl->query();
                    $store->update()
                            ->set(['parent_affiliate' => $parentAffiliate])
                            ->where(['clients' => $this->request->getData('editId')]);
                    $store->execute();
                }
                else
                    $parentAffiliate = 0;
            } else {
                $refAffiliateId = 0;
                $parentAffiliate = 0;
            }
            if ($this->request->getData('affiliate') == 1) {
                $affiliate = (int) $this->request->getData('affiliate');
                if ($this->request->getData('plan') != 0)
                    $plan = $this->request->getData('plan');
                else
                    $plan = 0;
                if ($this->request->getData('ssn') != '')
                    $ssn = $this->request->getData('ssn');
                else
                    $ssn = NULL;
            } else {
                $affiliate = 0;
                $plan = 0;
                $ssn = NULL;
                
            }
            if ($this->request->getData('override') == 1)
            { 
                $override = (int) $this->request->getData('override');
                $override_percentage = $this->request->getData('override_percentage');
            }
            else
            {
                $override = 0; $override_percentage = 0;
            }
                
            $old_values = [];
            $tasks = [];
            $editId = $this->request->getData('editId');
            $override_partner = $this->request->getData('override_partner');
            $first_name = $this->request->getData('first_name');
            $last_name = $this->request->getData('last_name');
            $email = $this->request->getData('email');
            $contact_no = $this->request->getData('contact_no');
            $address = $this->request->getData('address');
            $organisation_name = $this->request->getData('organisation');
            $affiliate_client = $affiliate;
            $client_plan = $plan;
            $referrer_affiliate = $refAffiliateId;
            $parent_affiliate = $parentAffiliate;
            $override_partner = $override;
            $has_store = $this->request->getData('store_own');

            $userData = $this->userTbl->find()->where(['id' => $editId])->first();
            $overridePartnerValues = [];
            if ($override_partner) {
                $overridePartnerData = $this->override_partner->find()->where(['client_id' => $editId])->toArray();
                $overridePartnerValues = array_column($overridePartnerData, 'override_partner');
            }

            $fieldValuePairs = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'contact_no' => $contact_no,
                'address' => $address,
                'organisation_name' => $organisation_name,
                'affiliate_client' => $affiliate_client,
                'client_plan' => $client_plan,
                'ssn' => $ssn,
                'referrer_affiliate' => $referrer_affiliate,
                'parent_affiliate' => $parent_affiliate,
                'override_partner' => $override_partner,
                'override_percentage' => $override_percentage,
                'has_store' => $has_store
            ];

            $old_values = [];
            $tasks = [];

            foreach ($fieldValuePairs as $field => $value) {
                $fieldName = ucwords(str_replace('_', ' ', $field));
                if ($userData->{$field} != $value) {
                    $old_values[] = "$fieldName was " . $userData->{$field};
                    $tasks[] = $fieldName;
                }
            }
            
           if ( $overridePartnerValues != $this->request->getData('override_partner')) {
            $old_values[] = "Override Partner was " . implode(', ', $overridePartnerValues);
            $tasks[] = "Override Partner";
        }

            $old_value = implode(', ', $old_values);
            $task = implode(', ', $tasks);
            if(!empty($old_value)){
                // Save data into activity log
                $this->activityLog($loginUser, 'Edit Client', $task, "", $editId, "", "", "", "", $old_value);
            }

            $updateMasterClient = $this->userTbl->query()
                ->update()
                ->set([
                    'first_name' => $this->request->getData('first_name'),
                    'last_name' => $this->request->getData('last_name'),
                    'email' => $this->request->getData('email'),
                    'contact_no' => $this->request->getData('contact_no'),
                    'address' => $this->request->getData('address'),
                    'organisation_name' => $this->request->getData('organisation'),
                    'affiliate_client' => $affiliate,
                    'client_plan' => $plan,
                    'ssn' => $ssn,
                    'referrer_affiliate' => $refAffiliateId,
                    'parent_affiliate' => $parentAffiliate,
                    'override_partner' => $override,
                    'override_percentage' => $override_percentage,
                    'has_store' =>$this->request->getData('store_own')
                ])
                ->where(['id' => $this->request->getData('editId')]);

            if ($updateMasterClient->execute()) {
                $this->AffiliateParentRelations->query()
                    ->update()
                    ->set([
                        'affiliate_id' => $refAffiliateId,
                        'parent_affiliate_id' => $parentAffiliate
                    ])
                    ->where(['client_id' => $this->request->getData('editId')])
                    ->execute();

                    if($override ==1){
                        if ($this->request->getData('override_partner')) {
                            $this->override_partner->query()->delete()->where(['client_id' => $this->request->getData('editId')])->execute();
                            for ($i = 0; $i < count($this->request->getData('override_partner')); $i++) {
                                $overridePartner = $this->override_partner->newEmptyEntity();
                                $overridePartner->client_id = $this->request->getData('editId');
                                $overridePartner->override_partner = $this->request->getData('override_partner')[$i];
                                $this->override_partner->save($overridePartner);
                            }
                        }               
                    }else{
                        $this->override_partner->query()->delete()->where(['client_id' => $this->request->getData('editId')])->execute();
                    }

                $this->Flash->success('Master Client is updated successfully.', ['key' => 'masterUpdate']);
                return $this->redirect(['controller' => 'Client', 'action' => 'index']);
            }
        }
    }
    public function addMasterClients()
    {
        $user = $this->session->read('user');
        if(!$user)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        if($user->user_type==2)
        {
            return $this->redirect(['controller' => 'Client', 'action' => 'index']);
        }
        if ($this->request->is('post')) {
            $parentRelation = $this->AffiliateParentRelations->newEmptyEntity();
            $usersTbl = $this->userTbl->newEmptyEntity();

            $password = 'password';
            $email  = $this->request->getData('email');
            $first_name = $this->request->getData('first_name');
            $last_name = $this->request->getData('last_name');

            $usersTbl->first_name = $first_name;
            $usersTbl->last_name = $last_name;
            $usersTbl->email = $email;
            $usersTbl->password = (new DefaultPasswordHasher())->hash($password);
            $usersTbl->contact_no = $this->request->getData('contact_no');
            $usersTbl->address = $this->request->getData('address');
            $usersTbl->organisation_name = $this->request->getData('organisation');
            $usersTbl->user_type = 2;

            //Enable 2FA
            // $tfa = new TwoFactorAuth();
            // $usersTbl->secret = $tfa->createSecret();

            if ($this->request->getData('referrer_affiliate') != 0) {
                $usersTbl->referrer_affiliate = (int) $this->request->getData('referrer_affiliate');
                if ($this->userTbl->get($this->request->getData('referrer_affiliate'))->referrer_affiliate != 0)
                    $usersTbl->parent_affiliate = $this->userTbl->get($this->request->getData('referrer_affiliate'))->referrer_affiliate;
            }
            if ($this->request->getData('affiliate') == 1) {
                $usersTbl->affiliate_client = (int) $this->request->getData('affiliate');
                if ($this->request->getData('plan') != 0) {
                    $usersTbl->client_plan = $this->request->getData('plan');
                }
                if ($this->request->getData('ssn') != '') {
                    $usersTbl->ssn = $this->request->getData('ssn');
                }
                
            }
            if ($this->request->getData('override') == 1)
                $usersTbl->override_partner = (int) $this->request->getData('override');
            if ($this->request->getData('override_percentage'))
                $usersTbl->override_percentage = $this->request->getData('override_percentage');
            

            if ($this->userTbl->save($usersTbl)) {
                $parentRelation->client_id = $usersTbl->id;
                if ($usersTbl->referrer_affiliate != 0) {
                    $parentRelation->affiliate_id = $usersTbl->referrer_affiliate;
                }
                if ($usersTbl->parent_affiliate != 0) {
                    $parentRelation->parent_affiliate_id = $usersTbl->parent_affiliate;
                }

                if ($usersTbl->override_partner == 1) {
                    if ($this->request->getData('override_partner')) {
                        for ($i = 0; $i < count($this->request->getData('override_partner')); $i++) {
                            $overridePartner = $this->override_partner->newEmptyEntity();
                            $overridePartner->client_id = $usersTbl->id;
                            $overridePartner->override_partner = $this->request->getData('override_partner')[$i];
                            $this->override_partner->save($overridePartner);
                        }
                    }
                }

                $this->AffiliateParentRelations->save($parentRelation);

                $emailData = [];
                $emailData['toEmail'] = $email;
                $emailData['clientName'] = $first_name . ' ' . $last_name;
                $emailData['userId'] = $usersTbl->id;
                $emailData['userName'] = $email;
                $emailData['password'] = $password;


                // $this->sendEmailViaEmailType(1, $emailData);
                // $this->addClientEmail($first_name,$password, $email);

                if ($this->request->getData('store_client') == 'store') {
                    $this->Flash->success('Client Data is saved successfully. Now you can add the store.', ['key' => 'clientStore']);
                    return $this->redirect(['controller' => 'Store', 'action' => 'index']);
                }
                $this->Flash->success('Client Data is saved successfully.', ['key' => 'clientData']);
                return $this->redirect(['controller' => 'Client', 'action' => 'index']);
            } else {
                $this->Flash->error($usersTbl->getErrors()['email']['_isUnique'], ['key' => 'clientEmailError']);
                return $this->redirect(['controller' => 'Client', 'action' => 'index']);
            }
        }
    }

    public function loginClient($id)
    {
        $this->Authorization->skipAuthorization();
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        if($loginUser->user_type == 2)
        {
            return $this->redirect(['controller' => 'Tickets', 'action' => 'index']);
        }

        $clientData = $this->userTbl->get($id);
        // $this->session->write('loginUser', $clientData->user_type);
        $this->session->write('user',$clientData);
        $mlist = $this->menu->find()->select(['folder','sequence','icon'])->where(['status'=>'Active','user IN'=>[2,4]])->order(['sequence'=>'ASC'])->group('sequence')->toArray();
        $list = [];
        foreach($mlist as $ml)
        {

            $menu = $this->menu->find()->where(['status'=>'Active','user IN'=>[2,4],'sequence'=>$ml->sequence])->order(['sub_sequence'=>'ASC'])->toArray();
            
            $m1 = [];
            $m1['icon'] = $ml->icon;
            $m1['folder'] = $ml->folder;
            foreach($menu as $m)
            {
                $m2 = [];
                $m2['menu_name'] = $m->menu_name;
                $m2['url'] = $m->url;
                $m1['main'][$ml->folder][] = (object)$m2;

            }
            
            $list[] = (object)$m1;
            
        }
        $this->session->write('menu',$list);
        $this->session->write('userType', 'admin');
        $this->session->write('menu_category',1);
        $this->session->write('page_category',1);

        return $this->redirect(['controller' => 'Tickets', 'action' => 'index']);
    }

    public function backToAdmin()
    {
        $master = $_SESSION['master'];
        $id = $master->id;
        $clientData = $this->userTbl->get($id);
        $this->session->write('user',$clientData);

        if($master->user_type==0)
        {
            $mlist = $this->menu->find()->select(['folder','sequence','icon'])->where(['status'=>'Active','user IN'=>[1,2]])->order(['sequence'=>'ASC'])->group('sequence')->toArray();

            $list = [];
            foreach($mlist as $ml)
            {

                $menu = $this->menu->find()->where(['status'=>'Active','user IN'=>[1,2],'sequence'=>$ml->sequence])->order(['sub_sequence'=>'ASC'])->toArray();
                
                $m1 = [];
                $m1['icon'] = $ml->icon;
                $m1['folder'] = $ml->folder;
                foreach($menu as $m)
                {
                    $m2 = [];
                    $m2['menu_name'] = $m->menu_name;
                    $m2['url'] = $m->url;
                    $m1['main'][$ml->folder][] = (object)$m2;

                }
                
                $list[] = (object)$m1;
                
            }
        }
        else{

            $this->session->write('userType', 'staff');

            $mlist = $this->menu->find()->select(['folder','sequence','icon'])->join([
                'UserPermissions' => [
                'table' => 'user_permissions',
                'type' => 'INNER',
                'conditions' => 'UserPermissions.menu_id = Menu.id',
            ]
            ])->where(['UserPermissions.user_id'=>$master->id,'UserPermissions.permission !='=>3,'Menu.status'=>'Active'])->order(['sequence'=>'ASC'])->group('sequence')->toArray();
            $list = [];
            foreach($mlist as $ml)
            {
                $menu = $this->menu->find()->select(['menu_name','url','icon','user','permission'=>'UserPermissions.permission'])->join([
                    'UserPermissions' => [
                    'table' => 'user_permissions',
                    'type' => 'INNER',
                    'conditions' => 'UserPermissions.menu_id = Menu.id',
                ]
                ])->where(['UserPermissions.user_id'=>$master->id,'UserPermissions.permission !='=>3,'Menu.status'=>'Active','sequence'=>$ml->sequence])->order(['sub_sequence'=>'ASC'])->toArray();

                $m1 = [];
                $m1['icon'] = $ml->icon;
                $m1['folder'] = $ml->folder;
                $check = 0;       
                if($ml->folder == 'Clients & Stores')
                {
                    foreach($menu as $m)
                    {
                        if($m->menu_name == 'Onboarding Clients')
                        {
                            $check = 1; break;
                        }    
                        
                    }
                }
                foreach($menu as $m)
                {
                    $m2 = [];
                    $m2['menu_name'] = ($m->menu_name == 'Walmart Onboarding' && $check == 0) ? 'Onboarding Clients' : $m->menu_name;
                    $m2['url'] = $m->url;
                    $m2['permission'] = $m->permission;
                    $m1['main'][$ml->folder][] = (object)$m2;

                }
               
                $list[] = (object)$m1;
                
            }

        }
        $this->session->write('menu',$list);
        $this->session->write('brand_data', 'no');
        unset($_SESSION['adminLoginClientId']);
        return $this->redirect('/client');
    }
}
