<?php
    include_once '../templates/dbframe.php';

    $title = $_POST['title'];
    $content = $_POST['content'];

    if(!isset($title) || $title == '' || !isset($content) || $content == '') {
        echo '<script>
            alert("Please fill in the values");
            history.go(-1);
        </script>';
        exit();
    }

    $stmt = $mysqli->prepare("INSERT into bae_board (title, content, view, created_at) values (?, ?, 0, now())");
    if($stmt) {
        $stmt->bind_param("ss", $title, $content);
        $stmt->execute();
        $stmt->close();
    } else {
        echo '<script>
            alert("db error");
            history.go(-1);
        </script>';
        exit();
    }
    $stmt = $mysqli->prepare("SELECT max(id) from bae_board order by id desc limit 1");
    if($stmt) {
        $stmt->execute();
        $stmt->bind_result($id);
        $stmt->fetch();
        $stmt->close();
    } else {
        echo '<script>
            alert("db error");
            history.go(-1);
        </script>';
        exit();
    }
?>
<script>
    alert("Post registration has been completed.");
    location.href="../viewcontent.php?id=<?=$id?>";
</script>