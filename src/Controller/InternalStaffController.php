<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Datasource\ConnectionManager;


/**
 * InternalStaff Controller
 *
 * @method \App\Model\Entity\InternalStaff[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InternalStaffController extends AppController
{
    private $session;
    private $roleTbl;
    public function initialize(): void
    {
        parent::initialize();
        // $this->getTableLocator('Users');
        $this->session = $this->request->getSession();

        $this->roleTbl = $this->getTableLocator()->get('Roles');
        $this->menu = $this->getTableLocator()->get('Menus');
        $this->permision = $this->getTableLocator()->get('RolePermissionMenus');
        // $this->issueTbl = $this->getTableLocator()->get('UsersIssues');
        $this->loginTbl = $this->getTableLocator()->get('UserLogins');
        $this->userTbl = $this->getTableLocator()->get('Users');
        // $this->onboardTbl = $this->getTableLocator()->get('UserOnboarding');
        // $this->brandTbl = $this->getTableLocator()->get('BrandApprovalData');
        // $this->staffTbl = $this->getTableLocator()->get('StaffStore');
        // $this->storeGroupTbl = $this->getTableLocator()->get('StoreGroup');
        // $this->grplistTbl = $this->getTableLocator()->get('GroupList');
        // $this->ActivityLogs = $this->getTableLocator()->get('ActivityLogs');
        // $this->DeactivationTemplates = $this->getTableLocator()->get('DeactivationTemplates');
        // $this->StaffAccountManagers = $this->getTableLocator()->get('StaffAccountManagers');
        $this->UserPermissions = $this->getTableLocator()->get('UserPermissions');
        // $this->storeNameType = $this->getTableLocator()->get('StoreTypeAssign');

    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['login','updateSession','updatePassword','setupTwoFactor','verifyTwoFactor']);

    }
    public function index()
    {
         // echo "Hello"; die;
         $this->viewBuilder()->setLayout('admin_dashboard');
         $this->session->write('activeUrl', 'internal-staff');
         $user = $this->session->read('user');
         if(!$user)
         {
             return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
         }
         if($user->user_type==2)
         {
             return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
         }
         $menu = $this->session->read('menu');
         $permission = 3; $lgPermission = 3;
         foreach($menu as $m)
         {
             if($m->folder == 'Internal Staff')
             {
                 foreach($m->main['Internal Staff'] as $ml) {
 
                     if($ml->url=='internal-staff' && $user->user_type==1)
                     { 
                         $permission = $ml->permission;
                         break;
                     }
                 }
                 
             }
             if($m->folder == 'Extra')
             {
                 foreach($m->main['Extra'] as $ml) {
 
                     if($ml->menu_name=='Login as Staff' && $ml->permission != 3)
                     { 
                         $lgPermission = 1;
                     }
                 }
             }
             
         }
 
         $roles = $this->roleTbl
             ->find()
             ->select(['id', 'role_name'])
             ->where(['status' => 1, 'delete_role' => 0])
             ->toArray();
 
             $role = '';
             $condition = ['Users.user_type' => 1, 'Users.delete_user' => 0];
     
             if ($this->request->is('post')) {
                 $role = $this->request->getData('role');
                
                 if($role > 0):
                     $condition['Roles.id'] = $role; 
                 endif;
             }
     
 
         $staffData = $this->userTbl->find('all', [
             'fields' => [
                 'role_name' => 'Roles.role_name',
                 'first_name' => 'Users.first_name',
                 'last_name' => 'Users.last_name',
                 'email' => 'Users.email',
                 'contact_no' => 'Users.contact_no',
                 'status' => 'Users.status',
                 'issue' => 'Users.issue_type',
                 'id' => 'Users.id',
                 'login_time' => 'DATE_FORMAT(DATE_SUB(MAX(UserLogin.log_in), INTERVAL 4 HOUR),"%m/%d/%Y %H:%i%s")' 
             ],
             'join' => [
                 'Roles' => [
                     'table' => 'roles',
                     'type' => 'LEFT',
                     'conditions' => '`Roles`.`id` = `Users`.`role_id`'
                 ],
                 'UserLogin' => [
                     'table' => 'user_logins',
                     'type' => 'LEFT',
                     'conditions' => 'Users.id = UserLogin.user_id'
                 ]
             ],
 
         ])->where($condition)->group('Users.id')->limit(5)->toArray();
 
        
        $groupData = [];             
         
         $senior_manager = $this->userTbl->find()
         ->select(['id', 'name' => 'concat(first_name," ",last_name)'])
         ->where(['delete_user' => 0,'role_id' => 20])
         ->toArray();      
         
         $account_manager = $this->userTbl->find()
         ->select(['id', 'name' => 'concat(first_name," ",last_name)'])
         ->where(['delete_user' => 0,'role_id IN' => [15,20]])
         ->toArray();   
        
         $this->set(compact('roles', 'staffData','user','permission','groupData','lgPermission','role','senior_manager','account_manager'));
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

            $staffData = $this->userTbl->find()
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

                $senior_manager = $this->userTbl->find()
                    ->select(['id', 'name' => 'concat(first_name," ",last_name)'])
                    ->where(['delete_user' => 0,'role_id' => 20])
                    ->toArray();
                $account_manager = $this->userTbl->find()
                    ->select(['id', 'name' => 'concat(first_name," ",last_name)'])
                    ->where(['delete_user' => 0,'role_id IN' => [15,20]])
                    ->toArray();   
                $temp_account_manager = $this->userTbl->find()
                    ->select(['id', 'name' => 'concat(first_name," ",last_name)'])
                    ->where(['delete_user' => 0,'role_id IN' => [15,20],'id !=' => $staffData[0]['parent_role']])
                    ->toArray();
                $this->set(compact('staffData','roles','issueCount', 'account_manager','temp_account_manager'));
        } elseif ($this->request->is(['post', 'put', 'patch'])) {

            $staff_id = $this->request->getData('staff_id');
            $emailCount = $this->userTbl->find()
            ->where(['id !=' => $staff_id, 'email' => $this->request->getData('email'),'delete_user'=>0])
            ->toArray();
             //get the staff account information
             $staff = $this->userTbl->find('all')
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
            $staffUpdate = $this->userTbl->query()
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

    public function individualPermission(){
        $id = $this->request->getData('id');
        $connection = ConnectionManager::get('default');
        
            $query = "SELECT menus.menu_name,menus.id,menus.parent,
            (CASE
                WHEN user_permissions.permission IS NULL THEN 3
                ELSE user_permissions.permission
            END) as permission,menus.folder
            FROM menus
            LEFT JOIN user_permissions ON menus.id = user_permissions.menu_id
                AND user_permissions.user_id = $id
            WHERE menus.status = 'Active' and menus.user not in (4,5) ORDER BY sequence ASC,sub_sequence ASC";
 
        $menuData = $connection->execute($query)->fetchAll('assoc');
        
        $organizedMenu = [];

        foreach ($menuData as $menu) {
            $folder = $menu['folder'];
            $parent = $menu['parent'];

            if ($parent) {
                // If the menu has a parent, associate it under the parent's folder
                if (!isset($organizedMenu[$parent])) {
                    $organizedMenu[$parent] = [ 
                        'folder' => $parent,
                        'items' => [],
                    ];
                }
                $organizedMenu[$parent]['items'][] = $menu; // Add the menu to the parent's items
            } else {
                // If no parent, organize it directly under its folder
                if (!isset($organizedMenu[$folder])) {
                    $organizedMenu[$folder] = [
                        'folder' => $folder,
                        'items' => [],
                    ];
                }
                $organizedMenu[$folder]['items'][] = $menu;
            }
        }

        $this->set(compact('organizedMenu','id'));  
    }

    public function updateIndividualPermission()
    {
        $data = $this->request->getData();
        $userId = $data['userId'];
        unset($data['userId']); 

        // Fetch existing permissions for the user
        $existingPermissions = $this->UserPermissions->find()
        ->select(['id','menu_id', 'permission'])
        ->where(['user_id' => $userId])
        ->indexBy('menu_id')
        ->toArray();

        foreach ($data as $key => $val) {
            $menuId = (int) strtok($key, '/');

            if ($menuId === 0) {
                continue;
            }
            if (isset($existingPermissions[$menuId])) {
                $existingPermission = $existingPermissions[$menuId];
                if ($existingPermission->permission != $val) {
                    // Update existing permission if it has changed
                    $existingPermission->permission = $val;
                    $existingPermission->type = 'user';
                    $this->UserPermissions->save($existingPermission);
                }
            } else if($val != 3){
                // Create a new permission
                $newPermission = $this->UserPermissions->newEntity([
                    'user_id' => $userId,
                    'menu_id' => $menuId,
                    'permission' => $val,
                    'type' => 'user',
                ]);
                $this->UserPermissions->save($newPermission);
            }
        }
        $this->Flash->success('Permission updated successfully.', ['key' => 'staffUpdate']);

        return $this->redirect(['controller' => 'InternalStaff', 'action' => 'internalStaff']);
    }
}
