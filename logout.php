<?php
    ini_set("display_errors", "1");
    session_start();    
    session_destroy();  //세션 사용 종료
    header('Location: ./login.php');
?>