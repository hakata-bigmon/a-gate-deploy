<?php 
 require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
 require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
 require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
 require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
 require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");
 //ポストユーザー
 $user_id = get_current_user_id();

 //もし管理者等でチェックするユーザーがいるならここで変更する
 $get_url = CheckUserPageAdmin($user_id);


 //シートID
 $sheet_id = $_POST["sheet_id"];

 $userClass = new SpiritUserClass(); //ユーザー管理
 $spirit_sheet_data = new spiritSheetClass(); //質問データ
 $spiritTypeClass = new SpiritTypeClass(); //カテゴリー
 $spiritScheduleData = new SpiritScheduleClass(); //スケジュールデータ

 $spiritData = $userClass->getUserSpritApplicantSheet($user_id,$sheet_id);//浄霊情報
 
 $spirit_data = $spiritData[$sheet_id];


 //シート入力に変更
 if($spirit_data["会員ステータス"] == $userClass::MEMBER_STATUS_WATING_PAYMENT)
 {
    $spirit_sheet_data->changeSpiritSheetPayment( $sheet_id );
 }

$title ="";
$payment_setting = array();


if($spirit_data["スケジュール"] != "")
{
    $set_spirit_sheet = $spiritScheduleData->getSpritScheduledetail($spirit_data["スケジュール"]);
    $title = $set_spirit_sheet["表示名"];

    //var_dump($payment_setting);
}
else
{
    $set_spirit_sheet = array();

    $title = $spirit_data["依頼名前"];
}

/*
var_dump($_POST);

echo "<br>";
     echo "<br>";
 var_dump($spirit_data);

     echo "<br>";
     echo "<br>";
     var_dump($set_spirit_sheet);


*/
    
?>

<style>

    .user-jorei-section-content-wrap{
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        margin-top: 80px;
        margin-bottom: 100px;
    }

    .user-jorei-message-wrap{
        font-size: 16px;
        font-weight: 600;
        color: #888888;
        text-align: center;
    }
</style>


<div class="user-top-area" style="margin-top: 40px;">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title"><?php echo $title;?>の申込完了</div>
    </div>



    <div class="user-jorei-section-content-wrap">
   
            <div class="user-jorei-message-wrap">
                
               
                お支払いありがとうとざいます。 <br>
                <br>
                お申込みが完了しました。 <br>
                  
                <br>
                <br>
                TOP画面の各施術の入力シート編集より<br>
                必要な情報のご入力をお願い致します。
                
            </div>

        <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
          <form action="<?php echo getURLSetSlag("users/user-spirit-question-edit"); echo $get_url["add"];?>" method="post">
              <button type="submit"   class="user-account-edit-return-btn">入力シートに進む ＞</button>
              <input type="hidden"  name="sheet_id" value="<?php echo $sheet_id; ?>">
          </form>
        </div>

        <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
            <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
        </div>
    </div>


    
</div>
