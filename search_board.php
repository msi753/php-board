<?php
header("cache-control: no-cache");
header("Content-Type: text/html; charset=UTF-8");
  
//DB연결
$dsn = "mysql:host=127.0.0.1;port=3306;dbname=audtla;charset=utf8";
try {
        $option = array(
            PDO::MYSQL_ATTR_FOUND_ROWS => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION    //에러출력 옵션 : 에러출력
        );

        $pdo = new PDO($dsn, "root", "audtla12!@", $option);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);   //preparedStatement를 지원, false는 DB 기능 사용
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //PDO가 에러를 처리하는 방식
        echo "데이터베이스 연결 성공!!<br/>";
        
        //검색 변수 확인
        $category = $_GET["category"];
        $keyword = $_GET["keyword"];
        echo "검색 기준: ".$category;
        echo ", 검색어: ".$keyword;

        $query = "SELECT *
            FROM board 
            WHERE $category LIKE ?";

        $stmt = $pdo->prepare($query);        
        $stmt->bindValue(1, "%$keyword%");

        //실행
        $stmt->execute();

        //결과 가져오기
        $results=$stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "결과값 ".count($results)."개 있음";
        
    } catch(PDOException $e) {
        echo $e->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">    

    <title>검색</title>
</head>
<body>
    <div class="container">
        <table class="table table-striped">   
            <tr>
                <td>NO</td>
                <td>제목</td>
                <td>내용</td>
                <td>비밀번호</td>
                <td>글쓴이</td>
                <td>날짜</td>
                <td>IP</td>
                <td>조회수</td>
            </tr>               
            <tr>
            <?php if(count($results)>0):?>
            <?php foreach($results as $row):?>
                <td><?=$row['NO']?></td>
                <td><a href="/view_board.php?no=<?=$row['NO']?>"><?=$row['TITLE']?></a></td>
                <td><?=$row['CONTENTS']?></td>
                <td><?=$row['PW']?></td>
                <td><?=$row['WRITER']?></td>
                <td><?=$row['DATE']?></td>
                <td><?=$row['IP']?></td>
                <td><?=$row['HIT']?></td>   
            </tr>
            <?php endforeach;?>
            <?php endif;?>
        </table>   
    </div>          
</body>
</html>



