<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");

    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);


    //シートID
    $sheet_id = $_POST["sheet_id"];

    $userClass = new SpiritUserClass(); //ユーザー管理
    $spirit_sheet_data = new spiritSheetClass(); //質問データ
    $spiritTypeClass = new SpiritTypeClass(); //カテゴリー

    $spiritData = $userClass->getUserSpritApplicantSheet($user_id,$sheet_id);//浄霊情報
    $spiritSheetArray = $spirit_sheet_data->getSpiritQuestion($spiritData[$sheet_id]["質問"]);//質問データ
    //現在の質問番号を取得
  
    //対象者用
    $set_target_id = "";


    $spirit_data = $spiritData[$sheet_id];
    

   ?>


<?php if($spirit_data["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT){ //リモート依頼 ?>

    <?php include(dirname(__FILE__)."/user-spirit-remote-detail.php");?>

    
<?php }else if($spirit_data["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL){ //鑑定 ?>

    <?php include(dirname(__FILE__)."/user-spirit-remote-detail.php");?>

<?php }else if($spirit_data["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY){ //日程確定 ?>

    <?php include(dirname(__FILE__)."/user-spirit-nameday-detail.php");?>

<?php }else if($spirit_data["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){ //物販 ?>


    <?php include(dirname(__FILE__)."/user-spirit-sales-detail.php");?>

<?php }else if($spirit_data["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){ //遠隔・相談 ?>
    
    <?php include(dirname(__FILE__)."/user-spirit-nameday-detail.php");?>

<?php }else if($spirit_data["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_ELSE){ //その他 ?>



<?php }?>



<div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 20px;">
    <a href="<?php echo getURLSetSlag("users/user-in-progress-spirit-list"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">履歴一覧へ  &gt;</a>
</div>

<div class="user-account-edit-form-btn-wrap" style="margin-top: 10px;margin-bottom: 100px;">
    <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
</div>
