<?php 

    require_once ("a-gate-functions.php");


    $spiritReceiptSetting_id = 8625;

    if(isset($_POST['save'])){
      $acf_invoice_day = $_POST['acf_invoice_day'];
      $acf_invoice_pay_day = $_POST['acf_invoice_pay_day'];
      $acf_invoice_post_name = $_POST['acf_invoice_post_name'];
      $acf_invoice_registration_number = $_POST['acf_invoice_registration_number'];

      update_field('acf_invoice_day', $acf_invoice_day, $spiritReceiptSetting_id);
      update_field('acf_invoice_pay_day', $acf_invoice_pay_day, $spiritReceiptSetting_id);
      update_field('acf_invoice_post_name', $acf_invoice_post_name, $spiritReceiptSetting_id);
      update_field('acf_invoice_registration_number', $acf_invoice_registration_number, $spiritReceiptSetting_id);
    }
  ?>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
.admin-card {
  max-width: 520px;
  margin: 40px auto 60px auto;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 2px 16px #b0c4de;
  padding: 32px 28px 28px 28px;
}
.admin-title {
  font-size: 1.3rem;
  color: #234a6f;
  font-weight: bold;
  margin-bottom: 28px;
  letter-spacing: 0.08em;
  text-align: center;
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
.admin-setting-input input[type="number"], .admin-setting-input input[type="text"] {
  padding: 6px 10px;
  border-radius: 6px;
  border: 1px solid #b0c4de;
  font-size: 1em;
  background: #fff;
  width: 120px;
}
.admin-setting-btn-area {
  text-align: center;
  margin-top: 18px;
}
.admin-setting-btn {
  background: linear-gradient(90deg, #e3f2fd 0%, #bbdefb 100%);
  color: #1976d2;
  border: none;
  border-radius: 8px;
  font-weight: bold;
  padding: 8px 36px;
  cursor: pointer;
  transition: background 0.18s, color 0.18s;
  font-size: 1.08rem;
  box-shadow: 0 1px 6px #e0e7ef;
}
.admin-setting-btn:hover {
  background: #bbdefb;
  color: #0d47a1;
}
@media (max-width: 600px) {
  .admin-card { padding: 16px 2vw 24px 2vw; }
  .admin-setting-form-row { flex-direction: column; align-items: stretch; gap: 6px; }
  .admin-setting-label { min-width: 0; }
  .admin-setting-input input[type="number"], .admin-setting-input input[type="text"] { width: 100%; }
}
</style>

<div class="admin-card">
  <div class="admin-title">領収書・請求書設定</div>
  <form action="<?php echo getURLSetSlag('admin-receipt-setting'); ?>" method="post">
    <input type="hidden" name="save" value="" />
    <div class="admin-setting-form-row">
      <div class="admin-setting-label">請求日</div>
      <div class="admin-setting-input">
        毎月 <input type="number" name="acf_invoice_day" value="<?php echo get_field('acf_invoice_day', $spiritReceiptSetting_id); ?>" min="1" max="31" step="1" /> 日
      </div>
    </div>
    <div class="admin-setting-form-row">
      <div class="admin-setting-label">お振込期限</div>
      <div class="admin-setting-input">
        毎月 <input type="number" name="acf_invoice_pay_day" value="<?php echo get_field('acf_invoice_pay_day', $spiritReceiptSetting_id); ?>" min="1" max="31" step="1" /> 日
      </div>
    </div>
    <div class="admin-setting-form-row">
      <div class="admin-setting-label">請求書の宛先</div>
      <div class="admin-setting-input">
        <input type="text" name="acf_invoice_post_name" value="<?php echo get_field('acf_invoice_post_name', $spiritReceiptSetting_id); ?>" style="width:100%;"/>
      </div>
    </div>
    <div class="admin-setting-form-row">
      <div class="admin-setting-label">登録番号</div>
      <div class="admin-setting-input">
        <input type="text" name="acf_invoice_registration_number" value="<?php echo get_field('acf_invoice_registration_number', $spiritReceiptSetting_id); ?>" style="width:100%;"/>
      </div>
    </div>
    <div class="admin-setting-btn-area">
      <input type="submit" value="保存" class="admin-setting-btn" />
    </div>
  </form>
</div>