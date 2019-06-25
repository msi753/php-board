
<?php
    $no = $_GET['NO'];
    $cnt = $_GET['cnt'];
    
    $dsn = "mysql:host=127.0.0.1;port=3306;dbname=audtla;charset=utf8";

    $pdo = new PDO($dsn, "root", "audtla12!@");
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);   //preparedStatement를 지원, false는 DB 기능 사용
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

    $query1 = "SELECT
            A.NO,
            A.WRITER,
            A.CONTENTS,
            A.DATE
        FROM comment A LEFT OUTER JOIN board B
        ON A.BOARD_NO = B.NO
        WHERE B.NO = :NO
        ORDER BY A.NO DESC
        LIMIT :cnt, 5";

    $stmt = $pdo->prepare($query1);

    //sql injection 방지
    $stmt->bindParam(':NO', $no, PDO::PARAM_INT);
    $stmt->bindParam(':cnt', $cnt, PDO::PARAM_INT);

    //실행
    $stmt->execute();

    //결과 가져오기
    $data1 = $stmt->fetchAll();

?>

<?php if(is_array($data1)):?>
            <?php foreach($data1 as $row1):?>
            <li class="list-group-item">
                <div class="float-right">
                    <a href="#" class="btn btn-danger" data-sno="<?=$row1['NO']?>">삭제</a>
                </div>
                <?=$row1['WRITER']?>
                <?=$row1['DATE']?>
                <div class="clear"></div>
                <?=$row1['CONTENTS']?>
            </li>
            <?php endforeach;?>
            <?php endif;?>

