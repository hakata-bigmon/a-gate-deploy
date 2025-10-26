<?php 
    


?>
<!-- admin-user-table-area -->

    <?php /* 関連 */?>
    <?php if(isset($_GET['group_id']) && !isset($_GET['group_user_add'])){?>

        <div class="admin-title"><?php echo "関連グループ(".$connection_name.")一覧";?></div>


        <div class="btn-flex">
            <form action="<?php echo getURLSetSlag("admin-profile-connection"); ?>">
                
                <button class="form-btn">関連TOP</button>
            </form>
            <form action="<?php echo getURLSetSlag("admin-profile-connection"); ?>?connection_change=on&group_id=<?php echo $_GET['group_id']; ?>" method="POST">
                <input type="hidden" name="group_id" value="<?php echo $_GET['group_id']; ?>">
                <input type="hidden" name="group_user_change" value="on">
                <input type="hidden" name="chose_id" value="<?php echo $_GET['group_top']; ?>">
    
                <button class="form-btn">編集</button>
            </form>
    
            <form action="">
                <button class="form-btn">グループ設定（作成中</button>
            </form>
            <form action="">
                <button class="form-btn">表示設定（作成中</button>
            </form>
        </div>
    <?php }else if(isset($_GET['group_user_add'])){ ?>
        <?php 
            $action_url = getURLSetSlag("admin-profile-connection")."?connection_change=on&group_id=".$_GET['group_id'];
            // $action_url = getURLSetSlag("admin-member-list")."?group_id=".$_GET['group_id']."&chose_id=".$_GET['chose_id']."&group_user_add=add";
        ?>
        
        
        <div class="admin-title">（親族）関連者追加設定</div>

        <form class="" action="<?php echo $action_url; ?>" method="POST" onclick="getFormCheck()">

            <input type="hidden" name="group_id" value="<?php echo $_GET['group_id']; ?>">
            <input type="hidden" name="chose_id" value="<?php echo $_GET['chose_id']; ?>">
            <div id="checkbox-hidden-fields"></div>

            <button class="form-btn">追加</button>
        </form>

        <form class="btn-flex-right" action="" method="get">
            <input type="hidden" name="user_id" value="<?php echo $check_user_id?>">
            <select class="table-squeeze" name="user-setting-group" id="">
                <option value="">関連設定</option>
                <?php 
                    foreach ($spiritTypeArray as $key => $value) {
                ?>
                <option value="<?php echo ""; ?>" <?php if(isset($_GET['select_sprit_type']) && $_GET['select_sprit_type'] == $value_num["ID"]) echo "selected"; ?>><?php echo "";?></option>
                <?php 
                    }
                ?>
            </select>
            <select class="table-squeeze" name="select_sprit_type" id="">
                <option value="">グループ設定</option>
                <?php 
                    foreach ($spiritTypeArray as $key => $value) {
                ?>
                <option value="<?php echo $value_num["ID"]; ?>" <?php if(isset($_GET['select_sprit_type']) && $_GET['select_sprit_type'] == $value_num["ID"]) echo "selected"; ?>><?php echo $value_num["title"];?></option>
                <?php 
                    }
                ?>
            </select>
            <button class="squeeze-btn">絞り込む</button>
        </form>

    <?php } ?>

    <table id="userTable" class="user-disp-table table table-bordered">
        <thead>
            <tr>

            <?php /* 追加済み関係者 */?>
            <?php // if(isset($_GET['group_id']) && !isset($_GET['group_user_add'])){?>
            <?php if(isset($_GET['group_id'])){?>
                <th></th>
                <th>ユーザーID</th>
                <!-- <th>名前</th> -->
                <!-- <th>続柄</th> -->
                <!-- <th>追記</th> -->
                <th>苗字</th>
                <th>名前</th>
                <th>ミョウジ</th>
                <th>ナマエ</th>
                <th>関連</th>
                <!-- <th>グループ</th> -->
                <th>性別</th>
                <th>連絡先</th>
                <th>郵便番号</th>
                <th>住所</th>
                <th>メールアドレス</th>
                <th>LINE ID</th>
                <th>生年月日</th>
                <th>年齢</th>
                <th>DM</th>
                <th>流入元</th>
                <th>紹介者</th>
                <th>特記事項</th>
                <th>霊視鑑定</th>
                <th>先祖鑑定（父）</th>
                <th>先祖鑑定（母）</th>
                <th>土地鑑定</th>
                <th>完全浄霊</th>
                <th>先祖浄霊（父）</th>
                <th>先祖浄霊（母）</th>
                <th>土地浄化</th>
                <th>守護霊</th>
                <th>指導霊</th>
                <th>神繋ぎ</th>
                <th>アカシック</th>
                <th>会社浄霊</th>
                <th>会社土地</th>
                <th>パワーストーン</th>
            <?php } ?>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    

    
    <div class="admin-title"><?php echo "関連グループ一覧";?></div>
    他に関連設定されていれば表示（作成中

<!-- admin-user-table-area -->

<script>
    $('#userTable').DataTable({
    data: userData,
    columns: [
        <?php 
            // まとめてユーザー追加する用
            if(isset($_GET['group_user_add'])){
        ?>
        { 
            data: null, 
            render: function(row) { 
            return  '<input type="checkbox" name="add_connect_user[' + row.ID + ']" id="">'; 
            } 

        },
        <?php } ?>
        { data: 'user_unique_id' },
        { 
            data: null, 
            render: function(row) { 
            return '<a class="edit-mark" href="<?php echo getURLSetSlag("admin-profile-connection"); ?>?change_group=on&group_top=<?php echo $_GET['group_top']; ?>">編</a>' +
                    '<form action="<?php echo getURLSetSlag("admin-member-list"); ?>?group_id=<?php echo $group_id; ?>&group_top=<?php echo $_GET['group_top']; ?>" id="post_delete_group_user_' + row.ID + '" method="post">' +
                    '<input type="hidden" name="delete_group_user" value="' + row.ID + '">' +
                    '<div class="edit-mark gray" onclick="click_modal(\'関連グループからユーザーを削除しますか？\', \'post_delete_group_user_' + row.ID + '\')">削</div></form>'; 
            } 

        },
        { data: 'last_name' },
        { data: 'first_name' },
        { data: 'last_name_kana' },
        { data: 'first_name_kana' },
        
        <?php if(isset($_GET['group_id'])){?>
        { data: 'user_connection_disp' },   // 関連
        <?php }else{ ?>
        { data: 'user_connection' },   // 関連
        <?php } ?>
        // { data: '' },   // グループ
        { data: 'sex' },
        { data: 'tel' },
        { data: 'post_code' },
        { data: 'city' },
        { data: 'user_email' },
        // { data: 'line' },
        { data: 'born' },
        // { data: 'report_born' },
        { data: 'age' },
        { data: 'regist_day' },
        { data: '' },   //DM
        { data: 'input_inflow' },   // 流入元
        { data: 'input_introduction_name' },   // 紹介者
        { data: 'inflow_remarks' },
        // { data: '' },   // 霊視鑑定
        // { data: '' },   // 先祖鑑定（父）
        // { data: '' },   // 先祖鑑定（母）
        // { data: '' },   // 土地鑑定
        // { data: '' },   // 完全浄霊
        // { data: '' },   // 先祖浄霊（父）
        // { data: '' },   // 先祖浄霊（母）
        // { data: '' },   // 土地浄化
        // { data: '' },   // 守護霊
        // { data: '' },   // 指導霊
        // { data: '' },   // 神繋ぎ
        // { data: '' },   // アカシック
        // { data: '' },   // 会社浄霊
        // { data: '' },   // 会社土地
        // { data: '' },   // パワーストーン
    ],
    
    "order": [[0, "asc"]], // 第2列（インデックス2）を昇順（asc）にソート
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
    }
});
        
// 関連追加時フォームチェック追加
function getFormCheck(){
     // チェックボックスが入っているテーブルを取得
     var checkboxes = document.querySelectorAll('#userTable input[type="checkbox"]');
    
    // チェックされた値を保存する配列
    // var checkedValues = [];

    // チェックされているチェックボックスを配列に追加
    
    var hiddenFieldsContainer = document.getElementById('checkbox-hidden-fields');
    hiddenFieldsContainer.innerHTML = ''; // 既存のhiddenをクリア
    checkboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
            // console.log(checkbox.name);
            // checkedValues.push(checkbox.value); // チェックされた値を保存

            var hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = checkbox.name;  // 複数の値を配列としてPOST
            hiddenInput.value = 'add';

            // console.log(checkbox.name);
            // console.log(hiddenInput);
            hiddenFieldsContainer.appendChild(hiddenInput);
        }
    });


}
</script>