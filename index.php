<?php
    include_once 'templates/functions.php';
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
            <?php title('BAE BOARD WEB PAGE'); ?>
            <div class="container text-center">
                <b><span>This page is BAE's board. I made it for presentation purposes.</span></b>
                <br>
                <br>
                <button type="button" class="btn btn-success" onclick="location.href='list.php'">go to list</button>
                &nbsp;&nbsp;
                <button type="button" class="btn btn-success" onclick="location.href='write.php'">go to write</button>
            </div>
        </div>
    </main>
    <?php include_once 'templates/footer.php'; ?>
</body>
</html>