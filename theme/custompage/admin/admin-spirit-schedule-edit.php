<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritPlaceClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");


    $spiritPlaceData = new SpiritPlsceClass(); //場所データ
    $spiritTypeData = new SpiritTypeClass(); //浄霊タイプデータ
    $spiritScheduleData = new SpiritScheduleClass(); //スケジュールデータ
    $userClass = new SpiritUserClass(); //ユーザー管理

    //浄霊タイプ
    $spiritType = $spiritTypeData->getSpiritType();
    //場所取得
    $place_list = $spiritPlaceData->getSpritPlaceList();

    $edit_no = "";
    $read = false;

    //編集番号
    if(isset($_POST["edit_schedule"]))
    {
        $edit_no = $_POST["edit_schedule"];
    }


    //読み込み
    if(isset($_POST["read"]))
    {
        $read = true;
    }


    //保存
    if(isset($_POST["save"]))
    {


        if(!isset($_POST["edit_schedule"]))
        {
            $edit_no = $spiritScheduleData->newSpritSchedule($_POST);//新しく保存

            if($edit_no == "")
            {
                echo "<script>alert('日程の作成に失敗しました。');</script>";
            }
            else{
                echo "<script>alert('日程の作成に成功しました。');</script>";
            }
        }
        else{
             $spiritScheduleData->saveSpritSchedule( $edit_no , $_POST);
             echo "<script>alert('日程の作成に成功しました。');</script>";

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


    //var_dump($set_spirit_sheet);

    //価格配列を取得
    $spiritTypePrice = $spiritTypeData->getSpiritTypePrice();
   // var_dump($spiritType);
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<div class="admin-exorcism-button-area" style="margin-bottom: 240px;">

   <div class="admin-title">

        <?php if(!$read){?>
		    <?php echo "日程確定スケジュール作成・編集"; ?>
        <?php }else{ ?>
            <?php echo "日程確定スケジュール確認"; ?>
        <?php } ?>
	</div>

    <div class="">
       
        <div style="margin-bottom: 20px;text-align: right;display: flex;justify-content: flex-end;">


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

                    <form action="<?php echo getURLSetSlag("admin-spirit-schedule-edit"); ?>" method="post" onSubmit="">

                        <input type="hidden" name="edit_schedule" value="<?php echo $edit_no; ?>">
                        <input type="hidden" name="read" value="read">
                        <input type="hidden" name="return_calendar" value="1">
                        <input type="submit" value="確認画面へ" style="width: 250px;font-size: 18px;font-weight: bold;height: 45px;margin-right: 15px;border-radius: 8px;background-color: beige;">

                    </form>

                <?php } ?>

                <button class="admin-preview-button" type="button"  style="background-color: lightgray;cursor: pointer;"  onclick="window.location.href='<?php echo getURLSetSlag("admin-spirit-schedule-calendar"); ?>?calendar_year=<?php echo $url_year;?>&calendar_month=<?php echo $url_month;?>'">一覧に戻る</button>
            <?php }else{ ?>

                <?php if(!$read  && $edit_no != ""){?>

                    <form action="<?php echo getURLSetSlag("admin-spirit-schedule-edit"); ?>" method="post" onSubmit="">

                        <input type="hidden" name="edit_schedule" value="<?php echo $edit_no; ?>">
                         <input type="hidden" name="read" value="read">
                        <input type="submit" value="確認画面へ" style="width: 250px;font-size: 18px;font-weight: bold;height: 45px;margin-right: 15px;border-radius: 8px;background-color: beige;">

                    </form>
                <?php } ?>

                <button class="admin-preview-button" type="button"  style="background-color: lightgray;cursor: pointer;"  onclick="window.location.href='<?php echo getURLSetSlag("admin-spirit-schedule-list"); ?>'">一覧に戻る</button>

            <?php } ?>
        </div>
    </div>


    <form action="<?php echo getURLSetSlag("admin-spirit-schedule-edit"); ?>" method="post" onSubmit="">


        <?php if(!$read){?>
            <input type="hidden" name="save" value="">
            <input type="hidden" name="unixtime" value="<?php echo time();?>">
        <?php } ?>

         <?php if($edit_no != ""){ ?>

             <input type="hidden" name="edit_schedule" value="<?php echo $edit_no;?>">

        <?php } ?>

        <?php if($new_date != "" || $return_calendar == true){ ?>

             <input type="hidden" name="return_calendar" value="1">

        <?php } ?>


        <div class="user-table-flex">

            <?php if(!$read){?>

			    <div class="user-table-item"  style="width: 135px;">表示名<font color="red">(*必須)</font></div>
			    <input type="text" name="schedule_name" value="<?php if($edit_no != ""){ echo get_field('acf_spirit_schedule_disp_title' ,$edit_no);} ?>" style="width: 750px;" required>

            <?php }else{ ?>
                 <div class="user-table-item"  style="width: 135px;">表示名</div>
                 <?php echo get_field('acf_spirit_schedule_disp_title' ,$edit_no);?>
            <?php } ?>

		</div>

        <div class="user-table-flex">

            <?php if($read){?>

                <?php  $spiritTypeNum = $spiritTypeData->getSpiritTypeKeyTypeNum();?>

			    <div class="user-table-item"  style="width: 135px;">依頼種類</div>
                <?php if(isset($spiritTypeNum[ get_field('acf_spirit_schedule_type' ,$edit_no) ]["title"])){ echo $spiritTypeNum[ get_field('acf_spirit_schedule_type' ,$edit_no) ]["title"];}else{ echo "未選択";}?>


            <?php }else{ ?>
                <div class="user-table-item"  style="width: 135px;">依頼種類<font color="red">(*必須)</font></div>
            

                <select name="schedule_type" style="" onchange="updatePrice(this.value)" required>

                    <?php 
                        $type_select = "";
                
                        if($edit_no != ""){
                            $type_select = get_field('acf_spirit_schedule_type' ,$edit_no);
                        }
                    ?>

                    <option value="" >未選択</option>

                    <?php  foreach ($spiritType as $spirit_key => $spirit_value) {?>


                        <?php if( $spirit_key != SpiritTypeClass::SPIRIT_TYPE_NAME_DAY){ continue; } ?>

                        <?php  foreach ($spirit_value as $key => $value) {?>

                            <?php //if( !$value["placeOn"]){ continue; } ?>

                            <option value="<?php echo $value["ID"]; ?>" <?php if( $type_select == $value["ID"]) { echo "selected"; }?>><?php echo $value["title"]; ?></option>

                         <?php } ?>

                    <?php } ?>
               
                </select>

            <?php } ?>

		</div>

        <div class="user-table-flex">
			<div class="user-table-item"  style="width: 135px;">募集人数</div>
            <div>
                <?php if(!$read){?>
                    <div>
			            <input type="number" name="schedule_slot" value="<?php if($edit_no != ""){ echo get_field('acf_spirit_schedule_slots' ,$edit_no);}else{ echo "1";} ?>" style="width: 50px;">人
                    </div>
                    <div style="font-size: 12px;margin-top: 5px;font-weight: 600;color: black;">未入力、もしくは0の場合は枠数の制限はなくなります</div>

                <?php }else{ ?>

                    <?php if(get_field('acf_spirit_schedule_slots' ,$edit_no) == "" || get_field('acf_spirit_schedule_slots' ,$edit_no) == 0){?>
                        無制限
                    <?php }else{ ?>
                        <?php echo get_field('acf_spirit_schedule_slots' ,$edit_no);?>人
                    <?php } ?>

                    　(申込人数:<?php echo $set_spirit_sheet["予約人数"];?>人)

                <?php } ?>
            </div>
		</div>

        <div class="user-table-flex">

            <?php if(!$read){?>

			    <div class="user-table-item"  style="width: 135px;">実施日<font color="red">(*必須)</font></div>
			    <input type="date" name="schedule_date" value="<?php if($edit_no != ""){ echo get_field('acf_spirit_schedule_date' ,$edit_no);}else{ echo $new_date; } ?>" style=""  required>

            <?php }else{ ?>
                <div class="user-table-item"  style="width: 135px;">実施日</div>
                <?php echo get_field('acf_spirit_schedule_date' ,$edit_no);?>
             <?php } ?>
		</div>

        <div class="user-table-flex">
			<div class="user-table-item"  style="width: 135px;">実施時間</div>
            <?php 
                $time_select = "";
                
                if($edit_no != ""){
                    $time_select = get_field('acf_spirit_schedule_time' ,$edit_no);
                }
            ?>
            <?php if(!$read){?>

			    <select name="schedule_time" style="">
                    <option value="" >未選択</option>

                    <?php  for ($i=0;$i<=23;$i++) {?>

                        <option value="<?php echo $i;?>" <?php if( $time_select == $i) { echo "selected"; }?>><?php echo  $i; ?>時から</option>

                     <?php } ?>
                </select>

             <?php }else{ ?>

                <?php if($time_select == ""){ ?>
                    設定なし
                <?php }else{ ?>
                    <?php echo $time_select;?>時から
                <?php } ?>

             <?php } ?>
		</div>

        <div class="user-table-flex">
			<div class="user-table-item"  style="width: 135px;">値段</div>
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

        <div class="user-table-flex">
			<div class="user-table-item"  style="width: 135px;">申込に表示</div>

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
			<div class="user-table-item"  style="width: 135px;">支払い方法</div>

            <?php 
                $select_payment_type_array = array();

                if($edit_no != ""){
                    $select_payment_type_array = get_field('acf_spirit_schedule_payment_type' ,$edit_no);

                    if($select_payment_type_array == ""){
                        $select_payment_type_array = array();
                    }
                }
            ?>
            <?php if(!$read){?>

                 <div>
                    <?php foreach($userClass->payment_type_field as $key => $value){ ?>
                        <?php if($key == SpiritUserClass::PAYMENT_TYPE_NOT_SET){ continue; } ?>
                        <label style="display:block;">
                            <input type="checkbox" name="schedule_payment_type[]" id="" value="<?php echo $key; ?>" <?php if(in_array($key, $select_payment_type_array)){ echo "checked";  } ?> > <?php echo $value; ?>
                        </label>
                    <?php } ?>
                </div>   
             <?php }else{ ?>

                <?php foreach($userClass->payment_type_field as $key => $value){ ?>
                    <?php if($key == SpiritUserClass::PAYMENT_TYPE_NOT_SET){ continue; } ?>
                    <?php if(in_array($key, $select_payment_type_array)){ echo $value; } ?>
                <?php } ?>

             <?php } ?>
		</div>










        <div class="user-table-flex">
			<div class="user-table-item"  style="width: 135px;">締め切り日時</div>

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
			<div class="user-table-item"  style="width: 135px;">表示開始日時</div>
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

        <div class="user-table-flex">
			<div class="user-table-item"  style="width: 135px;">施術場所<font color="red"></font></div>

            <?php 
                $place_select = "";
                
                if($edit_no != ""){
                    $place_select = get_field('acf_spirit_schedule_place' ,$edit_no);
                }
            ?>

            <?php if(!$read){?>

                <select name="schedule_place" style="">

                    <option value="" >指定なし</option>

                    <?php  foreach ($place_list as $key => $value) {?>

                        <option value="<?php echo $key; ?>" <?php if( $place_select == $key) { echo "selected"; }?>><?php echo get_field('acf_spirit_palce_name' ,$key);?>　 <?php echo get_field('acf_spirit_palce_address' ,$key);?></option>

                    <?php } ?>
               
                </select>
                （<a href="<?php echo getURLSetSlag("admin-spirit-place-edit"); ?>" target="_blank">場所の作成</a>）

            <?php }else{ ?>

                <?php if($place_select != ""){?>
                    
                    <?php if( isset( $place_list[$place_select] )){?>
                    
                      <?php echo get_field('acf_spirit_palce_name' ,$place_select);?>　 <?php echo get_field('acf_spirit_palce_address' ,$place_select);?>

                    <?php }else{ ?>

                      <?php echo get_field('acf_spirit_palce_name' ,$edit_no);?>　 <?php echo get_field('acf_spirit_palce_address' ,$edit_no);?>

                    <?php } ?>

                 <?php } ?>

            <?php } ?>
		</div>

        <?php if(!$read){?>
            <div style="max-width: 500px;margin-left: auto;margin-right: auto;margin-top: 30px;" >
			    <button  type="submit" class="admin-remote-make-arami-button" style="width: 100%;font-size: 18px;" >保存する</button>
		    </div>
        <?php }else{ ?>

            <div style="max-width: 500px;margin-left: auto;margin-right: auto;margin-top: 30px;" >
			    <button  type="submit" class="admin-remote-make-arami-button" style="width: 100%;font-size: 18px;background-color: darkgray;cursor: pointer;" >編集する</button>
		    </div>

        <?php } ?>
    </form>

    <?php if($edit_no != "" && count($set_spirit_sheet["予約者"]) == 0){ echo count($set_spirit_sheet["予約者"]); //予約者が必要?>
        <?php if($new_date != "" || $return_calendar == true){ ?>
        
            <form action="<?php echo getURLSetSlag("admin-spirit-schedule-calendar"); ?>" method="post" style="text-align: center;" onSubmit="return delete_check()">

        <?php }else{ ?>

            <form action="<?php echo getURLSetSlag("admin-spirit-schedule-list"); ?>" method="post" style="text-align: center;" onSubmit="return delete_check()">

        <?php } ?>
            <input type="hidden" name="schedule_delete" value="<?php echo $edit_no;?>">
            <button type="submit"  class="admin-remote-make-arami-button" style="width: 100%;font-size: 18px;background-color: red;margin-top: 50px;width: 500px;cursor: pointer;">削除する</button>
        </form>

        <?php }else if($edit_no != "" && count($set_spirit_sheet["予約者"]) > 0){ ?>

            <div style="text-align: center;margin-top: 60px;font-weight: 600;color: red;">
                予約者をキャンセルしないと、スケジュールの削除はできません
            </div>

        <?php } ?>
    <?php if($read || (isset($set_spirit_sheet["予約者"]) && count($set_spirit_sheet["予約者"]) > 0)  ){?>

        <?php //var_dump($set_spirit_sheet);?>

        <div class="admin-title" style="margin-top: 100px;">
		    <?php echo "スケジュール申込者"; ?>
	    </div>

    <?php 
    
       



        if(count($set_spirit_sheet["予約者"]) == 0)
        {
        ?>

            <div style="font-size: 24px;color: black;">
                現在、申込者はいません
            </div>

        <?php
        }else{

        ?>

            <div class="admin-temporary-registration-check-table" style="">

                
                <table id="sort-table" class="display">

                    <thead>
                        <tr>
                            <th style="border: 1px solid black;text-align: center;">申込者</th>
                            <th style="border: 1px solid black;text-align: center;">申込日</th>
                            <th style="border: 1px solid black;text-align: center;">管理ステータス</th>
                            <th style="border: 1px solid black;text-align: center;">会員ステータス</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        <?php  
                        
                            foreach ($set_spirit_sheet["予約者データ"] as $key => $value) {
                            
                                $sheet_array = $userClass->getUserSpritApplicantSheet($value["ID"] , $value["シートID"]);

                                $sheet_data = $sheet_array[$value["シートID"]];

                                //var_dump($sheet_data);
                        
                        ?>

                            <tr <?php if($sheet_data["会員ステータス表示"] == "キャンセル" || $sheet_data["管理者ステータス表示"] == "キャンセル"){ ?> style="background-color: gray;" <?php }?>>
                           
                                <td style="border: 1px solid black;">
                                    <a href="<?php echo getURLSetSlag("admin-spirit-detail"); ?>?user_id=<?php echo $value["ID"]; ?>&sheet_name=<?php echo  $value["シートID"];?>" target="_blank">
                                        <?php echo $sheet_data["フル名前"];?>
                                    </a>
                                </td>

                                <td style="border: 1px solid black;">
                                    <?php echo $sheet_data["依頼日年月日"];?>
                                </td>

                                <td style="border: 1px solid black;">
                                     <?php echo $sheet_data["管理者ステータス表示"];?>
                                </td>

                                <td style="border: 1px solid black;">
                                     <?php echo $sheet_data["会員ステータス表示"];?>
                                </td>

                            </tr>


                        <?php } ?>
                    </tbody>


                </table>

		    </div>




        <?php
        }
       
    
    
    ?>

     


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
            "pageLength": 20,  // 1ページあたりの行数
            "lengthMenu": [20, 50, 100],  // 選択できる件数
            "order": [] // 初期ソートなし
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
    // 価格データをJavaScriptで保持
    const spiritTypePrice = <?php echo json_encode($spiritTypePrice); ?>;

    // 価格を更新する関数
    function updatePrice(selectedType) {
        const priceInput = document.querySelector('input[name="schedule_price"]');
        if (spiritTypePrice[selectedType]) {
            priceInput.value = spiritTypePrice[selectedType];
        } else {
            priceInput.value = 0;
        }
    }
</script>