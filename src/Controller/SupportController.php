<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Support Controller
 *
 * @method \App\Model\Entity\Support[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SupportController extends AppController
{
    private $session;
    public function initialize(): void
    {
        parent::initialize();
        $this->session = $this->request->getSession();
        $this->viewBuilder()->setLayout('admin_dashboard');
        $this->supportcategoryTbl = $this->getTableLocator()->get('SupportCategories');
        $this->supportresourceTbl = $this->getTableLocator()->get('SupportResources'); 
        // $this->clientmeetingtbl =$this->getTableLocator()->get('ClientWeeklyMeeting');   
        // $this->ActivityLogs = $this->getTableLocator()->get('ActivityLogs');
        $this->storeType = $this->getTableLocator()->get('StoreTypes');
        $this->resourcescategoryTbl = $this->getTableLocator()->get('ResourcesAssignCategories');
        $this->resourcesstoretypeTbl = $this->getTableLocator()->get('ResourcesAssignStoretype');
        // $this->storeNameType = $this->getTableLocator()->get('StoreTypeAssign');
        // $this->storeTbl = $this->getTableLocator()->get('Store');

    }

    public function supportCategories()
    {
        $loginUser = $this->session->read('user');
        if($loginUser->user_type==2)
        {
            return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
        }
        $menu = $this->session->read('menu');
        $permission = 3;
        foreach($menu as $m)
        { 
            if($m->folder == 'Support')
            {
                foreach($m->main['Support'] as $ml) {

                    if($ml->url=='support-library' && $loginUser->user_type==1)
                    { 
                        $permission = $ml->permission;
                        break;
                    }
                }
            }
        }
        $categoryData = $this->supportcategoryTbl->find()->where(['is_deleted' => 0])->order(['ranking'=>'ASC'])->toArray();    
        $Rank = $this->supportcategoryTbl->find()->select(['last_rank' => 'MAX(ranking)'])->where(['is_deleted'=>0])->first();
        $lastRank = $Rank->last_rank;
        $this->set(compact('categoryData','loginUser','permission','lastRank'));
    }
   
    public function supportResources()
    {
        $loginUser = $this->session->read('user');
        if($loginUser->user_type==2)
        {
            return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
        }
        $menu = $this->session->read('menu');
      
        $condition = ['SupportResources.is_deleted' => 0];
        $category_id = 0;
        if ($this->request->is('post')) {
           $category_id = $this->request->getData('category_id');
           if($category_id)
              $condition['SupportCategory.id IN'] = $category_id;
        }
        $resourceData = $this->supportresourceTbl->find()->select([
            'question' => 'SupportResources.question',
            'id' => 'SupportResources.id',
            'for_type' => 'SupportResources.for_type',
            'description' => 'SupportResources.description',
            'embed_code' => 'SupportResources.embed_code',
            'url' => 'SupportResources.url',
            'tags' => 'SupportResources.tags',
            'store_names' =>'GROUP_CONCAT(DISTINCT StoreType.store_name SEPARATOR ", ")',
            'category_names' => 'GROUP_CONCAT(DISTINCT SupportCategory.name SEPARATOR ", ")',
            'status'  => 'SupportResources.status',
            'ranking'  => 'ResourcesAssignCategory.ranking',
            'cat_id'  => 'ResourcesAssignCategory.id'
        ])->join([
            'ResourcesAssignStoretype' => [
                'table' => 'resources_assign_storetype',
                'type' => 'LEFT',
                'conditions' => 'ResourcesAssignStoretype.resources_id = SupportResources.id',
            ],
            'StoreType' => [
                'table' => 'store_types',
                'type' => 'LEFT',
                'conditions' => 'ResourcesAssignStoretype.store_type_id = StoreType.id',
            ],
            'ResourcesAssignCategory' => [
                'table' => 'resources_assign_categories',
                'type' => 'LEFT',
                'conditions' => 'ResourcesAssignCategory.resources_id = SupportResources.id',
            ],
            'SupportCategory' => [
                'table' => 'support_categories',
                'type' => 'LEFT',
                'conditions' => 'SupportCategory.id = ResourcesAssignCategory.category_id',
            ],
        ])->group('SupportResources.id')->order(['ResourcesAssignCategory.ranking'=>'ASC'])->where($condition)->toArray(); 

        $category = $this->supportcategoryTbl->find()->select(['id','name'])->where(['is_deleted' => 0])->toArray(); 

        $storeName =  $this->storeType->find()->select(['id', 'store_name'])->where(['view' => 1])->toArray();

        $this->set(compact('resourceData','loginUser','category','storeName','category_id'));
    }

    public function addCategory()
    {
        $category = $this->supportcategoryTbl->newEmptyEntity();
        if ($this->request->is('post')) {
            $category->name = $this->request->getData('category_name');
            $category->ranking = $this->request->getData('ranking');
            if ($this->supportcategoryTbl->save($category)) {
                $this->Flash->success('The Category has been saved.', ['key' => 'success']);
                return $this->redirect(['action' => 'supportCategories']);
            }
            $this->Flash->error(__('The Category could not be saved. Please, try again.'));
        }
        $this->set(compact('category'));
    }    
    
    public function editSupportCategory()
    {
        if ($this->request->is('get')) {
            $this->viewBuilder()->setLayout('ajax');
            $id = $this->request->getQuery('id');
            $categoryData = $this->supportcategoryTbl->find()->select(['id','name','ranking'])->where(['id' => $id])->first();
            $this->set(compact('categoryData'));
        } else if ($this->request->is(['post', 'put', 'patch'])) {
            $categoryData = $this->supportcategoryTbl->query()
                ->update()
                ->set([
                    'name' => $this->request->getData('category_name')
                ])
                ->where(['id' => $this->request->getData('editId')]);

            if ($categoryData->execute()) {
                $this->Flash->success('Category data is updated successfully.', ['key' => 'success']);
                return $this->redirect(['action' => 'supportCategories']);
            }
        }
    }

    public function addResource()
    {
        $resource = $this->supportresourceTbl->newEmptyEntity();
        if ($this->request->is('post')) {
            $question = $this->request->getData('question');
                $category = $this->request->getData('category');
                $embed_code = $this->request->getData('embed_code');
                $description = $this->request->getData('description');
                $tags = $this->request->getData('tags');
                $url = $this->request->getData('url');
                $type_text = $this->request->getData('type_text');
                $type_embed = $this->request->getData('type_embed');
                $type_url = $this->request->getData('type_url');
                $for = $this->request->getData('for');
                $store_type = $this->request->getData('store_type');

                $description = ($type_text === "") ? "" : $description;
                $embed_code = ($type_embed === "") ? "" : $embed_code;
                $url = ($type_url === "") ? "" : $url;

                $resource->question = $question;
                $resource->tags = $tags;
                $resource->embed_code = $embed_code;
                $resource->for_type = $for;
                $resource->description = $description;
                $resource->url = $url;

            if ($this->supportresourceTbl->save($resource)) {
                if($for == 'Client' || $for == 'Both' )
                    {
                        if(count($store_type) >0)
                        {
                            for ($i = 0; $i < count($store_type); $i++) {
                                $resourcesstoretype = $this->resourcesstoretypeTbl->newEmptyEntity();
                                $resourcesstoretype->resources_id = $resource->id;
                                $resourcesstoretype->store_type_id	 = $store_type[$i];
                                $this->resourcesstoretypeTbl->save($resourcesstoretype);
                            }
                        }                   
                    }

                if(count($category) >0)
                    {
                        for ($i = 0; $i < count($category); $i++) {
                            $Rank = $this->resourcescategoryTbl->find()->select(['last_rank' => 'MAX(ranking)'])->where(['category_id' => $category[$i],'is_deleted' =>0 ])->first();
                            $lastRank = $Rank->last_rank + 1;
                            $resourcescategory = $this->resourcescategoryTbl->newEmptyEntity();
                            $resourcescategory->resources_id = $resource->id;
                            $resourcescategory->category_id	 = $category[$i];
                            $resourcescategory->ranking	 = $lastRank;
                            $this->resourcescategoryTbl->save($resourcescategory);
                        }
                    }     
                
                $this->Flash->success('The Resource has been saved.', ['key' => 'success']);
                return $this->redirect(['action' => 'supportResources']);
            } else {
                $this->Flash->error(__('The Resource could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('category'));
    }

    public function editSupportResources()
    {
        if ($this->request->is('get')) {
            $this->viewBuilder()->setLayout('ajax');
            $id = $this->request->getQuery('id');
            $resorceData = $this->supportresourceTbl->find()->select(['id','question','tags','description','url','embed_code','for_type'])->where(['id' => $id])->first();
            $category = $this->supportcategoryTbl->find()->select(['id','name'])->where(['is_deleted' => 0])->toArray(); 
            $storeName =  $this->storeType->find()->select(['id', 'store_name'])->where(['view' => 1])->toArray();
            $storeNameData = $this->resourcesstoretypeTbl->find()
            ->select(['store_type_id'])
            ->where(['resources_id' => $id])->toArray();
            $categoryData = $this->resourcescategoryTbl->find()
            ->select(['category_id'])
            ->where(['resources_id' => $id])->toArray();
            $this->set(compact('resorceData','category','storeName','storeNameData','categoryData'));
        } else if ($this->request->is(['post', 'put', 'patch'])) {

            $question = $this->request->getData('question');
            $category = $this->request->getData('category');
            $url = $this->request->getData('url');
            $embed_code = $this->request->getData('embed_code');
            $editId = $this->request->getData('editId');
            $description = $this->request->getData('description');
            $for_type = $this->request->getData('for_type');
            $tags = $this->request->getData('tags');
            $type_text = $this->request->getData('type_text');
            $type_embed = $this->request->getData('type_embed');
            $type_url = $this->request->getData('type_url');
            $store_type = $this->request->getData('store_type');
            
            if($type_text == "")
            {
              $description = "";
            }
            if($type_embed == "")
            {
              $embed_code = "";
            }
            if($type_url == "")
            {
              $url = "";
            }
            $categoryData = $this->supportresourceTbl->query()
                ->update()
                ->set([
                    'question' => $question,
                    'url' => $url,
                    'embed_code' => $embed_code,
                    'description' => $description,
                    'for_type' => $for_type,
                    'tags' => $tags
                ])
                ->where(['id' => $editId]);

            if ($categoryData->execute()) {

                if($for_type == 'Client' || $for_type == 'Both' )
                    {
                        $this->resourcesstoretypeTbl->query()
                        ->delete()
                        ->where(['resources_id' => $editId])
                        ->execute();
                        if(count($store_type) >0)
                        {
                            for ($i = 0; $i < count($store_type); $i++) {
                                $resourcesstoretype = $this->resourcesstoretypeTbl->newEmptyEntity(); 
                                $resourcesstoretype->resources_id = $editId;
                                $resourcesstoretype->store_type_id	 = $store_type[$i];
                                $this->resourcesstoretypeTbl->save($resourcesstoretype);
                            }
                        }                   
                    }

                    if (count($category) >0) {
                        $category_ids = [];
                        $conditions = [
                            'is_deleted' => 0,
                            'resources_id' => $editId,
                        ];
                    
                        foreach ($category as $categoryId) {
                            $count = $this->resourcescategoryTbl
                                ->find()
                                ->select(['count' => 'COUNT(ranking)'])
                                ->where(['category_id' => $categoryId] + $conditions)
                                ->first();
                    
                            $rank = $this->resourcescategoryTbl
                                ->find()
                                ->select(['last_rank' => 'MAX(ranking)'])
                                ->where(['category_id' => $categoryId, 'is_deleted' => 0])
                                ->first();
                    
                            $lastRank = $rank->last_rank + 1;
                    
                            if ($count->count === 0) {
                                $resourcescategory = $this->resourcescategoryTbl->newEmptyEntity();
                                $resourcescategory->resources_id = $editId;
                                $resourcescategory->category_id = $categoryId;
                                $resourcescategory->ranking = $lastRank;
                                $this->resourcescategoryTbl->save($resourcescategory);
                            }
                    
                            $category_ids[] = $categoryId;
                        }
                    
                        $this->resourcescategoryTbl
                            ->query()
                            ->delete()
                            ->where(['resources_id' => $editId, 'category_id NOT IN' => $category_ids])
                            ->execute();
                    }
                $this->Flash->success('Resource data is update successfully.', ['key' => 'success']);
                return $this->redirect(['action' => 'supportResources']);
            }
        }
    }    

    public function deleteSupport($id = null)
    {
        $this->request->allowMethod(['get', 'delete']);
        $id = $this->request->getQuery('id');
        $tablename = $this->request->getQuery('tablename');
        // 1 for delete and 0 for not delete
        $this->$tablename->query()
            ->update()
            ->set(['is_deleted' => 1])
            ->where(['id' => $id])
            ->execute();
        if($tablename == 'supportresourceTbl')
        {
            $this->resourcescategoryTbl->query()
            ->update()
            ->set(['is_deleted' => 1])
            ->where(['resources_id' => $id])
            ->execute();
            $this->resourcesstoretypeTbl->query()
            ->update()
            ->set(['is_deleted' => 1])
            ->where(['resources_id' => $id])
            ->execute();
        }    
        echo 1;
        die;
    }

    private function uploadFile($file, $type,$editfile)
    {
        if ($file->getClientFilename()) {
            $fileName = chr(rand(97, 122)) . rand(10000, 99999) . $file->getClientFilename();
            $targetPath = WWW_ROOT . 'img' . DS . 'ECOM360' . DS . 'support' . DS . $fileName;
            $maxVideoSize = 8 * 1024 * 1024; 

            if ($file->getSize() > $maxVideoSize) {
                $this->Flash->error("Image size not more than 800K.", ['key' => 'error']);
                return $this->redirect('/weekly-meeting');
            }

            $type = 2;
            $file->moveTo($targetPath);  
        
        }else{
            $fileName = $editfile;
        }

        return $fileName;
    }

    public function showTag()
    {
        if ($this->request->is('GET')) {
            $id = $this->request->getQuery('id');;
            $tagsData = $this->supportresourceTbl->find()->select([
                'tags' => 'tags'
            ])->where(['id' => $id,'is_deleted' => 0])
                ->toArray();
            echo json_encode($tagsData);
            die;
        }
    }

    public function activeInactiveResource($id = null)
    {
        if ($this->request->is("GET")) {
            $id = $this->request->getQuery('id');
            $status = $this->request->getQuery('status');
            $changeStatus = $this->supportresourceTbl->query();
            if ($status == 1) {
                $changeStatus
                    ->update()
                    ->set(['status' => 0])
                    ->where(['id' => $id]);
            } else {
                $changeStatus
                    ->update()
                    ->set(['status' => 1])
                    ->where(['id' => $id]);
            }
            if ($changeStatus->execute()) {
                echo 1;
                die;
            }
        }
    }
    public function faq()
    {
        $loginUser = $this->session->read('user');
        $menu = $this->session->read('menu');
        $permission = 3;
        foreach($menu as $m)
        {
            if($m->folder == 'Support')
            {
                foreach($m->main['Support'] as $ml) {

                    if($ml->url=='faq' && $loginUser->user_type==1)
                    { 
                        $permission = $ml->permission;
                        break;
                    }
                }
            }
        }
        $searchdata  = $this->request->getQuery('searchdata');
        $key  = $this->request->getQuery('key');
      
        // $clientStoreType = $this->storeTbl->find()->select('StoreTypeAssign.store_name_id')->join([
        //     'StoreTypeAssign' => [
        //         'table' => 'store_type_assign',
        //         'type' => 'LEFT',
        //         'conditions' => 'StoreTypeAssign.store_id  = Store.id',
        //     ]
        // ])->where(['Store.delete_store' => 0,'Store.clients' => $loginUser->id])->group('StoreTypeAssign.store_name_id')->order(['StoreTypeAssign.id'=>'DESC'])->toArray();
        $clientStoreType = [];
      
        $storeNameIds = [];
        foreach ($clientStoreType as $storeEntity) {
            if (isset($storeEntity->StoreTypeAssign['store_name_id'])) {
                $storeNameIds[] = $storeEntity->StoreTypeAssign['store_name_id'];
            }
        }
        $condition = ['SupportResources.is_deleted' => 0,'SupportResources.status' => 1];
        $categoryCondition = [ 'SupportCategories.is_deleted' =>0];

        
        if($key == 'searchdata')
       {
        $condition = ['SupportResources.is_deleted'=>0,
        'OR'=>['SupportResources.embed_code LIKE'=>"%$searchdata%",
        'SupportResources.url LIKE'=>"%$searchdata%",
        'SupportResources.tags LIKE'=>"%$searchdata%",
        'SupportResources.description LIKE ' => "%$searchdata%",
        'SupportResources.question LIKE ' => "%$searchdata%"]];

        $categoryCondition = ['SupportResources.is_deleted'=>0,
        'OR'=>['SupportResources.embed_code LIKE'=>"%$searchdata%",
        'SupportResources.url LIKE'=>"%$searchdata%",
        'SupportResources.tags LIKE'=>"%$searchdata%",
        'SupportResources.description LIKE ' => "%$searchdata%",
        'SupportResources.question LIKE ' => "%$searchdata%"]];
       }
        $subcondition = "";
        $joinType = "LEFT";
        $for_type = "";
        if($clientStoreType){
            $subcondition = " AND ResourcesAssignStoretype.store_type_id IN (" . implode(",", $storeNameIds) . ")";
         }
       
        if($loginUser->user_type == 2) {
            $condition['SupportResources.for_type IN'] = ['both', 'client'];
            $joinType = "INNER";
            $for_type = " and SupportResources.for_type in ('both','client')";
        } else if($loginUser->user_type == 1){
            $condition['SupportResources.for_type IN'] = ['both', 'staff'];
            $for_type = " and SupportResources.for_type in ('both','staff')";
        }

        $faqlist = $this->supportresourceTbl->find()->select([
            'question' => 'SupportResources.question',
            'id' => 'SupportResources.id',
            'for_type' => 'SupportResources.for_type',
            'description' => 'SupportResources.description',
            'embed_code' => 'SupportResources.embed_code',
            'url' => 'SupportResources.url',
            'tags' => 'SupportResources.tags',
            'category_name' => 'SupportCategory.name',
            'category_id' => 'SupportCategory.id',
            'status'  => 'SupportResources.status'
        ])->join([
            'ResourcesAssignCategory' => [
                'table' => 'resources_assign_categories',
                'type' => 'INNER',
                'conditions' => 'ResourcesAssignCategory.resources_id = SupportResources.id',
            ],
            'SupportCategory' => [
                'table' => 'support_categories',
                'type' => 'INNER',
                'conditions' => 'SupportCategory.id = ResourcesAssignCategory.category_id',
            ],
            'ResourcesAssignStoretype' => [
                'table' => 'resources_assign_storetype',
                'type' => $joinType,
                'conditions' => '(ResourcesAssignStoretype.resources_id = SupportResources.id '.$subcondition.')',
            ],            
        ])->where([$condition])->group('ResourcesAssignStoretype.resources_id,ResourcesAssignCategory.category_id ')->order(['ResourcesAssignCategory.ranking'=>'ASC'])->toArray();

       $category = $this->supportcategoryTbl->find()->select([
        'name' => 'SupportCategories.name',
        'id' => 'SupportCategories.id',
        ])->join([
        'ResourcesAssignCategory' => [
            'table' => 'resources_assign_categories',
            'type' => 'INNER',
            'conditions' => 'ResourcesAssignCategory.category_id = SupportCategories.id',
        ],
        'SupportResources' => [
            'table' => 'support_resources',
            'type' => 'INNER',
            'conditions' => '(SupportResources.id = ResourcesAssignCategory.resources_id AND SupportResources.is_deleted=0 AND SupportResources.status=1'.$for_type.')',
        ],
        'ResourcesAssignStoretype' => [
            'table' => 'resources_assign_storetype',
            'type' => $joinType,
            'conditions' => '(ResourcesAssignStoretype.resources_id = SupportResources.id '.$subcondition.')',
        ],
      ])->where($categoryCondition)->group('SupportCategories.id')->order(['SupportCategories.ranking '=>'ASC'])->toArray();
      if($key == 'searchdata')
      {
        $response = [
            'faqlist' => $faqlist,
            'category' => $category
        ];
        echo json_encode($response);
        die;
      }else{
        $this->set(compact('loginUser','permission','faqlist','category'));
      }
    }

    public function openEmbedCode()
    {
        if ($this->request->is("get")) 
        {
            $id = $this->request->getQuery('id');

            $resource = $this->supportresourceTbl->find('all')
                       ->select(['embed_code'])
                       ->where(['is_deleted' => 0, 'id' => $id])
                       ->first();
            if(!empty($resource))
            {
                $data = $resource->embed_code;
            } 
            else
            {
               $data = 'No Data Found';   
            }
            echo $data;
            exit;
        }
    } 

    public function updateRank()
    {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $this->viewBuilder()->setLayout('ajax');

            $id = $this->request->getData('id');
            $val = $this->request->getData('val');
            $tablename = $this->request->getData('tablename');
           
            $update = $this->$tablename->query();
            $update->update()
            ->set(['ranking' => $val])
            ->where(['id' => $id]);
            if($update->execute()){
                echo 1;
            }else{
                echo 0; 
            }

            die;
        }
    }

}
