<?php
 // Database Connection
 
 $dbhost = "127.0.0.1";
 $dbuser = "care2020_fscrmuser";
 $dbpass = "11081819@12";
 $db = "care2020_fscrm";
 $con = mysqli_connect($dbhost, $dbuser, $dbpass , $db) or die($link);
 
 // insert data in to database
 
 $currentTime = date('Y-m-d H:i:s');
 //$sql = "INSERT INTO `cron` (created_date) VALUES ('$currentTime')";
 $max_leads = 'SELECT max_limit_leads FROM phone_settings';
 if($max_leads){
    $sql = "UPDATE phone_settings SET status=0";
 }
 //$week_leads = UPDATE phone_settings whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
 //$max_leads = UPDATE phone_settings where->count();
                
 //$sql = "UPDATE phone_settings SET status='0' WHERE id=2";
 $insertData = mysqli_query($con,$sql);
?>