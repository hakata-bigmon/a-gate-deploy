<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritPlaceClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");



    $spiritPlaceData = new SpiritPlsceClass(); //場所データ

    $spiritTypeData = new SpiritTypeClass(); //浄霊タイプデータ
    $spiritScheduleData = new SpiritScheduleClass(); //スケジュールデータ
    
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

       update_user_meta(get_current_user_id(),'search_schedule_list',json_encode($search_array, JSON_UNESCAPED_UNICODE));
   }

     //検索リセット
   if(isset($_POST["search_reset"]))
   {
       update_user_meta(get_current_user_id(),'search_schedule_list',"");
   }

   //検索取得
   $current_user_search_data = get_user_meta(get_current_user_id(),'search_schedule_list',true);//表示設定取得
   
   if($current_user_search_data != "-" && $current_user_search_data != null){

        $current_user_search_data = json_decode($current_user_search_data, true);//表示設定取得
   }



   $charge_user_id = "";

   if(!current_user_can('administrator')){
       $charge_user_id = get_current_user_id();
   }

    //浄霊タイプ
    $spiritType = $spiritTypeData->getSpiritTypeKeyTypeNum();
    //場所取得
    $place_list = $spiritPlaceData->getSpritPlaceList();
    //スケジュール
    $schedule_list = $spiritScheduleData->getSpritScheduleList($charge_user_id);

    //var_dump($current_user_search_data);
    date_default_timezone_set('Asia/Tokyo'); 

    $today = new DateTime(); // 今日の日付
    $today->setTime(0, 0, 0);

    $calendar_year = "";
    $calendar_month = "";


    if(isset($_GET["calendar_year"]))
    {
        $calendar_year = $_GET["calendar_year"];
        $calendar_month = $_GET["calendar_month"];
    }

    //何も入っていない
    if($calendar_year == "")
    {
        $calendar_year = $today->format('Y');
        $calendar_month = $today->format('n');
    }


    $befor_year = $calendar_year;
    $befor_month = $calendar_month - 1;

    if($befor_month == 0)
    {
        $befor_year = $calendar_year - 1;
        $befor_month = 12;
    }

    $next_year = $calendar_year;
    $next_month = $calendar_month + 1;

    if($next_month == 13)
    {
        $next_year = $calendar_year + 1;
        $next_month = 1;
    }

    //カレンダーの作成
    $calendarArray = $spiritScheduleData->generateCalendar($calendar_year, $calendar_month);

    //スケジュールを取得
    $scheduleData = $spiritScheduleData->getSpritScheduleMonthList($calendar_year,$calendar_month,$charge_user_id);


    //var_dump($scheduleData);

?>

<style>
.ellipsis-btn {
  border: 0;
  background-color: white;
  padding: 0;
  font-size: 12px;
  cursor: pointer;
  width: 110px;
  max-width: 100%;
  display: inline-block;
  vertical-align: middle;
}
.ellipsis-btn span {
  display: inline-block;
  max-width: 100%;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
/* 追加: 上部ボタンデザイン */
.top-btn-row {
  display: flex;
  gap: 18px;
  margin-bottom: 20px;
  justify-content: flex-end;
  flex-wrap: wrap;
}
.top-btn-row .top-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  border: none;
  border-radius: 20px;
  font-size: 1.1rem;
  font-weight: bold;
  box-shadow: 0 2px 8px rgba(0,0,0,0.07);
  cursor: pointer;
  padding: 10px 32px;
  transition: background 0.2s, color 0.2s, box-shadow 0.2s;
  background: linear-gradient(90deg, #e3f2fd 0%, #bbdefb 100%);
  color: #1976d2;
}
.top-btn-row .top-btn.schedule-list {
  background: linear-gradient(90deg, #ffe0b2 0%, #ffcc80 100%);
  color: #e65100;
}
.top-btn-row .top-btn.confirm-list {
  background: linear-gradient(90deg, #b2ebf2 0%, #80deea 100%);
  color: #00838f;
}
.top-btn-row .top-btn:hover {
  filter: brightness(1.08);
  box-shadow: 0 4px 16px rgba(33, 150, 243, 0.13);
}
.top-btn-row .material-icons {
  font-size: 1.3em;
}
@media (max-width: 900px) {
  .top-btn-row {
    flex-direction: column;
    gap: 10px;
    align-items: stretch;
  }
}
</style>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="admin-exorcism-button-area" style="margin-bottom: 240px;max-width: 1500px;">

    <div class="admin-title">
		<?php echo "相談・ヒーリング　スケジュールカレンダー"; ?>
	</div>

    <div class="">
       
       
        <div class="top-btn-row">
            <button class="top-btn schedule-list" type="button" onclick="window.location.href='<?php echo getURLSetSlag("admin-spirit-explanation-schedule-list"); ?>'"><span class="material-icons">list_alt</span>リスト表示</button>
            <?php if(current_user_can('administrator') || current_user_can('editor')){ ?>
                <button class="top-btn confirm-list" type="button" onclick="window.location.href='<?php echo getURLSetSlag("admin-spirit-schedule-calendar"); ?>'"><span class="material-icons">event_available</span>日程確定施術 スケジュール一覧</button>
            <?php } ?>
        </div>



        <div class="admin-schedule-calendar-area">


            <div class="admin-schedule-calendar-title-area">

                <div class="admin-schedule-calendar-title-moanth">

                    <form action="<?php echo getURLSetSlag("admin-spirit-explanation-schedule-calendar"); ?>" method="get" onSubmit="">

                        <input type="submit" value="本日" style="font-size: 20px;border-radius: 17px;background-color: floralwhite;">

                    </form>
                </div>

                <div class="admin-schedule-calendar-title-moanth">

                    <form action="<?php echo getURLSetSlag("admin-spirit-explanation-schedule-calendar"); ?>" method="get" onSubmit="">

                        <input type="hidden" name="calendar_year" value="<?php echo $befor_year; ?>">
                        <input type="hidden" name="calendar_month" value="<?php echo $befor_month; ?>">

                        <input type="submit" value="<" style="border: 0;background-color: white;font-size: 30px;cursor: pointer;">

                    </form>
                </div>
                <div class="admin-schedule-calendar-title-moanth">
                   <form action="<?php echo getURLSetSlag("admin-spirit-explanation-schedule-calendar"); ?>" method="get" onSubmit="">

                        <input type="hidden" name="calendar_year" value="<?php echo $next_year; ?>">
                        <input type="hidden" name="calendar_month" value="<?php echo $next_month; ?>">

                        <input type="submit" value=">" style="border: 0;background-color: white;font-size: 30px;cursor: pointer;">

                    </form>
                </div>

                <div class="admin-schedule-calendar-title"><?php echo $calendar_year ."年" .$calendar_month ."月" ?></div>

            </div>


            <div class="admin-schedule-calendar-contents">
       
                <table class="admin-schedule-calendar-table">
                    <tr>
                        <th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th>土</th><th style="background-color: lavender;">日</th>
                    </tr>
                    <tr>


                        <?php
                        foreach ($calendarArray as $index => $day) {
                            // 7日ごとに改行
                            if ($index % 7 == 0 && $index != 0) {
                                echo "</tr><tr>";
                            }

                            // クラスの設定
                            $class = $day['current'] ? "schedule-calendar-table-current-month" : "schedule-calendar-table-prev-month schedule-calendar-table-next-month";

                            // 「〇月1日」の表示を追加

                            $calendar_today = false;
                            $calendar_today_mark = false;

                            $scheduledDate = new DateTime($day['year'] . "-" . $day['month'] . "-" .$day['day']);
            
                            if ($scheduledDate >= $today) {
                                $calendar_today = true;//本日も含めた未来
                            }

                            if ($scheduledDate == $today) {
                                $calendar_today_mark = true;//本日
                            }

                            //今日より先の場合は色を変えない
                            if($calendar_today)
                            {
                                $class = "schedule-calendar-table-current-month";
                            }

                            //祝日取得
                            $holiday = $spiritScheduleData->isJapaneseHoliday($scheduledDate);

                            ?>
                        
                                <td class="<?php echo $class;?>">

                                    <div class='schedule-calendar-table-td-content'>

                                        <div class='schedule-calendar-table-date-header' <?php if($calendar_today_mark){ ?>style="background-color: plum;width: 25px;height: 18px;border-radius: 40px;margin-left: auto;margin-right: auto;"<?php } ?> >

                                            <?php
                                                if ($day['day'] == 1) {
                                                    echo $day['month'] ."月" . $day['day'] ."日";
                                                } else {
                                                    echo $day['day'];
                                                }

                                               
                                            ?>
                                        </div>
                                        <div class='schedule-calendar-table-date-content' <?php if(!$calendar_today){ ?> style="color:#bbb;"<?php } ?>>


                                            <?php if($holiday){ ?>

                                                <div class="schedule-calendar-holiday">
                                                    <?php echo $holiday;?>
                                                </div>
                                            <?php } ?>

                                            <?php

                                                if( isset( $scheduleData[ $day['year'] ][ $day['month'] ][ $day['day'] ] ) )
                                                {

                                                    foreach ( $scheduleData[ $day['year'] ][ $day['month'] ][ $day['day'] ] as $day_key => $day_value) 
                                                    {
                                                        $schedule_data = $spiritScheduleData->getSpritScheduledetail( $day_value );

                                                        $dispText = "";

                                                        if( $schedule_data["実行時間"] != "" )
                                                        { 
                                                            $dispText =  $schedule_data["実行時間"] .":00 "; 
                                                        }


                                                        $dispText .= $schedule_data["表示名"];

                                                        if($schedule_data["施術グループ"] != SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){
                                                            continue;
                                                        }
                                                    ?>

                                                        <form action="<?php echo getURLSetSlag("admin-spirit-explanation-schedule-edit"); ?>?setyear=<?php echo $day['year'];?>&set_month=<?php echo $day['month'];?>&set_day=<?php echo $day['day'];?>" method="post" onSubmit="" target="_blank">

                                                            <input type="hidden" name="edit_schedule" value="<?php echo $day_value; ?>">
                                                            <input type="hidden" name="read" value="read">

                                                            <button type="submit" class="ellipsis-btn"><span>〇<?php echo mb_substr($dispText, 0 , 18); ?></span></button>

                                                        </form>

                                                    <?php
                                                    }
                                                }

                                                

                                            ?>
                                        </div>


                                        

                                    </div>


                                  
                                    <?php if( $calendar_today){ ?>
                                        <div class='schedule-calendar-table-new-content'>

                                             <a href="<?php echo getURLSetSlag("admin-spirit-explanation-schedule-edit"); ?>?setyear=<?php echo $day['year'];?>&set_month=<?php echo $day['month'];?>&set_day=<?php echo $day['day'];?>">新規作成</a>

                                        </div>
                                    <?php } ?>
                                </td>
                        <?php } ?>
                    </tr>
                </table>

            </div>

        </div>


     </div>

</div>



