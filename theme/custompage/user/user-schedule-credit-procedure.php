<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleCalendarClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSalesClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
    require_once (dirname(__FILE__)."/../../class/mailTextClass.php");
    
    //浄霊タイプ
    $spiritTypeClass = new SpiritTypeClass();

    $spiritSchedule = new SpiritScheduleClass();
    $spiritSales = new SpiritSalesClass();
    $spiritSheet = new SpiritSheetClass();

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


    $schedule_data = $spiritSchedule->getSpritScheduledetail( $type );


    //商品自体のデータ
    $type_data = $spiritTypeArray[ $schedule_data["施術名"] ];

    $page_id = "";
    
    //販売ページデータ
    $saledata = $spiritSales->getSalesPage($type_data , $type_data["sales_page"]);

    
     //担当者取得
     $spiritSchedule = new SpiritScheduleClass();
    
     $schedule_manager = $spiritSchedule->getScheduleManager( $schedule_data["施術名"] , $spiritTypeArray);


     $schedule_people_check = false;

     $schedule_people = 1;

     if(isset($_POST["people-count"])){
        $schedule_people = $_POST["people-count"];
     }

    
    //予約が可能かどうかを人数で人数人数
    if($schedule_data["人数"] - $schedule_data["予約人数"] - $schedule_people >= 0){


        for( $i=0; $i<$schedule_people; $i++)
        {

            $post_array = array();

            $post_array["user_id"] = $user_id;
            $post_array["category_type"] = $schedule_data["施術名"];
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

            //実行日は確定している
            $post_array["acf_purespirit_execution_date"] = $schedule_data["実行日"];

             
            //状況ステータスは情報入力になる
            $post_array["acf_purespirit_user_status"] = SpiritUserClass::MEMBER_STATUS_NOT_INFOMATION;
            //管理者は確認待ちにする
            
            //依頼確定日（０円なので確定日を入れる）
            $post_array["acf_purespirit_request_confirmation_date"] = date("Y-m-d");
            //入金日は今日になる
            $post_array["acf_purespirit_payment_date"] = date("Y-m-d");

           

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


        if($schedule_people_check)
        {

            $payment_type = "";

            if(isset($_POST["user-schedule-payment"])){
                $payment_type = (int)$_POST["user-schedule-payment"];
            }

            $mailText = new MailTextClass();
            $mailText->sendScheduleConfirmMail( $user_id , $_POST , $schedule_data , $type_data ,$payment_type);
            //$spiritSchedule->sendScheduleConfirmMailCredit( $user_id , $_POST , $schedule_data);
        }
        
    }else{
        $schedule_people_check = false;
    }

    
/*
    var_dump($_POST);
     echo "<br>";
    echo "<br>";

    //var_dump($spiritTypeArray);
    echo "<br>";
    echo "<br>";
    var_dump($schedule_manager);
    echo "<br>";
    echo "<br>";
    var_dump($schedule_data);
    echo "<br>";
    echo "<br>";
    var_dump($type_data);
    echo "<br>";
    echo "<br>";
    var_dump($saledata);

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
        <div class="user-jorei-section-title"><?php echo $saledata["表示名"];?>の予約完了</div>
    </div>



    <div class="user-jorei-section-content-wrap">
   
        <?php if($schedule_people_check){?>
            <div class="user-jorei-message-wrap">
                
                予約が完了しました。<br>
                <br>
                <br>
                TOP画面の各施術の入力シート編集より<br>必要な情報のご入力をお願い致します。
               
            </div>
        <?php }else{?>
            <div class="user-jorei-message-wrap">
                予約枠の確保ができませんでした。<br>
                <br>
                お手数ですが、日程を確認し、再度申し込みをお願いします。
            </div>

            <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 400px;">
                 <a href="<?php echo getURLSetSlag("users/user-schedule-data"); echo $get_url["add"]; if($get_url["add"] == ""){echo "?type=" .$schedule_data["施術グループ"];}else{ echo "&type=" . $schedule_data["施術グループ"];}?>" class="user-account-edit-return-btn">日程選択へ戻る  &gt;</a>
            </div>
        <?php }?>

        <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
            <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
        </div>
    </div>


    
</div>
