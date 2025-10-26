<?php 

require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");

$userClass = new SpiritUserClass();
$spiritSheet = new SpiritSheetClass();

//ポストユーザー
$user_id = get_current_user_id();

//もし管理者等でチェックするユーザーがいるならここで変更する
$get_url = CheckUserPageAdmin($user_id);

$sheet_base_data = $userClass->getUserSpritApplicantSheet($user_id,$_POST["sheet_id"]);




$sheet_data = $sheet_base_data[$_POST["sheet_id"]];

//var_dump($sheet_data);

?>


<div class="user-top-area" style="margin-top: 40px;">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title"><?php echo $sheet_data["依頼名前"];?>の結果</div>
    </div>


    <?php 
        $yet_img_no = 1;
        $treatment_result_ids = json_decode(get_field('acf_purespirit_result_img_id',$_POST["sheet_id"]));         //画像データ取得


       
        if(is_array($treatment_result_ids)){

            foreach ($treatment_result_ids as $value) {
                $media_post = get_post($value);  // メディア情報を取得

                $treatment_result_image_date = $media_post->post_date;  //アップロード日
                $treatment_result_image_url = wp_get_attachment_url($value);  //画像URL

        ?>

            <div class="img-box" style="margin-top: 20px;">
                <img class="spirit-detail-img" src="<?php echo $treatment_result_image_url; ?>" style="margin-left: 10px;border: 0px;height: auto;max-width: 850px;margin-left: auto;margin-right: auto;">
            </div>

        <?php
            }
        }
    ?>

    <?php 
        $sales_img =  get_field('acf_previous_sales_post_img',$_POST["sheet_id"]);        //画像データ取得


       
        if($sales_img != ""){
            
        ?>

            <div class="img-box" style="margin-top: 20px;">
                <img class="spirit-detail-img" src="<?php echo $sales_img; ?>" style="margin-left: 10px;border: 0px;height: auto;max-width: 850px;margin-left: auto;margin-right: auto;">
            </div>

        <?php
            
        }
    ?>


    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <form action="<?php echo getURLSetSlag("users/user-spirit-detail"); echo $get_url["add"]; ?>" method="post">
            <input type="hidden" name="sheet_id" value="<?php echo $_POST["sheet_id"]; ?>">
            <button type="submit" class="user-account-edit-return-btn">詳細へ  &gt;</button>
        </form>
    </div>    


    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 20px;">
        <a href="<?php echo getURLSetSlag("users/user-in-progress-spirit-list"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">依頼一覧へ  &gt;</a>
    </div>



    <div class="user-account-edit-form-btn-wrap" style="margin-top: 10px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
    </div>    


</div>