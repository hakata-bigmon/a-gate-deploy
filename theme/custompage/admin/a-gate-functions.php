<?php


require_once("a-gate-function-modals.php");         //モーダル
require_once("a-gate-function-user-regist.php");    //登録
require_once("a-gate-function-users-remote.php");   //リモート登録
require_once("a-gate-function-table.php");          //テーブル
require_once("a-gate-function-remote-table.php");   //リモートテーブル
require_once("a-gate-function-disp.php");           //表示検索


//ページURL取得
function getURLSetSlag($slag)
{
    $page = get_page_by_path($slag);

    return esc_url(get_permalink($page->ID));
}

//年齢自動計算
function calculateAge($birthDate)
{
    // 生年月日をDateTimeオブジェクトに変換

    if ($birthDate != "//") {

        $birthDate = DateTime::createFromFormat('Y/m/d', $birthDate);
        // 現在の日付を取得
        $now = new DateTime();
        
        // 年齢を計算
        $age = $now->diff($birthDate);
        // 年齢を返す
        return $age->y;
    }
    return 0;
}


/****************************************************
 **   関連名取得
 ******************************************************/
function getConnectionName($connect_id)
{
    require_once(dirname(__FILE__) . "/../../class/ConnectionGroupClass.php");
    $connection_group_data = new ConnectionGroupClass(); //管理データ
    $cgroup_data = $connection_group_data->GetConnectionGroup();

    foreach ($cgroup_data as $item) {
        if ($item->ID == $connect_id) {
            return $item->post_title;

        }
    }
}

/****************************************************
 **   顧客情報変更処理
 ******************************************************/
function change_user_meta($chage_data, $filed_name, $change_user_id)
{
    if (isset($chage_data) && $chage_data != "") {
        $check = update_user_meta($change_user_id, $filed_name, $chage_data);

        if (!$check)
            return 0;
        else
            return true;    // 更新時に更新フィールドにtrueを戻す
    }
}




/****************************************************
 **   ユーザーメールアドレス取得
 ******************************************************/
function getUserEmails()
{

    $users = get_users();
    $registed_users_email = array();
    // メールアドレスのカウント
    foreach ($users as $user) {
        $registed_users_email[] = $user->user_email;
    }

    return $registed_users_email;
}




/****************************************************
 ** 先生判断
 ******************************************************/
function judgeUserRole()
{
    $login_user_role = getUserRoles();
    $teacher_role = "editor";

    if ($login_user_role[0] == $teacher_role)
        return true;
    else
        return false;
}

/****************************************************
 **  ユーザー権限取得
 ******************************************************/
function getUserRoles()
{

    // 現在ログインしているユーザーの情報を取得
    $current_user = wp_get_current_user();

    // ユーザーのロールを取得
    $user_roles = $current_user->roles;

    return $user_roles;
}
/****************************************************
 **  指定したユーザの権限取得
 ******************************************************/
function getSetUserRoles($user_id)
{

    // 現在ログインしているユーザーの情報を取得
    $current_user = get_userdata($user_id);

    // ユーザーのロールを取得
    $user_roles = $current_user->roles[0];

    return $user_roles;
}
/****************************************************
 **  ユニックスタイム取得
 ******************************************************/
function getUnixTime()
{
    //時間帯を変更
    return time();//ダブり禁止用UNIX TIME 
}

/****************************************************
 **  POST情報有無確認
 ******************************************************/
function CheckPostData($id,$tatget){
    if(isset($_POST[$tatget]) && $_POST[$tatget] != ""){
        // 前回値チェック

        $o_data = get_field($tatget,$id);
        if($o_data != $_POST[$tatget]) {
            return true;
        }
        
    }
    return false;
}

/****************************************************
 **  浄霊質問POST情報有無確認
 ******************************************************/
function CheckSpiritPostData($question_number,$anser){
    if(isset($_POST["question_" .$question_number]) & $_POST["question_" .$question_number]!= ""){
        // 前回値チェック
        $o_ques = nl2br(get_field('acf_questionqnser_text',$anser));

        if($_POST["question_" .$question_number] != $o_ques) {
            return true;
        }
        
    }
    return false;
}

/****************************************************
 **  変更履歴表示
 ******************************************************/
function DispChangeLog($user_id)
{
    // 更新者情報
    $user_edit_day = get_user_meta($user_id, 'user_edit_day', true);    //jsonデータ取得
    if ($user_edit_day != "") {

        $decoded_data = json_decode($user_edit_day, true);  //jsonデータ戻し
        $decoded_data_index = array_keys($decoded_data);
        $max_index = max($decoded_data_index);
        $user_edit_changer = get_updata_user_info($decoded_data[$max_index]);
    }

    ?>
    <div class="admin-spirit-subtitle-box">
        <div class="admin-spirit-menu-title">保存履歴</div>
    </div>

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
            <?php
        }
    }
}

/****************************************************
 **  Unixタイムから調べる
 ******************************************************/
function checkUnix($mail){
    $parts = explode('@', $mail);

    // @より前の部分を取得
    $timestamp = $parts[0];
    
    if (ctype_digit($timestamp) && $timestamp != 0) {
        // 数値としての範囲を確認（Unixタイムの妥当な範囲）
        $minUnixTime = 0; // 最小Unixタイム（1970-01-01 00:00:00 UTC）
        $maxUnixTime = time(); // 現在時刻を取得
        $intValue = (int)$timestamp;

        if($intValue >= $minUnixTime && $intValue <= $maxUnixTime) return true;
        else return false;
    }
    return false;
}

/****************************************************
 **  全ユーザーユニークID取得
 ******************************************************/
function GetUniqueIDs(){
    $users = get_users();
    $registed_users_unique = array();

    // メールアドレスのカウント
    foreach ($users as $user) {
        $deleted = get_user_meta($user->ID, 'is_delete', true);
        if($deleted) continue;

        $unique_id = get_user_meta($user->ID, 'user_unique_id', true);
        $registed_users_unique[$user->ID] = $unique_id;
    }
    return $registed_users_unique;

}

/****************************************************
 **  親アドレス取得
 ******************************************************/
function CheckFamilyMail($regist_mail){
    if (strpos($regist_mail, '_') !== false) {
        return substr($regist_mail, 0, strpos($regist_mail, '_')) . strstr($regist_mail, '@');
    }
    return $regist_mail;

}


/****************************************************
 **  ユーザーページで管理者が指定した場合のGETアドレス
 ******************************************************/
function CheckUserPageAdmin(&$user_num){
    

    $get_url = array();

    $get_url["and"] = "";
    $get_url["add"] = "";

    if(isset($_GET["check_user"]))
    {
        $get_url["and"] = "&check_user=" .$_GET["check_user"];
        $get_url["add"] = "?check_user=" .$_GET["check_user"];
        $user_num = $_GET["check_user"];
    }

    if(isset($_GET["language"]))
    {
        if($get_url["and"] == "")
        {
            $get_url["and"] = "?language=" .$_GET["language"];
        }
        else if($get_url["and"] != "")
        {
            $get_url["and"] = "&language=" .$_GET["language"];
        }

        if($get_url["add"] == "")
        {
            $get_url["add"] = "?language=" .$_GET["language"];
        }
        else if($get_url["add"] != "")
        {
            $get_url["add"] = "&language=" .$_GET["language"];
        }
    }

    return $get_url;

}