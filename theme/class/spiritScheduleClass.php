<?php 

require_once (dirname(__FILE__)."/spiritTypeClass.php");
require_once (dirname(__FILE__)."/spiritUserClass.php");


class SpiritScheduleClass
{
    //表示ステータス
    public const SCHEDULE_DISP_NOT = 0;//非表示
	public const SCHEDULE_DISP = 1;//表示中
    public const SCHEDULE_DISP_OVER = 2;//締切
    public const SCHEDULE_DISP_WAIT = 3;//待機中
    public const SCHEDULE_DISP_RESERVATION_OVER = 4;//予約人数オーバー


     //請求書の初期設定番号
     public const SCHEDULE_RECEIPT_SETTING_ID = 8625;
	

    /****************************************************
	 **  スケジュールデータを作成
	 ******************************************************/
	public function newSpritSchedule($post_data)
	{

        //同じUNIXTIMEがあるなら作成しない
        $unix_id = $this->checkSpritScheduleUnixtime( $post_data["unixtime"] );

        if($unix_id != "")
        {
            return $unix_id;
        }

        $user = wp_get_current_user();

        date_default_timezone_set('Asia/Tokyo'); 

        //今日の日付
        $today = date("Y-m-d H:i:s");

        $title =  $post_data["schedule_name"] . " " . $today;


        $my_post = array(
            'post_title' => $title,
            'post_type' => 'cpt_spirit_schedule', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );

        $program_id = wp_insert_post($my_post);

        if ($program_id) {
            
            $this->saveSpritSchedule( $program_id , $post_data);//保存
             
        }

        
        return $program_id;
    }


    /****************************************************
	 **  スケジュールデータを保存
	 ******************************************************/
	public function saveSpritSchedule( $program_id , $post_data)
	{
       
        if ($program_id) {
            // デフォルト値の設定
            $default_data = array(
                "schedule_name" => "",
                "schedule_type" => "",
                "schedule_date" => "",
                "schedule_time" => "",
                "schedule_clock" => "",
                "schedule_charge" => "",
                "schedule_slot" => "",
                "unixtime" => "",
                "schedule_disp" => "",
                "schedule_end" => "",
                "schedule_start" => "",
                "schedule_place" => "",
                "schedule_price" => "",
                "schedule_cancel_time" => "",
                "schedule_payment_type" => ""
            );

            // $post_dataとデフォルト値をマージ
            $post_data = array_merge($default_data, array_intersect_key($post_data, $default_data));

            update_field("acf_spirit_schedule_disp_title", $post_data["schedule_name"], $program_id);//表示タイトル
            update_field("acf_spirit_schedule_type", $post_data["schedule_type"], $program_id);//表示タイプ
            update_field("acf_spirit_schedule_date", $post_data["schedule_date"], $program_id);//実地日
            update_field("acf_spirit_schedule_time", $post_data["schedule_time"], $program_id);//実地時間
            update_field("acf_spirit_schedule_time_minutes", $post_data["schedule_clock"], $program_id);//実地時間(時計)
            update_field("acf_spirit_schedule_charge", $post_data["schedule_charge"], $program_id);//実地時間
            update_field("acf_spirit_schedule_cancel_time", $post_data["schedule_cancel_time"], $program_id);//キャンセル可能時間

            $slot = $post_data["schedule_slot"];
            if($slot == "") {
                $slot = 0;
            }

            update_field("acf_spirit_schedule_slots", $slot, $program_id);//募集人数
            update_field("acf_spirit_schedule_unixtime", $post_data["unixtime"], $program_id);//ユニックスタイム
            update_field("acf_spirit_schedule_disp", $post_data["schedule_disp"], $program_id);//表時
            update_field("acf_spirit_schedule_end", $post_data["schedule_end"], $program_id);//締め切り日時
            update_field("acf_spirit_schedule_start", $post_data["schedule_start"], $program_id);//申込表示
            update_field("acf_spirit_schedule_price", $post_data["schedule_price"], $program_id);//値段

            //場所指定
            update_field("acf_spirit_schedule_place", $post_data["schedule_place"], $program_id);

            $group_id = '8065';
            $fields = acf_get_fields($group_id);

            foreach ($fields as $field => $data) {
                if($post_data["schedule_place"] != "") {
                    update_field($data["name"], get_field($data["name"], $post_data["schedule_place"]), $program_id);
                } else {
                    update_field($data["name"], "", $program_id);
                }
            }

            //支払い方法
            if(isset($post_data["schedule_payment_type"]))
            {
                update_field("acf_spirit_schedule_payment_type", $post_data["schedule_payment_type"], $program_id);
            }
            else{
                update_field("acf_spirit_schedule_payment_type", array(), $program_id);
            }
        }

        return $program_id;
    }


     /****************************************************
	**  スケジュールのユニックスタイム検索
	******************************************************/
	public function checkSpritScheduleUnixtime( $unixtime )
	{
        $wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_spirit_schedule', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        $count = 0;

        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                $num = get_field('acf_spirit_schedule_unixtime');

                if( $unixtime == $num)
                {
                    return get_the_ID();
                }

            endwhile;
        endif;

        return "";
    }


    /****************************************************
	 **  スケジュールデータの取得
	 ******************************************************/
	public function getSpritScheduleList( $user_id = "")
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_spirit_schedule',//'cpt_spirit_status', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


		//ソート用
        $sort_array = array();

		//データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                if($user_id != ""){
                    $user_id_data = get_field('acf_spirit_schedule_charge' ,get_the_ID());

                    if($user_id_data != $user_id){
                        continue;
                    }
                }
                $sort_array[get_the_ID()] = get_the_ID();

            endwhile;
        endif;

        return $sort_array;

	}

    /****************************************************
	 **  スケジュールデータの取得(全てを日付順に取得)
	 ******************************************************/
	public function getSpritScheduleAllList( $group_id = "", $user_id = "")
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_spirit_schedule',//'cpt_spirit_status', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


		//ソート用
        $schedule__array = array();
        $schedule_base_array = array();


        $schedule_sort_array = array();

		//データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                if($group_id != ""){
                    
                    $type_id = get_field('acf_spirit_schedule_type' ,get_the_ID());

                    $group_id_data = get_field('acf_pure_spirit_type_min' ,$type_id);

                   
                    if($group_id_data != $group_id){
                        continue;
                    }
                }

                if($user_id != ""){
                    $user_id_data = get_field('acf_spirit_schedule_charge' ,get_the_ID());

                    if($user_id_data != $user_id){
                        continue;
                    }
                }

                 //実行日を取得
                 $schedule_date = get_field('acf_spirit_schedule_date' ,get_the_ID());


                 $date_time = new DateTime($schedule_date);
 
 
                 $post_yaer = $date_time->format('Y');
                 $post_month = $date_time->format('n');
                 $post_day = $date_time->format('j');

                 //時間を取得
                 $post_time = get_field('acf_spirit_schedule_time' ,get_the_ID());

                 if($post_time != "")
                 {
                    $post_time = $post_time.":00";
                 }
                 else{
                    $post_time = get_field('acf_spirit_schedule_time_minutes' ,get_the_ID());
                 }

                //ユニックスタイムに変換する
                $post_unixtime = strtotime($post_yaer."-".$post_month."-".$post_day." ".$post_time);

                if( !isset($schedule__array[ $post_yaer ][ $post_month ][ $post_day ] ) )
                {
                    $schedule__array[ $post_yaer ][ $post_month ][ $post_day ] = array();
                    $schedule_sort_array[ $post_yaer ][ $post_month ][ $post_day ] = array();
                }

                // 時間とIDの組み合わせを保存
                $schedule_sort_array[ $post_yaer ][ $post_month ][ $post_day ][] = array(
                    'id' => get_the_ID(),
                    'time' => $post_unixtime
                );

            endwhile;

            if(count($schedule_sort_array) > 0)
            {
                // 各日付ごとにソートして配列を構築
                foreach ($schedule_sort_array as $year => $months) {
                    foreach ($months as $month => $days) {
                        foreach ($days as $day => $items) {
                            // 時間とIDでソート
                            usort($items, function($a, $b) {
                                if ($a['time'] === $b['time']) {
                                    return $a['id'] - $b['id']; // 同じ時間の場合はIDで昇順
                                }
                                return $a['time'] - $b['time']; // 時間で昇順
                            });

                            // ソートされた順番でIDを配列に設定
                            $schedule__array[$year][$month][$day] = array_map(function($item) {
                                return $item['id'];
                            }, $items);
                        }
                    }
                }
            }
        endif;

        

        return $schedule__array;

	}


    /****************************************************
	 **  スケジュールデータの取得(請求月で取得)
	 ******************************************************/
	public function getSpritScheduleReceiptList( $receipt_year , $receipt_month  )
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_spirit_schedule',//'cpt_spirit_status', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


        //ユーザークラス
        $spiritUser = new SpiritUserClass();

		//ソート用
        $schedule_sort_array = array();

		//データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                
                $schedule_array = $this->getSpritScheduledetail( get_the_ID() );

              
                if($schedule_array["予約人数"] == 0){
                    continue;
                }

                $sheet_array = $spiritUser->getUserSpritApplicantSheet($schedule_array["予約者データ"][0]["ID"],$schedule_array["予約者"][0]);

                if($sheet_array[$schedule_array["予約者"][0]]["請求書発行"] != 1){ 
                    continue;
                }

                $exe_pay_amount = get_field('acf_previous_execution_pay',$schedule_array["予約者"][0]);
                if($exe_pay_amount == "") $exe_pay_amount = 0;


                $invoice_date_year = get_field('acf_previous_execution_invoice_date_year' ,$schedule_array["予約者"][0]);
                $invoice_date_month = get_field('acf_previous_execution_invoice_date' ,$schedule_array["予約者"][0]);

                if($invoice_date_year == $receipt_year && $invoice_date_month == $receipt_month)
                {

                    //var_dump($schedule_array);


                    //担当者ID
                    $charge_id = $schedule_array["担当者"];


                    if(!isset($schedule_sort_array[ $charge_id ] ) )
                    {
                        $schedule_sort_array[ $charge_id ] = array();
                        $schedule_sort_array[ $charge_id ]["ID"] = array();
                        $schedule_sort_array[ $charge_id ]["担当者"] = $schedule_array["担当者名前"];
                        $schedule_sort_array[ $charge_id ]["合計支払い金額"] = 0;
                        $schedule_sort_array[ $charge_id ]["施術数"] = 0;
                        $schedule_sort_array[ $charge_id ]["施術データ"] = array();
                    }

                    $schedule_sort_array[ $charge_id ]["ID"][] = get_the_ID();


                    $sprit_name = $schedule_array["施術名"];


                    if(!isset($schedule_sort_array[ $charge_id ]["施術データ"][$sprit_name]))
                    {
                        $schedule_sort_array[ $charge_id ]["施術データ"][ $sprit_name] = array();
                        $schedule_sort_array[ $charge_id ]["施術データ"][ $sprit_name]["ID"] = array();
                        $schedule_sort_array[ $charge_id ]["施術データ"][ $sprit_name]["施術名"] = $schedule_array["表示名"];
                        $schedule_sort_array[ $charge_id ]["施術データ"][ $sprit_name]["施術数"] = 0;
                        $schedule_sort_array[ $charge_id ]["施術データ"][ $sprit_name]["施術金額"] = 0;
                        $schedule_sort_array[ $charge_id ]["施術データ"][ $sprit_name]["施術支払い金額"] = 0;
                    }

                    $schedule_sort_array[ $charge_id ]["施術データ"][ $sprit_name]["施術数"]++;
                    $schedule_sort_array[ $charge_id ]["施術データ"][ $sprit_name]["ID"][] = get_the_ID();  
                    $schedule_sort_array[ $charge_id ]["施術数"]++;
                    $schedule_sort_array[ $charge_id ]["施術データ"][ $sprit_name]["施術金額"] += $schedule_array["価格"];
                    $schedule_sort_array[ $charge_id ]["施術データ"][ $sprit_name]["施術支払い金額"] += $exe_pay_amount;
                    $schedule_sort_array[ $charge_id ]["合計支払い金額"] += $exe_pay_amount;
                }

                
            endwhile;

        endif;


        //var_dump($schedule_sort_array);
        

        return $schedule_sort_array;

	}

    
    

    /****************************************************
	 **  指定した月のスケジュールデータの取得
	 ******************************************************/
	public function getSpritScheduleMonthList($check_year,$check_month,$user_id = "")
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_spirit_schedule',//'cpt_spirit_status', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        //指定よりも前の月
        $befor_year = "";
        $befor_month = $check_month - 1;

        if($befor_month == 0)
        {
            $befor_month = 12;
            $befor_year = $check_year - 1;
        }
        else{
            $befor_year = $check_year;
        }


        //指定よりも次の月
        $after_year = "";
        $after_month = $check_month + 1;

        if($after_month == 13)
        {
            $after_month = 1;
            $after_month = $check_year + 1;
        }
        else{
            $after_year = $check_year;
        }


		//ソート用
        $schedule__array = array();

        $schedule__array[ $check_year ] = array();//現在の年を入れる
        $schedule__array[ $check_year ][ $check_month ] = array();//現在の月を入れる

        //次の月を作る
        if(!isset( $schedule__array[ $after_year ] ))
        {
            $schedule__array[ $after_year ] = array();
        }

        $schedule__array[ $after_year ][ $after_month ] = array();


        //前の月を作る
         if(!isset( $schedule__array[ $befor_year ] ))
        {
            $schedule__array[ $befor_year ] = array();
        }

        $schedule__array[ $befor_year ][ $befor_month ] = array();


		//データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                if($user_id != ""){
                    $user_id_data = get_field('acf_spirit_schedule_charge' ,get_the_ID());

                    if($user_id_data != $user_id){
                        continue;
                    }
                }

                //実行日を取得
                $schedule_date = get_field('acf_spirit_schedule_date' ,get_the_ID());


                $date_time = new DateTime($schedule_date);


                $post_yaer = $date_time->format('Y');
                $post_month = $date_time->format('n');
                $post_day = $date_time->format('j');

               
                if(($post_yaer == $check_year && $post_month == $check_month) || ($post_yaer == $befor_year && $post_month == $befor_month) || ($post_yaer == $after_year && $post_month == $after_month))
                {

                    if( !isset($schedule__array[ $post_yaer ][ $post_month ][ $post_day ] ) )
                    {
                        $schedule__array[ $post_yaer ][ $post_month ][ $post_day ] = array();
                    }

                    array_push($schedule__array[ $post_yaer ][ $post_month ][ $post_day ],get_the_ID());
                }


            endwhile;
        endif;

        return $schedule__array;

	}


     /****************************************************
	 **  スケジュールデータの詳細
	 ******************************************************/
	public function getSpritScheduledetail( $program_id )
	{


        $spiritTypeData = new SpiritTypeClass(); //浄霊タイプデータ

        $spiritType = $spiritTypeData->getSpiritTypeKeyTypeNum();

        $schedule_array = array();

        //var_dump($spiritType);

        $schedule_array["ID"] = $program_id;
        $schedule_array["表示名"] = get_field('acf_spirit_schedule_disp_title' ,$program_id); //表示名
        $schedule_array["施術名"] = get_field('acf_spirit_schedule_type' ,$program_id); //施術名
        $schedule_array["施術名表示"] = get_field('acf_pure_spirit_disp_title' ,$schedule_array["施術名"]); //施術名
        if($schedule_array["施術名表示"] == "")
        {
            $schedule_array["施術名表示"] = get_field('acf_pure_spirit_title' ,$schedule_array["施術名"]);
        }

        $schedule_array["担当者"] = get_field('acf_spirit_schedule_charge' ,$program_id); //担当者
        $schedule_array["担当者名前"] = "";

        if($schedule_array["担当者"] != "")
        {
            $user = get_userdata($schedule_array["担当者"]);
            $schedule_array["担当者名前"] = $user->display_name;
           
        }

        

        if(isset($spiritType[ $schedule_array["施術名"] ]))
        {
            $schedule_array["施術名管理"] = $spiritType[ $schedule_array["施術名"] ]["title"]; //施術名(管理)
            $schedule_array["施術名会員"] = $spiritType[ $schedule_array["施術名"] ]["disp"]; //施術名(会員)
            $schedule_array["施術グループ"] = $spiritType[ $schedule_array["施術名"] ]["group"]; //施術グループ
            $schedule_array["施術時間"] = $spiritType[ $schedule_array["施術名"] ]["time"]; //施術時間
            $schedule_array["表示カラー"] = $spiritType[ $schedule_array["施術名"] ]["color"]; //表示カラー
        }
        else{
             
           $schedule_array["施術名管理"] = "";
           $schedule_array["施術名会員"] = "";
           $schedule_array["施術グループ"] = "";
           $schedule_array["施術時間"] = "";
           $schedule_array["表示カラー"] = "#EAD2D2";
        }



        //表示名はグループによって変更（相談は担当者名を入れる）
       /* if($schedule_array["施術グループ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN)
        {
            $schedule_array["表示名"] = $schedule_array["担当者名前"];
        }
            */


       
        if( $schedule_array["施術名会員"] == "")
        {
             $schedule_array["施術名会員"] = $schedule_array["施術名管理"];
        }

        $schedule_array["人数"] = get_field('acf_spirit_schedule_slots' ,$program_id); //人数
        $schedule_array["実行日"] = get_field('acf_spirit_schedule_date' ,$program_id); //実行日
        $schedule_array["実行年月日"] = "";

        if($schedule_array["実行日"] != "")
		{
			$date_time = new DateTime($schedule_array["実行日"]);

			$schedule_array["実行年月日"] = $date_time->format('Y年n月j日');
		}

        $schedule_array["実行時間"] =  get_field('acf_spirit_schedule_time' ,$program_id); //実行時間
        $schedule_array["実行時間分"] =  get_field('acf_spirit_schedule_time_minutes' ,$program_id); //実行時間

        $schedule_array["実行時間表示"] ="";

        if($schedule_array["実行時間"] != "")
        {
            $schedule_array["実行時間表示"] = $schedule_array["実行時間"].":00";
        }

        if($schedule_array["実行時間分"] != "")
        {
            $schedule_array["実行時間表示"] = $schedule_array["実行時間分"];
        }

        $schedule_array["表示"] =  get_field('acf_spirit_schedule_disp' ,$program_id); //表示

        $schedule_array["価格"] =  get_field('acf_spirit_schedule_price' ,$program_id); //価格

        if($schedule_array["価格"] == "")
        {
            $schedule_array["価格"] = 0;
        }

        $schedule_array["締切"] =  get_field('acf_spirit_schedule_end' ,$program_id); //締切
        $schedule_array["実締切"] =  $this->getSpritScheduleLimit( $program_id );//実際の締切日

        $schedule_array["締切年月日"] = "";

        if($schedule_array["締切"]!= "")
		{
			$date_time = new DateTime($schedule_array["締切"]);

			$schedule_array["締切年月日"] = $date_time->format('Y年n月j日');
		}

        if($schedule_array["実締切"]!= "")
		{
			$date_time = new DateTime($schedule_array["実締切"]);

			$schedule_array["実締切年月日"] = $date_time->format('Y年n月j日');
		}


        $schedule_array["開始"] =  get_field('acf_spirit_schedule_start' ,$program_id); //開始時間
        $schedule_array["開始年月日"] = "";

        if($schedule_array["開始"]!= "")
		{
			$date_time = new DateTime($schedule_array["開始"]);

			$schedule_array["開始年月日"] = $date_time->format('Y年n月j日');
		}

         //現在の予約人数(現在作成中)
        $schedule_array["予約者"] =  $this->getScheduleSetSpirit($program_id);

        $schedule_array["予約人数"] =  $this->getScheduleReservationSlots( $schedule_array["予約者"]);

        $schedule_array["予約者データ"] =  "";

        if($schedule_array["予約者"] != "")
        {
            $schedule_array["予約者データ"] =  array();

            $user_count = 0;
            foreach($schedule_array["予約者"] as $key => $value)
            {
                $user_data = get_userdata( get_field('acf_purespirit_id' ,$value));

                // ユーザーが存在するかチェック
                if ($user_data) {
                    $schedule_array["予約者データ"][$user_count] = array(
                        "ID" => $user_data->ID,
                        "名前" => $user_data->display_name,
                        "ナマエ" => $user_data->first_name_kana . " " . $user_data->last_name_kana,
                        "メール" => $user_data->user_email,
                        "シートID" => $value,
                        "電話番号" => $user_data->billing_phone ."-".$user_data->billing_phone2 ."-".$user_data->billing_phone3
                    );
                } else {
                    // ユーザーが存在しない場合はデフォルト値を設定
                    $schedule_array["予約者データ"][$user_count] = array(
                        "ID" => "",
                        "名前" => "不明なユーザー",
                        "ナマエ" => "",
                        "メール" => "",
                        "シートID" => "",
                        "電話番号" => ""
                    );
                }

                $user_count++;
            }
        }


        $schedule_array["表示ステータス"] = "";


        if($schedule_array["人数"] > 0)//予約人数が入っている
        {
            if($schedule_array["人数"] <= $schedule_array["予約人数"])
            {
                $schedule_array["表示ステータス"] = SpiritScheduleClass::SCHEDULE_DISP_RESERVATION_OVER;//人数オーバー
            }
        }


        if($schedule_array["表示ステータス"] == "")//何も入ってない
        {
            if($schedule_array["表示"] == 1)
            {
                if($this->checkSpritScheduleLimit($program_id))
                {
                    if(!$this->checkSpritScheduleStart($program_id))
                    {
                        $schedule_array["表示ステータス"] = SpiritScheduleClass::SCHEDULE_DISP_WAIT;//待機中
                    }
                    else{
                        $schedule_array["表示ステータス"] = SpiritScheduleClass::SCHEDULE_DISP;//表示中
                    }
                                             
                }
                else{
                   $schedule_array["表示ステータス"] = SpiritScheduleClass::SCHEDULE_DISP_OVER;//締め切り
                }
            }
            else{
                //非表示
                $schedule_array["表示ステータス"] = SpiritScheduleClass::SCHEDULE_DISP_NOT;
            }
        }


        $schedule_array["場所"] =  get_field('acf_spirit_schedule_place' ,$program_id); //場所
        $schedule_array["場所ステータス"] = array();


        if( $schedule_array["場所"] != "")
        {
            $palce_id = $schedule_array["場所"];

            //場所があるかどうかで取ってくるものが変わる
            if( get_field('acf_spirit_palce_name' ,$palce_id ) == "")//情報がなくなっているなら、保存している情報で表示
            {
                $palce_id = $program_id;
            }

            $schedule_array["場所ステータス"]["名前"] = get_field('acf_spirit_palce_name' ,$palce_id);
            $schedule_array["場所ステータス"]["URL"] = get_field('acf_spirit_palce_url' ,$palce_id);
            $schedule_array["場所ステータス"]["アクセス"] = get_field('acf_spirit_palce_access' ,$palce_id);
            $schedule_array["場所ステータス"]["住所"] = get_field('acf_spirit_palce_address' ,$palce_id);
            $schedule_array["場所ステータス"]["MAP"] = get_field('acf_spirit_palce_googlemap' ,$palce_id);

        }


        //キャンセル可能時間
        $schedule_array["キャンセル可能時間"] = get_field('acf_spirit_schedule_cancel_time' ,$program_id);

        if($schedule_array["キャンセル可能時間"] == "")
        {
            $schedule_array["キャンセル可能時間"] = 24;
        }


        //キャンセル時間
        $cancel_time = $schedule_array["実行日"]." ".$schedule_array["実行時間表示"];

        if($schedule_array["実行時間表示"] == "")
        {
            $cancel_time = $schedule_array["実行日"]." 00:00";
        }

        $cancel_time = new DateTime($cancel_time);

        $cancel_time->modify('-'.$schedule_array["キャンセル可能時間"].' hour');

        $schedule_array["キャンセル時間"] = $cancel_time->format('Y-n-j H:i');      


        if( $schedule_array["キャンセル時間"] != "")
        {
            $schedule_array["キャンセル時間年月日"] = $cancel_time->format('Y年n月j日 H:i');
        }



       //締め切りかどうかを調べる
       $schedule_array["予約完了"] = false;
       
       if($schedule_array["人数"] > 0)
       {
            if($schedule_array["人数"] <= $schedule_array["予約人数"])
            {
                $schedule_array["予約完了"] = true;
            }
       }

       //支払い方法
       $schedule_array["支払い方法"] = get_field('acf_spirit_schedule_payment_type' ,$program_id);
       if($schedule_array["支払い方法"] == "")
       {
            $schedule_array["支払い方法"] = array();
       }




        return $schedule_array;

    }

    /****************************************************
	**  締め切り日を出す
	******************************************************/
	public function getSpritScheduleLimit( $program_id )
	{

        $limit_day = get_field('acf_spirit_schedule_end' ,$program_id);
       
        date_default_timezone_set('Asia/Tokyo'); 

        $today = new DateTime(); // 今日の日付
        $today->setTime(0, 0, 0);

        //指定がない場合は１日前
        if($limit_day == "")
        {
            $specifiedDate = new DateTime(get_field('acf_spirit_schedule_date' ,$program_id));
            $specifiedDate->modify('-1 day'); // 1日前に変更

             $specifiedDate->setTime(0, 0, 0);

             return $specifiedDate->format('Y-n-j');
        }
        else{
            $specifiedDate = new DateTime(get_field('acf_spirit_schedule_end' ,$program_id));
            $specifiedDate->setTime(0, 0, 0);

            //実行日と比べる
            $scheduledDate = new DateTime(get_field('acf_spirit_schedule_date' ,$program_id));
            $scheduledDate->modify('-1 day'); // 1日前に変更
            $scheduledDate->setTime(0, 0, 0);

            //実行日の１日前が締切日よりも先の場合
            if ($scheduledDate <= $specifiedDate) {
               return $scheduledDate->format('Y-n-j');
            }

        }
        

       return $specifiedDate->format('Y-n-j');
    }


    /****************************************************
	**  締め切りかどうか
	******************************************************/
	public function checkSpritScheduleLimit( $program_id )
	{

        $limit_day = get_field('acf_spirit_schedule_end' ,$program_id);
       
        date_default_timezone_set('Asia/Tokyo'); 

        $today = new DateTime(); // 今日の日付
        $today->setTime(0, 0, 0);

        //指定がない場合は１日前
        if($limit_day == "")
        {
            $specifiedDate = new DateTime(get_field('acf_spirit_schedule_date' ,$program_id));
            $specifiedDate->modify('-1 day'); // 1日前に変更

             $specifiedDate->setTime(0, 0, 0);
        }
        else{
            $specifiedDate = new DateTime(get_field('acf_spirit_schedule_end' ,$program_id));
            $specifiedDate->setTime(0, 0, 0);

            //実行日と比べる
            $scheduledDate = new DateTime(get_field('acf_spirit_schedule_date' ,$program_id));
            $scheduledDate->modify('-1 day'); // 1日前に変更
            $scheduledDate->setTime(0, 0, 0);

            if ($scheduledDate <= $today) {
               return false;//過去
            }

        }
        

       
        

        if ($specifiedDate > $today) {
           return true;//未来
        } elseif ($specifiedDate < $today) {
            return false;//過去
        } else {
           return false;//同日
        }
    }

    /****************************************************
	**  待機中かどうか
	******************************************************/
	public function checkSpritScheduleStart( $program_id )
	{

        $start_day = get_field('acf_spirit_schedule_start' ,$program_id);

        date_default_timezone_set('Asia/Tokyo'); 

        $today = new DateTime(); // 今日の日付


        //指定がないので表示に従う
        if($start_day == "")
        {
            return true;
        }
        else{
            $specifiedDate = new DateTime(get_field('acf_spirit_schedule_start' ,$program_id));
        }
        

        $specifiedDate->setTime(0, 0, 0);
        $today->setTime(0, 0, 0);

        if ($specifiedDate > $today) {
           return false;//未来
        } elseif ($specifiedDate < $today) {
            return true;//過去
        } else {
           return true;//同日
        }
    }


    /****************************************************
	**  カレンダーの作成
	******************************************************/
   function generateCalendar($year, $month) {
        // 指定された年月の最初の日
        $firstDayOfMonth = strtotime("$year-$month-01");
    
        // 指定された月の日数
        $daysInMonth = date('t', $firstDayOfMonth);

        // 1日の曜日（1:月, 2:火, ..., 7:日）
        $firstDayWeekday = date('N', $firstDayOfMonth);

        // 前月の年月を取得
        $prevMonth = date('n', strtotime("-1 month", $firstDayOfMonth));
        $prevYear = date('Y', strtotime("-1 month", $firstDayOfMonth));

        // 前月の日数
        $daysInPrevMonth = date('t', strtotime("$prevYear-$prevMonth-01"));

        // 次月の年月を取得
        $nextMonth = date('n', strtotime("+1 month", $firstDayOfMonth));
        $nextYear = date('Y', strtotime("+1 month", $firstDayOfMonth));

        // カレンダー配列
        $calendar = [];

        // 先頭に埋める前月の日付
        $startDay = $daysInPrevMonth - ($firstDayWeekday - 2);
        if ($startDay < 1) $startDay = 1;
    
        for ($i = $startDay; $i <= $daysInPrevMonth; $i++) {
            $calendar[] = ['day' => $i, 'month' => $prevMonth, 'year' => $prevYear, 'current' => false];
        }

        // 現在の月の日付を追加
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $calendar[] = ['day' => $i, 'month' => $month, 'year' => $year, 'current' => true];
        }

        // 次月の日付を埋める
        $remainingDays = 42 - count($calendar);
        for ($i = 1; $i <= $remainingDays; $i++) {
            $calendar[] = ['day' => $i, 'month' => $nextMonth, 'year' => $nextYear, 'current' => true];
        }

        return $calendar;
    }


    /****************************************************
	**  カレンダーの祝日
	******************************************************/

    function isJapaneseHoliday($date) {

        

        $year = $date->format('Y');
        $url = "https://holidays-jp.github.io/api/v1/$year/date.json";

        $json = file_get_contents($url);
        $holidays = json_decode($json, true);


        $check_date = $date->format('Y-m-d');
       
        return isset($holidays[$check_date]) ? $holidays[$check_date] : false;
    }



    /****************************************************
    **  指定のスケジュールが設定されているかどうかを調べる
	******************************************************/
    function getScheduleSetSpirit($schedule_num) {

       
        $wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', // 全件取得
            'post_type' => 'cpt_purification', // カスタム投稿タイプ
            'post_status' => 'publish', // 公開済みのみ取得
            'orderby' => 'ID', // ID順
            'order' => 'DESC',
           'meta_query' => array(
                array(
                    'key'   => 'acf_previous_schedule_number',
                    'value' => $schedule_num, // 取得したい値
                    'compare' => '='  // 一致するもののみ取得
                )
            )
        );

        $wp_query->query($param);


        $schedule_set_id = array();

        //データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                //キャンセルは含まれない
                $admin_status =  get_field('acf_purespirit_status',get_the_ID());//管理者ステータス

                if($admin_status == SpiritUserClass::ADMIN_STATUS_CANCEL)
                {
                    continue;
                }
                $member_status =  get_field('acf_purespirit_user_status',get_the_ID());//会員ステータス

                if($member_status == SpiritUserClass::MEMBER_STATUS_CANCEL)
                {
                    continue;
                }

                array_push($schedule_set_id,get_the_ID());


            endwhile;
        endif;

        return $schedule_set_id;

    }


    /****************************************************
    **  指定のスケジュールの人数を取得する
	******************************************************/
    function getScheduleReservationSlots($schedule_array) 
    {

        $slots = 0;


        foreach ($schedule_array as $key => $value) {

            $admin_status =  get_field('acf_purespirit_status',$value);//管理者ステータス
            $member_status =  get_field('acf_purespirit_user_status',$value);//管理者ステータス


            //キャンセルは含まれない
            if($admin_status == SpiritUserClass::ADMIN_STATUS_CANCEL || $member_status == SpiritUserClass::MEMBER_STATUS_CANCEL)
            {
                continue;
            }

            $slots++;

        }

        return $slots;

    }


    /****************************************************
    **  領収書を検索
	******************************************************/
    function getScheduleReceipt($check_year,$check_month,$check_user_id ) 
    {


        //発行年月を作成
        $invoice_issue_year = $check_year . "-" .$check_month;

        $wp_query = new WP_Query();

        //acf_invoice_issue_yearとacf_invoice_userが一致するものを取得
        $param = array(
            'posts_per_page' => '-1', // 全件取得
            'post_type' => 'cpt_receipt', // カスタム投稿タイプ
            'post_status' => 'publish', // 公開済みのみ取得
            'orderby' => 'ID', // ID順
            'order' => 'DESC',
            'meta_query' => array(
                array(
                    'key'   => 'acf_invoice_issue_year',
                    'value' => $invoice_issue_year, // 取得したい値
                    'compare' => '='  // 一致するもののみ取得
                ),
                array(
                    'key'   => 'acf_invoice_user',
                    'value' => $check_user_id, // 取得したい値
                    'compare' => '='  // 一致するもののみ取得
                )
            )
        );


        $wp_query->query($param); 

        //データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                return get_the_ID();

            endwhile;
        endif;

        return "";
    }


    /****************************************************
    **  領収書を作成
	******************************************************/
    function createScheduleReceipt($check_year,$check_month , $post_data , $display_month) 
    {
        $receipt_id = $this->getScheduleReceipt($check_year,$check_month,$post_data["receipt_setting_save"] );


        //編集
        if($receipt_id != "")
        {

            update_field("acf_invoice_day",$post_data["acf_invoice_day"],$receipt_id);
            update_field("acf_invoice_pay_day",$post_data["acf_invoice_pay_day"],$receipt_id);
            update_field("acf_invoice_post_name",$post_data["acf_invoice_post_name"],$receipt_id);
            update_field("acf_invoice_adjustment_costs",$post_data["acf_invoice_adjustment_costs"],$receipt_id);
            update_field("acf_invoice_payment_check",$post_data["acf_invoice_payment_check"],$receipt_id);
            update_field("acf_invoice_check",$post_data["acf_invoice_check"],$receipt_id);
            update_field("acf_invoice_communication_expenses",$post_data["acf_invoice_communication_expenses"],$receipt_id);
            update_field("acf_invoice_transportation_expenses",$post_data["acf_invoice_transportation_expenses"],$receipt_id);
            update_field("acf_invoice_else_expenses",$post_data["acf_invoice_else_expenses"],$receipt_id);

            return $receipt_id;
        }

        //新規
        $title =  $post_data["receipt_setting_save"] . " " . $display_month . "分";


        $my_post = array(
            'post_title' => $title,
            'post_type' => 'cpt_receipt', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => 1,
        );

        $program_id = wp_insert_post($my_post);

        if ($program_id) {

            $schedule_array = $this->getSpritScheduleReceiptList( $check_year , $check_month );

            update_field("acf_invoice_day",$post_data["acf_invoice_day"],$program_id);
            update_field("acf_invoice_pay_day",$post_data["acf_invoice_pay_day"],$program_id);
            update_field("acf_invoice_post_name",$post_data["acf_invoice_post_name"],$program_id);
            update_field("acf_invoice_user",$post_data["receipt_setting_save"],$program_id);
            update_field("acf_invoice_issue_year",$check_year . "-" . $check_month,$program_id);
            update_field("acf_invoice_total_pay",$schedule_array[$post_data["receipt_setting_save"]]["合計支払い金額"],$program_id);
            update_field("acf_invoice_save_id",$schedule_array[$post_data["receipt_setting_save"]]["施術データ"],$program_id);
            update_field("acf_invoice_sprit_count",$schedule_array[$post_data["receipt_setting_save"]]["施術数"],$program_id);
            update_field("acf_invoice_payment_address",get_field('acf_teacher_profile_transfer_destination', 'user_' . $post_data["receipt_setting_save"]),$program_id);
            update_field("acf_invoice_adjustment_costs",$post_data["acf_invoice_adjustment_costs"],$program_id);
            update_field("acf_invoice_payment_check",$post_data["acf_invoice_payment_check"],$program_id);
            update_field("acf_invoice_check",$post_data["acf_invoice_check"],$program_id);
            update_field("acf_invoice_communication_expenses",$post_data["acf_invoice_communication_expenses"],$program_id);
            update_field("acf_invoice_transportation_expenses",$post_data["acf_invoice_transportation_expenses"],$program_id);
            update_field("acf_invoice_else_expenses",$post_data["acf_invoice_else_expenses"],$program_id);
        }

        
        return $program_id;

    }

    /****************************************************
    **  ユーザーと年度を指定して、保存されている領収書を取得
	******************************************************/
    function getScheduleUserUserReceiptList($check_year , $invoice_user) 
    {
        $wp_query = new WP_Query();
        //acf_invoice_issue_yearとacf_invoice_userが一致するものを取得
        $param = array(
            'posts_per_page' => '-1', // 全件取得
            'post_type' => 'cpt_receipt', // カスタム投稿タイプ
            'post_status' => 'publish', // 公開済みのみ取得
            'orderby' => 'ID', // ID順
            'order' => 'DESC',
            'meta_query' => array(
                array(
                    'key'   => 'acf_invoice_issue_year',
                    'value' => $check_year, // 取得したい値
                    'compare' => 'LIKE'  // 含むものを取得
                ),
                array(
                    'key'   => 'acf_invoice_user',
                    'value' => $invoice_user, // 取得したい値
                    'compare' => '='  // 一致するもののみ取得
                )
            )
        );


        //echo $check_year;

        $wp_query->query($param);


        $schedule_set_id = array();

        //データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                $schedule_set_id[ get_the_ID() ] = get_the_ID();

               
            endwhile;
        endif;

        return $schedule_set_id;
    }


    /****************************************************
    **  そのスケジュールの担当者できる人を取得
	******************************************************/
    function getScheduleManager($schedule_id , $spiritType) 
    {

         //var_dump($spiritType);
        // 全ユーザーの取得（管理者、編集者、投稿者）
        $users = get_users(array(
            'role__in' => array('administrator', 'editor', 'author'),
            'exclude' => array(1), // ID:1のユーザーを除外
            'orderby' => 'ID',
            'order' => 'ASC'
        ));

        //ユーザーメタから
        $type_user = array();
        $users_data = array(); // ユーザー情報を保持する配列を追加

        foreach($users as $user){


            //管理者以外は自分のみ
            if(!current_user_can('administrator')){
                if($user->ID != get_current_user_id()){
                    continue;
                }
            }
    
    
            // ユーザー情報を保存
            $users_data[$user->ID] = array(
                'name' => $user->display_name
            );
    
            //可能なデータを取得
            $type = get_user_meta($user->ID, 'explanation_types', true);
    
            if(is_array($type)) {
                foreach($type as $key => $value){
    
                    if(isset($spiritType[$value]))
                    {
                        if(!isset($type_user[$value]))
                        {
                            $type_user[$value] = array();
                        }
                        array_push($type_user[$value],$user->ID);
                    }
                }
            }
        }
        
      
        if(isset($type_user[$schedule_id]))
        {
            return $type_user[$schedule_id];
        }
        else
        {
            return array();
        }

    }



    
   
}









?>