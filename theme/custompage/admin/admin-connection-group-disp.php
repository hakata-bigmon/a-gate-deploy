<?php 
    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/ConnectionGroupClass.php");
    $connection_group_data = new ConnectionGroupClass(); //管理データ
    
    YNModalDisp();  //モーダル
    

    if(isset($_POST['chose_id'])){
        $group_top = $_POST['chose_id'];
    }
    if(isset($_GET['chose_id'])){
        $group_top = $_GET['chose_id'];
    }
    
    if(isset($_POST['group_id'])){
        $group_id = $_POST['group_id'];
    }
    if(isset($_GET['group_id'])){
        $group_id = $_GET['group_id'];
    }
    $mes = "";

    $users = get_users();
    $user_data = array();

    $cgroup_data = json_decode(get_field('acf_connection_list',$group_id),true);
    $cgroup_name = $connection_group_data->GetConnectGroupName();
    $res_text = "";


    // グループ一覧読み込み時に関連人数計算するようにする
    $cgroup_data = json_decode(get_field('acf_connection_list',$group_id),true);
    
    // グループから削除
    if(isset($_POST['delete_group_user'])){

        $res = $connection_group_data->deleteConnectGroup($cgroup_data,$group_id,$user_id);

        if($res) $res_text = "関係者を削除しました。";
    }

    // 関連名変更
    
    if(isset($_POST['connection_name']) ){
        
        $res = update_field('acf_connection_top_name', $_POST["connection_name"],$group_id);

    }

    // memo 関係者を追加を押した時点で追加される
    if(isset($_POST['add_connect_user']) || isset($_POST['connect_save'])){

        // 関連追加
        if(isset($_POST['add_connect_user'])){

            $add_flg = false;   //追加成功フラグ

            // チェック入れたユーザー分
            foreach($_POST['add_connect_user'] as $user_id => $value){
                $add_flg = $connection_group_data->addConnectGroup($cgroup_data,$group_id,$user_id);
                $cgroup_data = json_decode(get_field('acf_connection_list',$group_id),true);      //関連データ再読み込み

                if(!$add_flg) break;
            }

            if($add_flg){
                $res_text = "関係者を追加しました";
            }else{
                $res_text = "関係者追加失敗しました";

            }

            $cgroup_data = json_decode(get_field('acf_connection_list',$group_id),true);      //関連データ再読み込み

        }

        // 関連保存
        if(isset($_POST['connect_save'])){
            // 変更
            $updata_connection_list = $_POST["relationship_data"];
            $updata_connection_memo = $_POST["add_memo"];

            // 結合後の配列を格納するための配列
            $combined_data = [];

            // どちらの配列も同じキーを持っていることを仮定し、ループして結合
            // foreach ($updata_connection_list as $key => $value) {
            //     // 新しい形式に変換
            //     $combined_data[$key] = [
            //         "relationship_data" => $value,
            //         "add_memo" => $updata_connection_memo[$key]
            //     ];
            // }

            // 変更のあったユーザーだけ
            $change_id = array_keys($updata_connection_list);
            var_dump($change_id);  //削除okd
            foreach ($cgroup_data as $key => $value) {
                
                // 該当のIDの関係性変更
                foreach ($change_id as $c_id) {

                    if($key == $c_id){
                        $cgroup_data[$key] = [
                            "relationship_data" => $updata_connection_list[$key],
                            "add_memo" => $updata_connection_memo[$key]
                        ];
                    }
                }
                
            }

            // $json_list = json_encode($combined_data, JSON_UNESCAPED_UNICODE);
            $json_list = json_encode($cgroup_data, JSON_UNESCAPED_UNICODE);
            

            // TODO:追加できてない
            $res = update_field('acf_connection_list', $json_list,$group_id);       //追加したユーザーに関連データ追加

            $cgroup_data = json_decode(get_field('acf_connection_list',$group_id),true);      //関連データ再読み込み

            if($res){
                $res_text = "関係を保存しました";
            }else{
                $res_text = "関係の保存に失敗しました";

            }
        }
    }

    // ユーザーデータ作成
    if($cgroup_data != NULL){
        $data_count = 0;

        // 関連ユーザーデータ作成 
        foreach ($cgroup_data as $user_id => $data) {
            
            // $user_group_id = get_user_meta($user_id, 'connect_group', true);
            $user_connection_group_data = $connection_group_data->GetUserConnectGroupData($user_id,$group_id); //ユーザーの関連データ取得

            // if($user_group_id != $group_id) continue; 
            if(!$user_connection_group_data['registed_flg']) continue; 

            $user_data[] = array(
                'ID' => $user_id,
                'user_unique_id' => get_user_meta($user_id, 'user_unique_id', true),
                'first_name' => get_user_meta($user_id, 'first_name', true),
                'last_name' => get_user_meta($user_id, 'last_name', true),
                // 'group_id' => $user_group_id,
                'group_id' => $group_id,
                'connect_name' => $cgroup_data[$user_id]['relationship_data'],
                'add_memo' => $cgroup_data[$user_id]['add_memo']
            );

            $data_count++;
        }
    }

    // 関連グループと実際登録の人間の際があれば修正
    if($cgroup_data != null && count($cgroup_data) != count($user_data)){
        echo "登録の差異あり";

        $connection_group_data->adjustmentConnectGroup($cgroup_data,$user_data,$group_id);
    }


?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="<?php echo get_template_directory_uri()."/custompage/admin/a-gate-script.js"?>"></script>

<div class="admin-exorcism-area">
    <div class="admin-exorcism-button-area">

        <div class="admin-exorcism-menu-title-box">
            <div class="admin-exorcism-menu-title">（親族）関連編集</div>
        </div>
        
        <form id="back-connection" action="<?php echo  getURLSetSlag( "admin-connection-member-list" );?>" method="get" >
            <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
            <input type="hidden" name="group_top" value="<?php echo $group_top; ?>">
            <!-- <button type="button" class="form-btn red" onclick="change_check('back-connection')">関係一覧に戻る</button> -->
             <?php 
                $back_group_name = "関係一覧";
                if(isset($group_id)) $back_group_name = get_field('acf_connection_top_name',$group_id);
             
             ?>
            <button type="button" class="form-btn red" onclick="change_check('back-connection')"><?php echo $back_group_name ?>に戻る</button>
        </form>

        <?php /* エラー */?>
        <?php if($mes != ""){ ?>
            <div class="admin-spirit-edit-err-etr"><?php echo $mes;?></div>
        <?php } ?>

        <!-- <form id="" action="<?php echo  getURLSetSlag( "admin-profile-connection" );?>?new-connection=on" method="post" > -->
        <form id="" action="<?php echo  getURLSetSlag( "admin-connection-group-disp" );?>?group_id=<?php echo $_GET['group_id']; ?>" method="post" >
            <input type="hidden" name="add_connection">

            <div class="user-input-area">

                <div class="user-table-flex">
                    <div class="user-table-item">代表者</div>
                    <div class="user-table-data">
                        
                        <?php if(isset($group_top) || isset($group_id)){?>
                            <input type="hidden" name="chose_id" value="<?php echo $group_top; ?>">
                            <?php 
                                $chose_user_data =  get_userdata($group_top);
                                if($chose_user_data != false){

                                    echo $group_name = $chose_user_data->last_name.$chose_user_data->first_name;
                                }else{
                                    $group_name = "";
                                }
                                
                                $connection_id = get_field('acf_connection_top', $group_id);
                                $group_d = $connection_group_data->GetConnectionGroupDetail($group_id);     //関連グループデータ取得
                                $connection_name = $group_d[0]->post_title;                                 //関連グループタイトル
                                

                                $connect_group_name = get_field('acf_connection_top_name',$group_id);
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
                        <!-- <input class="" type="text" name="connection_name" value="<?php if(isset($group_top)) echo $group_name."グループ";?>"> -->
                        <input class="connection_name_text" type="text" name="connection_name" value="<?php  echo $connect_group_name;?>">
                    </div>
            </div>

            <button class="form-btn red">関連名を保存</button>

        </form>


        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">関係者</div></div>
        

        <form id="add-connection" action="<?php echo  getURLSetSlag( "admin-connection-member-list" );?>" method="get" >
            <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
            <input type="hidden" name="chose_id" value="<?php echo $group_top; ?>">
            <input type="hidden" name="group_user_add" value="add">
            <button type="button" class="form-btn red" onclick="change_check('add-connection')">関係者を追加</button>
        </form>

        <div class="res-text" id="res_text" style="color:red"><?php echo $res_text; ?></div>

        <div class="user-input-area">

        <div class="user-table-flex">
            <div class="user-table-data">ユーザーID</div>
            <div class="user-table-data">名前</div>
            <div class="user-table-data">続柄</div>
            <div class="user-table-data">追記</div>
        </div>
        <form class=" " action="<?php echo  getURLSetSlag( "admin-connection-group-disp" )?>?group_id=<?php echo $group_id?>" method="post" >
            <input type="hidden" name="connect_save">
            <input type="hidden" name="chose_id" value="<?php echo $group_top; ?>">

            <?php foreach ($user_data as $value) {?>
            <div class="user-table-flex">
                <div class="user-table-data"><?php echo $value['user_unique_id']?></div>
                <div class="user-table-data"><?php echo $value['last_name']." ".$value['first_name']?></div>
                <div class="user-table-data">
                    <?php if($value['connect_name'] == ConnectionGroupClass::MINE){ ?>
                        本人
                    <?php }else{ ?>

                    <select class="relationship_data_select" name="relationship_data[<?php echo $value['ID']?>]" id="" >
                        <option value="">未設定</option>
                        <!-- <option value="<?php echo ConnectionGroupClass::MINE?>"             <?php if(isset($value['connect_name']) && $value['connect_name'] == ConnectionGroupClass::MINE) echo "selected" ?>>本人</option> -->
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
                    <?php } ?>
                </div>
                <div class="user-table-data"><input class="add_memo" type="text" name="add_memo[<?php echo $value['ID']?>]" id="" value="<?php echo $value['add_memo'] ?>"></div>
                <div class="edit-mark gray" onclick="click_modal('<?php echo $connection_name;?>から<?php echo $value['last_name'].' '.$value['first_name']?>を削除しますか？','change-connect-group_<?php echo $value['ID']?>')">削除</div>
            </div>
            <?php } ?>
            <button class="form-btn ">関係を保存</button>
        </form>

        <?php foreach ($user_data as $value) {?>
            <form id="change-connect-group_<?php echo $value['ID']?>" action="<?php echo  getURLSetSlag( "admin-connection-group-disp" )?>?group_id=<?php echo $group_id?>&chose_id=<?php echo $group_top?>" method="post" style="display:none">
                <input type="" name="delete_group_user" value="<?php echo $value['ID']?>">';
                
            </form>

        <?php } ?>

    </div>
</div>

<script>

    // それぞれの変更を監視
    const inputElement = document.querySelector('.connection_name_text');   //関連名
    const addElements = document.querySelectorAll('.add_memo');             //追記
    const selectElements = document.querySelectorAll('.relationship_data_select');  //続き柄
    let chage_flg = false;


    inputElement.addEventListener('change', function() {
        // alert('Inputの値が変更されました: ' + inputElement.value);
        chage_flg = true;
    });
    addElements.forEach(function(selectElement) {
        selectElement.addEventListener('change', function() {
            // alert('add_memo: ' + selectElement.value);
            chage_flg = true;
        });
    });

    selectElements.forEach(function(selectElement) {
        selectElement.addEventListener('change', function() {
            // alert('Selectの値が変更されました: ' + selectElement.value);
            chage_flg = true;
        });
    });

    //削除
    function change_check(id) {

        if(chage_flg){

            let res = click_modal('まだ保存されていません。\n保存ボタンを押さない場合は入力した内容が\n削除されるので注意してください。', id);

            // if (window.confirm('まだ保存されていません。保存ボタンを押さない場合は入力した内容が削除されるので注意してください。')) { // 確認ダイアログを表示
            if (res) { // 確認ダイアログを表示
    
                document.getElementById(id).submit();
            }
            else { // 「キャンセル」時の処理
    
                return false; // 送信を中止
    
            }
        }else{
            
            document.getElementById(id).submit();
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const inputField = document.querySelector(".connection_name_text");

        inputField.addEventListener("input", function() {
            chage_flg = true;
            aleartMes();
        });

            // 全ての .relationship_data_select にイベントリスナーを追加
        document.querySelectorAll(".relationship_data_select").forEach(function(select) {
            select.addEventListener("change", function() {
                chage_flg = true;
                aleartMes();
            });
        });

        // すべての .add_memo にイベントリスナーを追加
        document.querySelectorAll(".add_memo").forEach(function(input) {
            input.addEventListener("input", function() {
                let userId = this.name.match(/\d+/)[0]; // name属性からIDを取得
                let newValue = this.value;

                chage_flg = true;
                aleartMes();
            });
        });


    });

    function aleartMes(){
        if(chage_flg){
            document.getElementById('res_text').innerHTML  = "まだ保存されていません。保存ボタンを押さない場合は入力した内容が削除されるので注意してください。";
        }
    }

</script>