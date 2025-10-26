<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleCalendarClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");

    $spiritScheduleCalendar = new SpiritScheduleCalendarClass();
    $spiritSchedule = new SpiritScheduleClass();

    
    $schedule_array = $spiritSchedule->getSpritScheduleAllList();

    //var_dump($schedule_array);
?>

<div class="admin-exorcism-menu-title-box">
  <div class="admin-exorcism-menu-title">日程・スケジュール</div>
</div>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
.admin-schedule-btn-row {
  display: flex;
  gap: 32px;
  flex-wrap: wrap;
  justify-content: center;
  margin: 36px 0 40px 0;
}
.admin-schedule-btn {
  display: flex;
  align-items: center;
  gap: 14px;
  background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
  color: #1976d2;
  border: none;
  border-radius: 18px;
  font-size: 1.18rem;
  font-weight: bold;
  box-shadow: 0 2px 12px rgba(33,150,243,0.10);
  cursor: pointer;
  padding: 18px 38px;
  transition: background 0.18s, color 0.18s, box-shadow 0.18s;
  min-width: 180px;
  justify-content: center;
}
.admin-schedule-btn:hover {
  background: linear-gradient(135deg, #bbdefb 0%, #90caf9 100%);
  color: #0d47a1;
  box-shadow: 0 4px 24px rgba(33,150,243,0.18);
}
.admin-schedule-btn .material-icons {
  font-size: 2rem;
}
.admin-exorcism-menu-title-box {
  text-align: center;
  margin-top: 30px;
  margin-bottom: 0;
}
.admin-exorcism-menu-title {
  font-size: 1.5rem;
  color: #234a6f;
  font-weight: bold;
  letter-spacing: 0.08em;
  margin-bottom: 0;
}
@media (max-width: 700px) {
  .admin-schedule-btn-row {
    flex-direction: column;
    gap: 18px;
    align-items: stretch;
  }
  .admin-schedule-btn {
    width: 100%;
    min-width: 0;
    justify-content: flex-start;
  }
}
</style>

<div class="admin-schedule-btn-row">
  <button class="admin-schedule-btn" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-schedule-execution-list'); ?>'">
    <span class="material-icons">receipt_long</span>実行履歴
  </button>
  <button class="admin-schedule-btn" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-explanation-receipt-list'); ?>'">
    <span class="material-icons">receipt_long</span>領収書一覧
  </button>
  <button class="admin-schedule-btn" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-spirit-schedule-list'); ?>?group=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_DAY?>'">
    <span class="material-icons">event_available</span>日程確定一覧
  </button>
  <button class="admin-schedule-btn" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-spirit-explanation-schedule-list'); ?>?group=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN?>'">
    <span class="material-icons">psychology</span>相談・ヒーリング一覧
  </button>
</div>

<div style="margin-top: 100px;">
    <?php $spiritScheduleCalendar->dispAllCalendarBase($schedule_array,false);?>
</div>
