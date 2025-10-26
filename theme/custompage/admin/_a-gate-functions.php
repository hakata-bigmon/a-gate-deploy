<?php 




    //ページURL取得
    function getURLSetSlag($slag)
    {
        $page = get_page_by_path($slag);

        return esc_url(get_permalink($page->ID));
    }

    //年齢自動計算
    function calculateAge($birthDate) {
        // 生年月日をDateTimeオブジェクトに変換

        if($birthDate != "//"){

            $birthDate = DateTime::createFromFormat('Y/m/d', $birthDate);
            // if ($birthDate === false) {
            //     throw new Exception("無効な日付形式です。YYYY/MM/DD形式で入力してください。");
            // }
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
     **   家族アドレス取得
     ******************************************************/
    function getParentAddress($check_email){
        
        $search = "_#&";   //家族記号
        // 家族なら親のメールアドレスを表示させる
        if(strpos( $check_email , $search) !== false){

            list($localPart, $domain) = explode('@', $check_email);
            $localPart = explode($search,  $localPart);
            $user_regitsted_mail = $localPart[0]. '@' .$domain;

        }else{
            $user_regitsted_mail = $check_email;
        }
        return $user_regitsted_mail;
    }

    /****************************************************
     **   ユーザー情報の新規登録
     ******************************************************/
    function setNewUserData($post_data,$same_address = "")
    {
        $last_name = $post_data["input_last_name"];     //姓
        $first_name = $post_data["input_first_name"];   //名前
        $no_mail = false;

        if($same_address != ""){
            // '@'で分割
            list($localPart, $domain) = explode('@', $post_data["input_user_email"]);

            // 連番を追加
            $newLocalPart = $localPart .'_#&_'. str_pad($same_address, 3, '0', STR_PAD_LEFT);

            // 新しいメールアドレスを作成
            $mail_add = $newLocalPart . '@' . $domain;;//メール
        }else if($post_data["input_user_email"] == ""){
            $current_unix_time = time();
            $mail_add = $current_unix_time."@gmail.com";
            $no_mail = true;
        }else{
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

        //作成
        $user_id = wp_insert_user($userdata);

        if (!isset($user_id->errors) && $user_id) {

            
            $users = get_userdata( $user_id );
            $regist_timestamp = strtotime($users->user_registered);
            $paddedData = str_pad($users->ID, 5, '0', STR_PAD_LEFT);
            $user_unique_id = $regist_timestamp.$paddedData;

            // 暗号ID作成
            update_user_meta($user_id, 'user_unique_id', $user_unique_id);

            //姓名（よみ）
            update_user_meta($user_id, 'last_name_kana', $post_data['input_last_name_kana']);
            update_user_meta($user_id, 'first_name_kana', $post_data['input_first_name_kana']);

            // 性別
            change_user_meta($post_data['input_user_sex'],'sex',$user_id);

            // 連絡先
            if(isset($post_data['input_tel_1'])){
                update_user_meta($user_id, 'billing_phone', $post_data['input_tel_1']);
                update_user_meta($user_id, 'billing_phone2', $post_data['input_tel_2']);
                update_user_meta($user_id, 'billing_phone3', $post_data['input_tel_3']);
            }else{
                
                update_user_meta($user_id, 'check_phone_none', true);
            }

            // LINEで登録してメアドがない場合の処理
            if($no_mail){
                
                update_user_meta($user_id, 'no_mail', true);
            }
            
            // 郵便番号
            if(isset($post_data['input_post_no'])){

                update_user_meta($user_id, 'billing_postcode', $post_data['input_post_no']);//郵便番号
            }else if(isset($post_data['input_post_no_1'])){
                $post_no = $post_data['input_post_no_1'].$post_data['input_post_no_2'];
                update_user_meta($user_id, 'billing_postcode', $post_no);//郵便番号
            }

            //住所
            if(isset($post_data['input_address1'])){
                update_user_meta($user_id, 'billing_city', $post_data['input_address1']);
            }
            // マンション
            if(isset($post_data['input_address2'])){
                update_user_meta($user_id, 'billing_address_1', $post_data['input_address2']);//マンション
            }

            //LINE　ID
            if(isset($post_data['input_user_line_id'])){
                update_user_meta($user_id, 'line_id', $post_data['input_user_line_id']);
            }

            //流入元
            if(isset($post_data['input_inflow'])){
                update_user_meta($user_id, 'input_inflow', $post_data['input_inflow']);
            }
            if(isset($post_data['input_inflow_remarks'])){
                update_user_meta($user_id, 'inflow_remarks', $post_data['input_inflow_remarks']);//流入元備考
            }

            update_user_meta($user_id, 'input_introduction_name', $post_data['input_introduction_name']);//紹介者名
            update_user_meta($user_id, 'input_introduction_id', $post_data['input_introduction_id']);//紹介者ID

            //特記事項
            if(isset($post_data['input_remarks'])){
                change_user_meta($post_data['input_remarks'],'user_remarks',$user_id);
                
            }

            // 生年月日
            if(isset($post_data['input_user_born'])){
                $date = new DateTime($post_data['input_user_born']);
                $year = $date->format('Y'); // 年を取得
                $month = $date->format('m'); // 月を取得
                $day = $date->format('d'); // 日を取得

                update_user_meta($user_id, 'born_year', $year);
                update_user_meta($user_id, 'born_month', $month);
                update_user_meta($user_id, 'born_day', $day);
            }else{
                update_user_meta($user_id, 'born_year', $post_data['input_user_born_year']);
                update_user_meta($user_id, 'born_month', $post_data['input_user_born_month']);
                update_user_meta($user_id, 'born_day', $post_data['input_user_born_day']);
            }
            
            // 届け出生年月日
            if(isset($post_data['input_user_report_year'])){

                update_user_meta($user_id, 'report_born_year', $post_data['input_user_report_year']);
                update_user_meta($user_id, 'report_born_month', $post_data['input_user_report_month']);
                update_user_meta($user_id, 'report_born_day', $post_data['input_user_report_day']);
            }

            return $user_id;
        }


        return $user_id;

    }

    /****************************************************
     **   ユーザー性別取得
    ******************************************************/
    function getUserSex($sex){
        $ret = "";
        if($sex == "M"){
            $ret = "男(M)";
        }else if($sex == "W"){
            $ret = "女(W)";
        }else{

        }

        return $ret;
    }

    /****************************************************
     **   ユーザー情報取得
    ******************************************************/
    function getUsersData($check_user_id){
        
        $data = array();
        $born_year = get_user_meta($check_user_id, 'born_year', true).'/'.get_user_meta($check_user_id, 'born_month', true).'/'.get_user_meta($check_user_id, 'born_day', true);
        
        
        $users = get_userdata( $check_user_id );
        $data['input_user_registed'] = $users->user_registered;
        $data['input_user_email'] = $users->user_email;
        $data['input_user_id'] = $users->ID;
        
        $data['input_user_age'] = calculateAge($born_year);
        
        $data['input_unique_id'] = get_user_meta($check_user_id,'user_unique_id',true);
        $data['input_last_name'] = get_user_meta($check_user_id,'last_name',true);
        $data['input_first_name'] = get_user_meta($check_user_id,'first_name',true);
        $data['input_last_name_kana'] = get_user_meta($check_user_id,'last_name_kana',true);
        $data['input_first_name_kana'] = get_user_meta($check_user_id,'first_name_kana',true);
        $data['input_user_sex'] = get_user_meta($check_user_id,'sex',true);
        $data['input_tel_1'] = get_user_meta($check_user_id,'billing_phone',true);
        $data['input_tel_2'] = get_user_meta($check_user_id,'billing_phone2',true);
        $data['input_tel_3'] = get_user_meta($check_user_id,'billing_phone3',true);
        $data['input_check_phone_none'] = get_user_meta($check_user_id,'check_phone_none',true);
        $data['input_post_no'] = get_user_meta($check_user_id,'billing_postcode',true);
        $data['input_billing_city'] = get_user_meta($check_user_id,'billing_city',true);    //フル住所

        $data['input_address2'] = get_user_meta($check_user_id,'billing_address_1',true);
        $data['input_user_line_id'] = get_user_meta($check_user_id,'line_id',true);
        $data['input_user_born_year'] = get_user_meta($check_user_id,'born_year',true);
        $data['input_user_born_month'] = get_user_meta($check_user_id,'born_month',true);
        $data['input_user_born_day'] = get_user_meta($check_user_id,'born_day',true);
        $data['input_user_report_year'] = get_user_meta($check_user_id,'report_born_year',true);
        $data['input_user_report_month'] = get_user_meta($check_user_id,'report_born_month',true);
        $data['input_user_report_day'] = get_user_meta($check_user_id,'report_born_day',true);
        $data['input_inflow'] = get_user_meta($check_user_id,'input_inflow',true);
        $data['input_inflow_remarks'] = get_user_meta($check_user_id,'inflow_remarks',true);
        $data['input_introduction_id'] = get_user_meta($check_user_id,'input_introduction_id',true);
        $data['input_introduction_name'] = get_user_meta($check_user_id,'input_introduction_name',true);
        $data['input_connect_group'] = get_user_meta($check_user_id, 'connect_group', true);

        // 更新者情報 
        $existing_data_json = get_user_meta($check_user_id,'user_edit_day',true);    //jsonデータ取得
        if ($existing_data_json) {
            $existing_data = json_decode($existing_data_json, JSON_UNESCAPED_UNICODE);
            if (!is_array($existing_data)) {
                $existing_data = array();
            }
        } else {
            $existing_data = array();
        }
        $data['user_edit_changer'] = $existing_data;



        $data['input_remarks'] = get_user_meta($check_user_id,'user_remarks',true);

        return $data;
    }

    /****************************************************
     **   ユーザー浄霊データ新規作成
     ******************************************************/
    function makeUserSplitData($user_id)
    {
        $split_data = array();
        $split_data['霊視鑑定']   = array('','','','','','','','');
        $split_data['先祖鑑定']   = array('','','','','','','','');
        $split_data['土地鑑定']   = array('','','','','','','','');
        $split_data['完全浄霊']   = array('','','','','','','','');
        $split_data['土地浄化']   = array('','','','','','','','');
        $split_data['守護霊']     = array('','','','','','','','');
        $split_data['指導霊']     = array('','','','','','','','');
        $split_data['神繋ぎ']     = array('','','','','','','','');
        $split_data['アカシック'] = array('','','','','','','','');
        $split_data['会社浄霊']   = array('','','','','','','','');
        $split_data['会社土地']   = array('','','','','','','','');
        $split_data['パワーストーン'] = array('','','','','','','','');
        $data = json_encode($split_data,JSON_UNESCAPED_UNICODE);


        $check = change_user_meta($data,'spirit_data',$user_id);

        if (!$check) return 0;

    }

    /****************************************************
     **   ユーザー情報のバックアップ
     ******************************************************/
    function saveUserData($user_id)
    {
        require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
        $spirit_sheet_data = new spiritSheetClass(); //管理データ

        $data = array();
        $data['acf_purespirit_requested_date'] = get_field('acf_purespirit_requested_date',$_GET["sheet_name"]);
        $data['acf_purespirit_request_confirmation_date'] = get_field('acf_purespirit_requested_date',$_GET["sheet_name"]);
        $data['acf_purespirit_payment_date'] = get_field('acf_purespirit_payment_date',$_GET["sheet_name"]);
        $data['acf_purespirit_execution_date'] = get_field('acf_purespirit_execution_date',$_GET["sheet_name"]);
        $data['acf_purespirit_status'] = get_field('acf_purespirit_status',$_GET["sheet_name"]);
        $data['acf_purespirit_status'] = get_field('acf_applicant',$_GET["sheet_name"]);    //申込者
        $data['acf_purespirit_price'] = get_field('acf_purespirit_price',$_GET["sheet_name"]);//単価
        $data['acf_agate_hands_on'] = get_field('acf_agate_hands_on',$_GET["sheet_name"]);  //a-gate実施
        $data['acf_agate_process_execution'] = get_field('acf_agate_process_execution',$_GET["sheet_name"]);  //処理実行日
        $data['acf_agate_result_on'] = get_field('acf_agate_result_on',$_GET["sheet_name"]);  //結果レポ格納
        $data['acf_agate_e_mark_send'] = get_field('acf_agate_e_mark_send',$_GET["sheet_name"]);  //Eマーク送付
        $data['acf_agate_result_report_send'] = get_field('acf_agate_result_report_send',$_GET["sheet_name"]);  //結果レポ送付
        $data['acf_agate_add_text'] = nl2br(get_field('acf_agate_add_text',$_GET["sheet_name"]));  //補足

        // 質問事項
        $type = get_field('acf_acf_purespirit_type',$_GET["sheet_name"]);
        //現在の質問番号を取得
        $spiritQuestionArray = $spirit_sheet_data->getSpiritQuestion($type);
        //回答配列を取得
        $anser = $spirit_sheet_data->getSpiritSheetAnswer( $_GET["sheet_name"] );

        $timezone = new DateTimeZone('Asia/Tokyo');
        $now = new DateTime('now', $timezone);
        $data['back_up_date'] = $now->format('Y-m-d H:i:s');
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        
        $check = change_user_meta($data,'user_before_data',$user_id);

        if (!$check) return 0;

    }

    /****************************************************
     **   浄霊シートのバックアップ
     ******************************************************/
    function saveUserSpritSheet($user_id)
    {
        $data = array();
        $data = getUsersData($user_id);

        $timezone = new DateTimeZone('Asia/Tokyo');
        $now = new DateTime('now', $timezone);
        $data['back_up_date'] = $now->format('Y-m-d H:i:s');
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        
        $check = change_user_meta($data,'user_before_data',$user_id);

        if (!$check) return 0;

    }

    

    /****************************************************
     **   ユーザー情報の更新
     ******************************************************/
    function setChangeUserData($user_id, $post_data)
    {
        if ($user_id) {
            $update_field = array();

            //姓名
            $update_field['苗字'] = change_user_meta($post_data['input_last_name'],'last_name',$user_id);
            $update_field['名前'] = change_user_meta($post_data['input_first_name'],'first_name',$user_id);

            //姓名（よみ）
            $update_field['苗字フリガナ'] = change_user_meta($post_data['input_last_name_kana'],'last_name_kana',$user_id);
            $update_field['名前フリガナ'] = change_user_meta($post_data['input_first_name_kana'],'first_name_kana',$user_id);

            // 性別
            if(isset($post_data['input_user_sex'])){
                $update_field['性別'] = change_user_meta($post_data['input_user_sex'],'sex',$user_id);
            }

            // 関連
            if(isset($post_data['input_user_connect'])){
                // change_user_meta($post_data['input_user_connect'],'connect_group',$user_id);
                // update_user_meta($user_id, 'connect_group', $post_data['input_user_connect']);
                // updateConnectGroup($user_id,$post_data);
            }

            

            //グループ
            if(isset($post_data['input_user_group'])){

                $group_data = json_encode($post_data['input_user_group']);
                $update_field['グループ'] = change_user_meta($group_data,'group_data',$user_id);
            }
            
            // 連絡先
            $update_field['連絡先1'] = change_user_meta($post_data['input_tel_1'],'billing_phone',$user_id);
            $update_field['連絡先2'] = change_user_meta($post_data['input_tel_2'],'billing_phone2',$user_id);
            $update_field['連絡先3'] = change_user_meta($post_data['input_tel_3'],'billing_phone3',$user_id);
            
            //電話番号無し
            if(isset($post_data['input_check_phone_none'])){
                $update_field['電話番号無し'] = change_user_meta($post_data['input_check_phone_none'],'check_phone_none',$user_id);
            }

            // 郵便番号
            $update_field['郵便番号'] = change_user_meta($post_data['input_post_no'],'billing_postcode',$user_id);

            // 住所
            $update_field['住所'] = change_user_meta($post_data['input_billing_city'],'billing_city',$user_id);
            $update_field['マンション名など'] = change_user_meta($post_data['input_address2'],'billing_address_1',$user_id);

            //メールアドレスの変更
            if (isset($post_data['input_user_email']) && $post_data['input_user_email'] != "" && get_the_author_meta('user_email', $user_id) != $post_data['input_user_email']) {
                $change_mail = wp_update_user([
                    'ID' => $user_id,
                    'user_email' => $post_data['input_user_email'],
                ]);

                if ($change_mail != $user_id) return 0;
                else{

                    $update_field['メールアドレス'] = $change_mail;
                    change_user_meta(0,'no_mail',$user_id);

                }
            }

            // LINE ID
            $update_field['LINE　ID'] = change_user_meta($post_data['input_user_line_id'],'line_id',$user_id);

            // 生年月日
            $update_field['生年月日　年'] = change_user_meta($post_data['input_user_born_year'],'born_year',$user_id);
            $update_field['生年月日　月'] = change_user_meta($post_data['input_user_born_month'],'born_month',$user_id);
            $update_field['生年月日　日'] = change_user_meta($post_data['input_user_born_day'],'born_day',$user_id);
            
            // 届け出日
            $update_field['届け出日　年'] = change_user_meta($post_data['input_user_report_year'],'report_born_year',$user_id);
            $update_field['届け出日　月'] = change_user_meta($post_data['input_user_report_month'],'report_born_month',$user_id);
            $update_field['届け出日　日'] = change_user_meta($post_data['input_user_report_day'],'report_born_day',$user_id);
            
            // 流入元
            if(isset($post_data['input_inflow'])){
                $update_field['流入元'] = change_user_meta($post_data['input_inflow'],'input_inflow',$user_id);
            }
            $update_field['流入追記'] = change_user_meta($post_data['input_inflow_remarks'],'inflow_remarks',$user_id);
            
            // 紹介者
            $update_field['紹介者ID'] = change_user_meta($post_data['input_introduction_id'],'input_introduction_id',$user_id);
            $update_field['紹介者名'] = change_user_meta($post_data['input_introduction_name'],'input_introduction_name',$user_id);
            
            // 特記事項
            $update_field['特記事項'] = change_user_meta($post_data['input_remarks'],'user_remarks',$user_id);

            // 更新者情報
            $timezone = new DateTimeZone('Asia/Tokyo');
            $now = new DateTime('now', $timezone);

            // 既存の情報　
            $update_field = array_filter($update_field, function($value) {
                return $value === true; // 厳密に true のみを返す
            });


            // 今回の更新情報
            $data_to_save = array(
                'edit_date' => $now->format('Y-m-d H:i:s'),
                'edit_changer' => get_current_user_id(),
                'edit_content' => $update_field
            );

            $existing_data_json = get_user_meta($user_id,'user_edit_day',true);    //jsonデータ取得

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

            if(!empty($update_field)) update_user_meta($user_id, 'user_edit_day', $updated_data_json);


            // change_user_meta($data_to_save,'user_edit_day',$user_id);
            // change_user_meta(get_current_user_id(),'user_edit_changer',$user_id);
            
            //表示非表示
            // change_user_meta($post_data['user_delete'],'is_delete',$user_id);

            // else{

            //     update_user_meta($user_id, 'user_page_disp', "");
            // }


            return $user_id;
        }

        return 0;

    }

    /****************************************************
     **   関連名取得
     ******************************************************/
    function getConnectionName($connect_id ){
        require_once (dirname(__FILE__)."/../../class/ConnectionGroupClass.php");
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
    function change_user_meta($chage_data,$filed_name,$change_user_id){
        if (isset($chage_data) && $chage_data != "") {
            $check = update_user_meta($change_user_id, $filed_name, $chage_data);

            if (!$check) return 0;
            else return true;    // 更新時に更新フィールドにtrueを戻す
        }
    }

    // 更新者情報取得
    function get_updata_user_info($data){
        $user_info = get_userdata($data['edit_changer']);   //更新者情報取得
        $ret_data = get_user_meta($user_info->ID,'last_name',true).get_user_meta($user_info->ID,'first_name',true);
        return $ret_data;
    }

    /****************************************************
     **   ユーザーテーブル表示
     ******************************************************/
    function makeUserTable($users,$connection_id = 0){
        
        require_once (dirname(__FILE__)."/../../class/ConnectionGroupClass.php");
        $user_count_no = 0;
        $user_data = array();

        foreach ($users as $user) {
            $born_year = get_user_meta($user->ID, 'born_year', true).'/'.get_user_meta($user->ID, 'born_month', true).'/'.get_user_meta($user->ID, 'born_day', true);
            $report_born_year = get_user_meta($user->ID, 'report_born_year', true).'/'.get_user_meta($user->ID, 'report_born_month', true).'/'.get_user_meta($user->ID, 'report_born_day', true);
            $is_delete = get_user_meta($user->ID, 'is_delete', true);
    
            if($is_delete) continue;
    
            $user_regitsted_mail = "";
    
            $user_regitsted_mail = getParentAddress($user->user_email);// 家族なら親のメールアドレスを表示させる
            
            $connection_group_data = new ConnectionGroupClass(); //管理データ

            // 関連グループデータ
            $connection_id = get_user_meta($user->ID, 'connect_group', true);
            $connect_disp = get_user_meta($user->ID, 'connect_group_disp', true);
            

            //関連グループ一覧表示時 かつ　追加でないとき　→　複数関連に対応できるように変更
            if(isset($_GET['group_id'])){
                $user_connection_group_data = $connection_group_data->GetUserConnectGroupData($user->ID,$_GET['group_id']); //ユーザーの関連データ取得
                if(!$user_connection_group_data['registed_flg'] && isset($_GET['squeeze_group'])) continue;
                if(!$user_connection_group_data['registed_flg'] && isset($_GET['group_id']) && !isset($_GET['group_user_add'])) continue;
            }

            // 顧客一覧での関連絞り込み機能
            if(isset($_GET['user-squeeze-connection'])){
                $user_connection_group_data = $connection_group_data->GetUserConnectGroupData($user->ID,$_GET['user-squeeze-connection']); //該当グループに属しているかチェック

                if(!$user_connection_group_data['registed_flg']) continue;
            }
            // 顧客一覧でのグループ絞り込み機能
            if(isset($_GET['user-squeeze-group'])){
                

                if(!$user_connection_group_data['registed_flg']) continue;
            }

            // 関連グループ追加時すでに追加済みは省く
            // if(isset($_GET['group_user_add']) && $_GET['group_id'] == $connection_id) continue;
            
            $regist_date = new DateTime($user->user_registered,);
    
            $user_data[] = array(
                'ID' => $user->ID,
                'user_unique_id' => get_user_meta($user->ID, 'user_unique_id', true),
                '' => '',
                'user_login' => $user->user_login,
                'first_name' => get_user_meta($user->ID, 'first_name', true),
                'last_name' => get_user_meta($user->ID, 'last_name', true),
                'first_name_kana' => get_user_meta($user->ID, 'first_name_kana', true),
                'last_name_kana' => get_user_meta($user->ID, 'last_name_kana', true),
                'sex' => get_user_meta($user->ID, 'sex', true),
                'tel' => get_user_meta($user->ID, 'billing_phone', true).'-'.get_user_meta($user->ID, 'billing_phone2', true).'-'.get_user_meta($user->ID, 'billing_phone2', true),
                'post_code' => get_user_meta($user->ID, 'billing_postcode', true),
                'city' => get_user_meta($user->ID, 'billing_city', true),
                'line' => get_user_meta($user->ID, 'line_id', true),
                'born' => $born_year,
                'report_born' => $report_born_year,
                'age' => calculateAge($born_year),
                'regist_day' => $regist_date->format('Y-m-d'),
                // 'input_introduction_name' => get_user_meta($user->ID, 'input_introduction_name', true),
                // 'input_introduction_id' => get_user_meta($user->ID, 'input_introduction_id', true),
                'input_inflow' => get_user_meta($user->ID, 'input_inflow', true),
                'inflow_remarks' => get_user_meta($user->ID, 'inflow_remarks', true),
                'input_remarks' => get_user_meta($user->ID, 'user_remarks', true),
                // TODO:紹介者のURLリンク
            );

            // 紹介者情報
            if(get_user_meta($user->ID, 'input_introduction_id', true) != 'x'){
                $introduction_id = get_user_meta($user->ID, 'input_introduction_id', true);
                $introduction_name = get_user_meta($introduction_id, 'last_name', true).get_user_meta($introduction_id, 'first_name', true);
                $user_data[$user_count_no]['input_introduction_name'] = '<a href="'.getURLSetSlag("admin-member-edit").'?user_id='.$introduction_id.'">'.$introduction_name.'</a>';
            }else{
                $user_data[$user_count_no]['input_introduction_name'] = "";

            }

            // 浄霊シート情報取得
            if(is_page('admin-member-list')){
                
                require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
                $spiritType = new SpiritTypeClass(); //管理データ
                $spiritTypeArray = $spiritType->getSpiritType();    //浄霊タイプ

                $user_split_data = json_decode(get_user_meta($user->ID,'spirit_data',true));
                if($user_split_data != NULL){

                    foreach ($user_split_data as $key => $value) { 
                        $sprit_type = get_field('acf_acf_purespirit_type',$value);
                        $is_delete = get_field("is_delete", $value);
                        
                        if($is_delete) continue;    //削除されたシートは表示しない
    
                        // 依頼日格納
                        $user_data[$user_count_no][get_field('acf_pure_spirit_title',get_field('acf_acf_purespirit_type',$value))] = get_field('acf_purespirit_requested_date',$value);
                    }
                    
                    // 空データの作成
                    // 現在のタイトルが配列にあるかチェック
                    foreach ($spiritTypeArray as $key => $value) {
                        foreach ($value as $key_num => $value_num) {
                            if (!array_key_exists($value_num["title"], $user_data[$user_count_no])) {
                                $user_data[$user_count_no][$value_num["title"]] = "";
                            }
                        }
                    }
                }else{
                    // 浄霊データなければすべて空
                    foreach ($spiritTypeArray as $key => $value) {
                        foreach ($value as $key_num => $value_num) {
                            $user_data[$user_count_no][$value_num["title"]] = "";
                        }
                    }
                }

            }

            $check_user_email = get_user_meta($user->ID, 'no_mail', true);
            if($check_user_email == "no_mail"){
                $user_data[$user_count_no]['user_email'] = "";
            }else{

                $user_data[$user_count_no]['user_email'] = $user_regitsted_mail;
            }
    
            if($connect_disp != ""){
                $user_data[$user_count_no]['user_connection_disp'] = json_decode($connect_disp);
            }else{
                $user_data[$user_count_no]['user_connection_disp'] = "";
            }
            
            $user_count_no++;
        }

        return $user_data;
    }

    /****************************************************
     **   ユーザーメールアドレス取得
     ******************************************************/
    function getUserEmails(){
        
        $users = get_users();
        $registed_users_email = array();
        // メールアドレスのカウント
        foreach ($users as $user) {
            $registed_users_email[] = $user->user_email;
        }

        return $registed_users_email;
    }


    /****************************************************
     **   ユーザー重複チェック
     ******************************************************/
    function checkUserRegist($post_data,$remort = false){

        $res_data = array();
        $res_data['res_msg'] = "";      //登録メッセージ
        $res_data['regist_error'] = ""; //登録エラー
        $res_data['res_data'] = "";     //登録でーた
        $res_data['registed_id'] = "";     //登録済みID

        $users = get_users();

        // 既存ユーザー情報
        $registed_users_email = array();
        $registed_users_address = array();
        $registed_users_fullname = array();
        $registed_users_last_name = array();
        $registed_users_tel = array();
        $registed_users_unique = array();

        $registed_address_flg = false;  //既存住所重複
        $registed_tel_flg = false;      //既存電話番号重複
        $registed_email_flg = false;    //既存メアド重複

        $check_ids = "";    //同姓同名のID

        // メールアドレスのカウント
        foreach ($users as $user) {
            $email = $user->user_email;
            $last_name = get_user_meta($user->ID, 'last_name', true);
            $first_name = get_user_meta($user->ID, 'first_name', true);
            $address = get_user_meta($user->ID, 'billing_city', true);
            $unique_id = get_user_meta($user->ID, 'user_unique_id', true);
            $tel = get_user_meta($user->ID,'billing_phone',true).get_user_meta($user->ID,'billing_phone2',true).get_user_meta($user->ID,'billing_phone3',true);
    
            // 既存ユーザーでの浄霊予約の際のチェック
            if(isset($post_data['input_registered_id']) && $post_data['input_registered_id'] == $unique_id){
                
                // 既存ユーザーはここで戻す
                $res_data['res_data'] = $user->ID;
                $res_data['res_msg'] = "既存ユーザーです";

                return $res_data;
            }

            if (!in_array($email,$registed_users_email)) {
                $registed_users_email[$user->ID] = $email;
                $registed_users_fullname[$user->ID] = $last_name.$first_name;
                $registed_users_last_name[$user->ID] = $last_name;
                $registed_users_address[$user->ID] = $address;
                $registed_users_tel[$user->ID] = $tel;
                $registed_users_unique[$user->ID] = $unique_id;
            }
        }

        //同姓同名チェック
        $check_ids = array_keys( $registed_users_fullname,$post_data["input_last_name"].$post_data["input_first_name"]);
        
        // 登録不可処理
        if($check_ids !== false){
            
            foreach ($check_ids as $check_id) {
                // $res_data['res_msg'] =  "同姓同名がいます。ID".$registed_users_unique[$check_id];
                $res_data['res_msg'] =  "同姓同名がいます。ID";
                $res_data['registed_id'] = $check_id;

                if($registed_users_address[$check_id] == $post_data['input_address1']){
                    $res_data['res_msg'] .=  "<br>同一住所の為、登録できません";
                    // $res_data['regist_error'] = true;
                    $registed_address_flg = true;       //同一住所
                }
                if($registed_users_email[$check_id] == $post_data['input_user_email']){
                    $res_data['res_msg'] .= "<br>同一メールの為、登録できません";
                    // $res_data['regist_error'] = true;
                    $registed_email_flg = true;
                }
                // リモート浄霊以外は電話番号チェック省く
                if(!$remort && $registed_users_tel[$check_id] == $post_data['input_tel_1'].$post_data['input_tel_2'].$post_data['input_tel_3']){
                    $res_data['res_msg'] .= "<br>同一電話番号の為、登録できません";
                    
                    $registed_tel_flg = true;       //同一番号
                }
            }

            // 重複があれば登録エラー
            if($registed_address_flg || $registed_email_flg || $registed_tel_flg  ){

                // 但し、住所電話番号が同じ　かつ
                // メアドが異なる場合は上書き
                // echo "<br>同姓チェック<br>";
                if($registed_address_flg && $registed_tel_flg && !$registed_email_flg){

                    $change_mail = wp_update_user([
                        'ID' => $check_ids[0],
                        'user_email' => $post_data['input_user_email'],
                    ]);
                    
                    $res_data['res_msg'] .= "<br>電話番号、住所同一の為、メールアドレスを上書き保存";
                    
                    return $res_data;
                }else{

                    $res_data['regist_error'] = true;
                }
            }

            
            if($res_data['regist_error'] == ""){
                // $res_data['res_msg'] .= "<br>同一人物ではないので登録します";
                $res_data['res_msg'] .= "<br>登録しました";
                $res_data['res_data'] = setNewUserData($post_data);
            }else{
                // return $res_data['registed_id'];
            }
        }
        
        // 同一メールでも登録許可
        if(empty($check_ids)){

            $same_flag = false;
            $email_id = array_search( $post_data['input_user_email'],$registed_users_email);

            if ($email_id !== false) {
                $res_data['res_msg'] =  "同じメールアドレスがいます。ID".$registed_users_unique[$email_id];
    
                // 家族番号として_#&#_をつける
                if($registed_users_address[$email_id] == $post_data['input_address1']){
                    $res_data['res_msg'] .=  "<br>同一住所の為、家族として登録します";
                    $same_flag = true;
                }else if($registed_users_last_name[$email_id] == $post_data['input_last_name']){
                    $res_data['res_msg'] .=  "<br>同一姓の為、家族として登録します";
                    $same_flag = true;
                }else if($remort){
                    $res_data['res_msg'] .=  "<br>リモート浄霊の為、登録します";
                    $same_flag = true;
                }
            }

            // 登録処理
            if($same_flag && isset($_POST['remote_no'])){
                    
                $res_data['regist_error'] = true;
                
                $res_data['res_data'] = setNewUserData($post_data,$post_data['remote_no']);

                if(isset($res_data['res_data']->errors)){

                    $res_data['res_msg'] .= "<br>".$res_data['res_data']->errors;
                    
                }
    
            }else{
                //通常登録
                $res_data['res_data'] = setNewUserData($post_data);
            }
        }

        return $res_data;
    }

    /****************************************************
     **   登録フォームからの顧客データ登録
     **   ret:登録ユーザーID
     ******************************************************/
    function makeRegistFormData($post_data){

        //メールアドレスの重複チェックかつ申込者登録    $res_data['res_data']にID情報登録される
        $apply_user = checkUserRegist($post_data);
        // $apply_user['res_data'] = 1;   //テスト中：固定でID出しておく

        // $sheet_type = $_POST["type_id"];    // 浄霊タイプ

        $new_regist = false;                // 新規登録完了
        $target_regist_flg = false;         // 対象者フラグ
        $remote_flg = false;                // 一斉浄霊フラグ

        $ret = array();                     // 戻り値
        $target_data = array();             // 対象者データ
        $remote_data = array();             //一斉浄霊データ
        $remote_user = array();
        $remote_no = 0;                     //一斉浄霊番号

        // 新規登録処理　$apply_user['res_data']：新規登録ID
        if($apply_user['res_data'] != ""){
            $new_regist = true;

             //申込者ID保存
            $ret['application_user'] = array();
            $ret['application_user'][] = $apply_user['res_data'];
        }else if($apply_user['registed_id'] != ""){

            $ret['application_user'] = array();
            $ret['application_user'][] = $apply_user['registed_id'];
        }
        
        // 対象者チェック
        if(isset($post_data['input_target_last_name'])){

            if($new_regist && $post_data['input_target_last_name'] != ""){
                // 新規 かつ 対象者同じ　→　新規の質問のみ更新
                // 新規 かつ 対象者違う
                $target_regist_flg = true;
            }
    
            if($apply_user['registed_id'] != "" && $post_data['input_target_last_name'] != ""){
                // 既存 かつ 対象者同じ　→　新規の質問のみ更新
                // 既存 かつ 対象者違う
                $target_regist_flg = true;
            }
        }

        // 質問情報追加
        foreach ($post_data as $key => $value) {
            if(strpos($key,'question') !== false){
                $target_data[$key] = $value;
            }

            // question_587があればリモート一斉浄霊対応
            if (strpos($key, 'question_587') !== false) {

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
            }
            
        }        

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
            $target_data['input_introduction_id'] = get_user_meta($apply_user['registed_id'],'user_unique_id',true);

            // 対象者登録
            $target_user = checkUserRegist($target_data);

            //対象者ID保存
            $ret['target_user'] = array();
            $ret['target_user'][] = $target_user['registed_id'];

        }else if($remote_flg){
        //リモート浄霊の場合
            $remote_res = array();
            foreach($remote_data as $data){
                $remote_res = checkUserRegist($data,$remote_flg);
                // var_dump($remote_res);  //削除okd
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

        return $ret;

    }


/****************************************************
 **  YNモーダル表示
 ******************************************************/
function YNModalDisp()
{
	?>

	<?php //表示用    ?>
	<div id="modal" class="modal js-modal">
		<div class="modal__bg js-modal-close"></div>
		<div class="modal__content pt20 pm20 pl20 pr20">

			<div class="modal-text-area" id="disp-text"></div>
			<div class="flex-area">

				<div class="yn-btn orange js-modal-ok">はい</div>
				<div class="yn-btn gray js-modal-back" id="moda-back">戻る</div>
			</div>
		</div>
	</div>

<?php } ?>



<?php 

/****************************************************
 **   ユーザーテーブル注釈表示
******************************************************/
function DispAnnotation(){
?>
    <div class="table-annotation">
        ユーザーのテーブル情報をスクロールさせる際は、1度テーブルをクリックしてから矢印キーで動かす事が可能です。<br>
        また、shiftキーを押しながらマウスホイールでも動かく事が可能です。
    </div>
<?php 
}


/****************************************************
 ** 先生判断
 ******************************************************/
function judgeUserRole(){
    $login_user_role = getUserRoles();
    $teacher_role = "editor";

    if($login_user_role[0] == $teacher_role) return true;
    else return false;
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
 **  ユニックスタイム取得
 ******************************************************/
function getUnixTime()
{
    //時間帯を変更
    return time();//ダブり禁止用UNIX TIME 
}

/****************************************************
 **  変更履歴表示
 ******************************************************/
function DispChangeLog($user_id){
    // 更新者情報
    $user_edit_day = get_user_meta($user_id,'user_edit_day',true);    //jsonデータ取得
    if($user_edit_day != ""){

        $decoded_data = json_decode($user_edit_day, true);  //jsonデータ戻し
        $decoded_data_index = array_keys($decoded_data);
        $max_index = max($decoded_data_index);
        $user_edit_changer = get_updata_user_info($decoded_data[$max_index]);
    }

?>
    <div class="admin-spirit-subtitle-box"><div class="admin-spirit-menu-title">保存履歴</div></div>

    <?php if($user_edit_day != ""){ ?>

    <div class="">
        <?php 
            
            $max_flg = false;   //履歴が5件以上あるならボタン表示
            for ($i=$max_index; $i >=0 ; $i--) { 
                if($i +5 <=$max_index){
                    $max_flg = true;
                    break;
                }

                $update_list = $decoded_data[$i]['edit_date'] . '　　' .get_updata_user_info($decoded_data[$i]);

                foreach ($decoded_data[$i]['edit_content'] as $key => $value) {
                    $update_list .= '　　' .$key.'変更';
                }
                echo $update_list ."<br>";
            }
        ?>

    </div>
    <?php if($max_flg){ ?>
        <button type="button" id="form-back" class="form-btn gray" ><a href="<?php echo getURLSetSlag("admin-member-edit").'?user_id='.$check_user_id.'&all_change_list=on'; ?>" target="_blank">変更履歴をすべて見る</a></button>
    <?php 
        }
    }
}



/****************************************************
 **  画像モーダル表示
 ******************************************************/

// 画像モーダル表示
function DispImgmodal(){
?>
<style>
    .modal__content{
        width: auto;
        height: auto;
    }
</style>

    <div id="img-modal" class="modal js-modal">
		<div class="modal__bg js-modal-close" id="img-modal-close"></div>
		<div class="modal__content pt20 pm20 pl20 pr20" >
            <img class="" id="disp-img" src="" style="">
		</div>
	</div>

	<script>
        

        // ボタン表示
        function img_modal(img){

            document.getElementById('img-modal').style.display = 'block';
            document.getElementById('disp-img').src = img;

            // 戻る
            document.getElementById('img-modal-close').addEventListener('click', function() {
                console.log("cli");
                document.getElementById('img-modal').style.display = 'none';
            });

        }

	</script>
<?php 


}


/****************************************************
 **  画像削除モーダル表示
 ******************************************************/
function DeleteImgModalDisp()
{
	?>

	<?php //表示用    ?>
	<div id="modal" class="modal js-modal">
		<div class="modal__bg js-modal-close"></div>
		<div class="modal__content pt20 pm20 pl20 pr20"  style="height:360px">

			<div class="modal-text-area" id="disp-text">この画像を本当に削除しても宜しいでしょうか？</div>
			<div class="flex-area">

				<div class="yn-btn orange " id="modal-go">はい</div>
				<!-- <div class="yn-btn orange " id="last-modal-delete">いいえ</div> -->
                <div class="yn-btn gray js-modal-back" id="modal-back">いいえ</div>
			</div>
		</div>
	</div>

	<script>
        

        // ボタン表示
        function dispBtn(id){

            document.getElementById('modal').style.display = 'block';

            // 戻る
            document.getElementById('modal-back').addEventListener('click', function() {
                document.getElementById('modal').style.display = 'none';
            });

            // 削除実行
            document.getElementById('modal-go').addEventListener('click', function() {
                
                document.getElementById('result_change').name = 'result_change'; // 新しい名前に変更
                document.getElementById('result_change').value = id;
                document.getElementById('treatment_result_form').submit();
            });

        }
        // 施術提出削除ボタン表示
        function dispTreatmentBtn(id){

            document.getElementById('modal').style.display = 'block';

            // 戻る
            document.getElementById('modal-back').addEventListener('click', function() {
                document.getElementById('modal').style.display = 'none';
            });

            // 削除実行
            document.getElementById('modal-go').addEventListener('click', function() {
                
                document.getElementById('upload_submit_result').name = 'upload_submit_change'; // 新しい名前に変更
                document.getElementById('upload_submit_result').value = id;
                document.getElementById('acf_treatment_result_image_field').submit();
            });

        }

	</script>

<?php 
}

/****************************************************
 **  削除モーダル表示
 ******************************************************/
function DeleteModalDisp()
{
	?>

	<?php //表示用    ?>
	<div id="modal" class="modal js-modal">
		<div class="modal__bg js-modal-close"></div>
		<div class="modal__content pt20 pm20 pl20 pr20"  style="height:360px">

			<div class="modal-text-area" id="disp-text">選択したシートを削除しますか？</div>
			<div class="flex-area">

				<div class="yn-btn orange " id="js-modal-visible">非表示にする</div>
				<div class="yn-btn orange " id="js-modal-delete" onclick="checkLastDelete()">完全削除する</div>
				<div class="yn-btn orange " id="last-modal-delete" style="display:none">完全削除する</div>
			</div>
            <div class="yn-btn gray js-modal-back" id="moda-back">戻る</div>
		</div>
	</div>

	<script>
        
        var text = document.getElementById('disp-text');
        var delete_btn = document.getElementById('js-modal-delete');
        var last_delete_btn = document.getElementById('last-modal-delete');

        // ボタン表示
        function dispBtn(id){

            document.getElementById('modal').style.display = 'block';
            // 戻る
            document.getElementById('moda-back').addEventListener('click', function() {
                document.getElementById('modal').style.display = 'none';
                
                // 文章戻す
                text.innerHTML = "選択したシートを削除しますか？";
                last_delete_btn.style.display = "none";
                delete_btn.style.display = "block";
            });

            // 非表示
            document.getElementById('js-modal-visible').addEventListener('click', function() {
                hiddenSheet(id);
            });

            // 完全削除
            document.getElementById('last-modal-delete').addEventListener('click', function() {
                deleteSheet(id);
                
            });
        }

        // 削除確認
        function checkLastDelete(){

            // 文章変更
            text.innerHTML = "本当に削除しますか？";
            last_delete_btn.style.display = "block";
            delete_btn.style.display = "none";

            // モーダルの再表示
            document.getElementById('modal').style.display = 'none';    //一旦非表示にして再度表示

            setTimeout(function() {
                document.getElementById('modal').style.display = 'block';
            }, 500); // 1000ミリ秒 = 1秒
        }

        // シート非表示
        function hiddenSheet(id){

            document.getElementById('hidden_sheet_'+id).submit();
        }

        // シート削除
        function deleteSheet(id){

            document.getElementById('delete_sheet_'+id).submit();
        }


	</script>
    
<?php } ?>

