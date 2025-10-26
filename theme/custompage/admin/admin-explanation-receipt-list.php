<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleCalendarClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");

    $spiritScheduleCalendar = new SpiritScheduleCalendarClass();
    $spiritSchedule = new SpiritScheduleClass();
    $spiritUser = new SpiritUserClass();
    $spiritSheet = new SpiritSheetClass();
    
    $schedule_array = $spiritSchedule->getSpritScheduleAllList();
    $spiritStatusArray = $spiritSheet->getSpiritAdminStatusID('cpt_spirit_status');//管理ステータス
    //var_dump($schedule_array);


    //請求書の初期設定番号
    $spiritReceiptSetting_id = 8625;

    //今月が何年何月かを取得
    $now_month = date("Y-m");
    $now_month_disp = date("Y年m月");

    //先月が何年何月かを取得
    $last_month = date("Y-m", strtotime("-1 month"));
    $last_month_disp = date("Y年m月", strtotime("-1 month"));


    //先々月が何年何月かを取得
    $before_month = date("Y-m", strtotime("-2 month"));
    $before_month_disp = date("Y年m月", strtotime("-2 month"));


    //
    if(isset($_GET['month'])){
      $disp_month = $_GET['month'];
    }else{
      $disp_month = $now_month;
    }

    $disp_month_disp = date("Y年m月", strtotime($disp_month));

    //$monthを分解して年と月を取得
    $year = date("Y", strtotime($disp_month));
    $month = date("n", strtotime($disp_month));//0なしで返す

    
    $schedule_array = $spiritSchedule->getSpritScheduleReceiptList( $year , $month );


    //$disp_monthの一月前を取得
    $before_month = date("Y-m", strtotime("-1 month", strtotime($disp_month)));

    //作成・保存
    if(isset($_POST["receipt_setting_save"])){
        $spiritSchedule->createScheduleReceipt($year,$month , $_POST , $disp_month_disp); 
    }

    //削除
    if(isset($_POST["receipt_setting_delete"])){
        wp_delete_post($_POST["receipt_setting_delete"]);
    }

    //var_dump($schedule_array[$_POST["receipt_setting_save"]]);
    //var_dump($_POST);


?>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
.admin-card {
  max-width: 1100px;
  margin: 40px auto 60px auto;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 2px 16px #b0c4de;
  padding: 32px 28px 28px 28px;
}
.admin-title {
  font-size: 1.5rem;
  color: #234a6f;
  font-weight: bold;
  margin-bottom: 32px;
  letter-spacing: 0.08em;
  text-align: center;
}
.admin-month-nav {
  display: flex;
  gap: 18px;
  justify-content: center;
  margin-bottom: 18px;
}
.admin-month-link {
  background: #e3f2fd;
  color: #1976d2;
  border-radius: 10px;
  padding: 8px 22px;
  font-weight: bold;
  text-decoration: none;
  transition: background 0.18s, color 0.18s;
  border: none;
  font-size: 1rem;
  box-shadow: 0 1px 6px #e0e7ef;
}
.admin-month-link:hover {
  background: #bbdefb;
  color: #0d47a1;
}
.admin-month-form {
  display: flex;
  align-items: center;
  gap: 8px;
  justify-content: center;
  margin-bottom: 18px;
}
.admin-list-area {
  margin-top: 32px;
  display: flex;
  flex-direction: column;
  gap: 28px;
  align-items: center;
}
.admin-list-area > form {
  display: contents;
}
.admin-list-card {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 2px 12px #e0e7ef;
  border: 1.5px solid #e3f2fd;
  padding: 24px 32px 18px 32px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  min-width: 0;
  min-height: 110px;
  position: relative;
  width: 100%;
  max-width: 900px;
}
.admin-list-row {
  display: flex;
  margin-bottom: 10px;
}
.admin-list-label {
  min-width: 90px;
  color: #1976d2;
  font-weight: bold;
  font-size: 1.02em;
  margin-right: 10px;
}
.admin-list-value {
  color: #333;
  font-size: 1.02em;
  word-break: break-all;
}
.admin-list-btn-area {
  margin-top: 18px;
  text-align: right;
}
.admin-list-btn {
  background: linear-gradient(90deg, #e3f2fd 0%, #bbdefb 100%);
  color: #1976d2;
  border: none;
  border-radius: 8px;
  font-weight: bold;
  padding: 8px 22px;
  cursor: pointer;
  transition: background 0.18s, color 0.18s;
  font-size: 1rem;
  box-shadow: 0 1px 6px #e0e7ef;
}
.admin-list-btn:hover {
  background: #bbdefb;
  color: #0d47a1;
}
.admin-list-btn.invoice {
  background: linear-gradient(90deg, #ffd180 0%, #ff8a65 100%);
  color: #fff;
  box-shadow: 0 1px 6px #ffccbc;
}
.admin-list-btn.invoice:hover {
  background: #ffab91;
  color: #fff;
}
.admin-list-btn.save {
  background: linear-gradient(90deg, #a5d6a7 0%, #66bb6a 100%);
  color: #fff;
  font-weight: bold;
  font-size: 1.08rem;
  box-shadow: 0 2px 12px rgba(102,187,106,0.13);
  border: none;
  border-radius: 8px;
  padding: 10px 36px;
  /*margin: 0 auto 18px auto;*/
  display: block;
  transition: background 0.2s, color 0.2s, box-shadow 0.2s;
}
.admin-list-btn.save:hover {
  background: linear-gradient(90deg, #66bb6a 0%, #a5d6a7 100%);
  color: #fff;
  box-shadow: 0 4px 18px rgba(102,187,106,0.18);
}
.admin-list-btn.delete {
  background: linear-gradient(90deg, #ff8a80 0%, #ff5252 100%);
  color: #fff;
  box-shadow: 0 1px 6px #ffccbc;
}
.admin-list-btn.delete:hover {
  background: #ff5252;
  color: #fff;
}
@media (max-width: 900px) {
  .admin-card { padding: 16px 2vw 24px 2vw; }
  .admin-list-area { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
  .admin-title { font-size: 1.1rem; }
  .admin-list-label, .admin-list-value { font-size: 0.95em; }
}
.admin-list-card-flex {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 32px;
}
.admin-list-info {
  flex: 1;
}
.admin-list-btns {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
}
@media (max-width: 700px) {
  .admin-list-card-flex {
    flex-direction: column;
    align-items: stretch;
    gap: 18px;
  }
  .admin-list-btns {
    align-items: stretch;
  }
}
.admin-link-row {
  display: flex;
  justify-content: center;
  gap: 18px;
  margin-bottom: 18px;
}
.admin-link-btn {
  display: inline-block;
  padding: 8px 22px;
  border-radius: 10px;
  font-weight: bold;
  font-size: 1rem;
  text-decoration: none;
  box-shadow: 0 1px 6px #e0e7ef;
  transition: background 0.18s, color 0.18s;
}
.admin-link-btn.receipt-setting {
  background: linear-gradient(90deg, #ffd180 0%, #ff8a65 100%);
  color: #fff;
  box-shadow: 0 1px 6px #ffccbc;
}
.admin-link-btn.receipt-setting:hover {
  background: #ffab91;
  color: #fff;
}
.admin-link-btn.jicchi-list {
  background: linear-gradient(90deg, #a5d6a7 0%, #66bb6a 100%);
  color: #fff;
  box-shadow: 0 2px 12px rgba(102,187,106,0.13);
}
.admin-link-btn.jicchi-list:hover {
  background: #81c784;
  color: #fff;
}
@media (max-width: 600px) {
  .admin-link-row {
    flex-direction: column;
    gap: 10px;
  }
}
.admin-setting-form-row {
  display: flex;
  align-items: center;
  margin-bottom: 18px;
  gap: 18px;
}
.admin-setting-label {
  min-width: 110px;
  color: #1976d2;
  font-weight: bold;
  font-size: 1.05em;
}
.admin-setting-input {
  flex: 1;
  font-size: 1.05em;
}
@media (max-width: 600px) {
  .admin-setting-form-row { flex-direction: column; align-items: stretch; gap: 6px; }
  .admin-setting-label { min-width: 0; }
}
.invoice-setting-row {
  display: flex;
  align-items: center;
  gap: 24px;
  margin-bottom: 12px;
  font-size: 1.05em;
}
.invoice-setting-row .setting-label {
  color: #1976d2;
  font-weight: bold;
  min-width: 90px;
  margin-right: 6px;
}
.invoice-setting-row .setting-input {
  color: #333;
  font-weight: normal;
}
.invoice-setting-row input[type="number"] {
  width: 60px;
  padding: 6px 10px;
  border-radius: 6px;
  border: 1px solid #b0c4de;
  font-size: 1em;
  background: #fff;
}
.invoice-setting-row input[type="text"] {
  width: 100%;
  padding: 6px 10px;
  border-radius: 6px;
  border: 1px solid #b0c4de;
  font-size: 1em;
  background: #fff;
}
.invoice-setting-row-full {
  display: flex;
  align-items: center;
  gap: 18px;
  margin-bottom: 18px;
}
.invoice-setting-row-full .setting-label {
  color: #1976d2;
  font-weight: bold;
  min-width: 110px;
}
.invoice-setting-row-full .setting-input {
  flex: 1;
}
@media (max-width: 700px) {
  .invoice-setting-row, .invoice-setting-row-full {
    flex-direction: column;
    align-items: stretch;
    gap: 8px;
  }
  .invoice-setting-row input[type="number"], .invoice-setting-row-full input[type="text"] {
    width: 100%;
  }
}
.invoice-btn-row {
  display: flex;
  justify-content: center;
  gap: 32px;
  margin-top: 18px;
  margin-bottom: 0;
}
.invoice-action-btn {
  width: 220px;
  height: 48px;
  font-size: 1.15rem;
  border-radius: 10px;
  box-sizing: border-box;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  margin: 0;
  padding: 0;
}
@media (max-width: 600px) {
  .invoice-btn-row {
    flex-direction: column;
    gap: 12px;
    align-items: stretch;
  }
  .invoice-action-btn {
    width: 100%;
    min-width: 0;
  }
}
.invoice-setting-row-double {
  display: flex;
  align-items: center;
  gap: 32px;
  margin-bottom: 18px;
}
.invoice-setting-row-double .setting-label {
  color: #1976d2;
  font-weight: bold;
  min-width: 110px;
}
.invoice-setting-row-double .setting-input {
  flex: 1;
}
@media (max-width: 700px) {
  .invoice-setting-row-double {
    flex-direction: column;
    gap: 8px;
    align-items: stretch;
  }
}
.receipt-reason-area {
  margin: 32px 0 24px 0;
}
.receipt-reason-title {
  font-weight: bold;
  color: #1976d2;
  font-size: 1.08em;
  margin-bottom: 8px;
}
.receipt-reason-box {
  border: 1.5px solid #b0c4de;
  border-radius: 8px;
  background: #f8fbff;
  padding: 18px 16px;
  font-size: 1.05em;
  color: #333;
  white-space: pre-wrap;
  min-height: 48px;
  text-align: left;
}
</style>

<div class="admin-card">
  <div class="admin-title">相談請求書リスト</div>
  <div class="admin-link-row">
    <a href="<?php echo getURLSetSlag('admin-receipt-setting'); ?>" target="_blank" class="admin-link-btn receipt-setting">請求書設定</a>
    <a href="<?php echo getURLSetSlag('admin-schedule-execution-list'); ?>?month=<?php echo $before_month; ?>" class="admin-link-btn jicchi-list">実地リスト</a>
</div>

  <div class="admin-month-nav">
    <a class="admin-month-link" href="<?php echo getURLSetSlag('admin-explanation-receipt-list'); ?>?month=<?php echo $now_month; ?>"><?php echo $now_month_disp; ?></a>
    <a class="admin-month-link" href="<?php echo getURLSetSlag('admin-explanation-receipt-list'); ?>?month=<?php echo $last_month; ?>"><?php echo $last_month_disp; ?></a>
    <a class="admin-month-link" href="<?php echo getURLSetSlag('admin-explanation-receipt-list'); ?>?month=<?php echo $before_month; ?>"><?php echo $before_month_disp; ?></a>
  </div>
  <form class="admin-month-form" action="<?php echo getURLSetSlag('admin-explanation-receipt-list'); ?>" method="get">
    <input type="month" name="month" value="<?php echo $disp_month; ?>">
    <input type="submit" value="検索" class="admin-list-btn">
  </form>
  <div style="text-align:center;font-weight:bold;font-size:1.1em;margin-bottom:18px;"><?php echo $disp_month_disp; ?>分　領収書一覧</div>

  <div class="admin-list-area">
    <?php if(count($schedule_array) > 0){ ?>
        <?php foreach($schedule_array as $key => $value){ ?>


        <?php 
        
            $receipt_data = $spiritSchedule->getScheduleReceipt($year,$month,$key);


           // var_dump($schedule_array);
        ?>

        <div class="admin-list-card">
          <div class="admin-list-card-flex">
            <div class="admin-list-info">
              <div class="admin-list-row">
                <div class="admin-list-label">担当者</div>
                <div class="admin-list-value"><?php echo $value["担当者"]; ?></div>
              </div>
              <div class="admin-list-row">
                <div class="admin-list-label">領収書</div>
                <div class="admin-list-value"><?php echo $disp_month_disp; ?>分</div>
              </div>
              <div class="admin-list-row">
                <div class="admin-list-label">合計金額</div>
                <div class="admin-list-value">
                
                <?php if($receipt_data == ""){?>
                    <?php echo $value["合計支払い金額"]; ?>円
                <?php }else{?>
                    <?php echo get_field('acf_invoice_total_pay', $receipt_data); ?>円　(最新:<?php echo $value["合計支払い金額"]; ?>円)
                <?php }?>
                
               
                </div>
              </div>
              <div class="admin-list-row">
                <div class="admin-list-label">合計件数</div>
                <div class="admin-list-value">
                    <?php if($receipt_data == ""){?>
                        <?php echo count($value["ID"]); ?>件
                    <?php }else{?>
                        <?php echo get_field('acf_invoice_sprit_count', $receipt_data); ?>件　(最新:<?php echo count($value["ID"]); ?>件)
                    <?php }?>
                </div>
              </div>
            </div>

            
              
            <div class="admin-list-btns">


                <?php if($receipt_data != "" && ( get_field('acf_invoice_sprit_count', $receipt_data) != count($value["ID"]) || get_field('acf_invoice_total_pay', $receipt_data) != $value["合計支払い金額"])){ ?>
                    <form action="<?php echo getURLSetSlag('admin-explanation-receipt'); ?>?new=true" method="post" target="_blank" >
                        <input type="hidden" name="receipt_user_id" value="<?php echo $key; ?>">
                        <input type="hidden" name="receipt_month" value="<?php echo $month; ?>">
                        <input type="hidden" name="receipt_year" value="<?php echo $year; ?>">
                        <input type="submit" value="最新の領収書を確認" class="admin-list-btn"  style="width:190px;">
                    </form>
                    <form action="<?php echo getURLSetSlag('admin-explanation-invoice'); ?>?new=true" method="post"target="_blank" >
                        <input type="hidden" name="receipt_user_id" value="<?php echo $key; ?>">
                        <input type="hidden" name="receipt_month" value="<?php echo $month; ?>">
                        <input type="hidden" name="receipt_year" value="<?php echo $year; ?>">
                        <input type="submit" value="最新の請求書を確認" class="admin-list-btn invoice"  style="width:190px;">
                    </form>
                <?php } ?>

                <form action="<?php echo getURLSetSlag('admin-explanation-receipt'); ?>" method="post" target="_blank">
                    <input type="hidden" name="receipt_user_id" value="<?php echo $key; ?>">
                    <input type="hidden" name="receipt_month" value="<?php echo $month; ?>">
                    <input type="hidden" name="receipt_year" value="<?php echo $year; ?>">
                    <?php if($receipt_data != ""){?>
                        <input type="hidden" name="receipt_id" value="<?php echo $receipt_data; ?>">
                        <input type="submit" value="作成した領収書を確認" class="admin-list-btn"  style="width:190px;">
                    <?php }else{?>
                        <input type="submit" value="領収書を確認" class="admin-list-btn"  style="width:190px;">
                    <?php }?>
                </form>
                <form action="<?php echo getURLSetSlag('admin-explanation-invoice'); ?>" method="post"target="_blank">
                    <input type="hidden" name="receipt_user_id" value="<?php echo $key; ?>">
                    <input type="hidden" name="receipt_month" value="<?php echo $month; ?>">
                    <input type="hidden" name="receipt_year" value="<?php echo $year; ?>">
                    <?php if($receipt_data != ""){?>
                        <input type="hidden" name="receipt_id" value="<?php echo $receipt_data; ?>">
                        <input type="submit" value="作成した請求書を確認" class="admin-list-btn invoice"  style="width:190px;">
                    <?php }else{?>
                        <input type="submit" value="請求書を確認" class="admin-list-btn invoice"  style="width:190px;">
                    <?php }?>
                </form>
            </div>
           

        </div>
        
        <div>
            <form action="<?php echo getURLSetSlag('admin-explanation-receipt-list'); ?>?month=<?php echo $disp_month; ?>" method="post" >
                <input type="hidden" name="receipt_setting_save" value="<?php echo $key; ?>">
                <div>
                    <?php 
                        $invoice_day = "";
                        
                        if($receipt_data == "")
                        {
                            $invoice_day = get_field('acf_invoice_day', $spiritReceiptSetting_id);
                        }
                        else{
                            $invoice_day = get_field('acf_invoice_day', $receipt_data);
                        }

                        $invoice_pay_day = "";

                        if($receipt_data == "")
                        {
                            $invoice_pay_day = get_field('acf_invoice_pay_day', $spiritReceiptSetting_id);
                        }
                        else{
                            $invoice_pay_day = get_field('acf_invoice_pay_day', $receipt_data);
                        }

                        $acf_invoice_adjustment_costs = "";

                        if($receipt_data == "")
                        {
                            $acf_invoice_adjustment_costs = 0;
                        }
                        else{
                            $acf_invoice_adjustment_costs = get_field('acf_invoice_adjustment_costs', $receipt_data);
                        }

                        if($acf_invoice_adjustment_costs == "")
                        {
                            $acf_invoice_adjustment_costs = 0;
                        }

                        $acf_invoice_communication_expenses = "";

                        if($receipt_data == "")
                        {
                          $acf_invoice_communication_expenses = 0;
                        }
                        else{
                          $acf_invoice_communication_expenses = get_field('acf_invoice_communication_expenses', $receipt_data);
                        }

                        if($acf_invoice_communication_expenses == "")
                        {
                            $acf_invoice_communication_expenses = 0;
                        }

                        $acf_invoice_transportation_expenses = "";

                        if($receipt_data == "")
                        {
                          $acf_invoice_transportation_expenses = 0;
                        }
                        else{
                          $acf_invoice_transportation_expenses = get_field('acf_invoice_transportation_expenses', $receipt_data);
                        }

                        if($acf_invoice_transportation_expenses == "")
                        {
                            $acf_invoice_transportation_expenses = 0;
                        }

                        $acf_invoice_else_expenses = "";

                        if($receipt_data == "")
                        {
                          $acf_invoice_else_expenses = 0;
                        }
                        else{
                          $acf_invoice_else_expenses = get_field('acf_invoice_else_expenses', $receipt_data);
                        }

                        if($acf_invoice_else_expenses == "")
                        {
                            $acf_invoice_else_expenses = 0;
                        }
                    ?>
                    <div class="invoice-setting-row">
                      <span class="setting-label">請求日</span>
                      <span class="setting-input">毎月 <input type="number" name="acf_invoice_day" value="<?php echo $invoice_day; ?>" min="1" max="31" step="1" /> 日</span>
                      <span class="setting-label">お振込期限</span>
                      <span class="setting-input">毎月 <input type="number" name="acf_invoice_pay_day" value="<?php echo $invoice_pay_day; ?>" min="1" max="31" step="1" /> 日</span>
                      <span class="setting-label">外注特別費</span>
                      <span class="setting-input"><input type="number" name="acf_invoice_adjustment_costs" value="<?php echo $acf_invoice_adjustment_costs; ?>" style="width: 100px;"/></span>
                    
                    </div>

                    <div class="invoice-setting-row">
                      <span class="setting-label">通信費</span>
                      <span class="setting-input"><input type="number" name="acf_invoice_communication_expenses" value="<?php echo $acf_invoice_communication_expenses; ?>" style="width: 100px;"/></span>
                      <span class="setting-label">交通費</span>
                      <span class="setting-input"><input type="number" name="acf_invoice_transportation_expenses" value="<?php echo $acf_invoice_transportation_expenses; ?>" style="width: 100px;"/></span>
                      <span class="setting-label">その他</span>
                      <span class="setting-input"><input type="number" name="acf_invoice_else_expenses" value="<?php echo $acf_invoice_else_expenses; ?>" style="width: 100px;"/></span>
                   

                    </div>
                        
                    <?php 
                        $invoice_post_name= "";
                        $invoice_registration_number = "";


                        if($receipt_data == "")
                        {
                            $invoice_post_name = get_field('acf_invoice_post_name', $spiritReceiptSetting_id);
                        }
                        else{
                            $invoice_post_name = get_field('acf_invoice_post_name', $receipt_data);
                        }

                        if($receipt_data == "")
                        {
                            $invoice_registration_number = get_field('acf_invoice_registration_number', $spiritReceiptSetting_id);
                        }
                        else{
                            $invoice_registration_number = get_field('acf_invoice_registration_number', $receipt_data);
                        }
                    ?>
                    <div class="invoice-setting-row-double">
                      <span class="setting-label">請求書の宛先</span>
                      <span class="setting-input"><input type="text" name="acf_invoice_post_name" value="<?php echo $invoice_post_name; ?>" /></span>
                      <span class="setting-label">宛先の登録番号</span>
                      <span class="setting-input"><input type="text" name="acf_invoice_registration_number" value="<?php echo $invoice_registration_number; ?>" /></span>
                    </div>

                    <?php if($receipt_data != ""){?>
                      <div class="invoice-setting-row-double">
                        <span class="setting-label">担当者確認</span>
                        <?php 
                            $invoice_check = get_field('acf_invoice_check', $receipt_data);

                            if($invoice_check == ""){
                              $invoice_check = 0;
                            }
                        ?>
                        <span class="setting-input">
                          <select name="acf_invoice_check">
                            <option value="0" <?php if($invoice_check == 0){ echo "selected"; } ?>>未確認</option>
                            <option value="1" <?php if($invoice_check == 1){ echo "selected"; } ?>>再申請</option>
                            <option value="2" <?php if($invoice_check == 2){ echo "selected"; } ?>>確認済み</option>
                            <option value="3" <?php if($invoice_check == 3){ echo "selected"; } ?>>再確認</option>
                          </select>
                        </span>
                        <span class="setting-label">管理者対応</span>
                        <?php 
                            $payment_check = get_field('acf_invoice_payment_check', $receipt_data);

                            if($payment_check == ""){
                              $payment_check = 0;
                            }
                        ?>

                        <span class="setting-input">
                          <select name="acf_invoice_payment_check">
                            <option value="0" <?php if($payment_check == 0){ echo "selected"; } ?>>待機中</option>
                            <option value="1" <?php if($payment_check == 1){ echo "selected"; } ?>>完了</option>
                            
                          </select>
                        </span>
                      </div>
                    <?php } ?>
                </div>


                <?php if($receipt_data != "" && $invoice_check == 1){ //再申請のみ?>
                  <div class="receipt-reason-area">
                    <div class="receipt-reason-title">再申請理由</div>
                    <div class="receipt-reason-box"><?php echo trim(get_field('acf_invoice_txt', $receipt_data)); ?></div>
                  </div>
                <?php }?>
                <?php if($receipt_data == ""){?>
                    <div class="invoice-btn-row">
                      <input type="submit" value="発行" class="admin-list-btn save invoice-action-btn">
                    </div>
                    <div style="font-size:0.9em;text-align: center;margin-top:10px;">まだ請求書・領収書は発行しておりません。確認し、問題なければ発行ボタンを押してください</div>
                <?php }else{?>
                    <div class="invoice-btn-row">
                      <form action="<?php echo getURLSetSlag('admin-explanation-receipt-list'); ?>?month=<?php echo $disp_month; ?>" method="post" onsubmit="return confirm('本当に更新しますか？');" style="display:inline;">
                        <input type="hidden" name="receipt_setting_save" value="<?php echo $key; ?>">
                        <input type="submit" value="更新" class="admin-list-btn save invoice-action-btn">
                      </form>
                      <form action="<?php echo getURLSetSlag('admin-explanation-receipt-list'); ?>?month=<?php echo $disp_month; ?>" method="post" onsubmit="return confirm('本当に削除しますか？この操作は元に戻せません。');" style="display:inline;">
                        <input type="hidden" name="receipt_setting_delete" value="<?php echo $receipt_data; ?>">
                        <input type="submit" value="領収書を削除" class="admin-list-btn delete invoice-action-btn">
                      </form>
            </div>
                    <div style="font-size:0.9em;text-align: center;margin-top:10px;">情報が変わっている場合は、一度削除し、再度、更新してください。</div>
                <?php }?>
            </form>
            
        </div>
        </div>
        <?php } ?>
    <?php }else{ ?>
      <div style="text-align:center;color:#888;font-size:1.1em;">該当月の領収書・請求書はありません</div>
    <?php } ?>
  </div>
</div>