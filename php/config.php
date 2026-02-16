<?php
   $servename= "localhost";
   $user = "root";
   $password = "";
   $db="ngo_db";

   $conn =new mysqli($servename,$user,$password,$db);

   if($conn -> connect_error){
    die("connection failed" . $conn->connect_error);
   }else{
   //  echo"well";
   }

?>