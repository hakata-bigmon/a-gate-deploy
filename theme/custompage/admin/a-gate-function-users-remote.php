<?php 


/****************************************************
 **   リモート浄霊用簡易情報ユーザー登録
 ******************************************************/
function setRemoteUserRegist($post_data)
{

    $last_name = $post_data["input_last_name"];     //姓
    $first_name = $post_data["input_first_name"];   //名前

    $current_unix_time = time();
    $mail_add = $current_unix_time . "@gmail.com";

    //ログインネーム
    $login_name = $mail_add;

    //初回パスワードを作成
    $init_pass = 11111; //ログインは不要なので


    $userdata = array(
        'user_login' => $login_name,
        'user_email' => $mail_add,
        'user_pass' => $init_pass,  // ユーザーを登録するとき、`user_pass` が必要。
        'first_name' => $first_name, //ユーザーの名前（姓名の名）。
        'last_name' => $last_name, //ユーザーの姓。
        'role' => 'subscriber',//権限
    );

    //作成
    $user_id = wp_insert_user($userdata);


    // 暗号ID作成
    $users = get_userdata($user_id);
    $regist_timestamp = strtotime($users->user_registered);
    $paddedData = str_pad($users->ID, 5, '0', STR_PAD_LEFT);
    $user_unique_id = $regist_timestamp . $paddedData;
    update_user_meta($user_id, 'user_unique_id', $user_unique_id);

    // 生年月日とヨミガナだけ更新
    update_user_meta($user_id, 'born_year', $post_data['input_user_born_year']);
    update_user_meta($user_id, 'born_month', $post_data['input_user_born_month']);
    update_user_meta($user_id, 'born_day', $post_data['input_user_born_day']);


    //姓名（よみ）
    update_user_meta($user_id, 'last_name_kana', $post_data['input_last_name_kana']);
    update_user_meta($user_id, 'first_name_kana', $post_data['input_first_name_kana']);


    return $user_id;
}
/****************************************************
 **   リモート浄霊用簡易情報ユーザー登録2
登録に必要な情報
    ■名前（姓）＊ふりがななし
    ■名前（名）＊ふりがななし
    ■郵便番号（ハイフン無し）
    ■住所
    ■メールアドレス
    ■電話番号
 ******************************************************/
function setRemoteUserRegistMiddle($post_data)
{

    $last_name = $post_data["input_last_name"];     //姓
    $first_name = $post_data["input_first_name"];   //名前
    $mail_add = $post_data["input_user_email"];//メール

    //ログインネーム
    $login_name = $mail_add;

    //初回パスワードを作成
    $init_pass = 11111; //ログインは不要なので


    $userdata = array(
        'user_login' => $login_name,
        'user_email' => $mail_add,
        'user_pass' => $init_pass,  // ユーザーを登録するとき、`user_pass` が必要。
        'first_name' => $first_name, //ユーザーの名前（姓名の名）。
        'last_name' => $last_name, //ユーザーの姓。
        'role' => 'subscriber',//権限
    );

    //作成
    $user_id = wp_insert_user($userdata);


    // 暗号ID作成
    $users = get_userdata($user_id);
    $regist_timestamp = strtotime($users->user_registered);
    $paddedData = str_pad($users->ID, 5, '0', STR_PAD_LEFT);
    $user_unique_id = $regist_timestamp . $paddedData;
    update_user_meta($user_id, 'user_unique_id', $user_unique_id);

    // 連絡先
    if (isset($post_data['input_tel_1'])) {
        update_user_meta($user_id, 'billing_phone', $post_data['input_tel_1']);
        update_user_meta($user_id, 'billing_phone2', $post_data['input_tel_2']);
        update_user_meta($user_id, 'billing_phone3', $post_data['input_tel_3']);
    } else {

        update_user_meta($user_id, 'check_phone_none', true);
    }

    // 郵便番号
    if (isset($post_data['input_post_no'])) {

        update_user_meta($user_id, 'billing_postcode', $post_data['input_post_no']);//郵便番号
    } else if (isset($post_data['input_post_no_1'])) {
        $post_no = $post_data['input_post_no_1'] . $post_data['input_post_no_2'];
        update_user_meta($user_id, 'billing_postcode', $post_no);//郵便番号
    }

    //住所
    if (isset($post_data['input_address1'])) {
        update_user_meta($user_id, 'billing_city', $post_data['input_address1']);
    }


    return $user_id;
}


?>