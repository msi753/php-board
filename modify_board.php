<?php
    include_once 'config.php';

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

            //글 번호 
            $no = $_GET['no'];
            echo "글 번호: ".$no."<br>";
                        
            $query = "SELECT
                        NO,
                        TITLE,
                        CONTENTS,
                        WRITER,
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
    <title>수정 화면</title>


    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> 

    <!-- jQuery를 사용하기 위해 cdn 추가 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <script>
	//유효성 검사(공백(spacebar)에 대한 내용 추가)
	$(document).ready(function(){
		$('#modifyBtn').click(function(){	
			$('#modifyBoard').submit(function(event){
                var pwPattern = /^.*(?=.{6,20})(?=.*[0-9])(?=.*[a-zA-Z]).*$/;
				if($.trim($('#title').val()).length<1) {
					$('#title').attr('placeholder', '한 글자 이상 입력해 주세요.').val('').focus();
					return false;	
				} else if($.trim($('#contents').val()).length<1) {
					$('#contents').attr('placeholder', '한 글자 이상 입력해 주세요.').val('').focus();
					return false;
				} else if($.trim($('#writer').val()) == '') {
					$('#writer').attr('placeholder', '한 글자 이상 입력해 주세요.').val('').focus();
					return false;
				}
			});
		});
	});
    </script>

</head>
<body>
    <div class="container">
    <h1>게시글 수정</h1>
        <form action="/board_action.php?mode=modify" method="post" id="modifyBoard" enctype="multipart/form-data">
            <input type="hidden" name="no" value="<?=$data['NO']?>" /> 
            <div class="form-group">
                글 제목:
                <input type="text" name="title" value="<?=htmlspecialchars($data['TITLE'])?>" id="title" class="form-control">
            </div>
            <div class="form-group">
                내용:
                <textarea name="contents" rows="15" id="contents" class="form-control"><?=htmlspecialchars($data['CONTENTS'])?></textarea>
            </div>
            <div class="form-group">
                글쓴이:
                <input type="text" name="writer" id="writer" value="<?=htmlspecialchars($data['WRITER'])?>" class="form-control">
            </div>
            <div class="form-group">
                이미지:
                <input type="hidden" name="image_name_old" value="<?=$data['IMAGE_NAME']?>">
                <img src=<?=$data['PATH']?> width=200><br>
                파일이름: <?=$data['IMAGE_NAME']?>
                <input type="file" name="userfile" class="form-control" id="modifyImg"/>
            </div>
            <button type="submit" class="btn btn-primary" id="modifyBtn">글 수정</button>
        </form>
        <a href="/list_board.php" class="btn btn-primary" role="button">게시글 목록</a>
    </div>
</body>
</html>