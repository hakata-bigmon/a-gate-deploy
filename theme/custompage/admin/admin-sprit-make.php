<?php

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
	require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleCalendarClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");

    $users = get_users();
    $user_data = array();

    $sprit_type = $_GET["type"];

    $spiritSheet = new SpiritSheetClass(); //管理データ
    $spiritType = new SpiritTypeClass(); //管理データ
    $userData = new SpiritUserClass(); //管理データ
    $spiritScheduleCalendar = new SpiritScheduleCalendarClass(); //管理データ
    $spiritSchedule = new SpiritScheduleClass(); //管理データ

    $spiritTypeArray = $spiritType->getSpiritType();

    //在庫数を全て取得
    $stock_data = array();

    foreach($spiritTypeArray as $key => $value){
        foreach($value as $data_key => $data_value){

            $stock = $data_value["stock"];

            if($data_value["stock"] >= $data_value["max"] ){
                $stock = $data_value["max"];
            }
            $stock_data[$data_value["ID"]] = $stock;
        }
    }


    $day_group_id = "";

    $schedule_array = $spiritSchedule->getSpritScheduleAllList($sprit_type);

    //var_dump($schedule_array);

?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
.sprit-menu-btn-row {
  display: flex;
  gap: 24px;
  flex-wrap: wrap;
  justify-content: center;
  margin-bottom: 24px;
}
.sprit-menu-btn-box {
  flex: 1 1 180px;
  min-width: 180px;
  max-width: 220px;
  display: flex;
  justify-content: center;
}
.sprit-menu-btn {
  width: 100%;
  height: 56px;
  font-size: 1.1rem;
  font-weight: bold;
  border-radius: 14px;
  border: none;
  background: linear-gradient(90deg, #e3f2fd 0%, #bbdefb 100%);
  color: #1976d2;
  box-shadow: 0 2px 8px rgba(0,0,0,0.07);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  cursor: pointer;
  transition: box-shadow 0.2s, background 0.2s, color 0.2s, transform 0.15s;
  margin-bottom: 0;
}
.sprit-menu-btn:hover {
  background: #bbdefb;
  color: #0d47a1;
  box-shadow: 0 4px 16px rgba(33, 150, 243, 0.13);
  transform: translateY(-2px) scale(1.03);
}
.sprit-menu-btn .material-icons {
  font-size: 1.7rem;
}
@media (max-width: 700px) {
  .sprit-menu-btn-row {
    flex-direction: column;
    gap: 12px;
  }
  .sprit-menu-btn-box {
    max-width: 100%;
  }
}
</style>

<div class="admin-user-table-area">

	<div class="admin-title">
		<?php echo  $spiritType->getSpiritTypeName($sprit_type) ." 申込作成"; ?>
	</div>


    <div class="admin-spirit-sheets-button-flex">

        <?php $button_style = "style='background: #938989;color: black;'";?>
    
        <div class="sprit-menu-btn-row">
            
                <div class="sprit-menu-btn-box">
                    <button class="sprit-menu-btn" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-sprit-make'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT?>'" <?php if($sprit_type == SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT){echo $button_style;}?>>
                        <span class="material-icons">psychology</span>リモート依頼
                    </button>
                </div>
                <div class="sprit-menu-btn-box">
                    <button class="sprit-menu-btn" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-sprit-make'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL?>'" <?php if($sprit_type == SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL){echo $button_style;}?>>
                        <span class="material-icons">fact_check</span>鑑　　定
                    </button>
                </div>
                <div class="sprit-menu-btn-box">
                    <button class="sprit-menu-btn" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-sprit-make'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_DAY?>'" <?php if($sprit_type == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY){echo $button_style;}?>>
                        <span class="material-icons">event_available</span>日程確定作成
                    </button>
                </div>
                <div class="sprit-menu-btn-box">
                    <button class="sprit-menu-btn" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-sprit-make'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SALES?>'" <?php if($sprit_type == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){echo $button_style;}?>>
                        <span class="material-icons">shopping_cart</span>物　　販
                    </button>
                </div>
                <div class="sprit-menu-btn-box">
                    <button class="sprit-menu-btn" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-sprit-make'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN?>'" <?php if($sprit_type == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){echo $button_style;}?>>
                        <span class="material-icons">support_agent</span>遠隔・相談作成
                    </button>
                </div>
        </div>
    </div>



    <div class="admin-spirit-list-select-area">

        <?php if($sprit_type == SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT || $sprit_type== SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL || $sprit_type == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){?>
            <div class="admin-spirit-list-select">

                <div class="admin-spirit-list-select-title">申込タイプを選択してください</div>
                    
                <select class="category-select" name="sprit_type" data-applicant-id="123" style="font-size: 18px;margin-top: 5px;">

                    <option value="" style="text-align: center;">----------選択してください------------</option>

                    <?php foreach ($spiritTypeArray as $key => $value) { ?>

                        
                        <?php foreach ($value as $data_key => $data_value) { ?>
                            <?php if($data_value["group"] != $sprit_type){continue;}?>
                            <?php if($data_value["stock"] == 0){continue;} //在庫数が0は表示しない ?> 
                            <option value="<?php echo $data_value["ID"];?>"><?php echo $data_value["title"];?></option>
                        <?php } ?>
                    <?php } ?>
                </select>

            </div>
        <?php } ?>

        <div class="admin-spirit-list-select">

            <?php if($sprit_type == SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT || $sprit_type== SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL){?>

                <div class="admin-spirit-list-select-title">枠数を選択してください</div>
                <input type="number" name="slot-input" value="1" min="1" style="width: 50px;margin-top: 6px;height: 30px;text-align: center;" disabled>枠
            
            <?php }elseif($sprit_type == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){?>

                <div class="admin-spirit-list-select-title">個数を選択してください</div>
                <input type="number" name="sales-input" value="1" min="1" style="width: 50px;margin-top: 6px;height: 30px;text-align: center;" disabled>個
            <?php }else{?>

               

                <div style="margin-top: 100px;">
                    <?php $spiritScheduleCalendar->dispAllCalendarBase($schedule_array, true);?>
                </div>

            <?php } ?>
        </div>
    </div>


    <div id="schedule-display-area" style="font-size: 32px;margin-top: 30px;text-align: center;font-weight: 600;">
        

    </div>
    <?php if($sprit_type == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY || $sprit_type== SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){?>
        <input type="hidden" name="sprit_type" value="">
    <?php } ?>

    <div class="admin-remote-sprit-text" style="margin-top: 100px;">申込者を選択してください</div>

	
    <div class="admin-profile-edit-area" style="max-width: 1700px;margin-top: 15px;">


		 <?php if(count($users) >= 1){?>


            <div class="admin-temporary-registration-check-table" style="">

                
                <table id="sort-table" class="display">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th style="width: 160px;"></th>
                            
                            <th>名前</th>
                            <th>依頼</th>
                            <th>メールアドレス</th>
                            <th>連絡先</th>
                            <th>住所</th>
                            <th>誕生日</th>
                            <th>年齢</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php 
                        
                             foreach ($users as $key => $value) {
                        
                                //管理者と先生は外す
                                $user_role = getSetUserRoles($value->ID);

                                if($user_role == "administrator" || $user_role == "editor")
                                {
                                    continue;
                                }

                                $one_user = $userData->getUserAcountData($value->ID);

                                if( $one_user["非表示"] == 1)
                                {
                                    continue;
                                }

                                if( $one_user["ユニークID"] == "")
                                {
                                    continue;
                                }
                        ?>



                           <tr>
                                <td style="border: 1px solid black;"><?php echo $one_user["ユニークID"];?></td>
                                <td style="border: 1px solid black;">
                                    <form class="target-form" action="<?php echo getURLSetSlag('admin-member-edit'); ?>?user_id=<?php echo $value->ID;?>" method="post" style="display: none;" onSubmit="return confirm('選択してもよろしいですか？');">
                                        <input type="hidden" name="user_id" value="<?php echo $value->ID;?>">
                                        <input type="hidden" name="category_type" value="">
                                        <input type="hidden" name="target_slots" value="1">
                                        <input type="hidden" name="add_sheet" value="">
                                        <input type="hidden" name="schedule_id" value="">
                                        <input type="hidden" name="add_sheet_unix" value="<?php echo time();?>">
                                        <input type="hidden" name="payment_type" value="99"><?php //支払いタイプはここからだとその他にしておく?>
                                        <button type="submit" style="background-color: #4CAF50;color: white;padding: 5px 15px;border-radius: 4px;border: none;cursor: pointer;">選択</button>
                                    </form>
                                </td>
                                <td style="border: 1px solid black;"><a href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $value->ID;?>" target="_blank"><?php echo $one_user["フル名前"];?></a></td>
                                <td style="border: 1px solid black;"><?php echo count($one_user["施術ID"]);?></td>
                                <td style="border: 1px solid black;"><?php echo $one_user["メール"];?></td>
                                <td style="border: 1px solid black;"><?php echo $one_user["電話番号"];?></td>
                                <td style="border: 1px solid black;"><?php echo $one_user["郵便番号"];?> <?php echo $one_user["住所"];?></td>
                                <td style="border: 1px solid black;"><?php echo $one_user["誕生日年月日"];?></td>
                                <td style="border: 1px solid black;"><?php echo $one_user["年齢"];?></td>
                           </tr>

                        <?php } ?>

                    </tbody>


                </table>

		</div>

            



		 <?php } ?>


	</div>
</div>



</div>






<script type="text/javascript"> 


function register_check(){

	if(window.confirm('登録してもよろしいですか？')){ // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

	}
	else{ // 「キャンセル」時の処理

		window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止

	}

}


// -->
</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#sort-table').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/ja.json"
            },
            "pageLength": 50,  // 1ページあたりの行数
            "lengthMenu": [50, 100, 200]  // 選択できる件数
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.querySelector('.category-select');
    const slotInput = document.querySelector('input[name="slot-input"]');
    const salesInput = document.querySelector('input[name="sales-input"]');
    const stockData = <?php echo json_encode($stock_data); ?>;
    const targetForms = document.querySelectorAll('.target-form');

    // セレクト要素が存在する場合のみイベントリスナーを追加
    if (categorySelect) {
        categorySelect.addEventListener('change', function () {
            const selectedValue = this.value;
            const selectedOption = this.options[this.selectedIndex];
            const selectedType = selectedOption.getAttribute('data-type');
            
            // すべてのフォームのcategory_type入力に値を設定
            targetForms.forEach(form => {
                form.querySelector('input[name="category_type"]').value = selectedValue;
                // フォームの表示/非表示を切り替え
                form.style.display = selectedValue ? 'block' : 'none';
            });

            // 入力フィールドの有効/無効を切り替え
            if (selectedValue) {
                if (slotInput) {
                    slotInput.disabled = false;
                    slotInput.value = 1; // 値を最小値にリセット
                }
                if (salesInput) {
                    salesInput.disabled = false;
                    salesInput.value = 1; // 値を最小値にリセット
                    // 在庫数を上限として設定
                    if (stockData[selectedValue]) {
                        salesInput.max = stockData[selectedValue];
                    }
                }
            } else {
                if (slotInput) {
                    slotInput.disabled = true;
                    slotInput.value = 1; // 値を最小値にリセット
                }
                if (salesInput) {
                    salesInput.disabled = true;
                    salesInput.value = 1; // 値を最小値にリセット
                }
            }
        });
    }

    // ナンバー入力変更イベント
    if (slotInput) {
        slotInput.addEventListener('input', function () {
            const value = this.value;
            document.querySelectorAll('.target-form input[name="target_slots"]').forEach(input => {
                input.value = value;
            });
        });
    }

    if (salesInput) {
        salesInput.addEventListener('input', function () {
            const value = this.value;
            document.querySelectorAll('.target-form input[name="target_slots"]').forEach(input => {
                input.value = value;
            });
        });
    }
});
</script>
