<?php
    include_once 'dbframe.php';
    
    function title($value) { ?>
        <div class="p-5 mb-4 bg-body-secondary rounded-3">
            <div class="container">
                <br><br><br>
                <h1 class="fw-semibold"><?=$value?></h1>
            </div>
        </div>
    <?php }
?>