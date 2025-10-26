<?php 

require_once(get_template_directory() . '/class/spiritTypeClass.php');
require_once(get_template_directory() . '/class/spiritUserClass.php');

class MailTextClass
{

    //メールの種類
    public const MAIL_TYPE_PAYMENT_CASH = 1;//現金支払い
    public const MAIL_TYPE_PAYMENT_ELECTRONIC = 2;//電子決済
    public const MAIL_TYPE_PAYMENT_FREE = 3;//無料
    

    //メールの入力ページ

    //リモート依頼
    public const MAIL_TYPE_REMOTE_REQUEST_TRANSFER_PAYMENT = 8996;//リモート依頼（銀行振込）
    public const MAIL_TYPE_REMOTE_REQUEST_ELECTRONIC_PAYMENT = 8997;//リモート依頼（コンビニ決済）
    public const MAIL_TYPE_REMOTE_REQUEST_CONVENIENCE_PAYMENT = 9013;//リモート依頼（クレジットカード）
    public const MAIL_TYPE_REMOTE_REQUEST_CREDIT_PAYMENT = 9014;//リモート依頼（電子マネー）
    public const MAIL_TYPE_REMOTE_REQUEST_TRANSIT_PAYMENT = 9015;//リモート依頼（交通系決済）
    public const MAIL_TYPE_REMOTE_REQUEST_CASH_PAYMENT = 9016;//リモート依頼（現金）
    public const MAIL_TYPE_REMOTE_REQUEST_FREE = 9017;//リモート依頼（無料）

    public const MAIL_TYPE_REMOTE_REQUEST_COMPLETE_REPORT = 9078;//リモート依頼（施術完了報告）
    public const MAIL_TYPE_REMOTE_REQUEST_SHEET_CONFIRM_REPORT = 9085;//リモート依頼（シート確認完了報告）
    public const MAIL_TYPE_REMOTE_REQUEST_SHEET_RETURN_REPORT = 9079;//リモート依頼（シート再提出報告）
    public const MAIL_TYPE_REMOTE_REQUEST_PAYMENT_CONFIRM_REPORT = 9080;//リモート依頼（入金確認完了報告）
    public const MAIL_TYPE_REMOTE_REQUEST_SALES_SEND_REPORT = 9084;//リモート依頼（物販発送完了報告）
    public const MAIL_TYPE_REMOTE_REQUEST_SCHEDULE_DECISION_REPORT = 9081;//リモート依頼（施術日決定報告）

    //鑑定
    public const MAIL_TYPE_READING_TRANSFER_PAYMENT = 8998;//鑑定（銀行振込）
    public const MAIL_TYPE_READING_ELECTRONIC_PAYMENT = 8999;//鑑定（コンビニ決済）
    public const MAIL_TYPE_READING_CONVENIENCE_PAYMENT = 9018;//鑑定（クレジットカード）
    public const MAIL_TYPE_READING_CREDIT_PAYMENT = 9019;//鑑定（電子マネー）
    public const MAIL_TYPE_READING_TRANSIT_PAYMENT = 9020;//鑑定（交通系決済）
    public const MAIL_TYPE_READING_CASH_PAYMENT = 9021;//鑑定（現金）
    public const MAIL_TYPE_READING_FREE = 9022;//鑑定（無料）

    public const MAIL_TYPE_READING_COMPLETE_REPORT = 9088;//鑑定（施術完了報告）
    public const MAIL_TYPE_READING_SHEET_CONFIRM_REPORT = 9089;//鑑定（シート確認完了報告）
    public const MAIL_TYPE_READING_SHEET_RETURN_REPORT = 9090;//鑑定（シート再提出報告）
    public const MAIL_TYPE_READING_PAYMENT_CONFIRM_REPORT = 9091;//鑑定（入金確認完了報告）
    public const MAIL_TYPE_READING_SALES_SEND_REPORT = 9092;//鑑定（物販発送完了報告）
    public const MAIL_TYPE_READING_SCHEDULE_DECISION_REPORT = 9093;//鑑定（施術日決定報告）


    //日程確定依頼
    public const MAIL_TYPE_SCHEDULE_CONFIRM_TRANSFER_PAYMENT = 9000;//日程確定依頼（銀行振込）
    public const MAIL_TYPE_SCHEDULE_CONFIRM_ELECTRONIC_PAYMENT = 9001;//日程確定依頼（コンビニ決済）
    public const MAIL_TYPE_SCHEDULE_CONFIRM_CONVENIENCE_PAYMENT = 9023;//日程確定依頼（クレジットカード）
    public const MAIL_TYPE_SCHEDULE_CONFIRM_CREDIT_PAYMENT = 9024;//日程確定依頼（電子マネー）
    public const MAIL_TYPE_SCHEDULE_CONFIRM_TRANSIT_PAYMENT = 9025;//日程確定依頼（交通系決済）
    public const MAIL_TYPE_SCHEDULE_CONFIRM_CASH_PAYMENT = 9026;//日程確定依頼（現金）
    public const MAIL_TYPE_SCHEDULE_CONFIRM_FREE = 9002;//日程確定依頼（無料）

    public const MAIL_TYPE_SCHEDULE_CONFIRM_COMPLETE_REPORT = 9094;//日程確定（施術完了報告）
    public const MAIL_TYPE_SCHEDULE_CONFIRM_SHEET_CONFIRM_REPORT = 9095;//日程確定（シート確認完了報告）
    public const MAIL_TYPE_SCHEDULE_CONFIRM_SHEET_RETURN_REPORT = 9096;//日程確定（シート再提出報告）
    public const MAIL_TYPE_SCHEDULE_CONFIRM_PAYMENT_CONFIRM_REPORT = 9097;//日程確定（入金確認完了報告）
    public const MAIL_TYPE_SCHEDULE_CONFIRM_SALES_SEND_REPORT = 9098;//日程確定（物販発送完了報告）
    public const MAIL_TYPE_SCHEDULE_CONFIRM_SCHEDULE_DECISION_REPORT = 9099;//日程確定（施術日決定報告）

    //物販
    public const MAIL_TYPE_SHOP_TRANSFER_PAYMENT = 9003;//物販（銀行振込）
    public const MAIL_TYPE_SHOP_ELECTRONIC_PAYMENT = 9004;//物販（コンビニ決済）
    public const MAIL_TYPE_SHOP_CONVENIENCE_PAYMENT = 9027;//物販（クレジットカード）
    public const MAIL_TYPE_SHOP_CREDIT_PAYMENT = 9028;//物販（電子マネー）
    public const MAIL_TYPE_SHOP_TRANSIT_PAYMENT = 9029;//物販（交通系決済）
    public const MAIL_TYPE_SHOP_CASH_PAYMENT = 9030;//物販（現金）
    public const MAIL_TYPE_SHOP_FREE = 9005;//物販（無料）

    public const MAIL_TYPE_SHOP_COMPLETE_REPORT = 9100;//物販（施術完了報告）
    public const MAIL_TYPE_SHOP_SHEET_CONFIRM_REPORT = 9101;//物販（シート確認完了報告）
    public const MAIL_TYPE_SHOP_SHEET_RETURN_REPORT = 9102;//物販（シート再提出報告）
    public const MAIL_TYPE_SHOP_PAYMENT_CONFIRM_REPORT = 9103;//物販（入金確認完了報告）
    public const MAIL_TYPE_SHOP_SALES_SEND_REPORT = 9104;//物販（物販発送完了報告）



    //相談
    public const MAIL_TYPE_CONSULTATION_TRANSFER_PAYMENT = 9006;//相談（銀行振込）
    public const MAIL_TYPE_CONSULTATION_ELECTRONIC_PAYMENT = 9007;//相談（コンビニ決済）
    public const MAIL_TYPE_CONSULTATION_CONVENIENCE_PAYMENT = 9031;//相談（クレジットカード）
    public const MAIL_TYPE_CONSULTATION_CREDIT_PAYMENT = 9032;//相談（電子マネー）
    public const MAIL_TYPE_CONSULTATION_TRANSIT_PAYMENT = 9033;//相談（交通系決済）
    public const MAIL_TYPE_CONSULTATION_CASH_PAYMENT = 9034;//相談（現金）
    public const MAIL_TYPE_CONSULTATION_FREE = 9008;//相談（無料）

    public const MAIL_TYPE_CONSULTATION_COMPLETE_REPORT = 9105;//相談（施術完了報告）
    public const MAIL_TYPE_CONSULTATION_SHEET_CONFIRM_REPORT = 9106;//相談（シート確認完了報告）
    public const MAIL_TYPE_CONSULTATION_SHEET_RETURN_REPORT = 9108;//相談（シート再提出報告）
    public const MAIL_TYPE_CONSULTATION_PAYMENT_CONFIRM_REPORT = 9109;//相談（入金確認完了報告）
    public const MAIL_TYPE_CONSULTATION_SALES_SEND_REPORT = 9110;//相談（物販発送完了報告）
    public const MAIL_TYPE_CONSULTATION_SCHEDULE_DECISION_REPORT = 9111;//相談（施術日決定報告）


    
    /****************************************************
    **  支払いメール取得
    ******************************************************/
    public function getMailText( $group_id , $mail_type , $get_text_id = false)
    {


      

        $mail_text = "";


        $mail_type_array = array(
            SpiritUserClass::PAYMENT_TYPE_CREDIT => array(
                SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT => MailTextClass::MAIL_TYPE_REMOTE_REQUEST_CREDIT_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL => MailTextClass::MAIL_TYPE_READING_CREDIT_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_DAY => MailTextClass::MAIL_TYPE_SCHEDULE_CONFIRM_CREDIT_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SALES => MailTextClass::MAIL_TYPE_SHOP_CREDIT_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN => MailTextClass::MAIL_TYPE_CONSULTATION_CREDIT_PAYMENT,
            ),
            SpiritUserClass::PAYMENT_TYPE_TRANSFER => array(
                SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT => MailTextClass::MAIL_TYPE_REMOTE_REQUEST_TRANSFER_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL => MailTextClass::MAIL_TYPE_READING_TRANSFER_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_DAY => MailTextClass::MAIL_TYPE_SCHEDULE_CONFIRM_TRANSFER_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SALES => MailTextClass::MAIL_TYPE_SHOP_TRANSFER_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN => MailTextClass::MAIL_TYPE_CONSULTATION_TRANSFER_PAYMENT,
            ),
            SpiritUserClass::PAYMENT_TYPE_CONVENIENCE => array(
                SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT => MailTextClass::MAIL_TYPE_REMOTE_REQUEST_CONVENIENCE_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL => MailTextClass::MAIL_TYPE_READING_CONVENIENCE_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_DAY => MailTextClass::MAIL_TYPE_SCHEDULE_CONFIRM_CONVENIENCE_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SALES => MailTextClass::MAIL_TYPE_SHOP_CONVENIENCE_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN => MailTextClass::MAIL_TYPE_CONSULTATION_CONVENIENCE_PAYMENT,
            ),
            SpiritUserClass::PAYMENT_TYPE_E_MONEY => array(
                SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT => MailTextClass::MAIL_TYPE_REMOTE_REQUEST_CREDIT_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL => MailTextClass::MAIL_TYPE_READING_CREDIT_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_DAY => MailTextClass::MAIL_TYPE_SCHEDULE_CONFIRM_CREDIT_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SALES => MailTextClass::MAIL_TYPE_SHOP_CREDIT_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN => MailTextClass::MAIL_TYPE_CONSULTATION_CREDIT_PAYMENT,
            ),
            SpiritUserClass::PAYMENT_TYPE_TRANSIT => array(
                SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT => MailTextClass::MAIL_TYPE_REMOTE_REQUEST_TRANSIT_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL => MailTextClass::MAIL_TYPE_READING_TRANSIT_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_DAY => MailTextClass::MAIL_TYPE_SCHEDULE_CONFIRM_TRANSIT_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SALES => MailTextClass::MAIL_TYPE_SHOP_TRANSIT_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN => MailTextClass::MAIL_TYPE_CONSULTATION_TRANSIT_PAYMENT,
            ),
            SpiritUserClass::PAYMENT_TYPE_CASH => array(
                SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT => MailTextClass::MAIL_TYPE_REMOTE_REQUEST_CASH_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL => MailTextClass::MAIL_TYPE_READING_CASH_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_DAY => MailTextClass::MAIL_TYPE_SCHEDULE_CONFIRM_CASH_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SALES => MailTextClass::MAIL_TYPE_SHOP_CASH_PAYMENT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN => MailTextClass::MAIL_TYPE_CONSULTATION_CASH_PAYMENT,
            ),
            SpiritUserClass::PAYMENT_TYPE_FREE => array(
                SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT => MailTextClass::MAIL_TYPE_REMOTE_REQUEST_FREE,
                SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL => MailTextClass::MAIL_TYPE_READING_FREE,
                SpiritTypeClass::SPIRIT_TYPE_NAME_DAY => MailTextClass::MAIL_TYPE_SCHEDULE_CONFIRM_FREE,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SALES => MailTextClass::MAIL_TYPE_SHOP_FREE,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN => MailTextClass::MAIL_TYPE_CONSULTATION_FREE,
            ),
            SpiritUserClass::MAIL_TYPE_COMPLETE_REPORT => array(
                SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT => MailTextClass::MAIL_TYPE_REMOTE_REQUEST_COMPLETE_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL => MailTextClass::MAIL_TYPE_READING_COMPLETE_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_DAY => MailTextClass::MAIL_TYPE_SCHEDULE_CONFIRM_COMPLETE_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SALES => MailTextClass::MAIL_TYPE_SHOP_COMPLETE_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN => MailTextClass::MAIL_TYPE_CONSULTATION_COMPLETE_REPORT,
            ),
            SpiritUserClass::MAIL_TYPE_SHEET_CONFIRM_REPORT => array(
                SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT => MailTextClass::MAIL_TYPE_REMOTE_REQUEST_SHEET_CONFIRM_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL => MailTextClass::MAIL_TYPE_READING_SHEET_CONFIRM_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_DAY => MailTextClass::MAIL_TYPE_SCHEDULE_CONFIRM_SHEET_CONFIRM_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SALES => MailTextClass::MAIL_TYPE_SHOP_SHEET_CONFIRM_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN => MailTextClass::MAIL_TYPE_CONSULTATION_SHEET_CONFIRM_REPORT,
            ),
            SpiritUserClass::MAIL_TYPE_SHEET_RETURN_REPORT => array(
                SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT => MailTextClass::MAIL_TYPE_REMOTE_REQUEST_SHEET_RETURN_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL => MailTextClass::MAIL_TYPE_READING_SHEET_RETURN_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_DAY => MailTextClass::MAIL_TYPE_SCHEDULE_CONFIRM_SHEET_RETURN_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SALES => MailTextClass::MAIL_TYPE_SHOP_SHEET_RETURN_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN => MailTextClass::MAIL_TYPE_CONSULTATION_SHEET_RETURN_REPORT,
            ),
            SpiritUserClass::MAIL_TYPE_PAYMENT_CONFIRM_REPORT => array(
                SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT => MailTextClass::MAIL_TYPE_REMOTE_REQUEST_PAYMENT_CONFIRM_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL => MailTextClass::MAIL_TYPE_READING_PAYMENT_CONFIRM_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_DAY => MailTextClass::MAIL_TYPE_SCHEDULE_CONFIRM_PAYMENT_CONFIRM_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SALES => MailTextClass::MAIL_TYPE_SHOP_PAYMENT_CONFIRM_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN => MailTextClass::MAIL_TYPE_CONSULTATION_PAYMENT_CONFIRM_REPORT,
            ),
            SpiritUserClass::MAIL_TYPE_SALES_SEND_REPORT => array(
                SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT => MailTextClass::MAIL_TYPE_REMOTE_REQUEST_SALES_SEND_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL => MailTextClass::MAIL_TYPE_READING_SALES_SEND_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_DAY => MailTextClass::MAIL_TYPE_SCHEDULE_CONFIRM_SALES_SEND_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SALES => MailTextClass::MAIL_TYPE_SHOP_SALES_SEND_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN => MailTextClass::MAIL_TYPE_CONSULTATION_SALES_SEND_REPORT,
            ),
            SpiritUserClass::MAIL_TYPE_SCHEDULE_DECISION_REPORT => array(
                SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT => MailTextClass::MAIL_TYPE_REMOTE_REQUEST_SCHEDULE_DECISION_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL => MailTextClass::MAIL_TYPE_READING_SCHEDULE_DECISION_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_DAY => MailTextClass::MAIL_TYPE_SCHEDULE_CONFIRM_SCHEDULE_DECISION_REPORT,
                SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN => MailTextClass::MAIL_TYPE_CONSULTATION_SCHEDULE_DECISION_REPORT,
            ),
        );

        if(isset($mail_type_array[$mail_type][$group_id])){
            if($get_text_id){
                $mail_text = $mail_type_array[$mail_type][$group_id];
            }
            else{
                $mail_text = get_field("acf_mail_text", $mail_type_array[$mail_type][$group_id]);
            }
        }
        else{
            $mail_text = "";
        }

        return $mail_text;
    }

    /****************************************************
    **  現金支払いメール
    ******************************************************/
    public function sendCashPaymentMail( $user_id , $post_array , $spirit_data ,$mail_type,$pre_view = false)
    {

        $users = get_userdata($user_id);

        $userClass = new SpiritUserClass();

        $mail_to = $users->user_email;
        $mail_subject = $spirit_data["title"] . "のお申込みありがとうございます。";
       

        $body = "";

        $body .= '<div style="max-width: 500px;margin-right: auto;margin-left: auto;">';


        $body .= ' <div style="text-align: left;">' .$spirit_data["title"] . 'の申込を受け付けました。<br><br>';

       
        $body .= nl2br($this->getMailText( (int)$spirit_data["group"] , (int)$mail_type));


        $body .= '
            <br></div>

            <hr>

            <div style="text-align: center;font-size: 24px;font-weight: bold;margin-bottom: 20px;">ご注文内容</div>
        ';
        
        
        $body .= 'ご注文枠　 : ' . $post_array["people-count"] . '枠<br>';
        
        if(isset($post_array["user-schedule-payment"]) && $post_array["user-schedule-payment"] != "" && $post_array["user-schedule-payment"] != SpiritUserClass::PAYMENT_TYPE_FREE && $spirit_data["price"] > 0){ 
            $body .= 'お支払い方法 : ' . $userClass->getPaymentTypeLabel($post_array["user-schedule-payment"]) . '<br>';
        }
        
      

        $body .= '<div style="text-align:right;margin-top: 30px;margin-bottom: 30px;max-width: 850px;font-size: 24px;color: black;">';

        if(isset($post_array["user-schedule-payment"]) && $post_array["user-schedule-payment"] != "" && $post_array["user-schedule-payment"] != SpiritUserClass::PAYMENT_TYPE_FREE && $spirit_data["price"] > 0){ 

            $body .= '<div style="">合計　' . number_format($spirit_data["price"] * $post_array["people-count"] ) . '円</div>';
        }

        $body .= '<hr>';

        $body .= '<div style="text-align: center;font-size: 24px;font-weight: bold;margin-bottom: 20px;margin-top: 30px;">A-GATE 運営 </div>';

        $body .= '</div></div>';


        if($pre_view){

            $body_array = array();
            $body_array["body"] = $body;
            $body_array["mail_subject"] = $mail_subject;
            $body_array["mail_to"] = $mail_to;

            return $body_array;
        }
        else{
             // 送信者名とアドレスをセット（ここが重要）
            $mail_headers  = "From: A-GATE 運営 <info@a-gate-kanri.com>\r\n";
            $mail_headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            wp_mail($mail_to, $mail_subject, $body, $mail_headers);

            return "";

        }

    }

    /****************************************************
    **  日程確保メール
    ******************************************************/
    public function sendScheduleConfirmMail( $user_id , $post_array , $schedule_data , $spirit_data ,$mail_type,$pre_view = false)
    {

        $users = get_userdata($user_id);

        $userClass = new SpiritUserClass();

        $mail_to = $users->user_email;
        $mail_subject = $schedule_data["表示名"] . "のお申込みありがとうございます。";
       

        $body = "";

        $body .= '<div style="max-width: 500px;margin-right: auto;margin-left: auto;">';


        $body .= ' <div style="text-align: left;">' .$schedule_data["表示名"] . 'の申込を受け付けました。<br><br>';

        $body .= nl2br($this->getMailText( (int)$spirit_data["group"] , (int)$mail_type));
       
        $body .= '
            <br></div>

            <hr>

            <div style="text-align: center;font-size: 24px;font-weight: bold;margin-bottom: 20px;">ご注文内容</div>
        ';
        
        
        $body .= 'ご注文枠　 : ' . $post_array["people-count"] . '枠<br>';
        $body .= '実行日　　 : ' . $schedule_data["実行年月日"] . '<br>';
        
        if(isset($post_array["user-schedule-payment"]) && $post_array["user-schedule-payment"] != "" && $post_array["user-schedule-payment"] != SpiritUserClass::PAYMENT_TYPE_FREE && $schedule_data["価格"] > 0){ 
            $body .= 'お支払い方法 : ' . $userClass->getPaymentTypeLabel($post_array["user-schedule-payment"]) . '<br>';
        }
        
        if($schedule_data["担当者名前"] != ""){
            $body .= '担当者　　 : ' . $schedule_data["担当者名前"] . '<br>';
        }
        if($schedule_data["場所"] != ""){
            $body .= '場所　　　 : ' . $schedule_data["場所ステータス"]["名前"] . '<br>';
            $body .= '場所詳細　 : ' . $schedule_data["場所ステータス"]["住所"] . '<br>';
        }
        

        $body .= '<div style="text-align:right;margin-top: 30px;margin-bottom: 30px;max-width: 850px;font-size: 24px;color: black;">';

        if(isset($post_array["user-schedule-payment"]) && $post_array["user-schedule-payment"] != "" && $post_array["user-schedule-payment"] != SpiritUserClass::PAYMENT_TYPE_FREE && $schedule_data["価格"] > 0){ 

            $body .= '<div style="">合計　' . number_format($schedule_data["価格"] * $post_array["people-count"] ) . '円</div>';
        }

        $body .= '<hr>';

        $body .= '<div style="text-align: center;font-size: 24px;font-weight: bold;margin-bottom: 20px;margin-top: 30px;">A-GATE 運営 </div>';

        $body .= '</div></div>';
        // echo $body;

        if($pre_view){
            $body_array = array();
            $body_array["body"] = $body;
            $body_array["mail_subject"] = $mail_subject;
            $body_array["mail_to"] = $mail_to;

            return $body_array;
        }
        else{
             // 送信者名とアドレスをセット（ここが重要）
            $mail_headers  = "From: A-GATE 運営 <info@a-gate-kanri.com>\r\n";
            $mail_headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            wp_mail($mail_to, $mail_subject, $body, $mail_headers);

            return "";

        }
      

    }

    
    /****************************************************
    **  購入メール
    ******************************************************/
    public function sendSalesMail( $user_id , $post_array , $spiritTypeArray,$post_address_data, $mail_type,$pre_view = false)
    {

        $users = get_userdata($user_id);

       
        if($pre_view){
            $mail_to = "info@a-gate-kanri.com";
        }
        else{
            $mail_to = $users->user_email;
        }

        
        $mail_subject = "A-GATE OFFICIAL STORE ご購入ありがとうございました";
       

        $body = "";
        $body .= ' <div style="max-width: 900px;margin-right: auto;margin-left: auto;">';

        $body .= ' <h2 style="text-align: center;">ご購入ありがとうございます！</h2> <div style="text-align: center;">';

        $body .= nl2br($this->getMailText( SpiritTypeClass::SPIRIT_TYPE_NAME_SALES , (int)$mail_type));
       

        $body .= '
            </div><hr>

            <div style="text-align: center;font-size: 24px;font-weight: bold;margin-bottom: 20px;">ご注文内容</div>

             <table style="width:100%; border-collapse: collapse;max-width: 800px;margin-right: auto;margin-left: auto;">
        ';
        
        $total_price = 0;


        if(!$pre_view)
        {

            foreach($post_array as $key => $value)
            {
                $body .= '<tr style="border-bottom: 1px solid black;border-top: 1px solid black;">';

                //商品自体のデータ
                $type_data = $spiritTypeArray[ $key ];

                //販売ページデータ
                $saledata = $this->getSalesPage($spiritTypeArray[$key ] , $type_data["sales_page"]);

                if($saledata["サムネイル"] != ""){
                    $body .= '<td style="text-align: right;padding-top: 10px;"><img src="'.esc_url($saledata["サムネイル"]).'" alt="'.esc_attr($type_data["title"]).'" style="width: 100px;max-width: 100px"></td>';
                }else{
                    $body .= '<td style="text-align: right;padding-top: 10px;"><img src="'.get_template_directory_uri().'/assets/images/noimage.jpg" alt="'.esc_attr($type_data["title"]).'" style="width: 100px;max-width: 100px"></td>';
                }
            

                $body .= '<td style="padding-left: 20px;">' .  esc_html($type_data["title"]) . '<br>';
                $body .=  $value . '点<br>';
                $body .=  '￥' . number_format($type_data["price"] * $value) . '円</td>';
                $body .= '</tr>';

                $total_price += $type_data["price"] * $value;
            }

            $body .= '</table>';

            $body .= '<div style="text-align:right;margin-top: 30px;margin-bottom: 30px;max-width: 850px;font-size: 24px;color: black;">';
            $body .= '<div style="">合計　' . number_format($total_price) . '円</div>';


        }
        else{
            $body .= '<div style="text-align: center;font-size: 24px;font-weight: bold;margin-bottom: 20px;margin-top: 30px;">商品情報はプレビュー中は表示されません</div>';
        }
        $body .= '<hr>';

        $body .= '<div style="text-align: center;font-size: 20px;margin-bottom: 20px;margin-top: 30px;">郵送先</div>';

        $body .= '<div style="margin-left: 50px;text-align: left;margin-top: 20px;margin-bottom: 20px;max-width: 850px;font-size: 16px;color: black;">';
        $body .= "【" . $post_address_data["郵送先名前"] . "】 様" . '<br><br>';
        $body .= "〒" .$post_address_data["郵送先郵便番号"] . '<br>';
        $body .= $post_address_data["郵送先住所1"];
        $body .= $post_address_data["郵送先住所2"] . '<br>';
        
        $body .= '</div>';


        $body .= '</div>';

        $body .= '<hr>';

        $body .= '<div style="text-align: center;font-size: 24px;font-weight: bold;margin-bottom: 20px;margin-top: 30px;">A-GATE OFFICIAL STORE</div>';

        $body .= '</div>';
       //  echo $body;
       if($pre_view){
            $body_array = array();
            $body_array["body"] = $body;
            $body_array["mail_subject"] = $mail_subject;
            $body_array["mail_to"] = $mail_to;

            return $body_array;
        }
        else{
            // 送信者名とアドレスをセット（ここが重要）
            $mail_headers  = "From: A-GATE OFFICIAL STORE <info@a-gate-kanri.com>\r\n";
            $mail_headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            wp_mail($mail_to, $mail_subject, $body, $mail_headers);

            return "";

        }

    }

    /****************************************************
    **  会員ステータスからのメール
    ******************************************************/
    public function sendMemberStatusMail( $user_id , $page_id,$spirit_data,$mail_type,$pre_view = false)
    {

        $users = get_userdata($user_id);

        $userClass = new SpiritUserClass();

        $mail_to = "";

        if($pre_view){
            $mail_to = "info@a-gate-kanri.com";
        }
        else{
            $mail_to = $users->user_email;
        }
        $mail_subject = $spirit_data["依頼名前"] . " " .get_field("acf_mail_title", $page_id);
       

        $body = "";

        $body .= '<div style="max-width: 500px;margin-right: auto;margin-left: auto;">';


            $body .= '<div style="text-align: left;margin-bottom: 20px;">';

            $body .= nl2br(get_field("acf_mail_text", $page_id));

            //「{施術名}」の文字列を$spirit_data["依頼名前"]に変換
            $body = str_replace("{施術名}", $spirit_data["依頼名前"], $body);
        
            $body .= '</div>';

            //再提出
            if($mail_type == SpiritUserClass::MAIL_TYPE_SHEET_RETURN_REPORT)
            {
                $body .= '<hr>';
                $body .= '<div style="text-align: left;margin-bottom: 20px;">';
                $body .= '<div style="font-size: 20px;font-weight: bold;margin-bottom: 20px;">【再提出依頼】</div>';
                $body .= nl2br($spirit_data["再提出依頼"]);
                $body .= '</div>';
            }
            //施術日決定
            else if($mail_type == SpiritUserClass::MAIL_TYPE_SCHEDULE_DECISION_REPORT)
            {
                if($spirit_data["実行予定日"] != "")
                {
                    $execution_date = $userClass->dispMemberStatus($spirit_data["実行予定日"]);

                    //日程確定・相談はそのまま表示
                    if($spirit_data["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY || $spirit_data["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){
                    
                        $execution_date = $spirit_data["実行日年月日"];

                        $body .= '<hr>';
                        $body .= '<div style="text-align: left;margin-bottom: 20px;">';
                        $body .= '<div style="font-size: 20px;font-weight: bold;margin-bottom: 20px;">【施術日決定】</div>';
                        $body .= nl2br($execution_date);
                    }
                    else{

                        //$execution_dateの一週間前の日付を取得
                        $execution_date_one_week_ago = date('Y年n月d日', strtotime($execution_date . ' -1 week'));

                        //$execution_dateの一週間後の日付を取得
                        $execution_date_one_week_after = date('Y年n月d日', strtotime($execution_date . ' +1 week'));

                        $body .= '<hr>';
                        $body .= '<div style="text-align: left;margin-bottom: 20px;">';
                        $body .= '<div style="font-size: 20px;font-weight: bold;margin-bottom: 20px;">【施術日決定】</div>';
                        $body .= nl2br($execution_date_one_week_ago . " ～ " . $execution_date_one_week_after . " 予定");
                        $body .= '</div>';
                    }
                }
               
                
                
              
            }


            $body .= '<hr>';

             $body .= '<div style="text-align: center;font-size: 24px;font-weight: bold;margin-bottom: 20px;margin-top: 30px;">A-GATE 運営 </div>';

        $body .= '</div>';

        if($pre_view){

            $body_array = array();
            $body_array["body"] = $body;
            $body_array["mail_subject"] = $mail_subject;
            $body_array["mail_to"] = $mail_to;

            return $body_array;
        }
        else{
             // 送信者名とアドレスをセット（ここが重要）
            $mail_headers  = "From: A-GATE 運営 <info@a-gate-kanri.com>\r\n";
            $mail_headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            wp_mail($mail_to, $mail_subject, $body, $mail_headers);

            return "";

        }
      

    }

    /****************************************************
    **  会員ステータスからの物販発送メール
    ******************************************************/
    public function sendSalesSendMail( $user_id , $page_id,$spirit_data,$mail_type,$pre_view = false)
    {
        $users = get_userdata($user_id);

        $userClass = new SpiritUserClass();

        $userData = $userClass->getUserAcountData($spirit_data["依頼者ID"]);

        $mail_to = "";

        if($pre_view){
            $mail_to = "info@a-gate-kanri.com";
        }
        else{
            $mail_to = $users->user_email;
        }
        $mail_subject = $spirit_data["依頼名前"] . " " .get_field("acf_mail_title", $page_id);
       

        $body = "";

        $body .= '<div style="max-width: 500px;margin-right: auto;margin-left: auto;">';


            $body .= '<div style="text-align: left;margin-bottom: 20px;">';

            $body .= nl2br(get_field("acf_mail_text", $page_id));

            //「{施術名}」の文字列を$spirit_data["依頼名前"]に変換（商品名）
            $body = str_replace("{商品名}", $spirit_data["依頼名前"], $body);
        
            $body .= '</div>';

            $body .= '<div style="text-align: left;margin-bottom: 20px;">';
            $body .= '<div style="font-size: 20px;font-weight: bold;margin-bottom: 20px;">【商品名】</div>';
            $body .= $spirit_data["依頼名前"] . '　';
            $body .= $spirit_data["販売個数"] . '点<br>';

            $body .= '<div style="font-size: 20px;font-weight: bold;margin-top: 20px;margin-bottom: 20px;">【送付日】</div>';
            $body .= $spirit_data["実行日年月日"] . '<br>';

            $body .= '<div style="font-size: 20px;font-weight: bold;margin-top: 20px;margin-bottom: 20px;">【郵送先】</div>';
            if($spirit_data["郵送先郵便番号"] != ""){
                $body .= "〒" . $spirit_data["郵送先郵便番号"] . '<br>';
                $body .= $spirit_data["郵送先住所1"] . '<br>';
                $body .= $spirit_data["郵送先住所2"] . '<br>';
                $body .= $spirit_data["郵送先名前"] . '<br>';
            }else{
                $body .= "〒" . $userData["郵便番号ハイフン"] . '<br>';
                $body .= $userData["住所"] . '<br>';
                $body .= $userData["フル名前"] . '<br>';
            }
            $body .= '</div>';

            $body .= '<hr>';

             $body .= '<div style="text-align: center;font-size: 24px;font-weight: bold;margin-bottom: 20px;margin-top: 30px;">A-GATE 運営 </div>';

        $body .= '</div>';
        // echo $body;

        if($pre_view){
            $body_array = array();
            $body_array["body"] = $body;
            $body_array["mail_subject"] = $mail_subject;
            $body_array["mail_to"] = $mail_to;

            return $body_array;
        }
        else{
             // 送信者名とアドレスをセット（ここが重要）
            $mail_headers  = "From: A-GATE 運営 <info@a-gate-kanri.com>\r\n";
            $mail_headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            wp_mail($mail_to, $mail_subject, $body, $mail_headers);

            return "";

        }
    }


    /****************************************************
    **  会員情報修正依頼からのメール
    ******************************************************/
    public function sendMemberStatusUpdateMail( $user_id , $page_id,$pre_view = false,$get_text = false)
    {

        $users = get_userdata($user_id);

        $userClass = new SpiritUserClass();

        $mail_to = "";

        if($pre_view){
            $mail_to = "info@a-gate-kanri.com";
        }
        else{
            $mail_to = $users->user_email;
        }
        $mail_subject =  get_field("acf_mail_title", $page_id);
       
        $body = "";

        $body .= '<div style="max-width: 500px;margin-right: auto;margin-left: auto;">';


            $body .= '<div style="text-align: left;margin-bottom: 20px;">';

            $body .= nl2br(get_field("acf_mail_text", $page_id));

            $body .= '</div>';

            $body .= '<div style="text-align: left;margin-bottom: 20px;">';

            $body .= "【修正内容】<br>";

            if($pre_view && !$get_text){

                $body .= "修正１<br>";
                $body .= "修正２<br>";
                $body .= "修正３<br>";
            }else{


                $member_update_reason = get_user_meta($user_id,"membersip_back_reason",true);

                if($member_update_reason != ""){
                    $body .= nl2br($member_update_reason);
                }

            }

            $body .= '</div>';

            $body .= '<hr>';

            $body .= '<div style="text-align: center;font-size: 24px;font-weight: bold;margin-bottom: 20px;margin-top: 30px;">A-GATE 運営 </div>';

        $body .= '</div>';

        if($pre_view){
            $body_array = array();
            $body_array["body"] = $body;
            $body_array["mail_subject"] = $mail_subject;
            $body_array["mail_to"] = $mail_to;

            return $body_array;
        }
        else{
             // 送信者名とアドレスをセット（ここが重要）
            $mail_headers  = "From: A-GATE 運営 <info@a-gate-kanri.com>\r\n";
            $mail_headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            wp_mail($mail_to, $mail_subject, $body, $mail_headers);

            return "";

        }

    }


    /****************************************************
    **  通常の汎用メール用
    ******************************************************/
    public function sendCommonMail( $user_id , $page_id,$pre_view = false,$get_text = false)
    {

        $users = get_userdata($user_id);

        $userClass = new SpiritUserClass();

        $mail_to = "";

        if($pre_view){
            $mail_to = "info@a-gate-kanri.com";
        }
        else{
            $mail_to = $users->user_email;
        }
        $mail_subject =  get_field("acf_mail_title", $page_id);
       
        $body = "";

        $body .= '<div style="max-width: 500px;margin-right: auto;margin-left: auto;">';


            $body .= '<div style="text-align: left;margin-bottom: 20px;">';

            if($pre_view && !$get_text){

                $body .= 'テスト様<br><br>';
            }
            else{
                $body .= $users->last_name . $users->first_name . '様<br><br>';
            }

            $body .= nl2br(get_field("acf_mail_text", $page_id));

            $body .= '</div>';

           

            $body .= '<hr>';

            $body .= '<div style="text-align: center;font-size: 24px;font-weight: bold;margin-bottom: 20px;margin-top: 30px;">A-GATE 運営 </div>';

        $body .= '</div>';

        if($pre_view){
            $body_array = array();
            $body_array["body"] = $body;
            $body_array["mail_subject"] = $mail_subject;
            $body_array["mail_to"] = $mail_to;

            return $body_array;
        }
        else{
             // 送信者名とアドレスをセット（ここが重要）
            $mail_headers  = "From: A-GATE 運営 <info@a-gate-kanri.com>\r\n";
            $mail_headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            wp_mail($mail_to, $mail_subject, $body, $mail_headers);

            return "";

        }

    }



    /****************************************************
    **  文言とタイトルを入れて、汎用的なメールを送信
    ******************************************************/
    public function sendSetCommonMail( $user_id,$set_mail_subject = "",$set_mail_body = "" ,$pre_view = false,$get_text = false)
    {

        $users = get_userdata($user_id);

        $userClass = new SpiritUserClass();

        $mail_to = "";

        if($pre_view){
            $mail_to = "info@a-gate-kanri.com";
        }
        else{
            $mail_to = $users->user_email;
        }
        $mail_subject = $set_mail_subject;
       
        $body = "";

        $body .= '<div style="max-width: 500px;margin-right: auto;margin-left: auto;">';


            $body .= '<div style="text-align: left;margin-bottom: 20px;">';

            $body .= nl2br($set_mail_body);

            $body .= '</div>';

           

            $body .= '<hr>';

            $body .= '<div style="text-align: center;font-size: 24px;font-weight: bold;margin-bottom: 20px;margin-top: 30px;">A-GATE 運営 </div>';

        $body .= '</div>';

        if($pre_view){
            $body_array = array();
            $body_array["body"] = $body;
            $body_array["mail_subject"] = $mail_subject;
            $body_array["mail_to"] = $mail_to;

            return $body_array;
        }
        else{
             // 送信者名とアドレスをセット（ここが重要）
            $mail_headers  = "From: A-GATE 運営 <info@a-gate-kanri.com>\r\n";
            $mail_headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            wp_mail($mail_to, $mail_subject, $body, $mail_headers);

            return "";

        }

    }
}

?>