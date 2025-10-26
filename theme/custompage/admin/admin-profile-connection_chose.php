<?php 
    $users = get_users();
    $user_data = array();

    
    foreach ($users as $user) {
        $is_delete = get_user_meta($user->ID, 'is_delete', true);
        if($is_delete) continue;

        $t_data = getUsersData($user->ID);
        $user_data[] = $t_data;
    }

    // var_dump($user_data);  //削除okd
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 
<script src="<?php echo get_template_directory_uri()."/custompage/admin/a-gate-script.js"?>"></script>

<div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">（親族）関連代表者設定</div></div>

<form class="over-area " id="" action="<?php //echo  getURLSetSlag( "" );?>" method="post" >

    <table id="userTable" class="table table-bordered">
        <thead>
            <tr>
                <th></th>
                <th>ユーザーID</th>
                <th>名前</th>
                <th>ヨミカタ</th>
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

    
</form>

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
                    return '<form action="<?php echo  getURLSetSlag( "admin-profile-connection" );?>?connection_new=on" method="post">' +
                                '<input type="hidden" name="chose_id" value="' + data.input_user_id + '" />' +
                                '<button class="edit-mark gray" >選択</button>' +
                            '</form>';
                        // '<div class="" onclick="sendPostData({input_introduction_id: ' + row.user_unique_id + ', input_introduction_name: \'' + row.first_name + row.last_name + '\'})">選択</div>';
                } 
            },
            { data: 'input_unique_id' },
            {   //名前
                data: null,
                "render": function (data, type, row) {
                    
                    return data.input_last_name + ' ' + data.input_first_name;
                }
            },
            {   //ヨミカタ
                data: null,
                "render": function (data, type, row) {
                    
                    return data.input_last_name_kana + ' ' + data.input_first_name_kana;
                }
            },
            { data: 'input_unique_id' },    //関連
            { data: 'input_unique_id' },    //グループ  TODO：グループ作成後0815
            { data: 'input_user_sex' },     //性別
            {   //連絡先
                data: null,
                "render": function (data, type, row) {
                    
                    return  data.input_tel_1 + '-' + data.input_tel_2 + '-' + data.input_tel_3 ;
                }
            },
            {   //住所
                data: null,
                "render": function (data, type, row) {
                    
                    return  data.input_post_no + data.input_billing_city;
                }
            },
            { data: 'input_user_email' },   //メールアドレス
            {   //生年月日
                data: null,
                "render": function (data, type, row) {
                    
                    return data.input_user_born_year + '-' + data.input_user_born_month + '-' + data.input_user_born_day ;
                }
            },
            { data: 'input_user_age' },     //年齢
            { data: 'input_user_registed' },//登録日
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
        }
    });

</script>