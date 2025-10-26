<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritPlaceClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");


    $spiritPlaceData = new SpiritPlsceClass(); //場所データ

    $spiritTypeData = new SpiritTypeClass(); //浄霊タイプデータ
    $spiritScheduleData = new SpiritScheduleClass(); //スケジュールデータ
    

    //選択
    $choice_user = "";
    $choice_sheet = "";
    $choice_edit = false;
    $choice_schedule = "";

    $group = SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN;

    if(isset($_POST["choice_user"]))
    {
        $choice_user = $_POST["choice_user"];
        $choice_sheet= $_POST["choice_sheet"];

        if(isset($_POST["choice_page"]))
        {
            $choice_edit = true;
        }

        if(isset($_POST["choice_schedule"]))
        {
            $choice_schedule = $_POST["choice_schedule"];
        }
    }




    //削除
    if(isset($_POST["schedule_delete"]))
    {
         wp_delete_post($_POST["schedule_delete"], true);
    }


    

   //検索登録
   if(isset($_POST["search"]))
   {
       $search_array = array();

       foreach ($_POST as $key => $value) {
           $search_array[ $key ] = $value;
       }

       update_user_meta(get_current_user_id(),'search_explanation_schedule_list',json_encode($search_array, JSON_UNESCAPED_UNICODE));
   }

     //検索リセット
   if(isset($_POST["search_reset"]))
   {
       update_user_meta(get_current_user_id(),'search_explanation_schedule_list',"");
   }

   //検索取得
   $current_user_search_data = get_user_meta(get_current_user_id(),'search_explanation_schedule_list',true);//表示設定取得
   
   if($current_user_search_data != "-" && $current_user_search_data != null){

        $current_user_search_data = json_decode($current_user_search_data, true);//表示設定取得
   }




    //浄霊タイプ
    $spiritType = $spiritTypeData->getSpiritType();
    //場所取得
    $place_list = $spiritPlaceData->getSpritPlaceList();
    //スケジュール

    $charge_user_id = "";

    if(!current_user_can('administrator')){
        $charge_user_id = get_current_user_id();
    }

    $schedule_list = $spiritScheduleData->getSpritScheduleList($charge_user_id);


    

    // 全ユーザーの取得（管理者、編集者、投稿者）
    $users = get_users(array(
        'role__in' => array('administrator', 'editor', 'author'),
        'exclude' => array(1), // ID:1のユーザーを除外
        'orderby' => 'ID',
        'order' => 'ASC'
    ));
    //var_dump($current_user_search_data);

?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
.admin-card {
  max-width: 1200px;
  margin: 40px auto 60px auto;
  background: #fff;
  border-radius: 20px;
  
}
.admin-title {
    font-size: 24px;
  color: #234a6f;
  font-weight: bold;
  margin-bottom: 36px;
  letter-spacing: 0.08em;
  text-align: center;
}
.admin-btn-row {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
  justify-content: center;
  margin-bottom: 24px;
}
.admin-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  background: linear-gradient(90deg, #e3f2fd 0%, #bbdefb 100%);
  color: #1976d2;
  border: none;
  border-radius: 12px;
  font-size: 1rem;
  font-weight: bold;
  box-shadow: 0 2px 8px rgba(0,0,0,0.07);
  cursor: pointer;
  padding: 10px 24px;
  transition: background 0.2s, color 0.2s, box-shadow 0.2s;
  text-decoration: none;
}
.admin-btn.red {
  background: linear-gradient(90deg, #ffebee 0%, #ffcdd2 100%);
  color: #d32f2f;
}
.admin-btn:hover {
  background: #bbdefb;
  color: #0d47a1;
  box-shadow: 0 4px 16px rgba(33, 150, 243, 0.13);
}
.admin-btn .material-icons {
  font-size: 1.3rem;
}
.admin-search-form {
  background: #f8fbff;
  border-radius: 14px;
  padding: 24px 20px 12px 20px;
  margin-bottom: 32px;
  box-shadow: 0 1px 6px #e0e7ef;
}
.admin-search-flex {
  display: flex;
  flex-wrap: wrap;
  gap: 18px 32px;
  align-items: flex-end;
}
.admin-search-label {
  font-weight: bold;
  color: #234a6f;
  margin-bottom: 6px;
  font-size: 1rem;
}
.admin-search-input, .admin-search-select {
  border-radius: 8px;
  border: 1px solid #b0c4de;
  padding: 8px 3px;
  font-size: 1rem;
  background: #fff;
  min-width: 120px;
}
.admin-table-area {
  margin-top: 32px;
}
table.dataTable {
  background: #fff;
  border-radius: 16px;
  margin: 24px 0 !important;
  border-collapse: separate;
  border-spacing: 0;
  width: 100% !important;
}
table.dataTable thead th {
  background: #f8fbff;
  color: #234a6f;
  font-weight: bold;
  padding: 16px;
  border-bottom: 2px solid #e0e7ef;
}
table.dataTable tbody td {
  padding: 12px 16px;
  border-bottom: 1px solid #e0e7ef;
  vertical-align: middle;
  font-size: 1rem;
}
table.dataTable tbody tr:hover {
  background: #f8fbff;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
  border-radius: 8px;
  padding: 8px 16px;
  margin: 0 4px;
  border: none;
  background: #f5f7fa;
  color: #234a6f !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
  background: #e3f2fd;
  color: #1976d2 !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
  background: #bbdefb;
  color: #0d47a1 !important;
}
.dataTables_wrapper .dataTables_length select {
  border-radius: 8px;
  border: 1px solid #b0c4de;
  padding: 4px 8px;
  background: #f8fbff;
}
.dataTables_wrapper .dataTables_filter input {
  border-radius: 8px;
  border: 1px solid #b0c4de;
  padding: 8px 12px;
  background: #f8fbff;
}
@media (max-width: 900px) {
  .admin-card {
    padding: 16px 2vw 24px 2vw;
    min-width: 0;
  }
  .admin-btn-row {
    flex-direction: column;
    gap: 12px;
    align-items: stretch;
  }
  .admin-search-flex {
   
  }

}

@media (max-width: 650px) {
  

    
    .admin-search-input, .admin-search-select {
        font-size: 12px;
    }
}


@media (max-width: 480px) {

    .admin-title{
        font-size: 18px;
    }

    .admin-search-input, .admin-search-select {
      font-size: 9px;
  }
}

.admin-search-accordion-header {
  display: flex;
  align-items: center;
  cursor: pointer;
  font-weight: bold;
  font-size: 1.1rem;
  color: #1976d2;
  padding: 12px 0 12px 8px;
  user-select: none;
  border-bottom: 1px solid #e0e7ef;
  margin-bottom: 0;
}
.admin-search-accordion-header .material-icons {
  margin-right: 8px;
  transition: transform 0.2s;
}
.admin-search-accordion-header.open .material-icons {
  transform: rotate(90deg);
}
.admin-search-accordion-content {
  display: none;
  animation: fadeInAccordion 0.3s;
}
.admin-search-accordion-content.open {
  display: block;
}
@keyframes fadeInAccordion {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
.admin-link-like {
  color: #1976d2;
  text-decoration: underline;
  background: none;
  border: none;
  padding: 0;
  font: inherit;
  cursor: pointer;
  transition: color 0.2s;
}
.admin-link-like:hover {
  color: #0d47a1;
  text-decoration: underline;
}
</style>
<div class="admin-card">
  <div class="admin-title">
    <?php if($group == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY){?>
      <?php echo "日程確定スケジュール一覧"; ?>
    <?php }else{ ?>
      <?php echo "相談・ヒーリングスケジュール一覧"; ?>
    <?php } ?>
  </div>
  <div class="admin-btn-row">
    <button class="admin-btn" type="button" onclick="window.location.href='<?php echo getURLSetSlag('admin-spirit-explanation-schedule-calendar'); ?>'">
      <span class="material-icons">calendar_month</span>カレンダー表示
    </button>
    <?php if(current_user_can('administrator') || current_user_can('editor')){?>
      <button class="admin-btn" type="button" onclick="window.location.href='<?php echo getURLSetSlag('admin-spirit-schedule-list'); ?>?group=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_DAY?>'">
        <span class="material-icons">list_alt</span>日程確定スケジュール一覧
      </button>
    <?php } ?>
    <button class="admin-btn red" type="button" onclick="window.location.href='<?php echo getURLSetSlag('admin-spirit-explanation-schedule-edit'); ?>'">
      <span class="material-icons">add_circle</span>新規作成
    </button>
  </div>



  <div class="admin-search-form">
    <div class="admin-search-accordion-header" id="searchAccordionHeader">
      <span class="material-icons" id="searchAccordionIcon">chevron_right</span>検索条件を開く／閉じる
    </div>
    <div class="admin-search-accordion-content" id="searchAccordionContent">
      <form action="<?php echo getURLSetSlag('admin-spirit-explanation-schedule-list'); ?>" method="post">
        <div class="admin-search-flex">
          <div>
            <div class="admin-search-label">実施日</div>
            <div style="display:flex;align-items:center;gap:8px;">
              <input type="date" class="admin-search-input" name="search_schedule_date_start" value="<?php  if(isset($current_user_search_data['search_schedule_date_start'])){ echo $current_user_search_data['search_schedule_date_start'];}?>">
              <span>～</span>
              <input type="date" class="admin-search-input" name="search_schedule_date_end" value="<?php if(isset($current_user_search_data['search_schedule_date_end'])){echo $current_user_search_data['search_schedule_date_end'];}?>">
            </div>
          </div>
          <div>
            <div class="admin-search-label">締切日</div>
            <div style="display:flex;align-items:center;gap:8px;">
              <input type="date" class="admin-search-input" name="search_schedule_limit_start" value="<?php  if(isset($current_user_search_data['search_schedule_limit_start'])){ echo $current_user_search_data['search_schedule_limit_start'];}?>">
              <span>～</span>
              <input type="date" class="admin-search-input" name="search_schedule_limit_end" value="<?php if(isset($current_user_search_data['search_schedule_limit_end'])){echo $current_user_search_data['search_schedule_limit_end'];}?>">
            </div>
          </div>
          <div>
            <div class="admin-search-label">施術名</div>
            <select class="admin-search-select" name="search_schedule_spirit_type" style="width: 100%;">
              <option value="">全て表示</option>
              <?php  foreach ($spiritType[SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN] as $key => $value) {?>
                <?php if( $value['group'] != $group){ continue; } ?>
                <option value="<?php echo $value['ID'];?>" <?php if(isset( $current_user_search_data['search_schedule_spirit_type'])) {if( $current_user_search_data['search_schedule_spirit_type'] == $value['ID']) { echo 'selected'; }}?>><?php echo $value['title'];?></option>
              <?php } ?>
            </select>
          </div>
          <div>
            <div class="admin-search-label">表示(状態)</div>
            <select class="admin-search-select" name="search_schedule_disp">
              <option value="">全て表示</option>
              <option value="<?php echo SpiritScheduleClass::SCHEDULE_DISP;?>" <?php if(isset( $current_user_search_data['search_schedule_disp'])) {if( $current_user_search_data['search_schedule_disp'] == SpiritScheduleClass::SCHEDULE_DISP) { echo 'selected'; }}?>>表示中</option>
              <option value="<?php echo SpiritScheduleClass::SCHEDULE_DISP_WAIT;?>" <?php if(isset( $current_user_search_data['search_schedule_disp'])) {if( $current_user_search_data['search_schedule_disp'] == SpiritScheduleClass::SCHEDULE_DISP_WAIT) { echo 'selected'; }}?>>待機中</option>
              <option value="<?php echo SpiritScheduleClass::SCHEDULE_DISP_OVER;?>" <?php if(isset( $current_user_search_data['search_schedule_disp'])) {if( $current_user_search_data['search_schedule_disp'] == SpiritScheduleClass::SCHEDULE_DISP_OVER) { echo 'selected'; }}?>>締め切り</option>
              <option value="<?php echo SpiritScheduleClass::SCHEDULE_DISP_NOT;?>" <?php if(isset( $current_user_search_data['search_schedule_disp'])) {if( $current_user_search_data['search_schedule_disp'] == SpiritScheduleClass::SCHEDULE_DISP_NOT) { echo 'selected'; }}?>>非表示</option>
              <option value="<?php echo SpiritScheduleClass::SCHEDULE_DISP_RESERVATION_OVER;?>" <?php if(isset( $current_user_search_data['search_schedule_disp'])) {if( $current_user_search_data['search_schedule_disp'] == SpiritScheduleClass::SCHEDULE_DISP_RESERVATION_OVER) { echo 'selected'; }}?>>人数締め切り</option>
            </select>
          </div>
          <div>
            <div class="admin-search-label">担当者</div>
            <select class="admin-search-select" name="search_schedule_charge">
              <option value="">全て表示</option>
              <?php  foreach ($users as $key => $value) {?>
                  <?php if($value->ID != $charge_user_id &&  !current_user_can('administrator')){ continue; } ?>
                  <option value="<?php echo $value->ID;?>" <?php if(isset( $current_user_search_data['search_schedule_charge'])) {if( $current_user_search_data['search_schedule_charge'] == $value->ID) { echo 'selected'; }}?>><?php echo $value->display_name;?></option>
              <?php } ?>
            </select>
          </div>
          <div style="display:flex;gap:12px;align-items:center;">
            <button class="admin-btn" style="padding:8px 20px;" type="submit">
              <span class="material-icons">filter_list</span>絞り込む
            </button>
            <button class="admin-btn" style="padding:8px 20px;background:#ececec;color:#888;" type="button" onclick="document.getElementById('reset_btn').submit();">
              <span class="material-icons">clear</span>リセット
            </button>
            <input type="hidden" name="search" value="">
            <?php if( $choice_user != ""){ ?>
              <input type="hidden"  name="choice_user" value="<?php echo $choice_user;?>">
              <input type="hidden"  name="choice_sheet" value="<?php echo $choice_sheet;?>">
              <?php if( $choice_edit ){ ?>
                <input type="hidden"  name="choice_page" value="">
              <?php } ?>
              <?php if( $choice_schedule !=  ""){ ?>
                <input type="hidden"  name="choice_schedule" value="<?php echo $choice_schedule;?>">
              <?php } ?>
            <?php } ?>
          </div>
        </div>
      </form>
      <form action="<?php echo getURLSetSlag('admin-spirit-explanation-schedule-list'); ?>" method="post" id="reset_btn" style="display:none">
        <button class="admin-btn">リセット</button>
        <input type="hidden" name="search_reset" value="">
        <?php if( $choice_user != ""){ ?>
          <input type="hidden"  name="choice_user" value="<?php echo $choice_user;?>">
          <input type="hidden"  name="choice_sheet" value="<?php echo $choice_sheet;?>">
          <?php if( $choice_edit ){ ?>
            <input type="hidden"  name="choice_page" value="">
          <?php } ?>
          <?php if( $choice_schedule !=  ""){ ?>
            <input type="hidden"  name="choice_schedule" value="<?php echo $choice_schedule;?>">
          <?php } ?>
        <?php } ?>
      </form>
    </div>
  </div>

  
  <div class="admin-table-area">
    <?php if(count($schedule_list) >= 1){?>



        <?php 
          //チェックを入れるとテーブルの実地日で今日以降のものだけ表示される
          $today = date("Y-m-d");
          $today_date = new DateTime($today);
          $today_date->modify('+1 day');
          $today_date_str = $today_date->format('Y-m-d');
          
          
        
        
        
        ?>


        <div style="margin-bottom: 10px;">
          <label>
            <input type="checkbox" id="show-future-only" />
            実施日が今日以降のみ表示
          </label>
        </div>


        <div class="admin-temporary-registration-check-table" >

            

            <table id="sort-table" class="display" style="padding-top: 20px;">

                <thead>
                    <tr>
                        <?php if(current_user_can('administrator')){?>
                            <th style="width: 160px;border: 1px solid black;"></th>
                        <?php } ?>
                            <th style="border: 1px solid black;text-align: center;">実施日</th>
                        <?php if(current_user_can('administrator')){?>
                            <th style="border: 1px solid black;text-align: center;">締切日</th>
                        <?php } ?>

                        <th style="border: 1px solid black;text-align: center;">施術名</th>

                        <?php if(current_user_can('administrator')){?>
                          <th style="border: 1px solid black;text-align: center;">価格</th>
                            <th style="border: 1px solid black;text-align: center;">予約</th>
                        <?php } ?>
                        <th style="border: 1px solid black;text-align: center;">状態</th>
                    </tr>
                </thead>
                <tbody>
                   
                    <?php  foreach ($schedule_list as $key => $value) {?>



                        <?php $schedule_data = $spiritScheduleData->getSpritScheduledetail( $key ); ?>


                        <?php 
                        
                            if($schedule_data["施術グループ"] != $group)
                            {
                                continue;
                            }


                            //絞込

                            //実施日
                            if( isset($current_user_search_data['search_schedule_date_start']) && $current_user_search_data['search_schedule_date_start'] != "")
                            {
                            
                                if($schedule_data["実行日"] == "")
                                {
                                    continue;
                                }

                                $d1 = new DateTime($schedule_data["実行日"]);
                                $d2 = new DateTime($current_user_search_data['search_schedule_date_start']);

                            

                                if($d1 < $d2)
                                {
                                    continue;
                                }
                            }

                             //終了日
                            if( isset($current_user_search_data['search_schedule_date_end']) && $current_user_search_data['search_schedule_date_end'] != "")
                            {

                                if($schedule_data["実行日"] == "")
                                {
                                    continue;
                                }

                                $d1 = new DateTime($schedule_data["実行日"]);
                                $d2 = new DateTime($current_user_search_data['search_schedule_date_end']);

                                if($d1 > $d2)
                                {
                                    continue;
                                }
                            }


                             //締切日
                            if( isset($current_user_search_data['search_schedule_limit_start']) && $current_user_search_data['search_schedule_limit_start'] != "")
                            {
                            
                                if($schedule_data["実締切日"] == "")
                                {
                                    continue;
                                }

                                $d1 = new DateTime($schedule_data["実締切日"]);
                                $d2 = new DateTime($current_user_search_data['search_schedule_limit_start']);

                            

                                if($d1 < $d2)
                                {
                                    continue;
                                }
                            }

                             //終了日
                            if( isset($current_user_search_data['search_schedule_limit_end']) && $current_user_search_data['search_schedule_limit_end'] != "")
                            {

                                if($schedule_data["実締切日"] == "")
                                {
                                    continue;
                                }

                                $d1 = new DateTime($schedule_data["実締切日"]);
                                $d2 = new DateTime($current_user_search_data['search_schedule_limit_end']);

                                if($d1 > $d2)
                                {
                                    continue;
                                }
                            }
                        

                            //施術名
                            if(isset($current_user_search_data["search_schedule_spirit_type"]))
                            {
                                if($current_user_search_data["search_schedule_spirit_type"] != "")
                                {
                                    if($current_user_search_data["search_schedule_spirit_type"] != $schedule_data["施術名"]) continue;
                                }
                            }

                            //表示(状態)
                            if(isset($current_user_search_data["search_schedule_disp"]))
                            {
                                if($current_user_search_data["search_schedule_disp"] != "")
                                {
                                    if($current_user_search_data["search_schedule_disp"] != $schedule_data["表示ステータス"]) continue;
                                }
                            }

                            //担当者
                            if(isset($current_user_search_data["search_schedule_charge"]))
                            {
                                if($current_user_search_data["search_schedule_charge"] != "")
                                {
                                    if($current_user_search_data["search_schedule_charge"] != $schedule_data["担当者"]) continue;
                                }
                            }
                        
                        ?>



                        <tr>
                       
                            <?php if(current_user_can('administrator')){?>

                                <td style="border: 1px solid black;">
                                    <?php if(!$choice_user){?>
                                        <div style="display: flex;width: 110px; justify-content: space-around;">

                                            <form action="<?php echo getURLSetSlag("admin-spirit-explanation-schedule-edit"); ?>" method="post" style="margin-left: 3px;">
                                                <input type="hidden" name="edit_schedule" value="<?php echo $key;?>">
                                                <button type="submit"  class="admin-temporary-registration-new-post-submit" style="width: 50px;height: 30px;">編</button>
                                            </form>

                                            <?php if(count($schedule_data["予約者"]) == 0){ ?>
                                                <form action="<?php echo getURLSetSlag("admin-spirit-explanation-schedule-list"); ?>" method="post" style="margin-left: 3px;" onSubmit="return delete_check()">
                                                    <input type="hidden" name="schedule_delete" value="<?php echo $key;?>">
                                                    <button type="submit"  class="admin-temporary-registration-new-post-submit" style="width: 50px;height: 30px;background-color: red;color: white;">削</button>
                                                </form>
                                            <?php } ?>
                                        </div>

                                    <?php }else if($schedule_data["表示ステータス"] == SpiritScheduleClass::SCHEDULE_DISP || $schedule_data["表示ステータス"] == SpiritScheduleClass::SCHEDULE_DISP_WAIT || $choice_schedule ==  $key){ //スケジュール変更 ?>

                                        <?php if( $choice_edit ){ ?>


                                            <form action="<?php echo getURLSetSlag("admin-spirit-detail"); ?>?user_id=<?php echo $choice_user; ?>&sheet_name=<?php echo $choice_sheet; ?>&sheet_edit=on" method="post" style="margin-left: 3px;">

                                        <?php }else{ ?>
                                        
                                            <form action="<?php echo getURLSetSlag("admin-spirit-detail"); ?>?user_id=<?php echo $choice_user; ?>&sheet_name=<?php echo $choice_sheet; ?>" method="post" style="margin-left: 3px;">

                                        <?php } ?>

                                            <input type="hidden" name="save_schedule" value="<?php echo $key;?>">


                                            <?php if( $choice_schedule ==  $key){ ?>
                                                <button type="submit"  class="admin-temporary-registration-new-post-submit" style="width: 65px;height: 30px;background-color: red;color: white;">選択中</button>
                                            <?php }else{ ?>
                                                <button type="submit"  class="admin-temporary-registration-new-post-submit" style="width: 50px;height: 30px;">選択</button>
                                            <?php } ?>
                                            
                                        </form>

                                    <?php } ?>
                                </td>
                            <?php } ?>
                           
                                                
                            <td  style="border: 1px solid black;text-align: left;vertical-align: middle;font-size: 14px;">
                                <?php echo $schedule_data["実行年月日"];?>
                                <?php if($schedule_data["実行時間分"] != ""){ echo "<br>" .$schedule_data["実行時間分"] . "～"; }?>
                            </td>

                            <?php if(current_user_can('administrator')){?>
                                <td  style="border: 1px solid black;vertical-align: middle;font-size: 14px;">
                                    <?php echo $schedule_data["実締切年月日"];?>
                                </td>

                            <?php } ?>

                            <td  style="border: 1px solid black;text-align: left;white-space: normal;vertical-align: middle;font-size: 14px;">
                           
                                <?php if(current_user_can('administrator')){?>
                                    <?php echo $schedule_data["表示名"];?><br>(担当:<?php echo $schedule_data["担当者名前"];?>)
                                <?php }else{ ?>

                                    <form action="<?php echo getURLSetSlag('admin-spirit-explanation-schedule-edit'); ?>" method="post" style="margin-left: 3px;display:inline;">
                                        <input type="hidden" name="edit_schedule" value="<?php echo $key;?>">
                                        <input type="hidden" name="read" value="read">
                                        <a href="#" class="admin-link-like" onclick="this.closest('form').submit();return false;">
                                            <?php echo $schedule_data['施術名管理'];?>
                                        </a>
                                    </form>

                                    
                                <?php } ?>
                            </td>

                           

                            <?php if(current_user_can('administrator')){?>
                                <td  style="border: 1px solid black;text-align: left;white-space: normal;vertical-align: middle;font-size: 14px;">
                                <?php echo $schedule_data["価格"];?>円
                                </td>
                            

                                <td  style="border: 1px solid black;text-align: center;vertical-align: middle;font-size: 14px;">
                                <?php echo $schedule_data["予約人数"];?>人
                                </td>
                                
                            <?php } ?>
                            <td  style="border: 1px solid black;vertical-align: middle;">
                                <?php 
                            
                                    $disp = get_field('acf_spirit_schedule_disp' ,$key);
                            
                            
                                    if($schedule_data["表示ステータス"] == SpiritScheduleClass::SCHEDULE_DISP_NOT)
                                    {
                                        echo "非表示";
                                    }
                                    else if($schedule_data["表示ステータス"] == SpiritScheduleClass::SCHEDULE_DISP_WAIT)
                                    {
                                        echo  "<font color='blue'>" . $schedule_data["開始年月日"] ." 表示予定</font>";
                                    }
                                    else if($schedule_data["表示ステータス"] == SpiritScheduleClass::SCHEDULE_DISP)
                                    {
                                        echo  "<font color='red'>表示中</font>";
                                    }
                                    else if($schedule_data["表示ステータス"] == SpiritScheduleClass::SCHEDULE_DISP_OVER)
                                    {
                                        echo  "<font color='glay'>締め切り</font>";
                                    }
                                    else if($schedule_data["表示ステータス"] == SpiritScheduleClass::SCHEDULE_DISP_RESERVATION_OVER)
                                    {
                                        echo  "<font color='glay'>人数締め切り</font>";
                                    }
                        
                                ?>
                            </td>
                       
                        </tr>
                    <?php } ?>
                </tbody>


            </table>

        </div>

    <?php } ?>

 </div>

</div>






<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#sort-table').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/ja.json"
            },
            "pageLength": 20,  // 1ページあたりの行数
            "lengthMenu": [20, 50, 100],  // 選択できる件数
            "order": []  // 初期ソートなし
        });
    });
</script>



<script>
function delete_check(){

	if(window.confirm('削除してもよろしいですか？')){ // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

	}
	else{ // 「キャンセル」時の処理

		window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止

	}

}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var header = document.getElementById('searchAccordionHeader');
  var content = document.getElementById('searchAccordionContent');
  var icon = document.getElementById('searchAccordionIcon');
  var open = false;
  header.addEventListener('click', function () {
    open = !open;
    if (open) {
      content.classList.add('open');
      header.classList.add('open');
      icon.textContent = 'expand_more';
    } else {
      content.classList.remove('open');
      header.classList.remove('open');
      icon.textContent = 'chevron_right';
    }
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const checkbox = document.getElementById('show-future-only');
  const today = new Date();
  today.setHours(0,0,0,0);

  checkbox.addEventListener('change', function() {
    const rows = document.querySelectorAll('#sort-table tbody tr');
    rows.forEach(row => {
      // 管理者は2列目、一般ユーザーは1列目
      const dateCell = row.querySelector('td:nth-child(' + (<?php echo current_user_can('administrator') ? 2 : 1; ?>) + ')');
      if (!dateCell) return;
      // 日付部分だけ取得（改行や時刻が入る場合も考慮）
      const dateText = dateCell.textContent.trim().split(/\s|<br>/)[0];
      // 日付をパース（YYYY-MM-DD or YYYY/MM/DD or YYYY.MM.DD）
      let rowDate = new Date(dateText.replace(/\./g, '-').replace(/年|月/g, '-').replace(/日/g, ''));
      if (isNaN(rowDate.getTime())) {
        row.style.display = '';
        return;
      }
      rowDate.setHours(0,0,0,0);
      if (checkbox.checked) {
        row.style.display = (rowDate >= today) ? '' : 'none';
      } else {
        row.style.display = '';
      }
    });
  });
});
</script>