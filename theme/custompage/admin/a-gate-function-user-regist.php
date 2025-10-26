<?php 


/****************************************************
 **   家族アドレス取得
 ******************************************************/
function getParentAddress($check_email)
{

    $search = "_#&";   //家族記号
    // 家族なら親のメールアドレスを表示させる
    if (strpos($check_email, $search) !== false) {

        list($localPart, $domain) = explode('@', $check_email);
        $localPart = explode($search, $localPart);
        // $user_regitsted_mail = $localPart[0] . '@' . $domain;
        
        $user_regitsted_mail = "親アドレス<br>".$localPart[0] . '@' . $domain;

    } else {
        $user_regitsted_mail = $check_email;
    }
    return $user_regitsted_mail;
}

/****************************************************
 **   ユーザー情報の新規登録
 ******************************************************/
function setNewUserData($post_data, $same_address = "")
{
    $last_name = $post_data["input_last_name"];     //姓
    $first_name = $post_data["input_first_name"];   //名前
    $no_mail = false;

    if ($post_data["input_user_email"] == "") {
        $current_unix_time = time();
        $mail_add = $current_unix_time . "@gmail.com";
        $no_mail = true;
    } else {
        $mail_add = $post_data["input_user_email"];//メール
    }

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
    
    $user_id = check_and_insert_user($userdata);
    
    if (!isset($user_id->errors) && $user_id != "") {

        $users = get_userdata($user_id);
        $regist_timestamp = strtotime($users->user_registered);
        $paddedData = str_pad($users->ID, 5, '0', STR_PAD_LEFT);
        $user_unique_id = $regist_timestamp . $paddedData;

        // 暗号ID作成
        update_user_meta($user_id, 'user_unique_id', $user_unique_id);

        //姓名（よみ）
        update_user_meta($user_id, 'last_name_kana', $post_data['input_last_name_kana']);
        update_user_meta($user_id, 'first_name_kana', $post_data['input_first_name_kana']);

        // 性別
        if (isset($post_data['input_user_sex'])) {
            change_user_meta($post_data['input_user_sex'], 'sex', $user_id);
        }
        if (isset($post_data['input_sex'])) {
            change_user_meta($post_data['input_sex'], 'sex', $user_id);
        }

        // 連絡先
        if (isset($post_data['input_tel_1'])) {
            update_user_meta($user_id, 'billing_phone', $post_data['input_tel_1']);
            update_user_meta($user_id, 'billing_phone2', $post_data['input_tel_2']);
            update_user_meta($user_id, 'billing_phone3', $post_data['input_tel_3']);
        } else {

            update_user_meta($user_id, 'check_phone_none', true);
        }

        // LINEで登録してメアドがない場合の処理
        if ($no_mail) {

            update_user_meta($user_id, 'no_mail', true);
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
        // マンション
        if (isset($post_data['input_address2'])) {
            update_user_meta($user_id, 'billing_address_1', $post_data['input_address2']);//マンション
        }

        //LINE　ID
        if (isset($post_data['input_user_line_id'])) {
            update_user_meta($user_id, 'line_id', $post_data['input_user_line_id']);
        }

        //流入元
        if (isset($post_data['input_inflow'])) {
            update_user_meta($user_id, 'input_inflow', $post_data['input_inflow']);
        }
        if (isset($post_data['input_inflow_remarks'])) {
            update_user_meta($user_id, 'inflow_remarks', $post_data['input_inflow_remarks']);//流入元備考
        }

        update_user_meta($user_id, 'input_introduction_name', $post_data['input_introduction_name']);//紹介者名
        update_user_meta($user_id, 'input_introduction_id', $post_data['input_introduction_id']);//紹介者ID

        //特記事項
        if (isset($post_data['input_remarks'])) {
            change_user_meta($post_data['input_remarks'], 'user_remarks', $user_id);

        }

        // 生年月日
        if (isset($post_data['input_user_born'])) {
            $date = new DateTime($post_data['input_user_born']);
            $year = $date->format('Y'); // 年を取得
            $month = $date->format('m'); // 月を取得
            $day = $date->format('d'); // 日を取得

            update_user_meta($user_id, 'born_year', $year);
            update_user_meta($user_id, 'born_month', $month);
            update_user_meta($user_id, 'born_day', $day);
        } else {
            update_user_meta($user_id, 'born_year', $post_data['input_user_born_year']);
            update_user_meta($user_id, 'born_month', $post_data['input_user_born_month']);
            update_user_meta($user_id, 'born_day', $post_data['input_user_born_day']);
        }

        // 届け出生年月日
        if (isset($post_data['input_user_report_year'])) {

            update_user_meta($user_id, 'report_born_year', $post_data['input_user_report_year']);
            update_user_meta($user_id, 'report_born_month', $post_data['input_user_report_month']);
            update_user_meta($user_id, 'report_born_day', $post_data['input_user_report_day']);
        }

        return $user_id;
    }else{
        return "そのアドレスは既に使用されています。";
    }
}


/****************************************************
 **   ユーザー登録確認
 ******************************************************/
function check_and_insert_user($user_data) {
    // ユーザー名の存在確認
    // if (username_exists($user_data['user_login'])) {
    //     return new WP_Error('existing_user_login', 'ユーザー名またはメールアドレスはすでに使用されています。');
    // }

    // // メールアドレスの存在確認
    // if (email_exists($user_data['user_email'])) {
    //     return new WP_Error('existing_user_email', 'メールアドレスはすでに使用されています。');
    // }

    // ユーザーを登録
    $result = wp_insert_user($user_data);

    if (is_wp_error($result)) {
        return $result; // エラーの場合は返す
    }

    return $result; // 成功時はユーザーIDを返す
}

/****************************************************
 **   ユーザー性別取得
 ******************************************************/
function getUserSex($sex)
{
    $ret = "";
    if ($sex == "M") {
        $ret = "男(M)";
    } else if ($sex == "W") {
        $ret = "女(W)";
    } else {

    }

    return $ret;
}

/****************************************************
 **   ユーザー情報取得
 ******************************************************/
function getUsersData($check_user_id)
{

    $data = array();
    $born_year = get_user_meta($check_user_id, 'born_year', true) . '/' . get_user_meta($check_user_id, 'born_month', true) . '/' . get_user_meta($check_user_id, 'born_day', true);


    $users = get_userdata($check_user_id);
    $data['input_user_registed'] = $users->user_registered;
    $data['input_user_email'] = $users->user_email;
    $data['input_user_id'] = $users->ID;

    $data['input_user_age'] = calculateAge($born_year);

    $data['input_unique_id'] = get_user_meta($check_user_id, 'user_unique_id', true);
    $data['input_last_name'] = get_user_meta($check_user_id, 'last_name', true);
    $data['input_first_name'] = get_user_meta($check_user_id, 'first_name', true);
    $data['input_last_name_kana'] = get_user_meta($check_user_id, 'last_name_kana', true);
    $data['input_first_name_kana'] = get_user_meta($check_user_id, 'first_name_kana', true);
    $data['input_user_sex'] = get_user_meta($check_user_id, 'sex', true);
    $data['input_tel_1'] = get_user_meta($check_user_id, 'billing_phone', true);
    $data['input_tel_2'] = get_user_meta($check_user_id, 'billing_phone2', true);
    $data['input_tel_3'] = get_user_meta($check_user_id, 'billing_phone3', true);
    $data['input_check_phone_none'] = get_user_meta($check_user_id, 'check_phone_none', true);
    $data['input_post_no'] = get_user_meta($check_user_id, 'billing_postcode', true);
    $data['input_billing_city'] = get_user_meta($check_user_id, 'billing_city', true);    //フル住所

    $data['input_address2'] = get_user_meta($check_user_id, 'billing_address_1', true);
    $data['input_user_line_id'] = get_user_meta($check_user_id, 'line_id', true);
    $data['input_user_born_year'] = get_user_meta($check_user_id, 'born_year', true);
    $data['input_user_born_month'] = get_user_meta($check_user_id, 'born_month', true);
    $data['input_user_born_day'] = get_user_meta($check_user_id, 'born_day', true);
    $data['input_user_report_year'] = get_user_meta($check_user_id, 'report_born_year', true);
    $data['input_user_report_month'] = get_user_meta($check_user_id, 'report_born_month', true);
    $data['input_user_report_day'] = get_user_meta($check_user_id, 'report_born_day', true);
    $data['input_inflow'] = get_user_meta($check_user_id, 'input_inflow', true);
    $data['input_inflow_remarks'] = get_user_meta($check_user_id, 'inflow_remarks', true);
    $data['input_introduction_id'] = get_user_meta($check_user_id, 'input_introduction_id', true);
    $data['input_introduction_name'] = get_user_meta($check_user_id, 'input_introduction_name', true);
    $data['input_connect_group'] = get_user_meta($check_user_id, 'connect_group', true);

    // 更新者情報 
    $existing_data_json = get_user_meta($check_user_id, 'user_edit_day', true);    //jsonデータ取得
    if ($existing_data_json) {
        $existing_data = json_decode($existing_data_json, JSON_UNESCAPED_UNICODE);
        if (!is_array($existing_data)) {
            $existing_data = array();
        }
    } else {
        $existing_data = array();
    }
    $data['user_edit_changer'] = $existing_data;



    $data['input_remarks'] = get_user_meta($check_user_id, 'user_remarks', true);

    return $data;
}

/****************************************************
 **   ユーザー情報のバックアップ
 ******************************************************/
function saveUserData($user_id)
{
    require_once(dirname(__FILE__) . "/../../class/spiritSheetClass.php");
    $spirit_sheet_data = new spiritSheetClass(); //管理データ

    $data = array();
    $data['acf_purespirit_requested_date'] = get_field('acf_purespirit_requested_date', $_GET["sheet_name"]);
    $data['acf_purespirit_request_confirmation_date'] = get_field('acf_purespirit_requested_date', $_GET["sheet_name"]);
    $data['acf_purespirit_payment_date'] = get_field('acf_purespirit_payment_date', $_GET["sheet_name"]);
    $data['acf_purespirit_execution_date'] = get_field('acf_purespirit_execution_date', $_GET["sheet_name"]);
    $data['acf_purespirit_status'] = get_field('acf_purespirit_status', $_GET["sheet_name"]);
    $data['acf_purespirit_status'] = get_field('acf_applicant', $_GET["sheet_name"]);    //申込者
    $data['acf_purespirit_price'] = get_field('acf_purespirit_price', $_GET["sheet_name"]);//単価
    $data['acf_agate_hands_on'] = get_field('acf_agate_hands_on', $_GET["sheet_name"]);  //a-gate実施
    $data['acf_agate_process_execution'] = get_field('acf_agate_process_execution', $_GET["sheet_name"]);  //処理実行日
    $data['acf_agate_result_on'] = get_field('acf_agate_result_on', $_GET["sheet_name"]);  //結果レポ格納
    $data['acf_agate_e_mark_send'] = get_field('acf_agate_e_mark_send', $_GET["sheet_name"]);  //Eマーク送付
    $data['acf_agate_result_report_send'] = get_field('acf_agate_result_report_send', $_GET["sheet_name"]);  //結果レポ送付
    $data['acf_agate_add_text'] = nl2br(get_field('acf_agate_add_text', $_GET["sheet_name"]));  //補足

    // 質問事項
    $type = get_field('acf_acf_purespirit_type', $_GET["sheet_name"]);
    //現在の質問番号を取得
    $spiritQuestionArray = $spirit_sheet_data->getSpiritQuestion($type);
    //回答配列を取得
    $anser = $spirit_sheet_data->getSpiritSheetAnswer($_GET["sheet_name"]);

    $timezone = new DateTimeZone('Asia/Tokyo');
    $now = new DateTime('now', $timezone);
    $data['back_up_date'] = $now->format('Y-m-d H:i:s');
    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $check = change_user_meta($data, 'user_before_data', $user_id);

    if (!$check)
        return 0;

}

/****************************************************
 **   ユーザー情報の更新
 ******************************************************/
function setChangeUserData($user_id, $post_data)
{
    if ($user_id) {
        $update_field = array();

        //姓名
        $update_field['苗字'] = change_user_meta($post_data['input_last_name'], 'last_name', $user_id);
        $update_field['名前'] = change_user_meta($post_data['input_first_name'], 'first_name', $user_id);

        //姓名（よみ）
        $update_field['苗字フリガナ'] = change_user_meta($post_data['input_last_name_kana'], 'last_name_kana', $user_id);
        $update_field['名前フリガナ'] = change_user_meta($post_data['input_first_name_kana'], 'first_name_kana', $user_id);

        // 性別
        if (isset($post_data['input_user_sex'])) {
            $update_field['性別'] = change_user_meta($post_data['input_user_sex'], 'sex', $user_id);
        }else if(isset($post_data['input_sex'])) {
            $update_field['性別'] = change_user_meta($post_data['input_sex'], 'sex', $user_id);
        }

        if (isset($post_data['input_user_group'])) {
            //グループ
            $group_data = json_encode($post_data['input_user_group']);
            if($group_data != '["","",""]' ){

                if(change_user_meta($group_data, 'group_data', $user_id)){
    
                    $update_field['グループ'] = true;
                }
            }
        }

        // 連絡先
        $update_field['連絡先'] = change_user_meta($post_data['input_tel_1'], 'billing_phone', $user_id);
        $update_field['連絡先'] = change_user_meta($post_data['input_tel_2'], 'billing_phone2', $user_id);
        $update_field['連絡先'] = change_user_meta($post_data['input_tel_3'], 'billing_phone3', $user_id);

        //電話番号無し
        if (isset($post_data['input_check_phone_none'])) {
            $update_field['電話番号無し'] = change_user_meta($post_data['input_check_phone_none'], 'check_phone_none', $user_id);
        }

        // 郵便番号
        if(isset($post_data['input_post_no'])){

            $update_field['郵便番号'] = change_user_meta($post_data['input_post_no'], 'billing_postcode', $user_id);
        }else if(isset($post_data['input_post_no_1'])){
            $update_field['郵便番号'] = change_user_meta($post_data['input_post_no_1'].$post_data['input_post_no_2'], 'billing_postcode', $user_id);
        }

        // 住所
        if(isset($post_data['input_billing_city'])){

            $update_field['住所'] = change_user_meta($post_data['input_billing_city'], 'billing_city', $user_id);
        }else if(isset($post_data['input_address1'])){
            $update_field['住所'] = change_user_meta($post_data['input_address1'], 'billing_city', $user_id);

        }
        $update_field['マンション名など'] = change_user_meta($post_data['input_address2'], 'billing_address_1', $user_id);

        //メールアドレスの変更
        if (isset($post_data['input_user_email']) && $post_data['input_user_email'] != "" && get_the_author_meta('user_email', $user_id) != $post_data['input_user_email']) {
            $change_mail = wp_update_user([
                'ID' => $user_id,
                'user_email' => $post_data['input_user_email'],
            ]);

            if ($change_mail != $user_id)
                return 0;
            else {

                $update_field['メールアドレス'] = true;
                change_user_meta(0, 'no_mail', $user_id);

            }
        }

        // LINE ID
        $update_field['LINE　ID'] = change_user_meta($post_data['input_user_line_id'], 'line_id', $user_id);

        // 生年月日
        if(isset($post_data['input_user_born_year'])){

            $update_field['生年月日　年'] = change_user_meta($post_data['input_user_born_year'], 'born_year', $user_id);
            $update_field['生年月日　月'] = change_user_meta($post_data['input_user_born_month'], 'born_month', $user_id);
            $update_field['生年月日　日'] = change_user_meta($post_data['input_user_born_day'], 'born_day', $user_id);
        }else if (isset($post_data['input_user_born'])) {
            $date = new DateTime($post_data['input_user_born']);
            $year = $date->format('Y'); // 年を取得
            $month = $date->format('m'); // 月を取得
            $day = $date->format('d'); // 日を取得

            $update_field['生年月日　年'] = change_user_meta($year, 'born_year', $user_id);
            $update_field['生年月日　月'] = change_user_meta($month, 'born_month', $user_id);
            $update_field['生年月日　日'] = change_user_meta($day, 'born_day', $user_id);
        }

        // 届け出日
        if (isset($post_data['input_user_report_year'])) {

            $update_field['届け出日　年'] = change_user_meta($post_data['input_user_report_year'], 'report_born_year', $user_id);
            $update_field['届け出日　月'] = change_user_meta($post_data['input_user_report_month'], 'report_born_month', $user_id);
            $update_field['届け出日　日'] = change_user_meta($post_data['input_user_report_day'], 'report_born_day', $user_id);
        }if (isset($post_data['input_user_report'])) {
            $date = new DateTime($post_data['input_user_report']);
            $year = $date->format('Y'); // 年を取得
            $month = $date->format('m'); // 月を取得
            $day = $date->format('d'); // 日を取得

            $update_field['届け出日　年'] = change_user_meta($year, 'report_born_year', $user_id);
            $update_field['届け出日　月'] = change_user_meta($month, 'report_born_month', $user_id);
            $update_field['届け出日　日'] = change_user_meta($day, 'report_born_day', $user_id);
        }

        

        // 流入元
        if (isset($post_data['input_inflow'])  && $post_data['input_inflow'] != "") {
            $update_field['流入元'] = change_user_meta($post_data['input_inflow'], 'input_inflow', $user_id);
        }
        if(isset($post_data['input_inflow_remarks'])){

            $update_field['流入追記'] = change_user_meta($post_data['input_inflow_remarks'], 'inflow_remarks', $user_id);
        }

        // 紹介者
        if($post_data['input_introduction_id'] != 'x'){

            if(change_user_meta($post_data['input_introduction_id'], 'input_introduction_id', $user_id)) $update_field['紹介者ID'] = true;
            
        }

        if(isset($post_data['input_introduction_name'])){

            if($post_data['input_introduction_id'] == 'x'){
                change_user_meta('x', 'input_introduction_id', $user_id);
                $update_field['紹介者名'] = change_user_meta($post_data['input_introduction_name'], 'input_introduction_name', $user_id);
            }else{

                $update_field['紹介者名'] = change_user_meta($post_data['input_introduction_name'], 'input_introduction_name', $user_id);
            }
        }

        // 特記事項
        if(isset($post_data['input_remarks'])){
            $update_field['特記事項'] = change_user_meta($post_data['input_remarks'], 'user_remarks', $user_id);
        }

        //ロール権限変更
        if(isset($post_data['input_customer_role']) && $post_data['input_customer_role'] != ""){

            // 現在ログインしているユーザーの情報を取得
            $current_user = get_userdata($user_id);

            // ユーザーのロールを取得
            $user_roles = $current_user->roles[0];

            if($user_roles != $post_data['input_customer_role']){
                $user = new WP_User($user_id);

                //echo $post_data['input_customer_role'];
                $user->set_role($post_data['input_customer_role']);
                $update_field['顧客権限'] = true;
            }

           
        }

        //会員情報認証
        if(isset($post_data['user_date_complete'])){
            $update_field['会員情報認証'] = change_user_meta($post_data['user_date_complete'], 'user_date_complete', $user_id);
        }



        // 更新者情報
        MakeUpdateUserMeta($update_field,$user_id);


        
        return $user_id;
    }

    return 0;

}
/****************************************************
 **   更新情報作成
 ******************************************************/
function MakeUpdateUserMeta($update_field,$user_id){
     // 更新者情報
     $timezone = new DateTimeZone('Asia/Tokyo');
     $now = new DateTime('now', $timezone);

     // 既存の情報　
     $update_field = array_filter($update_field, function ($value) {
         return $value === true; // 厳密に true のみを返す
     });


     // 今回の更新情報
     $data_to_save = array(
         'edit_date' => $now->format('Y-m-d H:i:s'),
         'edit_changer' => get_current_user_id(),
         'edit_content' => $update_field
     );
     
     $existing_data_json = get_user_meta($user_id, 'user_edit_day', true);    //jsonデータ取得

     // 既存のデータがある場合はデコードし、なければ空の配列を準備
     if ($existing_data_json) {
         $existing_data = json_decode($existing_data_json, true);
         if (!is_array($existing_data)) {
             $existing_data = array();
         }
     } else {
         $existing_data = array();
     }

      // 新しいデータを既存のデータに追加
      $existing_data[] = $data_to_save;
      $updated_data_json = json_encode($existing_data, JSON_UNESCAPED_UNICODE);

      if (!empty($update_field))
          update_user_meta($user_id, 'user_edit_day', $updated_data_json);


}

/****************************************************
 **   更新者情報取得
 ******************************************************/
function get_updata_user_info($data)
{
    $user_info = get_userdata($data['edit_changer']);   //更新者情報取得
    $ret_data = get_user_meta($user_info->ID, 'last_name', true) . get_user_meta($user_info->ID, 'first_name', true);
    return $ret_data;
}


/****************************************************
 **   ユーザー重複チェック

　申込者が既存のユーザーならユーザーIDを返す

 ******************************************************/
function checkUserRegist($post_data, $remort = false)
{

    $res_data = array();
    $res_data['res_msg'] = "";      //登録メッセージ
    $res_data['regist_error'] = false; //登録エラー
    $res_data['res_data'] = "";     //登録でーた
    $res_data['registed_id'] = "";     //登録済みID

    $users = get_users();

    if($post_data["input_user_email"] == "") return $res_data;

    // メールアドレスのカウント
    foreach ($users as $user) {

        $email = $user->user_email;
        $unique_id = get_user_meta($user->ID, 'user_unique_id', true);
        $tel = get_user_meta($user->ID, 'billing_phone', true) . get_user_meta($user->ID, 'billing_phone2', true) . get_user_meta($user->ID, 'billing_phone3', true);

        // ユニークID入力はここで戻す
        if ((isset($post_data['input_registered_id']) && $post_data['input_registered_id'] == $unique_id )) {
            $res_data['res_data'] = $user->ID;
            $res_data['res_msg'] = "既存ユーザーです";

            return $res_data;
        }

        // 同一メアド、同一TELならここで戻す
        if ($email == $post_data['input_user_email']) {
            $res_data['res_msg'] = "既に使用されているアドレスです。";
            

            if($tel == $post_data['input_tel_1'].$post_data['input_tel_2'].$post_data['input_tel_3']){

                // $res_data['res_data'] = $user->ID;
                $res_data['registed_id'] = $user->ID;
                $res_data['res_msg'] = "既存ユーザーです";
    
                return $res_data;
            }
        }
    }

    return $res_data;
}
function _checkUserRegist($post_data, $remort = false)
{

    $res_data = array();
    $res_data['res_msg'] = "";      //登録メッセージ
    $res_data['regist_error'] = false; //登録エラー
    $res_data['res_data'] = "";     //登録でーた
    $res_data['registed_id'] = "";     //登録済みID

    $users = get_users();

    // 既存ユーザー情報
    $registed_users_email = array();        //メアドチェック

    // メアドと電話番号のみでチェック
    // $registed_users_address = array();      //住所チェック      
    // $registed_users_fullname = array();     //フルネームチェック
    // $registed_users_last_name = array();    //苗字チェック
    $registed_users_tel = array();          //電話番号チェック
    $registed_users_unique = array();       //ユニークIDチェック

    // $registed_address_flg = false;  //既存住所重複
    // $registed_tel_flg = false;      //既存電話番号重複
    // $registed_email_flg = false;    //既存メアド重複

    $check_ids = "";    //同姓同名のID

    // メールアドレスのカウント
    foreach ($users as $user) {

        $email = $user->user_email;
        $last_name = get_user_meta($user->ID, 'last_name', true);
        $first_name = get_user_meta($user->ID, 'first_name', true);
        $address = get_user_meta($user->ID, 'billing_city', true);
        $unique_id = get_user_meta($user->ID, 'user_unique_id', true);
        $tel = get_user_meta($user->ID, 'billing_phone', true) . get_user_meta($user->ID, 'billing_phone2', true) . get_user_meta($user->ID, 'billing_phone3', true);


        
        // 既存ユーザーはここで戻す
        if (
            (isset($post_data['input_registered_id']) && $post_data['input_registered_id'] == $unique_id ) ||
            ($last_name == $post_data['input_last_name'] && $first_name == $post_data['input_first_name'] && $email == $post_data['input_user_email'])
            ) {
            // 既存ユーザーでの浄霊予約の際のチェック
            // 同姓同名で同じメアド

            // echo "既に登録済みのユーザーです。";
            $res_data['res_data'] = $user->ID;
            $res_data['res_msg'] = "既存ユーザーです";

            return $res_data;
        }

        // 既存ユーザー配列作成
        if (!in_array($email, $registed_users_email)) {
            $registed_users_email[$user->ID] = $email;
            // $registed_users_fullname[$user->ID] = $last_name . $first_name;
            // $registed_users_last_name[$user->ID] = $last_name;
            // $registed_users_address[$user->ID] = $address;
            $registed_users_tel[$user->ID] = $tel;
            $registed_users_unique[$user->ID] = $unique_id;
        }
    }

    // echo "既存チェック開始<br>";

    //同姓同名チェック
    // $check_ids = array_keys($registed_users_fullname, $post_data["input_last_name"] . $post_data["input_first_name"]);

    // 同じメールアドレスチェック
    $same_flag = false;
    $email_id = array_search($post_data['input_user_email'], $registed_users_email);

    // 登録不可処理
    // if (!empty($check_ids)) {

    //     // echo "同姓同名アリ<br>";

    //     foreach ($check_ids as $check_id) {
    //         $res_data['res_msg'] = "同姓同名がいます。";
    //         $res_data['registed_id'] = $check_id;

    //         // if ($registed_users_address[$check_id] == $post_data['input_address1']) {
    //         //     $res_data['res_msg'] .= "<br>同一住所の為、登録できません";
    //         //     $registed_address_flg = true;       //同一住所
    //         // }
    //         if ($registed_users_email[$check_id] == $post_data['input_user_email']) {
    //             $res_data['res_msg'] .= "<br>同一メールの為、登録できません";
    //             $registed_email_flg = true;
    //         }

    //         // リモート浄霊以外は電話番号チェック省く
    //         if ($registed_users_tel[$check_id] == $post_data['input_tel_1'] . $post_data['input_tel_2'] . $post_data['input_tel_3']) {
    //             $res_data['res_msg'] .= "<br>同一電話番号の為、登録できません";
    //             $registed_tel_flg = true;       //同一番号
    //         }
    //     }


    //     // 重複があれば登録エラー
    //     if ($registed_address_flg || $registed_email_flg || $registed_tel_flg) {

    //         if($registed_address_flg && $registed_email_flg && $registed_tel_flg){
    //             // $res_data['registed_id']
    //             return $res_data;
    //         }

    //         // 但し、住所電話番号が同じ　かつ メアドが異なる場合は上書き
    //         if ($registed_address_flg && $registed_tel_flg && !$registed_email_flg) {

    //             $change_mail = wp_update_user([
    //                 'ID' => $check_ids[0],
    //                 'user_email' => $post_data['input_user_email'],
    //             ]);

    //             $res_data['res_msg'] .= "<br>電話番号、住所同一の為、メールアドレスを上書き保存";

    //             return $res_data;
    //         } else {

    //             $res_data['regist_error'] = true;
    //         }
    //     }

        
    //     if ($res_data['regist_error'] != true) {
            
    //         $res_data['res_data'] = setNewUserData($post_data);

    //         return $res_data;            // ここでリターンするように変更　241210
    //     }
    // }


    // 同一メールでも登録許可
    if (!empty($check_ids) || !empty($email_id) && $post_data['input_user_email'] != "" ) {
        
        if (!empty($email_id)) {
            // echo "同一メアド有<br>";
            $res_data['res_msg'] = "同じメールアドレスがいます。ID" . $registed_users_unique[$email_id];
            $res_data['registed_id'] = $email_id;

            // 家族番号として_#&#_をつける
            // if($last_name == $post_data['input_last_name'] && $first_name == $post_data['input_first_name']){
            //     // 同姓同名ならそのままID返す
            //     $res_data['res_data'] = $user->ID;
            //     $res_data['res_msg'] = "既存ユーザーです";

            //     return $res_data;

            // }else if ($registed_users_address[$email_id] == $post_data['input_address1']) {
            // if ($registed_users_address[$email_id] == $post_data['input_address1']) {
            //     $res_data['res_msg'] .= "<br>同一住所の為、家族として登録します";

            //     // 作成するアドレスチェック                        
            //     // '@'で分割
            //     list($localPart, $domain) = explode('@', $post_data["input_user_email"]);

            //     // 親アドレスと同じものを抽出
            //     $filteredArray = array_filter($registed_users_email, function($value) use ($localPart) {
            //         return strpos($value, $localPart) !== false;
            //     });

            //     // 連番を確認
            //     $numbers = [];
            //     foreach ($filteredArray as $key => $value) {

            //         // "@" の前の数値をすべて抽出
            //         if (preg_match_all('/([0-9]+)@/', $value, $matches)) {
            //             $numbers[] = $matches[1][0]; // 数字部分をすべて取得
            //         }
            //     }
                
            //     // 連番を追加
            //     $newLocalPart = $localPart . '_#&_' . str_pad(count($numbers), 3, '0', STR_PAD_LEFT);

            //     // 新しいメールアドレスを作成
            //     $post_data['input_user_email'] = "$newLocalPart@$domain"; //家族用アドレスに変更
            //     $post_data['input_introduction_id'] = $email_id;
            //     $same_flag = true;
            // } else 
            // if ($registed_users_last_name[$email_id] == $post_data['input_last_name']) {
            //     $res_data['res_msg'] .= "<br>同一姓の為、家族として登録します";
            //     $same_flag = true;
            // }else{
                return $res_data;   //登録済みユーザーID返す
            // }

        }

        
        // echo "同一メアドチェック終了<br>";
        
        if ($same_flag) {

            $res_data['regist_error'] = true;

            $res_data['res_data'] = setNewUserData($post_data,true);

            if (isset($res_data['res_data']->errors)) {

                $res_data['res_msg'] .= "<br>" . $res_data['res_data']->errors;

            }

        } else {
            //通常登録
            // echo "通常登録";
            $res_data['res_data'] = setNewUserData($post_data);
        }
    }else{
        
        // echo "通常登録";
        $res_data['res_data'] = setNewUserData($post_data);
    }

    // echo "登録チェック終了<br>";

    return $res_data;
}

/****************************************************
 **   登録フォームからの顧客データ登録
 **   ret:登録ユーザーID
 ******************************************************/
function makeRegistFormData($post_data)
{

    // 既存ユーザーの情報更新
    if(isset($post_data['input_registered_id'])){

        $timestamp = substr($post_data['input_registered_id'], 0, 10); // 最初の10文字がタイムスタンプ
        $userId = substr($post_data['input_registered_id'], 10); // 残りがユーザーID
        $userId = preg_replace('/^0+/', '', $userId);

        $apply_user = array();
        $apply_user['registed_id'] = setChangeUserData($userId,$post_data);
        $apply_user['res_data'] = "";

    }else{
        //メールアドレスの重複チェックかつ申込者登録    $res_data['res_data']にID情報登録される
        $apply_user = checkUserRegist($post_data);

    }
    var_dump($apply_user);  //削除okd

    $ret = array();                     // 戻り値

    // 新規登録処理　$apply_user['res_data']：新規登録ID
    
    if ($apply_user['res_data'] != "") {
            
        // 重複ユーザーは居ないので
        // 登録フォームで必要
        $res_data['res_data'] = setNewUserData($post_data);

        //申込者ID保存
        $ret['application_user'] = array();
        $ret['application_user'][] = $apply_user['res_data'];
    } else if ($apply_user['registed_id'] != "") {

        $ret['application_user'] = array();
        $ret['application_user'][] = $apply_user['registed_id'];

    }
    // 申込者のIDだけ返すように対応　24/11/14
    // ↑対象者チェック　→　申込者のみ登録　25/2/17

    return $ret;

}
function _makeRegistFormData($post_data)
{

    // 既存ユーザーの情報更新
    if(isset($post_data['input_registered_id'])){

        $timestamp = substr($post_data['input_registered_id'], 0, 10); // 最初の10文字がタイムスタンプ
        $userId = substr($post_data['input_registered_id'], 10); // 残りがユーザーID
        $userId = preg_replace('/^0+/', '', $userId);

        $apply_user = array();
        $apply_user['registed_id'] = setChangeUserData($userId,$post_data);
        $apply_user['res_data'] = "";

    }else{
        //メールアドレスの重複チェックかつ申込者登録    $res_data['res_data']にID情報登録される
        $apply_user = checkUserRegist($post_data);

    }

    $new_regist = false;                // 新規登録完了
    $target_regist_flg = false;         // 対象者フラグ

    $ret = array();                     // 戻り値
    $target_data = array();             // 対象者データ

    // 新規登録処理　$apply_user['res_data']：新規登録ID
    
    if ($apply_user['res_data'] != "") {
        $new_regist = true;

        //申込者ID保存
        $ret['application_user'] = array();
        $ret['application_user'][] = $apply_user['res_data'];
    } else if ($apply_user['registed_id'] != "") {

        $ret['application_user'] = array();
        $ret['application_user'][] = $apply_user['registed_id'];

    }
    
    // 対象者チェック
    if (isset($post_data['input_target_last_name'])) {

        if ($new_regist && $post_data['input_target_last_name'] != "") {
            // 新規 かつ 対象者同じ　→　新規の質問のみ更新
            // 新規 かつ 対象者違う
            $target_regist_flg = true;
        }

        if ($apply_user['registed_id'] != "" && $post_data['input_target_last_name'] != "") {
            // 既存 かつ 対象者同じ　→　新規の質問のみ更新
            // 既存 かつ 対象者違う
            $target_regist_flg = true;
        }
    }

    // 質問情報追加
    foreach ($post_data as $key => $value) {
        if (strpos($key, 'question') !== false) {
            $target_data[$key] = $value;
        }

        // question_587があればリモート一斉浄霊対応
        if (strpos($key, 'question_587') !== false) {


            // 申込者のIDだけ返すように対応　24/11/14
            /* 
            // リモート番号設定
            $new_key = preg_replace('/\D/', '', str_replace('587', '', $key));

            // 紹介者設定
            if($new_key != $remote_no){
                $remote_no = $new_key;
                // 紹介者設定
                $application_user = get_userdata( $ret['application_user'][0] );
                $remote_data[$remote_no]['input_introduction_id'] = $ret['application_user'][0];
                $remote_data[$remote_no]['input_introduction_name'] = $application_user->last_name.$application_user->first_name;
                $remote_data[$remote_no]['input_address1'] = get_user_meta($application_user->ID,'input_address1',true);

                if(isset( $post_data["input_registered_mail"])){

                    $remote_data[$remote_no]['input_user_email'] = $post_data["input_registered_mail"];
                }else{

                    $remote_data[$remote_no]['input_user_email'] = $post_data["input_user_email"];
                }
                $remote_data[$remote_no]['remote_no'] = $remote_no;
            }

            // 姓
            if(strpos($key, 'sei') !== false){
                $remote_flg = true;
                if(strpos($key, 'sei_kana') === false){
                    $remote_data[$remote_no]['input_last_name'] = $value;
                }else{
                    $remote_data[$remote_no]['input_last_name_kana'] = $value;
                }
            }

            if(strpos($key, 'mei') !== false){

                if(strpos($key, 'mei_kana') === false){
                    $remote_data[$remote_no]['input_first_name'] = $value;
                }else{
                    $remote_data[$remote_no]['input_first_name_kana'] = $value;
                }
            }

            // 関係性
            if(strpos($key, 'parents') !== false){
                $remote_data[$remote_no]['input_parents'] = $value;
            }

            // 誕生日
            if(strpos($key, 'date') !== false){
                $remote_data[$remote_no]['input_user_born'] = $value;
            }
            */
        }
    }

    // 申込者のIDだけ返すように対応　24/11/14
    // TODO:申込者のみ登録に変更　

    // 登録者と対象者が異なる場合の登録 
    if($target_regist_flg){
        
        // 対象者データ作成
        foreach ($post_data as $key => $value) {
            if(strpos($key,'target') !== false){

                if(strpos($key,'born') !== false || strpos($key,'report') !== false){
                    $input_key = str_replace("target","user",$key);

                }else{

                    $input_key = str_replace("_target","",$key);
                }

                if($input_key == "input_email"){

                    $target_data["input_user_email"] = $value;
                }else{

                    $target_data[$input_key] = $value;
                }
            }
        }
        $target_data['input_introduction_name'] = get_user_meta($apply_user['registed_id'],'last_name',true).get_user_meta($apply_user['registed_id'],'first_name',true);
        // $target_data['input_introduction_id'] = get_user_meta($apply_user['registed_id'],'user_unique_id',true);
        $target_data['input_introduction_id'] = $apply_user['registed_id'];


        // 対象者登録
        $target_user = checkUserRegist($target_data);

        if($target_user['res_msg'] == "既存ユーザーです" || $target_user['registed_id'] != "" || $target_user['res_data'] != ""){

            $ret['target_user'][] = $target_user['res_data'];
        }else{

            $ret['target_user'][] = $target_user;
        }
    }
    /* 
    else if($remote_flg){
    //リモート浄霊の場合
        $remote_res = array();
        foreach($remote_data as $data){
            $remote_res = checkUserRegist($data,$remote_flg);
            // 既存ID取得
            if($remote_res['registed_id'] != ""){
                // $ret['remote_user'.$data['remote_no']] = $remote_res['registed_id'];
                $ret['remote_user'][] = $remote_res['registed_id'];
            }else{
                // $ret['remote_user'.$data['remote_no']] = $remote_res['res_data'];
                $ret['remote_user'][] = $remote_res['res_data'];
            }
        }
    }
    */

    return $ret;

}



?>