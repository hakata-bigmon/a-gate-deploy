<?php 
    $users = get_users();
    $user_data = array();

    require_once (dirname(__FILE__)."/../../class/ConnectionGroupClass.php");
    $connection_group_data = new ConnectionGroupClass(); //管理データ

    $cgroup_data = $connection_group_data->GetConnectionGroup();

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 
<script src="<?php echo get_template_directory_uri()."/custompage/admin/a-gate-script.js"?>"></script>

<div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">関係者</div></div>

<form id="" action="<?php echo  getURLSetSlag( "admin-member-list" );?>" method="get" >
    <input type="hidden" name="group_id" value="<?php echo $_POST['group_id']; ?>">
    <input type="hidden" name="chose_id" value="<?php echo $_POST['chose_id']; ?>">
    <input type="hidden" name="group_user_add" value="add">
    <button class="form-btn red">関係者を追加</button>
</form>

<form class="over-area " id="" action="<?php //echo  getURLSetSlag( "" );?>" method="post" >

    <table id="" class="user-disp-table table table-bordered">
        <thead>
            <tr>
                <th>ユーザーID</th>
                <th>名前</th>
                <th>続柄</th>
                <th>追記</th>
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
            { data: 'input_unique_id' },
            {   //名前
                data: null,
                "render": function (data, type, row) {
                    return data.input_last_name + ' ' + data.input_first_name;
                }
            },

            // { data: 'input_connect_group' },    //関連
            
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
        }
    });

</script>