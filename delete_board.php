<?php
    include_once 'config.php';

    //글 번호 
    $no = $_GET['no'];
    echo "글 번호: ".$no."<br>";
?>
<!DOCTYPE html>
<html lang="en">  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <title>게시글 삭제 화면</title>

</head>
<body>
    <div class="container">
        <h1>삭제하시겠습니까?</h1>
        <p>삭제하시려면 비밀번호를 입력해주세요.</p>
        <form action="/board_action.php?mode=delete" method="post" onsubmit="return pw_ck();">
            <input type="hidden" name="no" value="<?=$no?>">
            <input type="password" name="pw" >
            <p id="demo"></p>
            <button type="submit" class="btn btn-primary">삭제</button>
        </form>
        <a href="/view_board.php?no=<?=$no?>" class="btn btn-primary" role="button">뷰로 돌아가기</a>
    </div>
</body>
</html>