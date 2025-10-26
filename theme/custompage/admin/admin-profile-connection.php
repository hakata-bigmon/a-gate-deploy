


<?php 
    // 関連一覧ページ
    require_once ("a-gate-functions.php");
    
    require_once ("a-gate-function-modals.php");
    require_once (dirname(__FILE__)."/../../class/ConnectionGroupClass.php");
    $connection_group_data = new ConnectionGroupClass(); //管理データ

    $mes = "";
    $edit_err = "";
    
    YNModalDisp();


    //編集
    if(isset($_POST["input_edit_name"]))
    {

        if($spirit_sheet_data->checkGeneralPurposeData( $_POST["edit_no"] , $_POST["input_edit_name"]  , "cpt_inflow"))
        {
            $edit_err = "同じ名前のものは登録できません";
        }
        else if($_POST["input_edit_name"] != "")
        {
            $spirit_sheet_data->saveGeneralPurposeData( $_POST["edit_no"] , $_POST["input_edit_name"] );
        }
        else{

            $edit_err = "空白は登録できません";

        }
    }
    
    //新規
    if(isset($_POST["add_connection"]))
    {
        if(isset($_POST["connection_name"])){

            $ok_check = $connection_group_data->checkConnectionGroup($_POST['connection_name']);
            
            if($_POST["connection_name"] == ""){
                $mes = "関連名を入れてください";
    
            
            }else if(!$ok_check){
                $mes = "同じ名前のものは登録できません";
            }else{
    
                $res = $connection_group_data->newConnectionGroup($_POST);
                if(!is_wp_error($res)){
                    $mes = "追加しました。";
                }
            }
        }

    }

    //削除
    if(isset($_POST["delete_no"]))
    {
        $connection_group_data->deleteConnectionGroup($_POST["delete_no"]);
    }
    
    $cgroup = $connection_group_data->MakeCGroupData(); //関連グループデータ作成
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 
<script src="<?php echo get_template_directory_uri()."/custompage/admin/a-gate-script.js"?>"></script>

<div class="admin-exorcism-area">
    <div class="admin-exorcism-button-area">

        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">関連設定</div></div>

        <?php /* エラー */?>
        <?php if($mes != ""){ ?>
            <div class="admin-spirit-edit-err-etr"><?php echo $mes;?></div>
        <?php } ?>

        <div class="admin-spirit-edit-list-area">

            <a href="<?php echo  getURLSetSlag( "admin-connection-make" );?>">
                <button class="form-btn red">新規作成</button>
            </a>

            <table id="userTable" class="user-disp-table table table-bordered" >
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>関連名</th>
                        <th>関連人数</th>
                        <th>関連代表</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <div class="admin-spirit-return-button-area">
                <button type=“button” class="admin-spirit-return-button" onclick="location.href='<?php echo getURLSetSlag('admin-profile-menu'); ?>'">戻る</button>
            </div>

        </div>
        
        <script>
            var cgroup = <?php echo json_encode($cgroup); ?>;
            
            $('#userTable').DataTable({
                data: cgroup,
                columns: [
                    
                    { data: 'ID' },
                    { data: 'group_name' },
                    { data: 'group_number' },
                    { 
                        data: null, 
                        render: function(data, type, row) { 
                            return '<a href="<?php echo  getURLSetSlag( "admin-member-edit" );?>?user_id=' + data.group_id + '" target="_blank">' + data.group_top + '</a>';
                        } 
                    },
                    { 
                        data: null, 
                        render: function(data, type, row) { 
                            return '<a class="edit-mark" href="<?php echo  getURLSetSlag( "admin-connection-member-list" );?>?group_id=' + data.ID + '&group_top=' + data.group_id + '">詳細</a>';
                        } 
                    },
                    { 
                        data: null, 
                        render: function(data, type, row) { 
                            return '<form action="<?php echo  getURLSetSlag( "admin-profile-connection" );?>" id="delete_item_' + row.ID + '" method="post" style="display:inline;">' +
                                '<input type="hidden" name="delete_no" value="' + row.ID + '" />' +
                                '<div class="edit-mark gray" onclick="click_modal(\'' + row.group_name + 'を削除して宜しいですか？\\nグループに登録されている他のユーザーは関連をリセットされます\',\'delete_item_' + row.ID + '\')">削除</div>' +
                                '</form>';
                        } 
                    },
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
                }
            });
        </script>
        <?php //} ?>
    </div>
</div>

