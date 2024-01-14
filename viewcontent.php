<?php
    include_once 'templates/functions.php';
    $id = $_GET['id'];
    if(isset($id)) {
        $stmt = $mysqli->prepare("SELECT title, content, view, created_at from bae_board where id=?");
        if($stmt) {
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $stmt->bind_result($title, $content, $view, $created_at);
            $stmt->fetch();
            $stmt->close();
            if($title !== null) {
                $stmt = $mysqli->prepare("UPDATE bae_board set view=view+1 where id=?");
                if($stmt) {
                    $stmt->bind_param("s", $id);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    echo '<script>
                        alert("db error");
                        history.go(-1);
                    </script>';
                    exit();
                }
            }
        } else {
            echo '<script>
                alert("db error");
                history.go(-1);
            </script>';
            exit();
        }
    }
    
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAE Board Web Site</title>
    <?php include_once 'templates/head.php'; ?>
</head>
<body>
    <?php include_once 'templates/header.php'; ?>
    <main role="main">
        <div class="container">
            <?php title('View a content'); ?>
        </div>
        <div class="container">
            <?php if($title == null) { ?>
                <div class="alert alert-danger" role="alert">
                    게시글이 없습니다.
                </div>
            <?php } else { ?>
                <div class="float-start"> 
                    <img src="images/user.png" style="height: 48px;border-radius: 8px;">
                </div>
                <div class="d-flex">
                    <div class="d-grid">
                        <b>&nbsp;ananymous&nbsp;</b>
                        <?php $mobile_agent = "/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Chrome|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/";
                        if(!preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])){ ?><br><?php } ?>
                        <span style="color:gray">&nbsp;<?=$created_at?></span>
                    </div>
                </div>
                <p style="word-break:break-all;">
                    <br>
                    <b><?=nl2br(htmlspecialchars($title))?></b>
                    <br>
                    <br><?=nl2br(htmlspecialchars($content))?><br><br>
                </p>
                <span>viewed: <?=$view+1?></span>
            <?php } ?>
        </div>
    </main>
    <?php include_once 'templates/footer.php'; ?>
</body>
</html>