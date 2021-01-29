<?php
  session_start();
      
  session_destroy();
  
  header("Location: indice.php");
    
  exit();
?>
