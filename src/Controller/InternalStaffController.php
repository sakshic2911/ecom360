<?php
declare(strict_types=1);

namespace App\Controller;

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
        // $this->UserPermissions = $this->getTableLocator()->get('UserPermissions');
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

    /**
     * View method
     *
     * @param string|null $id Internal Staff id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $internalStaff = $this->InternalStaff->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('internalStaff'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $internalStaff = $this->InternalStaff->newEmptyEntity();
        if ($this->request->is('post')) {
            $internalStaff = $this->InternalStaff->patchEntity($internalStaff, $this->request->getData());
            if ($this->InternalStaff->save($internalStaff)) {
                $this->Flash->success(__('The internal staff has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The internal staff could not be saved. Please, try again.'));
        }
        $this->set(compact('internalStaff'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Internal Staff id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $internalStaff = $this->InternalStaff->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $internalStaff = $this->InternalStaff->patchEntity($internalStaff, $this->request->getData());
            if ($this->InternalStaff->save($internalStaff)) {
                $this->Flash->success(__('The internal staff has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The internal staff could not be saved. Please, try again.'));
        }
        $this->set(compact('internalStaff'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Internal Staff id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $internalStaff = $this->InternalStaff->get($id);
        if ($this->InternalStaff->delete($internalStaff)) {
            $this->Flash->success(__('The internal staff has been deleted.'));
        } else {
            $this->Flash->error(__('The internal staff could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
