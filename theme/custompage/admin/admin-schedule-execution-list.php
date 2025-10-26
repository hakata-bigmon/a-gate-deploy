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

    

    //保存
    if(isset($_POST['edit_save'])){
      
      //var_dump($_POST);

      foreach($_POST["edit_sheet"] as $schedule_sheet_id){

        foreach($_POST as $post_key => $post_value){

          if(strpos($post_key, $schedule_sheet_id) !== false){
          
            //キーから番号を削除する
            $acf_name = str_replace( "_".$schedule_sheet_id,"",$post_key);

            update_field($acf_name, $post_value, $schedule_sheet_id);

          }

        }
        
      }
    }

    //$disp_monthの一月後を取得
    $next_month = date("Y-m", strtotime("+1 month", strtotime($disp_month)));

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
  min-height: 220px;
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
.admin-list-btn.save {
  background: linear-gradient(90deg, #a5d6a7 0%, #66bb6a 100%);
  color: #fff;
  font-weight: bold;
  font-size: 1.08rem;
  box-shadow: 0 2px 12px rgba(102,187,106,0.13);
  border: none;
  border-radius: 8px;
  padding: 10px 36px;
  margin: 0 auto 18px auto;
  display: block;
  transition: background 0.2s, color 0.2s, box-shadow 0.2s;
}
.admin-list-btn.save:hover {
  background: linear-gradient(90deg, #66bb6a 0%, #a5d6a7 100%);
  color: #fff;
  box-shadow: 0 4px 18px rgba(102,187,106,0.18);
}
@media (max-width: 900px) {
  .admin-card { padding: 16px 2vw 24px 2vw; }
  .admin-list-area { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
  .admin-title { font-size: 1.1rem; }
  .admin-list-label, .admin-list-value { font-size: 0.95em; }
}
.bulk-input-card {
  background: #f8fbff;
  border-radius: 14px;
  box-shadow: 0 1px 6px #e0e7ef;
  padding: 22px 28px 18px 28px;
  margin: 0 0 32px 0;
  max-width: 900px;
  margin-left: auto;
  margin-right: auto;
}
.bulk-input-title {
  font-size: 1.18rem;
  font-weight: bold;
  color: #1976d2;
  margin-bottom: 18px;
  letter-spacing: 0.04em;
}
.bulk-input-row {
  display: flex;
  align-items: center;
  gap: 18px;
  margin-bottom: 14px;
  flex-wrap: wrap;
}
.bulk-input-label {
  min-width: 120px;
  color: #234a6f;
  font-weight: bold;
  font-size: 1.05em;
}
.bulk-input-date {
  padding: 7px 10px;
  border-radius: 6px;
  border: 1px solid #b0c4de;
  font-size: 1em;
  background: #fff;
}
.bulk-input-btn {
  background: linear-gradient(90deg, #e3f2fd 0%, #bbdefb 100%);
  color: #1976d2;
  border: none;
  border-radius: 8px;
  font-weight: bold;
  padding: 8px 22px;
  cursor: pointer;
  transition: background 0.18s, color 0.18s;
  font-size: 1em;
  box-shadow: 0 1px 6px #e0e7ef;
  margin-left: 8px;
}
.bulk-input-btn:hover {
  background: #bbdefb;
  color: #0d47a1;
}
@media (max-width: 700px) {
  .bulk-input-card { padding: 12px 2vw 12px 2vw; }
  .bulk-input-row { flex-direction: column; align-items: flex-start; gap: 8px; }
  .bulk-input-label { min-width: 0; }
}
.bulk-input-accordion-header {
  display: flex;
  align-items: center;
  cursor: pointer;
  font-weight: bold;
  font-size: 1.18rem;
  color: #1976d2;
  padding: 8px 0 8px 0;
  user-select: none;
  border-bottom: 1px solid #e0e7ef;
  margin-bottom: 0;
  gap: 8px;
}
.bulk-input-accordion-header .material-icons {
  transition: transform 0.2s;
}
.bulk-input-accordion-header.closed .material-icons {
  transform: rotate(-90deg);
}
.bulk-input-accordion-content {
  display: block;
  animation: fadeInAccordion 0.3s;
}
.bulk-input-accordion-content.closed {
  display: none;
}
@keyframes fadeInAccordion {
  from { opacity: 0; transform: translateY(-10px);}
  to { opacity: 1; transform: translateY(0);}
}
.admin-list-row-flex2 {
  display: flex;
  gap: 32px;
  margin-bottom: 10px;
}
@media (max-width: 700px) {
  .admin-list-row-flex2 {
    flex-direction: column;
    gap: 8px;
  }
}
</style>


<div class="admin-card">
  <div class="admin-title">相談スケジュール実地リスト</div>
  <div style="text-align:center;margin-bottom:18px;">
    <a href="<?php echo getURLSetSlag('admin-explanation-receipt-list'); ?>?month=<?php echo $next_month; ?>" class="admin-month-link" style="background:linear-gradient(90deg, #a5d6a7 0%, #66bb6a 100%);color:#fff;box-shadow:0 2px 12px rgba(102,187,106,0.13);">領収書・請求書リスト</a>
  </div>
  <div class="admin-month-nav">
    <a class="admin-month-link" href="<?php echo getURLSetSlag('admin-schedule-execution-list'); ?>?month=<?php echo $now_month; ?>"><?php echo $now_month_disp; ?></a>
    <a class="admin-month-link" href="<?php echo getURLSetSlag('admin-schedule-execution-list'); ?>?month=<?php echo $last_month; ?>"><?php echo $last_month_disp; ?></a>
    <a class="admin-month-link" href="<?php echo getURLSetSlag('admin-schedule-execution-list'); ?>?month=<?php echo $before_month; ?>"><?php echo $before_month_disp; ?></a>
  </div>
  <form class="admin-month-form" action="<?php echo getURLSetSlag('admin-schedule-execution-list'); ?>" method="get">
    <input type="month" name="month" value="<?php echo $disp_month; ?>">
    <input type="submit" value="検索" class="admin-list-btn">
  </form>
  <div style="text-align:center;font-weight:bold;font-size:1.1em;margin-bottom:18px;"><?php echo $disp_month_disp; ?></div>


 


  


  <div class="admin-list-area">
    <?php if(isset($schedule_array[$year][$month])){ ?>

      <?php if(!isset($_GET['edit'])){ ?>

        <form action="<?php echo getURLSetSlag('admin-schedule-execution-list'); ?>?month=<?php echo $now_month; ?>&edit=treu" method="post">
          <input type="submit" value="編集" class="admin-list-btn">
        </form>
      <?php } ?>
      
      <?php if(isset($_GET['edit'])){ ?>

        <div class="bulk-input-card">
          <div class="bulk-input-accordion-header closed" id="bulkAccordionHeader">
            <span class="material-icons" id="bulkAccordionIcon">chevron_right</span>一括入力
          </div>
          <div class="bulk-input-accordion-content closed" id="bulkAccordionContent">
            <div class="bulk-input-row">
              <div class="bulk-input-label">領収書発行月</div>
              <input type="number" id="bulk-receipt-date" class="bulk-input-date">月分
              <button type="button" class="bulk-input-btn" onclick="bulkSetDate('bulk-receipt-date', 'input[name^=acf_previous_execution_receipt_date_]')">一括入力</button>
            </div>
            <div class="bulk-input-row">
              <div class="bulk-input-label">発行</div>
              <select id="bulk-invoice-enable" class="bulk-input-date">
                <option value="1">発行</option>
                <option value="0">未発行</option>
              </select>
              <button type="button" class="bulk-input-btn" onclick="bulkSetSelect('bulk-invoice-enable', 'select[name^=acf_previous_execution_invoice_enable_]')">一括入力</button>
            </div>
          </div>
        </div>

        <form action="<?php echo getURLSetSlag('admin-schedule-execution-list'); ?>?month=<?php echo $now_month; ?>" method="post">
          <input type="hidden" name="edit_save" value="true">
          <input type="submit" value="保存" class="admin-list-btn save">

        
      <?php } ?>

      <?php foreach($schedule_array[$year][$month] as $schedule){ ?>
        <?php foreach($schedule as $schedule_id){ ?>
          <?php 
            $schedule_data = $spiritSchedule->getSpritScheduledetail( $schedule_id ); 

            if($schedule_data["表示ステータス"] != SpiritScheduleClass::SCHEDULE_DISP_RESERVATION_OVER) continue;
            $sheet_array = $spiritUser->getUserSpritApplicantSheet($schedule_data["予約者データ"][0]["ID"],$schedule_data["予約者"][0]);
            $exe_pay_amount = get_field('acf_previous_execution_pay',$schedule_data["予約者"][0]);
            if($exe_pay_amount == "") $exe_pay_amount = 0;
          ?>


          <input type="hidden" name="edit_sheet[]" value="<?php echo $schedule_data["予約者"][0]; ?>">

          <div class="admin-list-card">
            <div class="admin-list-row">
              <div class="admin-list-label">実行日</div>
              <div class="admin-list-value"><?php echo $schedule_data["実行年月日"];?></div>
            </div>
            <div class="admin-list-row">
              <div class="admin-list-label">依頼名</div>
              <div class="admin-list-value">
                <a href="<?php echo getURLSetSlag('admin-spirit-explanation-schedule-edit'); ?>?edit_schedule=<?php echo $schedule_data["ID"]; ?>" target="_blank"><?php echo $schedule_data["表示名"];?></a>
              </div>
            </div>
            
            <div class="admin-list-row admin-list-row-flex2">
              <div style="flex:1;">
                <div class="admin-list-label">管理ステータス</div>
                <?php if(!isset($_GET['edit'])){ ?> 
                  <div class="admin-list-value"><?php echo $sheet_array[$schedule_data["予約者"][0]]["管理者ステータス表示"];?></div>
                <?php }else{ ?>
                  <div class="admin-list-value">
                    <select name="acf_purespirit_status_<?php echo $schedule_data["予約者"][0]; ?>">
                      <?php foreach($spiritStatusArray as $status_id => $status){ ?>
                        <option value="<?php echo $status_id; ?>" <?php if($status_id == $sheet_array[$schedule_data["予約者"][0]]["管理者ステータス"]){ echo "selected"; } ?>><?php echo $status["title"]; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                <?php } ?>
              </div>
              <div style="flex:1;">
                <div class="admin-list-label">発行</div>
                <div class="admin-list-value">
                  <?php if(!isset($_GET['edit'])){ ?>
                    <?php if($sheet_array[$schedule_data["予約者"][0]]["請求書発行"] == 1){ ?>
                      <div class="admin-list-value">発行</div>
                    <?php }else{ ?>
                      <div class="admin-list-value">未発行</div>
                    <?php } ?>
                  <?php }else{ ?>
                    <select name="acf_previous_execution_invoice_enable_<?php echo $schedule_data["予約者"][0]; ?>">
                      <option value="1" <?php if($sheet_array[$schedule_data["予約者"][0]]["請求書発行"] == 1){ echo "selected"; } ?>>発行</option>
                      <option value="0" <?php if($sheet_array[$schedule_data["予約者"][0]]["請求書発行"] == 0){ echo "selected"; } ?>>未発行</option>
                    </select>
                  <?php } ?>
                </div>
              </div>
            </div>

            <div class="admin-list-row admin-list-row-flex2">
              <div style="flex:1;">
                <div class="admin-list-label">担当者</div>
                <div class="admin-list-value"><?php echo $schedule_data["担当者名前"];?></div>
              </div>
              <div style="flex:1;">
                <div class="admin-list-label">相談者</div>
                <div class="admin-list-value">
                  <a href="<?php echo getURLSetSlag('admin-spirit-detail/'); ?>?user_id=<?php echo $sheet_array[$schedule_data["予約者"][0]]["依頼者ID"]; ?>&sheet_name=<?php echo $sheet_array[$schedule_data["予約者"][0]]["ID"]; ?>" target="_blank"><?php echo $sheet_array[$schedule_data["予約者"][0]]["フル名前"];?></a>
                </div>
              </div>
            </div>

            <div class="admin-list-row admin-list-row-flex2">
              <div style="flex:1;">
                <div class="admin-list-label">価格</div>
                <div class="admin-list-value"><?php echo $schedule_data["価格"];?>円</div>
              </div>
              <div style="flex:1;">
                <div class="admin-list-label">支払い単価</div>
                <?php if(!isset($_GET['edit'])){ ?>
                  <div class="admin-list-value"><?php echo $exe_pay_amount . "円（税込）"; ?></div>
                <?php }else{ ?>
                  <div class="admin-list-value">
                    <input type="number" name="acf_previous_execution_pay_<?php echo $schedule_data["予約者"][0]; ?>" value="<?php echo $exe_pay_amount; ?>">円（税込）
                  </div>
                <?php } ?>
              </div>
            </div>

            <div class="admin-list-row admin-list-row-flex2">
              <div style="flex:1;">
                <div class="admin-list-label">領収書発行月</div>
                <?php 
                  $receipt_month = $sheet_array[$schedule_data["予約者"][0]]["請求書発行月"];
                  $receipt_year = $sheet_array[$schedule_data["予約者"][0]]["請求書発行年"];
          
                  if($receipt_month == "" || $receipt_year == ""){


                    //どちらかが空だった場合、現在指定されている月の次の月にする
                    $receipt_make_date = $year . "-" . $month . "-01";
                    $receipt_make_date = date("Y-m-d", strtotime($receipt_make_date . " +1 month"));

                    $receipt_month = date("n", strtotime($receipt_make_date));
                    $receipt_year = date("Y", strtotime($receipt_make_date));

                   
                    
                  }
                ?>
                <?php if(!isset($_GET['edit'])){ ?>
                  <div class="admin-list-value"><?php echo $receipt_year; ?>年<?php echo $receipt_month; ?>月分</div>
                <?php }else{ ?>
                  <div class="admin-list-value">
                    <input type="number" name="acf_previous_execution_invoice_date_year_<?php echo $schedule_data["予約者"][0]; ?>" min="2024" style="width: 50px;" value="<?php echo $receipt_year; ?>">年
                    <input type="number" name="acf_previous_execution_invoice_date_<?php echo $schedule_data["予約者"][0]; ?>" min="1" max="12" style="width: 40px;" value="<?php echo $receipt_month; ?>">月分
                  </div>
                <?php } ?>
              </div>
              <div style="flex:1;">
                <div class="admin-list-label">領収書登録</div>
                <div class="admin-list-value">
                  <?php if(!isset($_GET['edit'])){ ?>
                    <?php if($sheet_array[$schedule_data["予約者"][0]]["請求書発行"] == 1){ ?>
                      <div class="admin-list-value">登録</div>
                    <?php }else{ ?>
                      <div class="admin-list-value">未登録</div>
                    <?php } ?>
                  <?php }else{ ?>
                    <select name="acf_previous_execution_invoice_enable_<?php echo $schedule_data["予約者"][0]; ?>">
                      <option value="1" <?php if($sheet_array[$schedule_data["予約者"][0]]["請求書発行"] == 1){ echo "selected"; } ?>>発行</option>
                      <option value="0" <?php if($sheet_array[$schedule_data["予約者"][0]]["請求書発行"] == 0){ echo "selected"; } ?>>未発行</option>
                    </select>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>

       
      <?php } ?>

      <?php if(isset($_GET['edit'])){ ?>

        </form>
      <?php } ?>

    <?php }else{ ?>
      <div style="text-align:center;color:#888;font-size:1.1em;">該当月のスケジュールはありません</div>
    <?php } ?>
  </div>
</div>

<script>
function bulkSetDate(bulkInputId, targetSelector) {
  var bulkValue = document.getElementById(bulkInputId).value;
  if (!bulkValue) return;
  document.querySelectorAll(targetSelector).forEach(function(input) {
    input.value = bulkValue;
  });
}

function bulkSetSelect(bulkSelectId, targetSelector) {
  var bulkValue = document.getElementById(bulkSelectId).value;
  document.querySelectorAll(targetSelector).forEach(function(select) {
    select.value = bulkValue;
  });
}

document.addEventListener('DOMContentLoaded', function() {
  var header = document.getElementById('bulkAccordionHeader');
  var content = document.getElementById('bulkAccordionContent');
  var icon = document.getElementById('bulkAccordionIcon');
  var open = false;
  if(header && content && icon) {
    header.addEventListener('click', function () {
      open = !open;
      if (open) {
        content.classList.remove('closed');
        header.classList.remove('closed');
        icon.textContent = 'expand_more';
      } else {
        content.classList.add('closed');
        header.classList.add('closed');
        icon.textContent = 'chevron_right';
      }
    });
  }
});
</script>



