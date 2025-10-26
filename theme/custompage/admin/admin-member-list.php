<?php 
    
    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/ConnectionGroupClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/GroupSettingClass.php");
    $group_setting_data = new GroupSettingClass(); //グループクラス
    $connection_group_data = new ConnectionGroupClass(); //管理データ
    
    $spiritType = new SpiritTypeClass(); //管理データ
    $spiritTypeArray = $spiritType->getSpiritType();    //浄霊タイプ


    $users = get_users();
    $user_data = array();
    
    YNModalDisp();      //モーダル読み込み
    
    $cgroup_data = $connection_group_data->GetConnectionGroup();    //関連データ取得
    $cgroup_name = $connection_group_data->GetConnectGroupName();   //関連名取得
    
    $group_list = $group_setting_data->getGroupData();
    
    $title = "顧客一覧";

    if(isset($_GET['group_id'])){
        $group_id = $_GET['group_id'];
    }

    // グループから削除
    if(isset($_POST['delete_group_user'])){
    
        $res = update_user_meta($_POST['delete_group_user'],'connect_group',"");        // ユーザーに関連番号付与
        
        $res = update_user_meta($_POST['delete_group_user'],'connect_group_disp',"");   // ユーザーに関連名付与
        $connection_group_data->ChangeConnectionGroupCount($cgroup_data,$group_id);     // 関連人数変更

    }

    // 関連
    if(isset($_GET['group_id'])){
        $connection_id = get_field('acf_connection_top', $group_id);
        $group_d = $connection_group_data->GetConnectionGroupDetail($group_id);     //関連グループデータ取得
        $connection_name = $group_d[0]->post_title;                                 //関連グループタイトル
        $user_data = makeUserTable($users,$connection_id); //テーブルデータ作成

    }else{
        // 通常
        $user_data = makeUserTable($users); //テーブルデータ作成
    }
   //  var_dump($user_data);  //削除okd

    // ユーザー削除
    if(isset($_GET['delete_user'])){
        $delete_user_id = $_GET['user_id'];

        $target_user = array_filter($user_data, function($item) use ($delete_user_id) {
            return isset($item['ID']) && $item['ID'] == $delete_user_id;
        });

        $target_user = array_values($target_user);
        // var_dump($target_user[0]['group_top_flg']);  //削除okd

        if(isset($target_user[0]['group_top_flg']) && $target_user[0]['group_top_flg'] == true){
            $al_mes = get_user_meta($delete_user_id, 'last_name', true).get_user_meta($delete_user_id, 'first_name', true)."は関連の代表に設定されているため、削除できません。<br>先にグループを削除して下さい";
            YNMojiModalDisp($al_mes);  //モーダル
        }else{
            // 削除時に関連からも削除
            $delete_group_datas = json_decode(get_user_meta($delete_user_id, 'connect_group', true));
    
            if($delete_group_datas != null){
    
                foreach ($delete_group_datas as $d_gid) {
        
                    $connection_group_data->deleteTargetConnectGroup($d_gid,$delete_user_id);
                }

                // if($group_id != null){

                //     $res = $connection_group_data->deleteConnectGroup($cgroup_data,$group_id,$user_id);
                // }
            }
            
            $res = update_user_meta($delete_user_id,'is_delete',1);
        }

    }

    $introduction_on = false;
    $change_on = false;
    if(isset($_GET['introduction_member'])){
        $introduction_on = true;
        $title = "紹介者選択";
        
        if(isset($_GET['member_change'])){
            $change_on = true;
        }
    };

    // 検索絞り込み保存
    $res = SearchSqueeze();
    $current_user_search_squeeze = get_user_meta(get_current_user_id(),'search_squeeze',true);//表示設定取得
    
    // 設定がなければ全表示
    if($current_user_search_squeeze != "-" && $current_user_search_squeeze != null){

        $current_user_search_squeeze = json_decode(get_user_meta(get_current_user_id(),'search_squeeze',true));//表示設定取得
    }

    $res = DispSqueeze();
    $current_user_disp_squeeze = json_decode(get_user_meta(get_current_user_id(),'disp_squeeze',true));//表示設定取得

    SettingModalDisp($spiritTypeArray,$current_user_disp_squeeze);      //表示設定モーダル
    SearchModalDisp($current_user_search_squeeze);                      //検索設定モーダル

?>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/4.0.2/css/fixedColumns.dataTables.min.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/4.0.2/js/dataTables.fixedColumns.min.js"></script>
    <script src="<?php echo get_template_directory_uri()."/custompage/admin/a-gate-script.js"?>"></script>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .admin-user-table-area {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .admin-header-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px #b0c4de;
            padding: 32px;
            margin-bottom: 32px;
            padding-bottom: 4px;
            margin-top: 30px;
            padding-top: 20px;
        }
        .admin-title {
            font-size: 3rem;
            color: #234a6f;
            letter-spacing: 0.1em;
            margin-bottom: 24px;
        }
        .member-list-btn {
            width: 1100px;
        }
        .btn-flex {
            display: flex;
            gap: 24px;
            width: 100%;
            justify-content: center;
        }
        .form-btn {
            flex: 1 1 0;
            min-width: 180px;
            height: 56px;
            background: linear-gradient(90deg, #e3f2fd 0%, #bbdefb 100%);
            color: #1976d2;
            border: none;
            border-radius: 16px;
            font-size: 1.1rem;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin: 0;
            height: 36px;
            max-width: 240px;
        }
        .form-btn .material-icons {
            font-size: 2rem;
        }
        .form-btn:hover {
            background: #bbdefb;
            color: #0d47a1;
        }
        .form-btn a {
            color: inherit;
            text-decoration: none;
        }
        .btn-flex-left {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: nowrap;
            margin-bottom: 24px;
        }
        .table-squeeze {
            min-width: 180px;
            max-width: 240px;
        }
        .squeeze-btn, .squeeze-btn.gray {
            min-width: 140px;
        }
        .squeeze-btn {
            background: #f5f7fa;
            color: #234a6f;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            box-shadow: 0 2px 6px rgba(0,0,0,0.07);
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }
        .squeeze-btn:hover {
            background: #e3e8ed;
            color: #0d47a1;
        }
        .squeeze-btn.gray {
            background: #ececec;
            color: #888;
            border: none;
        }
        .squeeze-btn.gray:hover {
            background: #d6d6d6;
            color: #555;
        }
        table.dataTable {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px #b0c4de;
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
            .btn-flex {
                flex-direction: column;
                gap: 16px;
            }
            .form-btn {
                max-width: 100%;
                width: 100%;
            }
        }
    </style>

<div class="admin-user-table-area">
   

        <div class="admin-title"><?php echo $title ?></div>
        <div class="member-list-btn">
            <?php if($introduction_on){ ?>
                <button class="form-btn" style="width:100%;max-width:600px;">
                    <span class="material-icons">arrow_back</span>新規登録に戻る
                </button>
            <?php } else { ?>
            <div class="btn-flex">
                <button class="form-btn" onclick="location.href='<?php echo getURLSetSlag('admin-member-regist'); ?>'" style="border: 1px solid #afa8a8;color: #417ab3;background: linear-gradient(90deg, #ffffff 0%, #ffffff 100%);">
                    <span class="material-icons">person_add</span>新規登録
                </button>
                <button type="button" class="form-btn" onclick="setting_modal()">
                    <span class="material-icons">settings</span>表示設定
                </button>
                <button type="button" class="form-btn" onclick="search_modal()">
                    <span class="material-icons">search</span>検索設定
                </button>
            </div>
            <?php } ?>
        </div>

    <div class="admin-header-card">
       

        <form class="btn-flex-left" action="" method="get">
            <select class="table-squeeze" name="user-squeeze-connection" id="">
                <option value="">関連設定</option>
                <?php foreach ($cgroup_name as $key => $value) { ?>
                    <option value="<?php echo $value['ID']?>" <?php if(isset($_GET['user-squeeze-connection']) && $value['ID'] == $_GET['user-squeeze-connection']) echo "selected";?>><?php echo $value['group_name']?></option>
                <?php } ?>
            </select>
            <select class="table-squeeze" name="user-squeeze-group" id="">
                <option value="">グループ設定</option>
                <?php foreach ($group_list as $key => $value) { ?>
                    <option value="<?php echo $value['ID']?>" <?php if(isset($_GET['user-squeeze-group']) && $value['ID'] == $_GET['user-squeeze-group']) echo "selected";?>><?php echo $value['title']?></option>
                <?php } ?>
            </select>
            <button class="squeeze-btn">
                <span class="material-icons">filter_list</span>
                絞り込む
            </button>
            <a class="squeeze-btn gray" href="<?php echo getURLSetSlag("admin-member-list"); ?>">
                <span class="material-icons">clear</span>
                絞り込み解除
            </a>
        </form>
    </div>

    <?php
        // 紹介者追加
        if(isset($_GET['introduction_member'])){ 
            if(isset($_GET['user_change'])){
                $go_url = getURLSetSlag('admin-member-edit').'?user_id='.$_GET['user_id'];

            }else{
                $go_url = getURLSetSlag('admin-member-regist');
            }
    ?>

        <form id="currentForm" action="<?php echo $go_url; ?>" method="post">
            
            <input type="hidden" name="use_old_regist_id" value="use_old_regist_id">
        <?php 
        foreach ($_POST  as $key => $value) {
            ?>
            <input type="hidden" name="<?php echo htmlspecialchars($key);?>" value="<?php echo ($value);?>">
            <?php 
        }
        ?>
        
            
        </form>
    <?php } ?>

    <?php DispAnnotation();?>

    <?php 

       
        // dispUserTabale($current_user_disp_squeeze,$introduction_on,$spiritTypeArray);
        dispUserTabaleS($user_data,$current_user_disp_squeeze,$introduction_on,$spiritTypeArray);
    ?>


    <form action=""></form>
    
    <script>

    

    // 入力情報POST
    function sendPostData(data) {

        // 現在のフォームのデータを取得して追加
        var currentForm = document.getElementById('currentForm');
        var postData = '<?php echo json_encode($_POST) ?>';
        var formData = new FormData(currentForm);

        // 追加のデータをフォームにhidden inputとして追加
        for (var key in data) {
            if (data.hasOwnProperty(key)) {
                var hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = key;
                hiddenField.value = data[key];
                currentForm.appendChild(hiddenField);
            }
        }
        // 前のページで入力途中だったものを引き継ぎ
        for (var key in postData) {
            if (data.hasOwnProperty(key)) {
                var hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = key;
                hiddenField.value = data[key];
                currentForm.appendChild(hiddenField);
            }
        }

        <?php 
            //ユーザー情報変更時にPOST場所変更
            if(isset($_GET['user_change'])){
        ?>
                var hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = 'change_user';
                hiddenField.value = 'change_user';
                currentForm.appendChild(hiddenField);
        <?php 
            }
        ?>

        currentForm.submit();
    }


</script>


</div>
