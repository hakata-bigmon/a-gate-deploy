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




$userData = $userClass->getUserAcountData($spirit_data["依頼者ID"]);



$title ="";
$payment_setting = array();


if($spirit_data["スケジュール"] != "")
{
    $set_spirit_sheet = $spiritScheduleData->getSpritScheduledetail($spirit_data["スケジュール"]);
    $title = $set_spirit_sheet["表示名"];
    $payment_setting = $set_spirit_sheet["支払い方法"];

    //var_dump($payment_setting);
}
else
{
    $set_spirit_sheet = array();

    $title = $spirit_data["依頼名前"];
    $payment_setting = $spirit_data["支払い設定"];
}
   
/*
 var_dump($spirit_data);

     echo "<br>";
     echo "<br>";
     var_dump($set_spirit_sheet);
*/

?>



<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@500&display=swap');
    
    * {
        font-family: 'Noto Sans JP', sans-serif;
    }
    
    .payment-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .payment-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .payment-header h1 {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    
    .payment-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 30px;
        margin-bottom: 40px;
        border: 1px solid #e9ecef;
    }
    
    .payment-info h2 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 0 0 20px 0;
        text-align: center;
    }
    
    .payment-detail {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #dee2e6;
    }
    
    .payment-detail:last-child {
        border-bottom: none;
    }
    
    .payment-label {
        font-size: 16px;
        font-weight: 500;
        color: #666;
        min-width: 120px;
    }
    
    .payment-value {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        text-align: right;
    }
    
    .payment-methods {
        margin-top: 40px;
    }
    
    .payment-method-item {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    
    .payment-method-item:hover {
        border-color: #A078D0;
        box-shadow: 0 4px 12px rgba(160, 120, 208, 0.15);
    }
    
    .payment-method-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #A078D0;
    }
    
    .payment-method-content {
        font-size: 14px;
        line-height: 1.6;
        color: #666;
    }
    
    .payment-form {
        margin-top: 20px;
    }
    
    .payment-submit-btn {
        background: #A078D0;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .payment-submit-btn:hover {
        background: #8B5BB3;
        transform: translateY(-1px);
    }
    
    .bank-info {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 20px;
        margin-top: 15px;
        border-left: 4px solid #A078D0;
    }
    
    .bank-info p {
        margin: 8px 0;
        font-size: 14px;
        color: #555;
    }
    
    .bank-info strong {
        color: #333;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .payment-container {
            padding: 15px;
        }
        
        .payment-detail {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        
        .payment-value {
            text-align: left;
        }
    }
</style>

<div class="payment-container">
    <div class="payment-header">
        <h1><?php echo $title;?>　支払い方法</h1>
    </div>

    <div class="payment-info">
        <h2>お支払い情報</h2>
        <div class="payment-detail">
            <div class="payment-label">申込日技</div>
            <div class="payment-value"><?php echo $spirit_data["依頼日年月日"];?></div>
        </div>

        <?php if($spirit_data["スケジュール"] != ""){?>
            <div class="payment-detail">
                <div class="payment-label">日時</div>
                <div class="payment-value"><?php echo $set_spirit_sheet["実行年月日"];?></div>
            </div>
        <?php }?>
        
        <div class="payment-detail">
            <div class="payment-label">お支払い金額</div>
            <div class="payment-value">￥<?php echo $spirit_data["価格"]; ?>（税込）</div>
        </div>
        <div class="payment-detail">
            <div class="payment-label">お支払い方法</div>
            <div class="payment-value">
                <?php if($spirit_data["支払いタイプ"] != ""){?>
                    <?php echo $userClass->getPaymentTypeLabel($spirit_data["支払いタイプ"]);?>
                <?php }else{?>
                    <?php 
                        $payment_methods = array();
                        foreach($payment_setting as $key => $value){
                            $payment_methods[] = $userClass->getPaymentTypeLabel($value);
                        }
                        echo implode('、', $payment_methods);
                    ?>
                <?php }?>
            </div>
        </div>
        <?php if($spirit_data["支払いタイプ"] != ""){?>
            <div class="payment-detail">
                <div class="payment-label">お支払い期限</div>
                <div class="payment-value">
                    <?php if($spirit_data["支払いタイプ"] != SpiritUserClass::PAYMENT_TYPE_TRANSFER){?>
                        申込日時より１日以内
                    <?php }else{?>
                        申込日時より営業日３日以内
                    <?php }?>
                </div>
            </div>
        <?php }?>
    </div>




    <div class="payment-methods">
        <?php foreach($payment_setting as $key => $value){?>

            <?php 
                
                if($spirit_data["支払いタイプ"] != "")
                {
                    if($spirit_data["支払いタイプ"] != $value)
                    {
                        continue;
                    }
                  
                }    
            ?>

            <div class="payment-method-item">   
                <div class="payment-method-title">
                    <?php echo $userClass->getPaymentTypeLabel($value);?>
                </div>

                <?php if($value == SpiritUserClass::PAYMENT_TYPE_CREDIT){ //クレジット ?>
                    <div class="payment-method-content">
                        <p>クレジットカードでのお支払いを行います。</p>
                        <div class="payment-form">
                            <form action="<?php echo getURLSetSlag("users/user-schedule-thanks"); echo $get_url["add"];?>" method="post" onsubmit="return confirmPayment()">
                                <input type="hidden" name="sheet_id" value="<?php echo $sheet_id;?>">
                                <button type="submit" name="payment_method" class="payment-submit-btn">クレジットカードで支払う</button>
                            </form>
                        </div>
                        
                        <script>
                        function confirmPayment() {
                            const message = `クレジットカードでのお支払いを実行しますか？\n\n※この操作は取り消せません。`;
                            return confirm(message);
                        }
                        </script>
                    </div>
                <?php }else if($value == SpiritUserClass::PAYMENT_TYPE_TRANSFER){?>
                    <div class="payment-method-content">
                        <p>下記の口座にお振込みください</p>
                        <div class="bank-info">
                            <p><strong>銀行名：</strong>住信SBIネット銀行 (0038)</p>
                            <p><strong>支店名：</strong>法人第一支店 (106)</p>
                            <p><strong>口座種別：</strong>普通</p>
                            <p><strong>口座番号：</strong>1399898</p>
                            <p><strong>口座名義：</strong>カ）エーゲート</p>
                        </div>
                    </div>
                <?php }else if($value == SpiritUserClass::PAYMENT_TYPE_E_MONEY){?>
                    <div class="payment-method-content">
                        <p>電子マネーでのお支払いについて</p>
                        <p>詳細は別途ご案内いたします。</p>
                    </div>
                <?php }else if($value == SpiritUserClass::PAYMENT_TYPE_CONVENIENCE){?>
                    <div class="payment-method-content">
                        <p>コンビニ決済でのお支払いについて</p>
                        <p>詳細は別途ご案内いたします。</p>
                    </div>
                <?php }else if($value == SpiritUserClass::PAYMENT_TYPE_CASH){?>
                    <div class="payment-method-content">
                        <p>現金でのお支払いについて</p>
                        <p>当日現金でのお支払いとなります。</p>
                    </div>
                <?php }else if($value == SpiritUserClass::PAYMENT_TYPE_TRANSIT){?>
                    <div class="payment-method-content">
                        <p>交通系電子マネーでのお支払いについて</p>
                        <p>詳細は別途ご案内いたします。</p>
                    </div>
                <?php }?>

            </div>

        <?php }?>
    </div>
</div>


<div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
    <form action="<?php echo getURLSetSlag("users/user-spirit-detail"); echo $get_url["add"];?>" method="post">
        <button type="submit"  class="user-account-edit-return-btn">詳細を確認する</button>
        <input type="hidden"  name="sheet_id"  value="<?php echo $sheet_id; ?>">
    </form>
</div>



<div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 20px;">
    <a href="<?php echo getURLSetSlag("users/user-in-progress-spirit-list"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">履歴一覧へ  &gt;</a>
</div>
<div class="user-account-edit-form-btn-wrap" style="margin-top: 10px;margin-bottom: 100px;">
    <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
</div>