<?php
    include_once 'templates/functions.php';
    $content_data = array();
    $page = $_GET['page'];
    $order = $_GET['order'];

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

    if(!isset($page) || $page < 0 || $max_offset < $page ) $page = 0;
    if(!isset($order) || ($order != 1 && $order != 2 && $order != 3)) $order = 1;

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
            array_push($content_data, array('id'=>$id, 'title'=>$title, 'view'=>$view, 'created_at'=>$created_at));
        }
        $stmt->free_result();
        $stmt->close();
    } else {
        echo '<script>
        alert("db error1");
        history.go(-1);
        </script>';
        exit();
    }
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAE Board Web Site</title>
    <?php include_once 'templates/head.php'; ?>
    <style>
        a.page_num {
            cursor: pointer;
        }
        a.active {
            pointer-events: none;
            cursor: default;
            text-decoration: none;
        }
    </style>
    <script>
        let page = <?=$page?>;
        let order = <?=$order?>;
        if (typeof (history.pushState) != "undefined") { 
            history.pushState(null, null, `?page=${page}&order=${order}`); 
        }
        let max_offset = <?=$max_offset?>;
        async function change_order(e) {
            let value = e.value;
            const send_data = {'type': 'order', 'order': value, 'page': 0};
            const url_data = new URLSearchParams(send_data).toString();
            try {
                let response = await fetch('api/list_data.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    },
                    body: url_data
                })
                const res_data = await response.json();
                if(!res_data.hasOwnProperty("type")) {
                    alert('HTTP communication error')
                } else if(res_data['type'] == 'error') {
                    alert(res_data['message']);
                } else if(res_data['type'] == 'success') {
                    page = 0;
                    order = value;
                    if (typeof (history.pushState) != "undefined") { 
                        history.pushState(null, null, `?page=${page}&order=${order}`); 
                    }
                    let old_tbody = document.getElementById('data_tbody');
                    let new_tbody = document.createElement('tbody');
                    new_tbody.setAttribute("id", "data_tbody");
                    for(let el of res_data['data']) {
                        let tmp_el = document.createElement('tr');
                        tmp_el.innerHTML = `<tr>
                            <td><a href="viewcontent.php?id=${el['id']}">${el['title']}</a></td>
                            <td>${el['created_at']}</td>
                            <td>${el['view']}</td>
                        </tr>`;
                        new_tbody.appendChild(tmp_el);
                    }
                    old_tbody.parentNode.replaceChild(new_tbody, old_tbody);
                    document.querySelector('a.active').classList.remove('active');
                    document.querySelector(`a[value="${page}"]`).classList.add('active');
                }
            } catch(err) {
                console.log(err);
                alert('Error.');
            }
        }
        async function change_page(val) {
            if(0<=val && val<=max_offset) {
                const send_data = {'type': 'page', 'order': order, 'page': val};
                const url_data = new URLSearchParams(send_data).toString();
                try {
                    let response = await fetch('api/list_data.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                        },
                        body: url_data
                    })
                    const res_data = await response.json();
                    if(!res_data.hasOwnProperty("type")) {
                        alert('HTTP communication error')
                    } else if(res_data['type'] == 'error') {
                        alert(res_data['message']);
                    } else {
                        page = val;
                        if (typeof (history.pushState) != "undefined") { 
                            history.pushState(null, null, `?page=${page}&order=${order}`); 
                        }
                        let old_tbody = document.getElementById('data_tbody');
                        let new_tbody = document.createElement('tbody');
                        new_tbody.setAttribute("id", "data_tbody");
                        for(let el of res_data['data']) {
                            let tmp_el = document.createElement('tr');
                            tmp_el.innerHTML = `<tr>
                                <td><a href="viewcontent.php?id=${el['id']}">${el['title']}</a></td>
                                <td>${el['created_at']}</td>
                                <td>${el['view']}</td>
                            </tr>`;
                            new_tbody.appendChild(tmp_el);
                        }
                        old_tbody.parentNode.replaceChild(new_tbody, old_tbody);
                        document.querySelector('a.active').classList.remove('active');
                        document.querySelector(`a[value="${page}"]`).classList.add('active');
                    }
                } catch(err) {
                    console.log(err);
                    alert('Error.');
                }
            }
        }
    </script>
</head>
<body>
    <?php include_once 'templates/header.php'; ?>
    <main role="main">
        <div class="container">
            <?php title('List of board'); ?>
        </div>
        <div class="container">
            <div class="mb-3">
                <select name="order" id="order" class="form-select" style="width: 200px; display: initial;" onchange="change_order(this)">
                    <option value="1" selected>most recent</option>
                    <option value="2">oldest</option>
                    <option value="3">number of views</option>
                </select>
                <div class="float-end">
                    <button type="button" class="btn btn-primary" onclick="location.href='write.php'">write</button>
                </div>
            </div>
            <table class="table" id="data_table">
                <thead>
                    <tr>
                        <th scope="col">title</th>
                        <th scope="col">created at</th>
                        <th scope="col">views</th>
                    </tr>
                </thead>
                <tbody id="data_tbody">
                    <?php foreach($content_data as $value) { ?>
                        <tr>
                            <td><a href="viewcontent.php?id=<?=$value['id']?>"><?=htmlspecialchars($value['title'])?></a></td>
                            <td><?=$value['created_at']?></td>
                            <td><?=$value['view']?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="container">
            <div class="text-center">
                <a class="page_num link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" onclick="change_page(page-1)">&lt;</a>
                <?php for($i=0; $i<$max_offset+1; $i++) {?>
                    <a class="page_num link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover<?=($i==$page)?" active":""?>" onclick="change_page(<?=$i?>)" value="<?=$i?>">[<?=$i+1?>]</a>
                <?php } ?>
                <a class="page_num link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" onclick="change_page(page+1)">&gt;</a>
            </div>
        </div>
    </main>
    <?php include_once 'templates/footer.php'; ?>
</body>
</html>