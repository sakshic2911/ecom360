<?php

declare(strict_types=1);

namespace App\Controller;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Datasource\ConnectionManager;
use RobThree\Auth\TwoFactorAuth;
use TwoFactorAuth\Authenticator\Result;
use Cake\Routing\Router;
use Cake\I18n\FrozenTime;

use function Symfony\Component\Clock\now;

class UsersController extends AppController
{
    // $this->Authentication->getIdentity()->getOriginalData();
    private $session;
    private $roleTbl;
    public function initialize(): void
    {
        parent::initialize();
        // $this->getTableLocator('Users');
        $this->session = $this->request->getSession();
        $this->roleTbl = $this->getTableLocator()->get('Roles');
        $this->menu = $this->getTableLocator()->get('Menus');
        $this->loginTbl = $this->getTableLocator()->get('UserLogins');
        $this->permision = $this->getTableLocator()->get('RolePermissionMenus');
        $this->UserPermissions = $this->getTableLocator()->get('UserPermissions');
        $this->issueTbl = $this->getTableLocator()->get('UsersIssues');
        $this->userTbl = $this->getTableLocator()->get('Users');
        // $this->ActivityLogs = $this->getTableLocator()->get('ActivityLogs');
        // $this->DeactivationTemplates = $this->getTableLocator()->get('DeactivationTemplates');

    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        
        $this->Authentication->addUnauthenticatedActions(['login','updateSession','updatePassword']);

    }

    public function login()
    {
        $this->viewBuilder()->setLayout('login');
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
      
        if ($this->session->check('loginE')) {
            $login =  $this->session->read('loginE');
            $this->session->write('loginE',$login);
        }
        else{
            $this->session->write('loginE',0);
            $this->session->write('loginTempError','');
        }
        
        if ($result && $result->isValid()) {
            $user = $this->Authentication->getIdentity()->getOriginalData();
            return $this->redirect(['controller' => 'Users', 'action' => 'afterLogin']);
        }
        // display error if user submitted and authentication failed
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('* Invalid credentials'));
        }
    }

    public function afterLogin()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();

        if ($result && $result->isValid()) {

            $user = $this->Authentication->getIdentity()->getOriginalData();
           
            if($user->delete_user=='1' || $user->user_type=='3')
            {
                $email = $user->email;
                $user = $this->userTbl->find()->where(['email'=>$user->email,'delete_user'=>0, 'user_type IN' => [1,2]])->first();
                if(empty($user))
                {
                    $this->Flash->error('* Invalid credentials', ['key' => 'loginError']);
                    return $this->redirect(['action' => 'logout']);
                }
            }   

            if ($user->password_change_required) {
                $this->session->write('user',$user);
                return $this->redirect(['controller' => 'Users', 'action' => 'updatePassword']);
            }

            if($user->status=='0')
            { 
                $deactivationMsg = $this->DeactivationTemplates->get($user->deactivation_template_id);
                if($user->deactivation_template_id == 5)
                $msg = $user->custom_message;
                else
                $msg = $deactivationMsg->description;
                $this->Flash->error($msg, ['key' => 'loginTempError']);
                $this->session->write('loginE',1);
                $this->session->write('loginTempError',$msg);
                return $this->redirect(['action' => 'logout']);
            }
            else{
                if (!empty($user->termination_access_days) && !empty($user->termination_date)) {
                    $terminationDateString = $user->termination_date->format('Y-m-d');
                    $terminationDate = new \DateTime($terminationDateString);
                    
                    $currentDate = new \DateTime();
                    $daysSinceTermination = $terminationDate->diff($currentDate)->days; 
                    // Check if they still have access
                    if ($daysSinceTermination > $user->termination_access_days) {
                        $this->Users->query()->update()->set(['status' => 0, 'deactivation_template_id' => 4])->where(['id' => $user->id])->execute();
                        $deactivationMsg = $this->DeactivationTemplates->get(4);
                        if($user->deactivation_template_id == 5)
                        $msg = $user->custom_message;
                        else
                        $msg = $deactivationMsg->description;

                        $this->session->write('loginE',1);
                        $this->session->write('loginTempError',$msg);
                        return $this->redirect(['action' => 'logout']);
                    }                    
                }
                $this->session->write('loginE',0);
                $this->session->write('loginTempError','');
            }

            $this->session->write('user',$user);

            //generate random string
            $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            $session_val = substr(str_shuffle($str_result), 0, 15);

            $this->Users->query()->update()->set(['session_login' => $session_val])->where(['id' => $user->id])->execute();
            $this->session->write('session_val',$session_val);

            if($user->user_type==0)
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
            else if($user->user_type==2){
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
            } else{
                $mlist = $this->menu->find()->select(['folder','sequence','icon'])->join([
                    'UserPermissions' => [
                    'table' => 'user_permissions',
                    'type' => 'INNER',
                    'conditions' => 'UserPermissions.menu_id = Menus.id',
                ]
                ])->where(['UserPermissions.user_id'=>$user->id,'UserPermissions.permission !='=>3,'Menus.status'=>'Active'])->order(['sequence'=>'ASC'])->group('sequence')->toArray();
                $list = [];
                foreach($mlist as $ml)
                {
                    $menu = $this->menu->find()->select(['menu_name','url','icon','user','permission'=>'UserPermissions.permission'])->join([
                        'UserPermissions' => [
                        'table' => 'user_permissions',
                        'type' => 'INNER',
                        'conditions' => 'UserPermissions.menu_id = Menus.id',
                    ]
                    ])->where(['UserPermissions.user_id'=>$user->id,'UserPermissions.permission !='=>3,'Menus.status'=>'Active','sequence'=>$ml->sequence])->order(['sub_sequence'=>'ASC'])->toArray();

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

                //for only internal staff menu
                $menus = $this->menu->find()->where(['user' => 5])->order(['sub_sequence'=>'ASC'])->toArray();

                $m1 = [];
                foreach($menus as $m)
                {
                    $m2 = [];
                    $m2['menu_name'] = $m->menu_name;
                    $m2['url'] = $m->url;
                    $m2['permission'] = 2;
                    $m1['main'][$m->folder][] = (object)$m2;
                    $m1['icon'] = $m->icon;
                    $m1['folder'] = $m->folder;

                }
            
                $list[] = (object)$m1;

            }
          

            $this->session->write('menu',$list);
            
            if ($user->user_type == 2) {
                $this->session->write('userType', 'client');
                $this->session->write('menu_category',1);
                $this->session->write('page_category',1);
                $this->session->write('store_change','');
                $this->session->write('store_id',0);
               
                //update user table with last login
                $userData = $this->userTbl->query();
                $userData->update()
                        ->set(['last_login' => date('Y-m-d H:i:s')])
                        ->where(['id' => $user->id]);
                $userData->execute();
                
            } else if ($user->user_type == 1) { 
                $this->session->write('userType', 'staff');
                $this->session->write('master',$user);
                $this->session->write('brand_data', 'no');
            }
            else{ 
                $this->session->write('userType', 'admin');
                $this->session->write('master',$user);
                $this->session->write('brand_data', 'no');
            }


            //insert login data into user login table
            $login = $this->loginTbl->newEmptyEntity();
            $login->user_id = $user->id;
            $login->log_in = date('Y-m-d H:i:s');
            $loginResult = $this->loginTbl->save($login);
            $this->session->write('loginId', $loginResult->id);

            return $this->redirect('/'.$list[0]->main[$list[0]->folder][0]->url);

        }
        // display error if user submitted and authentication failed
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('* Invalid credentials'));
            return $this->redirect('/');
        }
    }

    public function updateSession()
    {
        $this->session->write('loginE',0);
        $this->session->destroy();
        echo 1;
        die;

        // return $this->redirect(['action' => 'logout']);
    }

    public function logout()
    {
        $loginId = $this->session->read('loginId');
        $loginE = $this->session->read('loginE');
        $loginTempError = $this->session->read('loginTempError');
        if($loginId)
        {
            //update user table with last login
            $userData = $this->loginTbl->query();
            $userData->update()
                    ->set(['log_out' => date('Y-m-d H:i:s')])
                    ->where(['id' => $loginId]);
            $userData->execute();

        }
        
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        
        if ($result && $result->isValid()) {
            $this->Authentication->logout();
            $this->session->destroy();

            if ($loginE) {
                $this->session->write('loginE', $loginE);
            }            
            if ($loginTempError) {
                $this->session->write('loginTempError', $loginTempError);
            }

            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
        $this->session->destroy();
    }

    public function accountSetting()
    {
        $this->viewBuilder()->setLayout('admin_dashboard');
        $this->session->write('activeUrl', 'account');
        $user = $this->session->read('user');
        if(!$user)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        $loginUser = $this->Authentication->getIdentity('identity');

        $userData = $this->Users->find()->select(['first_name', 'last_name', 'email', 'contact_no', 'image', 'state', 'address', 'zip_code', 'country','manager_bio','role_id'])->where(['id' => $user->id])->toArray();

        $img = $userData[0]->image;
        // die;
        if ($this->request->is('post')) {
            // echo "<pre>";
            // print_r($this->request->getData());
            // die;
            if (isset($_SESSION['adminLoginClientId']))
                $userId = $_SESSION['adminLoginClientId'];
            else
                $userId = $loginUser->id;

            $accountUpdate = $this->Users->query();
            $image = $this->request->getData('image');
            $imgName = chr(rand(97, 122)) . rand(10000, 99999) . $image->getClientFilename();
            $targetPath = WWW_ROOT . 'img' . DS . 'ECOM360' . DS . 'avatars' . DS  . $imgName;

            $manager_bio = '';
            if($user->role_id == 15)
                $manager_bio = $this->request->getData('manager_bio');


            if ($image->getClientFilename()) {
                if ($image->getSize() > 800000) {
                    $this->Flash->error("Image size not more than 800K.", ['key' => 'imgErr']);
                    return $this->redirect('/account-setting');
                }
                $accountUpdate->update()
                    ->set([
                        'first_name' => $this->request->getData('first_name'),
                        'last_name' => $this->request->getData('last_name'),
                        'email' => $this->request->getData('email'),
                        'image' => $imgName,
                        'contact_no' => $this->request->getData('contact_no'),
                        'address' => $this->request->getData('address'),
                        'manager_bio' => $manager_bio                        
                    ])
                    ->where(['id' => $user->id]);
                $image->moveTo($targetPath);
            } else {
                $accountUpdate->update()
                    ->set([
                        'first_name' => $this->request->getData('first_name'),
                        'last_name' => $this->request->getData('last_name'),
                        'email' => $this->request->getData('email'),
                        'contact_no' => $this->request->getData('contact_no'),
                        'address' => $this->request->getData('address'),
                        'manager_bio' => $manager_bio
                    ])
                    ->where(['id' => $user->id]);
            }

            if ($accountUpdate->execute()) {
                $this->Flash->success("Account has been updated.", ['key' => 'accountUpdate']);
                return $this->redirect('/account-setting');
            }
        }

        $this->set(compact('userData', 'img','user'));
    }

    public function changePassword()
    {
        $this->viewBuilder()->setLayout('admin_dashboard');
        $userData = $this->Authentication->getIdentity('identity');
        $user = $this->session->read('user');
        if ($this->request->is(['post', 'put', 'patch'])) {
            $userPassword = $this->Users->find()->select(['password'])->where(['id' => $user->id])->toArray();
            if ((new DefaultPasswordHasher())->check($this->request->getData('old_password'), $userPassword[0]->password)) {
                $newPass = $this->request->getData('new_password');
                $confirmPass = $this->request->getData('confirm_password');
                if ($newPass == $confirmPass) {
                    $password = (new DefaultPasswordHasher())->hash($newPass);
                    $changePass = $this->Users->query()
                        ->update()
                        ->set(['password' => $password])
                        ->where(['id' => $user->id]);
                    if ($changePass->execute()) {
                        $this->Flash->success("Password had been changed. Now you can login with new password.", ['key' => 'passChange']);
                        return $this->redirect('/change-password');
                    } else {
                        $this->Flash->error("Password not changed.", ['key' => 'passChangeErr']);
                        return $this->redirect('/change-password');
                    }
                } else {
                    $this->Flash->error("New Password and Confirm Password doesn't match.", ['key' => 'passChangeErr']);
                    return $this->redirect(['changePassword']);
                }
            } else {
                $this->Flash->error("* Old Password doesn't match.", ['key' => 'passChangeErr']);
                return $this->redirect('/change-password');
            }
        }

        $this->set(compact('user'));
    }

    public function oldPasswordMatch()
    {
        if ($this->request->is(['post', 'put', 'patch'])) {
            // $oldPassword
            $userData = $this->Authentication->getIdentity('identity');
            $user = $this->session->read('user');
            $userPassword = $this->Users->find()->select(['password'])->where(['id' => $user->id])->toArray();

            if ((new DefaultPasswordHasher())->check($this->request->getData('password'), $userPassword[0]->password)) {
                echo 1;
                die;
            } else {
                echo 0;
                die;
            }
        }
    }

    public function forgotPassword()
    {
        $this->viewBuilder()->setLayout('login');

        if ($this->request->is('post')) {
            $email =  $this->request->getData('email');

            $emailexists = $this->Users->find()
                ->where(['email' => $email])
                ->toArray();

            if (count($emailexists) > 0) {
                $password = "password123";
                $newHashPass = (new DefaultPasswordHasher())->hash($password);
                $updatePass = $this->Users->query()
                    ->update()
                    ->set(['password' => $newHashPass])
                    ->where(['email' => $email]);
                if ($updatePass->execute()) {

                    $emailData = [];
                    $emailData['toEmail'] = $email;
                    $emailData['userName'] = $email;
                    $emailData['password'] = $password;
                    $emailData['userId'] = $emailexists[0]->id;

                    $this->sendEmailViaEmailType(10, $emailData);

                    // $this->forgotPasswordEmailSend($password, $email);
                    $this->Flash->success("New password has been set. Now you can login with new password.", ['key' => 'newPasswordSet']);
                    return $this->redirect('/');
                }
                $this->Flash->error("* Password not set. Please! try again.", ['key' => 'emailNotFound']);
                return $this->redirect('/forgot-password');
            }
            $this->Flash->error("* Email not found.", ['key' => 'emailNotFound']);
            return $this->redirect('/forgot-password');
        }
    }

    public function updatePassword()
    {
        $this->viewBuilder()->setLayout('login');
        $user = $this->session->read('user');

        // Redirect to login if the user session is not found
        if (empty($user)) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        if ($this->request->is(['post', 'put', 'patch'])) {
            // Retrieve the current password from the database
            $userPassword = $this->userTbl->find()
                ->select(['password'])
                ->where(['id' => $user->id])
                ->first();

            // Verify the old password
            if ((new DefaultPasswordHasher())->check($this->request->getData('old_password'), $userPassword->password)) {
                $newPass = $this->request->getData('new_password');
                $confirmPass = $this->request->getData('confirm_password');

                // Check if new and confirm passwords match
                if ($newPass === $confirmPass) {
                    $password = (new DefaultPasswordHasher())->hash($newPass);

                    // Update the password and reset the password_change_required flag
                    $changePass = $this->userTbl->query()
                        ->update()
                        ->set(['password' => $password, 'password_change_required' => 0])
                        ->where(['id' => $user->id])
                        ->execute();

                    if ($changePass) {
                        $this->Authentication->setIdentity($this->userTbl->get($user->id));
                        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                    } else {
                        $this->Flash->error("Password not changed.", ['key' => 'passChangeErr']);
                    }
                } else {
                    $this->Flash->error("New Password and Confirm Password do not match.", ['key' => 'passChangeErr']);
                }
            } else {
                $this->Flash->error("Old Password doesn't match.", ['key' => 'passChangeErr']);
            }

            return $this->redirect('/update-password');
        }

        $this->set(compact('user'));
    }

    public function activeInactiveStaff($id = null)
    {
        if ($this->request->is("GET")) {
            $id = $this->request->getQuery('id');
            $status = $this->request->getQuery('status');
            $changeStatus = $this->Users->query();
            if ($status == 1) {
                $changeStatus
                    ->update()
                    ->set(['status' => 0])
                    ->where(['id' => $id]);
            } else {
                $changeStatus
                    ->update()
                    ->set(['status' => 1,'termination_access_days' => null,'termination_date' => null])
                    ->where(['id' => $id]);
            }
            if ($changeStatus->execute()) {
                $userStatus = $this->Users->find()
                ->select(['id', 'status', 'deactivation_template_id'])
                ->where(['id' => $id])
                ->first();
            
            if ($userStatus && $userStatus->deactivation_template_id == 4) {
                $storeStatus = $this->storeTbl->find()
                    ->select(['id', 'onboarding_status'])
                    ->where(['clients' => $id])
                    ->toArray();
            
                foreach ($storeStatus as $store) {
                    if ($store->onboarding_status == 'Terminated') {
                        $onboardRecord = $this->onboardTbl->find()
                            ->select(['id', 'category'])
                            ->where(['user_id' => $id, 'store_id' => $store->id])
                            ->first();
            
                        $onboardingStatus = 'Active'; 
                        if ($onboardRecord) {
                            switch ($onboardRecord->category) {
                                case 4:
                                    $onboardingStatus = 'Onboarding';
                                    break;
                                case 5:
                                    $onboardingStatus = 'Active';
                                    break;
                                case 6:
                                    $onboardingStatus = 'Active-Launch';
                                    break;
                                case 7:
                                    $onboardingStatus = 'Abandoned';
                                    break;
                                default:
                                $onboardingStatus = 'Active';
                            }
                        }

                        $storeNameType = $this->storeNameType->find()
                        ->select(['name' => 'StoreType.store_name'])
                        ->join([
                            'StoreType' => [
                                'table' => 'store_type',
                                'type' => 'LEFT',
                                'conditions' => 'StoreType.id = StoreTypeAssign.store_name_id'
                            ]
                        ])
                        ->where(['StoreTypeAssign.store_id' => $store->id])->first();
                        if ($storeNameType && $storeNameType->name == 'Walmart') {
                            $checkData = $this->userWalmartOnboardingTbl->find()->where(['user_id' => $id,'store_id'=>$store->id])->first();
                           
                            if ($checkData) {
                                switch ($checkData->category) {
                                    case 1:
                                    case 2:
                                    case 3:
                                        $onboardingStatus = 'Onboarding';
                                        break;
                                    case 5:
                                        $onboardingStatus = 'Active';
                                        break;
                                    case 6:
                                        $onboardingStatus = 'Active-Launch';
                                        break;
                                }
                            }
                        }
            
                        $this->storeTbl->query()
                            ->update()
                            ->set(['onboarding_status' => $onboardingStatus])
                            ->where(['clients' => $id,'id' => $store->id])
                            ->execute();
                    }
                }
            } 

                echo 1;
                die;
            }
        }
    }

    public function uniqueEmailcheck($email = null)
    {
        if ($this->request->is('GET')) {
            if ($this->request->getQuery('id')) {
                $emailFound = $this->Users->find()
                    ->where(['id !=' => $this->request->getQuery('id'), 'email' => $this->request->getQuery('email'),'delete_user' => 0])
                    ->toArray();
                if (count($emailFound) > 0) {
                    echo 1;
                    die;
                } else {
                    echo 0;
                    die;
                }
            } else {
                $emailFound = $this->Users->find()
                    ->where(['email' => $this->request->getQuery('email'),'delete_user' => 0])
                    ->toArray();
                if (count($emailFound) > 0) {
                    echo 1;
                    die;
                } else {
                    echo 0;
                    die;
                }
            }
        }
    }

    public function addInternalStaff()
    {
        $user = $this->session->read('user');
        if(!$user)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        if($user->user_type==2)
        {
            return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
        }
        
        if ($this->request->is('post')) {
            $staff = $this->Users->newEmptyEntity();            
            
            if($this->request->getData('role_id')  == 15){
                $parent_role = $this->request->getData('senior_manager');
            }else if($this->request->getData('role_id')  == 16){
                $parent_role = $this->request->getData('primary_account_manager');
            }else{
                $parent_role = 0;   
            }
            $email = $this->request->getData('email');
            $password = "password";
            $store_permission =  $this->request->getData('store_permission');
            if($store_permission != ''){
                $store_permission = $store_permission;
            }else{
                $store_permission = 0;
            }
            $first_name = $this->request->getData('first_name');
            $last_name = $this->request->getData('last_name');
            $staff->first_name = $first_name;
            $staff->last_name = $last_name;
            $staff->email = $this->request->getData('email');
            // $staff->password = $password;
            $staff->password = (new DefaultPasswordHasher())->hash($password);

            $staff->contact_no = $this->request->getData('contact_no');
            $staff->role_id = $this->request->getData('role_id');
            $staff->parent_role = $parent_role;
            $staff->store_permission = $store_permission;
            $staff->user_type = 1;
            
            // //Enable 2FA
            // $tfa = new TwoFactorAuth();
            // $staff->secret = $tfa->createSecret();
            
            if($this->request->getData('role_id') == 15 || $this->request->getData('role_id') == 20){
                $staff->manager_bio = $this->request->getData('manager_bio');
                $staff->store_capacity = $this->request->getData('store_capacity');
                $staff->calender_id = $this->request->getData('calender_id');
            }
            
            // Image Upload 
            $image = $this->request->getData('image');
            $imgName = chr(rand(97, 122)) . rand(10000, 99999) . $image->getClientFilename();
            $targetPath = WWW_ROOT . 'img' . DS . 'ECOM360' . DS . 'avatars' . DS  . $imgName;

            if ($image->getClientFilename()) {
                $staff->image = $imgName;
                $image->moveTo($targetPath);
            }
            if ($result = $this->Users->save($staff)) {
                
                // Assign role permissions to user as individual permissions
                $this->assignRolePermissionsToUser($result->id, $staff->role_id); 
                
                if ($this->request->getData('issue_type')) {
                    for ($i = 0; $i < count($this->request->getData('issue_type')); $i++) {
                        $issues = $this->issueTbl->newEmptyEntity();
                        $issues->staff_id = $result->id;
                        $issues->issue_type = $this->request->getData('issue_type')[$i];
                        $this->issueTbl->save($issues);
                    }
                }
                if ($this->request->getData('temporary_account_manager')) {
                    for ($i = 0; $i < count($this->request->getData('temporary_account_manager')); $i++) {
                        $StaffAccountManagers = $this->StaffAccountManagers->newEmptyEntity();
                        $StaffAccountManagers->staff_id = $result->id;
                        $StaffAccountManagers->parent_role = $this->request->getData('temporary_account_manager')[$i];
                        $this->StaffAccountManagers->save($StaffAccountManagers);
                    }
                }                    
                

                if ($this->request->getData('selected_store')) {
                    $query = $this->staffTbl->query();
                    
                    for ($i = 0; $i < count($this->request->getData('selected_store')); $i++) {
                        $staff_store = $this->staffTbl->newEmptyEntity();
                        $staff_store->staff_id = $result->id;
                        $staff_store->group_id = $this->request->getData('selected_store')[$i];
                        $this->staffTbl->save($staff_store);
                    }  
                }

                $emailData = [];
                $emailData['toEmail'] = $email;
                $emailData['name'] = $first_name.' '.$last_name;
                $emailData['userName'] = $email;
                $emailData['password'] = $password;
                $emailData['userId'] = $result->id;

              // $this->sendEmailViaEmailType(11, $emailData);
                $this->Flash->success('Data is saved successfully.', ['key' => 'dataSave']);
                return $this->redirect(['controller' => 'InternalStaff', 'action' => 'index']);
            } else {
                $this->Flash->error($staff->getErrors()['email']['_isUnique'], ['key' => 'emailError']);
                return $this->redirect(['controller' => 'InternalStaff', 'action' => 'index']);
            }
        }
    }

    public function editInternalStaff($id = null)
    {
        $user = $this->session->read('user');
        if($user->user_type==2)
        {
            return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
        }
        
        if ($this->request->is("GET")) {
            $this->viewBuilder()->setLayout('ajax');
            $id = $this->request->getQuery('id');

            $staffData = $this->Users->find()
                ->select(['Users.id', 'store_permission', 'first_name', 'last_name', 'email', 'contact_no', 'image', 'role_id','parent_role','issue_type'=>'UsersIssues.issue_type','manager_bio','store_capacity','embed_code','calender_id','amazon','walmart'])
                ->join([
                    'UsersIssues' =>[
                        'table' => 'users_issues',
                        'type' => 'Left',
                        'conditions' => 'Users.id = UsersIssues.staff_id'
                    ]
                ])
                ->where(['Users.id' => $id])
                ->toArray();
                $issueCount = 0;
            foreach($staffData as $val)
            {
                if(in_array($val->issue_type,[1,2,3,4,5,6,7,8,10,11,12]))
                    $issueCount++;
            }    

                $roles = $this->roleTbl->find()
                    ->select(['id', 'role_name'])
                    ->where(['status' => 1, 'delete_role' => 0])
                    ->toArray();

                $senior_manager = $this->Users->find()
                    ->select(['id', 'name' => 'concat(first_name," ",last_name)'])
                    ->where(['delete_user' => 0,'role_id' => 20])
                    ->toArray();
                $account_manager = $this->Users->find()
                    ->select(['id', 'name' => 'concat(first_name," ",last_name)'])
                    ->where(['delete_user' => 0,'role_id IN' => [15,20]])
                    ->toArray();   
                $temp_account_manager = $this->Users->find()
                    ->select(['id', 'name' => 'concat(first_name," ",last_name)'])
                    ->where(['delete_user' => 0,'role_id IN' => [15,20],'id !=' => $staffData[0]['parent_role']])
                    ->toArray();
                $this->set(compact('staffData','roles','issueCount', 'account_manager','temp_account_manager'));
        } elseif ($this->request->is(['post', 'put', 'patch'])) {

            $staff_id = $this->request->getData('staff_id');
            $emailCount = $this->Users->find()
            ->where(['id !=' => $staff_id, 'email' => $this->request->getData('email'),'delete_user'=>0])
            ->toArray();
             //get the staff account information
             $staff = $this->Users->find('all')
             ->select(['first_name','role_id'])
             ->where(['id' => $staff_id])
             ->first();
            $currentRole = $staff->role_id;
            $newRole = $this->request->getData('role_id');
            if (count($emailCount) > 0) {
                $this->Flash->error('Your data is not saved because this email is already in used.', ['key' => 'emailError']);
                return $this->redirect(['controller' => 'Users', 'action' => 'internalStaff']);
            }
            $image = $this->request->getData('image');
            $imgName = chr(rand(97, 122)) . rand(10000, 99999) . $image->getClientFilename();
            $targetPath = WWW_ROOT . 'img' . DS . 'ECOM360' . DS . 'avatars' . DS  . $imgName;
            
            $manager_bio = $embed_code = $calender_id = '';$store_capacity = $parent_role = 0;
            $store_permission =  $this->request->getData('store_permission');

            if($this->request->getData('role_id') == 15 || $this->request->getData('role_id') == 20){
                $manager_bio = $this->request->getData('manager_bio');
                $store_capacity = $this->request->getData('store_capacity');
                // $embed_code = $this->request->getData('embed_code');
                $calender_id = $this->request->getData('calender_id');
            }
            if($this->request->getData('role_id')  == 15){
                $parent_role = $this->request->getData('senior_manager');
            }elseif($this->request->getData('role_id')  == 16){
                $parent_role = $this->request->getData('primary_account_manager');
            }
            if($this->request->getData('role_id') != 16){
                $store_permission = $store_permission;
            }else{
                $store_permission = 0;
            }

            if ($image->getClientFilename()) {
                
                $data = [
                        'first_name' => $this->request->getData('first_name'),
                        'last_name' => $this->request->getData('last_name'),
                        'email' => $this->request->getData('email'),
                        'contact_no' => $this->request->getData('contact_no'),
                        'role_id' => $this->request->getData('role_id'),
                        'parent_role' => $parent_role,
                        'store_permission' => $store_permission,
                        'manager_bio' => $manager_bio,
                        'store_capacity' => $store_capacity,
                        // 'embed_code' => $embed_code,
                        'image' => $imgName,
                        'calender_id' => $calender_id
                    ];
            }else{
                $data = [
                    'first_name' => $this->request->getData('first_name'),
                    'last_name' => $this->request->getData('last_name'),
                    'email' => $this->request->getData('email'),
                    'contact_no' => $this->request->getData('contact_no'),
                    'role_id' => $this->request->getData('role_id'),
                    'parent_role' => $parent_role,
                    'store_permission' => $store_permission,
                    'manager_bio' => $manager_bio,
                    'store_capacity' => $store_capacity,
                    // 'embed_code' => $embed_code,
                    'calender_id' => $calender_id
                ];                
            }
            $storeType = $this->request->getData('store_type');
                $data['amazon'] = 0; 
                $data['walmart'] = 0;                
                if (!empty($storeType)) {
                    foreach ($storeType as $type) {
                        if ($type == 1) {
                            $data['amazon'] = 1;
                        } elseif ($type == 2) {
                            $data['walmart'] = 1;
                        }
                    }
                }
            $staffUpdate = $this->Users->query()
                ->update()
                ->set($data)
            ->where(['id' => $staff_id]);
            
            if($store_permission == 1 || $store_permission == 0){
                $query = $this->staffTbl->query();
                $query->delete()
                ->where(['staff_id' => $staff_id])
                ->execute();
            }
            
            if($this->request->getData('role_id') != 16){
                $query = $this->StaffAccountManagers->query();
                $query->delete()
                      ->where(['staff_id' => $staff_id])
                      ->execute();
            }
            if ($staffUpdate->execute()) {
                //manage user individual permissions
                if ($currentRole != $newRole) {
                    $query = $this->UserPermissions->query();
                    $query->delete()
                        ->where(['user_id' => $staff_id])
                        ->execute();
                    $this->assignRolePermissionsToUser($staff_id, $newRole);    
                }

                if ($image->getClientFilename()) {
                  $image->moveTo($targetPath);
                }

                if ($this->request->getData('issue_type')) {
                    $query = $this->issueTbl->query();
                    $query->delete()
                        ->where(['staff_id' => $staff_id])
                        ->execute();
                    for ($i = 0; $i < count($this->request->getData('issue_type')); $i++) {
                        $issues = $this->issueTbl->newEmptyEntity();
                        $issues->staff_id = $staff_id;
                        $issues->issue_type = $this->request->getData('issue_type')[$i];
                        $this->issueTbl->save($issues);
                    }
                }

                if ($this->request->getData('temporary_account_manager') && $this->request->getData('role_id') == 16) {
                    $query = $this->StaffAccountManagers->query();
                    $query->delete()
                        ->where(['staff_id' => $staff_id])
                        ->execute();
                    for ($i = 0; $i < count($this->request->getData('temporary_account_manager')); $i++) {
                        $StaffAccountManagers = $this->StaffAccountManagers->newEmptyEntity();
                        $StaffAccountManagers->staff_id = $staff_id;
                        $StaffAccountManagers->parent_role = $this->request->getData('temporary_account_manager')[$i];
                        $this->StaffAccountManagers->save($StaffAccountManagers);
                    }
                }     

                if ($this->request->getData('selected_store') && $store_permission == 2 ) {
                    $query = $this->staffTbl->query();
                    $query->delete()
                        ->where(['staff_id' => $staff_id])
                        ->execute();
                    for ($i = 0; $i < count($this->request->getData('selected_store')); $i++) {
                        $staff_store = $this->staffTbl->newEmptyEntity();
                        $staff_store->staff_id = $staff_id;
                        $staff_store->group_id = $this->request->getData('selected_store')[$i];
                        $this->staffTbl->save($staff_store);
                    }  
                }
 
                $this->Flash->success('Data is updated successfully.', ['key' => 'staffUpdate']);
                return $this->redirect(['controller' => 'Users', 'action' => 'internalStaff']);
                }
            } 
    }

    public function assignRolePermissionsToUser($userId, $roleId)
    {    
        // Fetch role permissions
        $rolePermissions = $this->permision->find()
        ->where(['role_id' => $roleId])
        ->toArray();

        foreach ($rolePermissions as $rolePermission) {
            // Check if the user permission already exists
            $existingUserPermission = $this->UserPermissions->find()
                ->where([
                    'user_id' => $userId,
                    'menu_id' => $rolePermission->menu_id,
                ])
                ->first();

            if ($existingUserPermission) {
                // Update the existing permission
                $existingUserPermission->permission = $rolePermission->permission;
                $this->UserPermissions->save($existingUserPermission);
            } else {
                // Insert a new permission
                $userPermission = $this->UserPermissions->newEntity([
                    'user_id' => $userId,
                    'menu_id' => $rolePermission->menu_id,
                    'permission' => $rolePermission->permission,
                ]);
                $this->UserPermissions->save($userPermission);
            }
        }
    }

}