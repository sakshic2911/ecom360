<?php

declare(strict_types=1);


namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Mailer\Mailer;
use Cake\Http\Session;
use Google\Auth\Credentials\ServiceAccountCredentials;

class AppController extends Controller
{

    private $session;
    
    public function initialize(): void
    {
        parent::initialize();
        $this->session = $this->request->getSession();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->session = $this->request->getSession();
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Authorization.Authorization');
        $this->Authorization->skipAuthorization();
        $this->userTable = $this->getTableLocator()->get('Users');
        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // for all controllers in our application, make index and view
        // actions public, skipping the authentication check
        $this->Authentication->addUnauthenticatedActions(['forgotPassword']);
    }

    
    public function encryptData($card)
    {
        if($card)
        {
            $key = "ZiqRSj9O5XXYXDzZ9XXEBQ==";
            $method = "AES-256-CBC";
    
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
            $encryptedCard = openssl_encrypt($card, $method, $key, 0, $iv);
            $encryptedCard = base64_encode($iv.$encryptedCard);
    
           
            return $encryptedCard;
        }
        

    }

    public function decryptData($card)
    {
        if($card)
        {
            $key = "ZiqRSj9O5XXYXDzZ9XXEBQ==";
            $method = "AES-256-CBC";
    
            // Decode the encrypted data
           $card = base64_decode($card);
            
    
            // Extract the IV and the encrypted data
            $iv = substr($card, 0, openssl_cipher_iv_length($method));
            $card = substr($card, openssl_cipher_iv_length($method));
    
            // Decrypt the data
            $decryptedCard = openssl_decrypt($card, $method, $key, 0, $iv);
    
            // Display the decrypted data
            return $decryptedCard;
        }
        

    }


    public function sendEmailViaEmailType($emailTypeId, $emailData)
    {
        $this->loadModel('EmailTypes');
        $emailType = $this->EmailTypes->get($emailTypeId);
        $subject = $emailType->subject;
        $body = $emailType->body;
        
        if(!empty($emailData['name'])){
            $body = str_replace("#NAME#", $emailData['name'], $body);
        }
        if(!empty($emailData['clientName'])){
            $body = str_replace("#CLIENTNAME#", $emailData['clientName'], $body);
        }
        if(!empty($emailData['storeName'])){
            $body = str_replace("#STORENAME#", $emailData['storeName'], $body);
        }
        if(!empty($emailData['userName'])){
            $body = str_replace("#USERNAME#", $emailData['userName'], $body);
        }
        if(!empty($emailData['password'])){
            $body = str_replace("#PASSWORD#", $emailData['password'], $body);
        }
        if(!empty($emailData['invoiceNo'])){
            $body = str_replace("#INVOICENO#", $emailData['invoiceNo'], $body);
        }
        if(!empty($emailData['dueDate'])){
            $body = str_replace("#DUEDATE#", $emailData['dueDate'], $body);
        }
        if(!empty($emailData['amount'])){
            $body = str_replace("#AMOUNT#", $emailData['amount'], $body);
        }
        if(!empty($emailData['status'])){
            $body = str_replace("#STATUS#", $emailData['status'], $body);
        }
        if(!empty($emailData['brandName'])){
            $body = str_replace("#BRANDNAME#", $emailData['brandName'], $body);
        }
        if(!empty($emailData['productName'])){
            $body = str_replace("#PRODUCTNAME#", $emailData['productName'], $body);
        }
        if(!empty($emailData['quantity'])){
            $body = str_replace("#QUANTITY#", $emailData['quantity'], $body);
        }
        if(!empty($emailData['title'])){
            $body = str_replace("#TITLE#", $emailData['title'], $body);
        }
        if(!empty($emailData['managerName'])){
            $body = str_replace("#MANAGERNAME#", $emailData['managerName'], $body);
        }
        if(!empty($emailData['storeNo'])){
            $body = str_replace("#STORENUMBER#", $emailData['storeNo'], $body);
        }
        if(!empty($emailData['seniorManager'])){
            $body = str_replace("#SENIORNAME#", $emailData['seniorManager'], $body);
        }
        if(!empty($emailData['category'])){
            $body = str_replace("#CATEGORY#", $emailData['category'], $body);
        }
        if(!empty($emailData['identity'])){
            $body = str_replace("#IDENTITY#", $emailData['identity'], $body);
        }
        if(!empty($emailData['description'])){
            $body = str_replace("#DESCRIPTION#", $emailData['description'], $body);
        }
        if(!empty($emailData['storetype'])){
            $body = str_replace("#STORETYPE#", $emailData['storetype'], $body);
        }


        $body = str_replace("#ECOM360", ECOM360, $body);

        if(!empty($emailData['userId'])){
            $userId = $emailData['userId'];
        }
        else{
            $userId = null;
        }
        if(!empty($emailData['storeId'])){
            $storeId = $emailData['storeId'];
        }
        else{
            $storeId = null;
        }
        if($emailTypeId == 7){
            $bill_customer = $emailData['billCustomer'];
            $bill_email = $emailData['toEmail'];
        }
        else{
            $bill_customer = null;
            $bill_email = null;
        }
        $groupId = null;

        $emailLog = $this->createEmailLog($emailTypeId, $subject, $body, 0, $userId, $storeId,$groupId,$bill_customer,$bill_email);

        if($emailLog){
            $this->sendEmail($emailData['toEmail'], $subject, $body);
        }
    }

    public function createEmailLog($emailTypeId, $subject, $body, $isResent, $userId, $storeId = null, $groupId = null,$bill_customer = null,$bill_email = null, $managerId = null)
    {
        $user = $this->session->read('user');
        if(!empty($user))
        $send_by = $user->id;
        else
        $send_by = 0;
        $this->loadModel('EmailLogs');
        $emailLogs = $this->EmailLogs->newEmptyEntity();
        $emailLogs->email_type_id = $emailTypeId;
        if(!empty($userId)){
            $emailLogs->user_id = $userId;
        }
        if(!empty($storeId)){
            $emailLogs->store_id = $storeId;
        }
        if(!empty($groupId)){
            $emailLogs->group_id = $groupId;
        }
        if(!empty($bill_customer)){
            $emailLogs->bill_customer = $bill_customer;
        }
        if(!empty($bill_email)){
            $emailLogs->bill_email = $bill_email;
        }
        if(!empty($managerId)){
           $emailLogs->manager_id = $managerId;
        }
        $emailLogs->subject = $subject;
        $emailLogs->body = $body;
        $emailLogs->is_resent = $isResent;
        $emailLogs->send_by = $send_by;
        $emailLogs = $this->EmailLogs->save($emailLogs);
        // echo '<pre>'; print_r($emailLogs);
        if($emailLogs){
            return true;
        }
        else{
            return false;
        }
    }

    public function sendEmail($toEmail, $subject, $body, $emailAttachments=[])
    {
        $mailer = new Mailer();
        $mailer
            ->setTransport('default')
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($toEmail)
            ->setSubject($subject);

            if (!empty($emailAttachments)) {
                foreach ($emailAttachments as $attachment) {
                    $attachmentData = ['file' => $attachment['file']];
                    if (isset($attachment['mimetype'])) {
                        $attachmentData['mimetype'] = $attachment['mimetype'];
                    }
                    $mailer->addAttachments([$attachment['name'] => $attachmentData]);
                }
            }
            // ->viewBuilder()
            // ->setTemplate('addclient');
        // $mailer->setViewVars(['name'=> $name, 'email' => $email, 'password' => $password]);
        $mailer->deliver($body);
    }

    public function sendChatReport($file,$sendChatReport)
    {
        $mailer = new Mailer();

        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($sendChatReport[0]->email);
        $i = 0;
        foreach($sendChatReport as $val)
        {
            if($i > 0)
            $mailer->addTo($val->email);

            $i++;
        }
  
        $mailer->setSubject("Unanswered Chat Report")
            ->viewBuilder()
            ->setTemplate('chatreport');
        $mailer->setAttachments([$file]);
        $mailer->deliver();
    }

    public function sendInvoiceMail($file,$email)
    {
        $mailer = new Mailer();

        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email);
  
        $mailer->setSubject("Order Invoice")
            ->viewBuilder()
            ->setTemplate('invoice');
        $mailer->setAttachments([$file]);
        $mailer->deliver();
    }

    public function forgotPasswordEmailSend($password, $email)
    {
        $mailer = new Mailer();

        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->setSubject("Forgot Password")
            ->viewBuilder()
            ->setTemplate('forgotpass');
        $mailer->setViewVars(['email' => $email, 'password' => $password]);
        $mailer->deliver();
    }

    public function addInternalStaffEmail($name,$password, $email)
    {
        $mailer = new Mailer();

        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->setSubject("Welcome To The Ecom 360 Portal")
            ->viewBuilder()
            ->setTemplate('internalstaff');
        $mailer->setViewVars(['name'=> $name, 'email' => $email, 'password' => $password]);
        $mailer->deliver();
    }

    public function addClientEmail($name,$password, $email)
    {
        $mailer = new Mailer();

        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->setSubject("Welcome To The Ecom 360 Portal")
            ->viewBuilder()
            ->setTemplate('addclient');
        $mailer->setViewVars(['name'=> $name, 'email' => $email, 'password' => $password]);
        $mailer->deliver();
    }

    public function addStoreEmail($name,$store_name,$email)
    {
        $mailer = new Mailer();

        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->setSubject("Your Store Has Been Added To Ecom 360 Portal!")
            ->viewBuilder()
            ->setTemplate('addstore');
        $mailer->setViewVars(['name'=> $name, 'store' => $store_name]);
        $mailer->deliver();
    }

    public function uploadReportEmail($name,$store_name,$email)
    {
        $mailer = new Mailer();

        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->setSubject("A New Statement Has Been Added To Your Ecom 360 Portal!")
            ->viewBuilder()
            ->setTemplate('uploadreport');
        $mailer->setViewVars(['name'=> $name, 'store' => $store_name]);
        $mailer->deliver();
    }

    public function sendInvoiceEmail($name,$store_name,$email)
    {
        $mailer = new Mailer();

        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->setSubject("A New Invoice Has Been Added To Your Ecom 360 Portal!")
            ->viewBuilder()
            ->setTemplate('sendinvoice');
        $mailer->setViewVars(['name'=> $name, 'store' => $store_name]);
        $mailer->deliver();
    }

    public function addOrderEmail($store_name)
    {
        $mailer = new Mailer();

        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo('portal@wtepartners.com')
            ->setSubject("New Inventory Order Received From Your Ecom 360 Portal")
            ->viewBuilder()
            ->setTemplate('addorder');
        $mailer->setViewVars(['store' => $store_name]);
        $mailer->deliver();
    }

    public function updateOrderEmail($name,$store_name,$email,$status)
    {
        $mailer = new Mailer();

        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->setSubject("Inventory Order Status Update On Your Ecom 360 Portal")
            ->viewBuilder()
            ->setTemplate('updateorder');
        $mailer->setViewVars(['name'=> $name, 'store' => $store_name,'status'=>$status]);
        $mailer->deliver();
    }

    public function updateTicketEmail($name,$email,$title,$status)
    {
        $mailer = new Mailer();

        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->setSubject("Ticket Status Update On Your Ecom 360 Portal")
            ->viewBuilder()
            ->setTemplate('updateticket');
        $mailer->setViewVars(['name'=> $name, 'title'=> $title,'status'=>$status]);
        $mailer->deliver();
    }

    public function sendResponsetoAdminEmail($name,$email,$title,$support,$cc=[])
    {
        $mailer = new Mailer();
        $subject = "New Response Received on a Ticket ". $title." by ".$name;
        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo('portal@wtepartners.com');
            if($support != '')
            { 
                $mailer->addTo($support); 
            }
            if (!empty($cc)) {
                $mailer->setCc($cc);
            }
           $mailer->setSubject($subject)
            ->viewBuilder()
            ->setTemplate('adminticket');
        $mailer->setViewVars(['name'=> $name, 'title'=> $title]);
        $mailer->deliver();
    }

    public function sendResponsetoClientEmail($name,$email,$title)
    {
        $mailer = new Mailer();
        
        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->setSubject("New Response Received on a Ticket On Your Ecom 360 Portal")
            ->viewBuilder()
            ->setTemplate('clientticket');
        $mailer->setViewVars(['name'=> $name, 'title'=> $title]);
        $mailer->deliver();
    }

    public function sendChatResponsetoAdminEmail($name,$store,$manager_name,$manager_email)
    {
        if($manager_email != "")
            $email = $manager_email;
        else
        $email = 'vinny@wtepartners.com';

        $mailer = new Mailer();
        $subject = "New Response Received by ".$name;
        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->addTo('portal@wtepartners.com')
            ->setSubject($subject)
            ->viewBuilder()
            ->setTemplate('adminonboarding');
        $mailer->setViewVars(['name'=> $name, 'store'=> $store,'manager_name' => $manager_name]);
        $mailer->deliver();
    }

    public function sendAssignTicketEmail($name,$title,$email,$description)
    {
        $mailer = new Mailer();
        $subject = "New Internal Ticket Assigned";
        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->setSubject($subject)
            ->viewBuilder()
            ->setTemplate('assignticket');
        $mailer->setViewVars(['name'=> $name, 'title'=> $title,'description' => $description]);
        $mailer->deliver();
    }

    public function sendChatResponsetoClientEmail($name,$email,$store)
    {
        $mailer = new Mailer();
        $subject = "New Chat Response Received On Your Ecom 360 Portal";
        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->setSubject($subject)
            ->viewBuilder()
            ->setTemplate('clientonboarding');
        $mailer->setViewVars(['name'=> $name, 'store'=> $store]);
        $mailer->deliver();
    }

    public function sendBrandApprovalEmail($email,$name,$product_name,$brand_name,$amount,$quantity)
    {
        $mailer = new Mailer();
        $subject = "Inventory Assigned to Your Store";
        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->setSubject($subject)
            ->viewBuilder()
            ->setTemplate('branddata');
        $mailer->setViewVars(['name'=> $name,'pr_name' => $product_name,'br_name'=>$brand_name,'qnt'=>$quantity,'amt'=>$amount]);
        $mailer->deliver();
    }

    public function sendSurveyCompleteEmail($name)
    {
        $mailer = new Mailer();
        $subject = "New Survey Received On Your Ecom 360 Portal";
        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo('vinny@wtepartners.com')
            ->addTo('portal@wtepartners.com')
            ->setSubject($subject)
            ->viewBuilder()
            ->setTemplate('surveycomplete');
        $mailer->setViewVars(['name'=> $name]);
        $mailer->deliver();
    }

    public function sendDocCompleteEmail($name)
    {
        $mailer = new Mailer();
        $subject = "New Documents Received On Your Ecom 360 Portal";
        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo('vinny@wtepartners.com')
            ->addTo('portal@wtepartners.com')
            ->setSubject($subject)
            ->viewBuilder()
            ->setTemplate('doccomplete');
        $mailer->setViewVars(['name'=> $name]);
        $mailer->deliver();
    }

    public function sendVerificationEmail($name,$email)
    {
        $mailer = new Mailer();
        $subject = "Book a Verification Call With an Onboarding Specialist";
        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->setSubject($subject)
            ->viewBuilder()
            ->setTemplate('requestverify');
        $mailer->setViewVars(['name'=> $name]);
        $mailer->deliver();
    }

    public function sendLaunchEmail($name,$email)
    {
        $mailer = new Mailer();
        $subject = "Welcome To The Ecom 360 Portal";
        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->setSubject($subject)
            ->viewBuilder()
            ->setTemplate('launchphase');
        $mailer->setViewVars(['name'=> $name]);
        $mailer->deliver();
    }

    public function sendLaunchEmailToStaff($name,$store_name)
    {
        $mailer = new Mailer();
        $subject = "Assign store group to ".$store_name;
        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo('reynaldo.santiago@focusconsultingmedia.com')
            ->setSubject($subject)
            ->viewBuilder()
            ->setTemplate('stafflaunchphase');
        $mailer->setViewVars(['name'=> $name,'store' => $store_name]);
        $mailer->deliver();
    }

    public function addCreditEmail($name,$email)
    {
        $mailer = new Mailer();
        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->setSubject("Credit Issued")
            ->viewBuilder()
            ->setTemplate('addcredit');
        $mailer->setViewVars(['name' => $name]);
        $mailer->deliver();
    }

    public function addRefundEmail($name,$email)
    {
        $mailer = new Mailer();

        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->setSubject("Refund Initiated")
            ->viewBuilder()
            ->setTemplate('addrefund');
        $mailer->setViewVars(['name' => $name]);
        $mailer->deliver();
    }

    public function assignStaffTicketEmail($fname,$lname,$email,$title,$category)
    {
        $mailer = new Mailer();

        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
            ->setTo($email)
            ->setSubject("New Ticket Assigned")
            ->viewBuilder()
            ->setTemplate('assignstaff');
        $mailer->setViewVars(['fname' => $fname,'lname' => $lname, 'title' => $title, 'category' => $category]);
        $mailer->deliver();
    }

    public function sendNotification($firebaseid,$chat,$title,$devicename)
    {   
        // if(strcasecmp($devicename, 'Ios') == 0)
        // {
        //     $textContent = strip_tags($chat);
        //     $tagRemoveChat = str_replace(' ', ' ', $textContent);

        //     $payload = [
        //         'to' => $firebaseid,
        //         'notification' => [
        //             'title' => $title,
        //             'body' => $tagRemoveChat,
        //         ],
        //         'aps' => [
        //             "sound" => "ringtoneNotification.wav",
        //             "contentAvailable" => 1,
        //         ],               
        //         'data' => [
        //             "body" => $tagRemoveChat,
        //             "title" => $title,
        //             "content_available" => true,
        //             "priority" => "high"
        //         ]
        //     ];
        // }else{

        //     $payload = [
        //         'to' => $firebaseid,
        //         'aps' => [
        //             "alert" => "Notification with custom payload!",
        //             "badge" => 1,
        //             "content-available" => 1
        //         ],
        //         'data' => [
        //             "body" => $chat,
        //             "title" => $title,
        //             "content_available" => true,
        //             "priority" => "high"
        //         ]
        //     ];
        // }
        
        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        // CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => '',
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => 'POST',
        // CURLOPT_POSTFIELDS =>json_encode($payload),
        // CURLOPT_HTTPHEADER => array(
        //     'Authorization: key=AAAArG-tQQc:APA91bHgim1WOLMDNgMI7AzBxINOt8Tbz9Yxt6YeucrA0Y4Yn347CbnpQQYxa4iuTaiPoclEnhEFqHHMdFG-hd3XXnliUQjgE5-bUe6gkxul9XZBudZlcVSNL0Oc4CkDtujge_FgAH85',
        //     'Content-Type: application/json'
        // ),
        // ));
        // $response = curl_exec($curl);
        // curl_close($curl);
        $textContent = strip_tags($chat);
        $tagRemoveChat = str_replace(' ', ' ', $textContent);
        $bearerToken = $this->getBearerToken();
        if (!$bearerToken) {
            die('Failed to obtain access token');
        }
        $payload = [
            'message' => [
                'token' => $firebaseid,
                'notification' => [
                    'title' => $title,
                    'body' => $tagRemoveChat,
                ],
                'data' => [
                    'body' => $tagRemoveChat,
                    'title' => $title,
                ],
                'android' => [
                    'priority' => 'high',
                ],
                'apns' => [
                    'headers' => [
                        'apns-priority' => '10',
                    ],
                    'payload' => [
                        'aps' => [
                            'sound' => 'ringtoneNotification.wav',
                            'content-available' => 1,
                        ]
                    ]
                ]
            ]
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fcm.googleapis.com/v1/projects/wteapp-7cab0/messages:send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $bearerToken,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }
        curl_close($curl);
    }

    public function activityLog($loginUser,$page,$task,$store_id="",$page_related_id="",$management_fee="",$previous_amount="",$previous_date="",$issue_type="",$old_value = "")
    {  
        $activityDetail = $this->ActivityLogs->newEmptyEntity();
        $activityDetail->user_id = $loginUser->id;
        $activityDetail->page = $page;
        $activityDetail->task = $task;
        if($store_id != "")
        {
           $activityDetail->store_id = $store_id;
        }
        if($page_related_id != "")
        {
           $activityDetail->page_related_id = $page_related_id;
        }
        if($management_fee != "")
        {
           $activityDetail->management_fee = $management_fee;
        }
        if($previous_amount != "")
        {
           $activityDetail->previous_amount = $previous_amount;
        }
        if($previous_date != "")
        {
            $activityDetail->previous_date = $previous_date;
        }
        if($issue_type != "")
        {
           $activityDetail->issue_type = $issue_type;
        }
        if($old_value != "")
        {
            $activityDetail->old_value = $old_value;
        }
        $this->ActivityLogs->save($activityDetail);
    }

    public function NotifyWeeklyEmail($filePath)
    {
        // dd($filePath);
        $mailer = new Mailer();
        $mailer
        ->setTransport('default')
        ->setEmailFormat('html')
        ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
        ->setTo(['dan@ecomauthority.com', 'kelsey@ecomauthority.com'])
        // ->setTo('jyotima.tripathi@actiknow.com')
        ->setSubject('Weekly Status Change Report')
        ->addAttachments([$filePath])
        ->deliver('Please find attachment of the weekly store status change report.');

    }

    public function sendStaffTicketResponsetoEmail($name, $email, $title,$sendername,$subject,$template,$ticketIdentity) {
        $mailer = new Mailer();
        $mailer->setTransport('default');
        $mailer->setEmailFormat('html')
               ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
               ->setTo($email)
               ->setSubject($subject)
               ->viewBuilder()
               ->setTemplate($template);
    
        $mailer->setViewVars(['name' => $name, 'title' => $title,'Sendername' => $sendername,'ticketId' => $ticketIdentity]);
        $mailer->deliver();
    }
    public function sendInternalTicketEmailToWatchers($email, $name,$title,$createdName) {
        $mailer = new Mailer();
        $mailer->setTransport('default');
        $mailer->setEmailFormat('html')
               ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
               ->setTo($email)
               ->setSubject('A new internal ticket has been created.')
               ->viewBuilder()
               ->setTemplate('ticketwatcher');
    
        $mailer->setViewVars(['name' => $name, 'title' => $title,'Created_name' => $createdName]);
        $mailer->deliver();
    }

    public function send2FACodeEmail($toEmail,$code,$name){
        $mailer = new Mailer();
        $mailer->setTransport('default');
        $mailer->setEmailFormat('html')
               ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
               ->setTo($toEmail)
               ->setSubject('Your 2FA Code for Ecom 360')
               ->viewBuilder()
               ->setTemplate('2facode');
               $mailer->setViewVars(['code' => $code,'name' => $name]);
               $mailer->deliver();
    }

    public function getAccountManagerClient($client_id = null)
    {
        if($client_id){
            $account_manager = $this->userTable->find()->select(['id','assign_manager'])->where(['id' => $client_id])->first();
            return $account_manager->assign_manager;
        }  
        else{
            $account_manager = $this->getManagerViaRoundRobin();
            return $account_manager;
        }
    }

    private function getManagerViaRoundRobin(){
        $manager_id = 0;
        $condition = ['Users.role_id IN' => [15,20], 'Users.delete_user' => 0,'Users.status' => 1];

        $accountManagers = $this->userTable->find()
        ->select([
            'Users.id', 'Users.first_name', 'Users.last_name',
            'full_name' => 'CONCAT(Users.first_name, " ", Users.last_name)',
            'capacity' => 'Users.store_capacity',
            'client_assign' => 'count(Client.id)',
            'Users.amazon',
            'Users.walmart'
            ])
            ->join([
            'Client' => [
                'table' => 'users',
                'type' => 'LEFT',
                'conditions' => '(Users.id = Client.assign_manager)'
            ]
        ])->where($condition)
        ->group('Users.id')
        ->order(['full_name' => 'ASC'])
        ->toArray();

        // get last assign manager
        $lastAssignManager = $this->lastAssignManagersTbl->find()->order(['id' => 'DESC'])->limit(1)->first();
        if($lastAssignManager){
            $key = $this->findKeyByValue($accountManagers, $lastAssignManager->manager_name, $lastAssignManager->manager_id);
            if ($key !== false) {
                $key = $key + 1;
                for($i = $key; count($accountManagers) > $i; $i++){
                    if($accountManagers[$i]->capacity > $accountManagers[$i]->client_assign){
                        $manager_id = $accountManagers[$i]->id;
                        $lastAssignManager = $this->lastAssignManagersTbl->newEmptyEntity();
                        $lastAssignManager->manager_id = $manager_id;
                        $lastAssignManager->manager_name = $accountManagers[$i]->full_name;
                        $this->lastAssignManagersTbl->save($lastAssignManager);
                        break;
                    }
                }
                if($manager_id === 0){
                    foreach($accountManagers as $accountManager){
                        if($accountManager->capacity > $accountManager->client_assign){
                            $manager_id = $accountManager->id;
                            $lastAssignManager = $this->lastAssignManagersTbl->newEmptyEntity();
                            $lastAssignManager->manager_id = $manager_id;
                            $lastAssignManager->manager_name = $accountManager->full_name;
                            $this->lastAssignManagersTbl->save($lastAssignManager);
                            break;
                        }
                    }
                }
            }
        }
        else{
            foreach($accountManagers as $accountManager){
                if($accountManager->capacity > $accountManager->client_assign){
                    $manager_id = $accountManager->id;
                    $lastAssignManager = $this->lastAssignManagersTbl->newEmptyEntity();
                    $lastAssignManager->manager_id = $manager_id;
                    $lastAssignManager->manager_name = $accountManager->full_name;
                    $this->lastAssignManagersTbl->save($lastAssignManager);
                    break;
                }
            }
        }

        return $manager_id;
    }

    function getBearerToken()
    {
        // Load the service account key file
        $keyFilePath = WWW_ROOT .'wteapp-7cab0-firebase-adminsdk-3x6cr-6367110d51.json'; // Replace with your JSON file path

        // Define the required scope for Firebase Cloud Messaging
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        // Create credentials using service account key file
        $credentials = new ServiceAccountCredentials($scopes, $keyFilePath);

        // Fetch the access token
        $token = $credentials->fetchAuthToken();

        // Return the Bearer token
        return isset($token['access_token']) ? $token['access_token'] : null;
    }
    
    private function findKeyByValue($array, $value, $id) {
        foreach ($array as $key => $subArray) {
            if ($value === $subArray->full_name && $id === $subArray->id) {
                return $key; // Return the index of the found sub-array
            }
        }
        return false; // Return false if not found
    }

    public function notifyWatchers($ticketId, $emailData,$template,$subject,$commenterId = null)
    {
        $watchersDetails = $this->TicketWatchersTbl->find()
            ->select([
                'watcher_id' => 'Users.id',
                'watcher_name' => 'CONCAT(Users.first_name, " ", Users.last_name)',
                'email' => 'Users.email',
            ])
            ->join([
                'Users' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'Users.id = DevelopmentTicketWatchers.watcher_id',
                ]
            ])
            ->where(['DevelopmentTicketWatchers.ticket_id' => $ticketId])
            ->toArray();

            foreach ($watchersDetails as $watcher) {
                // Exclude the commenter from notifications, if the watcher is the commenter itself.
                if ($commenterId !== null && $watcher['watcher_id'] == $commenterId) {
                    continue;
                }
               
            $emailData['toEmail'] = $watcher['email'];
            $emailData['name'] = $watcher['watcher_name'];
            
            $this->sendEmailForDevTicketUpdate($emailData,$template,$subject);
        }
    }

    public function sendEmailForDevTicketUpdate($emailData,$template,$subject){
        $mailer = new Mailer();
        $mailer->setTransport('default');
        $mailer->setEmailFormat('html')
               ->setFrom(['noreply@ecomauthority.com' => 'Ecom 360'])
               ->setTo($emailData['toEmail'])
               ->setSubject($subject)
               ->viewBuilder()
               ->setTemplate($template);
    
        $mailer->setViewVars(['name' => $emailData['name'], 'title' => $emailData['title'],'status' => $emailData['status']??'','senderName'=>$emailData['userName']??'']);
        // dd($template);
        $mailer->deliver();
    }

}