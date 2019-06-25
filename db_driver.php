<?php
 //DB연결
 $dsn = "mysql:host=127.0.0.1;port=3306;dbname=audtla;charset=utf8";

$option = array(
    PDO::MYSQL_ATTR_FOUND_ROWS => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION    //에러출력 옵션 : 에러출력
);

$pdo = new PDO($dsn, "root", "audtla12!@", $option);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);   //preparedStatement를 지원, false는 DB 기능 사용
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //PDO가 에러를 처리하는 방식
echo "데이터베이스 연결 성공!!<br/>";
?>