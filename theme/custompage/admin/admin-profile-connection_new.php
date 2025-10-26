<?php 
    $mes = "";

    // var_dump($_POST);  //削除okd
     //新規
    if(isset($_POST["add_connection"]))
    {
        if($_POST["connection_name"] == ""){
            $new_err = "関連名を入れてください";

        
        }else if($connection_group_data->checkConnectionGroup($_POST) != "ok"){
            $new_err = "同じ名前のものは登録できません";
        }else{

            $res = $connection_group_data->newConnectionGroup($_POST);
            if(!is_wp_error($res)){
                $mes = "追加しました。";
            }
        }

        // else if($_POST["input_add_name"] != "")
        // {
        //     $spirit_sheet_data->newGeneralPurposeData( $_POST["input_add_name"] , "cpt_inflow");
        // }
        // else{

        //     $new_err = "空白は登録できません";

        // }
        
        // var_dump($res);
    }

    if(isset($_GET['connection_list'])){

        if(isset($_POST['group_user_change'])){

            $page_title = "（親族）関連編集";
        }else{

            $page_title = "（親族）関連設定";
        }
    }
?>

<?php /* タイトル */?>
<?php if(isset($_GET['connection_list'])){ ?>
    <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title"><?php echo $page_title; ?></div></div>
    <form id="" action="<?php echo  getURLSetSlag( "admin-member-list" );?>" method="get" >
        <input type="hidden" name="group_user_id" value="<?php echo $_POST['group_user_id']; ?>">
        <button class="form-btn red">関係一覧に戻る</button>
    </form>
<?php }else{ ?>
    <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">（親族）関連新規作成</div></div>
<?php } ?>

<?php /* エラー */?>
<?php if($new_err != ""){ //エラー ?>
    <div class="admin-spirit-edit-err-etr"><?php echo $new_err;?></div>
<?php } ?>
<?php if($mes != ""){ ?>
    <div class="admin-spirit-edit-err-etr"><?php echo $mes;?></div>
<?php } ?>

<form id="" action="<?php echo  getURLSetSlag( "admin-profile-connection" );?>?new-connection=on" method="post" >
    <input type="hidden" name="add_connection">

    <div class="user-input-area">

        <div class="user-table-flex">
            <div class="user-table-item">代表者</div>
            <div class="user-table-data">
                
                <?php if(isset($_POST['chose_id']) || isset($_POST['group_user_id'])){?>
                    <input type="hidden" name="chose_user_id" value="<?php echo $_POST['chose_id']; ?>">
                    <?php 
                        $chose_user_data =  get_userdata($_POST['chose_id']);
                        echo $group_name = $chose_user_data->first_name.$chose_user_data->last_name;
                        
                    ?>
                    <input type="hidden" name="top_name" value="<?php echo $group_name;?>">

                <?php }elseif(!isset($_POST['chose_connect_user'])){?>
                    <input type="hidden" name="chose_connect_user">
                    <a href="<?php echo getURLSetSlag("admin-profile-connection"); ?>?connection_chose=on">

                        <button type="button" id="" class="admin-spirit-return-button" onclick="changeFormActionAndSubmit()">登録者から選択</button>
                    </a>
                <?php } ?>
                
            </div>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">関連名</div>
                <input class="" type="text" name="connection_name" value="<?php if(isset($_POST['chose_id'])) echo $group_name."グループ";?>">
            </div>
        </div>

    <?php /* 新規作成ノミ */?>
    <?php if(!isset($_POST['group_user_id'])){?>

    <div class="">＊関連者を追加する場合は、新規作成後、関連一覧の個別の詳細から追加してください</div>

    <button class="form-btn red">作成する</button>
    
    <?php } ?>
</form>


<?php /* 新規作成ノミ */?>
<?php if(!isset($_POST['group_user_id'])){?>
<div class="admin-spirit-return-button-area">
    <button type=“button” class="admin-spirit-return-button" onclick="location.href='<?php echo getURLSetSlag('admin-profile-connection'); ?>'">戻る</button>
</div>
<?php } ?>