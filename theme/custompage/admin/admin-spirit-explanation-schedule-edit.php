<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritPlaceClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritQuestionDispClass.php");

    $spiritPlaceData = new SpiritPlsceClass(); //場所データ
    $spiritTypeData = new SpiritTypeClass(); //浄霊タイプデータ
    $spiritScheduleData = new SpiritScheduleClass(); //スケジュールデータ
    $userClass = new SpiritUserClass(); //ユーザー管理
    $spirit_sheet_data = new SpiritSheetClass(); //管理データ
    $disp_questiont_class = new SpiritQuestionDispClass(); //表示データ
    //浄霊タイプ
    $spiritType = $spiritTypeData->getSpiritTypeKeyTypeNum();
    //浄霊タイプソート
    $spiritTypeSort = $spiritTypeData->getSpiritType();

    //var_dump($spiritType);
    //浄霊タイプを相談だけに絞る
    foreach($spiritType as $key => $value){
        if($value["group"] != SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){
            unset($spiritType[$key]);
        }
    }
    foreach($spiritTypeSort as $key => $value){
        if($key != SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){
            unset($spiritTypeSort[$key]);
        }
    }
    
    $edit_no = "";
    $read = false;

    //var_dump($spiritType);
    // 全ユーザーの取得（管理者、編集者、投稿者）
    $users = get_users(array(
        'role__in' => array('administrator', 'editor', 'author'),
        'exclude' => array(1), // ID:1のユーザーを除外
        'orderby' => 'ID',
        'order' => 'ASC'
    ));


    //ユーザーメタから
    $type_user = array();
    $users_data = array(); // ユーザー情報を保持する配列を追加
    $spirit_type_data = array(); // 依頼種類のデータを保持する配列

    foreach($users as $user){


        //管理者以外は自分のみ
        if(!current_user_can('administrator')){
            if($user->ID != get_current_user_id()){
                continue;
            }
        }


        // ユーザー情報を保存
        $users_data[$user->ID] = array(
            'name' => $user->display_name
        );

        //可能なデータを取得
        $type = get_user_meta($user->ID, 'explanation_types', true);

        if(is_array($type)) {
            foreach($type as $key => $value){

                if(isset($spiritType[$value]))
                {
                    if(!isset($type_user[$value]))
                    {
                        $type_user[$value] = array();
                    }
                    array_push($type_user[$value],$user->ID);
                }
            }
        }
    }

    //$type_userにキーがないものはタイプから削除する
    if(!current_user_can('administrator')){
        foreach($spiritType as $key => $value){

            if(!isset($type_user[$value["ID"]])){
                unset($spiritType[$key]);
            }
            
        }

        foreach($spiritTypeSort[SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN] as $key => $value){

            if(!isset($type_user[$value["ID"]])){
                unset($spiritTypeSort[SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN][$key]);
            }
            
        }
    }
    

    // 依頼種類のデータを準備
    foreach($spiritType as $spirit_key => $spirit_value){
        if($spirit_value["group"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){ 
            $spirit_type_data[$spirit_value["ID"]] = array(
                'title' => $spirit_value["title"]
            );
        }
    }

    //var_dump($spiritType);


    //編集番号
    if(isset($_POST["edit_schedule"]))
    {
        $edit_no = $_POST["edit_schedule"];
    }
    else if(isset($_GET["edit_schedule"]))
    {
        $edit_no = $_GET["edit_schedule"];
    }


    //読み込み
   /* if(isset($_POST["read"]))
    {
        $read = true;
    }
*/

    //保存
    if(isset($_POST["save"]))
    {


        if(!isset($_POST["edit_schedule"]))
        {
            $edit_no = $spiritScheduleData->newSpritSchedule($_POST);//新しく保存
        }
        else{
             $spiritScheduleData->saveSpritSchedule( $edit_no , $_POST);
        }
    }

   // var_dump($_POST);

    //質問保存
    if(isset($_POST["save_edit_question"]))
    {
        $set_spirit_sheet = $spiritScheduleData->getSpritScheduledetail($edit_no); 
        $type = get_field('acf_acf_purespirit_type',$set_spirit_sheet["予約者データ"][0]["シートID"]);
        $spiritQuestionArray = $spirit_sheet_data->getSpiritQuestion($type);
        $anser = $spirit_sheet_data->getSpiritSheetAnswer( $set_spirit_sheet["予約者データ"][0]["シートID"] );

        //var_dump($anser);

        //var_dump($spiritQuestionArray);

        foreach ($spiritQuestionArray[$type] as $key => $value) {

            if(isset($_POST["question_" .$value["ID"]]))
            {
                $update_text = $_POST["question_" .$value["ID"]];
                $question_number = $spirit_sheet_data->saveQuestionAnser(  $set_spirit_sheet["予約者データ"][0]["ID"] , $set_spirit_sheet["予約者データ"][0]["シートID"] , $value["ID"] , $value["type"] , $update_text );
               
            }
        }

        //実行日を保存
        if(isset($_POST["execution_schedule_date"]) && $_POST["execution_schedule_date"] != "")
        {
            update_field('acf_purespirit_execution_date' , $_POST["execution_schedule_date"] , $set_spirit_sheet["予約者データ"][0]["シートID"]);
        }
    }


    $new_date = "";
    $return_calendar = false;

    //日付の設定がある
    if(isset($_GET["setyear"]) && isset($_GET["set_month"]))
    {
        $new_date = $_GET["setyear"];

        if($_GET["set_month"] < 10)
        {
            $new_date .= "-0" .  $_GET["set_month"];
        }
        else{
            $new_date .= "-" .  $_GET["set_month"];
        }

        if(isset($_GET["set_day"]))
        {
        if($_GET["set_day"] < 10)
        {
            $new_date .= "-0" .  $_GET["set_day"];
        }
        else{
            $new_date .= "-" .  $_GET["set_day"];
        }
        }
        else{
            $new_date .= "-01";
        }

        $return_calendar = true;
    }

    //calendarに戻る
    if( isset($_POST["return_calendar"]) ){
        $return_calendar = true;
    }


    //詳細情報
    $set_spirit_sheet = "";

    if($edit_no != "")
    {
        $set_spirit_sheet = $spiritScheduleData->getSpritScheduledetail($edit_no); 
    }

    //価格配列を取得
    $spiritTypePrice = $spiritTypeData->getSpiritTypePrice();

    //管理者は変更できるが、その他は申込者がいると変更できない
    if(!current_user_can('administrator') && count($set_spirit_sheet["予約者"]) > 0){
        $read = true;
    }
   

   // var_dump($set_spirit_sheet);
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<style>
select, select option {
  text-overflow: ellipsis;
  white-space: nowrap;
  overflow: hidden;
  max-width: 100%;
}
.select-wrapper {
  max-width: 100%;
}
.admin-main-btn {
  width: 100%;
  max-width: 500px;
  font-size: 18px;
  font-weight: bold;
  border-radius: 10px;
  border: none;
  padding: 16px 0;
  background: linear-gradient(90deg, #e3f2fd 0%, #bbdefb 100%);
  color: #1976d2;
  box-shadow: 0 2px 8px rgba(0,0,0,0.07);
  cursor: pointer;
  margin: 0 auto 16px auto;
  display: block;
  transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.15s;
}
.admin-main-btn:hover {
  background: #bbdefb;
  color: #0d47a1;
  box-shadow: 0 4px 16px rgba(33, 150, 243, 0.13);
  transform: translateY(-2px) scale(1.03);
}
.admin-main-btn.gray {
  background: linear-gradient(90deg, #ececec 0%, #e0e0e0 100%);
  color: #555;
}
.admin-main-btn.red {
  background: linear-gradient(90deg, #ffebee 0%, #ffcdd2 100%);
  color: #d32f2f;
}

@media (max-width: 600px) {
  .admin-title {
   font-size: 18px;
  }
}

.admin-card {
  max-width: 700px;
  margin: 40px auto 60px auto;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 2px 16px #b0c4de;
  padding: 32px 28px 28px 28px;
}
.info-row {
  display: flex;
  align-items: center;
  gap: 18px;
  margin-bottom: 18px;
}
.info-label {
  min-width: 90px;
  font-weight: bold;
  color: #234a6f;
  font-size: 1.08em;
}
.info-value {
  font-size: 1.08em;
  color: #333;
  word-break: break-all;
}
.spirt_question_title {
  font-weight: bold;
  color: #1976d2;
  margin: 18px 0 6px 0;
  font-size: 1.08em;
}
.save-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: linear-gradient(90deg, #ffe082 0%, #ffd54f 100%);
  color: #795548;
  border: none;
  border-radius: 24px;
  font-size: 1.1rem;
  font-weight: bold;
  box-shadow: 0 2px 10px rgba(255,193,7,0.10);
  cursor: pointer;
  padding: 12px 38px 12px 28px;
  margin: 24px 0 0 auto;
  transition: background 0.2s, color 0.2s, box-shadow 0.2s;
  position: relative;
}
.save-btn:hover {
  background: linear-gradient(90deg, #ffd54f 0%, #ffe082 100%);
  color: #4e342e;
  box-shadow: 0 4px 18px rgba(255,193,7,0.18);
}
.save-btn .material-icons {
  font-size: 1.3em;
  margin-right: 4px;
}
.save-btn.complete {
  background: linear-gradient(90deg, #ff5252 0%, #ff1744 100%);
  color: #fff;
  font-weight: bold;
  font-size: 1.15rem;
  box-shadow: 0 2px 12px rgba(255,82,82,0.18);
  border: none;
  border-radius: 24px;
  padding: 12px 44px 14px 32px;
  margin-left: 18px;
  margin-top: 24px;
  transition: background 0.2s, color 0.2s, box-shadow 0.2s;
}
.save-btn.complete:hover {
  background: linear-gradient(90deg, #ff1744 0%, #ff5252 100%);
  color: #fff;
  box-shadow: 0 4px 24px rgba(255,82,82,0.28);
}
@media (max-width: 700px) {
  .admin-card {
    max-width: 98vw;
    padding: 16px 2vw 24px 2vw;
  }
  .info-row {
    flex-direction: column;
    align-items: flex-start;
    gap: 4px;
  }
  .save-btn {
    width: 80%;
    font-size: 1rem;
  }

  .save-btn.complete {

    margin-left: 0;
  }
}
</style>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="admin-exorcism-button-area" style="margin-bottom: 240px;">

   <div class="admin-title">

        <?php if(!$read){?>
		    <?php echo "相談・ヒーリング スケジュール作成・編集"; ?>
        <?php }else{ ?>
            <?php echo "相談・ヒーリング スケジュール確認"; ?>
        <?php } ?>
	</div>

    <div class="">
       
        <div style="margin-bottom: 20px;text-align: right;display: flex;justify-content: center;">


            <?php if($new_date != "" || $return_calendar == true){?>

                <?php 
                
                    $url_year = "";
                    $url_month = "";
                    
                    if($new_date)
                    {
                        $specifiedDate = new DateTime($new_date);
            

                        $url_year =  $specifiedDate->format('Y');
                        $url_month =  $specifiedDate->format('n');
                    }
                    else if($edit_no != ""){
                        $specifiedDate = new DateTime(get_field('acf_spirit_schedule_date' ,$edit_no));
            
                        $url_year =  $specifiedDate->format('Y');
                        $url_month =  $specifiedDate->format('n');
                    }
                
                ?>
                <?php if(!$read && $edit_no != ""){?>

                    <form action="<?php echo getURLSetSlag("admin-spirit-explanation-schedule-edit"); ?>" method="post" onSubmit="">

                        <input type="hidden" name="edit_schedule" value="<?php echo $edit_no; ?>">
                        <input type="hidden" name="read" value="read">
                        <input type="hidden" name="return_calendar" value="1">
                        <input type="submit" value="確認画面へ" class="admin-main-btn" style="margin-right: 15px;">

                    </form>

                <?php } ?>

                <button class="admin-main-btn gray" type="button" style="max-width:150px;margin-right:15px;" onclick="window.location.href='<?php echo getURLSetSlag('admin-spirit-explanation-schedule-list'); ?><?php if(isset($url_year) && isset($url_month)){ echo '?calendar_year=' . $url_year . '&calendar_month=' . $url_month; } ?>'">一覧に戻る</button>
            <?php }else{ ?>

                <?php if(!$read  && $edit_no != ""){?>

                    <form action="<?php echo getURLSetSlag("admin-spirit-explanation-schedule-edit"); ?>" method="post" onSubmit="">

                        <input type="hidden" name="edit_schedule" value="<?php echo $edit_no; ?>">
                         <input type="hidden" name="read" value="read">
                        <input type="submit" value="確認画面へ" class="admin-main-btn" style="margin-right: 15px;">

                    </form>
                <?php } ?>

                <button class="admin-main-btn gray" type="button" style="max-width:150px;margin-right:15px;" onclick="window.location.href='<?php echo getURLSetSlag('admin-spirit-explanation-schedule-list'); ?><?php if(isset($url_year) && isset($url_month)){ echo '?calendar_year=' . $url_year . '&calendar_month=' . $url_month; } ?>'">一覧に戻る</button>

            <?php } ?>
        </div>
    </div>


    <form action="<?php echo getURLSetSlag("admin-spirit-explanation-schedule-edit"); ?>" method="post" onSubmit="return checkSubmit(this);">


        <?php if(!$read){?>
            <input type="hidden" name="save" value="">
            <input type="hidden" name="unixtime" value="<?php echo time();?>">
            <input type="hidden" name="schedule_slot" value="1">

        <?php } ?>

         <?php if($edit_no != ""){ ?>

             <input type="hidden" name="edit_schedule" value="<?php echo $edit_no;?>">

        <?php } ?>

        <?php if($new_date != "" || $return_calendar == true){ ?>

             <input type="hidden" name="return_calendar" value="1">

        <?php } ?>


        <div class="user-table-flex">

            <?php if($read){?>

			    <div class="user-table-item"  style="width: 135px;font-weight: 600;">依頼種類</div>
                <?php echo $spiritType[ get_field('acf_spirit_schedule_type' ,$edit_no) ]["title"];?>


            <?php }else{ ?>
                <div class="user-table-item"  style="width: 135px;font-weight: 600;">依頼種類<font color="red">(*必須)</font></div>
            

                <div class="select-wrapper">
                    <select name="schedule_type" id="schedule_type" style="width:100%;min-width:0;max-width:100%;" required>
                        <?php 
                            $type_select = "";
                            if($edit_no != ""){
                                $type_select = get_field('acf_spirit_schedule_type' ,$edit_no);
                            }
                        ?>
                        <option value="">未選択</option>
                        <?php foreach ($spiritTypeSort[SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN] as $spirit_key => $spirit_value) {
                            if($spirit_value["group"] != SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){ continue; }
                        ?>
                            <option value="<?php echo $spirit_value["ID"]; ?>" <?php if($type_select == $spirit_value["ID"]) { echo "selected"; }?>><?php echo $spirit_value["title"]; ?>(<?php echo $spirit_value["time"]; ?>分)</option>
                        <?php } ?>
                    </select>
                </div>
			    <input type="hidden" name="schedule_name" value="">

            <?php } ?>

		</div>

        <div class="user-table-flex">

            <?php if($read){?>

			    <div class="user-table-item"  style="width: 135px;font-weight: 600;">担当者</div>
                <?php 
                    $charge_id = get_field('acf_spirit_schedule_charge' ,$edit_no);
                    if (isset($users_data[$charge_id])) {
                        echo $users_data[$charge_id]['name'];
                    } else {
                        echo $charge_id;
                    }
                ?>


            <?php }else{ ?>
                <div class="user-table-item"  style="width: 135px;font-weight: 600;">担当者<font color="red">(*必須)</font></div>
            

                <select name="schedule_charge" id="schedule_charge" style="" required disabled>
                    <?php 
                        $schedule_charge = "";
                        if($edit_no != ""){
                            $schedule_charge = get_field('acf_spirit_schedule_charge' ,$edit_no);
                        }
                    ?>
                    <option value="">依頼種類を選択してください</option>
                </select>

            <?php } ?>

		</div>
        
        <div class="user-table-flex">

            <?php if(!$read){?>

			    <div class="user-table-item"  style="width: 135px;font-weight: 600;">実施日<font color="red">(*必須)</font></div>
			    <input type="date" name="schedule_date" value="<?php if($edit_no != ""){ echo get_field('acf_spirit_schedule_date' ,$edit_no);}else{ echo $new_date; } ?>" style=""  required>

            <?php }else{ ?>
                <div class="user-table-item"  style="width: 135px;font-weight: 600;">実施日</div>
                <?php echo get_field('acf_spirit_schedule_date' ,$edit_no);?>
             <?php } ?>
		</div>

        <div class="user-table-flex">
			<div class="user-table-item"  style="width: 135px;font-weight: 600;">実施時間 <?php if(!$read){?><font color="red">(*必須)</font><?php }?></div>
            <?php 
                $time_select = "";
                
                if($edit_no != ""){
                    $time_select = get_field('acf_spirit_schedule_time_minutes' ,$edit_no);
                }
            ?>
            <?php if(!$read){?>

			    <input type="time" id="timeInput" name="schedule_clock" value="<?php if($edit_no != ""){ echo get_field('acf_spirit_schedule_time_minutes' ,$edit_no);}else{ echo $new_date; } ?>" style=""  required>

             <?php }else{ ?>

                <?php if($time_select == ""){ ?>
                    設定なし
                <?php }else{ ?>
                    <?php echo $time_select;?>時から
                <?php } ?>

             <?php } ?>
		</div>


        <? if(current_user_can('administrator')){?>
            <div class="user-table-flex">
                <div class="user-table-item"  style="width: 135px;font-weight: 600;">値段</div>
                <div>
                    <?php if(!$read){?>
                        <div>
                            <input type="number" name="schedule_price" value="<?php if($edit_no != ""){ echo get_field('acf_spirit_schedule_price' ,$edit_no);}else{ echo "0";} ?>" style="width: 100px;text-align: right;">円
                        </div>

                    <?php }else{ ?>

                        <?php echo get_field('acf_spirit_schedule_price' ,$edit_no);?>円

                    <?php } ?>
                </div>
            </div>
        <? }else{ ?>
            <?php if(!$read){?>
                <input type="hidden" name="schedule_price" value="<?php if($edit_no != ""){ echo get_field('acf_spirit_schedule_price' ,$edit_no);}else{ echo "0";} ?>">
            <? }?>
        <? }?>
        <div class="user-table-flex">
			<div class="user-table-item"  style="width: 135px;font-weight: 600;">申込に表示</div>

            <?php 
                $disp_radip = 0;
                
                if($edit_no != ""){
                    $disp_radip = get_field('acf_spirit_schedule_disp' ,$edit_no);
                }
            ?>
            <?php if(!$read){?>

			    <label style="display:block;">
                    <input type="radio" name="schedule_disp" id="" value="0" <?php if($disp_radip == 0 ){ echo "checked";  } ?> > <b>表示しない</b>
                </label>
                <label style="display:block;">
                    <input type="radio" name="schedule_disp" id="" value="1" <?php if( $disp_radip == 1){ echo "checked";  }  ?> > <b>表示する</b>
                </label>

             <?php }else{ ?>

                 <?php if($disp_radip == 0 ){ echo "表示しない";  } ?> 
                 <?php if( $disp_radip == 1){ echo "表示する";  }  ?>

             <?php } ?>
		</div>

        <div class="user-table-flex">
			<div class="user-table-item"  style="width: 135px;font-weight: 600;">キャンセル可能時間</div>

            <?php if(!$read){?>

                <div>
                    <div>
			           <input type="number" name="schedule_cancel_time" style="width: 40px;" value="<?php if($edit_no != ""){ echo get_field('acf_spirit_schedule_cancel_time' ,$edit_no);} ?>" style="">時間前
                    </div>
                    <div style="font-size: 12px;margin-top: 5px;font-weight: 600;color: black;">未入力の場合は時間設定がある場合は１時間前、設定がない場合は１日前になります。</div>
                </div>

            <?php }else{ ?>

                <?php echo get_field('acf_spirit_schedule_end' ,$edit_no);?>

            <?php } ?>
		</div>


        <div class="user-table-flex">
			<div class="user-table-item"  style="width: 135px;font-weight: 600;">締め切り日時</div>

            <?php if(!$read){?>

                <div>
                    <div>
			           <input type="date" name="schedule_end" value="<?php if($edit_no != ""){ echo get_field('acf_spirit_schedule_end' ,$edit_no);} ?>" style="">
                    </div>
                    <div style="font-size: 12px;margin-top: 5px;font-weight: 600;color: black;">未入力の場合は1日前に締め切りになります。</div>
                </div>

            <?php }else{ ?>

                <?php echo get_field('acf_spirit_schedule_end' ,$edit_no);?>

            <?php } ?>
		</div>

        <div class="user-table-flex">
			<div class="user-table-item"  style="width: 135px;font-weight: 600;">表示開始日時</div>
            <?php if(!$read){?>

                <div>
                    <div>
			            <input type="date" name="schedule_start" value="<?php if($edit_no != ""){ echo get_field('acf_spirit_schedule_start' ,$edit_no);} ?>" style="">
                    </div>
                    <div style="font-size: 12px;margin-top: 5px;font-weight: 600;color: black;">未入力の場合は「申込に表示」のON・OFFですぐに対応になります。<br>「申込に表示」OFF場合は日程が来ても表示されません</div>
                </div>

             <?php }else{ ?>

                <?php echo get_field('acf_spirit_schedule_start' ,$edit_no);?>

            <?php } ?>

		</div>

        

        <?php if(!$read){?>
            <div style="max-width: 500px;margin-left: auto;margin-right: auto;margin-top: 30px;" >
			    <button type="submit" class="admin-main-btn">保存する</button>
		    </div>
        <?php }else{ ?>

            <div style="max-width: 500px;margin-left: auto;margin-right: auto;margin-top: 30px;" >
			    <button type="submit" class="admin-main-btn gray" style="background-color: darkgray;cursor: pointer;">編集する</button>
		    </div>

        <?php } ?>
    </form>


    <?php if($edit_no != "" && count($set_spirit_sheet["予約者"]) == 0){ ?>

        <?php if($new_date != "" || $return_calendar == true){ ?>
        
            <form action="<?php echo getURLSetSlag("admin-spirit-explanation-schedule-calendar"); ?>" method="post" style="text-align: center;" onSubmit="return delete_check()">

        <?php }else{ ?>

            <form action="<?php echo getURLSetSlag("admin-spirit-explanation-schedule-list"); ?>" method="post" style="text-align: center;" onSubmit="return delete_check()">

        <?php } ?>
            <input type="hidden" name="schedule_delete" value="<?php echo $edit_no;?>">
            <button type="submit" class="admin-main-btn red" style="margin-top: 50px;">削除する</button>
        </form>
    <?php }else if($edit_no != "" && count($set_spirit_sheet["予約者"]) > 0){ ?>

        <div style="text-align: center;margin-top: 60px;font-weight: 600;color: red;">
            予約者をキャンセルしないと、スケジュールの編集・削除はできません
        </div>

    <?php } ?>

    

    <?php if( ($read || current_user_can('administrator') ) && $edit_no != ""){?>

        <div class="admin-card">
            <div class="admin-title" style="margin-top: 0;">
                <?php echo "申込"; ?>
            </div>

            <?php 
            if(count($set_spirit_sheet["予約者"]) == 0)
            { ?>
                <div style="font-size: 24px;color: black;">
                    現在、申込者はいません
                </div>
            <?php }else{ ?>
                <div class="info-row">
                    <div class="info-label">予約者</div>
                    <div class="info-value"><?php echo $set_spirit_sheet["予約者データ"][0]["名前"]; ?>(<?php echo $set_spirit_sheet["予約者データ"][0]["ナマエ"]; ?>)</div>
                </div>
                <div class="info-row">
                    <div class="info-label">電話番号</div>
                    <div class="info-value"><?php echo $set_spirit_sheet["予約者データ"][0]["電話番号"]; ?></div>
                </div>
                <?php if(current_user_can('administrator')){?>
                <div class="info-row">
                    <div class="info-label">メールアドレス</div>
                    <div class="info-value"><?php echo $set_spirit_sheet["予約者データ"][0]["メール"]; ?></div>
                </div>
                <?php } ?>
                <form action="<?php echo getURLSetSlag("admin-spirit-explanation-schedule-edit"); ?>" method="post" style="margin-left: 3px;">
                    <input type="hidden" name="edit_schedule" value="<?php echo $edit_no;?>">
                    <input type="hidden" name="save_edit_question" value="">

                    <?php 
                        //実行日を取得
                        $exe_schedule_date = get_field('acf_purespirit_execution_date' ,$set_spirit_sheet["予約者データ"][0]["シートID"]);

                        //状況ステータスを取得
                        $admin_status = get_field('acf_purespirit_status',$set_spirit_sheet["予約者データ"][0]["シートID"]);

                        $edit_enable = true;

                        //会員ステータスが完了、もしくは確認待ちの時は編集できない
                        if($admin_status == SpiritUserClass::MEMBER_STATUS_COMPLETE || $admin_status == SpiritUserClass::MEMBER_STATUS_CONFIRMATION_INFOMATION){
                            $edit_enable = false;
                        }


                    ?>
                    <div class="spirt_question_title">
                        <div>実行完了日  <?php if($edit_enable){ ?> (入力可能)<?php } ?></div>
                    </div>
                    <div>
                        <?php if($edit_enable){ ?>
                            <input type="date" name="execution_schedule_date" value="<?php echo $exe_schedule_date; ?>" style="">
                        <?php }else{ ?>
                            <?php echo $exe_schedule_date; ?>
                        <?php } ?>
                    </div>
                    <?php 
                        $type = get_field('acf_acf_purespirit_type',$set_spirit_sheet["予約者データ"][0]["シートID"]);
                        $spiritQuestionArray = $spirit_sheet_data->getSpiritQuestion($type);
                        $anser = $spirit_sheet_data->getSpiritSheetAnswer( $set_spirit_sheet["予約者データ"][0]["シートID"] );
                        if(isset($spiritQuestionArray[$type])){
                            foreach ($spiritQuestionArray[$type] as $key => $value) {
                                if($value["text"] != true || $value["type"] == 10) continue;
                    ?>
                                <div class="spirt_question_title">
                                    <?php echo $value["text"];?>

                                    <?php if($value ["admin_disp"] == true && current_user_can('administrator')){?> 
                                        <?php if($edit_enable){ ?>(入力可能)<?php } ?>
                                    <?php }else{ ?>

                                        <?php if($value ["counselor"] == true){?>
                                            <?php if($edit_enable){ ?>(入力可能)<?php } ?>
                                        <?php }else{ ?>
                                            <?php if($edit_enable){ ?>(相談者入力)<?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                <?php 
                                    $disp_value = "";
                                    if( isset(  $anser[$value["ID"]] ) ){
                                        if($value["type"] == SpiritSheetClass::QUESTION_TYPE_IMG_DATA){
                                            $disp_value = get_field("acf_questionqnser_img_url" ,  $anser[$value["ID"]]);
                                        } else {
                                            $disp_value = get_field("acf_questionqnser_text" ,  $anser[$value["ID"]]);
                                        }
                                    }

                                    if($value ["admin_disp"] == true && current_user_can('administrator') && $edit_enable)
                                    {
                                        $disp_questiont_class->dispQuestionByType( $userClass , $value , $anser , $disp_value  , true);
                                    }
                                    else if($value ["counselor"] == true && $edit_enable)
                                    {
                                        $disp_questiont_class->dispQuestionByType( $userClass , $value , $anser , $disp_value  , true);
                                    }
                                    else
                                    {
                                        $disp_questiont_class->dispQuestionByType( $userClass , $value , $anser , $disp_value  , false);
                                    }
                                ?>
                    <?php 
                            }
                        }
                    ?>
                    <?php if($edit_enable){ ?>
                        <button type="submit" class="save-btn"><span class="material-icons">save</span>報告保存</button>
                    <?php } ?>

                    <?php if($edit_enable){ ?>
                        <button type="submit" class="save-btn complete" id="complete-btn"><span class="material-icons">save</span>報告完了</button>
                    <?php } ?>


                </form>


                

            <?php } ?>
        </div>

    <?php } ?>

</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#sort-table').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/ja.json"
            },
            "pageLength": 20,
            "lengthMenu": [20, 50, 100],
            "order": []  // カンマを削除
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

<script type="text/javascript">
jQuery(document).ready(function($) {
    // 担当者データの準備
    var typeUsers = <?php echo json_encode($type_user); ?>;
    var usersData = <?php echo json_encode($users_data); ?>;
    var spiritTypeData = <?php echo json_encode($spirit_type_data); ?>;
    var spiritTypePrice = <?php echo json_encode($spiritTypePrice); ?>;
    
    // 依頼種類が変更された時の処理
    $('#schedule_type').change(function() {
        var selectedType = $(this).val();
        var chargeSelect = $('#schedule_charge');
        
        // schedule_nameの更新
        if (selectedType && spiritTypeData[selectedType]) {
            $('input[name="schedule_name"]').val(spiritTypeData[selectedType].title);
        } else {
            $('input[name="schedule_name"]').val('');
        }

        // 価格の更新
        if (selectedType && spiritTypePrice[selectedType]) {
            $('input[name="schedule_price"]').val(spiritTypePrice[selectedType]);
        } else {
            $('input[name="schedule_price"]').val(0);
        }
        
        chargeSelect.empty(); // セレクトボックスをクリア
        
        if (selectedType === '') {
            chargeSelect.prop('disabled', true);
            chargeSelect.append('<option value="">依頼種類を選択してください</option>');
            return;
        }

        chargeSelect.prop('disabled', false);
        chargeSelect.append('<option value="">担当者を選択してください</option>');

        if (typeUsers[selectedType]) {
            typeUsers[selectedType].forEach(function(userId) {
                if (usersData[userId]) {
                    chargeSelect.append(
                        $('<option></option>')
                            .val(userId)
                            .text(usersData[userId].name)
                    );
                }
            });
        }

        // 編集時の選択状態を復元
        <?php if($edit_no != "" && !empty($schedule_charge)): ?>
        chargeSelect.val('<?php echo $schedule_charge; ?>');
        <?php endif; ?>
    });

    // 編集時の初期表示
    <?php if($edit_no != "" && !empty($type_select)): ?>
    $('#schedule_type').trigger('change');
    <?php endif; ?>
});
</script>

<script>
function checkSubmit(form) {
    // 保存ボタンの場合のみ確認ダイアログを表示
    if (form.querySelector('button[type="submit"]').textContent === '保存する') {
        return confirm('保存しますか？');
    }
    return true;
}
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const timeInput = document.getElementById("timeInput");

    // もし value が空なら、現在時刻の「分00」を設定
    if (!timeInput.value) {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const defaultTime = `${hours}:00`;
        timeInput.value = defaultTime;
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var completeBtn = document.getElementById('complete-btn');
  if (completeBtn) {
    completeBtn.addEventListener('click', function(e) {
      var form = completeBtn.closest('form');
      var exeDate = form ? form.querySelector('input[name="execution_schedule_date"]') : null;
      if (exeDate && !exeDate.value) {
        alert('実行完了日が入力されていません');
        e.preventDefault();
        return false;
      }
      if (!window.confirm('報告を完了すると変更ができなくなりますが、宜しいですか？')) {
        e.preventDefault();
        return false;
      }
      if (form && !form.querySelector('input[name="complete_schedule"]')) {
        var hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'complete_schedule';
        hidden.value = '1';
        form.appendChild(hidden);
      }
    });
  }
});
</script>
