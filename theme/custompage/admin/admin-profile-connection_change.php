<?php 
    $mes = "";

    $users = get_users();
    $user_data = array();
    require_once (dirname(__FILE__)."/../../class/ConnectionGroupClass.php");
    $connection_group_data = new ConnectionGroupClass(); //管理データ

    if(isset($_POST['chose_id'])){
        $group_top = $_POST['chose_id'];
    }
    
    if(isset($_POST['group_id'])){
        $group_id = $_POST['group_id'];
    }
    if(isset($_GET['group_id'])){
        $group_id = $_GET['group_id'];
    }

    // グループ一覧読み込み時に関連人数計算するようにする
    $cgroup_data = json_decode(get_field('acf_connection_list',$group_id),true);
    var_dump($cgroup_data);  //削除okd
    $connection_group_data->ChangeConnectionGroupCount($cgroup_data,$group_id);
    // update_field("acf_connection_count", count($cgroup_data), $group_id);
    $cgroup_data_array = array();
    

    // 関連追加
    if(isset($_POST['add_connect_user'])){

        foreach($_POST['add_connect_user'] as $user_id => $value){

            // $res = update_user_meta($user_id, 'connect_group', $_POST['group_id']);
            // echo "関連追加結果".$res;

            // TODO:関連追加時に（親族）関連設定の関連詳細を保存する

            // 混合
            // $cgroup_data[] = new stdClass();
            // $cgroup_data->{$user_id} = new stdClass();
            // $cgroup_data->{$user_id}->relationship_data = "";
            // $cgroup_data->{$user_id}->add_memo = "";
            $cgroup_data[$user_id]["relationship_data"] = "";
            $cgroup_data[$user_id]["add_memo"] = "";

            // ユーザーに関連情報追加
            $group_d = $connection_group_data->GetConnectionGroupDetail($group_id);
            $res = update_user_meta($user_id,'connect_group',$group_id);        // ユーザーに関連番号付与
            $res = update_user_meta($user_id,'connect_group_disp',$group_d[0]->post_title);   // ユーザーに関連名付与

            // アップデート
            $json_list = json_encode($cgroup_data, JSON_UNESCAPED_UNICODE);

            $res = update_field('acf_connection_list', $json_list,$group_id);
            
            $cgroup_data = json_decode(get_field('acf_connection_list',$group_id),true);      //関連データ再読み込み
        }
    }

    // 関連保存
    if(isset($_POST['connect_save'])){
        // 続柄保存処理 //新規追加後にacf_connection_listのアップデートができない　ここから0925
        // 変更
        $updata_connection_list = $_POST["relationship_data"];
        $updata_connection_memo = $_POST["add_memo"];

        // 結合後の配列を格納するための配列
        $combined_data = [];

        // どちらの配列も同じキーを持っていることを仮定し、ループして結合
        foreach ($updata_connection_list as $key => $value) {
            // 新しい形式に変換
            $combined_data[$key] = [
                "relationship_data" => $value,
                "add_memo" => $updata_connection_memo[$key]
            ];
        }

        $json_list = json_encode($combined_data, JSON_UNESCAPED_UNICODE);

        $res = update_field('acf_connection_list', $json_list,$group_id);       //追加したユーザーに関連データ追加

        $cgroup_data = json_decode(get_field('acf_connection_list',$group_id),true);      //関連データ再読み込み
    }


    // if($cgroup_data == null){
    //     $cgroup_data = array();
    // }else{

    //     $cgroup_data_array = (array)$cgroup_data;
    // }

    $data_count = 0;

    // 関連ユーザーデータ作成
    // foreach ($cgroup_data_array as $user_id => $data) {
    foreach ($cgroup_data as $user_id => $data) {
        
        $user_group_id = get_user_meta($user_id, 'connect_group', true);    // 個別の関連に追加されていない？

        if($user_group_id != $group_id) continue; 


        $user_data[] = array(
            'ID' => $user_id,
            'user_unique_id' => get_user_meta($user_id, 'user_unique_id', true),
            'first_name' => get_user_meta($user_id, 'first_name', true),
            'last_name' => get_user_meta($user_id, 'last_name', true),
            'group_id' => $user_group_id,
            'connect_name' => $cgroup_data[$user_id]['relationship_data'],
            'add_memo' => $cgroup_data[$user_id]['add_memo']
            // 'connect_name' => $cgroup_data->{$user_id}->relationship_data,
            // 'add_memo' => $cgroup_data->{$user_id}->add_memo
        );

        $data_count++;
    }
    
?>

<?php /* タイトル */?>
    <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">（親族）関連編集</div></div>
    <form id="" action="<?php echo  getURLSetSlag( "admin-member-list" );?>" method="get" >
        <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
        <input type="hidden" name="group_top" value="<?php echo $group_top; ?>">
        <button class="form-btn red">関係一覧に戻る</button>
    </form>

<?php /* エラー */?>
<?php if($new_err != ""){ //エラー ?>
    <div class="admin-spirit-edit-err-etr"><?php echo $new_err;?></div>
<?php } ?>
<?php if($mes != ""){ ?>
    <div class="admin-spirit-edit-err-etr"><?php echo $mes;?></div>
<?php } ?>

<form id="" action="<?php echo  getURLSetSlag( "admin-profile-connection" );?>?new-connection=on" method="post" >
    <input type="hidden" name="add_connection">

    <div class="user-input-area">

        <div class="user-table-flex">
            <div class="user-table-item">代表者</div>
            <div class="user-table-data">
                
                <?php if(isset($group_top) || isset($group_id)){?>
                    <input type="hidden" name="chose_user_id" value="<?php echo $group_top; ?>">
                    <?php 
                        $chose_user_data =  get_userdata($group_top);
                        echo $group_name = $chose_user_data->first_name.$chose_user_data->last_name;
                        
                    ?>
                    <input type="hidden" name="top_name" value="<?php echo $group_name;?>">

                <?php }elseif(!isset($_POST['chose_connect_user'])){?>
                    <input type="hidden" name="chose_connect_user">
                    <a href="<?php echo getURLSetSlag("admin-profile-connection"); ?>?connection_chose=on">

                        <button type="button" id="" class="admin-spirit-return-button" onclick="changeFormActionAndSubmit()">登録者から選択</button>
                    </a>
                <?php } ?>
                
            </div>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">関連名</div>
                <input class="" type="text" name="connection_name" value="<?php if(isset($group_top)) echo $group_name."グループ";?>">
            </div>
        </div>

    <?php /* 新規作成ノミ */?>
    <?php if(!isset($group_id)){?>

    <div class="">＊関連者を追加する場合は、新規作成後、関連一覧の個別の詳細から追加してください</div>

    <button class="form-btn red">作成する</button>
    
    <?php } ?>
</form>


<div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">関係者</div></div>

<form id="" action="<?php echo  getURLSetSlag( "admin-member-list" );?>" method="get" >
    <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
    <input type="hidden" name="chose_id" value="<?php echo $group_top; ?>">
    <input type="hidden" name="group_user_add" value="add">
    <button class="form-btn red">関係者を追加</button>
</form>

<form class=" " id="" action="<?php echo  getURLSetSlag( "admin-profile-connection" )?>?connection_change=on&group_id=<?php echo $_GET['group_id']?>" method="post" >
    <input type="hidden" name="connect_save">
    <input type="hidden" name="chose_id" value="<?php echo $group_top; ?>">
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
            <?php foreach ($user_data as $value) {?>
            <tr>
                <td><?php echo $value['user_unique_id']?></td>
                <td><?php echo $value['last_name']." ".$value['first_name']?></td>
                <td>
                    <select name="relationship_data[<?php echo $value['ID']?>]" id="">
                        <option value="<?php echo ConnectionGroupClass::MINE?>"             <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::MINE) echo "selected" ?>>本人</option>
                        <option value="<?php echo ConnectionGroupClass::HUSBAND?>"          <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::HUSBAND) echo "selected" ?>>夫</option>
                        <option value="<?php echo ConnectionGroupClass::WIFE?>"             <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::WIFE) echo "selected" ?>>妻</option>
                        <option value="<?php echo ConnectionGroupClass::FIRST_SON?>"        <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::FIRST_SON) echo "selected" ?>>長男</option>
                        <option value="<?php echo ConnectionGroupClass::SECOND_SON?>"       <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::SECOND_SON) echo "selected" ?>>次男</option>
                        <option value="<?php echo ConnectionGroupClass::THIRD_SON?>"        <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::THIRD_SON) echo "selected" ?>>三男</option>
                        <option value="<?php echo ConnectionGroupClass::FORTH_SON?>"        <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::FORTH_SON) echo "selected" ?>>四男</option>
                        <option value="<?php echo ConnectionGroupClass::FIRST_DAUGHTER?>"   <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::FIRST_DAUGHTER) echo "selected" ?>>長女</option>
                        <option value="<?php echo ConnectionGroupClass::SECOND_DAUGHTER?>"  <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::SECOND_DAUGHTER) echo "selected" ?>>次女</option>
                        <option value="<?php echo ConnectionGroupClass::THIRD_DAUGHTER?>"   <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::THIRD_DAUGHTER) echo "selected" ?>>三女</option>
                        <option value="<?php echo ConnectionGroupClass::FORTH_DAUGHTER?>"   <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::FORTH_DAUGHTER) echo "selected" ?>>四女</option>
                        <option value="<?php echo ConnectionGroupClass::MOTHER?>"           <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::MOTHER) echo "selected" ?>>母</option>
                        <option value="<?php echo ConnectionGroupClass::FATHER?>"           <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::FATHER) echo "selected" ?>>父</option>
                        <option value="<?php echo ConnectionGroupClass::GROND_MOTHER?>"     <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::GROND_MOTHER) echo "selected" ?>>祖父</option>
                        <option value="<?php echo ConnectionGroupClass::GROND_FATHER?>"     <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::GROND_FATHER) echo "selected" ?>>祖母</option>
                        <option value="<?php echo ConnectionGroupClass::RELATIVES?>"        <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::RELATIVES) echo "selected" ?>>親族</option>
                        <option value="<?php echo ConnectionGroupClass::PARTNER?>"          <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::PARTNER) echo "selected" ?>>パートナー</option>
                        <option value="<?php echo ConnectionGroupClass::OTHER?>"            <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::OTHER) echo "selected" ?>>その他</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="add_memo[<?php echo $value['ID']?>]" id="" value="<?php echo $value['add_memo'] ?>">
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <button class="form-btn ">関係を保存</button>
</form>
<?php /* 
    続柄はどこに保存する？　→　JSONでacf_connection_listに保存、順番に
    追記の保存処理から　0918

*/?>
