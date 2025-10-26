<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/ConnectionGroupClass.php");
    $connection_group_data = new ConnectionGroupClass(); //管理データ

    $users = get_users();
    $user_data = array();
    $cgroup_data = $connection_group_data->GetConnectGroupName();

    foreach ($users as $user) {
        $is_delete = get_user_meta($user->ID, 'is_delete', true);
        if($is_delete) continue;
        
        $group_user_id = get_user_meta($user->ID, 'connect_group', true);
        $connection_id = get_field('acf_connection_top', $group_user_id);
        $group_d = $connection_group_data->GetConnectionGroupDetail($group_user_id);     //関連グループデータ取得

        $user_data = makeUserTable($users,$connection_id); //テーブルデータ作成
    }
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 
<script src="<?php echo get_template_directory_uri()."/custompage/admin/a-gate-script.js"?>"></script>


<div class="admin-exorcism-area">
    <div class="admin-connect-area">
        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">（親族）関連代表者設定</div></div>

        <div class="btn-flex-right">

            <form class="btn-flex-right" action="" method="get">
                <input type="hidden" name="squeeze_group">
                <select class="table-squeeze" name="group_id" id="">
                    <option value="">グループ設定</option>
                    <?php 
                        foreach ($cgroup_data as $key => $value) {
                    ?>
                    <option value="<?php echo $value['ID']?>" <?php if(isset($_GET['group_id']) && $value['ID'] == $_GET['group_id']) echo "selected";?>><?php echo $value['group_name']?></option>
                    <?php 
                        }
                    ?>
                </select>
                <button class="squeeze-btn">絞り込む</button>
            </form>
            <a href="<?php echo getURLSetSlag("admin-connection-top-chose"); ?>" >
                <button class="squeeze-btn release">絞りみ解除</button>
            </a>
        </div>


        <table id="userTable" class="table table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>ユーザーID</th>
                    <th width="150">名前</th>
                    <th width="150">ヨミカタ</th>
                    <th>関連</th>
                    <th>グループ</th>
                    <th>性別</th>
                    <th>連絡先</th>
                    <th>住所</th>
                    <th>メールアドレス</th>
                    <th>生年月日</th>
                    <th>年齢</th>
                    <th>登録日</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<script>
    
    var userData = <?php echo json_encode($user_data); ?>;

    if ($.fn.DataTable.isDataTable('#userTable')) {
        $('#userTable').DataTable().destroy();
    }

    $('#userTable').DataTable({
        data: userData,
        columns: [
            { 
                data: null, 
                "render": function(data, type, row) { 
                    return '<form action="<?php echo  getURLSetSlag( "admin-connection-make" );?>" method="post">' +
                                '<input type="hidden" name="chose_id" value="' + data.ID + '" />' +
                                '<button class="edit-mark gray" >選択</button>' +
                            '</form>';
                        } 
            },
            { data: 'user_unique_id' },
            {   //名前
                data: null,
                "render": function (data, type, row) {
                    
                    return data.last_name + ' ' + data.first_name;
                }
            },
            {   //ヨミカタ
                data: null,
                "render": function (data, type, row) {
                    
                    return data.last_name_kana + ' ' + data.first_name_kana;
                }
            },
            { data: 'user_connection_disp'},    //関連
            { data: 'sex' },    //グループ  TODO：グループ作成後0815
            { data: 'sex' },
            { data: 'tel' },
            { data: 'city' },   //住所
            { data: 'user_email' },   //メールアドレス
            { data: 'born' },   //メールアドレス
            { data: 'age' },     //年齢
            { data: 'regist_day' },//登録日
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
        }
    });

</script>