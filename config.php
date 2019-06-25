<?php
    //세션 사용 시작
    session_start();
    //세션에 값이 없으면 로그인 화면으로 리다이렉션
    if(!$_SESSION['member_id']){   
        header('Location: ./login.php');
    }
    echo $_SESSION['member_id']."님 환영합니다^0^/&nbsp;";
    echo "<a href='./logout.php'><button type='button' class='btn btn-success'>로그아웃</button></a><br>";
?>