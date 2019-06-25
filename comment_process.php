<?php
header("Content-Type:application/json");

    //session추가
    include_once 'config.php';

    //db연결
    include_once 'db_driver.php';

    //post방식으로 받기
    $board_no = $_GET['board_no'];
    $contents = $_GET['comment']; 
    $writer = $_SESSION['member_id']; 

    // 잘 넘어왔는지 확인
    print_r($_GET);
    print_r($writer);

    //commet댓글 테이블에 내용 추가
    $query = "INSERT INTO comment(
            BOARD_NO,
            CONTENTS,
            WRITER,
            DATE) 
        VALUES(
            :board_no,
            :contents,
            :writer,
            now())";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(':board_no',$board_no, PDO::PARAM_INT);
    $stmt->bindParam(':contents',$contents, PDO::PARAM_STR);
    $stmt->bindParam(':writer',$writer, PDO::PARAM_STR);

    $stmt->execute();
    
    //select 제외하고 성공여부 확인
    //(rowCount는 가장 최근의 delete, insert, update에 대한 리턴값만 반환하기 때문)
    $count = $stmt->rowCount();
    if($count>0){
        echo "성공";
        //json_encode()에 넣어야지
        

    } else { 
        echo "실패";
    }

    //총 댓글 수 board테이블에 추가
    $query = "UPDATE board
            SET COMMENT_CNT=(SELECT COUNT(*) 
                            FROM comment 
                            WHERE BOARD_NO=$board_no)
            WHERE NO=$board_no";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':board_no',$board_no, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->rowCount();
    if($count>0){
        echo "성공";
    } else { 
        echo "실패";
    }

    //리다이렉션
    header("Location: view_board.1.php?no=$board_no"); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>댓글 처리 화면</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    
</body>
</html>