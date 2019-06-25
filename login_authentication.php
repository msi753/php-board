<?php
session_start();

//print_r($_POST);
$MEMBER_ID = $_POST['id'];
$MEMBER_PW = $_POST['pw'];

//DB연결
$dsn = "mysql:host=127.0.0.1;port=3306;dbname=audtla;charset=utf8";

    $pdo = new PDO($dsn, "root", "audtla12!@");
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);   //preparedStatement를 지원, false는 DB 기능 사용
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //PDO가 에러를 처리하는 방식
    echo "데이터베이스 연결 성공!!<br/>";

    $query = "SELECT 1 AS OK
        FROM member
        WHERE MEMBER_ID=:MEMBER_ID
        AND MEMBER_PW = :MEMBER_PW";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':MEMBER_ID',$MEMBER_ID, PDO::PARAM_STR);
    $stmt->bindParam(':MEMBER_PW',$MEMBER_PW, PDO::PARAM_STR);

    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if($data['OK']){
        $_SESSION['member_id'] = $MEMBER_ID;
        header('Location: ./list_board.php');
    }else{
        echo "<script>alert('로그인 실패!');</script>";
        echo "<script>history.back();</script>";
    }

?>