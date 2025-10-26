<?php

require_once ("a-gate-functions.php");
require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");

 date_default_timezone_set('Asia/Tokyo'); 

//ユーザー情報作成
$check_user_id = $_GET['user_id'];
$user_last_name = get_user_meta($check_user_id,'last_name',true);
$user_first_name = get_user_meta($check_user_id,'first_name',true);
$users = get_userdata( $check_user_id );
$user_unique_code = get_user_meta($check_user_id,'user_unique_id',true);
$user_last_name_kana = get_user_meta($check_user_id,'last_name_kana',true);
$user_first_name_kana = get_user_meta($check_user_id,'first_name_kana',true);
$user_sex = get_user_meta($check_user_id,'sex',true);


// 流入データ
$spirit_sheet_data = new spiritSheetClass(); //管理データ
$spiritStatusArray = $spirit_sheet_data->getGeneralPurposeData("cpt_inflow");

// 浄霊データテーブル項目
$user_split_data = json_decode(get_user_meta($check_user_id,'spirit_data',true));

YNModalDisp();  // シート追加確認



$spiritType = new SpiritTypeClass(); //管理データ
$spiritTypeArray = $spiritType->getSpiritType();

?>

<script src="<?php echo get_template_directory_uri()."/custompage/admin/a-gate-script.js"?>"></script>


<div class="admin-profile-edit-area">

    <div class="admin-profile-edit-title-box" style="margin-bottom:30px">
        <div class="admin-exorcism-menu-title"><?php echo $user_last_name . " " . $user_first_name;?> 浄霊シート追加</div>
    </div>

    <button type="submit" id="form-back" class="form-btn gray"><a href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $_GET['user_id'];?>">プロフィール詳細に戻る</a></button>
    
    <?php /*
    <div class="user-input-area">

        <div class="user-table-flex">
            <div class="user-table-item">ID</div>
            <div class="user-table-data">
                <input class="data-check" type="text" name="user_id"  value="<?php echo $user_unique_code ?>" readonly>
            </div>
        </div>
        <div class="user-table-flex">
            <div class="user-table-item">名前</div>
            <div class="user-table-data">
                <input class="data-check" type="text" name="input_last_name" placeholder="姓" value="<?php echo $user_last_name ?>" readonly>
                <input class="data-check" type="text" name="input_first_name" placeholder="名" value="<?php echo $user_first_name?>" readonly>
            </div>
        </div>
    
        <div class="user-table-flex">
            <div class="user-table-item">ナマエ</div>
            <div class="user-table-data">
                <input class="data-check" type="text" name="input_last_name_kana" value="<?php echo $user_last_name_kana?>" readonly>
                <input class="data-check" type="text" name="input_first_name_kana" value="<?php echo $user_first_name_kana?>" readonly>
            </div>
        </div>
        
        <div class="user-table-flex">
            <div class="user-table-item">*性別</div>
            <input class="data-check" type="text" name="input_user_sex" id="" value="<?php echo $user_sex?>" readonly>
        </div>
    </div>
    */ ?>    

    <?php /* 浄霊内容 */?>
        <?php 
    
            foreach ($spiritTypeArray as $key => $value) {
        ?>

        <div class="admin-exorcism-button-type-area">

            <?php 
                foreach ($value as $key_num => $value_num) {


                    //リモート浄霊
                    if( $value_num["ID"] == 77)
                    {
                        continue;
                    }

            ?>

                <div class="admin-exorcism-menu-button-box">
                    
                    <form action="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $check_user_id; ?>" method="post" id="add_sprit_sheet_<?php echo $value_num["ID"]?>">
                        <input type="hidden" name="type_id" value="<?php echo $value_num["ID"]; ?>">
                        <input type="hidden" name="add_sheet" value="<?php echo $value_num["ID"]; ?>">
                        <input type="hidden" name="add_sheet_name" value="<?php echo $value_num["title"]; ?>">
                        <input type="hidden" name="add_sheet_unix" value="<?php echo time(); ?>">
                        <input type="button" class="admin-exorcism-menu-button" value="<?php echo $value_num["title"]; ?>" onclick='click_modal("<?php echo $value_num["title"]; ?>を追加しますか？","add_sprit_sheet_<?php echo $value_num["ID"]?>")'>
                    </form>
                </div>
            <?php
                }
            ?>
        </div>
    <?php } ?>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 

  
</div>
