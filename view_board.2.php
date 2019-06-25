<?php
header("cache-control: no-cache");
header("Content-Type: text/html; charset=UTF-8");

include_once 'config.php';

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
            //echo "데이터베이스 연결 성공!!<br/>";

            //글 번호
            $no = $_GET['no'];
            echo "글 번호: ".$no."<br>";

            $query = "SELECT
                        NO,
                        HIT,
                        IP,
                        TITLE,
                        CONTENTS,
                        WRITER,
                        DATE,
                        IMAGE_NAME,
                        PATH
                    FROM board A LEFT OUTER JOIN image B
                    ON A.IMAGE_ID = B.IMAGE_ID
                    WHERE NO = :NO";

            $stmt = $pdo->prepare($query);

            //sql injection 방지
            $stmt->bindParam(':NO', $no, PDO::PARAM_INT);

            //실행
            $stmt->execute();

            //결과 가져오기
            $data = $stmt->fetch();

            //조회수 증가
            $query="UPDATE board SET hit=hit+1 WHERE NO=:NO";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':NO', $no, PDO::PARAM_INT);
            $stmt->execute();

            //select 제외하고 성공여부 확인
            //(rowCount는 가장 최근의 delete, insert, update에 대한 리턴값만 반환하기 때문)
            $count = $stmt->rowCount();
            if($count>0){
                echo "성공";
            } else {
                echo "실패";
            }

            //댓글 가져오기
            $query1 = "SELECT
                        A.WRITER,
                        A.CONTENTS,
                        A.DATE
                    FROM comment A LEFT OUTER JOIN board B
                    ON A.BOARD_NO = B.NO
                    WHERE B.NO = :NO";
            $stmt1 = $pdo->prepare($query1);
            $stmt1->bindParam(':NO', $no, PDO::PARAM_INT);
            $stmt1->execute();            
            $data1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
            //print_r($data1)."데이터1";

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
    <title>뷰 화면</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- jQuery를 사용하기 위해 cdn 추가 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- 페이스북 공유하기 -->
    <meta property="og:url"           content="/view_board.php?no=<?=$row['NO']?>" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="Your Website Title" />
    <meta property="og:description"   content="Your description" />
    <meta property="og:image"         content="<?=$data['PATH']?>" /> 

    <script>

    </script>

</head>
<body>
    <div class="container">
        <h1>게시글 뷰</h1>
        
        <!-- 뷰 상세보기 -->
        <table class="table">
            <tr>
                <th>NO: </th>
                <td><?=$data['NO']?></td>
            <tr>
                <th>조회수: </th>
                <td><?=$data['HIT']?></td>
            </tr>
            <tr>
                <th>IP: </th>
                <td><?=$data['IP']?></td>
            </tr>
            <tr>
                <th>제목: </th>
                <td><?=htmlspecialchars($data['TITLE'])?></td>
            </tr>
            <tr>
                <th>내용: </th>
                <td><?=htmlspecialchars($data['CONTENTS'])?></td>
            </tr>
            <tr>
                <th>글쓴이: </th>
                <td><?=htmlspecialchars($data['WRITER'])?></td>
            </tr>
            <tr>
                <th>날짜: </th>
                <td><?=$data['DATE']?></td>
            </tr>
            <tr>
                <th>이미지: </th>
                <td><img src=<?=$data['PATH']?> width=200><br>
                <?=$data['IMAGE_NAME']?></td>
            </tr> 
        </table>

        <!-- 댓글 폼 -->
        <div class="jumbotron">

        <ul class="list-group">
            <?php if(is_array($data1)):?>
            <?php foreach($data1 as $row1):?>
            <li class="list-group-item">
                <div class="float-right">
                    <a href="#" class="btn btn-danger" data-sno="2156">삭제</a>
                </div>
                <?=$row1['WRITER']?>
                <?=$row1['DATE']?>
                <div class="clear"></div>
                <?=$row1['CONTENTS']?>
            </li>
            <?php endforeach;?>
            <?php endif;?>

        </ul>

        <br>
            <form action="/comment_process.php" method="get">
                <input type="hidden" name="board_no" value="<?=$data['NO']?>">
                <textarea name="comment" class="form-control"></textarea>
                <input type="submit" class="btn btn-primary" value="댓글 저장">
            </form>
        </div>

        <a href="/list_board.php" class="btn btn-primary" role="button">목록으로 돌아가기</a>
        <a href="/modify_board.php?no=<?=$data['NO']?>" class="btn btn-primary" role="button">수정</a>
        <a href="/delete_board.php?no=<?=$data['NO']?>" class="btn btn-primary">삭제</a>

        <!-- JavaScript를 위한 Facebook SDK 불러오기  -->
        <div id="fb-root"></div>
        <script>
            function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "https://connect.facebook.net/ko_KR/sdk.js#xfbml=1&version=v3.0";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        
        <br>
        <!-- 좋아요 버튼 -->
        <div class="fb-like" 
            data-href="/view_board.php?no=<?=$row['NO']?>"
            data-layout="standard" 
            data-action="like" 
            data-show-faces="true"
            data-share="true">
        </div>

    </div>
</body>
</html>