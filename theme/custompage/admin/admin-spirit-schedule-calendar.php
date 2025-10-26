

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




    //浄霊タイプ
    $spiritType = $spiritTypeData->getSpiritTypeKeyTypeNum();
    //場所取得
    $place_list = $spiritPlaceData->getSpritPlaceList();
    //スケジュール
    $schedule_list = $spiritScheduleData->getSpritScheduleList();

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
    $scheduleData = $spiritScheduleData->getSpritScheduleMonthList($calendar_year,$calendar_month);


    //var_dump($scheduleData);

?>

<div class="admin-exorcism-button-area" style="margin-bottom: 240px;max-width: 1500px;">

    <div class="admin-title">
		<?php echo "日程確定施術スケジュールカレンダー"; ?>
	</div>

    <div class="">
       
       
        <div style="margin-bottom: 20px;text-align: right;">
            <button class="admin-preview-button" type="button"  style="cursor: pointer;height: 30px;width: 160px;"  onclick="window.location.href='<?php echo getURLSetSlag("admin-spirit-schedule-list"); ?>'">リスト表示</button>
            <button class="admin-preview-button" type="button"  style="cursor: pointer;height: 30px;width: 300px;background-color: aliceblue;"  onclick="window.location.href='<?php echo getURLSetSlag("admin-spirit-explanation-schedule-calendar"); ?>'">相談・ヒーリング スケジュール一覧</button>
        </div>



        <div class="admin-schedule-calendar-area">


            <div class="admin-schedule-calendar-title-area">

                <div class="admin-schedule-calendar-title-moanth">

                    <form action="<?php echo getURLSetSlag("admin-spirit-schedule-calendar"); ?>" method="get" onSubmit="">

                        <input type="submit" value="本日" style="font-size: 20px;border-radius: 17px;background-color: floralwhite;">

                    </form>
                </div>

                <div class="admin-schedule-calendar-title-moanth">

                    <form action="<?php echo getURLSetSlag("admin-spirit-schedule-calendar"); ?>" method="get" onSubmit="">

                        <input type="hidden" name="calendar_year" value="<?php echo $befor_year; ?>">
                        <input type="hidden" name="calendar_month" value="<?php echo $befor_month; ?>">

                        <input type="submit" value="<" style="border: 0;background-color: white;font-size: 30px;cursor: pointer;">

                    </form>
                </div>
                <div class="admin-schedule-calendar-title-moanth">
                   <form action="<?php echo getURLSetSlag("admin-spirit-schedule-calendar"); ?>" method="get" onSubmit="">

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

                                                        if($schedule_data["施術グループ"] != SpiritTypeClass::SPIRIT_TYPE_NAME_DAY){
                                                            continue;
                                                        }
                                                    ?>

                                                        <form action="<?php echo getURLSetSlag("admin-spirit-schedule-edit"); ?>?setyear=<?php echo $day['year'];?>&set_month=<?php echo $day['month'];?>&set_day=<?php echo $day['day'];?>" method="post" onSubmit="" target="_blank">

                                                            <input type="hidden" name="edit_schedule" value="<?php echo $day_value; ?>">
                                                            <input type="hidden" name="read" value="read">

                                                            <input type="submit" value="〇<?php  echo mb_substr($dispText, 0 , 18);  ?>" style="border: 0;background-color: white;padding: 0;font-size: 12px;cursor: pointer;">

                                                        </form>

                                                    <?php
                                                    }
                                                }

                                                

                                            ?>
                                        </div>


                                        

                                    </div>


                                  
                                    <?php if( $calendar_today){ ?>
                                        <div class='schedule-calendar-table-new-content'>

                                             <a href="<?php echo getURLSetSlag("admin-spirit-schedule-edit"); ?>?setyear=<?php echo $day['year'];?>&set_month=<?php echo $day['month'];?>&set_day=<?php echo $day['day'];?>">新規作成</a>

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



