<?php
$no = $_GET['NO'];

$dsn = "mysql:host=127.0.0.1;port=3306;dbname=audtla;charset=utf8";

$pdo = new PDO($dsn, "root", "audtla12!@");
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);   //preparedStatement를 지원, false는 DB 기능 사용
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

$query = "DELETE FROM comment 
    WHERE NO=:NO";

$stmt = $pdo->prepare($query);

//sql injection 방지
$stmt->bindParam(':NO', $no, PDO::PARAM_INT);

//실행
$stmt->execute();

echo "OK";
?>