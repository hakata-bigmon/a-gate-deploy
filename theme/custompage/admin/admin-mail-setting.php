<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/mailTextClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    require_once (dirname(__FILE__)."/../../class/mailTextClass.php");

    $mail_title = "";
    $page_id = "";
    $get_link = "";
    $mail_type = "";

    //現在のgetのリンクを取得
    $get_link = $_SERVER['REQUEST_URI'];

    $spiritTypeClass = new SpiritTypeClass();
    $mailText = new MailTextClass();

    if(isset($_GET["type"]))
    {    
        $mail_title = $spiritTypeClass->getSpiritTypeName($_GET["type"]);

        //メールの種類を設定
        $mail_type = $_GET["mailtype"];

        if($mail_type == SpiritUserClass::PAYMENT_TYPE_TRANSFER)
        {
            $mail_title .= "(銀行振込)";
        }
        else if($mail_type == SpiritUserClass::PAYMENT_TYPE_CONVENIENCE)
        {
            $mail_title .= "(コンビニ決済)";
        }
        else if($mail_type == SpiritUserClass::PAYMENT_TYPE_CREDIT)
        {
            $mail_title .= "(クレジットカード)";
        }
        else if($mail_type == SpiritUserClass::PAYMENT_TYPE_E_MONEY)
        {
            $mail_title .= "(電子マネー)";
        }
        else if($mail_type == SpiritUserClass::PAYMENT_TYPE_TRANSIT)
        {
            $mail_title .= "(交通系決済)";
        }
        else if($mail_type == SpiritUserClass::PAYMENT_TYPE_CASH)
        {
            $mail_title .= "(現金)";
        }
        else if($mail_type == SpiritUserClass::PAYMENT_TYPE_FREE)
        {
            $mail_title .= "(無料)";
        }
        else if($mail_type == SpiritUserClass::MAIL_TYPE_COMPLETE_REPORT)
        {
            $mail_title .= "(施術完了報告)";
        }
        else if($mail_type == SpiritUserClass::MAIL_TYPE_SHEET_CONFIRM_REPORT)
        {
            $mail_title .= "(シート確認完了報告)";
        }
        else if($mail_type == SpiritUserClass::MAIL_TYPE_SHEET_RETURN_REPORT)
        {
            $mail_title .= "(シート再提出報告)";
        }
        else if($mail_type == SpiritUserClass::MAIL_TYPE_PAYMENT_CONFIRM_REPORT)
        {
            $mail_title .= "(入金確認完了報告)";
        }
        else if($mail_type == SpiritUserClass::MAIL_TYPE_SALES_SEND_REPORT)
        {
            $mail_title .= "(物販発送完了報告)";
        }
        else if($mail_type == SpiritUserClass::MAIL_TYPE_SCHEDULE_DECISION_REPORT)
        {
            $mail_title .= "(施術日決定報告)";
        }

        $page_id = $mailText->getMailText( (int)$_GET["type"] , (int)$mail_type , true);

    }
    //タイプがない場合はステータス関連の処理
    else{

        $page_id = $_GET["mailtype"];
        $mail_title = get_the_title($page_id);
        $mail_type = $_GET["mailtype"];

       
    }

    //var_dump($_POST);
    //保存
    if(isset($_POST["save"]) && $page_id != "")
    {
        update_field("acf_mail_text", $_POST["mail_text"], $page_id);

        if(isset($_POST["mail_title"])){
            update_field("acf_mail_title", $_POST["mail_title"], $page_id);
        }
    }

?>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<style>
.admin-mail-back-link {
    margin: 20px 0 30px 0;
}

.admin-mail-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f5f5f5;
    color: #333;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 1px solid #ddd;
}

.admin-mail-back-btn:hover {
    background: #e0e0e0;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.admin-mail-form {
    max-width: 1000px;
    margin: 0 auto;
}

.admin-mail-textarea-container {
    margin-bottom: 30px;
}

.admin-mail-label {
    display: block;
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
    padding: 0 5px;
}

.admin-mail-textarea {
    width: 100%;
    min-height: 600px;
    padding: 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-family: 'Courier New', monospace;
    font-size: 14px;
    line-height: 1.5;
    resize: vertical;
    transition: border-color 0.3s ease;
    box-sizing: border-box;
}

.admin-mail-textarea:focus {
    outline: none;
    border-color: #1976d2;
    box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
}

.admin-mail-submit-container {
    text-align: center;
    margin-top: 30px;
}

.admin-mail-submit-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #1976d2;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(25, 118, 210, 0.3);
}

.admin-mail-submit-btn:hover {
    background: #1565c0;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(25, 118, 210, 0.4);
}

.admin-mail-submit-btn:active {
    transform: translateY(0);
}

@media (max-width: 768px) {
    .admin-mail-textarea {
        min-height: 400px;
        font-size: 13px;
    }
    
    .admin-mail-submit-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="admin-menu-wrapper">
  <h1 class="admin-menu-title"><?php echo $mail_title; ?><br>メール設定</h1>

  <div class="admin-mail-back-link">
    <a href="<?php echo getURLSetSlag('admin-mail-setting-menu'); ?>" class="admin-mail-back-btn">
      <span class="material-icons">arrow_back</span>
      メール設定メニューに戻る
    </a>
  </div>
 

  <?php if($page_id != ""){?>
 
    <form action="<?php echo $get_link; ?>" method="post" class="admin-mail-form">
        <input type="hidden" name="save" value="1">
        

        <?php if(isset($_GET["mailtype"]) && $_GET["mailtype"] > SpiritUserClass::PAYMENT_TYPE_OTHER){?>
            <div class="admin-mail-textarea-container">
                <label for="mail_text" class="admin-mail-label">メールタイトル</label>
                <input type="text" name="mail_title" id="mail_title" value="<?php echo get_field("acf_mail_title", $page_id); ?>" style="width: 100%;">
                <div style="font-size: 12px;font-weight: 600;color: red;">
                    <?php if(isset($_GET["type"])){?>
                        <?php if($mail_type == SpiritUserClass::MAIL_TYPE_SALES_SEND_REPORT){?>
                            タイトルの前には商品名が自動的に入ります　例:「テスト商品　物販発送完了報告」
                        <?php }else{?>
                            タイトルの前には施術名が自動的に入ります　例:「霊視鑑定　完了報告」
                        <?php }?>
                    <?php }?>
                </div>
            </div>
        <?php }?>

        <div class="admin-mail-textarea-container">
          <label for="mail_text" class="admin-mail-label">メール本文</label>
          <div style="font-size: 12px;font-weight: 600;color: red;">
            <?php if($mail_type == SpiritUserClass::MAIL_TYPE_SALES_SEND_REPORT){?>
                商品名を文章の中に入れる場合は{商品名}と入力してください
            <?php }else if($mail_type == 9935){ //会員情報修正依頼?>
                会員情報修正依頼の文章はメールの最後に追加されます。
            <?php }else if($mail_type == 9941){ //会員情報完了報告?>
                   
            <?php }else{?>
                施術名を文章の中に入れる場合は{施術名}と入力してください
            <?php }?>

          </div>
          <textarea name="mail_text" id="mail_text" rows="30" class="admin-mail-textarea"><?php echo get_field("acf_mail_text", $page_id); ?></textarea>
        </div>
        
        <div class="admin-mail-submit-container">
          <button type="submit" class="admin-mail-submit-btn">
            <span class="material-icons">save</span>
            保存
          </button>
        </div>
    </form>



    <div style="margin-top: 50px;">

        <hr>
        <div style="text-align: center;font-size: 40px;font-weight: 600;">メール送信内容</div>
        <hr>

        <div style="margin-top: 30px;">
        <?php 

            if(isset($_GET["mailtype"]) && $_GET["mailtype"] <= SpiritUserClass::PAYMENT_TYPE_OTHER){
        
                $post_array = array();
                $post_array["people-count"] = 1;
                $post_array["user-schedule-payment"] = $mail_type;

                $spirit_data = array();
                $spirit_data["title"] = $mail_title;
                $spirit_data["price"] = 10000;
                $spirit_data["group"] = $_GET["type"];

                if($_GET["type"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT || $_GET["type"] == SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL){
                    

                    //リモート依頼、鑑定
                    $mail_text_array = $mailText->sendCashPaymentMail(1,$post_array,$spirit_data,$mail_type,true);

                    echo "<div style='text-align: center;'>タイトル：" . $mail_text_array["mail_subject"] . "</div>";
                    echo '<hr>';
                    echo $mail_text_array["body"];
                }
                else if($_GET["type"] == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY){
                
                    $schedule_data = array();
                    $schedule_data["表示名"] = $mail_title;
                    $schedule_data["実行年月日"] = date("Y/m/d");//本日
                    $schedule_data["担当者名前"] = "";
                    $schedule_data["場所"] = "テスト場所";
                    $schedule_data["場所ステータス"] = array();
                    $schedule_data["場所ステータス"]["名前"] = "テスト場所";
                    $schedule_data["場所ステータス"]["住所"] = "テスト住所　テスト住所　テスト住所";
                    $schedule_data["価格"] = 10000;

                    //日程確定、遠隔・相談
                    $mail_text_array = $mailText->sendScheduleConfirmMail(1,$post_array,$schedule_data,$spirit_data,$mail_type,true);

                    echo "<div style='text-align: center;'>タイトル：" . $mail_text_array["mail_subject"] . "</div>";
                    echo '<hr>';
                    echo $mail_text_array["body"];
                }
                else if($_GET["type"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){
                
                    $schedule_data = array();
                    $schedule_data["表示名"] = $mail_title;
                    $schedule_data["実行年月日"] = date("Y/m/d");//本日
                    $schedule_data["担当者名前"] = "テスト担当者";
                    $schedule_data["場所"] = "";
                    $schedule_data["価格"] = 10000;

                    //日程確定、遠隔・相談
                    $mail_text_array = $mailText->sendScheduleConfirmMail(1,$post_array,$schedule_data,$spirit_data,$mail_type,true);

                    
                    echo "<div style='text-align: center;'>タイトル：" . $mail_text_array["mail_subject"] . "</div>";
                    echo '<hr>';
                    echo $mail_text_array["body"];
                }
                else if($_GET["type"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){
                
                    $post_address_data = array();
                    $post_address_data["郵送先名前"] = "テスト郵送先";
                    $post_address_data["郵送先郵便番号"] = "123-4567";
                    $post_address_data["郵送先住所1"] = "テスト住所1";
                    $post_address_data["郵送先住所2"] = "テスト住所2";

                

                    //日程確定、遠隔・相談
                    $mail_text_array = $mailText->sendSalesMail( $user_id , array() , array() , $post_address_data, $mail_type,true);

                    echo "<div style='text-align: center;'>タイトル：" . $mail_text_array["mail_subject"] . "</div>";
                    echo '<hr>';
                    echo $mail_text_array["body"];
                }

            }else{

                $spirit_data = array();
                $spirit_data["依頼名前"] = "テスト施術";
                $spirit_data["依頼タイプ"] = isset($_GET["type"]) ? $_GET["type"] : "";

                if($mail_type == SpiritUserClass::MAIL_TYPE_SHEET_RETURN_REPORT)
                {
                    $spirit_data["再提出依頼"] = "再提出依頼の内容";
                }
                else if($mail_type == SpiritUserClass::MAIL_TYPE_SCHEDULE_DECISION_REPORT)
                {
                    $spirit_data["実行予定日"] = date("Y-m-d");
                }

                if($mail_type == SpiritUserClass::MAIL_TYPE_SALES_SEND_REPORT)
                {
                    $spirit_data["依頼名前"] = "テスト商品名";
                    $spirit_data["依頼者ID"] = 20;
                    $spirit_data["販売個数"] = 1;
                    $spirit_data["価格"] = 10000;
                    $spirit_data["郵送先郵便番号"] = "123-4567";
                    $spirit_data["郵送先住所1"] = "テスト住所1";
                    $spirit_data["郵送先住所2"] = "テスト住所2";
                    $spirit_data["郵送先名前"] = "テスト郵送先";
                    $spirit_data["実行日"] =  date("Y-m-d");
                    //年月日に変更
                    $spirit_data["実行日年月日"] = date("Y年m月d日", strtotime($spirit_data["実行日"]));

                    //物販
                    $mail_text_array = $mailText->sendSalesSendMail( $user_id , $page_id , $spirit_data ,$mail_type ,true);

                    echo "<div style='text-align: center;'>タイトル：" . $mail_text_array["mail_subject"] . "</div>";
                    echo '<hr>';
                    echo $mail_text_array["body"];
                }
                else if($mail_type == 9935){ //会員情報修正依頼
                    $mail_text_array = $mailText->sendMemberStatusUpdateMail( $user_id , $page_id,true);

                    echo "<div style='text-align: center;'>タイトル：" . $mail_text_array["mail_subject"] . "</div>";
                    echo '<hr>';
                    echo $mail_text_array["body"];
                }
                else if($mail_type == 9941){ //会員情報完了報告
                    $mail_text_array = $mailText->sendCommonMail( $user_id , $page_id,true);

                    echo "<div style='text-align: center;'>タイトル：" . $mail_text_array["mail_subject"] . "</div>";
                    echo '<hr>';
                    echo $mail_text_array["body"];
                }
               else{
                    $mail_text_array = $mailText->sendMemberStatusMail( $user_id , $page_id , $spirit_data ,$mail_type ,true);

                    echo "<div style='text-align: center;'>タイトル：" . $mail_text_array["mail_subject"] . "</div>";
                    echo '<hr>';
                    echo $mail_text_array["body"];
               }
            }

        ?>
        </div>

    </div>

  <?php }?>

</div>