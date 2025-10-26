<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/mailTextClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSalesClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
    
    //浄霊タイプ
    $spiritTypeClass = new SpiritTypeClass();

    $spiritSales = new SpiritSalesClass();
    $spiritSheet = new SpiritSheetClass();
    $mailText = new MailTextClass();

    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);

    $userClass = new SpiritUserClass(); //ユーザー管理
   
    $type = "";

    if(isset($_POST["schedule-id"]))
    {
        $type = $_POST["schedule-id"];
    }
    //指定データ
    $spiritTypeArray = $spiritTypeClass->getSpiritTypeKeyTypeNum();


    //商品自体のデータ
    $type_data = $spiritTypeArray[ $type ];

    $page_id = "";
    
    //販売ページデータ
    $saledata = $spiritSales->getSalesPage($type_data , $type_data["sales_page"]);

    
     $schedule_people_check = false;

     $schedule_people = 1;

     if(isset($_POST["people-count"])){
        $schedule_people = $_POST["people-count"];
     }

    
    //予約が可能かどうかを人数で人数人数
   


    for( $i=0; $i<$schedule_people; $i++)
    {

        $post_array = array();

        $post_array["user_id"] = $user_id;
        $post_array["category_type"] = $type;
        $post_array["target_slots"] = 1;
        $post_array["add_sheet"] = "";
        $post_array["schedule_id"] = $type;
        $post_array["add_sheet_unix"] = $_POST["save-unixtime"] + $i;

        if(isset($_POST["user-schedule-payment"])){
            $post_array["payment_type"] = $_POST["user-schedule-payment"];
        }
        else{
            $post_array["payment_type"] = "";//支払いタイプは後で設定
        }

        //実行日はk確定していない
        $post_array["acf_purespirit_execution_date"] = "";

            
        //状況ステータスは情報入力になる
        if($_POST["user-schedule-payment"] == SpiritUserClass::PAYMENT_TYPE_TRANSFER || $_POST["user-schedule-payment"] == SpiritUserClass::PAYMENT_TYPE_CONVENIENCE){
            $post_array["acf_purespirit_user_status"] = SpiritUserClass::MEMBER_STATUS_WATING_PAYMENT; //入金待ち
            $post_array["acf_purespirit_request_confirmation_date"] = "";//依頼確定日は未定
            $post_array["acf_purespirit_payment_date"] = "";//入金日は未定
        }
        else{
            $post_array["acf_purespirit_user_status"] = SpiritUserClass::MEMBER_STATUS_NOT_INFOMATION;//情報未入力
            $post_array["acf_purespirit_request_confirmation_date"] = date("Y-m-d");
            $post_array["acf_purespirit_payment_date"] = date("Y-m-d"); //入金日は今日になる
        }
        
        //var_dump($post_array);

        $add_id = "";
        $add_id = $spiritSheet->newSpiritSheet($post_array);
        if($add_id != "")
        {
             $spiritSheet->newSpiritSheetUserAdd( $user_id ,$add_id);
            $schedule_people_check = true;//１つでも登録可能
        }
        else{
            //すでに登録されているのでbreak
            break;
        }
    }


    //メール
    if($schedule_people_check)
    {

        $payment_type = "";

        if(isset($_POST["user-schedule-payment"])){
            $payment_type = (int)$_POST["user-schedule-payment"];
        }
        
        $mailText->sendCashPaymentMail($user_id, $_POST, $type_data, $payment_type);
        
    }
    
    
    //$spiritTypeNum = $spiritTypeClass->getSpiritTypeKeyTypeNum();
    

    //var_dump($spiritTypeNum);
   /*  echo "<br>";
    echo "<br>";

    var_dump($type_data);
    echo "<br>";
    echo "<br>";
  
    */


    //予約確保できるかどうか
   
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
        <div class="user-jorei-section-title"><?php echo $saledata["表示名"];?>のお申込み完了</div>
    </div>



    <div class="user-jorei-section-content-wrap">
   
        <?php if($schedule_people_check){?>


            <div class="user-jorei-message-wrap">
                
                <?php if($_POST["user-schedule-payment"] == SpiritUserClass::PAYMENT_TYPE_TRANSFER || $_POST["user-schedule-payment"] == SpiritUserClass::PAYMENT_TYPE_CONVENIENCE){?>
                    <?php $bank_info = $spiritTypeClass->getSpiritBankInfo();?>
                    お申込みが完了しました。<br>
                    <br>
                    <br>
                    <br>
                    <br>
                    支払い確認後、TOP画面の各施術の入力シート編集より<br>必要な情報のご入力が可能となります。<br>
                    <br>
                    <br>
                    <div style="max-width: 400px;margin-left: auto;margin-right: auto;text-align: left;">
                        銀行振込の場合は下記の口座にお振込みください<br>
                        <div style="margin-top: 20px;">
                        銀行名：<?php echo $bank_info["bank_name"];?><br>
                        支店名：<?php echo $bank_info["bank_branch"];?><br>
                        口座種別：<?php echo $bank_info["bank_type"];?><br>
                        口座番号：<?php echo $bank_info["bank_number"];?><br>
                        口座名義：<?php echo $bank_info["bank_acount_name"];?>
                        </div>
                    </div>
                    <br>
                    
               <?php }else{?>
                    お申込みが完了しました。<br>
                    <br>
                    <br>
                    TOP画面の各施術の入力シート編集より<br>必要な情報のご入力をお願い致します。<br>
                    入力を完了しないと、施術を受ける事は出来ない為、ご注意ください。
               <?php }?>
            </div>
        <?php }else{?>
            <div class="user-jorei-message-wrap">
                申込が失敗しました。<br>
                <br>
                お手数ですが、再度申し込みをお願いします。
            </div>

           
        <?php }?>

        <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
            <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
        </div>
    </div>


    
</div>
