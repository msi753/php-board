<?php
    header("cache-control: no-cache");
    header("Content-Type: text/html; charset=UTF-8");
    
    include_once 'config.php';
    
    //select결과가 담길 변수
    $data = null;
    $pagerow = 5;
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

            //검색 변수 받아오기
            $category = $_GET["category"];
            $keyword = $_GET["keyword"];
            $current_page = $_GET['current_page'];
            echo "검색 기준: ".$category;
            echo ", 검색어: ".$keyword."<br>";

            //쿼리문
            if($category=="TITLE"||$category=="CONTENTS") {
                //총 개수 구하기
                $sql = "SELECT count(*) as total_cnt
                FROM board 
                WHERE $category LIKE ?";
                $stmt = $pdo->prepare($sql);        
                $stmt->bindValue(1, "%$keyword%");
                $stmt->execute();
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                $total_cnt = $data['total_cnt'];
            } else {
                //총 개수 구하기
                $sql = "SELECT count(*) as total_cnt
                FROM board";
                $stmt = $pdo->prepare($sql);      
                $stmt->execute();
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                $total_cnt = $data['total_cnt'];
            }  

            //-------------------- 페이징 --------------------
            include "./paging.php";
            $add_para = $_SERVER['PHP_SELF']."?current_page=";
            $Page = new Pages($current_page,$total_cnt,$pagerow,5,$add_para);	//현재페이지,전체게시물수,세로,가로,링크값
            //$Page->Print_Page()	//게시물출력
            $limit = $Page->start_num();

            if($category=="TITLE"||$category=="CONTENTS") {
                
                $sql = "SELECT *
                FROM board 
                WHERE $category LIKE ? 
                ORDER BY date DESC
                LIMIT $limit, $pagerow";

                $stmt = $pdo->prepare($sql);        
                $stmt->bindValue(1, "%$keyword%");
                                        
                //실행
                $stmt->execute();

                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo "총 기사 수: ".count($data)."<br>";
            } else {
                $sql = "SELECT *
                FROM board 
                ORDER BY date DESC
                LIMIT $limit, $pagerow"; 

                $stmt = $pdo->prepare($sql);

                //실행
                $stmt->execute();

                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);  //fetch는 하나만 가져올 때 사용
                echo "총 기사 수: ".count($data)."<br>";
            }


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
    <!-- 아이콘 -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" 
    integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <!-- jQuery를 사용하기 위해 cdn 추가 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <title>boardList</title>

    <script>
        function search_ck(){
            //무슨 내용을 추가해야되지?
            return false;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>게시글 목록</h1>

        <!-- 내비게이션 바 -->
        <nav class="navbar navbar-expand-sm bg-primary navbar-dark">
            <!-- 브랜드/로고 -->
            <a class="navbar-brand" href="/list_board.php">
                <img src="logo.png" alt="logo" style="width:100px;">
            </a>           
            <ul class="navbar-nav">
                <li class="nav-item">
                <a class="nav-link" href="/map.php">지도</a>
                </li>
            </ul>
        </nav> 
        <br>
        <form action="/list_board.php" method="get"> <!-- onsubmit="return search_ck();" onsubmit 유효성검사 추가해야하는데 뭘 추가해야하지? -->
            <select name="category">
                <option value="TITLE">제목</option>
                <option value="CONTENTS">내용</option>
            </select>
            <input type="text" name="keyword" value="" title="검색">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>      
        </form>  
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
            <?php if(count($data)>0):?>
            <?php foreach($data as $row):?>
                <td><?=$row['NO']?></td>
                <td><a href="/view_board.1.php?no=<?=$row['NO']?>"><?=htmlspecialchars($row['TITLE'])?></a>[<?=($row['COMMENT_CNT'])?>]</td>
                <td><?=htmlspecialchars($row['CONTENTS'])?></td>
                <td><?=htmlspecialchars($row['PW'])?></td>
                <td><?=htmlspecialchars($row['WRITER'])?></td>
                <td><?=$row['DATE']?></td>
                <td><?=$row['IP']?></td>
                <td><?=$row['HIT']?></td>   
            </tr>
            <?php endforeach;?>
            <?php endif;?>
        </table>     
            <ul class="pagination">
            <?=$Page->Print_Page()?>
            </ul>
        <div>
            <a href="/add_board.php" class="btn btn-primary" role="button">게시글 입력</a>
        <div>    

    </div>


</body>
</html>