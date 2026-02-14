<?php
   $servename= "sql205.infinityfree.com";
   $user = "if0_41158582";
   $password = "tf4ySlvOTU81k4u";
   $db="if0_41158582_ngo_db";

   $conn =new mysqli($servename,$user,$password,$db);

   if($conn -> connect_error){
    die("connection failed" . $conn->connect_error);
   }else{
   //  echo"well";
   }

?>