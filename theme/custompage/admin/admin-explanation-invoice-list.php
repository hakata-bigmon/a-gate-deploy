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


    $check_user_id = get_current_user_id();

   // $check_user_id = 209;//仮

    //今月が何年何月かを取得
    $now_year = date("Y");
    $now_year_disp = date("Y年");

   
    //var_dump($_POST);
    //
    if(isset($_GET['check_year'])){
      $disp_year = $_GET['check_year'];
    }else{
      $disp_year = $now_year;
    }

    //更新
    if(isset($_POST['receipt_save'])){
      
      $receipt_id = $_POST['receipt_id'];

      update_field('acf_invoice_check', $_POST['acf_invoice_check'], $receipt_id);
      update_field('acf_invoice_txt', $_POST['acf_invoice_txt'], $receipt_id);

    }

    //領収書を取得
    $receipt_array = $spiritSchedule->getScheduleUserUserReceiptList($disp_year , $check_user_id) ;

    //var_dump($receipt_array);

?>

<style>
.admin-card {
  max-width: 1100px;
  margin: 40px auto 60px auto;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 2px 16px #b0c4de;
  padding: 32px 28px 28px 28px;
  margin-left: 10px;
  margin-right: 10px;
}
.admin-title {
  font-size: 1.5rem;
  color: #234a6f;
  font-weight: bold;
  margin-bottom: 32px;
  letter-spacing: 0.08em;
  text-align: center;
}
.admin-month-form {
  display: flex;
  align-items: center;
  gap: 8px;
  justify-content: center;
  margin-bottom: 18px;
}
.invoice-list-area {
  margin-top: 32px;
  display: flex;
  flex-direction: column;
  gap: 28px;
  align-items: center;
}
@media (max-width: 900px) {
  .admin-card { padding: 16px 2vw 24px 2vw; }
}
@media (max-width: 600px) {
  .admin-title { font-size: 1.1rem; }
  .invoice-list-label, .invoice-list-value { font-size: 0.95em; }
}
.invoice-list-card {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 2px 16px #b0c4de;
  padding: 32px 28px 28px 28px;
  margin-left: 10px;
  margin-right: 10px;
  position: relative;
  min-width: 360px;
}
.invoice-list-btn-float {
  position: absolute;
  top: 24px;
  right: 8px;
  z-index: 2;
}
.invoice-list-row.flex-row {
  display: flex;
  align-items: center;
  gap: 18px;
  margin-bottom: 8px;
}
.invoice-list-row.flex-row .invoice-list-label {
  min-width: 70px;
  font-weight: bold;
  color: #1976d2;
}
.invoice-list-row.flex-row .invoice-list-value {
  font-size: 1.08em;
  font-weight: bold;
}
.invoice-list-row.status-row {
  display: flex;
  align-items: center;
  gap: 18px;
  margin-bottom: 0;
}
.invoice-list-status-actions {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  margin-top: 8px;
  gap: 10px;
}
.invoice-list-status-group.apply-group {
  display: flex;
  align-items: center;
  gap: 10px;
}
.invoice-list-status-group.apply-group select,
.invoice-list-status-group.apply-group input[type="text"] {
  padding: 4px 8px;
  border-radius: 6px;
  border: 1px solid #b0c4de;
  background: #fff;
  font-size: 0.95em;
  color: #333;
  min-width: 100px;
}
.invoice-list-status-group.apply-group .invoice-list-btn {
  margin-left: 8px;
  padding: 4px 16px;
  font-size: 0.98em;
}
.invoice-list-label {
  min-width: 70px;
  color: #1976d2;
  font-weight: bold;
  font-size: 1.02em;
}
.invoice-list-value {
  color: #333;
  font-size: 1.02em;
  word-break: break-all;
}
.invoice-badge {
  display: inline-block;
  padding: 3px 14px;
  border-radius: 12px;
  font-size: 0.98em;
  font-weight: bold;
}
.badge-unchecked { background: #fff3e0; color: #ff9800; border: 1px solid #ffb74d; }
.badge-checked { background: #e3f2fd; color: #1976d2; border: 1px solid #90caf9; }
.badge-reapply { background: #ffebee; color: #e53935; border: 1px solid #ef9a9a; }
.badge-paid { background: #e8f5e9; color: #388e3c; border: 1px solid #a5d6a7; }
.invoice-list-btn-row {
  display: flex;
  gap: 18px;
  margin-top: 12px;
  justify-content: flex-end;
}
.invoice-list-btn {
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
.invoice-list-btn.invoice {
  background: linear-gradient(90deg, #ffd180 0%, #ff8a65 100%);
  color: #fff;
  box-shadow: 0 1px 6px #ffccbc;
}
.invoice-list-btn:hover {
  background: #bbdefb;
  color: #0d47a1;
}
@media (max-width: 700px) {
  .invoice-list-card { padding: 12px 2vw 12px 2vw; }
  .invoice-list-btn-float {  margin-bottom: 10px; display: block; text-align: right; }
  .invoice-list-row.flex-row { flex-direction: column; align-items: flex-start; gap: 2px; }
  .invoice-list-row.status-row { flex-direction: column; align-items: flex-start; gap: 8px; }
  .invoice-list-status-actions { justify-content: stretch; width: 100%; }
}

/* 修正された申請セクションのスタイル */
.invoice-list-status-group {
  display: flex;
  align-items: center;
  margin-bottom: 8px;
}

.invoice-list-status-group.application-section {
  display: flex;
  align-items: flex-start;
  margin-bottom: 8px;
}

.invoice-list-status-group.application-section .invoice-list-label {
  min-width: 70px;
  padding-top: 6px; /* セレクトボックスとラベルの高さを合わせる */
}

.application-controls {
  display: flex;
  flex-direction: column;
  gap: 10px;
  width: 100%;
}

.application-controls-row {
  display: flex;
  align-items: center;
  gap: 10px;
}

.application-controls-row.text-row {
  margin-top: 5px;
  width: 100%;
}

.invoice-list-status-group select {
  padding: 4px 8px;
  border-radius: 6px;
  border: 1px solid #b0c4de;
  background: #fff;
  font-size: 0.95em;
  color: #333;
  min-width: 100px;
}

.invoice-list-status-group .invoice-list-btn {
  padding: 4px 12px;
  font-size: 0.95em;
}

.reapply-reason-wrap {
  display: flex;
  align-items: center;
  width: 100%;
}

.reapply-reason-wrap input[type="text"] {
  padding: 4px 8px;
  border-radius: 6px;
  border: 1px solid #b0c4de;
  background: #fff;
  font-size: 0.95em;
  color: #333;
  width: 100%;
}
</style>

<div class="admin-card">
  <div class="admin-title">請求書・領収書リスト</div>
  <form class="admin-month-form" action="<?php echo getURLSetSlag('admin-explanation-invoice-list'); ?>" method="get">
    <input type="number" name="check_year" min="2024" max="5099" value="<?php echo $disp_year; ?>">年
    <input type="submit" value="検索" class="admin-list-btn">
  </form>
  <div style="text-align:center;font-weight:bold;font-size:1.1em;margin-bottom:18px;">
    <?php echo $disp_year; ?>年分
  </div>
  <div class="invoice-list-area">
    <?php if(count($receipt_array) > 0){ ?>
      <?php foreach($receipt_array as $key => $value){ ?>


        <?php 
            $payment_check = get_field('acf_invoice_payment_check', $value);
            if($payment_check == ""){ $payment_check = 0; }

            $invoice_check = get_field('acf_invoice_check', $value);

            if($invoice_check == ""){
                $invoice_check = 0;
            }
        ?>
        <?php
            $acf_invoice_issue_year = get_field('acf_invoice_issue_year', $value);
            $disp_date = date("Y年n月", strtotime($acf_invoice_issue_year  . "-01"));
            $set_year = date("Y", strtotime($acf_invoice_issue_year  . "-01"));
            $set_month = date("n", strtotime($acf_invoice_issue_year  . "-01"));
        ?>
        <div class="invoice-list-card">
          <div class="invoice-list-btn-float">
            <?php if($payment_check == 0){ ?>
              <form action="<?php echo getURLSetSlag('admin-explanation-invoice'); ?>" method="post" target="_blank">
                <input type="hidden" name="receipt_user_id" value="<?php echo $check_user_id; ?>">
                <input type="hidden" name="receipt_month" value="<?php echo $set_month; ?>">
                <input type="hidden" name="receipt_year" value="<?php echo $set_year; ?>">
                <input type="submit" value="請求書を確認" class="invoice-list-btn invoice">
              </form>
            <?php }else if($payment_check == 1){ ?>
              <form action="<?php echo getURLSetSlag('admin-explanation-receipt'); ?>" method="post" target="_blank">
                <input type="hidden" name="receipt_user_id" value="<?php echo $check_user_id; ?>">
                <input type="hidden" name="receipt_month" value="<?php echo $set_month; ?>">
                <input type="hidden" name="receipt_year" value="<?php echo $set_year; ?>">
                <input type="submit" value="領収書を確認" class="invoice-list-btn">
              </form>
            <?php } ?>
          </div>
          <div class="invoice-list-row flex-row">
            <div style="display:flex;">
                <div class="invoice-list-label">対象月</div>
                <div class="invoice-list-value">
              
                <?php echo $disp_date;?>分
                </div>
            </div>
          </div>
          <div class="invoice-list-row flex-row">
            <div style="display:flex;">
              <div class="invoice-list-label">金　額</div>
              <div class="invoice-list-value"><?php echo number_format(get_field('acf_invoice_total_pay', $value)); ?>円</div>
            </div>
          </div>
          <div class="">
            <div class="invoice-list-status-group">
              <div class="invoice-list-label">運　営</div>
              <div class="invoice-list-value">
                <?php if($payment_check == 0){ ?>
                  <span class="invoice-badge badge-unchecked">確認中</span>
                <?php }else if($payment_check == 1){ ?>
                  <span class="invoice-badge badge-paid">支払い済み</span>
                <?php } ?>
              </div>
            </div>
            <div class="invoice-list-status-group application-section">
                <div class="invoice-list-label">申　請</div>
                <?php if($invoice_check != 2){ ?>
                    <form id="invoice-status-form-<?php echo $key; ?>" action="<?php echo getURLSetSlag('admin-explanation-invoice-list'); ?>?check_year=<?php echo $disp_year;?>" method="post" onsubmit="return confirm('ステータスを更新してもよろしいですか？');">
                        <div class="application-controls">
                            <div class="application-controls-row">
                                <select name="acf_invoice_check">
                                    <option value="0" <?php if($invoice_check == 0){ echo "selected"; } ?>>未確認</option>
                                    <option value="1" <?php if($invoice_check == 1){ echo "selected"; } ?>>再申請</option>
                                    <option value="2" <?php if($invoice_check == 2){ echo "selected"; } ?>>確認済み</option>
                                    <option value="3" <?php if($invoice_check == 3){ echo "selected"; } ?>>再確認</option>
                                </select>
                                <input type="hidden" name="receipt_id" value="<?php echo $value; ?>">
                                <input type="hidden" name="receipt_save" value="1">
                                <input type="submit" value="更新" class="invoice-list-btn">
                            </div>
                            <div class="application-controls-row text-row">
                                <span class="reapply-reason-wrap">
                                <input type="text" name="acf_invoice_txt" value="<?php echo get_field('acf_invoice_txt', $value); ?>" placeholder="再申請理由">
                                </span>
                            </div>
                        </div>
                    </form>
                <?php }else{ ?>
                    <div class="invoice-list-value" style="margin-top: 7px;">
                        確認済み
                    </div>
                <?php } ?>
            </div>
        </div>
      <?php } ?>
    <?php }else{ ?>
      <div style="text-align:center;color:#888;font-size:1.1em;">該当年の請求書・領収書はありません</div>
    <?php } ?>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('[id^="invoice-status-form-"]').forEach(function(form) {
    var select = form.querySelector('select[name="acf_invoice_check"]');
    var reasonWrap = form.querySelector('.reapply-reason-wrap');
    function toggleReason() {
      if (select.value === '1') {
        reasonWrap.style.display = '';
      } else {
        reasonWrap.style.display = 'none';
      }
    }
    toggleReason();
    select.addEventListener('change', toggleReason);
  });
});
</script>





