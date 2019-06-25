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
            echo "데이터베이스 연결 성공!!<br/>";

            switch($_GET['mode']){
                case 'insert':
                    //post방식으로 받기
                    $title = $_POST["title"]; 
                    $contents = $_POST["contents"]; 
                    $pw = $_POST["pw"];
                    $writer = $_POST["writer"];

                    // 잘 넘어왔는지 확인
                    echo "제목: ".$title;
                    echo "내용: ".$contents;
                    echo "비밀번호: ".$pw;
                    echo "글쓴이: ".$writer."<br>";

                    //ip주소 가져오기
                    $ip = $_SERVER['REMOTE_ADDR'];
                    echo "ip주소: ".$ip."<br>";

                    //첨부파일이 없을 경우
                    if($_FILES['userfile']['name']==''){

                        $query = "INSERT INTO board(
                            TITLE,
                            CONTENTS,
                            PW,
                            WRITER,
                            DATE,
                            IP) 
                        VALUES(
                            :title,
                            :contents,
                            :pw,
                            :writer,
                            now(),
                            :ip)";
                        
                        $stmt = $pdo->prepare($query);

                        $stmt->bindParam(':title',$title, PDO::PARAM_STR);
                        $stmt->bindParam(':contents',$contents, PDO::PARAM_STR);
                        $stmt->bindParam(':pw',$pw, PDO::PARAM_STR);
                        $stmt->bindParam(':writer',$_SESSION['member_id'], PDO::PARAM_STR);
                        $stmt->bindParam(':ip',$ip, PDO::PARAM_STR);
                
                        $stmt->execute();
    
                        //select 제외하고 성공여부 확인
                        //(rowCount는 가장 최근의 delete, insert, update에 대한 리턴값만 반환하기 때문)
                        $count = $stmt->rowCount();
                        if($count>0){
                            echo "성공";
                        } else { 
                            echo "실패";
                        }

                    //첨부파일이 있을 때
                    } else {
                        //-------------------------------------- 파일 업로드 --------------------------------------
                        //var_dump($_FILES); 하면 업로드한 파일에 대한 정보를 알 수 있다
                        $uploadOk = 1;  //업로드 성공 여부 변수(1: 성공)
                        ini_set("display_errors", "1");     //에러 보여주기 php.ini파일 수정하는 개념

                        $uploaddir = '/myeongsim/www/uploads/';
                        $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
                        echo basename($_FILES['userfile']['name']);

                        //특정 파일의 형식만 허용
                        $imageFileType = strtolower(pathinfo($uploadfile,PATHINFO_EXTENSION));
                        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                            echo '<script>alert(\'파일을 업로드할 수 없습니다(jpg, jpeg, png, gif 형식의 파일만 업로드 가능))\');</script>'; 
                            $uploadOk = 0;
                        }

                        //같은 이름의 파일이 존재하는지 확인
                        if(file_exists($uploadfile)) {
                            echo "<script>alert('같은 이름의 파일이 이미 존재합니다.".$uploadfile."');</script>";
                            $uploadOk = 0;
                        }


                        if ($uploadOk == 0) {   
                            echo "<script>history.back();</script>";
                            exit;
                        } else {
                            echo '<pre>';
                            //move_uploaded_file(파일의 경로, 파일의 이동 경로) 임시디렉터리에서 파일디렉터리로 이동
                            if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $uploadfile)) {
                                $image_name = $_FILES["userfile"]["name"];
                                $path = "http://audtla.com/uploads/". $_FILES["userfile"]["name"]; 
                                $size = $_FILES["userfile"]["size"];
                        
                                echo '자세한 디버깅 정보입니다:';
                                print_r($_FILES);
                                
                                print "</pre>";

                                $query = "INSERT INTO image(
                                    IMAGE_NAME,
                                    PATH,
                                    SIZE,
                                    REG_TIME) 
                                VALUES(
                                    :image_name,
                                    :path,
                                    :size,
                                    now())";
            
                                $stmt = $pdo->prepare($query);        
                                
                                //sql injection 방지
                                $stmt->bindParam(':image_name',$image_name, PDO::PARAM_STR);
                                $stmt->bindParam(':path',$path, PDO::PARAM_STR);
                                $stmt->bindParam(':size',$size, PDO::PARAM_INT);
                        
                                $stmt->execute(); 
                                
                                //마지막 인덱스 가져오기 PDO::lastInsertId                       
                                $image_id = $pdo->lastInsertId(); 
                            
                                //board테이블에 image_id가 동일한 행 추가
                                $query = "INSERT INTO board(
                                        TITLE,
                                        CONTENTS,
                                        PW,
                                        WRITER,
                                        IP,
                                        IMAGE_ID,
                                        DATE)
                                    VALUES(
                                        :title,
                                        :contents,
                                        :pw,
                                        :writer,
                                        :ip,
                                        :image_id,
                                        now())";
                                    
                                $stmt = $pdo->prepare($query);        

                                $stmt->bindParam(':title',$title, PDO::PARAM_STR);
                                $stmt->bindParam(':contents',$contents, PDO::PARAM_STR);
                                $stmt->bindParam(':pw',$pw, PDO::PARAM_STR);
                                $stmt->bindParam(':writer',$_SESSION['member_id'], PDO::PARAM_STR);
                                $stmt->bindParam(':ip',$ip, PDO::PARAM_STR);
                                $stmt->bindParam(':image_id',$image_id, PDO::PARAM_INT);
                        
                                $stmt->execute(); 

                                echo "<p>파일 ". basename( $_FILES["userfile"]["name"]). " 이(가) 업로드 되었습니다.</p>";
                                echo "<br><img src=/uploads/". basename( $_FILES["userfile"]["name"]). " width=400>";
                                echo "<br><button type='button' onclick='history.back()'>돌아가기</button>";
                            } else {
                                echo "<p>파일 업로드 실패</p>";
                                echo "<br><button type='button' onclick='history.back()'>돌아가기</button>";
                                exit;
                            }
                        }                    
                        //-------------------------------------- 파일 업로드 --------------------------------------
                    }

                    //리다이렉트
                    header("Location: list_board.php"); 
       
                    break;
                    
                case 'delete':

                    //post방식으로 받기
                    $no = $_POST['no'];
                    $pw = $_POST['pw'];

                    $query = "SELECT 
                                COUNT(*) AS total
                            FROM board
                            WHERE NO = :no
                            AND PW=:pw";
                    $stmt = $pdo->prepare($query); 
                    $stmt->bindParam(':no',$no);
                    $stmt->bindParam(':pw',$pw);
                    $stmt->execute();
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);

                    if($data['total']>0) {
                        $query = "DELETE 
                        FROM board 
                        WHERE NO = :no";

                        $stmt = $pdo->prepare($query);        
                        
                        //sql injection 방지
                        $stmt->bindParam(':no',$no);
                
                        $stmt->execute();
                    } else {
                        echo "비밀번호 오류";
                        echo "<script>alert('비밀번호오류');
                            history.back(); </script>";
                            exit;
                    }

                    //select 제외하고 성공여부 확인
                    //(rowCount는 가장 최근의 delete, insert, update에 대한 리턴값만 반환하기 때문)
                    $count = $stmt->rowCount();
                    if($count>0){
                        echo "성공";
                    } else { 
                        echo "실패";
                    }
                    
                    //리다이렉트
                    header("Location: list_board.php"); 
            
                    break;  
                
                case 'modify':
 
                    //기존 첨부파일 이름 받아오기
                    $image_name_old = $_POST["image_name_old"]; 
                    //새로운 첨부파일 이름 받아오기
                    $image_name = $_FILES['userfile']['name']; 

                    //post방식으로 받기
                    $title = $_POST["title"]; 
                    $contents = $_POST["contents"]; 
                    $writer = $_POST["writer"];
                    $no = $_POST['no'];

                    //잘 넘어왔는지 확인
                    print_r($_POST);
                    print_r($_FILES);

                    //첨부파일 변경하지 않을 때
                    if($image_name=='') {
                        $query = "UPDATE board 
                                SET 
                                    title=:title,
                                    contents=:contents,
                                    writer=:writer 
                                WHERE NO=:no";
                        //쿼리문준비
                        $stmt = $pdo->prepare($query);                
                        //sql injection 방지
                        $stmt->bindParam(':title',$title, PDO::PARAM_STR);
                        $stmt->bindParam(':contents',$contents, PDO::PARAM_STR);
                        $stmt->bindParam(':writer',$_SESSION['member_id'], PDO::PARAM_STR);
                        $stmt->bindParam(':no',$no, PDO::PARAM_INT);
                        //실행
                        $stmt->execute();
                    //첨부파일 변경할 때
                    } else {
                        //원래 있던 파일을 DB에서 삭제한다
                        $query = "DELETE FROM image 
                                WHERE IMAGE_NAME=:image_name_old";
                        $stmt = $pdo->prepare($query); 
                        $stmt->bindParam(':image_name_old',$image_name_old, PDO::PARAM_STR);
                        $stmt->execute();
                        //파일 삭제
                        unlink('uploads/'.$image_name_old);

                        //-------------------------------------- 파일 업로드 --------------------------------------
                        //var_dump($_FILES); 하면 업로드한 파일에 대한 정보를 알 수 있다
                        $uploadOk = 1;  //업로드 성공 여부 변수(1: 성공)
                        ini_set("display_errors", "1");     //에러 보여주기 php.ini파일 수정하는 개념

                        $uploaddir = 'D:\myeongsim\www\uploads\\';
                        $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

                        //특정 파일의 형식만 허용
                        $imageFileType = strtolower(pathinfo($uploadfile,PATHINFO_EXTENSION));
                        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                            echo '<script>alert(\'파일을 업로드할 수 없습니다(jpg, jpeg, png, gif 형식의 파일만 업로드 가능))\');</script>'; 
                            $uploadOk = 0;
                        }

                        //같은 이름의 파일이 존재하는지 확인
                        if(file_exists($uploadfile)) {
                            echo "<script>alert('같은 이름의 파일이 이미 존재합니다.');</script>";
                            $uploadOk = 0;
                        }

                        if ($uploadOk == 0) {   
                            echo "<script>history.back();</script>";
                            exit;
                        } else {
                            echo '<pre>';
                            //move_uploaded_file(파일의 경로, 파일의 이동 경로) 임시디렉터리에서 파일디렉터리로 이동
                            if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $uploadfile)) {
                                $image_name = $_FILES["userfile"]["name"];
                                $path = "http://audtla.com/uploads/". $_FILES["userfile"]["name"]; 
                                $size = $_FILES["userfile"]["size"];
                        
                                echo '자세한 디버깅 정보입니다:';
                                print_r($_FILES);
                                
                                print "</pre>";

                                $query = "INSERT INTO image(
                                        IMAGE_NAME,
                                        PATH,
                                        SIZE,
                                        REG_TIME) 
                                    VALUES(
                                        :image_name,
                                        :path,
                                        :size,
                                        now())";
            
                                $stmt = $pdo->prepare($query);        
                                
                                //sql injection 방지
                                $stmt->bindParam(':image_name',$image_name, PDO::PARAM_STR);
                                $stmt->bindParam(':path',$path, PDO::PARAM_STR);
                                $stmt->bindParam(':size',$size, PDO::PARAM_INT);
                        
                                $stmt->execute(); 
                                
                                //마지막 인덱스 가져오기 PDO::lastInsertId                       
                                $image_id = $pdo->lastInsertId(); 
                            
                                //board테이블 image_id 새롭게 업데이트하기
                                $query = "UPDATE board 
                                     SET IMAGE_ID=:IMAGE_ID
                                     WHERE NO=:NO";
                                $stmt = $pdo->prepare($query);        
                                $stmt->bindParam(':IMAGE_ID',$image_id, PDO::PARAM_INT);
                                $stmt->bindParam(':NO',$no, PDO::PARAM_INT);
                                $stmt->execute(); 

                                echo "<p>파일 ". basename( $_FILES["userfile"]["name"]). " 이(가) 업로드 되었습니다.</p>";
                                echo "<br><img src=/uploads/". basename( $_FILES["userfile"]["name"]). " width=400>";
                                echo "<br><button type='button' onclick='history.back()'>돌아가기</button>";
                            } else {
                                echo "<p>파일 업로드 실패</p>";
                                echo "<br><button type='button' onclick='history.back()'>돌아가기</button>";
                                exit;
                            }
                        }                    
                        //-------------------------------------- 파일 업로드 --------------------------------------

                    }
                    
                    //select 제외하고 성공여부 확인
                    //(rowCount는 가장 최근의 delete, insert, update에 대한 리턴값만 반환하기 때문)
                    $count = $stmt->rowCount();
                    if($count>0){
                        echo "성공";
                    } else { 
                        echo "실패";
                    }

                    //리다이렉트
                    header("Location: list_board.php?no={$_POST['no']}"); 
            
                    break;  
            }
            
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
        
?>