<?php

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
	require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");

    $users = get_users();
    $user_data = array();

    $spiritSheet = new SpiritSheetClass(); //管理データ
    $spiritType = new SpiritTypeClass(); //管理データ
    $userData = new SpiritUserClass(); //管理データ

     $spiritTypeArray = $spiritType->getSpiritType();

?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<div class="admin-user-table-area">

	<div class="admin-title">
		<?php echo "浄霊・施術作成"; ?>
	</div>


    <div class="admin-spirit-sheets-button-flex">

        <div class="admin-preview-button-flex">

            <div class="admin-preview-button-flex-box">
                <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin_make_remote_sprit'); ?>'">浄霊・施術作成</button>
            </div>

            <div class="admin-preview-button-flex-box">
                <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin_make_remote_sprit'); ?>'">日程確定作成</button>
            </div>

            <div class="admin-preview-button-flex-box">
                <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin_make_remote_sprit'); ?>'">物販作成</button>
            </div>
            <div class="admin-preview-button-flex-box">
                <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin_make_remote_sprit'); ?>'">遠隔・相談作成</button>
            </div>
        </div>

    </div>



    <div class="admin-spirit-list-select-area">

         <div class="admin-spirit-list-select">

            <div class="admin-spirit-list-select-title">浄霊・施術タイプを選択してください</div>
                
            <select class="category-select" data-applicant-id="123" style="font-size: 18px;margin-top: 5px;">

                <option value="" style="text-align: center;">----------選択してください------------</option>

                <?php foreach ($spiritTypeArray as $key => $value) { ?>

                    
                    <?php foreach ($value as $data_key => $data_value) { ?>
                        <?php if($data_value["group"] > SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL){continue;}?>
                        <option value="<?php echo $data_value["ID"];?>"><?php echo $data_value["title"];?></option>
                    <?php } ?>
                <?php } ?>
            </select>

        </div>

        <div class="admin-spirit-list-select">

             <div class="admin-spirit-list-select-title">枠数を選択してください</div>
        
            <input type="number" name="slot-input" value="1" min="1" data-applicant-id="123" style="width: 50px;margin-top: 6px;height: 30px;text-align: center;">枠
        </div>

        
    </div>

    <div class="admin-remote-sprit-text">申込者を選択してください</div>


    
	
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
                        


                                $one_user = $userData->getUserAcountData($value->ID);

                                if( $one_user["ユニークID"] == "")
                                {
                                    continue;
                                }
                        ?>



                           <tr>
                                <td style="border: 1px solid black;"><?php echo $one_user["ユニークID"];?></td>
                                <td style="border: 1px solid black;">
                                    <form class="target-form" action="<?php echo getURLSetSlag('admin_make_remote_sprit'); ?>" method="post">
                                        <input type="hidden" name="applicant_id" value="<?php echo $value->ID;?>">
                                        <input type="hidden" name="category_type" value="">
                                        <input type="hidden" name="target_slots" value="">
                                        <button type="submit">選択</button>
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
  // セレクト変更イベント
  document.querySelector('.category-select').addEventListener('change', function () {
    const value = this.value;
    // すべてのフォームのcategory_type入力に値を設定
    document.querySelectorAll('.target-form input[name="category_type"]').forEach(input => {
      input.value = value;
    });
  });

  // ナンバー入力変更イベント
  document.querySelector('input[name="slot-input"]').addEventListener('input', function () {
    const value = this.value;
    // すべてのフォームのtarget_slots入力に値を設定
    document.querySelectorAll('.target-form input[name="target_slots"]').forEach(input => {
      input.value = value;
    });
  });
});
</script>
