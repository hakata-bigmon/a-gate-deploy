<?php

require_once("a-gate-functions.php");
require_once(dirname(__FILE__) . "/../../class/spiritSheetClass.php");

// 流入データ
$spirit_sheet_data = new spiritSheetClass(); //管理データ
$spiritStatusArray = $spirit_sheet_data->getGeneralPurposeData("cpt_inflow");


if (isset($_GET['user_id'])) {
    $check_user_id = $_GET['user_id'];
}

// データ更新
$result_msg = "";
$res = 0;

//保存
if (isset($_POST["do_change_user"])) {
    
    // 変更された項目のみを処理
    $filtered_post_data = $_POST;
    
    if (isset($_POST['changed_fields']) && !empty($_POST['changed_fields'])) {
        $changed_fields = json_decode($_POST['changed_fields'], true);
        
        if ($changed_fields && is_array($changed_fields)) {
            // 変更された項目のみを含む新しい配列を作成
            $filtered_post_data = array();
            
            // 必須フィールドは常に含める
            $filtered_post_data['do_change_user'] = $_POST['do_change_user'];
            
            // 変更された項目のみを追加
            foreach ($changed_fields as $field_name => $field_value) {
                $filtered_post_data[$field_name] = $field_value;
            }
            
            // デバッグ用：変更された項目をログに出力
            error_log("変更された項目のみを処理: " . print_r($changed_fields, true));
        }


        //変更があった
        //タイムゾーン東京
        date_default_timezone_set('Asia/Tokyo');
        $today = date('Y-m-d H:i:s');   
        update_user_meta ($check_user_id, 'last_up_date', $today);
        update_user_meta ($check_user_id, 'last_up_date_id', get_current_user_id());
    }
    
    $res = setChangeUserData($check_user_id, $filtered_post_data);
    if ($res != 0) {

        if (isset($res->errors)) {

            $result_msg = "変更に失敗しました。";

        } else {

            $result_msg = '<div class="result-font-message">変更しました</div>';
        }
    } else {
        $result_msg = "変更に失敗しました。";
    }
}

//var_dump($_POST);

// デバッグ用：POSTデータをログに出力（本番環境では削除推奨）
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log("POST data: " . print_r($_POST, true));
}



$users = get_userdata($check_user_id);

$user_unique_code = get_user_meta($check_user_id, 'user_unique_id', true);
$user_last_name = get_user_meta($check_user_id, 'last_name', true);
$user_first_name = get_user_meta($check_user_id, 'first_name', true);
$user_last_name_kana = get_user_meta($check_user_id, 'last_name_kana', true);
$user_first_name_kana = get_user_meta($check_user_id, 'first_name_kana', true);
$user_sex = get_user_meta($check_user_id, 'sex', true);
$user_auth = get_user_meta($check_user_id, 'user_date_complete', true);
$user_tel1 = get_user_meta($check_user_id, 'billing_phone', true);
$user_tel2 = get_user_meta($check_user_id, 'billing_phone2', true);
$user_tel3 = get_user_meta($check_user_id, 'billing_phone3', true);
$user_none_phone = get_user_meta($check_user_id, 'check_phone_none', true);
$user_zip = get_user_meta($check_user_id, 'billing_postcode', true);
$user_address = get_user_meta($check_user_id, 'billing_city', true);    //フル住所


$user_connect = get_user_meta($check_user_id, 'connect_group', true);// 関連
$user_group_data = json_decode(get_user_meta($check_user_id, 'group_data', true));// グループ

$user_address2 = get_user_meta($check_user_id, 'billing_address_1', true);
$user_line = get_user_meta($check_user_id, 'line_id', true);
$user_born_year = get_user_meta($check_user_id, 'born_year', true);
$user_born_month = get_user_meta($check_user_id, 'born_month', true);
$user_born_day = get_user_meta($check_user_id, 'born_day', true);
$user_report_born_year = get_user_meta($check_user_id, 'report_born_year', true);
$user_report_born_month = get_user_meta($check_user_id, 'report_born_month', true);
$user_report_born_day = get_user_meta($check_user_id, 'report_born_day', true);
$user_inflow = get_user_meta($check_user_id, 'input_inflow', true);
$user_inflow_remarks = get_user_meta($check_user_id, 'inflow_remarks', true);
$user_introduction_id = get_user_meta($check_user_id, 'input_introduction_id', true);
$user_introduction_name = get_user_meta($check_user_id, 'input_introduction_name', true);
$user_customer_role =  getSetUserRoles($check_user_id);









// 更新者情報
$user_edit_day = get_user_meta($check_user_id, 'user_edit_day', true);    //jsonデータ取得


if ($user_edit_day != "") {

    $decoded_data = json_decode($user_edit_day, true);  //jsonデータ戻し
    $decoded_data_index = array_keys($decoded_data);
    $max_index = max($decoded_data_index);
    $user_edit_changer = get_updata_user_info($decoded_data[$max_index]);
}

$user_remarks = get_user_meta($check_user_id, 'user_remarks', true);

// モーダル読み込み
YNModalDisp();

$before_data = get_user_meta($check_user_id, 'user_before_data', true);
$decode_before_data = json_decode($before_data, true);


// バックアップデータ作成
if (isset($_POST['do_back_up'])) {

    $res = setChangeUserData($check_user_id, $_POST);
    if ($res != 0) {

        if (isset($res->errors)) {

            $result_msg = "前日データに戻す事が出来ませんでした";

        } else {

            $result_msg = "前日データ変更完了";
        }
    } else {
        $result_msg = "前日データに戻す事が出来ませんでした";
    }
}
if (isset($_POST['back_up_make'])) {

    echo saveUserData(10);
}

?>

<style>
.admin-profile-card {
    box-shadow:0 2px 12px #b0c4de;
    border-radius:16px;
    background:#fff;
    padding:40px 32px 32px 32px;
    max-width:900px;
    margin:40px auto 60px auto;
}
.admin-profile-edit-title-box {
    margin-bottom:32px;
}
.admin-section-title.admin-spirit-menu-title {
    font-size:2rem;
    color:#234a6f;
    letter-spacing:0.1em;
}
.user-table-flex {
  display: flex;
  align-items: center;
  margin-bottom: 18px;
  padding: 12px 0;
  border-bottom: 1px solid #e0e7ef;
}
.user-table-item {
  min-width: 120px;
  font-weight: bold;
  color: #234a6f;
  font-size: 1rem;
  margin-right: 16px;
}
.user-table-data input,
.user-table-data select,
.user-table-data textarea {
  border-radius: 8px;
  border: 1px solid #b0c4de;
  padding: 8px 12px;
  font-size: 1rem;
  background: #f8fbff;
  margin-right: 8px;
}
.user-table-data {
  flex: 1;
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}
.form-btn, .squeeze-btn, button[type="submit"] {
  background: linear-gradient(90deg, #e3f2fd 0%, #bbdefb 100%);
  color: #1976d2;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: bold;
  box-shadow: 0 2px 6px rgba(0,0,0,0.07);
  cursor: pointer;
  margin: 8px 0;
  transition: background 0.2s, color 0.2s;
}
.form-btn.gray {
  background: #f5f7fa;
  color: #234a6f;
}
.form-btn:hover, .squeeze-btn:hover {
  background: #bbdefb;
  color: #0d47a1;
}
textarea.user-input-remarks {
  width: 100%;
  min-height: 60px;
  border-radius: 8px;
  border: 1px solid #b0c4de;
  padding: 8px 12px;
  background: #f8fbff;
  font-size: 1rem;
}
@media (max-width: 700px) {
  .admin-profile-card {
    padding: 16px 4vw 24px 4vw;
    min-width: 0;
  }
  .user-table-flex {
    flex-direction: column;
    align-items: flex-start;
    padding: 10px 0;
  }
  .user-table-item {
    margin-bottom: 4px;
  }
  .user-table-data {
    width: 100%;
    flex-direction: column;
    gap: 4px;
  }
}
.icon-btn {
  width: 30px;
  height: 30px;
  min-width: 30px;
  min-height: 30px;
  max-width: 30px;
  max-height: 30px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border: none;
  border-radius: 50%;
  font-size: 18px;
  margin: 0 2px;
  cursor: pointer;
  background: #e3f2fd;
  color: #1976d2;
  box-shadow: 0 1px 3px rgba(0,0,0,0.07);
  transition: background 0.2s, color 0.2s;
  text-decoration: none;
  padding: 0;
}
.icon-btn.edit-btn:hover {
  background: #bbdefb;
  color: #0d47a1;
}
.icon-btn.delete-btn {
  background: #ffebee;
  color: #d32f2f;
}
.icon-btn.delete-btn:hover {
  background: #ffcdd2;
  color: #b71c1c;
}
.material-icons {
  font-size: 20px;
}
</style>
<div id="copy-toast" style="display:none;position:fixed;right:32px;bottom:32px;z-index:9999;background:rgba(60,60,60,0.95);color:#fff;padding:16px 32px;border-radius:8px;font-size:18px;box-shadow:0 2px 8px #333;pointer-events:none;transition:opacity 0.3s;opacity:0;"></div>
<script>
function copyToClipboard(text) {
  navigator.clipboard.writeText(text);
  const toast = document.getElementById('copy-toast');
  if (toast) {
    toast.textContent = 'コピーしました！';
    toast.style.display = 'block';
    toast.style.opacity = '1';
    setTimeout(() => {
      toast.style.opacity = '0';
      setTimeout(() => { toast.style.display = 'none'; }, 300);
    }, 1500);
  }
}
</script>
<div class="admin-profile-card">
  <div class="admin-profile-edit-title-box">
    <div class="admin-section-title admin-spirit-menu-title"><?php echo $user_last_name . " " . $user_first_name; ?> プロフィール情報編集</div>
  </div>

    <form action="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $_GET['user_id']; ?>" method="post">
        <input type="hidden" name="back_up_make" value="back_up_make">
        <input type="hidden" name="change_user" value="change_user">
        <button>バックアップデータ作成</button>
    </form>

    <?php /* バックアップデータに戻す */ ?>
    <form action="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $_GET['user_id']; ?>" method="post" id="post_back_user" name="post_back_user">
        <input type="hidden" name="do_back_up" value="">
        <input type="hidden" name="change_user" value="change_user">
        <?php
        if ($decode_before_data != NULL) {

            foreach ($decode_before_data as $key => $value) {
                if ($key == 'user_edit_changer') {
                    $val = htmlspecialchars(json_encode($value), ENT_QUOTES, 'UTF-8');
                } else {
                    $val = $value;
                }
            }
            ?>
            <input type="hidden" name="<?php echo $key ?>" value="<?php echo $val ?>">
        <?php } ?>
    </form>

    <form class="user-input-area" action="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $_GET['user_id']; ?>" method="post" name="post_change_user_input" id="post_change_user_input" onsubmit="return check_change_user()">
        <?php
        $back_url = getURLSetSlag("admin-member-edit") . '?user_id=' . $check_user_id;
        ?>

        <div class="admin-btn-area btn-flex" style="">
            <button type="button" id="form-back" class="form-btn gray" onclick="goCheck('<?php echo $back_url; ?>')" style="width: 220px;height: 40px;background-color: aliceblue;">
                <span class="material-icons" style="vertical-align: middle;margin-right: 4px;">arrow_back</span>
                シートTOPに戻る
            </button>
            

            <?php if ($decode_before_data != NULL) { ?>
                <button type="button" id="form-back" class="form-btn gray" onclick='click_modal("<?php echo $decode_before_data["back_up_date"] ?> 時点のデータに戻ります。\n入力されていた情報は戻ってしまいますが\n宜しいでしょうか？", "post_back_user");'>
                    <span class="material-icons" style="vertical-align: middle;margin-right: 4px;">restore</span>
                    <?php echo $decode_before_data['back_up_date'] ?>の情報に戻す
                </button>
            <?php } ?>
        </div>

        <div class="result-font"><?php echo $result_msg; ?></div>

        <input type="hidden" name="do_change_user" value="do_change_user" id="do_change_user">

        <div class="user-table-flex">
            <div class="user-table-item">ユニークID</div>
            <div class="user-table-data"><?php echo $user_unique_code ?></div>
            <button type="button" class="icon-btn edit-btn" onclick="copyToClipboard('<?php echo $user_unique_code?>')" title="コピー"><span class="material-icons">content_copy</span></button>
        </div>
        <div class="user-table-flex">
            <div class="user-table-item">名前</div>
            <div class="user-table-data">
                <input class="" type="text" name="input_last_name" value="<?php echo $user_last_name ?>">　
                <input class="" type="text" name="input_first_name" value="<?php echo $user_first_name ?>">
            </div>
            <button type="button" class="icon-btn edit-btn" onclick="copyToClipboard('<?php echo $user_last_name . $user_first_name?>')" title="コピー"><span class="material-icons">content_copy</span></button>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">ナマエ</div>
            <div class="user-table-data">
                <input class="" type="text" name="input_last_name_kana" value="<?php echo $user_last_name_kana ?>">　
                <input class="" type="text" name="input_first_name_kana" value="<?php echo $user_first_name_kana ?>">
            </div>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">認証</div>
            <select name="user_date_complete" id="user_date_complete">
                <option value="">未入力</option>
                <option value="1" <?php if ($user_auth == "1") echo "selected"; ?>>申請中</option>
                <option value="2" <?php if ($user_auth == "2") echo "selected"; ?>>認証済</option>
                <option value="3" <?php if ($user_auth == "3") echo "selected"; ?>>再申請</option>
            </select>
        </div>


        <div class="user-table-flex">
            <div class="user-table-item">*性別</div>
            <select name="input_user_sex" id="input_user_sex">
                <option value="">未設定</option>
                <option value="M" <?php if ($user_sex == "M") echo "selected"; ?>>男性</option>
                <option value="W" <?php if ($user_sex == "W") echo "selected"; ?>>女性</option>
                <option value="U" <?php if ($user_sex == "U") echo "selected"; ?>>不明</option>
            </select>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">関連</div>
            <?php

                $group_no = 1;
                if ($connection_id != "" || $connection_id != null) {

                    foreach ($connection_id as $id) {
                        if (array_key_exists($id, $cgroup_data)) {
                            $cgroup_acf_data = json_decode(get_field('acf_connection_list', $id), true);    //続き柄取得

                            // グループ追加時にエラーが出た際の処理
                            //削除okd
                            if(get_current_user_id() == 1){
                                if(!isset($cgroup_acf_data[$user_id])){
                                    // echo "設定エラー";
                                    // var_dump($cgroup_data[$id]);  //削除okd
                                    
                                    // 設定エラー時は削除する
                                    $res = $connection_group_data->DeleteTargetUserConnectGroup($user_id);
                                    
                                    continue;
                                }
                                
                            }


                            $relationship = $connection_group_data->getRelationship($cgroup_acf_data[$user_id]['relationship_data']);
                            if ($relationship == "") {
                                $relationship = "未設定";
                            }
                            // echo $cgroup_data[$id]."　".$connection_group_data->getRelationship($cgroup_acf_data[$user_id]['relationship_data'])."<br>";
                            echo $group_no . ":" . $cgroup_data[$id] . "　続柄:";
                            echo $relationship . "<br>";
                            $group_no++;
                        }
                    }
                }

            ?>
            <button type="button" class="squeeze-btn" onclick="goCheck('<?php echo getURLSetSlag('admin-profile-connection'); ?>')">（親族）関連一覧へ</button>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">グループ</div>
            <select name="input_user_group[]" id="" class="input-group" style="margin-right: 10px;">
                <option value=""></option>
                <?php foreach ($group_list as $key => $value) { ?>
                    <option value="<?php echo $value['ID'] ?>" <?php if (isset($user_group_data) && $value['ID'] == $user_group_data[0]) echo "selected"; ?>><?php echo $value['title']; ?></option>
                <?php } ?>
            </select>
            <select name="input_user_group[]" id="" class="input-group" style="margin-right: 10px;">
                <option value=""></option>
                <?php
                    foreach ($group_list as $key => $value) {
                        if ($value['ID'] == $user_group_data[0])
                            continue;
                ?>
                    <option value="<?php echo $value['ID'] ?>" <?php if (isset($user_group_data) && $value['ID'] == $user_group_data[1])
                           echo "selected"; ?>><?php echo $value['title']; ?></option>
                <?php } ?>
            </select>
            <select name="input_user_group[]" id="" class="input-group">
                <option value=""></option>
                <?php
                foreach ($group_list as $key => $value) {
                    if ($value['ID'] == $user_group_data[0] || $value['ID'] == $user_group_data[1])
                        continue;
                    ?>
                    <option value="<?php echo $value['ID'] ?>" <?php if (isset($user_group_data) && $value['ID'] == $user_group_data[2])
                           echo "selected"; ?>><?php echo $value['title']; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">*連絡先</div>
            <div class="user-table-data">
                <input class=" user-tel-input" type="number" name="input_tel_1" id="" value="<?php echo $user_tel1 ?>">　-　
                <input class=" user-tel-input" type="number" name="input_tel_2" id="" value="<?php echo $user_tel2 ?>">　-　
                <input class=" user-tel-input" type="number" name="input_tel_3" id="" value="<?php echo $user_tel3 ?>">
            </div>
            <button type="button" class="icon-btn edit-btn" onclick="copyToClipboard('<?php echo $user_tel1 . $user_tel2 . $user_tel3?>')" title="コピー"><span class="material-icons">content_copy</span></button>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">電話番号なし</div>
            <input type="checkbox" name="input_check_phone_none" id="input_check_phone_none" <?php if ($user_none_phone)
                echo "checked" ?>>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">*郵便番号</div>
                <input class="" type="number" name="input_post_no" id="zipcode" value="<?php echo $user_zip ?>">
                <button type="button" class="icon-btn edit-btn" onclick="copyToClipboard('<?php echo $user_zip?>')" title="コピー"><span class="material-icons">content_copy</span></button>
                <button type="button" class="form-btn" onclick="getAddress()" style="margin-left: 10px;">住所を取得</button>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">*住所</div>
                <input class="input_address" type="text" name="input_billing_city" id="address" value="<?php echo $user_address ?>">
                <button type="button" class="icon-btn edit-btn" onclick="copyToClipboard('<?php echo $user_address?>')" title="コピー"><span class="material-icons">content_copy</span></button>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">マンション名など</div>
                <input class="input_address" type="text" name="input_address2" id="" value="<?php echo $user_address2 ?>">
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">メールアドレス</div>
                <div class="user-table-data">
                    <?php if (get_user_meta($check_user_id, 'no_mail', true) != "no_mail") { ?>
                        <?php
                            // ユニックスタイムで登録されていれば仮アドレス
                            $pl_mes = getParentAddress($users->user_email);
                            if(checkUnix($users->user_email)){
                                $pl_mes = "仮アドレスで登録中";
                        ?>
                        <input class="" type="email" name="input_user_email" id="input_user_email" placeholder="<?php echo $pl_mes; ?>" style="width: 300px;">
                        <?php }else{ ?>
                            <input class="" type="email" name="input_user_email" id="input_user_email" value="<?php echo $pl_mes; ?>" style="width: 300px;">
                        <?php } ?>
                    <?php } else { ?>


                        <input class="" type="email" name="input_user_email" id="input_user_email" value="<?php  echo $pl_mes;?>" style="width: 300px;">

                    <?php } ?>
                </div>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">LINE ID</div>
                <input class="" type="text" name="input_user_line_id" id="" value="<?php echo $user_line ?>">
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">生年月日</div>
                <input class=" user-born-input" type="number" name="input_user_born_year" id="" value="<?php echo $user_born_year ?>">年
                <input class=" user-born-input" type="number" max="12" min="1" name="input_user_born_month" id="" value="<?php echo $user_born_month ?>">月
                <input class=" user-born-input" type="number" max="31" min="1" name="input_user_born_day" id="" value="<?php echo $user_born_day ?>">日
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">届け出日</div>
                <input class=" user-born-input" type="number" name="input_user_report_year" id="" value="<?php echo $user_report_born_year ?>">年
                <input class=" user-born-input" type="number" max="12" min="1" name="input_user_report_month" id="" value="<?php echo $user_report_born_month ?>">月
                <input class=" user-born-input" type="number" max="31" min="1" name="input_user_report_day" id="" value="<?php echo $user_report_born_day ?>">日
            </div>

            <div class="user-table-flex">
                <div class="user-table-item" class="user-table-item">流入元</div>
                <select name="input_inflow" id="input_inflow">
                    <option value=""></option>
                    <?php

                    foreach ($spiritStatusArray as $key => $value) {
                        ?>
                        <option value="<?php echo $value['ID']; ?>" <?php if ($user_inflow == $value['ID'] || $user_inflow == $value["title"]) echo "selected"; ?>><?php echo $value["title"]; ?></option>

                    <?php } ?>
                </select>
                
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">流入元追記</div>
                <input class="" type="text" name="input_inflow_remarks" id="" value="<?php echo $user_inflow_remarks ?>">
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">紹介者</div>
                <?php
                $change_ingro_flg = false;

                if(isset($_POST['input_introduction_id']) && $_POST['input_introduction_id'] != 'x') {
                    // 紹介者を選択した時
                    $introduction_name = get_user_meta($_POST['input_introduction_id'], 'last_name', true) . " " . get_user_meta($_POST['input_introduction_id'], 'first_name', true);
                    echo 'ID' . get_user_meta($_POST['input_introduction_id'], 'user_unique_id', true) . '：' . $introduction_name."<br>";
                    
                    // 紹介者変更中表示
                    $change_ingro_flg = true;
                    if ($res != 0 && !isset($res->errors)) {
                        $change_ingro_flg = false;
                    }
                ?>
                    <input type="hidden" name="input_introduction_id" value="<?php echo $_POST['input_introduction_id']; ?>">
                    <input type="hidden" name="input_introduction_name" value="<?php echo $introduction_name; ?>">
                <?php 
                }else if (isset($_POST['use_old_regist_id'])) {
                    $introduction_name = get_user_meta($user_introduction_id, 'last_name', true) . " " . get_user_meta($user_introduction_id, 'first_name', true);
                    echo 'ID' . get_user_meta($user_introduction_id, 'user_unique_id', true) . '：' . $introduction_name;
                    ?>
                    <input type="hidden" name="input_introduction_id" value="<?php echo $_POST['input_introduction_id']; ?>">
                    <input type="hidden" name="input_introduction_name" value="<?php echo $introduction_name; ?>">
                    <?php
                } else {
                    if ($user_introduction_id == 'x') {

                        echo $user_introduction_name;
                    
                    } else {
                        $disp_user_intro_id = get_user_meta($user_introduction_id, 'user_unique_id', true);
                        if ($disp_user_intro_id != "") {
                            echo "ID" . $disp_user_intro_id. '：';
                            echo get_user_meta($user_introduction_id, 'last_name', true);
                            echo get_user_meta($user_introduction_id, 'first_name', true);
                        }else{
                            echo get_user_meta($user_id, 'input_introduction_name', true);
                        }
                    }
                }
                ?>

            <button type="button" class="squeeze-btn" onclick="change_form_action()" style="margin-left: 30px;">登録者から選択</button>
            
            <?php if( $change_ingro_flg){?>
                    <div class="" style="color:red"> 登録者変更中</div>
            <?php } ?>

            </div>

            <?php if (!isset($_POST['use_old_regist_id'])) { ?>
                <div class="user-table-flex">
                    <div class="user-table-item"></div>

                    <input type="text" name="input_introduction_name" id="" value="">
                    <div class="no_introduction"> 登録者でない場合はこちらに入力してください</div>

                    <input type="hidden" name="input_introduction_id" value="x">

                </div>
            <?php } ?>

            <div class="user-table-flex">
                <div class="user-table-item">登録日</div>
                <!-- <?php echo $users->user_registered ?> -->

                <?php

                $date = new DateTime($users->user_registered, new DateTimeZone('UTC'));
                $regist_date = $date->setTimezone(new DateTimeZone('Asia/Tokyo'));

                ?>
                <?php echo $regist_date->format('Y-m-d H:i:s') ?>

            </div>

            <div class="user-table-flex">
                <div class="user-table-item">最終更新日</div>
                <?php
                if ($user_edit_day != "") {

                    echo $decoded_data[$max_index]['edit_date'] . '  ' . $user_edit_changer;
                }

                ?>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">特記事項</div>
                <textarea class=" user-input-remarks" name="input_remarks" id=""><?php echo $user_remarks ?></textarea>
            </div>


            <div class="user-table-flex">
                <div class="user-table-item" class="user-table-item">顧客権限</div>
                <select name="input_customer_role" id="input_customer_role">
                    <option value="subscriber" <?php if ($user_customer_role == "subscriber") echo "selected"; ?>>通常</option>
                    <option value="author" <?php if ($user_customer_role == "author") echo "selected"; ?>>電話相談スタッフ</option>
                </select>
                
            </div>


            <button type="submit" id="form-save" class="form-btn" style="max-width: 500px;width: 100%;margin-left: auto;margin-right: auto;margin-top: 60px;">
                <span class="material-icons" style="vertical-align: middle;margin-right: 4px;">save</span>
                保存する
            </button>
        </form>

        <div class="save-log-area">

            <div class="admin-title">保存履歴</div>

            <?php if ($user_edit_day != "") { ?>

                <div class="">
                    <?php

                    $max_flg = false;   //履歴が5件以上あるならボタン表示
                    for ($i = $max_index; $i >= 0; $i--) {
                        if ($i + 5 <= $max_index) {
                            $max_flg = true;
                            break;
                        }

                        $update_list = $decoded_data[$i]['edit_date'] . '　　' . get_updata_user_info($decoded_data[$i]);

                        foreach ($decoded_data[$i]['edit_content'] as $key => $value) {
                            $update_list .= '　　' . $key . '変更';
                        }
                        echo $update_list . "<br>";
                    }
                    ?>

                </div>
                <?php if ($max_flg) { ?>
                    <button type="button" id="form-back" class="form-btn gray"><a href="<?php echo getURLSetSlag("admin-member-edit") . '?user_id=' . $check_user_id . '&all_change_list=on'; ?>" target="_blank">変更履歴をすべて見る</a></button>
                <?php } ?>
            </div>
        <?php } else { ?>

        <?php } ?>
    </div>
</div>
<script>
    // 元の値を保存するオブジェクト
    let originalValues = {};
    
    // ページ読み込み時に元の値を保存
    document.addEventListener('DOMContentLoaded', function() {
        saveOriginalValues();
    });
    
    // 元の値を保存する関数
    function saveOriginalValues() {
        const form = document.getElementById('post_change_user_input');
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(function(input) {
            // name属性がない場合はスキップ
            if (!input.name) return;
            
            // 配列形式のname属性（input_user_group[]など）を特別に処理
            if (input.name.endsWith('[]')) {
                if (!originalValues[input.name]) {
                    originalValues[input.name] = [];
                }
                originalValues[input.name].push(input.value);
            } else if (input.type === 'checkbox') {
                originalValues[input.name] = input.checked;
            } else if (input.type === 'radio') {
                if (input.checked) {
                    originalValues[input.name] = input.value;
                }
            } else {
                originalValues[input.name] = input.value;
            }
        });
        
        console.log('元の値を保存しました:', originalValues);
        
        // デバッグ用：input_user_group[]の値を特別に確認
        const groupInputs = form.querySelectorAll('select[name="input_user_group[]"]');
        console.log('input_user_group[]の要素数:', groupInputs.length);
        groupInputs.forEach(function(input, index) {
            console.log(`input_user_group[${index}]: 値="${input.value}", 選択肢="${input.selectedIndex}"`);
        });
    }
    
    // 変更があった項目のみをフィルタリングする関数
    function filterChangedFields() {
        const form = document.getElementById('post_change_user_input');
        const inputs = form.querySelectorAll('input, select, textarea');
        const changedFields = {};
        
        // 配列形式のname属性を処理するための一時的なオブジェクト
        const currentArrayValues = {};
        
        inputs.forEach(function(input) {
            // name属性がない場合はスキップ
            if (!input.name) return;
            
            let currentValue;
            
            if (input.type === 'checkbox') {
                currentValue = input.checked;
            } else if (input.type === 'radio') {
                currentValue = input.checked ? input.value : '';
            } else {
                currentValue = input.value;
            }
            
            // 配列形式のname属性（input_user_group[]など）を特別に処理
            if (input.name.endsWith('[]')) {
                if (!currentArrayValues[input.name]) {
                    currentArrayValues[input.name] = [];
                }
                currentArrayValues[input.name].push(currentValue);
            } else {
                // 通常のフィールドの比較
                if (originalValues[input.name] !== undefined && originalValues[input.name] !== currentValue) {
                    changedFields[input.name] = currentValue;
                    console.log(`変更検出: ${input.name} - 元: "${originalValues[input.name]}" → 新: "${currentValue}"`);
                }
            }
        });
        
        // 配列形式のフィールドの比較
        for (let fieldName in currentArrayValues) {
            if (originalValues[fieldName] && Array.isArray(originalValues[fieldName])) {
                // 配列の内容を比較
                const originalArray = originalValues[fieldName];
                const currentArray = currentArrayValues[fieldName];
                
                // 配列の長さが違う場合は変更あり
                if (originalArray.length !== currentArray.length) {
                    changedFields[fieldName] = currentArray;
                    console.log(`変更検出（配列長）: ${fieldName} - 元: [${originalArray.join(', ')}] → 新: [${currentArray.join(', ')}]`);
                } else {
                    // 各要素を比較
                    let hasChange = false;
                    for (let i = 0; i < originalArray.length; i++) {
                        if (originalArray[i] !== currentArray[i]) {
                            hasChange = true;
                            break;
                        }
                    }
                    if (hasChange) {
                        changedFields[fieldName] = currentArray;
                        console.log(`変更検出（配列内容）: ${fieldName} - 元: [${originalArray.join(', ')}] → 新: [${currentArray.join(', ')}]`);
                    }
                }
            }
        }
        
        return changedFields;
    }
    
    // フォーム送信前の処理
    function check_change_user() {
        const changedFields = filterChangedFields();
        
        if (Object.keys(changedFields).length === 0) {
            alert('変更がありません。');
            return false;
        }
        
        console.log('変更された項目:', changedFields);
        
        // 変更があった項目のみをhiddenフィールドとして追加
        const form = document.getElementById('post_change_user_input');
        
        // 既存のchanged_fieldsフィールドを削除
        const existingChangedFields = form.querySelector('input[name="changed_fields"]');
        if (existingChangedFields) {
            existingChangedFields.remove();
        }
        
        // 変更された項目をJSONで送信
        const changedFieldsInput = document.createElement('input');
        changedFieldsInput.type = 'hidden';
        changedFieldsInput.name = 'changed_fields';
        changedFieldsInput.value = JSON.stringify(changedFields);
        form.appendChild(changedFieldsInput);
        
        // デバッグ用：変更された項目を表示
        const debugInfo = document.createElement('div');
        debugInfo.style.cssText = 'position:fixed;top:10px;right:10px;background:rgba(0,0,0,0.8);color:white;padding:10px;border-radius:5px;z-index:10000;max-width:300px;font-size:12px;';
        debugInfo.innerHTML = '<strong>変更された項目:</strong><br>' + 
            Object.keys(changedFields).map(key => `${key}: "${changedFields[key]}"`).join('<br>');
        document.body.appendChild(debugInfo);
        
        // 3秒後にデバッグ情報を削除
        setTimeout(() => {
            if (debugInfo.parentNode) {
                debugInfo.parentNode.removeChild(debugInfo);
            }
        }, 3000);
        
        return true;
    }
    
    // 紹介者ボタン押下時URL変更
    function change_form_action() {
        document.getElementById("post_change_user_input").action = '<?php echo getURLSetSlag("admin-member-list") . '?introduction_member=on&user_change=on&user_id=' . $check_user_id; ?>';
        document.getElementById("do_change_user").remove();
        document.getElementById("post_change_user_input").submit();
    }
</script>