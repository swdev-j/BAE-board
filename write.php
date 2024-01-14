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
            <?php title('Write a board'); ?>
        </div>
        <div class="container">
            <form action="api/writecontent.php" method="post" id="writecontent">
                <div class="mb-3">
                    <span>Title</span>
                    <div class="form-floating">
                        <input type="text" class="form-control" id="title" name="title" placeholder="Write a title of board" required>
                        <label for="title">Write a title of board</label>
                        <!-- <div id="TitleHelp" class="form-text"></div> -->
                    </div>
                </div>
                <div class="mb-3">
                    <span>Content</span>
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Write a content of board" id="content" name="content" rows="12" style="height: auto;" required></textarea>
                        <label for="content">Write a content of board</label>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="float-end">
                        <button type="submit" class="btn btn-primary" form="writecontent">submit</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <?php include_once 'templates/footer.php'; ?>
</body>
</html>