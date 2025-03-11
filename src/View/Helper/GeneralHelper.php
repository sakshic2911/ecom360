<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;
use PDO;

class GeneralHelper extends Helper
{
    public function ticketRespondCount($id)
    {
        $commentNotesTbl = TableRegistry::getTableLocator()->get('CommentNote');
        $responseCount = $commentNotesTbl->find()
        ->select([
            'ticket_id' => 'CommentNote.ticket_id',
            'staff_response_count' => 'COUNT(CommentNote.id)',
            'last_response' => 'CommentNote.created_at',
        ])
        ->join([
            'Staff' => [
                'table' => 'users',
                'type' => 'INNER',
                'conditions' => 'Staff.id = CommentNote.user_id AND Staff.user_type IN (0, 1)'
            ]
        ])
        ->where(['CommentNote.ticket_id' => $id])
        ->first();
        
        return $responseCount['staff_response_count'] ?? 0;
    }

    public function lastClientResponseDateTime($store_id){
        //'DATE_FORMAT(DATE_SUB(OnboardingChat.created_at, INTERVAL 4 HOUR), "%m/%d/%Y %H:%i:%s")',
        $chat = TableRegistry::getTableLocator()->get('OnboardingChat');
        $lastResponse = $chat->find()
        ->select(['last_response' => 'DATE_FORMAT(DATE_SUB(OnboardingChat.created_at, INTERVAL 4 HOUR),"%m/%d/%Y %H:%i:%s")'])
        ->join([
            'Users' => [
                'table' => 'users',
                'type' => 'INNER',
                'conditions' => 'Users.id = OnboardingChat.sender_id AND Users.user_type = 2'
            ]
        ])
        ->where(['OnboardingChat.store_id' => $store_id, 'OnboardingChat.category IN' => ['Launch', 'Active']])
        ->order(['OnboardingChat.created_at' => 'DESC'])
        ->first();
        
        return $lastResponse['last_response']?? null;
    }

    public function lastStaffResponseDateTime($store_id){
        $chat = TableRegistry::getTableLocator()->get('OnboardingChat');
        $lastResponse = $chat->find()
        ->select(['last_response' => 'DATE_FORMAT(DATE_SUB(OnboardingChat.created_at,INTERVAL 4 HOUR),"%m/%d/%Y %H:%i:%s")'])
        ->join([
            'Users' => [
                'table' => 'users',
                'type' => 'INNER',
                'conditions' => 'Users.id = OnboardingChat.sender_id AND Users.user_type IN (0, 1)'
            ]
        ])
        ->where(['OnboardingChat.store_id' => $store_id,'OnboardingChat.category IN' => ['Launch', 'Active']])
        ->order(['OnboardingChat.created_at' => 'DESC'])
        ->first();
        
        return $lastResponse['last_response']?? null;
    }

    public function lastRespondedBy($store_id,$client_id){
        $chat = TableRegistry::getTableLocator()->get('OnboardingChat');
        $lastResponse = $chat->find()
        ->select(['id'=>'Users.id','last_responded_by' => 'CONCAT(Users.first_name, " ", Users.last_name)'])
        ->join([
            'Users' => [
                'table' => 'users',
                'type' => 'INNER',
                'conditions' => 'Users.id = OnboardingChat.sender_id'
            ]
        ])
        ->where(['OnboardingChat.store_id' => $store_id,'OnboardingChat.category IN' => ['Launch', 'Active']])
        ->order(['OnboardingChat.created_at' => 'DESC'])
        ->first();

        if ($client_id == $lastResponse['id']) {
            $lastResponse['last_responded_by'] = 'Client';
        }
        
        return $lastResponse['last_responded_by']?? null;
    }
}