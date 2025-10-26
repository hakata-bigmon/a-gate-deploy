<?php 
    // 関連グループ内メンバー一覧 or 追加

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/ConnectionGroupClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/GroupSettingClass.php");
    $group_setting_data = new GroupSettingClass(); //グループクラス
    $spiritType = new SpiritTypeClass(); //管理データ
    $connection_group_data = new ConnectionGroupClass(); //管理データ

    
    $group_list = $group_setting_data->getGroupData();

    
    $res = DispSqueeze();
    $current_user_disp_squeeze = json_decode(get_user_meta(get_current_user_id(),'disp_squeeze',true));//表示設定取得
    $spiritTypeArray = $spiritType->getSpiritType();    //浄霊タイプ
    
    YNModalDisp();  //モーダル
    
    $users = get_users();
    $user_data = array();

    if(isset($_GET['chose_id']) ){
        $group_top = $_GET['chose_id'];
    }
    if(isset($_GET['group_top']) ){
        $group_top = $_GET['group_top'];
    }
    

    if(isset($_GET['group_id'])){
        $group_id = $_GET['group_id'];
        $cgroup_data = json_decode(get_field('acf_connection_list',$group_id),true);

    }
    $cgroup_name = $connection_group_data->GetConnectGroupName();

    // グループから削除
    if(isset($_POST['delete_group_user'])){
        
        // 指定ユーザーの関連削除
        $res = update_user_meta($_POST['delete_group_user'],'connect_group',"");        // 
        $res = update_user_meta($_POST['delete_group_user'],'connect_group_disp',"");   // 
        $connection_group_data->ChangeConnectionGroupCount($cgroup_data,$group_id);     // 関連人数変更

    }

    
    SettingModalDisp($spiritTypeArray,$current_user_disp_squeeze,"connection");      //表示設定モーダル


?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 
<script src="<?php echo get_template_directory_uri()."/custompage/admin/a-gate-script.js"?>"></script>
<script>
    
    var user_add_on = <?php if(isset($_GET['group_user_add'])) echo 1; else echo 0; ?>;
</script>

<div class="admin-user-table-area">

    <?php 
        /* 関連グループ選択時 */
        // 関連グループ参加者一覧
        if(!isset($_GET['group_user_add'])){
            $connection_id = get_field('acf_connection_top', $group_id);
            $connection_name = get_field('acf_connection_top_name',$group_id);  //関連グループタイトル
            $user_data = makeUserTable($users); //テーブルデータ作成
            

    ?>

        <div class="admin-title"><?php echo "関連グループ(".$connection_name.")一覧";?></div>

        <div class="btn-flex">

            <form action="<?php echo getURLSetSlag("admin-profile-connection"); ?>">
                <button class="form-btn">関連TOP</button>
            </form>

            <form action="<?php echo getURLSetSlag("admin-connection-group-disp"); ?>?group_id=<?php echo $group_id; ?>" method="POST">
                <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                <input type="hidden" name="group_user_change" value="on">
                <input type="hidden" name="chose_id" value="<?php echo $group_top; ?>">
                <button class="form-btn">関連グループ詳細編集</button>
            </form>
    
            <form action="">
                <button type="button" class="form-btn" onclick="location.href='<?php echo getURLSetSlag('admin-profile-grope'); ?>'" >グループ設定</button>
            </form>
            <form action="">

                <button type="button" class="form-btn" onclick="setting_modal()" >表示設定</button>
            </form>
        </div>

    <?php 
        // 関連グループにユーザー追加
        // }else if(isset($_GET['group_user_add'])){ 
        }else{
            $connection_id = get_field('acf_connection_top', $group_id);
            $user_data = makeUserTable($users); //テーブルデータ作成
            
            $action_url = getURLSetSlag("admin-connection-group-disp")."?group_id=".$group_id;
            $squeeze_url = getURLSetSlag("admin-connection-member-list")."?group_id=$group_id&chose_id=$group_top&group_user_add=add";
    ?>

        <div class="admin-title">（親族）関連者追加設定</div>

        <form class="" action="<?php echo $action_url; ?>" method="POST" onclick="getFormCheck()">

            <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
            <input type="hidden" name="chose_id" value="<?php echo $group_top; ?>">
            
            <div id="checkbox-hidden-fields"></div>
            <button class="form-btn">追加</button>
        </form>

        <div class="btn-flex-right">

            <form class="btn-flex-left" action="" method="get">
                <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                <input type="hidden" name="chose_id" value="<?php echo $group_top; ?>">
                <input type="hidden" name="group_user_add" value="add">
                
                <select class="table-squeeze" name="user-squeeze-connection" id="">
                    <option value="">関連設定</option>

                        <?php foreach ($cgroup_name as $key => $value) { ?>

                        <option value="<?php echo $value['ID']?>" <?php if(isset($_GET['user-squeeze-connection'])  && $value['ID'] == $_GET['user-squeeze-connection']) echo "selected";?>><?php echo $value['group_name']?></option>
                        
                        <?php } ?>

                    </select>
                </select>
                <select class="table-squeeze" name="user-squeeze-group" id="">
                    <option value="">グループ設定</option>
                    <?php 
                            foreach ($group_list as $key => $value) { 
                        ?>
                        <option value="<?php echo $value['ID']?>" <?php if(isset($_GET['user-squeeze-group']) && $value['ID'] == $_GET['user-squeeze-group']) echo "selected";?>><?php echo $value['title']?></option>
                        <?php } ?>

                </select>
                <button class="squeeze-btn">絞り込む</button>
                <!-- <a class="squeeze-btn gray" href="<?php echo getURLSetSlag("admin-member-list"); ?>">絞り込み解除</a> -->
                <a class="squeeze-btn" href="<?php echo $squeeze_url ?>" >絞り込み解除</a>

                </a>
            </form>

        </div>

    <?php } ?>

    
    <?php 

        // テーブル作成
        // foreach ($user_data as $key => $value) {
            
        //     var_dump($value['user_connection_disp']);  //削除okd
        // }
        
        DispAnnotation();
        dispUserTabaleS($user_data,$current_user_disp_squeeze,false,$spiritTypeArray,0);

        // ユーザーに設定されているグループ取得
        if(!isset($_GET['group_user_add'])){


            // TODO:ユーザーが関与しているグループをすべて表示　
            // □オカダリョウグループ56を表示中に名倉Aグループが関与グループとして表示されればOK　250325
            // □名倉グループ表示中には表示させない
            $cgroup = $connection_group_data->MakeCGroupData();
            $disp_title_flg = false;
    
            $table_id = 1;
            foreach ($cgroup as $key => $value) {

                if($value['ID'] == $group_id) continue;     // 同じものは表示しない

                $user_data = makeUserTable($users,$value['ID']); //関与テーブルデータ作成
                // $user_data = makeUserTable($users,$group_id); //テーブルデータ作成

                
                if(!$disp_title_flg){
                    echo '<div class="admin-title">関与グループ一覧</div>';
                    $disp_title_flg = true;
                }

                ?>
                    <div class="connect-sub-title"><?php echo "<br>".$value['group_name']."<br>"; ?></div>
                <?php 
                
                dispUserTabaleS($user_data,$current_user_disp_squeeze,false,$spiritTypeArray,$table_id);
                $table_id++;
            }
        }
    
    
    ?>


</div><!-- admin-user-table-area -->
<script>
        
// 関連追加時フォームチェック追加
function getFormCheck(){
     // チェックボックスが入っているテーブルを取得
    var checkboxes = document.querySelectorAll('#userTable0 input[type="checkbox"]');
    

    // チェックされているチェックボックスを配列に追加
    var hiddenFieldsContainer = document.getElementById('checkbox-hidden-fields');
    hiddenFieldsContainer.innerHTML = ''; // 既存のhiddenをクリア
    checkboxes.forEach(function(checkbox) {
        if (checkbox.checked) {

            var hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = checkbox.name;  // 複数の値を配列としてPOST
            hiddenInput.value = 'add';
            hiddenFieldsContainer.appendChild(hiddenInput);
        }
    });


}
</script>