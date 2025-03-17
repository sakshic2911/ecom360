<?php

declare(strict_types=1);

namespace App\Controller;
use Cake\I18n\FrozenTime;
use Cake\Core\Configure;

class TicketsController extends AppController
{

    private $session;
    private $storeTbl;
    private $ticketDocTbl;
    private $labelTbl;
    private $userTbl;
    private $commentTbl;
    private $issues = [
        "", "General Support", "Billing", "Refer a friend", "Onboarding a new store", 
        "Portal Support", "Inventory management", "TeamViewer/device is offline", 
        "E-comm 360/E-comm Tax Service", "Multilogin", "Walmart Stores", "Case management", "Store Transfer",
        "Amazon Questions"
    ];
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('admin_dashboard');
        $this->session = $this->request->getSession();
        $this->menu = $this->getTableLocator()->get('Menus');
        $this->ticketDocTbl = $this->getTableLocator()->get('TicketDocs');
        $this->userTbl = $this->getTableLocator()->get('Users');
        // $this->storeTbl = $this->getTableLocator()->get('Store');
        $this->labelTbl = $this->getTableLocator()->get('Labels');
        $this->commentTbl = $this->getTableLocator()->get('CommentNotes');
        // $this->issuTbl = $this->getTableLocator()->get('UsersIssues');
        // $this->staffTbl = $this->getTableLocator()->get('StaffStore');
        // $this->ActivityLogs = $this->getTableLocator()->get('ActivityLogs');
        // $this->managenotiTbl = $this->getTableLocator()->get('ManageNotification');
        // $this->notificationTbl = $this->getTableLocator()->get('Notification');
        // $this->internalTicketTbl = $this->getTableLocator()->get('InternalTickets');  
        // $this->productTbl     = $this->getTableLocator()->get('Product');
        // $this->internalTicketProductsTbl = $this->getTableLocator()->get('InternalTicketProducts');
        // $this->staffManagerTbl = $this->getTableLocator()->get('StaffAccountManagers');
        $this->ticketActivitiesTbl = $this->getTableLocator()->get('TicketsActivities');
        // $this->chatTbl = $this->getTableLocator()->get('OnboardingChat');
        // $this->internalTicketWatchersTbl = $this->getTableLocator()->get('InternalTicketWatchers');
        $this->TicketWatchersTbl = $this->getTableLocator()->get('TicketWatchers');
        $this->TicketRatingTbl = $this->getTableLocator()->get('TicketRatings');
        // $this->staffNotificationTbl = $this->getTableLocator()->get('StaffNotificationSetting');

    }

    public function index()
    {
        
        $this->session->write('activeUrl', 'tickets');
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        $menu = $this->session->read('menu');
        
        $identity = $this->Authentication->getIdentity()->getOriginalData();
        if ($loginUser->user_type == 2) {
            $clientIdForTicket = $loginUser->id;
            return $this->redirect(['action' => 'clientTicket']);
        }
        $issue = [1,2,3,4,5,6,8,10,11,12,13];

        $condition = ['delete_tickets' => 0,'Tickets.issue_type IN'=>$issue,'Tickets.status !=' => 4];
        $clientCondition = ['Users.delete_user' => 0,'Users.user_type'=> 2];

        if($loginUser->user_type == 1)
        {
            $issue = [];
            $issueArray = $this->issuTbl->find()->select(['UsersIssues.issue_type'])->join([
                'Users' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'Users.id = UsersIssues.staff_id'
                ]
            ])->where(['UsersIssues.staff_id'=> $loginUser->id])->toArray();
                   
            foreach($issueArray as $s)
            {
                $issue[] = $s->issue_type;
            }
            if (!empty($issue)) {
                $condition['Tickets.issue_type IN'] = $issue;
            }
           // $condition['Tickets.issue_type IN'] = $issue;
           $staffStoreArray = [];
            if($loginUser->store_permission == 2)
            {
                $staffStore = $this->staffTbl->find()->join([
                    'groupList' => [
                        'table' =>'group_list',
                        'join' => 'inner',
                        'conditions' => '(StaffStore.group_id = groupList.id and groupList.delete_group=0)'
                    ],
                    'StoreGroup' => [
                        'table' =>'store_group',
                        'join' => 'inner',
                        'conditions' => '(groupList.id = StoreGroup.group_id)'
                    ] 
                ])->select(['store_id'=>'StoreGroup.store_id'])->where(['staff_id'=>$loginUser->id])->toArray();
                foreach($staffStore as $val)
                {
                    $staffStoreArray[] = $val->store_id;
                }
                if($loginUser->role_id == 15)
                {
                    $storeIds = array_column($this->storeTbl->find()->select(['id'])->where(['assign_manager' => $loginUser->id])->toArray(),'id');
                    $staffStoreArray = array_merge($staffStoreArray,$storeIds);
                }
                if($loginUser->role_id == 20)
                {
                    $userIds = array_column($this->userTbl->find()->select(['id'])->where(['parent_role' => $loginUser->id])->toArray(),'id');
                    if(count($userIds) == 0)
                $userIds[0] = 0;
                    $storeIds = array_column($this->storeTbl->find()->select(['id'])->where(['OR' => [
                        'assign_manager' => $loginUser->id,
                        'assign_manager IN' => $userIds
                        ]])->toArray(),'id');
                    $staffStoreArray = array_merge($staffStoreArray,$storeIds);
                }
                $condition['Stores.id IN'] = $staffStoreArray;
                $clientCondition['Store.id IN'] = $staffStoreArray;
            }

            if($loginUser->user_type == 1 &&  $loginUser->role_id == 16)
            {
                $parent = $this->userTbl->find()->select(['parent_role'])->where(['id' => $loginUser->id])->first();
                $temporary = array_column($this->staffManagerTbl->find()->select(['parent_role'])->where(['staff_id' => $loginUser->id])->toArray(),'parent_role');
                if(count($temporary) == 0)
                $temporary[0] = 0;
                $staffStore = $this->staffTbl->find()->join([
                    'groupList' => [
                        'table' =>'group_list',
                        'join' => 'inner',
                        'conditions' => '(StaffStore.group_id = groupList.id and groupList.delete_group=0)'
                    ],
                    'StoreGroup' => [
                        'table' =>'store_group',
                        'join' => 'inner',
                        'conditions' => '(groupList.id = StoreGroup.group_id)'
                    ] 
                ])->select(['store_id'=>'StoreGroup.store_id'])->where(['OR' => [
                    'staff_id'=>$parent->parent_role,
                    'staff_id IN' => $temporary
                    ]
                    ])->toArray();
                    $staffStoreArray = [];
                foreach($staffStore as $val)
                {
                    $staffStoreArray[] = $val->store_id;
                }
                
                $storeIds = array_column($this->storeTbl->find()->select(['id'])->where(['OR' => [
                    'assign_manager' => $parent->parent_role,
                    'assign_manager IN' => $temporary,
                ]])->toArray(),'id');
                $staffStoreArray = array_merge($staffStoreArray,$storeIds);
                $condition['Stores.id IN'] = $staffStoreArray;
                $clientCondition['Store.id IN'] = $staffStoreArray;
            }
        }
        
        
        $clientId = $mark =$assignto  = 0; $filterDate='';$accountManagerID = 0;$watcherId = 0; $ticketId = 0;
        // for filter
        if ($this->request->is('post')) {
            $mark =$this->request->getData('mark');
            $assignto =$this->request->getData('assign_to');
            $accountManagerID =$this->request->getData('account_manager');
            $watcherId =$this->request->getData('watcher_id');
            $ticketId =$this->request->getData('ticket_identity');

            if (empty($this->request->getData('date')) && empty($this->request->getData('client_id'))&& empty($this->request->getData('mark'))&& empty($this->request->getData('assign_to')) && empty($accountManagerID) && empty($watcherId) && empty($ticketId)) {
                $this->Flash->error('Please select Client name and/or Date and/or status and/or Assign To and/or Title to filter through the list.', ['key' => 'fieldErr']);
                return $this->redirect(['action' => 'index']);
            }

            $filterDate = $this->request->getData('date');
            if($this->request->getData('date'))
            $condition['Tickets.created_at >='] = date('Y-m-d', strtotime($this->request->getData('date')));

            $clientId = $this->request->getData('client_id');
            if($this->request->getData('client_id'))
            $condition['Tickets.client_id'] = $clientId;
            if($this->request->getData('ticket_identity'))
            $condition['Tickets.id'] = $ticketId;

            if($this->request->getData('mark'))
            {
                if($this->request->getData('mark') == 'important')
                  $condition['Tickets.mark_important'] = 1;
                else if($this->request->getData('mark') == 'flag')
                   $condition['Tickets.mark_flag'] = 1;
                elseif($this->request->getData('mark') == 'Unanswered'){
                    $staffIds = $this->userTbl->find()
                                    ->select(['id'])
                                    ->where(['user_type IN' => [0,1], 'delete_user' => 0])
                                    ->extract('id')
                                    ->toArray();
                    $ticketWithNoStaffResponse = $this->commentTbl->find()
                                                    ->select(['comment_notes.ticket_id'])
                                                    ->distinct()
                                                    ->from('comment_notes')
                                                    ->where([
                                                        'user_id NOT IN' => $staffIds,
                                                        'category' => 0
                                                    ])
                                                    ->extract('comment_notes.ticket_id')
                                                    ->toArray();

                    $ticketWithStaffResponse = $this->commentTbl->find()
                                                ->select(['comment_notes.ticket_id'])
                                                ->distinct()
                                                ->from('comment_notes')
                                                ->where([
                                                    'user_id IN' => $staffIds,
                                                    'category' => 0
                                                ])
                                                ->extract('comment_notes.ticket_id')
                                                ->toArray();
                    
                    $noStaffResponse = array_diff($ticketWithNoStaffResponse, $ticketWithStaffResponse);
                    if (!empty($noStaffResponse)) {
                    $condition['Tickets.id IN'] = $noStaffResponse;
                    }else {
                    $condition['Tickets.id IN'] = [0];
                    }
                }
                else if($this->request->getData('mark') == 'pending_response'){
                    $condition['Comments.seen'] = 0;
                }                  
               
            }

            if($this->request->getData('assign_to'))
            $condition['Tickets.support_staff'] = $this->request->getData('assign_to');

            if($accountManagerID == 'none_assigned'){
                $condition['UsersManager.id IS'] = null;
            }elseif($accountManagerID > 0){
                $condition['UsersManager.id'] = $accountManagerID;
            }

            if($watcherId)
            $condition['TicketWatchers.watcher_id'] = $watcherId;
            
        } 
            
        
        $ticketData = $this->Tickets->find()->select(['Tickets.id','Tickets.ticket_identity','Tickets.store_specific','Tickets.client_id','Tickets.store_id','title','description','issue_type','Tickets.status',
        'created_at' => 'DATE_SUB(Tickets.created_at, INTERVAL 4 HOUR)',
        'user'=>'CONCAT(Users.first_name," ",Users.last_name)','seen' => 'Comments.seen',
            'sender_id' => 'Comments.user_id','last_response'=>'MAX(CommentsNotes.created_at)',
            'staff' => 'CONCAT(Staff.first_name," ",Staff.last_name)','Tickets.updated_at','Tickets.mark_important','Tickets.mark_flag'])
            ->join([
                'Users' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'Users.id=Tickets.client_id'
                ],
                'Comments' => [
                    'table' => 'comment_notes',
                    'type' => 'LEFT',
                    'conditions' => '(Tickets.id = Comments.ticket_id AND Comments.seen=0 AND Comments.category = 0)'
                ],
                'CommentsNotes' => [
                    'table' => 'comment_notes',
                    'type' => 'LEFT',
                    'conditions' => '(Tickets.id = CommentsNotes.ticket_id AND Comments.category = 0)'
                ],
                'Staff' => [
                    'table' => 'users',
                    'type' => 'LEFT',
                    'conditions' => 'Tickets.support_staff = Staff.id'
                ],
                'TicketWatchers' => [
                    'table' => 'ticket_watchers',
                    'type' => 'LEFT',
                    'conditions' => 'TicketWatchers.ticket_id = Tickets.id',
                ],
            ])
                ->where($condition)->group('Tickets.id')->order(['Comments.seen'=> 'DESC','Tickets.id' => 'DESC'])
                ->limit(1)  ->toArray();

                // echo '<pre>'; print_r($ticketData); exit;
        // }
            $ticket1 = $ticket2 = $ticket3 = $ticket4 = $ticket5 = $ticket6 = $ticket8 = $ticket10 = $ticket11 = $ticket12 = $ticket13 = $closed = 0;
            if(count($ticketData)>0)
            {
                foreach($ticketData as $key => $t)
                {
                    if($t->issue_type==1 && ($t->status != 3 && $t->status != 4))
                    $ticket1++;
                    if($t->issue_type==2 && ($t->status != 3 && $t->status != 4))
                    $ticket2++;
                    if($t->issue_type==3 && ($t->status != 3 && $t->status != 4))
                    $ticket3++;
                    if($t->issue_type==4 && ($t->status != 3 && $t->status != 4))
                    $ticket4++;
                    if($t->issue_type==5 && ($t->status != 3 && $t->status != 4))
                    $ticket5++;
                    if($t->issue_type==6 && ($t->status != 3 && $t->status != 4))
                    $ticket6++; 
                    // if($t->issue_type==7 && ($t->status != 3 && $t->status != 4))
                    // $ticket7++; 
                    if($t->issue_type==8 && ($t->status != 3 && $t->status != 4))
                    $ticket8++; 
                    if($t->issue_type==10 && ($t->status != 3 && $t->status != 4))
                    $ticket10++;
                    if($t->issue_type==11 && ($t->status != 3 && $t->status != 4))
                    $ticket11++;
                    if($t->issue_type==12 && ($t->status != 3 && $t->status != 4))
                    $ticket12++;
                    if($t->issue_type==13 && ($t->status != 3 && $t->status != 4))
                    $ticket13++;
                    if($t->status==3)
                    $closed++; 
                
                    $t->watchers = $this->getTicketWatchers($t->id);

                    if ($mark == 'pending_response' && $t->seen === 0 && $t->client_id != $t->sender_id) {
                        unset($ticketData[$key]);
                    }

                }
            }    
            $client = [];
        // $client = $this->storeTbl->find()
        //         ->select([
        //             'id' => 'Users.id',
        //             'first_name' => 'Users.first_name',
        //             'last_name' => 'Users.last_name'
        //         ])
        //         ->join([
        //             'Users' => [
        //                 'table' => 'users',
        //                 'type' => 'RIGHT',
        //                 'conditions' => 'Users.id = Store.clients'
        //             ]
        //         ])->DISTINCT(['Users.id'])
        //         ->where($clientCondition)
        //         ->toArray();
$supportStaff =[];

        $this->set(compact('ticketData','ticket1','ticket2','ticket3','ticket4','ticket5','ticket6','ticket8','ticket10','ticket11','ticket12','ticket13','closed', 'identity', 'client','loginUser','issue','clientId','filterDate','mark','supportStaff','assignto','accountManagerID','watcherId','ticketId'));
    }

    public function clientTicket()
    {
        $this->session->write('activeUrl', 'tickets');
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        
        $issues = ["","General Support","Billing","Refer a friend","Onboarding a new store","Portal Support","Inventory management","TeamViewer/device is offline","E-comm 360/E-comm Tax Service","Multilogin","Walmart Stores","Case management","Store Transfer","Amazon Questions"];

        $condition = ['Tickets.client_id'=>$loginUser->id, 'delete_tickets' => 0, 'Tickets.status !=' => 4];

        $filterDate=date('Y-m-d');
        if ($this->request->is('post')) 
        {
            $condition = ['Tickets.client_id'=>$loginUser->id,'delete_tickets' => 0];           
        }
            
        $tickets = $this->Tickets->find('all', [
            'fields' => [
                'id' => 'Tickets.id',
                'issue_type' => 'Tickets.issue_type',
                'title' => 'Tickets.title',
                'status' => 'Tickets.status',
                'store_id' => 'Tickets.store_id',
                'add_date' => 'DATE_FORMAT(Tickets.created_at,"%m/%d/%Y")',
                'seen' => 'Comments.seen',
                'client_id' => 'Comments.user_id',
                'rating' => 'TicketRating.rating',
                'ticket_identity' => 'IF(Tickets.ticket_identity="","-",Tickets.ticket_identity)'
            ],
            'join' => [
                'Comments' => [
                    'table' => 'comment_notes',
                    'type' => 'LEFT',
                    'conditions' => '(Tickets.id = Comments.ticket_id AND Comments.seen=0 AND Comments.category = 0)'
                ],
                'TicketRating' => [
                'table' => 'ticket_ratings',
                'type' => 'LEFT',
                'conditions' => 'TicketRating.ticket_id = Tickets.id',
                ],
            ],

        ])->where($condition)->group('Tickets.id')->order(['Tickets.created_at'=>'DESC'])->toArray();
       
        // $clientStoreData =  $this->storeTbl->find()
        // ->select(['id', 'store_name'])
        // ->join([
        //     'StoreTypeAssign' => [
        //             'table' => 'store_type_assign',
        //             'type' => 'INNER',
        //             'conditions' => 'StoreTypeAssign.store_id = Store.id'
        //         ]])
        // ->where(['clients' => $loginUser->id, 'delete_store' => 0,'onboarding_status !='=> 'Terminated','StoreTypeAssign.store_name_id' => 1])
        // ->toArray();//,'Store.onboarding_status'=> 'Active'
        $clientStoreData = [];
        $this->set(compact('tickets','loginUser','issues','filterDate','clientStoreData'));
    }

    public function addTicket()
    {
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

        $ticket = $this->Tickets->newEmptyEntity();
        if ($this->request->is('post')) {

            $identity = $this->Authentication->getIdentity()->getOriginalData();
            if ($loginUser->user_type == 2)
                $clientId = $loginUser->id;
            else
                $clientId = $this->request->getData('client_id');
           
            if ($this->request->getData('title') == '') {

                $this->Flash->error('Title at least 3 character.', ['key' => 'fieldErr']);
                return $this->redirect(['action' => 'index']);
            } elseif ($this->request->getData('issue_type') ==  0 || empty($this->request->getData('issue_type'))) {

                $this->Flash->error('Please select the issue type.', ['key' => 'fieldErr']);
                return $this->redirect(['action' => 'index']);
            }
            $storeID = $this->request->getData('store_id');
            
            $issueType = $this->request->getData('issue_type');
            $title = $this->request->getData('title');
            $ticket->client_id = $clientId;
            $ticket->store_id = $storeID;
            $ticket->title = $title;
            $ticket->issue_type = $issueType;
            $ticket->description = $this->request->getData('description');
            $ticket->store_specific = 1;

            
            if ($res = $this->Tickets->save($ticket)) {

                //update ticket identifier in table
                $this->Tickets->query()->update()->set(['ticket_identity' => 'SUPPORT-'.$res->id])->where(['id' => $res->id])->execute();

                //save ticket watchers
                $watchers = $this->request->getData('watchers') ?? [];
                if (count($watchers) > 0) {
                    foreach ($watchers as $watcher) {
                        $watch = $this->TicketWatchersTbl->newEmptyEntity();
                        $watch->ticket_id = $res->id;
                        $watch->watcher_id = $watcher;
                        $this->TicketWatchersTbl->save($watch);
                    }
                }

                $clientData = $this->userTbl->get($clientId);
                $store_name = "";
                if($storeID > 0){
                    $stores = $this->storeTbl->find()->where(['id'=>$storeID])->first();
                    $store_name = $stores->store_name;
                }
                
                $emailData = [];
                $emailData['title'] = $title;                                           
                $emailData['storeName'] = $store_name;
                $emailData['category'] = $this->issues[$issueType];
                $emailData['identity'] = 'SUPPORT-'.$res->id;

                if ($loginUser->user_type != 2) { //it is not a client
                    if (count($watchers) > 0) {
                        foreach ($watchers as $watcherId) {
                            //check if watcher has permission to receive notification
                            $notificationAccess = $this->staffNotificationTbl->find()->select(['watcher_ticket'])->where(['user_id' => $watcherId])->first();
                            if($notificationAccess && $notificationAccess->watcher_ticket == 1) {
                                $watcher = $this->userTbl->get($watcherId);
                                //send notification to watcher
                                $emailData['name'] = $watcher['first_name'].' '.$watcher['last_name'];
                                $emailData['toEmail'] = $watcher['email']; 
                                $this->sendEmailViaEmailType(50, $emailData);
                            }
                        }
                    }
                    //send mail to client
                    $emailData['toEmail'] = $clientData->email;                
                    $emailData['clientName'] = $clientData->first_name.' '.$clientData->last_name;
                    $this->sendEmailViaEmailType(49, $emailData);
                }
                

                //save data into activity log
                $this->activityLog($loginUser,'Ticket','Create a ticket with title '.$this->request->getData('title'),$storeID,$res->id,"","","",$this->request->getData('issue_type'));
                $this->TicketActivities($loginUser->id,'Created',$res->id,'Create a ticket with title '.$this->request->getData('title'),'ticket');

                $files = $this->request->getData('file');
                if(!empty($files)){
                    for ($i = 0; $i < count($files); $i++) {
                        $ticketDoc = $this->ticketDocTbl->newEmptyEntity();
                        
                        // $targetPath = WWW_ROOT . 'img' . DS . 'tickets_file' . DS . $fileName;
                        if ($files[$i]->getClientFilename()) {
        
                            $originalFileName = $files[$i]->getClientFilename();
                            $fileName = chr(rand(97, 122)) . rand(10000, 99999) . pathinfo($originalFileName, PATHINFO_FILENAME);
                            $cleanedFileName = preg_replace('/[^A-Za-z0-9]/', '', $fileName);    
                            // Determine the file extension
                            $ext = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
                            $encodedFileName = $cleanedFileName.'.'.$ext;
    
                            // store image in aws s3
                            try {
                                $objects = $this->Amazon->s3->putObject([
                                    'Bucket'       => 'wte-partners',
                                    'Key'          => 'img/tickets_file/'.$encodedFileName,
                                    'SourceFile'   => $files[$i]->getStream()->getMetadata('uri')
                                ]);
    
                                $ext = strtolower(pathinfo($files[$i]->getClientFilename(), PATHINFO_EXTENSION));
                                if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'bmp' || $ext == 'eps') {
                                    $ticketDoc->doc_type = 2;
                                } else {
                                    $ticketDoc->doc_type = 1;
                                }
                                $ticketDoc->ticket_id = $ticket->id;
                                $ticketDoc->document = $encodedFileName;
                                $this->ticketDocTbl->save($ticketDoc);
                                
                            } catch (Aws\S3\Exception\S3Exception $e) {
                                print_r($e);
                            }
                            // filepath = 'https://wte-partners.s3.us-east-2.amazonaws.com/x73934rose.jpeg';
    
                            
                            // $files[$i]->moveTo($targetPath);
                        }
                    }
                }
                

                if ($loginUser->user_type == 2)
                    $this->Flash->success('Your ticket was submitted successfully.', ['key' => 'saveTickets']);
                else
                    $this->Flash->success('The ticket has been saved.', ['key' => 'saveTickets']);
                
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The ticket could not be saved. Please, try again.'));
        }
        $this->set(compact('ticket'));
    }

    public function editTicket($id = null)
    {
        $ticket = $this->Tickets->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ticket = $this->Tickets->patchEntity($ticket, $this->request->getData());
            if ($this->Tickets->save($ticket)) {
                $this->Flash->success(__('The ticket has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The ticket could not be saved. Please, try again.'));
        }
        $this->set(compact('ticket'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ticket = $this->Tickets->get($id);
        if ($this->Tickets->delete($ticket)) {
            $this->Flash->success(__('The ticket has been deleted.'));
        } else {
            $this->Flash->error(__('The ticket could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function kanbanModal()
    {
        if ($this->request->is('get')) {
            $this->viewBuilder()->setLayout('ajax');
            $identity = $this->Authentication->getIdentity()->getOriginalData();
            $loginUser = $this->session->read('user');
            if(!$loginUser)
            {
                return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
            }
            $loginMaster = $this->session->read('master');
            $menu = $this->session->read('menu');
            $permission = 3; $lgPermission = 3; $dptPermission = 3;
            foreach($menu as $m)
            {
                
                if($m->folder== 'Support')
                {
                    foreach($m->main['Support'] as $ml) {

                        if($ml->url=='tickets' && $loginUser->user_type==1)
                        { 
                            $permission = $ml->permission;
                            break;
                        }
                    }
                    
                }
                if($m->folder == 'Extra')
                {
                    foreach($m->main['Extra'] as $ml) {

                        if($ml->menu_name=='Change Ticket Department' && $ml->permission != 3)
                        { 
                            $lgPermission = 1;
                        }
                        if($ml->menu_name=='Ticket assign permission' && $ml->permission != 3)
                        {
                            $dptPermission = 1;
                        }
                    }
                }
            }

            
            $id = $this->request->getQuery('id'); // Ticket ID
            $storeId = $this->request->getQuery('storeId');

            // $id = 7;
            // $storeId = 53;

            $labelData = $this->labelTbl->find()
                ->where(['ticket_id' => $id])
                ->toArray();

            $ticketData = $this->Tickets->find()
                ->select([
                    'id' => 'Tickets.id',
                    'ticket_identity' => 'Tickets.ticket_identity',
                    'title' => 'Tickets.title',
                    'status' => 'Tickets.status',
                    'description' => 'Tickets.description',
                    'issue_type' => 'Tickets.issue_type',
                    'docType' => 'TicketDoc.doc_type',
                    'document' => 'TicketDoc.document',
                    'mark_important' => 'Tickets.mark_important',
                    'mark_flag' => 'Tickets.mark_flag',
                    'doc_id' => 'TicketDoc.id',
                    'name' => 'CONCAT(Users.first_name," ",Users.last_name)',
                    'client_id' => 'Users.id',
                    'support_staff' => 'Tickets.support_staff',
                    'store_name' => 'Stores.store_name',
                    'store_id' => 'Stores.id',
                    'store_status' => 'Stores.onboarding_status',
                    'store_specific' => 'Tickets.store_specific',
                    'created' => 'DATE_FORMAT(DATE_SUB(Tickets.created_at, INTERVAL 4 HOUR), "%m/%d/%Y %H:%i:%s")',
                    'account_manager' => 'CONCAT(UsersManager.first_name, " ", UsersManager.last_name)',
                    'onboarding_status' => 'Stores.onboarding_status',
                ])
                ->join([
                    'TicketDoc' => [
                        'table' => 'ticket_doc',
                        'type' => 'LEFT',
                        'conditions' => '(TicketDoc.ticket_id = Tickets.id AND TicketDoc.category = 0)'
                    ],
                    'Stores' => [
                        'table' => 'store',
                        'type' => 'LEFT',
                        'conditions' => 'Tickets.store_id = Stores.id'
                    ],
                    'Users' => [
                        'table' => 'users',
                        'type' => 'INNER',
                        'conditions' => 'Users.id = Tickets.client_id'
                    ],
                    'UsersManager' => [
                        'table' => 'users',
                        'type' => 'LEFT',
                        'conditions' => 'UsersManager.id = Stores.assign_manager',
                    ]
                ])
                ->where(['Tickets.id' => $id, 'Tickets.store_id' => $storeId, 'Tickets.delete_tickets' => 0])
                ->toArray();

            $commentData = $this->commentTbl->find()->select([
                'id' => 'CommentNote.id',
                'comment_notes' => 'CommentNote.comment_notes',
                'cmt_time' => 'DATE_FORMAT(DATE_SUB(CommentNote.created_at, INTERVAL 4 HOUR), "%m/%d/%Y %H:%i:%s")',
                'image' => 'Client.image',
                'first_name' => 'Client.first_name',
                'last_name' => 'Client.last_name',
            ])
            ->join([
                'Client' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'Client.id = CommentNote.user_id'
                ]
            ])->where(['ticket_id' => $id, 'CommentNote.category' => 0])->toArray();
            // $ticketDoc = $this->ticketDocTbl->find()->where(['ticket_id' => $id])->toArray();

            $supportStaff = $this->userTbl->find()->where(['user_type'=>1,'status'=>1,'delete_user'=>0])->toArray();
           // echo $ticketData->client_id;
            $storeid = $this->storeTbl->find()
            ->select(['id', 'store_name'])
            ->where(['delete_store' => 0,'clients'=>$ticketData[0]->client_id,'onboarding_status !=' =>'Terminated' ])
            ->toArray();

            // get all watchers of ticket
            $watchers = $this->TicketWatchersTbl->find()
            ->select([
                'watcher_id' => 'Users.id',
                'watcher_name' => 'CONCAT(Users.first_name," ",Users.last_name)',
            ])
            ->join([
                'Users' => [
                    'table' => 'users',
                    'type' => 'LEFT',
                    'conditions' => 'Users.id = TicketWatchers.watcher_id'
                ]
            ])
            ->where(['TicketWatchers.ticket_id' => $id])
            ->toArray();

            $this->set(compact('ticketData', 'labelData', 'identity', 'commentData','loginUser','permission','lgPermission','supportStaff','dptPermission','loginMaster','storeid','watchers'));
        }

       
    }


    public function getTicketDoc()
    {
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        $img = $this->request->getData('img');
        // Define the bucket and object key
        $bucket = 'wte-partners'; // Replace with your bucket name
        $key = 'img/tickets_file/'.$img; // Replace with the object key you want to generate URL for

        // Set the expiration time (in seconds)
        $expires = '+5 minutes';  // 60 minutes expiry time

        // Generate the pre-signed URL
        try {
            $cmd = $this->Amazon->s3->getCommand('GetObject', [
                'Bucket' => $bucket,
                'Key'    => $key
            ]);

            // Create a pre-signed URL with expiration
            $request = $this->Amazon->s3->createPresignedRequest($cmd, $expires);

            // Get the actual URL
            $presignedUrl = (string) $request->getUri();

            echo $presignedUrl;
            die();

        } catch (AwsException $e) {
            echo "Error: " . $e->getMessage() . PHP_EOL;
        }
    }

    public function markupdate()
    {  
        $loginUser = $this->session->read('user');  
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        if($this->request->is('post'))
        {
            $Tickets = $this->Tickets->get($this->request->getData('id'));
            if($this->request->getData('col') == 'mark_flag'){
                $Tickets->mark_flag = ($Tickets->mark_flag == 0) ? 1 : 0;
                $Status = $Tickets->mark_flag ? 'Flagged' : 'Not Flagged';
             }
            if($this->request->getData('col') == 'mark_important'){
                $Tickets->mark_important = ($Tickets->mark_important == 0) ? 1 : 0;
                $Status = $Tickets->mark_important ? 'Important' : 'Not Important';
            }
            
            if ($this->Tickets->save($Tickets)) {
                $ticketData = $this->Tickets->find()->select(['title'])->where(['id' => $this->request->getData('id')])->first();                
                $description = sprintf('Ticket marked as %s having title %s', $Status, $ticketData->title);
                $this->TicketActivities($loginUser->id,'Updated',$this->request->getData('id'),$description,'ticket');                 
                return $this->redirect(['action' => 'index']);
            }
            return $this->redirect(['action' => 'index']);

        }
    }

    // add and get label 

    public function addLabelData()
    {
        if ($this->request->is('get')) {
            $ticketId = $this->request->getQuery('ticket_id');
            $label = $this->request->getQuery('label');

            // echo $label . ' ' . $ticketId;
            $labels = $this->labelTbl->newEmptyEntity();
            $labels->label_name = $label;
            $labels->ticket_id = $ticketId;
            if ($this->labelTbl->save($labels)) {
                // echo '<pre>';
                // print_r($labelTbl);
                echo json_encode($labels);
                die;
            }
        }
    }

    public function deleteLabel()
    {
        if ($this->request->is('get')) {
            $id = $this->request->getQuery('labelId');
            $deleteLabel = $this->labelTbl->Query()->delete()
                ->where(['id' => $id]);

            if ($deleteLabel->execute()) {
                echo 1;
                die;
            }
        }
    }

    public function addMoreFile()
    { 
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

        if ($this->request->is('post')) {
            // echo '<pre>';
            // print_r($this->request->getData());
            // die;

            $files = $this->request->getData('files');
            $ticket_id = $this->request->getData('ticketID');
            $category = $this->request->getData('category');
            for ($i = 0; $i < count($files); $i++) {
                $ticketDoc = $this->ticketDocTbl->newEmptyEntity();                

                // $targetPath = WWW_ROOT . 'img' . DS . 'tickets_file' . DS . $fileName;
                if ($files[$i]->getClientFilename()) {

                $originalFileName = $files[$i]->getClientFilename();
                $fileName = chr(rand(97, 122)) . rand(10000, 99999) . pathinfo($originalFileName, PATHINFO_FILENAME);
                $cleanedFileName = preg_replace('/[^A-Za-z0-9]/', '', $fileName);    
                // Determine the file extension
                $ext = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
                // dd($cleanedFileName.'.'.$ext);   
                $encodedFileName = $cleanedFileName.'.'.$ext;
                    
                    // store image in aws s3
                    try {
                        $objects = $this->Amazon->s3->putObject([
                            'Bucket'       => 'wte-partners',
                            'Key'          => 'img/tickets_file/'.$encodedFileName,
                            'SourceFile'   => $files[$i]->getStream()->getMetadata('uri')
                        ]);
                        $ext = strtolower(pathinfo($files[$i]->getClientFilename(), PATHINFO_EXTENSION));
                        if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'bmp' || $ext == 'eps') {
                            $ticketDoc->doc_type = 2;
                        } else {
                            $ticketDoc->doc_type = 1;
                        }
                        $ticketDoc->ticket_id = $ticket_id;
                        $ticketDoc->document = $encodedFileName;
                        $ticketDoc->category = $category;
                        $this->ticketDocTbl->save($ticketDoc);
                        // $files[$i]->moveTo($targetPath);
                    } catch (Aws\S3\Exception\S3Exception $e) {
                        print_r($e);
                    }
                }
            }
            $pics =['file'=> $encodedFileName];
            $this->Flash->success('Files has been saved.', ['key' => 'saveTickets']);
            echo json_encode($pics);
            die;
        }
    }

    public function addAtcFile()
    { 
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

        if ($this->request->is('post')) {
            // echo '<pre>';
            // print_r($this->request->getData());
            // die;
            $category = $this->request->getData('category');
            $status = $this->commentTbl->query()->update()
            ->set(['seen' => 1])->where(['ticket_id' => $this->request->getData('ticketID'),'user_id !='=>$loginUser->id,'category' => $category]);
            $status->execute();

            $files = $this->request->getData('files');
            $ticket_id = $this->request->getData('ticketID');
            for ($i = 0; $i < count($files); $i++) {
                $ticketDoc = $this->ticketDocTbl->newEmptyEntity();                
                
                // $targetPath = WWW_ROOT . 'img' . DS . 'tickets_file' . DS . $fileName;
                if ($files[$i]->getClientFilename()) {

                    $originalFileName = $files[$i]->getClientFilename();
                    $fileName = chr(rand(97, 122)) . rand(10000, 99999) . pathinfo($originalFileName, PATHINFO_FILENAME);
                    $cleanedFileName = preg_replace('/[^A-Za-z0-9]/', '', $fileName);    
                    // Determine the file extension
                    $ext = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
                    $encodedFileName = $cleanedFileName.'.'.$ext;
                    
                    try {
                        $objects = $this->Amazon->s3->putObject([
                            'Bucket'       => 'wte-partners',
                            'Key'          => 'img/tickets_file/'.$encodedFileName,
                            'SourceFile'   => $files[$i]->getStream()->getMetadata('uri')
                        ]);

                        $ext = strtolower(pathinfo($files[$i]->getClientFilename(), PATHINFO_EXTENSION));
                        if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'bmp' || $ext == 'eps') {
                            $ticketDoc->doc_type = 2;
                        } else {
                            $ticketDoc->doc_type = 1;
                        }
                        $ticketDoc->ticket_id = $ticket_id;
                        $ticketDoc->document = $encodedFileName;
                        $ticketDoc->category = $category;
                        $this->ticketDocTbl->save($ticketDoc);
                        // $files[$i]->moveTo($targetPath);

                    } catch (Aws\S3\Exception\S3Exception $e) {
                                            print_r($e);
                                        }

                }
            }

            $this->Flash->success('Files has been saved.', ['key' => 'saveTickets']);
            if($category == 0)
            return $this->redirect(['action' => 'index']);
            else
            {
                if ($loginUser->user_type == 0)
                return $this->redirect(['action' => 'internalTickets']);
                if ($loginUser->user_type == 1)
                return $this->redirect(['action' => 'staffTicket']);
            } 
            
        }
    }

    public function removeDoc($doc = null)
    {
       
        $deleteDoc = $this->ticketDocTbl->query()->delete()->where(['document' => $doc]);
        if ($deleteDoc->execute()) {
            // $path = WWW_ROOT . 'img' . DS . 'tickets_file' . DS . $doc;
            // unlink($path);
            $this->Flash->success('Files has been deleted.', ['key' => 'saveTickets']);
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error('Files has not been deleted.', ['key' => 'fieldErr']);
        return $this->redirect(['action' => 'index']);
    }


    public function addShowComment()
    {
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

        if ($this->request->is('post')) {
            // echo '<pre>';
            // print_r($this->request->getData());
            // die;
            $category = $this->request->getData('category');
            if($loginUser->user_type != 2 && $category == 0)
            {
                $ticket = $this->commentTbl->find()->where(['ticket_id' => $this->request->getData('ticketId'),'user_id !='=>$loginUser->id])->toArray();

                if(count($ticket)==0)
                {
                    $status = $this->Tickets->query()->update()
                    ->set(['support_staff' => $this->request->getData('userId')])->where(['id' => $this->request->getData('ticketId')]);

                    $status->execute();
                }
            }

            $status = $this->commentTbl->query()->update()
            ->set(['seen' => 1])->where(['ticket_id' => $this->request->getData('ticketId'),'user_id !='=>$loginUser->id ,'category' => $category]);
            $status->execute();

            $comment = $this->commentTbl->newEmptyEntity();
            $commentMsg = preg_replace('/\s+/', ' ', $this->request->getData('commentMessage'));
            
            $comment->ticket_id = $this->request->getData('ticketId');
            $comment->user_id = $this->request->getData('userId');
            $comment->comment_notes = $commentMsg;
            $comment->type = 1;
            $comment->category = $category;

            if ($this->commentTbl->save($comment)) {

                if($category == 0)
                {
                    $ticketData = $this->Tickets->find()->select(['Tickets.title','name'=>'Concat(Users.first_name," ",Users.last_name)','email'=>'Users.email','client_id'=>'Tickets.client_id','support'=>'Support.email','firebaseid'=>'Users.firebaseid','devicename'=>'Users.devicename','support_id' => 'Support.id','store_status'=>'Stores.onboarding_status'])->join([
                        'Users' => [
                            'table' => 'users',
                            'type' => 'INNER',
                            'conditions' => 'Users.id = Tickets.client_id'
                        ],
                        'Support' => [
                            'table' => 'users',
                            'type' => 'LEFT',
                            'conditions' => 'Tickets.support_staff = Support.id'
                        ],
                        'Stores' => [
                            'table' => 'store',
                            'type' => 'LEFT',
                            'conditions' => 'Tickets.store_id = Stores.id'
                        ]
                    ])->where(['Tickets.id'=>$this->request->getData('ticketId')])->first();
                       
                    $name = $ticketData->name;
                    $email = $ticketData->email;
                    $title = $ticketData->title;
                    $support = $ticketData->support;
                    $storeStatus = $ticketData->store_status;
                    $description = 'New comment added on ticket having title "'.$title.'"';
                    $this->TicketActivities($loginUser->id,'Commented',$this->request->getData('ticketId'),$description,'ticket');

                    $ticketID = $this->request->getData('ticketId');
                    //get ticket watcher data
                    $watchersDetails = $this->getTicketWatchers($ticketID);
                    $watcherEmails = [];
                    if(!empty($watchersDetails)){
                        foreach($watchersDetails as $watcher){
                            //check permission for watcher to receive notification
                            $notificationAccess = $this->staffNotificationTbl->find()->select(['ticket_comment'])->where(['user_id' => $watcher->watcher_id])->first();
                            if($notificationAccess && $notificationAccess->ticket_comment == 1){
                                $watcherEmails[$watcher->email] = $watcher->email;
                            }
                        }
                    }                    
                    $heading = "Response Received on Ticket"; 
                    $description = 'Click here to view the new comments on ticket "'.$title.'"'; 
                    $url = "tickets";
                    if ($storeStatus !== 'terminated') {
                        if($this->request->getData('userId')==$ticketData->client_id)
                        {
                            $url = "tickets/client-ticket";
                            //check if staff has given permission to receive this notification
                            if($ticketData->support_id){
                                $commentNotificationAccess = $this->staffNotificationTbl->find()->select(['ticket_comment'])->where(['user_id' => $ticketData->support_id])->first();
                                if($commentNotificationAccess && $commentNotificationAccess->ticket_comment == 0){
                                    $support = '';
                                }
                            }
                        $this->sendResponsetoAdminEmail($name,$email,$title,$support,$watcherEmails);
        
                        }else  {
    
                            /* Start Code Added by Sakshi Chandrakar*/                         
                            $firebaseid =  $ticketData->firebaseid;
                            $devicename = $ticketData->devicename;    
                            if(!empty($firebaseid) && !empty($devicename))
                            {          
                                $ticket_app = $this->managenotiTbl->find()->where(['user_id' => $ticketData->client_id])
                                ->first();   
                                $ticket_app_status = $ticket_app->ticket_app;
                                if($ticket_app_status ==1){
                                    $sendnotification = $this->sendNotification($firebaseid,$description,$heading,$devicename);
                                }
                            }                        
                            /* End Code Added by Sakshi Chandrakar*/                     

    
                            $this->sendResponsetoClientEmail($name,$email,$title);
                        }
                    }
                    $notificationTbl = $this->notificationTbl->newEmptyEntity();
                    $notificationTbl->user_id = $ticketData->client_id;
                    $notificationTbl->title =  $heading;
                    $notificationTbl->message =  $description;
                    $notificationTbl->sender_id =  $this->request->getData('userId');
                    $notificationTbl->url =  $url;
                    $this->notificationTbl->save($notificationTbl);
                }else if($category == 1 && $loginUser->user_type != 2){
                    $ticketId = $this->request->getData('ticketId');
                    $ticketData = $this->internalTicketTbl->find()->select([
                        'InternalTickets.title',
                        'InternalTickets.ticket_identity',
                        'name'=>'Concat(Users.first_name," ",Users.last_name)',
                        'email'=>'Users.email',
                        'staff_id'=>'InternalTickets.staff_id',
                        'created_by_id' => 'InternalTickets.created_by',
                        'creator_email'=>'Creator.email',
                        'creator_name'=>'Concat(Creator.first_name," ",Creator.last_name)',
    
                        ])->join([
                        'Users' => [
                            'table' => 'users',
                            'type' => 'INNER',
                            'conditions' => 'Users.id = InternalTickets.staff_id'
                        ],
                        'Creator' => [
                            'table' => 'users',
                            'type' => 'LEFT',
                            'conditions' => 'Creator.id = InternalTickets.created_by'
                        ]

                    ])->where(['InternalTickets.id'=>$ticketId])->first();

                    $description = sprintf('%s commented on internal ticket with title %s', $loginUser->first_name.' '.$loginUser->last_name, $ticketData->title);
                    $this->TicketActivities($loginUser->id, 'Commented', $ticketId, $description, 'internalticket');

                    $name = $ticketData->name;
                    $email = $ticketData->email;
                    $title = $ticketData->title;
                    $ticketIdentity = $ticketData->ticket_identity;
                    $currentUserId = $loginUser->id;
                    $isCreatedByMessage = ($currentUserId === $ticketData->created_by_id);
                    $subject = 'New Response Received on a Internal Ticket On Your Ecom 360 Portal';
                    $template = 'internalticket';
                    $title = $ticketData->title;

                    //check notification permission
                    $cmInNotificationAccess = $this->staffNotificationTbl->find()->select(['internal_ticket_comment'])->where(['user_id' => $ticketData->staff_id])->first();

                    if ($isCreatedByMessage) {                      
                        // When created_by sends a message ,mail to staff
                        $name = $ticketData->name;
                        $email = $ticketData->email;
                        $sender_name = $ticketData->creator_name;
                        if ($cmInNotificationAccess && $cmInNotificationAccess->internal_ticket_comment == 1) {
                            $this->sendStaffTicketResponseToEmail($name, $email, $title,$sender_name,$subject,$template,$ticketIdentity);   
                        }                        
                    } else if($ticketData->staff_id == $currentUserId) {                     
                        // When staff sends a message , mail to creator
                        $name = $ticketData->creator_name;
                        $email = $ticketData->creator_email;
                        $sender_name = $ticketData->name;
                        $this->sendStaffTicketResponseToEmail($name, $email, $title,$sender_name,$subject,$template,$ticketIdentity);   
                    }else{
                        //when other user sends a message
                        $name = $ticketData->name;
                        $email = $ticketData->email;
                        $sender_name = $loginUser->first_name.' '.$loginUser->last_name;
                        $this->sendStaffTicketResponseToEmail($name, $email, $title,$sender_name,$subject,$template,$ticketIdentity);
                    }

                }

                echo json_encode($comment);
                die;
            }
        }
    }

    public function deleteTicket($id = null)
    {
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

       if($this->request->is("GET")) 
       {
           $id = $this->request->getQuery('id');

           $ticketData = $this->Tickets->find()->where(['id' => $id])->first();

           $deleteTicket = $this->Tickets->query()->update()->set(['delete_tickets' => 1])->where(['id' => $id]);
           if ($deleteTicket->execute()) {

                //save data into activity log
                $this->activityLog($loginUser,'Ticket','Delete Ticket',$ticketData->store_id,$id);
                $this->TicketActivities($loginUser->id,'Deleted',$id,'Delete Ticket','ticket');
                   echo 1;
                   die;
                   
               }
       }
    }

    public function ticketStatus()
    {
        $loginUser = $this->session->read('user');
        
        if ($this->request->is('get')) {

            $issues = ["","General Support","Billing","Refer a friend","Onboarding a new store","Portal Support","Inventory management","TeamViewer/device is offline","E-comm 360/E-comm Tax Service","Multilogin","Walmart Stores","Case management","Store Transfer","Amazon Questions"];

            $ticketId = $this->request->getQuery('ticketId');
            $type = $this->request->getQuery('type');
            $statusValue = $this->request->getQuery('status');
            $category = $this->request->getQuery('category');

            if($category == 0)
            $table = $this->Tickets;
            else
            $table = $this->internalTicketTbl;

            $ticketData = $table->find()->where(['id' => $ticketId])->first();

            $comnt = $this->commentTbl->query()->update()
            ->set(['seen' => 1])->where(['ticket_id' => $ticketId,'user_id !='=>$loginUser->id,'category' => $category]);
            $comnt->execute();
            $closed_at = date('Y-m-d H:i:s');
            
            if($statusValue==20)
            {
                $closed_at = date('Y-m-d H:i:s');
                //this comes from Ticket/index when somone drag and drop on closed column
                $status = $this->Tickets->query()->update()
                ->set(['status' => 3,'closed_at'=>$closed_at,'closed_by'=>$loginUser->id])->where(['id' => $ticketId]);
            }
            else{
                if($statusValue ==3 && $type == 'status' && $category == 0){
                    $status = $this->Tickets->query()->update()
                    ->set([$type => $statusValue,'closed_at'=>$closed_at,'closed_by'=>$loginUser->id])->where(['id' => $ticketId]);
                }else{
                    $status = $table->query()->update()
                    ->set([$type => $statusValue])->where(['id' => $ticketId]);
                    if ($type=='support_staff') {
                        //add assigned_at date
                        $this->Tickets->query()->update()
                        ->set(['assigned_at'=>date('Y-m-d H:i:s')])->where(['id' => $ticketId])->execute();
                    }
                }
               
            }
            

            if ($status->execute()) {

                if($category == 0)
                {
                    $issue = 0;$st = ["","In-progress","","Closed"];
                    if($type=='support_staff')
                    $text = 'Assign support staff';
                    if($type=='status')
                    $text = 'Update ticket status to '.$st[$statusValue];
                    if($type=='issue_type')
                    { 
                        $text = 'Change ticket category';
                        if ($statusValue==20) {
                            $text = 'Ticket is closed.';
                        }
                        $issue =$statusValue; 
                    }
    
                    //save data into activity log
                    $this->activityLog($loginUser,'Ticket',$text,$ticketData->store_id,$ticketId,"","","",$issue);
                    $this->TicketActivities($loginUser->id,'Updated',$ticketId,$text,'ticket');
                    
                    $ticketDatas = $this->Tickets->find()->select(['Tickets.title','name'=>'Users.first_name','email'=>'Users.email','tstatus'=>'CASE WHEN Tickets.status=1 THEN "In-Progress"
                    WHEN Tickets.status=3 THEN "Closed" END','client_id'=>'Tickets.client_id','firebaseid'=>'Users.firebaseid','devicename'=>'Users.devicename'])->join([
                        'Users' => [
                            'table' => 'users',
                            'type' => 'INNER',
                            'conditions' => 'Users.id = Tickets.client_id'
                        ]
                    ])->where(['Tickets.id'=>$ticketId])->first();
                       
                    $name = $ticketDatas->name;
                    $email = $ticketDatas->email;
                    $title = $ticketDatas->title;
                    $tstatus = $ticketDatas->tstatus;
                    if($statusValue==20 || ($type=='status' && $statusValue==3))
                    {
                         /* Start Code Added by Sakshi Chandrakar*/
                         $heading = "Ticket Closed"; 
                         $description = 'Your ticket "'.$title.'" was closed successfully';  
                         $firebaseid =  $ticketDatas->firebaseid;
                         $devicename = $ticketDatas->devicename;
                         $user_id = $ticketDatas->client_id;
                         $url = "tickets/client-ticket"; 
                         if(!empty($firebaseid) && !empty($devicename))
                         {  
                            $ticket_app = $this->managenotiTbl->find()
                            ->where(['user_id' => $ticketDatas->client_id])
                            ->first();   
                             $ticket_app_status = $ticket_app->ticket_app;
                             if($ticket_app_status ==1){
                                $sendnotification = $this->sendNotification($firebaseid,$description,$heading,$devicename);
                              }
                            }
                            $notificationTbl = $this->notificationTbl->newEmptyEntity();
                            $notificationTbl->user_id = $ticketDatas->client_id;
                            $notificationTbl->title =  $heading;
                            $notificationTbl->message =  $description;
                            $notificationTbl->sender_id =  $loginUser->id;
                            $notificationTbl->url =  $url;
                            $this->notificationTbl->save($notificationTbl);
                         /* End Code Added by Sakshi Chandrakar*/
                    }
                    if($type=='status'){
                        $emailData = [];
                        $emailData['toEmail'] = $email;
                        $emailData['name'] = $name;
                        $emailData['title'] = $title;                        
                        $emailData['status'] = $tstatus;                    
                        $emailData['userId'] = $ticketDatas->client_id;
                        $emailData['storeId'] = $ticketData->store_id;
                        $emailData['category'] = $issues[$ticketData->issue_type];
                        $this->sendEmailViaEmailType(15, $emailData);
    
                        // $this->updateTicketEmail($name,$email,$title,$tstatus);
                    }
                    
                    if($type=='support_staff' && $loginUser->id != $statusValue)
                    {
                        $category = ["","General Support","Billing","Refer a friend","Onboarding a new store","Portal Support","Inventory management","TeamViewer/device is offline","E-comm 360/E-comm Tax Service","Multilogin","Walmart Stores","Case management","Store Transfer","Amazon Questions"];
    
                        $staffData = $this->userTbl->get($statusValue);
    
                        $clientData = $this->userTbl->get($ticketData->client_id);
                        
                        $emailData = [];
                        $emailData['toEmail'] = $staffData->email;
                        $emailData['clientName'] = $clientData->first_name.' '.$clientData->last_name;
                        $emailData['title'] = $title;                        
                        $emailData['category'] = $category[$ticketData->issue_type];                    
                        $emailData['userId'] = $staffData->id;
                        $emailData['storeId'] = $ticketData->store_id;

                        //client ticket status notification email
                        $statusNotificationAccess = $this->staffNotificationTbl->find()->select(['assign_ticket'])->where(['user_id' => $staffData->id])->first();
                        if ($statusNotificationAccess && $statusNotificationAccess->assign_ticket == 1){
                            $this->sendEmailViaEmailType(19, $emailData);
                        }
    
                        // $this->assignStaffTicketEmail($clientData->first_name,$clientData->last_name,$staffData->email,$title,$category[$ticketData->issue_type]);
                    }        
                }
                else{
                    $ticketData = $this->internalTicketTbl->find()->select([
                        'InternalTickets.title',
                        'InternalTickets.ticket_identity',
                        'name'=>'Concat(Users.first_name," ",Users.last_name)',
                        'email'=>'Users.email',
                        'staff_id'=>'InternalTickets.staff_id',
                        'created_by_id' => 'InternalTickets.created_by',
                        'creator_email'=>'Creator.email',
                        'creator_name'=>'Concat(Creator.first_name," ",Creator.last_name)',
    
                        ])->join([
                        'Users' => [
                            'table' => 'users',
                            'type' => 'INNER',
                            'conditions' => 'Users.id = InternalTickets.staff_id'
                        ],
                        'Creator' => [
                            'table' => 'users',
                            'type' => 'LEFT',
                            'conditions' => 'Creator.id = InternalTickets.created_by'
                        ]

                    ])->where(['InternalTickets.id'=>$ticketId])->first();

                    $st = ["","In-progress","Closed","Archived"];
                    if($type=='support_staff')
                      $text = 'Assign support staff';

                    $text = 'Update ticket status to '.$st[$statusValue];
                    $this->activityLog($loginUser,'Internal Ticket',$text,0,$ticketId);

                    $description = sprintf('%s updated internal ticket status to '.$st[$statusValue],$loginUser->first_name.' '.$loginUser->last_name);
                    $this->TicketActivities($loginUser->id,'Updated',$ticketId,$description,'internalticket');

                    $name = $ticketData->name;
                    $email = $ticketData->email;
                    $title = $ticketData->title;
                    $ticketIdentity = $ticketData->ticket_identity;
                    $currentUserId = $loginUser->id;
                    $isCreatedByMessage = $currentUserId === $ticketData->created_by_id;
                    $subject = 'Updated status on a Internal Ticket On Your Ecom 360 Portal';
                    $template = 'internalticketstatus';
                    $title = $ticketData->title;
                    if ($isCreatedByMessage) {                      
                        $name = $ticketData->name;
                        $email = $ticketData->email;
                        $sender_name = $ticketData->creator_name;
                        $statusNotificationAccess = $this->staffNotificationTbl->find()->select(['internal_ticket_status'])->where(['user_id' => $ticketData->staff_id])->first();
                        if ($statusNotificationAccess && $statusNotificationAccess->internal_ticket_status == 1){
                            $this->sendStaffTicketResponseToEmail($name, $email, $title,$sender_name,$subject,$template,$ticketIdentity);   
                        }                        
                    } else if($ticketData->staff_id == $currentUserId) {                     
                        $name = $ticketData->creator_name;
                        $email = $ticketData->creator_email;
                        $sender_name = $ticketData->name;
                        $statusNotificationAccess = $this->staffNotificationTbl->find()->select(['internal_ticket_status','user_id'])->where(['user_id' => $ticketData->created_by_id])->first();
                        if ($statusNotificationAccess && $statusNotificationAccess->internal_ticket_status == 1){
                            $this->sendStaffTicketResponseToEmail($name, $email, $title,$sender_name,$subject,$template,$ticketIdentity);
                        }   
                    }else{
                        $name = $ticketData->name;
                        $email = $ticketData->email;
                        $sender_name = $loginUser->first_name.' '.$loginUser->last_name;

                        $statusNotificationAccess = $this->staffNotificationTbl->find()->select(['internal_ticket_status'])->where(['user_id' => $ticketData->staff_id])->first();
                        if ($statusNotificationAccess && $statusNotificationAccess->internal_ticket_status == 1){                         
                            $this->sendStaffTicketResponseToEmail($name, $email, $title,$sender_name,$subject,$template,$ticketIdentity);
                        }
                    }
                }

                echo 1;
                die;
            }
        }
    }

    public function reassignIntenalStaff()
    {
        $loginUser = $this->session->read('user');
        
        if ($this->request->is('get')) {

            $ticketId = $this->request->getQuery('ticketId');
            $staff_id = $this->request->getQuery('staff_id');
            
            $this->internalTicketTbl->query()->update()
            ->set(['staff_id' => $staff_id])->where(['id' => $ticketId])->execute();
            

            echo 1;
            die;
        }
    }
    //cron function 
    public function archiveList()
    {
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

        $issues = ["","General Support","Billing","Refer a friend","Onboarding a new store","Portal Support","Inventory management","TeamViewer/device is offline","E-comm 360/E-comm Tax Service","Multilogin","Walmart Stores","Case management","Store Transfer","Amazon Questions"];

        $condition = ['Tickets.status'=>4];
        $clientCondition = ['Users.delete_user' => 0,'Users.user_type'=> 2];
        $staffStoreArray = [];
        if($loginUser->user_type == 1 && $loginUser->store_permission == 2 && $loginUser->role_id != 16)
        {  
            $staffStore = $this->staffTbl->find()->join([
                'groupList' => [
                    'table' =>'group_list',
                    'join' => 'inner',
                    'conditions' => '(StaffStore.group_id = groupList.id and groupList.delete_group=0)'
                ],
                'StoreGroup' => [
                    'table' =>'store_group',
                    'join' => 'inner',
                    'conditions' => '(groupList.id = StoreGroup.group_id)'
                ] 
            ])->select(['store_id'=>'StoreGroup.store_id'])->where(['staff_id'=>$loginUser->id])->toArray();
               foreach($staffStore as $val)
               {
                   $staffStoreArray[] = $val->store_id;
               }
               if($loginUser->role_id == 15)
                {
                    $storeIds = array_column($this->storeTbl->find()->select(['id'])->where(['assign_manager' => $loginUser->id])->toArray(),'id');
                    $staffStoreArray = array_merge($staffStoreArray,$storeIds);
                }
               $condition['Stores.id IN'] = $staffStoreArray;
        }
        if($loginUser->user_type == 1 && $loginUser->role_id == 16)
        {
            $parent = $this->userTbl->find()->select(['parent_role'])->where(['id' => $loginUser->id])->first();
            $temporary = array_column($this->staffManagerTbl->find()->select(['parent_role'])->where(['staff_id' => $loginUser->id])->toArray(),'parent_role');
            if(count($temporary) == 0)
            $temporary[0] = 0;
            $staffStore = $this->staffTbl->find()->join([
                'groupList' => [
                    'table' =>'group_list',
                    'join' => 'inner',
                    'conditions' => '(StaffStore.group_id = groupList.id and groupList.delete_group=0)'
                ],
                'StoreGroup' => [
                    'table' =>'store_group',
                    'join' => 'inner',
                    'conditions' => '(groupList.id = StoreGroup.group_id)'
                ] 
            ])->select(['store_id'=>'StoreGroup.store_id'])->where(['OR' => [
                'staff_id'=>$parent->parent_role,
                'staff_id IN' => $temporary
                ]
                ])->toArray();
                $staffStoreArray = [];
            foreach($staffStore as $val)
            {
                $staffStoreArray[] = $val->store_id;
            }
            
            $storeIds = array_column($this->storeTbl->find()->select(['id'])->where(['OR' => [
                'assign_manager' => $parent->parent_role,
                'assign_manager IN' => $temporary,
            ]])->toArray(),'id');
            $staffStoreArray = array_merge($staffStoreArray,$storeIds);
            $condition['Stores.id IN'] = $staffStoreArray;
        }

        //Add by kajal
        if ($loginUser->user_type == 2) {
            $clientIdForTicket = $loginUser->id;
            return $this->redirect(['action' => 'clientArchiveList']);
        }
        //End

        $client = $this->userTbl->find()
        ->select([
            'id' => 'Users.id',
            'first_name' => 'Users.first_name',
            'last_name' => 'Users.last_name'
        ])
        ->where($clientCondition)
        ->toArray();

        // $stores = $this->storeTbl->find()
        //     ->select(['id', 'store_name'])
        //     ->where(['delete_store' => 0])
        //     ->toArray();
        $stores = [];

        $accountManagers = $this->userTbl->find()
        ->select(['id', 'first_name', 'last_name'])->group('Users.id')
        ->where(['role_id IN' => [15,20], 'delete_user' => 0,'Users.status' => 1])
        ->toArray();

        $supportStaff = $this->userTbl->find()->where(['user_type'=>1,'status'=>1,'delete_user'=>0])->toArray();
        
        $clientId = 0; $storeId = 0; $assignto = 0; $departmentId = 0; $accountManagerID = 0; $watcherId = 0;
        $start_date = '';
        $end_date = '';

        if ($this->request->is('post')) {
            $clientId = $this->request->getData('client_id'); 
            $storeId = $this->request->getData('store_id'); 
            $start_date = $this->request->getData('start_date'); 
            $end_date = $this->request->getData('end_date');
            $assignto  =$this->request->getData('assign_to');
            $departmentId  =$this->request->getData('department_id');
            $accountManagerID = $this->request->getData('account_manager');
            $watcherId = $this->request->getData('watcher_id');

            if(!empty($clientId)){
                $condition['Tickets.client_id'] = $clientId;
            }

            if(!empty($storeId)){
                $condition['Tickets.store_id'] = $storeId;
            }

            if(!empty($start_date)){
                $condition['Tickets.created_at >='] = $start_date;
            }

            if(!empty($end_date)){
                $condition['Tickets.closed_at <='] = $end_date;
            }

            if(!empty($assignto)){
                $condition['Tickets.support_staff'] = $assignto;
            }

            if(!empty($departmentId)){
                $condition['Tickets.issue_type'] = $departmentId;
            }

            if($accountManagerID == 'none_assigned'){
                $condition['UsersManager.id IS'] = null;
            }
            elseif($accountManagerID > 0){
                $condition['UsersManager.id'] = $accountManagerID;
            }

            if(!empty($watcherId)){
                $condition['TicketWatchers.watcher_id'] = $watcherId;
            }
            
        }

        $tickets = $this->Tickets->find()->select(['Tickets.id','Tickets.title','Tickets.store_id','client'=>'Concat(Users.first_name," ",Users.last_name)','Tickets.issue_type','created_date' => 'DATE_FORMAT(Tickets.created_at, "%m/%d/%Y")','closed_date' => 'DATE_FORMAT(Tickets.closed_at, "%m/%d/%Y")'])->join([
            'Users' =>[
                'table' => 'users',
                'type' => 'INNER',
                'conditions' => 'Users.id = Tickets.client_id'
            ],
            // 'UsersManager' => [
            //     'table' => 'users',
            //     'type' => 'LEFT',
            //     'conditions' => 'UsersManager.id = Stores.assign_manager',
            // ],
            'TicketWatchers' => [
                'table' => 'ticket_watchers',
                'type' => 'LEFT',
                'conditions' => 'TicketWatchers.ticket_id = Tickets.id',
            ]
        ])->where($condition)->order(['Tickets.closed_at'=>'DESC'])->toArray();

        $this->set(compact('loginUser','tickets','issues','client','stores','accountManagers','supportStaff','clientId','storeId','start_date','end_date','assignto','departmentId','accountManagerID','watcherId'));
        
    }

    public function archiveDetail()
    {
        if ($this->request->is('get')) {
            $this->viewBuilder()->setLayout('ajax');
            
            $loginUser = $this->session->read('user');
            if(!$loginUser)
            {
                return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
            }
            $loginMaster = $this->session->read('master');
            $menu = $this->session->read('menu');
            // $permission = 3; $lgPermission = 3; $dptPermission = 3;
            // foreach($menu as $m)
            // {
            //     if($m->url=='tickets' && $loginUser->user_type==1)
            //     { 
            //         $permission = $m->permission;
            //     }
            //     if($m->menu_name=='Change Ticket Department' && $m->permission != 3)
            //     {
            //         $lgPermission = 1;
            //     }

            //     if($m->menu_name=='Ticket assign permission' && $m->permission != 3)
            //     {
            //         $dptPermission = 1;
            //     }
            // }

            
            $id = $this->request->getQuery('id'); 

            $labelData = $this->labelTbl->find()
                ->where(['ticket_id' => $id])
                ->toArray();

            $ticketData = $this->Tickets->find()
                ->select([
                    'id' => 'Tickets.id',
                    'title' => 'Tickets.title',
                    'status' => 'Tickets.status',
                    'description' => 'Tickets.description',
                    'issue_type' => 'Tickets.issue_type',
                    'docType' => 'TicketDoc.doc_type',
                    'document' => 'TicketDoc.document',
                    'doc_id' => 'TicketDoc.id',
                    'name' => 'CONCAT(Users.first_name," ",Users.last_name)',
                    'support_staff' => 'CONCAT(support.first_name," ",support.last_name)',
                    'ticket_identity' => 'Tickets.ticket_identity',
                ])
                ->join([
                    'TicketDoc' => [
                        'table' => 'ticket_docs',
                        'type' => 'LEFT',
                        'conditions' => '(TicketDoc.ticket_id = Tickets.id AND TicketDoc.category = 0)'
                    ],
                    'Users' => [
                        'table' => 'users',
                        'type' => 'INNER',
                        'conditions' => 'Users.id = Tickets.client_id'
                    ],
                    'support' => [
                        'table' => 'users',
                        'type' => 'LEFT',
                        'conditions' => 'Tickets.support_staff = Users.id'
                    ]
                ])
                ->where(['Tickets.id' => $id, 'Tickets.delete_tickets' => 0])
                ->toArray();

            $commentData = $this->commentTbl->find()->select([
                'id' => 'CommentNotes.id',
                'comment_notes' => 'CommentNotes.comment_notes',
                'cmt_time' => 'DATE_FORMAT(DATE_SUB(CommentNotes.created_at, INTERVAL 4 HOUR),"%m/%d/%Y %H:%i:%s")',
                'image' => 'Client.image',
                'first_name' => 'Client.first_name',
                'last_name' => 'Client.last_name',
            ])
            ->join([
                'Client' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'Client.id = CommentNotes.user_id'
                ]
            ])->where(['ticket_id' => $id,'CommentNotes.category' => 0])->toArray();

            $this->set(compact('ticketData', 'labelData', 'commentData','loginUser','loginMaster'));
        }
    }

    //By kajal
    public function clientArchiveList()
    {
        $this->session->write('activeUrl', 'tickets');
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        
        $issues = ["","General Support","Billing","Refer a friend","Onboarding a new store","Portal Support","Inventory management","TeamViewer/device is offline","E-comm 360/E-comm Tax Service","Multilogin","Walmart Stores","Case management","Store Transfer","Amazon Questions"];
        $condition = ['Tickets.client_id'=>$loginUser->id, 'Tickets.delete_tickets' => 0, 'Tickets.status'=> 4];
            
            $tickets = $this->Tickets->find('all', [
                'fields' => [
                    'id' => 'Tickets.id',
                    'issue_type' => 'Tickets.issue_type',
                    'title' => 'Tickets.title',
                    'status' => 'Tickets.status',
                    'store_id' => 'Tickets.store_id',
                    'add_date' => 'DATE_FORMAT(Tickets.created_at,"%m/%d/%Y")',
                    'seen' => 'Comments.seen',
                    'client_id' => 'Comments.user_id',
                ],
                'join' => [
                    'Comments' => [
                        'table' => 'comment_notes',
                        'type' => 'LEFT',
                        'conditions' => '(Tickets.id = Comments.ticket_id AND Comments.seen=0 AND Comments.category=0)'
                    ]
                ],  
            ])->where($condition)->group('Tickets.id')->order(['Tickets.created_at'=>'DESC'])->toArray();

        $this->set(compact('tickets','loginUser','issues'));
    }

    public function archiveTicket()
    {
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

        if ($this->request->is('get')) {
            $ticketId = $this->request->getQuery('id');
            

            $ticketData = $this->Tickets->find()->where(['id' => $ticketId])->first();

            $comnt = $this->Tickets->query()->update()->set(['status' => 4])->where(['id' => $ticketId,'client_id' => $loginUser->id]);
            

            if ($comnt->execute()) {

                //save data into activity log
                $this->activityLog($loginUser,'Ticket','Client Archive his ticket',$ticketData->store_id,$ticketId);
                $this->TicketActivities($loginUser->id,'Updated',$ticketId,'Client Archive his ticket','ticket');

                echo 1;
                die;
            }
        }
    }

    public function internalTickets()
    {
        
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        $menu = $this->session->read('menu');
        $permission = 3; $lgPermission = 3;
        if ($loginUser->user_type == 0) {
            $permission = 2;
        }
        // echo '<pre>'; print_r($menu); exit;
        foreach($menu as $m)
        {
            if( $m->folder== 'Support')
            {
                foreach($m->main['Support'] as $ml) {

                    if($ml->url=='internal-tickets' && $loginUser->user_type==1)
                    { 
                        $permission = $ml->permission;
                        break;
                    }
                }
            }
            if($m->folder == 'Extra')
            {
                foreach($m->main['Extra'] as $ml) {

                    if($ml->menu_name=='View All Internal Tickets' && $loginUser->user_type==1)
                    { 
                        $lgPermission = 1;
                    }
                }
            }
        }
        
        if ($loginUser->user_type == 2) {
            
            return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
        }

        $condition = ['delete_tickets' => 0];
        
        $productData =  [];

        $staffId = 0; $filterDate=''; $clientId=0;$productId=0;
        $storeId = 0; $openedBy = 0;$watcherId = 0;$ticketId = 0;
        // for filter
        if ($this->request->is('post')) {
            $filterDate = $this->request->getData('date');
            $staffId = $this->request->getData('staff_id');
            $clientId = $this->request->getData('client_id');
            $productId = $this->request->getData('product_id');
            $storeId = $this->request->getData('store_id');
            $openedBy = $this->request->getData('opened_by');
            $watcherId = $this->request->getData('watcher_id');
            $ticketId =$this->request->getData('ticket_identity');

            if (empty($filterDate) && empty($staffId) && empty($clientId) && empty($productId) && empty($storeId) && empty($openedBy) && empty($watcherId) && empty($ticketId)) {
                $this->Flash->error('Please select staff name and/or Date to filter through the list.', ['key' => 'fieldErr']);
                return $this->redirect(['action' => 'internalTickets']);
            }

            if($filterDate)
            $condition['InternalTickets.created_at >='] = date('Y-m-d', strtotime($filterDate));

            if($staffId)
            $condition['InternalTickets.staff_id'] = $staffId;

            if($clientId)
            $condition['InternalTickets.client_id'] = $clientId;

            if($productId)
            {
                $condition['InternalTicketProducts.product_id'] = $productId;

                $productData = $this->productTbl->find()
                ->select([
                    'id'            => 'Product.id',
                    'name'          => 'Product.name',
                    'sku'           => 'Product.sku'
                ])
                ->order(['id' => 'DESC'])
                ->where(['Product.is_delete' => 0,'id' => $productId])
                ->toArray();
            }
            

            if($storeId)
            $condition['InternalTickets.store_id'] = $storeId;

            if($openedBy)
            $condition['InternalTickets.created_by'] = $openedBy;

            if($watcherId)
            $condition['InternalTicketWatchers.watcher_id'] = $watcherId;
            
            if($ticketId)
            $condition['InternalTickets.id'] = $ticketId;
            
        } 
        // if internal staff show those tickets in which login staff is added as watcher
        if($loginUser->user_type == 1 && $lgPermission == 3){
            $ticketIds = $this->internalTicketWatchersTbl->find()
                            ->select(['internal_tickets_id'])
                            ->where(['watcher_id' => $loginUser->id])
                            ->all()
                            ->extract('internal_tickets_id')
                            ->toArray();               
            //add also those tickets which is assigned to him or created by him
            $tiketsAssingedAndCreated = $this->internalTicketTbl->find()
                            ->select(['id'])
                            ->where(['OR'=>['staff_id'=>$loginUser->id,'created_by' => $loginUser->id]])
                            ->all()
                            ->extract('id')
                            ->toArray();
            // dd($tiketsAssingedAndCreated);                            
            //merge both arrays
            $ticketIds = array_merge($ticketIds, $tiketsAssingedAndCreated);
            if (empty($ticketIds)) {
                $ticketIds = [0];
            }
            $condition['InternalTickets.id IN'] = $ticketIds;             
        }            
        
        $ticketData = $this->internalTicketTbl->find()->select(['InternalTickets.id','InternalTickets.staff_id','title','description','InternalTickets.status','InternalTickets.created_at','user'=>'CONCAT(Users.first_name," ",Users.last_name)','seen' => 'Comments.seen',
            'InternalTickets.department_id',
            'InternalTickets.department_type',
            'InternalTickets.product_type',
            'createdBy' => 'CONCAT(CreatedBy.first_name," ",CreatedBy.last_name)',
            'created_role' => 'Role.role_name',
            'Client_name' => 'CONCAT(Clients.first_name," ",Clients.last_name)',
            'store_name' => 'Store.store_name',
            'last_response'=>'MAX(CommentsNotes.created_at)','InternalTickets.updated_at',
            'sender_id' => 'Comments.user_id',
            'ticket_identity' => 'InternalTickets.ticket_identity',
            ])->join([
                'Users' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'Users.id=InternalTickets.staff_id'
                ],
                'Comments' => [
                    'table' => 'comment_notes',
                    'type' => 'LEFT',
                    'conditions' => '(InternalTickets.id = Comments.ticket_id AND Comments.seen=0 AND Comments.category=1)'
                ],
                'CommentsNotes' => [
                    'table' => 'comment_notes',
                    'type' => 'LEFT',
                    'conditions' => '(InternalTickets.id = CommentsNotes.ticket_id AND CommentsNotes.category=1)'
                ],
                'Clients' => [
                    'table' => 'users',
                    'type' => 'LEFT',
                    'conditions' => 'Clients.id = InternalTickets.client_id'
                ],
                'CreatedBy' => [
                    'table' => 'users',
                    'type' => 'LEFT',
                    'conditions' => 'CreatedBy.id = InternalTickets.created_by',
                ],
                'Store' => [
                    'table' => 'store',
                    'type' => 'LEFT',
                    'conditions' => '(Store.id = InternalTickets.store_id AND InternalTickets.store_type != 0)',
                ],
                'Role' => [
                    'table' => 'roles',
                    'type' => 'LEFT',
                    'conditions' => 'Role.id = CreatedBy.role_id',
                ],
                'InternalTicketProducts' => [
                    'table' => 'internal_ticket_products',
                    'type' => 'LEFT',
                    'conditions' => 'InternalTicketProducts.internal_tickets_id = InternalTickets.id'
                ],
                'InternalTicketWatchers' => [
                    'table' => 'internal_ticket_watchers',
                    'type' => 'LEFT',
                    'conditions' => 'InternalTicketWatchers.internal_tickets_id = InternalTickets.id',
                ],
            ])
                ->where($condition)->group('InternalTickets.id')->order(['Comments.seen'=> 'DESC','InternalTickets.id' => 'DESC'])
                ->toArray();

                // echo '<pre>'; print_r($ticketData); exit;
        // }
        
            $pending = $inprogress = $closed = 0;
            if(count($ticketData)>0)
            {
                foreach($ticketData as $t)
                {
                    if($t->status==0)
                    $pending++;
                    if($t->status==1)
                    $inprogress++;  
                    if($t->status==2)
                    $closed++;
                
                    if ($t->department_type != 0) {
                        $t->department_name = $this->getDepartmentName($t->department_id);
                    }
                    if($t->product_type != 0){
                        $t->products = $this->getInternalTicketProducts($t->id);
                    }
                    $t->watchers = $this->getInternalTicketWatchers($t->id);

                }
            }    
            
            // echo '<pre>'; print_r($ticketData); exit;
        $staffData = $this->userTbl->find()->where(['user_type'=>1,'status'=>1,'delete_user'=>0])->toArray();

        $clientData = $this->userTbl->find()->where(['user_type'=>2,'status'=>1,'delete_user'=>0])->toArray();

        $storeData =  $this->storeTbl->find()
        ->select(['id', 'store_name'])
        ->where(['delete_store' => 0,'onboarding_status !='=> 'Terminated'])
        ->toArray();
        
        $productData =  $this->productTbl->find()
                    ->select([
                        'id'            => 'Product.id',
                        'name'          => 'Product.name',
                        'sku'           => 'Product.sku'
                    ])
                    ->order(['id' => 'DESC'])
                    ->where(['Product.is_delete' => 0])
                    ->toArray();

        $mlist = $this->menu->find()->select(['id'])->where(['url' => 'internal-tickets'])->first();
        
        $watcherData = $this->userTbl->find()
        ->select(['id', 'first_name', 'last_name'])
        ->join([
            'UserPermissions' => [
                'table' => 'user_permissions',
                'type' => 'INNER',
                'conditions' => 'UserPermissions.user_id = Users.id'
            ]
        ])
        ->where(['Users.user_type' => 1, 'Users.delete_user' => 0,'UserPermissions.permission !=' => 3, 'UserPermissions.menu_id' => $mlist['id']])
        ->toArray();
        // die('bbb');

        $this->set(compact('ticketData','pending','inprogress','closed','loginUser','permission','staffId','filterDate','staffData','clientData','storeData','productData','clientId','productId','storeId','openedBy','watcherData','watcherId','ticketId'));
    }

    public function getDepartmentName($type){
        $departmentMap = [
            1 => 'General Support',
            2 => 'Billing',
            3 => 'Refer a friend',
            4 => 'Onboarding a new store',
            5 => 'Portal Support',
            6 => 'Inventory management',
            8 => 'E-comm 360/E-comm Tax Service',
            // 9 => 'Multilogin' ,
            10 => 'Walmart Stores',
            11 => 'Case management',
            12 =>  'Store Transfer',
            13 => 'Amazon Questions',  
        ];

        return $departmentMap[$type] ?? '';
    }

    public function getInternalTicketProducts($id){        
        $productData =  $this->internalTicketProductsTbl->find()
                    ->select([
                        'name' => 'Product.name',
                    ])
                    ->join([
                        'Product' => [
                            'table' => 'product',
                            'type' => 'LEFT',
                            'conditions' => 'Product.id = InternalTicketProducts.product_id'
                        ]
                    ])
                    ->where(['InternalTicketProducts.internal_tickets_id' => $id])
                    ->toArray();
        return $productData;
    }

    public function getInternalTicketWatchers($id){
        $watchers =  $this->internalTicketWatchersTbl->find()
                    ->select([
                        'watcher_id' => 'Users.id',
                        'watcher_name' => 'CONCAT(Users.first_name," ",Users.last_name)',
                    ])
                    ->join([
                        'Users' => [
                            'table' => 'users',
                            'type' => 'LEFT',
                            'conditions' => 'Users.id = InternalTicketWatchers.watcher_id'
                        ]
                    ])
                    ->where(['InternalTicketWatchers.internal_tickets_id' => $id])
                    ->toArray();
        return $watchers;
    }

    public function staffKanbanModal()
    {
        if ($this->request->is('get')) {
            $this->viewBuilder()->setLayout('ajax');
            $loginUser = $this->session->read('user');
            if(!$loginUser)
            {
                return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
            }
            if($loginUser->user_type == 2)
            {
                return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
            }
            $loginMaster = $this->session->read('master');
            $menu = $this->session->read('menu');
            $permission = 3; 
            foreach($menu as $m)
            {
                
                if($m->folder== 'Support')
                {
                    foreach($m->main['Support'] as $ml) {

                        if($ml->url=='internal-tickets' && $loginUser->user_type==1)
                        { 
                            $permission = $ml->permission;
                            break;
                        }
                    }
                    
                }
                
            }

            
            $id = $this->request->getQuery('id'); // Ticket ID
        
            $update_seen = $this->internalTicketTbl->query()->update()
            ->set(['seen' => 1])
            ->where(['id' => $id]);
            $update_seen->execute();          
            $ticketData = $this->internalTicketTbl->find()
                ->select([
                    'id' => 'InternalTickets.id',
                    'title' => 'InternalTickets.title',
                    'status' => 'InternalTickets.status',
                    'description' => 'InternalTickets.description',
                    'department_type' => 'InternalTickets.department_type',
                    'department_id' => 'InternalTickets.department_id',
                    'product_type' => 'InternalTickets.product_type',
                    'created_by' => 'InternalTickets.created_by',
                    'docType' => 'TicketDoc.doc_type',
                    'document' => 'TicketDoc.document',
                    'doc_id' => 'TicketDoc.id',
                    'name' => 'CONCAT(Users.first_name," ",Users.last_name)',
                    'staff_id' => 'Users.id',
                    'created' => 'DATE_FORMAT(DATE_SUB(InternalTickets.created_at, INTERVAL 4 HOUR), "%m/%d/%Y %H:%i:%s")',
                    'Client_name' => 'CONCAT(Clients.first_name," ",Clients.last_name)',
                    'store_name' => 'Store.store_name',
                    'createdBy' => 'CONCAT(CreatedBy.first_name," ",CreatedBy.last_name)',
                    'ticket_identity' => 'InternalTickets.ticket_identity',
                ])
                ->join([
                    'TicketDoc' => [
                        'table' => 'ticket_doc',
                        'type' => 'LEFT',
                        'conditions' => '(TicketDoc.ticket_id = InternalTickets.id AND TicketDoc.category = 1)'
                    ],
                    'Users' => [
                        'table' => 'users',
                        'type' => 'INNER',
                        'conditions' => 'Users.id = InternalTickets.staff_id'
                    ],
                    'Clients' => [
                        'table' => 'users',
                        'type' => 'LEFT',
                        'conditions' => 'Clients.id = InternalTickets.client_id'
                    ],
                    'CreatedBy' => [
                        'table' => 'users',
                        'type' => 'LEFT',
                        'conditions' => 'CreatedBy.id = InternalTickets.created_by',
                    ],
                    'Store' => [
                        'table' =>'store',
                        'type' => 'LEFT',
                        'conditions' => '(Store.id = InternalTickets.store_id AND InternalTickets.store_type != 0)'
                    ],
                ])
                ->where(['InternalTickets.id' => $id, 'InternalTickets.delete_tickets' => 0])
                ->toArray();
                // echo "<pre>";print_r($loginUser->id);exit();

                if ($ticketData[0]->status == 2 && ($loginUser->id == $ticketData[0]->staff_id || $loginUser->id == $ticketData[0]->created_by)) {

                    $comment = $this->commentTbl->find()
                                    ->where([
                                        'ticket_id' => $id,
                                        'user_id !=' => $loginUser->id,
                                        'category' => 1,
                                        'seen' => 0
                                    ])
                                    ->order(['created_at' => 'DESC'])
                                    ->first();
                        if ($comment) {
                            $this->commentTbl->query()->update()
                                ->set(['seen' => 1])
                                ->where([
                                    'ticket_id' => $id,
                                    'user_id !=' => $loginUser->id,
                                    'category' => 1,
                                    'seen' => 0
                                ])
                                ->execute();
                        }
                }

            $commentData = $this->commentTbl->find()->select([
                'id' => 'CommentNote.id',
                'comment_notes' => 'CommentNote.comment_notes',
                'cmt_time' => 'DATE_FORMAT(DATE_SUB(CommentNote.created_at, INTERVAL 4 HOUR),"%m/%d/%Y %H:%i:%s")',
                'image' => 'Client.image',
                'first_name' => 'Client.first_name',
                'last_name' => 'Client.last_name',
            ])
            ->join([
                'Client' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'Client.id = CommentNote.user_id'
                ]
            ])->where(['ticket_id' => $id, 'CommentNote.category' => 1])->toArray();
            // $ticketDoc = $this->ticketDocTbl->find()->where(['ticket_id' => $id])->toArray();

            $staff = $this->userTbl->find()->where(['user_type'=>1,'status'=>1,'delete_user'=>0])->toArray();            
           // echo $ticketData->client_id;

           if(count($ticketData)>0)
            {
                foreach($ticketData as $t)
                {                
                    if ($t->department_type != 0) {
                        $t->department_name = $this->getDepartmentName($t->department_id);
                    }
                    if ($t->product_type != 0) {
                        $t->products = $this->getInternalTicketProducts($t->id);
                    }
                    //all watchers of this ticket
                    $t->watchers = $this->getInternalTicketWatchers($t->id);
                }
            }
            //add ticket activity information
            $description = sprintf('%s viewed internal ticket with title %s', $loginUser->first_name.' '.$loginUser->last_name, $ticketData[0]->title);
            $this->TicketActivities($loginUser->id, 'Viewed', $id, $description, 'internalticket');

        //    echo "<pre>";print_r($ticketData);exit;
            $this->set(compact('ticketData', 'commentData','loginUser','permission','staff','loginMaster'));
        }

       
    }

    public function staffTicket()
    {
        
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        
        if ($loginUser->user_type == 2) {
            
            return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
        }

        $condition = ['InternalTickets.staff_id'=>$loginUser->id, 'delete_tickets' => 0, 'InternalTickets.status !=' => 3];
                   
            $tickets = $this->internalTicketTbl->find('all', [
                'fields' => [
                    'id' => 'InternalTickets.id',
                    'title' => 'InternalTickets.title',
                    'status' => 'InternalTickets.status',
                    'add_date' => 'DATE_FORMAT(InternalTickets.created_at,"%m/%d/%Y")',
                    'seen' => 'Comments.seen',
                    'ticket_seen' => 'InternalTickets.seen',
                    'staff_id' => 'Comments.user_id',
                    'store_name' => 'Store.store_name',
                    'client_name' => 'concat(Users.first_name," ", Users.last_name)'
                ],
                'join' => [
                    'Comments' => [
                        'table' => 'comment_notes',
                        'type' => 'LEFT',
                        'conditions' => '(InternalTickets.id = Comments.ticket_id AND Comments.seen=0 AND Comments.category = 1)'
                    ],
                    'Users' => [
                        'table' => 'users',
                        'type' => 'LEFT',
                        'conditions' => '(Users.id = InternalTickets.client_id AND Users.user_type=2 AND Users.delete_user = 0)'
                    ],
                    'Store' => [
                        'table' => 'store',
                        'type' => 'LEFT',
                        'conditions' => '(InternalTickets.store_id = Store.id AND Store.delete_store=0)'
                    ]
                ],
    
            ])->where($condition)->group('InternalTickets.id')->order(['InternalTickets.created_at'=>'DESC'])->toArray();

            $staffSeenCount = $this->internalTicketTbl->find()
            ->select(['ticket_seen' => 'COUNT(IF(InternalTickets.seen = 0, 1, NULL))', 'comment_seen' => 'COUNT(IF(Comments.seen = 0 AND Comments.category = 1, 1, NULL))'])
            ->join([
                'Comments' => [
                    'table' => 'comment_notes',
                    'type' => 'LEFT',
                    'conditions' => [
                        'InternalTickets.id = Comments.ticket_id',
                        'Comments.category' => 1,
                        'Comments.user_id !=' => $loginUser->id,
                    ]
                ]
            ])
            ->where([
                'InternalTickets.staff_id' => $loginUser->id,
                'InternalTickets.delete_tickets' => 0,
                'InternalTickets.status !=' => 3,
            ])
            ->first();
        
            $ticket_seen = $staffSeenCount['ticket_seen'];
           $comment_seen = $staffSeenCount['comment_seen'];

        
        $this->set(compact('tickets','loginUser','ticket_seen','comment_seen'));
    }
    public function openInternalTicket()
    {
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        if ($this->request->is('get')) {
            $this->viewBuilder()->setLayout('ajax');
            $store_id = $this->request->getQuery('store_id'); 
            $client_id = $this->request->getQuery('client_id'); 
            $department_id = $this->request->getQuery('department_id'); 
            $support_staff = $this->request->getQuery('support_staff');

            if ($loginUser->user_type == 1)
               $satffCondition = ['user_type'=>1,'status'=>1,'delete_user'=>0,'id !='=>$loginUser->id];
            else
               $satffCondition = ['user_type'=>1,'status'=>1,'delete_user'=>0];

            $staffData = $this->userTbl->find()->where($satffCondition)->toArray();
            if($client_id >0){
                    $storeData =  $this->storeTbl->find()
                    ->select(['id', 'store_name'])
                    ->where(['delete_store' => 0,'onboarding_status !='=> 'Terminated','clients'=>$client_id])
                    ->toArray();
            }else{
                $storeData =  $this->storeTbl->find()
                ->select(['id', 'store_name'])
                ->where(['delete_store' => 0,'onboarding_status !='=> 'Terminated'])
                ->toArray();      
            }
            $clientData = $this->userTbl->find()->where(['user_type'=>2,'status'=>1,'delete_user'=>0])->toArray();


            $productData = [];

            $assign_manager = $this->storeTbl->find()
            ->select(['id', 'assign_manager'])
            ->where(['delete_store' => 0, 'onboarding_status !=' => 'Terminated', 'id' => $store_id])
            ->first();
            
            $assign_manager_id = 0; // Default value
            
            if (!empty($assign_manager)) {
                $assign_manager_id = $assign_manager->assign_manager;
            }
            
            if ($support_staff != "") {
                $assign_manager_id = $support_staff;
            }
            //all users who have permission to view internal ticket are watchers
            $mlist = $this->menu->find()->select(['id'])->where(['url' => 'internal-tickets'])->first();
            
            $watcherData = $this->userTbl->find()
            ->select(['id', 'first_name', 'last_name'])
            ->join([
                'UserPermissions' => [
                    'table' => 'user_permissions',
                    'type' => 'INNER',
                    'conditions' => 'UserPermissions.user_id = Users.id'
                ]
            ])
            ->where(['Users.user_type' => 1, 'Users.delete_user' => 0,'UserPermissions.permission !=' => 3, 'UserPermissions.menu_id' => $mlist['id']])
            ->toArray();

            $this->set(compact('loginUser','staffData','clientData','storeData','productData','client_id','store_id','assign_manager_id','department_id','watcherData'));
        } else if ($this->request->is(['post', 'put', 'patch'])) {
                if ($loginUser->user_type == 2) {
                    return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
                }

                $ticket = $this->internalTicketTbl->newEmptyEntity();


                $type = $this->request->getData('type');

                $staffId = $this->request->getData('staff_id');
                $title = $this->request->getData('title');
                $description = $this->request->getData('description');
                $client_id = $this->request->getData('client_id');
                $department_type = $this->request->getData('department_type');
                $product_type = $this->request->getData('product_type');
                $store_type =  $this->request->getData('store_type');
                $department_id =  $this->request->getData('department_id');
                $product_id =  $this->request->getData('product_id');
                $store_id =  $this->request->getData('store_id');
                $watcher_ids = $this->request->getData('watcher_id') ?? [];
    
                if($department_type ==1){
                    $ticket->department_id = $department_id;
                }
                if($store_type ==1){
                    $ticket->store_id = $store_id;
                }
                $ticket->staff_id = $staffId;
                $ticket->client_id = $client_id;
                $ticket->department_type = $department_type;
                $ticket->product_type = $product_type;
                $ticket->store_type = $store_type;
                $ticket->title = $title;
                $ticket->description = $description;
                $ticket->created_by = $loginUser->id;
                
                if ($res = $this->internalTicketTbl->save($ticket)) {

                    $ticketIdentity = 'INTERNAL-'.$res->id;
                    //update ticket identifier in table
                    $this->internalTicketTbl->query()->update()->set(['ticket_identity' => $ticketIdentity])->where(['id' => $res->id])->execute();

                    $emailData = [];
                    $emailData['title'] = $title;                                           
                    $emailData['identity'] = $ticketIdentity;
                    
                    if($product_type ==1){
                        if(count($product_id)){
                            foreach($product_id	 as $product){
                                $ticketProduct = $this->internalTicketProductsTbl->newEmptyEntity();
                                $ticketProduct->internal_tickets_id	 = $ticket->id;
                                $ticketProduct->product_id = $product;
                                $this->internalTicketProductsTbl->save($ticketProduct);
                            }
                        }    
                    }
                    //add watchers
                    if(count($watcher_ids) > 0) {
                        foreach($watcher_ids as $watcher){
                            $ticketWatcher = $this->internalTicketWatchersTbl->newEmptyEntity();
                            $ticketWatcher->internal_tickets_id     = $ticket->id;
                            $ticketWatcher->watcher_id = $watcher;
                            $this->internalTicketWatchersTbl->save($ticketWatcher);
                        }
                        //send mail to all watchers
                        $watchers = $this->userTbl->find('all', [
                            'conditions' => ['id IN' => $watcher_ids]
                        ])->toArray();
                        foreach ($watchers as $watcher) {
                            $watcherNotificationAccess = $this->staffNotificationTbl->find()->select(['internal_ticket_watcher'])->where(['user_id' => $watcher->id])->first();
                            if ($watcherNotificationAccess && $watcherNotificationAccess->internal_ticket_watcher == 1) {
                                $watcherName = $watcher->first_name.' '.$watcher->last_name;
                                $emailData['toEmail'] = $watcher->email;                
                                $emailData['name'] = $watcherName;
                                $emailData['userName'] = $loginUser->first_name.' '.$loginUser->last_name;
                                $this->sendEmailViaEmailType(53, $emailData);
                                // $this->sendInternalTicketEmailToWatchers($watcher->email, $watcherName, $title,$loginUser->first_name.' '.$loginUser->last_name);
                            }
                        }
                    }
                    
                    //save data into activity log
                    $this->activityLog($loginUser,'Internal Ticket','Create a ticket with title '.$this->request->getData('title'),0,$res->id);

                    $description = sprintf('%s created a internal ticket with title %s',$loginUser->first_name.' '.$loginUser->last_name,$this->request->getData('title'));
                    $this->TicketActivities($loginUser->id,'Created',$res->id,$description,$type);

                    $staffData = $this->userTbl->find()->select(['first_name','email'])->where(['id' => $staffId])->first();
                     
                    $emailData['toEmail'] = $staffData->email;
                    $emailData['name'] = $staffData->first_name;
                    $emailData['description'] = $description;
                    $assignNotificationAccess = $this->staffNotificationTbl->find()->select(['internal_ticket_create'])->where(['user_id' => $staffId])->first();
                    if ($assignNotificationAccess && $assignNotificationAccess->internal_ticket_create == 1) {
                        $this->sendEmailViaEmailType(54, $emailData);
                        // $this->sendAssignTicketEmail($name,$title,$email,$description); 
                    } 
    
                    $files = $this->request->getData('file');
                    for ($i = 0; $i < count($files); $i++) {
                        $ticketDoc = $this->ticketDocTbl->newEmptyEntity();
                        
                        // $targetPath = WWW_ROOT . 'img' . DS . 'tickets_file' . DS . $fileName;
                        if ($files[$i]->getClientFilename()) {
                            $originalFileName = $files[$i]->getClientFilename();
                            $fileName = chr(rand(97, 122)) . rand(10000, 99999) . pathinfo($originalFileName, PATHINFO_FILENAME);
                            $cleanedFileName = preg_replace('/[^A-Za-z0-9]/', '', $fileName);    
                            // Determine the file extension
                            $ext = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
                            $encodedFileName = $cleanedFileName.'.'.$ext;
    
                            // store image in aws s3
                            try {
                                $objects = $this->Amazon->s3->putObject([
                                    'Bucket'       => 'wte-partners',
                                    'Key'          => 'img/tickets_file/'.$encodedFileName,
                                    'SourceFile'   => $files[$i]->getStream()->getMetadata('uri')
                                ]);
    
                                $ext = strtolower(pathinfo($files[$i]->getClientFilename(), PATHINFO_EXTENSION));
                                if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'bmp' || $ext == 'eps') {
                                    $ticketDoc->doc_type = 2;
                                } else {
                                    $ticketDoc->doc_type = 1;
                                }
                                $ticketDoc->ticket_id = $ticket->id;
                                $ticketDoc->document = $encodedFileName;
                                $ticketDoc->category = 1;
                                $this->ticketDocTbl->save($ticketDoc);
                                
                            } catch (Aws\S3\Exception\S3Exception $e) {
                                print_r($e);
                            } 
                        }
                    }
                }
                $this->Flash->error(__('The ticket could not be saved. Please, try again.'));
            
        }
    }

    public function staffCreatedTicket()
    {
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        
        if ($loginUser->user_type == 2) {
            
            return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
        }

        $condition = ['InternalTickets.created_by'=>$loginUser->id, 'delete_tickets' => 0];
        $staffSeenCount = $this->internalTicketTbl->find()
        ->select(['ticket_seen' => 'COUNT(IF(InternalTickets.seen = 0, 1, NULL))', 'comment_seen' => 'COUNT(IF(Comments.seen = 0 AND Comments.category = 1, 1, NULL))'])
        ->join([
            'Comments' => [
                'table' => 'comment_notes',
                'type' => 'LEFT',
                'conditions' => [
                    'InternalTickets.id = Comments.ticket_id',
                    'Comments.category' => 1,
                    'Comments.user_id !=' => $loginUser->id,
                ]
            ]
        ])
        ->where([
            'InternalTickets.staff_id' => $loginUser->id,
            'InternalTickets.delete_tickets' => 0,
            'InternalTickets.status !=' => 3,
        ])
        ->first();
    
        $ticket_seen = $staffSeenCount['ticket_seen'];
       $comment_seen = $staffSeenCount['comment_seen'];
                   
            $tickets = $this->internalTicketTbl->find('all', [
                'fields' => [
                    'id' => 'InternalTickets.id',
                    'title' => 'InternalTickets.title',
                    'status' => 'InternalTickets.status',
                    'add_date' => 'DATE_FORMAT(InternalTickets.created_at,"%m/%d/%Y")',
                    'seen' => 'Comments.seen',
                    'staff_id' => 'Comments.user_id',
                    'store_name' => 'Store.store_name',
                    'client_name' => 'concat(Users.first_name," ", Users.last_name)',
                    'staff_name' => 'concat(StaffUsers.first_name," ", StaffUsers.last_name)'
                ],
                'join' => [
                    'Comments' => [
                        'table' => 'comment_notes',
                        'type' => 'LEFT',
                        'conditions' => '(InternalTickets.id = Comments.ticket_id AND Comments.seen=0 AND Comments.category = 1)'
                    ],
                    'Users' => [
                        'table' => 'users',
                        'type' => 'LEFT',
                        'conditions' => '(Users.id = InternalTickets.client_id AND Users.user_type=2 AND Users.delete_user = 0)'
                    ],
                    'Store' => [
                        'table' => 'store',
                        'type' => 'LEFT',
                        'conditions' => '(InternalTickets.store_id = Store.id AND Store.delete_store=0)'
                    ],
                    'StaffUsers' => [
                        'table' => 'users',
                        'type' => 'LEFT',
                        'conditions' => '(StaffUsers.id = InternalTickets.staff_id AND StaffUsers.user_type=1 AND StaffUsers.delete_user = 0)'
                    ]
                ],
    
            ])->where($condition)->group('InternalTickets.id')->order(['InternalTickets.created_at'=>'DESC'])->toarray();
        
        $this->set(compact('tickets','loginUser','ticket_seen','comment_seen'));
    }  
    
    public function staffTicketCount()
    {
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

        // $condition = ['InternalTickets.created_by'=>$loginUser->id, 'delete_tickets' => 0];
        $staffSeenCount = $this->internalTicketTbl->find()
        ->select([
            'ticket_seen' => 'COUNT(IF(InternalTickets.seen = 0, 1, NULL))', 'comment_seen' => 'COUNT(IF(Comments.seen = 0 AND Comments.category = 1, 1, NULL))'
            ])
        ->join([
            'Comments' => [
                'table' => 'comment_notes',
                'type' => 'LEFT',
                'conditions' => [
                    'InternalTickets.id = Comments.ticket_id',
                    'Comments.category' => 1,
                    'Comments.user_id !=' => $loginUser->id,
                ]
            ]
        ])
        ->where([
        //    'OR' => [
        //         'InternalTickets.created_by' => $loginUser->id,
        //         'InternalTickets.staff_id' => $loginUser->id,
        //     ], 
            'InternalTickets.staff_id' => $loginUser->id,

            'InternalTickets.delete_tickets' => 0,
            // 'InternalTickets.status NOT IN' => [2, 3],
            'InternalTickets.status !=' => 3,
        ])
        ->first();

        $createdSeenCount = $this->internalTicketTbl->find()
        ->select([
            'comment_seen' => 'COUNT(IF(Comments.seen = 0 AND Comments.category = 1, 1, NULL))'
        ])
        ->join([
            'Comments' => [
                'table' => 'comment_notes',
                'type' => 'LEFT',
                'conditions' => [
                    'InternalTickets.id = Comments.ticket_id',
                    'Comments.category' => 1,
                    'Comments.user_id !=' => $loginUser->id,
                ]
            ]
        ])
        ->where([
            'InternalTickets.created_by' => $loginUser->id,
            'InternalTickets.delete_tickets' => 0,
            'InternalTickets.status !=' => 3,
        ])
        ->first();

        $result = [
            'ticket_seen' => $staffSeenCount['ticket_seen'],
            'comment_seen' => $staffSeenCount['comment_seen'] + $createdSeenCount['comment_seen']
        ];

        echo json_encode($result);
        die;         
    } 


    public function TicketActivities($userId,$action,$ticketId,$description,$origin){
        $activity = $this->ticketActivitiesTbl->newEmptyEntity();
        $activity->ticket_id = $ticketId;
        $activity->user_id = $userId;
        $activity->action = $action;
        $activity->description = $description;
        $activity->origin = $origin;
        $this->ticketActivitiesTbl->save($activity);
    }

    public function deleteInternalTicket(){
        $loginUser = $this->session->read('user');
        
        if($this->request->is("GET")) 
        {
            $id = $this->request->getQuery('id');
            
            $ticketData = $this->internalTicketTbl->find()->where(['id' => $id])->first();  
 
            $deleteTicket = $this->internalTicketTbl->query()->update()->set(['delete_tickets' => 1])->where(['id' => $id]);
            if ($deleteTicket->execute()) {
 
                 //save data into activity log
                 $this->activityLog($loginUser,'Internal Ticket','Delete Internal Ticket',$ticketData->store_id,$id);

                 $description = sprintf('%s deleted a internal ticket with title %s',$loginUser->first_name.' '.$loginUser->last_name,$ticketData->title);
                 $this->TicketActivities($loginUser->id,'Deleted',$id,$description,'internalticket');
                    echo 1;
                    die;
                    
                }
        }
    }

    public function createChatTicket()
    {
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        
        if ($this->request->is('post')) {

            $ticketType = $this->request->getData('ticket_type');
            $action = $this->request->getData('action');
            $chats = $this->request->getData('bulkChats');
            $manager = $this->request->getData('assign_manager');
            $store_id = $this->request->getData('store_id');
            $redirectURl = ['controller' => 'Client','action' => $action];
            if ($action == 'tickets') {
                $redirectURl = ['controller' => 'Tickets', 'action' => 'index']; 
            }elseif ($action != 'launchPhase') {
                $redirectURl= ['controller' => 'Store', 'action' => $action,$store_id];
            }

            if($ticketType == 2)
            {
                $ticket = $this->Tickets->newEmptyEntity();

                $clientId = $this->request->getData('client_id');
           
                if ($this->request->getData('supporttitle') == '') {

                    $this->Flash->error('Ticket Title should be at least 3 character.', ['key' => 'fieldErr']);
                    return $this->redirect(['controller' => 'Client','action' => 'onboardingClient']);
                } elseif ($this->request->getData('issue_type') ==  0 || empty($this->request->getData('issue_type'))) {

                    $this->Flash->error('Please select the issue type.', ['key' => 'fieldErr']);
                    return $this->redirect(['controller' => 'Client','action' => 'onboardingClient']);
                }
                $storeID = $this->request->getData('store_id');
                $title = $this->request->getData('supporttitle');
                $issueType = $this->request->getData('issue_type');
                
                $ticket->client_id = $clientId;
                $ticket->store_id = $storeID;
                $ticket->title = $title;
                $ticket->issue_type = $issueType;
                $ticket->description = $this->request->getData('supportdescription');
                $ticket->store_specific = '1';
                $ticket->support_staff = $manager;
                if ($res = $this->Tickets->save($ticket)) {

                    //update ticket identifier in table
                    $this->Tickets->query()->update()->set(['ticket_identity' => 'SUPPORT-'.$res->id])->where(['id' => $res->id])->execute();

                    $chatIds = explode(',',$chats);
                    for($c = 0; $c < count($chatIds)-1; $c++)
                    {
                        $comments = $this->chatTbl->get($chatIds[$c]);
                        $commentNotes = $this->commentTbl->newEmptyEntity();
                        $commentNotes->ticket_id = $res->id;
                        $commentNotes->user_id = $comments->sender_id;
                        $commentNotes->comment_notes = ($comments->type == 'chat') ? $comments->chat : '<a class="fw-semi-bold" href="javascript:void(0);" onclick="getDoc(`'. $comments->doc .'`)">'.$comments->doc.'</a>';
                        $commentNotes->type = 1;
                        $commentNotes->seen = $comments->seen;
                        $commentNotes->category = 0;
                        $commentNotes->created_at = $comments->created_at;
                        $commentNotes->updated_at = $comments->updated_at;
                        $this->commentTbl->save($commentNotes);
                    }

                    //assign watchers
                    $watchers = $this->request->getData('watchers') ?? [];
                    if (count($watchers) > 0) {
                        foreach ($watchers as $watcher) {
                            $watch = $this->TicketWatchersTbl->newEmptyEntity();
                            $watch->ticket_id = $res->id;
                            $watch->watcher_id = $watcher;
                            $this->TicketWatchersTbl->save($watch);
                        }
                    }

                    $clientData = $this->userTbl->get($clientId);
                    $stores = $this->storeTbl->find()->where(['id'=>$storeID])->first();
                    $emailData = [];
                    $emailData['title'] = $title;                                           
                    $emailData['storeName'] = $stores->store_name;
                    $emailData['category'] = $this->issues[$issueType];
                    $emailData['identity'] = 'SUPPORT-'.$res->id;
                    if ($loginUser->user_type != 2) {
                        if (count($watchers) > 0) {
                            foreach ($watchers as $watcherId) {
                                //check if watcher has permission to receive notification
                                $notificationAccess = $this->staffNotificationTbl->find()->select(['watcher_ticket'])->where(['user_id' => $watcherId])->first();
                                if($notificationAccess && $notificationAccess->watcher_ticket == 1) {
                                    $watcher = $this->userTbl->get($watcherId);
                                    //send notification to watcher
                                    $emailData['name'] = $watcher['first_name'].' '.$watcher['last_name'];
                                    $emailData['toEmail'] = $watcher['email']; 
                                    $this->sendEmailViaEmailType(50, $emailData);
                                }
                            }
                        }
        
                        //send mail to client
                        $emailData['toEmail'] = $clientData->email;                
                        $emailData['clientName'] = $clientData->first_name.' '.$clientData->last_name;
                        $this->sendEmailViaEmailType(49, $emailData);
                    }


                    //save data into activity log
                    $this->activityLog($loginUser,'Ticket','Create a ticket with title '.$this->request->getData('title').'from onboarding client chat section',$storeID,$res->id,"","","",$this->request->getData('issue_type'));
                    $this->TicketActivities($loginUser->id,'Created',$res->id,'Create a ticket with title '.$this->request->getData('title').'from onboarding client chat section','ticket');

                    $files = $this->request->getData('file');
                    for ($i = 0; $i < count($files); $i++) {
                        $ticketDoc = $this->ticketDocTbl->newEmptyEntity();
                        
                        // $targetPath = WWW_ROOT . 'img' . DS . 'tickets_file' . DS . $fileName;
                        if ($files[$i]->getClientFilename()) {
        
                            $originalFileName = $files[$i]->getClientFilename();
                            $fileName = chr(rand(97, 122)) . rand(10000, 99999) . pathinfo($originalFileName, PATHINFO_FILENAME);
                            $cleanedFileName = preg_replace('/[^A-Za-z0-9]/', '', $fileName);    
                            // Determine the file extension
                            $ext = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
                            $encodedFileName = $cleanedFileName.'.'.$ext;

                            // store image in aws s3
                            try {
                                $objects = $this->Amazon->s3->putObject([
                                    'Bucket'       => 'wte-partners',
                                    'Key'          => 'img/tickets_file/'.$encodedFileName,
                                    'SourceFile'   => $files[$i]->getStream()->getMetadata('uri')
                                ]);

                                $ext = strtolower(pathinfo($files[$i]->getClientFilename(), PATHINFO_EXTENSION));
                                if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'bmp' || $ext == 'eps') {
                                    $ticketDoc->doc_type = 2;
                                } else {
                                    $ticketDoc->doc_type = 1;
                                }
                                $ticketDoc->ticket_id = $ticket->id;
                                $ticketDoc->document = $encodedFileName;
                                $this->ticketDocTbl->save($ticketDoc);
                                
                            } catch (Aws\S3\Exception\S3Exception $e) {
                                print_r($e);
                            }
                            
                        }
                    }


                    $this->Flash->success('The ticket has been saved.', ['key' => 'saveTickets']);
                    return $this->redirect($redirectURl);
                }
            }
            else{

                $ticket = $this->internalTicketTbl->newEmptyEntity();

                $staffId = $this->request->getData('staff_id');
                $title = $this->request->getData('title');
                $description = $this->request->getData('description');
                $client_id = $this->request->getData('client_id');
                $department_type = $this->request->getData('department_type');
                $product_type = $this->request->getData('product_type');
                $store_type =  $this->request->getData('store_type');
                $department_id =  $this->request->getData('department_id');
                $product_id =  $this->request->getData('product_id');
                $store_id =  $this->request->getData('store_id');
                $watcher_ids = $this->request->getData('watcher_id') ?? [];
    
                if($department_type ==1){
                    $ticket->department_id = $department_id;
                }
                if($store_type ==1){
                    $ticket->store_id = $store_id;
                }
                $ticket->staff_id = $staffId;
                $ticket->client_id = $client_id;
                $ticket->department_type = $department_type;
                $ticket->product_type = $product_type;
                $ticket->store_type = $store_type;
                $ticket->title = $title;
                $ticket->description = $description;
                $ticket->created_by = $loginUser->id;
                
                if ($res = $this->internalTicketTbl->save($ticket)) {

                    $ticketIdentity = 'INTERNAL-'.$res->id;
                    //update ticket identifier in table
                    $this->internalTicketTbl->query()->update()->set(['ticket_identity' => $ticketIdentity])->where(['id' => $res->id])->execute();

                    $emailData = [];
                    $emailData['title'] = $title;                                           
                    $emailData['identity'] = $ticketIdentity;
                    
                    if($product_type ==1){
                        if(count($product_id)){
                            foreach($product_id	 as $product){
                                $ticketProduct = $this->internalTicketProductsTbl->newEmptyEntity();
                                $ticketProduct->internal_tickets_id	 = $ticket->id;
                                $ticketProduct->product_id = $product;
                                $this->internalTicketProductsTbl->save($ticketProduct);
                            }
                        }    
                    }

                    //add watchers
                    if(count($watcher_ids) > 0) {
                        foreach($watcher_ids as $watcher){
                            $ticketWatcher = $this->internalTicketWatchersTbl->newEmptyEntity();
                            $ticketWatcher->internal_tickets_id     = $ticket->id;
                            $ticketWatcher->watcher_id = $watcher;
                            $this->internalTicketWatchersTbl->save($ticketWatcher);
                        }
                        //send mail to all watchers
                        $watchers = $this->userTbl->find('all', [
                            'conditions' => ['id IN' => $watcher_ids]
                        ])->toArray();
                        foreach ($watchers as $watcher) {
                            $watcherNotificationAccess = $this->staffNotificationTbl->find()->select(['internal_ticket_watcher'])->where(['user_id' => $watcher->id])->first();
                            if ($watcherNotificationAccess && $watcherNotificationAccess->internal_ticket_watcher == 1) {
                                $watcherName = $watcher->first_name.' '.$watcher->last_name;
                                $emailData['toEmail'] = $watcher->email;                
                                $emailData['name'] = $watcherName;
                                $emailData['userName'] = $loginUser->first_name.' '.$loginUser->last_name;
                                $this->sendEmailViaEmailType(53, $emailData);
                                // $this->sendInternalTicketEmailToWatchers($watcher->email, $watcherName, $title,$loginUser->first_name.' '.$loginUser->last_name,$ticketIdentity);
                            }
                        }
                    }

                    $chatIds = explode(',',$chats);
                    for($c = 0; $c < count($chatIds)-1; $c++)
                    {
                        $comments = $this->chatTbl->get($chatIds[$c]);
                        $commentNotes = $this->commentTbl->newEmptyEntity();
                        $commentNotes->ticket_id = $res->id;
                        $commentNotes->user_id = $comments->sender_id;
                        $commentNotes->comment_notes = ($comments->type == 'chat') ? $comments->chat : '<a class="fw-semi-bold" href="javascript:void(0);" onclick="getDoc(`'. $comments->doc .'`)">'.$comments->doc.'</a>';
                        $commentNotes->type = 1;
                        $commentNotes->seen = $comments->seen;
                        $commentNotes->category = 1;
                        $commentNotes->created_at = $comments->created_at;
                        $commentNotes->updated_at = $comments->updated_at;
                        $this->commentTbl->save($commentNotes);
                    }
                    //save data into activity log
                    $this->activityLog($loginUser,'Internal Ticket','Create a ticket with title '.$this->request->getData('title').' from onboarding client page',0,$res->id);

                    $description = sprintf('%s created a internal ticket with title %s',$loginUser->first_name.' '.$loginUser->last_name,$this->request->getData('title'));
                    $this->TicketActivities($loginUser->id,'Created',$res->id,$description,$ticketType);

                    if($staffId)
                    {
                        $staffData = $this->userTbl->find()->select(['first_name','email'])->where(['id' => $staffId])->first();
                     
                        // $name = $emailData->first_name;
                        // $email = $emailData->email;
                        $emailData['toEmail'] = $staffData->email;
                        $emailData['name'] = $staffData->first_name;
                        $emailData['description'] = $description;
                        $assignNotificationAccess = $this->staffNotificationTbl->find()->select(['internal_ticket_create'])->where(['user_id' => $staffId])->first();
                        if ($assignNotificationAccess && $assignNotificationAccess->internal_ticket_create == 1) {
                            $this->sendEmailViaEmailType(54, $emailData);
                            // $this->sendAssignTicketEmail($name,$title,$email,$description); 
                        }
                    }
                    
    
                    $files = $this->request->getData('files');
                    for ($i = 0; $i < count($files); $i++) {
                        $ticketDoc = $this->ticketDocTbl->newEmptyEntity();
                        
                        // $targetPath = WWW_ROOT . 'img' . DS . 'tickets_file' . DS . $fileName;
                        if ($files[$i]->getClientFilename()) {
                            $originalFileName = $files[$i]->getClientFilename();
                            $fileName = chr(rand(97, 122)) . rand(10000, 99999) . pathinfo($originalFileName, PATHINFO_FILENAME);
                            $cleanedFileName = preg_replace('/[^A-Za-z0-9]/', '', $fileName);    
                            // Determine the file extension
                            $ext = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
                            $encodedFileName = $cleanedFileName.'.'.$ext;
    
                            // store image in aws s3
                            try {
                                $objects = $this->Amazon->s3->putObject([
                                    'Bucket'       => 'wte-partners',
                                    'Key'          => 'img/tickets_file/'.$encodedFileName,
                                    'SourceFile'   => $files[$i]->getStream()->getMetadata('uri')
                                ]);
    
                                $ext = strtolower(pathinfo($files[$i]->getClientFilename(), PATHINFO_EXTENSION));
                                if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'bmp' || $ext == 'eps') {
                                    $ticketDoc->doc_type = 2;
                                } else {
                                    $ticketDoc->doc_type = 1;
                                }
                                $ticketDoc->ticket_id = $ticket->id;
                                $ticketDoc->document = $encodedFileName;
                                $ticketDoc->category = 1;
                                $this->ticketDocTbl->save($ticketDoc);
                                
                            } catch (Aws\S3\Exception\S3Exception $e) {
                                print_r($e);
                            } 
                        }
                    }
                    $this->Flash->success('The ticket has been saved.', ['key' => 'saveTickets']);
                    return $this->redirect($redirectURl);
                }

            }

            $this->Flash->error(__('The ticket could not be saved. Please, try again.'));

        }
        return $this->redirect(['controller' => 'Client','action' => 'onboardingClient']);
    }

    public function editInternalTicketWatcher(){
        $loginUser = $this->session->read('user');
        if(!$loginUser)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        if ($this->request->is('get')) {
            $this->viewBuilder()->setLayout('ajax');
            $ticketID = $this->request->getQuery('ticket_id');
            $ticketWatchers = $this->internalTicketWatchersTbl->find()->where(['internal_tickets_id' => $ticketID])->toArray();
            //all users who have permission to view internal ticket are watchers
            $mlist = $this->menu->find()->select(['id'])->where(['url' => 'internal-tickets'])->first();
            
            $watcherData = $this->userTbl->find()
            ->select(['id', 'first_name', 'last_name'])
            ->join([
                'UserPermissions' => [
                    'table' => 'user_permissions',
                    'type' => 'INNER',
                    'conditions' => 'UserPermissions.user_id = Users.id'
                ]
            ])
            ->where(['Users.user_type' => 1, 'Users.delete_user' => 0,'UserPermissions.permission !=' => 3, 'UserPermissions.menu_id' => $mlist['id']])
            ->toArray();

            $this->set(compact('ticketID','ticketWatchers','watcherData'));

        } else if ($this->request->is(['post', 'put', 'patch'])) {
            $this->viewBuilder()->setLayout('ajax');

            $id = $this->request->getData('editId');
            $watchers = $this->request->getData('watchers') ?? [];

            // Retrieve the ticket title
            $ticketData = $this->internalTicketTbl->find()
                            ->select(['title','ticket_identity'])
                            ->where(['id' => $id])
                            ->first();

            // Retrieve existing watchers
            $existingWatchers = $this->internalTicketWatchersTbl
                ->find()
                ->where(['internal_tickets_id' => $id])
                ->extract('watcher_id')
                ->toArray();

            // get deleted and added watchers
            $deletedWatchers = array_diff($existingWatchers, $watchers);
            $addedWatchers = array_diff($watchers, $existingWatchers);
            // Delete watchers
            if (!empty($deletedWatchers)) {
                $this->internalTicketWatchersTbl
                    ->deleteAll(['watcher_id IN' => $deletedWatchers, 'internal_tickets_id' => $id]);
            }
            $newWatchersDetails = [];
            // Add new watchers
            if (!empty($addedWatchers)) {
                $newWatchers = [];
                foreach ($addedWatchers as $watcherId) {
                    $newWatchers[] = [
                        'internal_tickets_id' => $id,
                        'watcher_id' => $watcherId
                    ];
                }
                $entities = $this->internalTicketWatchersTbl->newEntities($newWatchers);
                $this->internalTicketWatchersTbl->saveMany($entities);

                $newWatchersDetails = $this->userTbl->find('all', [
                    'conditions' => ['id IN' => $addedWatchers],
                    'fields' => ['id','email', 'first_name', 'last_name']
                ])->toArray();
                
                foreach ($newWatchersDetails as $watcher) {
                    //notification Access
                    $watcherNotificationAccess = $this->staffNotificationTbl->find()->select(['internal_ticket_watcher'])->where(['user_id' => $watcher->id])->first();
                    if ($watcherNotificationAccess && $watcherNotificationAccess->internal_ticket_watcher == 1)
                    {
                        $watcherName = $watcher->first_name . ' ' . $watcher->last_name;
                        $emailData['toEmail'] = $watcher->email;                
                        $emailData['name'] = $watcherName;
                        $emailData['userName'] = $loginUser->first_name.' '.$loginUser->last_name;
                        $emailData['title'] = $ticketData->title;
                        $emailData['identity'] = $ticketData->ticket_identity;
                        $this->sendEmailViaEmailType(53, $emailData);
                        // $this->sendInternalTicketEmailToWatchers(
                        //     $watcher->email,
                        //     $watcherName,
                        //     $ticketTitle,
                        //     $loginUser['first_name'] . ' ' . $loginUser['last_name']
                        // );
                    }                    
                }
            }
            $WatchersDetails = $this->getInternalTicketWatchers($id);

            $this->Flash->success('The watchers have been saved.', ['key' => 'editTickets']);
            echo json_encode(['status' => 1,'watchers'=> $WatchersDetails]);
            exit;

        }

    }

    public function getDepartmentStaff(){

        $this->viewBuilder()->setLayout('ajax');
        $departmentId = $this->request->getQuery('department_id');
    
        // Get all users of the department
        $staffData = $this->issuTbl->find()
            ->select(['Users.id', 'Users.first_name', 'Users.last_name'])
            ->join([
                'Users' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'Users.id = UsersIssues.staff_id'
                ]
            ])
            ->where([
                'UsersIssues.issue_type' => $departmentId, 
                'Users.user_type' => 1, 
                'Users.delete_user' => 0
            ])
            ->toArray();   
    
        $data = json_encode($staffData);

        echo $data;
        die;
    }  
    
    public function editTicketWatcher(){
        $loginUser = $this->session->read('user');
        if ($this->request->is('get')) {
            $this->viewBuilder()->setLayout('ajax');
            $ticketID = $this->request->getQuery('ticket_id');
            $ticketWatchers = $this->TicketWatchersTbl->find()->where(['ticket_id' => $ticketID])->toArray();

            $departmentId = $this->request->getQuery('issue_type');

            $watcherData = $this->issuTbl->find()
            ->select(['Users.id', 'Users.first_name', 'Users.last_name'])
            ->join([
                'Users' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'Users.id = UsersIssues.staff_id'
                ]
            ])
            ->where([
                'UsersIssues.issue_type' => $departmentId, 
                'Users.user_type' => 1, 
                'Users.delete_user' => 0
            ])
            ->toArray();

            $this->set(compact('ticketID','ticketWatchers','watcherData'));
        }else if ($this->request->is(['post', 'put', 'patch'])) {
            $this->viewBuilder()->setLayout('ajax');

            $id = $this->request->getData('editId');
            $watchers = $this->request->getData('watchers') ?? [];
            $ticketData = $this->Tickets->find()->select(['title','client_id','store_id','issue_type'])->where(['id' => $id])->first();
            // Retrieve existing watchers
            $existingWatchers = $this->TicketWatchersTbl
                ->find()
                ->where(['ticket_id' => $id])
                ->extract('watcher_id')
                ->toArray();

            // get deleted and added watchers
            $deletedWatchers = array_diff($existingWatchers, $watchers);
            $addedWatchers = array_diff($watchers, $existingWatchers);
            // Delete watchers
            if (!empty($deletedWatchers)) {
                $this->TicketWatchersTbl
                    ->deleteAll(['watcher_id IN' => $deletedWatchers, 'ticket_id' => $id]);
            }

            //add the new watcher
            if (!empty($addedWatchers)) {
                $newWatchers = [];
                foreach ($addedWatchers as $watcherId) {
                    //check if watcher has permission to receive notification
                    $notificationAccess = $this->staffNotificationTbl->find()->select(['watcher_ticket'])->where(['user_id' => $watcherId])->first();

                    if($notificationAccess && $notificationAccess->watcher_ticket == 1) {                        
                        $watcher = $this->userTbl->get($watcherId);
                        $watcherName = $watcher['first_name'] .''. $watcher['last_name'];
                        //send email to new watchers
                        $stores = $this->storeTbl->find()->where(['id'=>$ticketData->store_id])->first();

                        $emailData = [];
                        $emailData['toEmail'] = $watcher['email'];                
                        $emailData['name'] = $watcherName;
                        $emailData['title'] = $ticketData->title;                                           
                        $emailData['storeName'] = $stores->store_name;
                        $emailData['category'] = $this->issues[$ticketData->issue_type];

                        $this->sendEmailViaEmailType(50, $emailData);
                    }

                    $newWatchers[] = [
                        'ticket_id' => $id,
                        'watcher_id' => $watcherId
                    ];
                }
                $entities = $this->TicketWatchersTbl->newEntities($newWatchers);
                $this->TicketWatchersTbl->saveMany($entities);
            }
            

            $description = 'Updated watchers of ticket having title '.$ticketData->title;
            $this->TicketActivities($loginUser->id,'Updated',$id,$description,'ticket');
            $watchersDetails = $this->getTicketWatchers($id);
            $this->Flash->success('The watchers have been saved.', ['key' => 'editTickets']);
            echo json_encode(['status' => 1,'watchers'=> $watchersDetails]);

            die();
        }


    }

    public function getTicketWatchers($id){
        $watchersDetails = $this->TicketWatchersTbl->find()
            ->select(['watcher_id' => 'Users.id', 'watcher_name' => 'CONCAT(Users.first_name," ",Users.last_name)','email' => 'Users.email'])
            ->join([
                'Users' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'Users.id = TicketWatchers.watcher_id'
                ]
            ])
            ->where(['TicketWatchers.ticket_id' => $id])
            ->toArray();
        return $watchersDetails;

    }

    public function editInternalTicketClient(){
        $this->viewBuilder()->setLayout('ajax');
        $loginUser = $this->session->read('user');
        if ($this->request->is('get')) {
            $ticketID = $this->request->getQuery('id');
            $ticketData = $this->internalTicketTbl->find()->where(['id' => $ticketID])->first();
            $clientData = $this->userTbl->find()->where(['user_type'=>2,'status'=>1,'delete_user'=>0])->toArray();
            $storeData =  $this->storeTbl->find()
            ->select(['id', 'store_name'])
            ->where(['clients' => $ticketData->client_id])
            ->toArray();
            $this->set(compact('ticketID','ticketData','clientData','storeData'));
        } else if ($this->request->is(['post', 'put', 'patch'])) {
            $id = $this->request->getData('editId');
            $client_id = $this->request->getData('client_id');
            $store_id = $this->request->getData('store_id');            

            // update client
            $updateData['client_id'] = $client_id;
            if(!empty($store_id)){
                $updateData['store_id'] = $store_id;
                $updateData['store_type'] = 1;
            }else{
                $updateData['store_id'] = 0;
                $updateData['store_type'] = 0;
                
            }

            $query = $this->internalTicketTbl->query()
            ->update()
            ->set($updateData)
            ->where(['id' => $id]);
            // dd($query);
            if ($query->execute()) {
                $ticketData = $this->internalTicketTbl
                ->find('all',[
                    'fields' => [
                        'id' => 'InternalTickets.id',
                        'store_name' => 'Store.store_name',
                        'client_name' => 'concat(Users.first_name," ", Users.last_name)',
                    ],
                    'join' => [
                        'Users' => [
                            'table' => 'users',
                            'type' => 'LEFT',
                            'conditions' => '(Users.id = InternalTickets.client_id AND Users.user_type=2 AND Users.delete_user = 0)'
                        ],
                        'Store' => [
                            'table' => 'store',
                            'type' => 'LEFT',
                            'conditions' => '(InternalTickets.store_id = Store.id AND Store.delete_store=0)'
                        ]
                    ]
                ])->where(['InternalTickets.id' => $id])
                ->first();
                $this->Flash->success('The client has been updated.', ['key' => 'editTickets']);
                echo json_encode(['status' => 1,'data' =>$ticketData]);
                die();
            }

        }
    }

    public function addTicketRating(){
        $this->viewBuilder()->setLayout('ajax');
        $loginUser = $this->session->read('user');
        if ($this->request->is('post')) {
            $ticketId = $this->request->getData('ticketId');
            $rating = $this->request->getData('rating');
            $userType = $loginUser->user_type;
            $ratingData = $this->TicketRatingTbl->find()->where(['ticket_id' => $ticketId, 'user_id' => $loginUser->id, 'user_type' => $userType])->first();
            $ticketData = $this->Tickets->find()->select(['title'])->where(['id' => $ticketId])->first();
            if (!empty($ratingData)) {
                $ratingData->rating = $rating;
                if ($this->TicketRatingTbl->save($ratingData)) {
                    echo json_encode(['status' => 1]);
                    die();
                } else {
                    echo json_encode(['status' => 0]);
                    die();
                }
            } else {                
                $ratingData = $this->TicketRatingTbl->newEmptyEntity();
                $ratingData->ticket_id = $ticketId;
                $ratingData->rating = $rating;
                $ratingData->user_id = $loginUser->id;
                $ratingData->user_type = $userType;
                if ($this->TicketRatingTbl->save($ratingData)) {
                    $description = sprintf('%s added rating to ticket having title '.$ticketData->title,$loginUser->first_name.' '.$loginUser->last_name);
                    $this->TicketActivities($loginUser->id,'Updated',$ticketId,$description,'ticket');
                    echo json_encode(['status' => 1]);
                    die();
                } else {
                    echo json_encode(['status' => 0]);
                    die();
                }
            }
        }
    }

    public function archivedInternalTickets(){
        $loginUser = $this->session->read('user');
        if ($loginUser->user_type == 2) {
            return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
        }
        $menu = $this->session->read('menu');
        $lgPermission = 3;        
        foreach($menu as $m)
        {
            if($m->folder == 'Extra')
            {
                foreach($m->main['Extra'] as $ml) {

                    if($ml->menu_name=='View All Internal Tickets' && $loginUser->user_type==1)
                    { 
                        $lgPermission = 1;
                    }
                }
            }
        }
        $condition = ['delete_tickets' => 0,'InternalTickets.status' =>3];

        // if internal staff show those tickets in which login staff is added as watcher
        if($loginUser->user_type == 1 && $lgPermission == 3){
            $ticketIds = $this->internalTicketWatchersTbl->find()
                            ->select(['internal_tickets_id'])
                            ->where(['watcher_id' => $loginUser->id])
                            ->all()
                            ->extract('internal_tickets_id')
                            ->toArray();               
            //add also those tickets which is assigned to him or created by him
            $tiketsAssingedAndCreated = $this->internalTicketTbl->find()
                            ->select(['id'])
                            ->where(['OR'=>['staff_id'=>$loginUser->id,'created_by' => $loginUser->id]])
                            ->all()
                            ->extract('id')
                            ->toArray();
            // dd($tiketsAssingedAndCreated);                            
            //merge both arrays
            $ticketIds = array_merge($ticketIds, $tiketsAssingedAndCreated);
            if (empty($ticketIds)) {
                $ticketIds = [0];
            }
            $condition['InternalTickets.id IN'] = $ticketIds;             
        } 

        $ticketData = $this->internalTicketTbl->find()->select([
            'id' => 'InternalTickets.id',
            'title','InternalTickets.status',
            'user'=>'CONCAT(Users.first_name," ",Users.last_name)',
            'InternalTickets.department_id',
            'InternalTickets.department_type',
            'InternalTickets.product_type',
            'createdBy' => 'CONCAT(CreatedBy.first_name," ",CreatedBy.last_name)',
            'created_role' => 'Role.role_name',
            'Client_name' => 'CONCAT(Clients.first_name," ",Clients.last_name)',
            'store_name' => 'Store.store_name',
            'ticket_identity' => 'InternalTickets.ticket_identity',
            'closed_at' => 'InternalTickets.closed_at',
            'created_at' => 'InternalTickets.created_at',
            ])->join([
                'Users' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'Users.id=InternalTickets.staff_id'
                ],
                'Clients' => [
                    'table' => 'users',
                    'type' => 'LEFT',
                    'conditions' => 'Clients.id = InternalTickets.client_id'
                ],
                'CreatedBy' => [
                    'table' => 'users',
                    'type' => 'LEFT',
                    'conditions' => 'CreatedBy.id = InternalTickets.created_by',
                ],
                'Store' => [
                    'table' => 'store',
                    'type' => 'LEFT',
                    'conditions' => '(Store.id = InternalTickets.store_id AND InternalTickets.store_type != 0)',
                ],
                'Role' => [
                    'table' => 'roles',
                    'type' => 'LEFT',
                    'conditions' => 'Role.id = CreatedBy.role_id',
                ],
                'InternalTicketProducts' => [
                    'table' => 'internal_ticket_products',
                    'type' => 'LEFT',
                    'conditions' => 'InternalTicketProducts.internal_tickets_id = InternalTickets.id'
                ],
                'InternalTicketWatchers' => [
                    'table' => 'internal_ticket_watchers',
                    'type' => 'LEFT',
                    'conditions' => 'InternalTicketWatchers.internal_tickets_id = InternalTickets.id',
                ],
            ])
            ->where($condition)->group('InternalTickets.id')->order(['InternalTickets.id' => 'DESC'])
            ->toArray();
            
        $this->set(compact('loginUser','ticketData'));
    }

    public function archiveInternalDetail(){
        if ($this->request->is('get')) {
            $this->viewBuilder()->setLayout('ajax');
            $loginUser = $this->session->read('user');
            if ($loginUser->user_type == 2) {
                return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
            }
            $loginMaster = $this->session->read('master');
            $menu = $this->session->read('menu');
            $permission = 3; 
            if ($loginUser->user_type == 0) {
                $permission = 2;
            }
            foreach($menu as $m)
            {
                if( $m->folder== 'Support')
                {
                    foreach($m->main['Support'] as $ml) {

                        if($ml->url=='internal-tickets' && $loginUser->user_type==1)
                        { 
                            $permission = $ml->permission;
                            break;
                        }
                    }
                }
            }

            $id = $this->request->getQuery('id');

            $ticketData = $this->internalTicketTbl->find()
                    ->select([
                        'id' => 'InternalTickets.id',
                        'title' => 'InternalTickets.title',
                        'status' => 'InternalTickets.status',
                        'description' => 'InternalTickets.description',
                        'department_type' => 'InternalTickets.department_type',
                        'department_id' => 'InternalTickets.department_id',
                        'product_type' => 'InternalTickets.product_type',
                        'created_by' => 'InternalTickets.created_by',
                        'docType' => 'TicketDoc.doc_type',
                        'document' => 'TicketDoc.document',
                        'doc_id' => 'TicketDoc.id',
                        'name' => 'CONCAT(Users.first_name," ",Users.last_name)',
                        'staff_id' => 'Users.id',
                        'created' => 'DATE_FORMAT(DATE_SUB(InternalTickets.created_at, INTERVAL 4 HOUR), "%m/%d/%Y %H:%i:%s")',
                        'Client_name' => 'CONCAT(Clients.first_name," ",Clients.last_name)',
                        'store_name' => 'Store.store_name',
                        'createdBy' => 'CONCAT(CreatedBy.first_name," ",CreatedBy.last_name)',
                        'ticket_identity' => 'InternalTickets.ticket_identity',
                    ])
                    ->join([
                        'TicketDoc' => [
                            'table' => 'ticket_doc',
                            'type' => 'LEFT',
                            'conditions' => '(TicketDoc.ticket_id = InternalTickets.id AND TicketDoc.category = 1)'
                        ],
                        'Users' => [
                            'table' => 'users',
                            'type' => 'INNER',
                            'conditions' => 'Users.id = InternalTickets.staff_id'
                        ],
                        'Clients' => [
                            'table' => 'users',
                            'type' => 'LEFT',
                            'conditions' => 'Clients.id = InternalTickets.client_id'
                        ],
                        'CreatedBy' => [
                            'table' => 'users',
                            'type' => 'LEFT',
                            'conditions' => 'CreatedBy.id = InternalTickets.created_by',
                        ],
                        'Store' => [
                            'table' =>'store',
                            'type' => 'LEFT',
                            'conditions' => '(Store.id = InternalTickets.store_id AND InternalTickets.store_type != 0)'
                        ],
                    ])
                ->where(['InternalTickets.id' => $id, 'InternalTickets.delete_tickets' => 0])
                ->toArray();

                $commentData = $this->commentTbl->find()->select([
                    'id' => 'CommentNote.id',
                    'comment_notes' => 'CommentNote.comment_notes',
                    'cmt_time' => 'DATE_FORMAT(DATE_SUB(CommentNote.created_at, INTERVAL 4 HOUR),"%m/%d/%Y %H:%i:%s")',
                    'image' => 'Client.image',
                    'first_name' => 'Client.first_name',
                    'last_name' => 'Client.last_name',
                ])
                ->join([
                    'Client' => [
                        'table' => 'users',
                        'type' => 'INNER',
                        'conditions' => 'Client.id = CommentNote.user_id'
                    ]
                ])->where(['ticket_id' => $id, 'CommentNote.category' => 1])->toArray();
        }

        $this->set(compact('loginUser','ticketData','commentData','loginMaster','permission'));
    }
     
}