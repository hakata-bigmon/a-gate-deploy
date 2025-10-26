<?php 

require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");

$spiritSchedule = new SpiritScheduleClass();



$receipt_id = "";
$receipt_user_id = "";
$receipt_month = "";
$receipt_year = "";

//作成した領収書
if(isset($_POST["receipt_id"])){
    $receipt_id = $_POST["receipt_id"];
}

//ユーザー
if(isset($_POST["receipt_user_id"])){
    $receipt_user_id = $_POST["receipt_user_id"];
}

//月
if(isset($_POST["receipt_month"])){
    $receipt_month = $_POST["receipt_month"];
}

//年
if(isset($_POST["receipt_year"])){
    $receipt_year = $_POST["receipt_year"];
}


//支払い官憲のデータ取得
$invoice_day = "";
                        
if($receipt_id == "")
{
    $invoice_day = get_field('acf_invoice_day', SpiritScheduleClass::SCHEDULE_RECEIPT_SETTING_ID);
}
else{
    $invoice_day = get_field('acf_invoice_day', $receipt_id);
}

//支払い期限
$invoice_pay_day = "";

if($receipt_id == "")
{
    $invoice_pay_day = get_field('acf_invoice_pay_day', SpiritScheduleClass::SCHEDULE_RECEIPT_SETTING_ID);
}
else{
    $invoice_pay_day = get_field('acf_invoice_pay_day', $receipt_id);
}

//宛先
$invoice_post_name= "";
                        
if($receipt_id == "")
{
    $invoice_post_name = get_field('acf_invoice_post_name', SpiritScheduleClass::SCHEDULE_RECEIPT_SETTING_ID);
}
else{
    $invoice_post_name = get_field('acf_invoice_post_name', $receipt_id);
}

//作成するデータ(最新)
$schedule_array = $spiritSchedule->getSpritScheduleReceiptList( $receipt_year , $receipt_month );
$receipt_data = $schedule_array[ $receipt_user_id ];
//var_dump($receipt_data);

$receipt_disp_array = array();



//担当者
$receipt_disp_array["担当者"] = $receipt_data["担当者"];
//請求日

//$disp_monthの一月前を取得
$before_month = date("Y年m月", strtotime("-1 month", strtotime($receipt_year . "-" . $receipt_month . "-01")));

$receipt_disp_array["請求分"] = $before_month . "分";

//請求日
$receipt_disp_array["請求日"] = $receipt_year . "年" . $receipt_month . "月" . $invoice_day . "日";

//折込期限
$receipt_disp_array["折込期限"] = $receipt_year . "年" . $receipt_month . "月" . $invoice_pay_day . "日";

//会社宛先
$receipt_disp_array["会社宛先"] = $invoice_post_name;

//合計金額
$receipt_disp_array["合計金額"] = 0;$receipt_data["合計支払い金額"];

if($receipt_id != "")
{
    //$receipt_disp_array["合計金額"] = get_field('acf_invoice_total_pay', $receipt_id);
}

//


//施術データ
$receipt_disp_array["施術データ"] = $receipt_data["施術データ"];


if($receipt_id != "")
{
    $receipt_disp_array["施術データ"] = get_field('acf_invoice_save_id', $receipt_id);

    //これがJsonデータならデコード
    if(is_string($receipt_disp_array["施術データ"]))
    {
        $receipt_disp_array["施術データ"] = json_decode($receipt_disp_array["施術データ"], true);
    }
}

//振込先
$receipt_disp_array["振込先"] = get_field('acf_teacher_profile_transfer_destination', 'user_' . $receipt_user_id);

if($receipt_id != "" && get_field('acf_invoice_post_name', $receipt_id) != "")
{
    $receipt_disp_array["振込先"] = get_field('acf_invoice_post_name', $receipt_id);
}

//宛先の登録番号
$receipt_disp_array["宛先の登録番号"] = get_field('acf_invoice_registration_number', SpiritScheduleClass::SCHEDULE_RECEIPT_SETTING_ID);

if($receipt_id != "" && get_field('acf_invoice_registration_number', $receipt_id) != "")
{
    $receipt_disp_array["宛先の登録番号"] = get_field('acf_invoice_registration_number', $receipt_id);
}

//var_dump($receipt_disp_array);

?>

<style>
.receipt-print-area {
  max-width: 600px;
  margin: 60px auto;
  background: #fff;
  padding: 0 0 0 0;
  font-family: 'Yu Mincho', 'YuMincho', '游明朝', 'serif', '游ゴシック', 'Yu Gothic', 'sans-serif';
  color: #222;
  border: 1px solid black;
    padding: 20px;
    padding-bottom: 50px;
}
.receipt-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 48px;
}
.receipt-header-left {
  font-size: 1.2em;
  margin-top: 0.5em;
  font-weight: 600;
}
.receipt-header-right {
  text-align: right;
  font-size: 1.1em;
  min-width: 220px;
}
.receipt-title {
  font-size: 2rem;
  font-weight: bold;
  text-align: center;
  margin-bottom: 32px;
  letter-spacing: 0.12em;
}
.receipt-section {
  
  font-size: 1.1em;
}
.receipt-total-row {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  margin-bottom: 12px;
  font-size: 1.2em;
  font-weight: bold;
}
.receipt-table-area {
  display: flex;
  justify-content: center;
  margin-top: 10px;
}
.receipt-table {
  border-collapse: collapse;
  min-width: 600px;
  margin-bottom: 32px;
}
.receipt-table th, .receipt-table td {
  border: 1px solid #888;
  padding: 8px 12px;
  text-align: center;
  font-size: 1em;
}
.receipt-table th {
  background: #f5f5f5;
  font-weight: bold;
}
.receipt-table td:nth-child(2), .receipt-table th:nth-child(2) {
  max-width: 300px;
  word-break: break-all;
  white-space: normal;
}
@media print {
  body {
    background: #fff !important;
  }
  .print-btn {
    display: none !important;
  }
  .receipt-print-area {
    max-width: 600px !important;
    padding: 20px 20px 50px 20px !important;
    margin: 0 auto !important;
    border: 1px solid black !important;
  }
}
.print-btn {
  display: block;
  margin: 0 auto 24px auto;
  padding: 8px 36px;
  font-size: 1.1em;
  background: #1976d2;
  color: #fff;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  box-shadow: 0 1px 6px #e0e7ef;
  transition: background 0.18s;
  margin-top: 40px;
}
.print-btn:hover {
  background: #0d47a1;
}
</style>

<button class="print-btn" onclick="window.print()">印刷</button>
<div class="receipt-print-area">
  <div class="receipt-title">領収書</div>
  <div class="receipt-header">
    <div class="receipt-header-left">
      <?php echo $receipt_disp_array["担当者"]; ?>　様
    </div>
    <div class="receipt-header-right">
      発行日：<?php echo $receipt_disp_array["請求日"]; ?><br>
      <?php echo $receipt_disp_array["会社宛先"]; ?>
    </div>
  </div>
  <div class="receipt-section"><?php echo $receipt_disp_array["請求分"]?>実施分の電話対応として</div>
  <div class="receipt-section">下記の通り、領収いたしました。</div>
 
 
  <div class="receipt-table-area">
    <table class="receipt-table">
      <tr>
        <th>No</th>
        <th>摘要</th>
        <th>数量</th>
        <th>単価</th>
        <th>金額(税込)</th>
      </tr>
      <?php $no_count = 1;?>
      <?php foreach($receipt_disp_array["施術データ"] as $key => $value){ ?>
        <tr>
          <td><?php echo $no_count; ?></td>
          <td style="text-align: left;font-size: 14px;"><?php echo $value["施術名"]; ?></td>
          <td><?php echo $value["施術数"]; ?></td>
          <td>￥<?php echo number_format($value["施術金額"] / $value["施術数"]); ?></td>
          <td>￥<?php echo number_format($value["施術支払い金額"]); $receipt_disp_array["合計金額"] += $value["施術支払い金額"]; ?></td>
        </tr>
        <?php $no_count++;?>
      <?php } ?>
      <?php if($receipt_id != "" && get_field('acf_invoice_adjustment_costs', $receipt_id) != "" && get_field('acf_invoice_adjustment_costs', $receipt_id) != 0){ ?>
        <tr>
          <td><?php echo $no_count; ?></td>
          <td style="text-align: left;">外注特別費</td>
          <td></td>
          <td></td>
          <td>￥<?php echo number_format(get_field('acf_invoice_adjustment_costs', $receipt_id)); $receipt_disp_array["合計金額"] += get_field('acf_invoice_adjustment_costs', $receipt_id); ?></td>
        </tr>
      <?php } ?>
      <?php if($receipt_id != "" && get_field('acf_invoice_communication_expenses', $receipt_id) != "" && get_field('acf_invoice_communication_expenses', $receipt_id) != 0){ ?>
        <tr>
          <td><?php echo $no_count; ?></td>
          <td style="text-align: left;">通信費</td>
          <td></td>
          <td></td>
          <td>￥<?php echo number_format(get_field('acf_invoice_communication_expenses', $receipt_id)); $receipt_disp_array["合計金額"] += get_field('acf_invoice_communication_expenses', $receipt_id); ?></td>
        </tr>
      <?php } ?>
      <?php if($receipt_id != "" && get_field('acf_invoice_transportation_expenses', $receipt_id) != "" && get_field('acf_invoice_transportation_expenses', $receipt_id) != 0){ ?>
        <tr>
          <td><?php echo $no_count; ?></td>
          <td style="text-align: left;">交通費</td>
          <td></td>
          <td></td> 
          <td>￥<?php echo number_format(get_field('acf_invoice_transportation_expenses', $receipt_id)); $receipt_disp_array["合計金額"] += get_field('acf_invoice_transportation_expenses', $receipt_id); ?></td>
        </tr>
      <?php } ?>
      <?php if($receipt_id != "" && get_field('acf_invoice_else_expenses', $receipt_id) != "" && get_field('acf_invoice_else_expenses', $receipt_id) != 0){ ?>
        <tr>
          <td><?php echo $no_count; ?></td>
          <td style="text-align: left;">その他</td>
          <td></td>
          <td></td>
          <td>￥<?php echo number_format(get_field('acf_invoice_else_expenses', $receipt_id)); $receipt_disp_array["合計金額"] += get_field('acf_invoice_else_expenses', $receipt_id); ?></td>
        </tr>
      <?php } ?>

    </table>

    
  </div>

    <div class="receipt-total-row">
      合計金額　￥<?php echo number_format($receipt_disp_array["合計金額"]); ?>（税込）
    </div>
</div>