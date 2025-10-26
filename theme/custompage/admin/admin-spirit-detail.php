<?php 

    require_once ("a-gate-functions.php");

    //ユーザー情報作成
    $check_user_id = $_GET['user_id'];
    $check_user_data = getUsersData( $check_user_id);


   // var_dump($check_user_data);
?>




<div class="admin-spirit-area">


    <?php 

        if(!isset($_GET["sheet_edit"]) && !isset($_POST["do_change_user"])){

            require_once ("admin-spirit-detail_read.php");
        }else{
            
            require_once ("admin-spirit-detail_change.php");
        }
        
        
    ?>

</div>