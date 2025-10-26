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

    $group = SpiritTypeClass::SPIRIT_TYPE_NAME_DAY;

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
    $spiritType = $spiritTypeData->getSpiritType();
    //場所取得
    $place_list = $spiritPlaceData->getSpritPlaceList();
    //スケジュール
    $schedule_list = $spiritScheduleData->getSpritScheduleList();

    //var_dump($current_user_search_data);

?>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">

<style>
.dataTables_wrapper {
    width: 100%;
    margin: 0 auto;
}
.table-responsive {
    width: 100%;
    overflow-x: auto;
}
#sort-table {
    width: 100% !important;
}
</style>

<div class="admin-exorcism-button-area" style="margin-bottom: 240px;max-width: 1800px;">

    <div class="admin-title">
        <?php if($group == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY){?>
            <?php echo "日程確定施術スケジュール一覧"; ?>
        <?php }else{ ?>
            <?php echo "相談・ヒーリングスケジュール一覧"; ?>
        <?php } ?>
	</div>

    <div class="">
       
        <?php if(!$choice_user){?>
            <div style="margin-bottom: 20px;text-align: right;">
                <button class="admin-preview-button" type="button"  style="cursor: pointer;height: 30px;width: 160px;"  onclick="window.location.href='<?php echo getURLSetSlag("admin-spirit-schedule-calendar"); ?>'">カレンダー表示</button>
                <button class="admin-preview-button" type="button"  style="cursor: pointer;height: 30px;width: 300px;background-color: aliceblue;"  onclick="window.location.href='<?php echo getURLSetSlag("admin-spirit-explanation-schedule-list"); ?>?group=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN?>'">相談・ヒーリング スケジュール一覧</button>

            </div>
        <?php }else{ ?>

            <?php if( $choice_edit ){ ?>

                <div style="margin-bottom: 20px;text-align: right;">
                    <form action="<?php echo getURLSetSlag("admin-spirit-detail"); ?>?user_id=<?php echo $choice_user; ?>&sheet_name=<?php echo $choice_sheet; ?>&sheet_edit=on" method="post" style="margin-left: 3px;">

                        <input type="hidden"  name="choice_page" value="">

                    <?php }else{ ?>
                                        
                        <form action="<?php echo getURLSetSlag("admin-spirit-detail"); ?>?user_id=<?php echo $choice_user; ?>&sheet_name=<?php echo $choice_sheet; ?>" method="post" style="margin-left: 3px;">

                    <?php } ?>

				        <button type="submit"  class="admin-preview-button" style="background-color: lightgray;cursor: pointer;">浄霊シートに戻る</button>

                                            
			        </form>

                </div>

        <?php } ?>


        <div class="admin-temporary-registration-search-header" style="display: flex;justify-content: space-between;">


            <div class="admin-temporary-registration-search-area">

                <div class="admin-temporary-registration-search-flex">

                    <form action="<?php echo getURLSetSlag("admin-spirit-schedule-list"); ?>" method="post">

                        <div class="admin-temporary-registration-search-flex">

                            <div class="admin-temporary-registration-input-box">

                                <div class="admin-temporary-registration-input-label">実施日</div>

                                <div class="admin-temporary-registration-input-flex">
                                    <div class="admin-temporary-registration-input-form">
                                        <input type="date" id="" name="search_schedule_date_start" value="<?php  if(isset($current_user_search_data["search_schedule_date_start"])){ echo $current_user_search_data["search_schedule_date_start"];}?>">
                                    </div>
                
                                    <div style="margin-left: 10px;margin-right: 10px;">～</div>

                                    <div class="admin-temporary-registration-input-form">
                                        <input type="date" id="" name="search_schedule_date_end" value="<?php if(isset($current_user_search_data["search_schedule_date_end"])){echo $current_user_search_data["search_schedule_date_end"];}?>">
                                    </div>
                                </div>

                            </div>

                            <div class="admin-temporary-registration-input-box">

                                <div class="admin-temporary-registration-input-label">締切日</div>

                                <div class="admin-temporary-registration-input-flex">
                                    <div class="admin-temporary-registration-input-form">
                                        <input type="date" id="" name="search_schedule_limit_start" value="<?php  if(isset($current_user_search_data["search_schedule_limit_start"])){ echo $current_user_search_data["search_schedule_limit_start"];}?>">
                                    </div>
                
                                    <div style="margin-left: 10px;margin-right: 10px;">～</div>

                                    <div class="admin-temporary-registration-input-form">
                                        <input type="date" id="" name="search_schedule_limit_end" value="<?php if(isset($current_user_search_data["search_schedule_limit_end"])){echo $current_user_search_data["search_schedule_limit_end"];}?>">
                                    </div>
                                </div>

                            </div>

                            <div class="admin-temporary-registration-input-box">
                                <div class="admin-temporary-registration-input-label">施術名</div>

                                <div class="admin-temporary-registration-input-flex">
                                    <div class="admin-temporary-registration-input-form">
                                        <select name="search_schedule_spirit_type" style="font-size: 16px;margin: 3px;margin-left: 0px;">
                                            <option value="" >全て表示</option>
                                            <?php  foreach ($spiritType[ SpiritTypeClass::SPIRIT_TYPE_NAME_DAY ] as $key => $value) {?>

                                                <?php if( $value["group"] != $group){ continue; } ?>

                                                <option value="<?php echo $value["ID"];?>" <?php if(isset( $current_user_search_data["search_schedule_spirit_type"])) {if( $current_user_search_data["search_schedule_spirit_type"] == $value["ID"]) { echo "selected"; }}?>><?php echo $value["title"];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="admin-temporary-registration-input-box">
                                <div class="admin-temporary-registration-input-label">表示(状態)</div>

                                <div class="admin-temporary-registration-input-flex">
                                    <div class="admin-temporary-registration-input-form">
                                        <select name="search_schedule_disp" style="font-size: 16px;margin: 3px;margin-left: 0px;">
                                            <option value="" >全て表示</option>
                                            <option value="<?php echo SpiritScheduleClass::SCHEDULE_DISP;?>" <?php if(isset( $current_user_search_data["search_schedule_disp"])) {if( $current_user_search_data["search_schedule_disp"] == SpiritScheduleClass::SCHEDULE_DISP) { echo "selected"; }}?>>表示中</option>
                                            <option value="<?php echo SpiritScheduleClass::SCHEDULE_DISP_WAIT;?>" <?php if(isset( $current_user_search_data["search_schedule_disp"])) {if( $current_user_search_data["search_schedule_disp"] == SpiritScheduleClass::SCHEDULE_DISP_WAIT) { echo "selected"; }}?>>待機中</option>
                                            <option value="<?php echo SpiritScheduleClass::SCHEDULE_DISP_OVER;?>" <?php if(isset( $current_user_search_data["search_schedule_disp"])) {if( $current_user_search_data["search_schedule_disp"] == SpiritScheduleClass::SCHEDULE_DISP_OVER) { echo "selected"; }}?>>締め切り</option>
                                            <option value="<?php echo SpiritScheduleClass::SCHEDULE_DISP_NOT;?>" <?php if(isset( $current_user_search_data["search_schedule_disp"])) {if( $current_user_search_data["search_schedule_disp"] == SpiritScheduleClass::SCHEDULE_DISP_NOT) { echo "selected"; }}?>>非表示</option>
                                            <option value="<?php echo SpiritScheduleClass::SCHEDULE_DISP_RESERVATION_OVER;?>" <?php if(isset( $current_user_search_data["search_schedule_disp"])) {if( $current_user_search_data["search_schedule_disp"] == SpiritScheduleClass::SCHEDULE_DISP_RESERVATION_OVER) { echo "selected"; }}?>>人数締め切り</option>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="admin-temporary-registration-input-box">
                                <div class="admin-temporary-registration-input-label">場所</div>

                                <div class="admin-temporary-registration-input-flex">
                                    <div class="admin-temporary-registration-input-form">
                                        <select name="search_schedule_spirit_place" style="font-size: 16px;margin: 3px;margin-left: 0px;">
                                            <option value="" >全て表示</option>
                                            <?php  foreach ($place_list as $key => $value) {?>

                                                <option value="<?php echo $key;?>" <?php if(isset( $current_user_search_data["search_schedule_spirit_place"])) {if( $current_user_search_data["search_schedule_spirit_place"] == $key) { echo "selected"; }}?>><?php echo get_field('acf_spirit_palce_name' ,$key);?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <div class="admin-temporary-registration-input-box">
                                

                        <button class="admin-remote-search-button">絞り込む</button>

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

                        <button type="button" class="admin-remote-search-reset-button" onclick="document.getElementById('reset_btn').submit();">リセット</button>
                        </div>

                    </form>
                    
                    
                </div>

                <form action="<?php echo getURLSetSlag("admin-spirit-schedule-list"); ?>" method="post" id="reset_btn" style="display:none">

                    <button class="admin-remote-search-reset-button">リセット</button>

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

        <?php if(count($schedule_list) >= 1){?>

            <div class="admin-temporary-registration-check-table" style="">
                <div style="margin-bottom: 20px;">
                    <button class="admin-preview-button" type="button" style="background-color: red;cursor: pointer;height: 40px;width: 400px;color: white;" onclick="window.location.href='<?php echo getURLSetSlag("admin-spirit-schedule-edit"); ?>'">新規作成</button>
                </div>

                <div class="table-responsive">
                    <table id="sort-table" class="display nowrap" style="width:100%">

                        <thead>
                            <tr>
                            
                                <th style="width: 160px;border: 1px solid black;"></th>
                                <th style="border: 1px solid black;text-align: center;">実施日</th>
                                <th style="border: 1px solid black;text-align: center;">締切日</th>
                                <th style="min-width: 300px;border: 1px solid black;text-align: center;">表示名</th>
                                <th style="min-width: 300px;border: 1px solid black;text-align: center;">施術名</th>
                                <th style="border: 1px solid black;text-align: center;">価格</th>
                                <th style="border: 1px solid black;text-align: center;">予約枠</th>
                                <th style="border: 1px solid black;text-align: center;">予約人数</th>
                                <th style="border: 1px solid black;text-align: center;">表示(状態)</th>
                                <th style="border: 1px solid black;text-align: center;">場所</th>
                         
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

                                    //場所
                                    if(isset($current_user_search_data["search_schedule_spirit_place"]))
                                    {
                                        if($current_user_search_data["search_schedule_spirit_place"] != "")
                                        {
                                            if($current_user_search_data["search_schedule_spirit_place"] != $schedule_data["場所"]) continue;
                                        }
                                    }
                                
                                ?>



                                <tr>
                               
                                    <td style="border: 1px solid black;">
                                
                                        <?php if(!$choice_user){?>
                                            <div style="display: flex;width: 110px; justify-content: space-around;">

                                                <form action="<?php echo getURLSetSlag("admin-spirit-schedule-edit"); ?>" method="post" style="margin-left: 3px;">
						                            <input type="hidden" name="edit_schedule" value="<?php echo $key;?>">
						                            <button type="submit"  class="admin-temporary-registration-new-post-submit" style="width: 50px;height: 30px;">編</button>
					                            </form>
                                                <?php if(count($schedule_data["予約者"]) == 0){ ?>
                                                    <form action="<?php echo getURLSetSlag("admin-spirit-schedule-list"); ?>" method="post" style="margin-left: 3px;" onSubmit="return delete_check()">
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

                                    <td  style="border: 1px solid black;text-align: left;vertical-align: middle;font-size: 14px;">
                                        <?php echo $schedule_data["実行年月日"];?>
                                        <?php if($schedule_data["実行時間"] != ""){ echo "<br>" .$schedule_data["実行時間"] . ":00～"; }?>
                                    </td>

                                    <td  style="border: 1px solid black;vertical-align: middle;font-size: 14px;">
                                        <?php echo $schedule_data["実締切年月日"];?>
                                    </td>

                                    <td  style="border: 1px solid black;text-align: left;white-space: normal;vertical-align: middle;font-size: 14px;">
                                       <?php echo $schedule_data["表示名"];?>
                                    </td>

                                    <td  style="border: 1px solid black;text-align: left;white-space: normal;vertical-align: middle;font-size: 14px;">
                                       <?php echo $schedule_data["施術名管理"];?>
                                    </td>

                                    <td  style="border: 1px solid black;text-align: center;vertical-align: middle;font-size: 14px;">
                                       <?php echo $schedule_data["価格"];?>円
                                    </td>

                                    <td  style="border: 1px solid black;text-align: center;vertical-align: middle;font-size: 14px;">
                                        <?php 
                                            if($schedule_data["人数"] == 0)
                                            {
                                                echo "制限なし";
                                            }
                                            else{
                                                echo $schedule_data["人数"] . "人";
                                            }
                                        ?>
                                    </td>
                                    <td  style="border: 1px solid black;text-align: center;vertical-align: middle;font-size: 14px;">
                                       <?php echo $schedule_data["予約人数"];?>人
                                    </td>
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

                                    <td  style="border: 1px solid black;width: 100%;text-align: left;vertical-align: middle;white-space: normal;font-size: 14px;">
                                        <?php 
                                            if($schedule_data["場所"] == "")
                                            {
                                                echo "";
                                            }
                                            else{

                                                if($schedule_data["場所ステータス"]["URL"] == "")
                                                {
                                                    echo  $schedule_data["場所ステータス"]["名前"];
                                                }
                                                else
                                                {
                                                ?>
                                                     <a href="<?php echo $schedule_data["場所ステータス"]["URL"]; ?>"  target="_blank"><?php echo  $schedule_data["場所ステータス"]["名前"];?></a>

                                                <?php
                                                }

                                            
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
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script>
$(document).ready(function() {
    $('#sort-table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/ja.json"
        },
        "pageLength": 20,
        "lengthMenu": [20, 50, 100],
        "order": [],
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ],
        "responsive": true,
        "scrollX": true,
        "autoWidth": false
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