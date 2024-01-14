<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    include_once '../templates/dbframe.php';
    header('Content-Type: application/json');
    $type = $_POST['type'];
    $order = $_POST['order'];
    $page = $_POST['page'];
    if(!isset($type) || !isset($order) || !isset($page)) {
        echo json_encode(array("type"=>"error", "message"=>"Required parameter missing."));
        exit();
    } else {
        if(!(1<=$order && $order<=3)) {
            echo json_encode(array("type"=>"error", "message"=>"out of range."));
            exit();
        } else {
            $stmt = $mysqli->prepare("SELECT id from bae_board order by id desc limit 1");
            if($stmt) {
                $stmt->execute();
                $stmt->bind_result($max_id);
                $stmt->fetch();
                $stmt->close();
            } else {
                echo '<script>
                alert("db error1");
                history.go(-1);
                </script>';
                exit();
            }
            $max_offset = floor($max_id/10);
            if($page < 0 || $max_offset < $page ) {
                echo json_encode(array("type"=>"error", "message"=>"Invalid Data."));
                exit();
            }
            if($order != 1 && $order != 2 && $order != 3) {
                echo json_encode(array("type"=>"error", "message"=>"Invalid Data."));
                exit();
            }
            $content_data = array();
            if($order == 1) {
                $stmt = $mysqli->prepare("SELECT id, title, view, created_at from bae_board order by id desc limit 10 offset ?");
            } else if($order == 2) {
                $stmt = $mysqli->prepare("SELECT id, title, view, created_at from bae_board order by id asc limit 10 offset ?");
            } else if($order == 3) {
                $stmt = $mysqli->prepare("SELECT id, title, view, created_at from bae_board order by view desc limit 10 offset ?");
            }
            if($stmt) {
                $stmt->bind_param("s", $page);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($id, $title, $view, $created_at);
                while($stmt->fetch()) { 
                    array_push($content_data, array('id'=>$id, 'title'=>htmlspecialchars($title), 'view'=>$view, 'created_at'=>$created_at));
                }
                $stmt->free_result();
                $stmt->close();
                echo json_encode(array("type"=>"success", "data"=>$content_data));
            } else {
                echo json_encode(array("type"=>"error", "message"=>"DB error"));
                exit();
            }
        }
    }
?>