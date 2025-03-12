<?php

declare(strict_types=1);

namespace App\Controller;
use Cake\Datasource\ConnectionManager;

class RolesController extends AppController
{
    private $session;

    public function initialize(): void
    {
        parent::initialize();
        $this->session = $this->request->getSession();
        $this->menuTbl = $this->getTableLocator()->get('Menus');
        $this->rolePermissionTbl = $this->getTableLocator()->get('RolePermissionMenus');
        $this->UserPermissions = $this->getTableLocator()->get('UserPermissions');
        $this->userTbl = $this->getTableLocator()->get('Users');
    }

    public function index()
    {
        
        $this->viewBuilder()->setLayout('admin_dashboard');
        $this->session->write('activeUrl', 'roles');
        $user = $this->session->read('user');
        if(!$user)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        if($user->user_type==2)
        {
            return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
        }
        $menuList = $this->session->read('menu');
        $permission = 3;
        foreach($menuList as $m)
        {
            if($m->folder == 'Roles')
            {
                foreach($m->main['Roles'] as $ml) {

                    if($ml->url=='roles' && $user->user_type==1)
                    { 
                        $permission = $ml->permission;
                        break;
                    }
                }
                break;
            }
        }

        $menu = $this->menuTbl->find()
            ->select(['id', 'menu_name','folder','parent'])
            ->where(['status' => 'Active','user not IN' => [4,5]])
            ->order(['sequence' => 'ASC','sub_sequence' => 'ASC'])
            ->toArray();

        $organizedMenu = [];
        foreach($menu as $ml){
            $folder = $ml->folder;
            $parent = $ml['parent'];
            if ($parent) {
                // If the menu has a parent, associate it under the parent's folder
                if (!isset($organizedMenu[$parent])) {
                    $organizedMenu[$parent] = [ 
                        'folder' => $parent,
                        'items' => [],
                    ];
                }
                $organizedMenu[$parent]['items'][] = $ml; // Add the menu to the parent's items
            } else {
                // If no parent, organize it directly under its folder
                if (!isset($organizedMenu[$folder])) {
                    $organizedMenu[$folder] = [
                        'folder' => $folder,
                        'items' => [],
                    ];
                }
                $organizedMenu[$folder]['items'][] = $ml;
            }
        }

        $roleName = $this->Roles->find()
            ->select(['id', 'role_name'])
            ->where(['delete_role' => 0]) // 0 for not deleted roles and 1 for delete role
            ->toArray();

        $roles = $this->paginate($this->Roles);

        $this->set(compact('roles', 'organizedMenu', 'roleName','user','permission'));
    }

    public function view($id = null)
    {
        $role = $this->Roles->get($id, [
            'contain' => ['RolePermissionMenu', 'Users'],
        ]);

        $this->set(compact('role'));
    }

    public function add()
    {
        $user = $this->session->read('user');
        if($user->user_type==2)
        {
            return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
        }
        $role = $this->Roles->newEmptyEntity();
        if ($this->request->is('post')) {

            // Get the Menu Id from keys
            foreach ($this->request->getData() as $key => $val) {
                $menu_id[] = (int) strtok($key, '/');
            }
            // $role = $this->Roles->patchEntity($role, $this->request->getData());
            $role->role_name = $this->request->getData('role_name');
            if ($this->Roles->save($role)) {

                $roleId = $role->id;
                // Loop start index 1 bcz role name is 0 index  and we need only Menu Id and Permission value 
                for ($i = 1; $i < count($menu_id); $i++) {
                    $role_permission_menu = $this->rolePermissionTbl->newEmptyEntity();
                    $role_permission_menu->role_id = $roleId;
                    $role_permission_menu->menu_id = $menu_id[$i];
                    // Array value is convert associative array to simple array 
                    $role_permission_menu->permission = array_values($this->request->getData())[$i];
                    $this->rolePermissionTbl->save($role_permission_menu);
                }
                $this->Flash->success('The role has been saved.', ['key' => 'roleSave']);
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The role could not be saved. Please, try again.'));
        }
        $this->set(compact('role'));
    }

    public function edit($id = null)
    {
        $user = $this->session->read('user');
        if($user->user_type==2)
        {
            return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
        }
        
        if ($this->request->is('get')) {

            $id = $this->request->getQuery('id');            
            $roleData = $this->Roles->find()
            ->select([
                    'roleName' => 'Roles.role_name',
                     'roleId' => 'Roles.id'
                    ])
            ->where(['id' => $id])->toArray();


            
            $connection = ConnectionManager::get('default');
            $query = "SELECT menu.menu_name,menu.id,menu.parent,
                        (CASE
                            WHEN role_permission_menus.permission IS NULL THEN 3
                            ELSE role_permission_menus.permission
                        END) as permission,menu.folder
                        FROM menus as menu
                        LEFT JOIN role_permission_menus  ON menu.id = role_permission_menus.menu_id
                            AND role_permission_menus.role_id = $id
                        WHERE menu.status = 'Active' and menu.user not in (4,5) ORDER BY sequence ASC,sub_sequence ASC";
             
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

            $this->set(compact('roleData','organizedMenu'));
            
        } else if ($this->request->is(['patch', 'post', 'put'])) {

            $roleNameUpdate = $this->Roles->query()
                ->update()
                ->set(['role_name' => $this->request->getData('role_name')])
                ->where(['id' => $this->request->getData('roleId')]);

            // Get the Menu Id from keys
            foreach ($this->request->getData() as $key => $val) {
                $menu_id[] = (int) strtok($key, '/');
                
            }
           
             $rolePermissionData = $this->rolePermissionTbl->find()->select(['menu_id'])->where(['role_id' => $this->request->getData('roleId')])->toArray();
             $permissionData = [];
             foreach($rolePermissionData as $value)
             {
                $permissionData[] = (int) ($value->menu_id);
             }
            
            $x = 1;
            for ($i = 1; $i < count($menu_id); $i++) {

                // First Time Add Menu into permission table - kajal
                if(!in_array($menu_id[$i], $permissionData))
                {
                    $role_permission_menu = $this->rolePermissionTbl->newEmptyEntity();
                    $role_permission_menu->role_id = $this->request->getData('roleId');
                    $role_permission_menu->menu_id = $menu_id[$i];
                    $role_permission_menu->permission = array_values($this->request->getData())[$i];
                    if($menu_id[$i] != 0):
                       $this->rolePermissionTbl->save($role_permission_menu);
                    endif;
                }
                else
                {
                    $this->rolePermissionTbl->query()
                    ->update()
                    ->set(['permission' => array_values($this->request->getData())[$i]])
                    ->where(['menu_id' => $menu_id[$i], 'role_id' => $this->request->getData('roleId')])
                    ->execute();
                }
                $x++;
            }
            if ($roleNameUpdate->execute()) {

                $this->assignRolePermissionsToUser($this->request->getData('roleId'));  
                $this->Flash->success('The role data has been updated.', ['key' => 'roleUpdate']);
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The role data could not be updated. Please, try again.'));
        }
    }


    public function delete($id = null)
    {
        $this->request->allowMethod(['get', 'delete']);
        $id = $this->request->getQuery('id');
        // 1 for delete and 0 for not delete
        $this->Roles->query()
            ->update()
            ->set(['delete_role' => 1])
            ->where(['id' => $id])
            ->execute();
        echo 1;
        die;
    }

    public function assignRolePermissionsToUser($roleId)
    {    
        // Fetch role permissions
        $rolePermissions = $this->rolePermissionTbl->find()
        ->where(['role_id' => $roleId])
        ->toArray();

        foreach ($rolePermissions as $rolePermission) {

            $userData = $this->userTbl->find()->select(['id'])->where(['role_id' => $roleId])->toArray();
            foreach($userData as $u)
            {
                    // Check if the user permission already exists
                    $existingUserPermission = $this->UserPermissions->find()
                        ->where([
                            'user_id' => $u->id,
                            'menu_id' => $rolePermission->menu_id,
                        ])
                        ->first();

                    if ($existingUserPermission) {
                        if ($existingUserPermission->type == 'user') {
                            continue; // Skip this record when the type is 'user'
                        }

                        // If permission exists with type 'role', update it
                        if ($existingUserPermission->type == 'role') {
                            $existingUserPermission->permission = $rolePermission->permission;
                            $this->UserPermissions->save($existingUserPermission);
                        }
                    } else {
                        // Insert a new permission
                        $userPermission = $this->UserPermissions->newEntity([
                            'user_id' => $u->id,
                            'menu_id' => $rolePermission->menu_id,
                            'permission' => $rolePermission->permission,
                        ]);
                        $this->UserPermissions->save($userPermission);
                    }
            }
            
        }
    }
}
