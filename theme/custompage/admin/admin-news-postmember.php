

<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");

	
    $users = get_users();
    $user_data = array();

    $spiritSheet = new SpiritSheetClass(); //管理データ


    $user_names[] = array();

?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">


<div class="admin-user-table-area">


    <div class="admin-title">
		<?php echo "お知らせ送信者選択"; ?>
	</div>

	



    <div class="admin-profile-edit-area" style="max-width: 1700px;margin-top: 50px;">


		 <?php if(count($users) >= 1){?>


            <div style="font-size: 18px;font-weight: 600;text-align: center;">【お知らせ対象者】</div>

            <div style="max-width: 125px;margin-left: auto;margin-right: auto;margin-top: 5px;">
                <button id="clearButton" style="width: 100%;" disabled>全チェックを外す</button>
            </div>
            
            <div id="selectedValues" style="margin-top: 10px;"></div>
            <?php /*
            <form action="<?php echo getURLSetSlag("admin-new-edit"); ?>" method="post" onSubmit="return checkBeforeSubmit()">
            */?>

            <form id="popup-form" onSubmit="return sendDataToParent();" >

                <input type="hidden" name="new_mail" value="">
                
                <div style="max-width: 320px;margin-left: auto;margin-right: auto;">
                    <button  type="submit" class="admin-remote-make-arami-button" style="width: 100%;font-size: 18px;" >上記の対象者で送信</button>
                </div>
                <?php
            
                    $target_array = array();

                
                   // var_dump($users);
                ?>

           

			    <div class="admin-temporary-registration-check-table" style="">

                
                    <table id="sort-table" class="display">

                        <thead>
                            <tr>
                                <th>ユーザーID</th>
                                <th></th>
                                <th>名前</th>
                                <th>ヨミカタ</th>
                                <th>メールアドレス</th>
                                <th>連絡先</th>
                                <th>住所</th>
                            </tr>
                        </thead>
                        <tbody>
                       
                            <?php  foreach ($users as $users_key => $users_value) {?>

                                <?php  
                                    $is_delete = get_user_meta($users_value->ID, 'is_delete', true); 
                                    $user_info = get_userdata($users_value->ID);
                                
                                    if($is_delete  || $user_info->roles[0] == 'administrator' || $user_info->roles[0] == 'editor')
                                    {
                                        if( $users_value->ID != 1){ //管理者だけはテスト用に入れておく
                                            continue;
                                        }
                                    }

                                     if($users_value->user_email == "")
                                    {
                                        continue;
                                    }

                                    $city = get_user_meta($users_value->ID, 'billing_city', true);
                                    $billing_address  =  get_user_meta($users_value->ID, 'billing_address_1', true);


                                    $user_names[ $users_value->ID ] = get_user_meta($users_value->ID, 'last_name', true) . " " . get_user_meta($users_value->ID, 'first_name', true);
                                    $user_names[ $users_value->ID ] .= "(" .$users_value->user_email . ")"; 
                                ?>



                                <tr>
                                    <td><?php echo get_user_meta($users_value->ID, 'user_unique_id', true);?>(<?php echo $users_value->ID;?>)</td>
                                    <td><input type="checkbox" name="post_target" value="<?php echo $users_value->ID;?>"></td>
                                    <td><?php echo get_user_meta($users_value->ID, 'last_name', true) . " " . get_user_meta($users_value->ID, 'first_name', true);?></td>
                                    <td><?php echo get_user_meta($users_value->ID, 'last_name_kana', true) . " " . get_user_meta($users_value->ID, 'first_name_kana', true);?></td>
                                    <td><?php echo $users_value->user_email;?></td>
                                    <td>
                                        <?php if(get_user_meta($users_value->ID, 'billing_phone', true) != ""){ ?>
                                            <?php echo get_user_meta($users_value->ID, 'billing_phone', true) . '-' . get_user_meta($users_value->ID, 'billing_phone2', true) . '-' . get_user_meta($users_value->ID, 'billing_phone2', true)?>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $city . " " .$billing_address;?></td>
                                </tr>
                            <?php } ?>
                        </tbody>


                   </table>

			    </div>

            </form>


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
            "lengthMenu": [20, 50, 100]  // 選択できる件数
        });
    });
</script>

<script>
    const userNames = <?php echo json_encode($user_names); ?>;
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const selectedValuesDiv = document.getElementById('selectedValues');
    const clearButton = document.getElementById('clearButton');

    function updateDisplayedValues() {
        const selectedValues = Array.from(checkboxes)
            .filter(checkbox => checkbox.checked)
             .map(checkbox => userNames[checkbox.value] || '不明なユーザー');
            
        selectedValuesDiv.textContent = selectedValues.join(', ');

        // チェックボックスが1つでも選択されていればボタンを有効にする
        clearButton.disabled = selectedValuesDiv.length === 0;
    }

     // すべてのチェックをオフにする処理
    clearButton.addEventListener('click', () => {
        checkboxes.forEach(checkbox => checkbox.checked = false);
        updateDisplayedValues();  // 表示を更新
        clearButton.disabled = true;  // ボタンを再び無効化する
    });

    // チェックボックスのイベントリスナーを設定
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateDisplayedValues);
    });


    // ボタンのonclickで呼ばれる関数
    function checkBeforeSubmit() {
        const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

        if (!isAnyChecked) {
            alert('1つ以上のチェックボックスにチェックを入れてください。');
            return false; // 「OK」時は送信を実行
        } else {
            // 1つでもチェックされている場合に送信
            return true; // 「OK」時は送信を実行
        }
    }

</script>

   


<script>
    // クエリパラメータを取得して解析
    function getQueryParams() {
        const params = new URLSearchParams(window.location.search);
        const selected = params.get('selected');
        return selected ? selected.split(',') : [];
    }

    // 初期化: クエリパラメータに基づいてチェックボックスにチェックを付ける
    const preselectedOptions = getQueryParams();
    preselectedOptions.forEach(value => {
        const checkbox = document.querySelector(`input[name="post_target"][value="${value}"]`);
        if (checkbox) {
            checkbox.checked = true; // 対応するチェックボックスにチェックを付ける
            // チェックボックスが1つでも選択されていればボタンを有効にする
            updateDisplayedValues();//名前表示
            //const clearButton = document.getElementById('clearButton');

            //clearButton.disabled = selectedValuesDiv.length === 0;
        }

    });

   function sendDataToParent() {
    // チェックボックスで選択された値を取得
    const selectedOptions = Array.from(document.querySelectorAll('input[name="post_target"]:checked'))
                                    .map(checkbox => checkbox.value);

    if (window.opener && !window.opener.closed) {
        // 親ウィンドウの関数を呼び出してデータを渡す
        window.opener.displayPopupData(selectedOptions);
    }

    // ポップアップを閉じる
    window.close();

    // 送信を中止する（ページリロードを防ぐ）
    return false;
}
</script>