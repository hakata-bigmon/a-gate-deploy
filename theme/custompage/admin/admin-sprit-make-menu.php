<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");

?>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
.sprit-menu-card {
  max-width: 900px;
  margin: 40px auto 60px auto;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 2px 16px #b0c4de;
  padding: 40px 32px 32px 32px;
}
.sprit-menu-title {
  font-size: 2.2rem;
  color: #234a6f;
  font-weight: bold;
  margin-bottom: 36px;
  letter-spacing: 0.08em;
  text-align: center;
}
.sprit-menu-btn-row {
  display: flex;
  flex-direction: column;
  gap: 24px;
  align-items: center;
  margin-bottom: 0;
}
.sprit-menu-btn-box {
  width: 100%;
  max-width: 340px;
  margin: 0 auto;
}
.sprit-menu-btn {
  width: 100%;
  height: 64px;
  font-size: 1.2rem;
  font-weight: bold;
  border-radius: 14px;
  border: none;
  background: linear-gradient(90deg, #e3f2fd 0%, #bbdefb 100%);
  color: #1976d2;
  box-shadow: 0 2px 8px rgba(0,0,0,0.07);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  cursor: pointer;
  transition: box-shadow 0.2s, background 0.2s, color 0.2s, transform 0.15s;
  margin-bottom: 18px;
}
.sprit-menu-btn:hover {
  background: #bbdefb;
  color: #0d47a1;
  box-shadow: 0 4px 16px rgba(33, 150, 243, 0.13);
  transform: translateY(-2px) scale(1.03);
}
.sprit-menu-btn .material-icons {
  font-size: 2rem;
}
@media (max-width: 700px) {

    .sprit-menu-title{
        font-size: 24px;
    }

  .sprit-menu-card {
    padding: 16px 2vw 24px 2vw;
    min-width: 0;
    margin-left: 10px;
    margin-right: 10px;
  }
  .sprit-menu-btn-row {
    flex-direction: column;
    gap: 12px;
  }
  .sprit-menu-btn-box {
    max-width: 100%;
  }
}
</style>
<div class="sprit-menu-card">
  <div class="sprit-menu-title">浄霊・施術作成メニュー</div>
  <div class="sprit-menu-btn-row">

    <?php if(current_user_can('administrator')){?>
        <div class="sprit-menu-btn-box">
        <button type="button" class="sprit-menu-btn" onclick="location.href='<?php echo getURLSetSlag('admin-sprit-make'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT?>'">
            <span class="material-icons">psychology</span>リモート依頼
        </button>
        </div>
        <div class="sprit-menu-btn-box">
        <button type="button" class="sprit-menu-btn" onclick="location.href='<?php echo getURLSetSlag('admin-sprit-make'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL?>'">
            <span class="material-icons">fact_check</span>鑑　　定
        </button>
        </div>

        <div class="sprit-menu-btn-box">
        <button type="button" class="sprit-menu-btn" onclick="location.href='<?php echo getURLSetSlag('admin-sprit-make'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_DAY?>'">
            <span class="material-icons">event_available</span>日程確定依頼
        </button>
        </div>
        <div class="sprit-menu-btn-box">
            <button type="button" class="sprit-menu-btn" onclick="location.href='<?php echo getURLSetSlag('admin-sprit-make'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SALES?>'">
                <span class="material-icons">shopping_cart</span>物　　販
            </button>
        </div>
    
        <div class="sprit-menu-btn-box">
        <button type="button" class="sprit-menu-btn" onclick="location.href='<?php echo getURLSetSlag('admin-sprit-make'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN?>'">
            <span class="material-icons">support_agent</span>遠隔・相談
        </button>
        </div>
    <?php }else{ ?>
        <div class="sprit-menu-btn-box">
            <button type="button" class="sprit-menu-btn" onclick="location.href='<?php echo getURLSetSlag('admin-spirit-explanation-schedule-list'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN?>'">
                <span class="material-icons">support_agent</span>遠隔・相談
            </button>
            </div>
    <?php } ?>

  </div>
</div>