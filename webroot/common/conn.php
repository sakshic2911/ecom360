<?php

$conn = new mysqli('localhost', 'root', 'root', 'chatbot');
/* if(!isset($_SESSION['session_list']) || empty($_SESSION['session_list']))
  {
  $_SESSION['session_list']   =   session_id();
  }
 */

if (!isset($_SESSION['settings'])) {
    if ($settings = $conn->query("SELECT * FROM account_settings")) {
        $r = $settings->fetch_array(MYSQLI_ASSOC);
        $_SESSION['settings'] = $r;
    }
}
//unset($_SESSION['settings']);
?>