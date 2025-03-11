<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;

class LabelsDataHelper extends Helper
{
    public function labelData($ticket_id)
    {
        $lableTbl = TableRegistry::getTableLocator()->get('Labels');

        $lableData = $lableTbl->find()->select(['id', 'label_name'])->where(['ticket_id' => $ticket_id])->toArray();
        return $lableData;
    }
}