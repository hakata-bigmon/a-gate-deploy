<?php 

require_once (dirname(__FILE__)."/spiritUserClass.php");

class SpiritSheetClass
{

    //浄霊シートフィールド番号
    public const SPIRITSHEET_FIELD_NUM = 33;

    //粗見シートの最大値
    public const MAX_ARAMI_NUMBER = 20;


    //質問のタイプ
    public const QUESTION_TYPE_TEXT = 0;//テキスト
    public const QUESTION_TYPE_TEXT_AREA = 1;//テキストエリア
    public const QUESTION_TYPE_CALENDAR = 2;//カレンダー
    public const QUESTION_TYPE_NAME = 3;//名前
    public const QUESTION_TYPE_NUMBER = 4;//数字
    public const QUESTION_TYPE_MAIL = 5;//メール
    public const QUESTION_TYPE_TEL = 6;//電話番号
    public const QUESTION_TYPE_SELECT = 7;//セレクトボックス
    public const QUESTION_TYPE_RADIO = 9;//ラジオボタン
    public const QUESTION_TYPE_ADD_MEMBER = 10;//対象者を追加(リモート浄霊)
    public const QUESTION_TYPE_IMG_DATA = 11;//画像
    public const QUESTION_TYPE_POST_ADDRESS = 12;//送付先
    public const QUESTION_TYPE_IMG_CHOICE = 13;//画像選択


    //リモート浄霊一覧シートのテーブル
    public function getRemoteListDispTable(  )
	{
        return  array("粗見シート","依頼日", '入力完了日', '実行予定日', '実行日', '入力', 'ステータス', '会員ステータス', '生年月日', '関係');

    }

    public function getRemoteListSearchTable(  )
	{
        return  array("粗見シート","依頼日", '入力完了日', '実行予定日', '実行日', 'ステータス', '会員ステータス', '生年月日', '関係');

    }

    //粗見シートのテーブル
    public function getAramiDispTable(  )
	{
        return  array("依頼日", '入力完了日', '実行予定日', '実行日', 'ステータス', '生年月日', '関係');

    }
   
    
     /****************************************************
	**  管理シートのユニックスタイム検索
	******************************************************/
	public function checkSpiritSheetUnixtime( $unixtime )
	{
        $wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_purification', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        $count = 0;

        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                $num = get_field('acf_purespirit_unixtime');

                if( $unixtime == $num)
                {
                    return true;
                }

            endwhile;
        endif;

        return false;
    }

    /****************************************************
	**  管理シートの新規登録
	******************************************************/
	public function newSpiritSheetUnixtime( $user_id, $type_id,$unixtime )
	{
       
        //同じUNIXTIMEがあるなら作成しない
        if($this->checkSpiritSheetUnixtime( $unixtime ))
        {
            return "";
        }

        $user = wp_get_current_user();

        date_default_timezone_set('Asia/Tokyo'); 

        //今日の日付
        $today = date("Y-m-d H:i:s");

        $title =  get_user_meta( $user_id,'last_name',true) . " " . get_user_meta( $user_id,'first_name',true) . " " . get_field('acf_pure_spirit_title',$type_id) . " " . $today;


        $my_post = array(
            'post_title' => $title,
            'post_type' => 'cpt_purification', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );

        $program_id = wp_insert_post($my_post);

        if ($program_id) {
            
            update_field("acf_purespirit_id", $user_id, $program_id);//ユーザー番号
            update_field("acf_acf_purespirit_type", $type_id, $program_id);//タイプ
            update_field("acf_purespirit_unixtime", $unixtime, $program_id);//ユニックスタイム


            //現在の依頼日を入れる
            date_default_timezone_set('Asia/Tokyo');
            update_field("acf_purespirit_requested_date", date("Y-m-d"), $program_id);//ユニックスタイム
            




        }

        
        return $program_id;
        // return false;

	}

    /****************************************************
	**  管理シートの新規登録(最新版)
	******************************************************/
	public function newSpiritSheet( $post_array )
	{
       
        require_once ("spiritTypeClass.php");

        $spiritType = new SpiritTypeClass();


        //物販の場合は在庫チェック
        if(!$spiritType->checkSpiritSalesStock( $post_array["category_type"] , $post_array["target_slots"]))
        {
            return "";//在庫がない
        }

        //同じUNIXTIMEがあるなら作成しない
        if($this->checkSpiritSheetUnixtime( $post_array["add_sheet_unix"] ))
        {
            return "";
        }

        $user = wp_get_current_user();

        $user_id = $post_array["user_id"];

        date_default_timezone_set('Asia/Tokyo'); 

        //今日の日付
        $today = date("Y-m-d H:i:s");

        $title =  get_user_meta( $user_id,'last_name',true) . " " . get_user_meta( $user_id,'first_name',true) . " " . get_field('acf_pure_spirit_title',$post_array["category_type"]) . " " . $today;


        $my_post = array(
            'post_title' => $title,
            'post_type' => 'cpt_purification', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );

        $program_id = wp_insert_post($my_post);

        if ($program_id) {
            
            update_field("acf_purespirit_id", $user_id, $program_id);//ユーザー番号
            update_field("acf_acf_purespirit_type", $post_array["category_type"], $program_id);//タイプ
            update_field("acf_purespirit_unixtime", $post_array["add_sheet_unix"], $program_id);//ユニックスタイム
            update_field("acf_previous_payment_type", $post_array["payment_type"], $program_id);//支払いタイプ

            // スケジュール番号を入れる
            update_field("acf_previous_schedule_number", $post_array["schedule_id"], $program_id);

            
            $spiritTypeNum = $spiritType->getSpiritTypeKeyTypeNum();

            if($spiritTypeNum[$post_array["category_type"]]["group"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES) //物販の場合は在庫を減らす
            {
                $spiritType->reduceSpiritSalesStock( $post_array["category_type"] , $post_array["target_slots"]);

                //値段を入れる(数分)
                update_field("acf_purespirit_price", $spiritTypeNum[$post_array["category_type"]]["price"] * $post_array["target_slots"] , $program_id);


                //販売個数を入れる
                update_field("acf_previous_quantity", $post_array["target_slots"] , $program_id);
            }
            else if($spiritTypeNum[$post_array["category_type"]]["group"] == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY || $spiritTypeNum[$post_array["category_type"]] == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN) //日程確定の場合はスケジュール番号を入れる
            {
                update_field("acf_previous_schedule_number", $post_array["schedule_id"], $program_id);

                //値段を入れる
                update_field("acf_purespirit_price", $spiritTypeNum[$post_array["category_type"]]["price"], $program_id);

                //販売個数を入れる
                update_field("acf_previous_quantity", 1 , $program_id);
            }
            else{
                //値段を入れる
                update_field("acf_purespirit_price", $spiritTypeNum[$post_array["category_type"]]["price"], $program_id);

                //販売個数を入れる
                update_field("acf_previous_quantity", 1 , $program_id);
            }
            

            //入金日
            if(isset($post_array["acf_purespirit_payment_date"]))
            {
                update_field("acf_purespirit_payment_date", $post_array["acf_purespirit_payment_date"], $program_id);
            }

            //状況ステータス
            if(isset($post_array["acf_purespirit_user_status"]))
            {
                update_field("acf_purespirit_user_status", $post_array["acf_purespirit_user_status"], $program_id);
            }

            //郵送先
            if(isset($post_array["acf_previous_post_billing_postcode"]))
            {
                update_field("acf_previous_post_billing_postcode", $post_array["acf_previous_post_billing_postcode"], $program_id);
            }

            if(isset($post_array["acf_previous_billing_city"]))
            {
                update_field("acf_previous_billing_city", $post_array["acf_previous_billing_city"], $program_id);
            }

            if(isset($post_array["acf_previous_billing_address_1"]))
            {
                update_field("acf_previous_billing_address_1", $post_array["acf_previous_billing_address_1"], $program_id);
            }

            if(isset($post_array["acf_previous_billing_first_name"]))
            {
                update_field("acf_previous_billing_first_name", $post_array["acf_previous_billing_first_name"], $program_id);
            }

            //依頼確定日
            if(isset($post_array["acf_purespirit_request_confirmation_date"]))
            {
                update_field("acf_purespirit_request_confirmation_date", $post_array["acf_purespirit_request_confirmation_date"], $program_id);
            }

            //実行日
            if(isset($post_array["acf_purespirit_execution_date"]))
            {
                update_field("acf_purespirit_execution_date", $post_array["acf_purespirit_execution_date"], $program_id);
            }
           
            //現在の依頼日を入れる
            date_default_timezone_set('Asia/Tokyo');
            update_field("acf_purespirit_requested_date", date("Y-m-d"), $program_id);//ユニックスタイム
            




        }

        
        return $program_id;
        // return false;

	}
    /****************************************************
	**  ユーザーのsheet配列を追加する
	******************************************************/
	public function newSpiritSheetUserAdd( $user_id ,$add_id )
	{
        
        //JSON番号を保存
        $user_json_data = get_user_meta($user_id,'spirit_data',true);    //jsonデータ取得
        
        $decoded_data = "";
        
        if($user_json_data == "")
        {
            $decoded_data = array();
        }
        else{
            $decoded_data = json_decode($user_json_data, true);  //jsonデータ戻し
        }

        array_push($decoded_data,$add_id);

        $json_data = json_encode($decoded_data, JSON_UNESCAPED_UNICODE);

        update_user_meta($user_id,"spirit_data",$json_data);

    }


    /****************************************************
	**  管理シートの削除
	******************************************************/
	public function deleteSpiritSheet( $id , $user_id )
	{

        $post = get_post($id);

        if (!$post) {
            return; // 存在しないので処理しない
        }

        //物販の数を戻す
        $this->deleteSpiritSheetSalesStock($id);
       
        //管理シートの質問を全て削除
        $json_data = get_field('acf_purespirit_array',$id);    //jsonデータ取得

        if($json_data != "")
         {
             //echo $json_data;

             foreach ($json_data as $key => $value) {
                 wp_delete_post($value, true);
             }
         }


         //一斉浄霊の場合一斉浄霊シートも削除
         $remote_data = get_field('acf_purespirit_remote_sprit_num',$id); 

         if($remote_data != "")
         {
             //一緒に対象者シートも削除する
             for($i=1;$i<=10;$i++)
             {
                 $target_sheet_id = get_field('acf_remote_sprit_sheet_id_' . $i ,$remote_data);

                 if($target_sheet_id != "")
                 {
                     wp_delete_post($target_sheet_id, true);
                 }

             }


              wp_delete_post($remote_data, true);
         }


         //IDを削除
          wp_delete_post($id, true);

         //ユーザーの浄霊シートの配列を削除
         $user_json_data = get_user_meta($user_id,'spirit_data',true);    //jsonデータ取得

         $decoded_data = "";
        
        if($user_json_data == "")
        {
            $decoded_data = array();
        }
        else{
            $decoded_data = json_decode($user_json_data, true);  //jsonデータ戻し

            $result = array_diff($decoded_data, array($id));
            //indexを詰める
            $decoded_data = array_values($result);
        }

        $json_data = json_encode($decoded_data, JSON_UNESCAPED_UNICODE);

        update_user_meta($user_id,"spirit_data",$json_data);
    }

    /****************************************************
	**  管理シートの削除時に物販の数を戻す
	******************************************************/
	public function deleteSpiritSheetSalesStock( $id )
	{

        //すでに非表示の場合は処理しない
        if(get_field('is_delete',$id))
        {
            return;
        }

        
        //浄霊タイプ
        $acf_pure_spirit_type = get_field('acf_acf_purespirit_type',$id);
        //物販のみ数を戻す
        if(get_field('acf_pure_spirit_type_min',$acf_pure_spirit_type) == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES)
        {

            require_once ("spiritUserClass.php");

            $admin_status =  get_field('acf_purespirit_status',$id);//管理者ステータス
            $member_status =  get_field('acf_purespirit_user_status',$id);//会員ステータス

            //キャンセルじゃない時のみ
            if($admin_status != SpiritUserClass::ADMIN_STATUS_CANCEL && $member_status != SpiritUserClass::MEMBER_STATUS_CANCEL)
            {
                //数を戻す
                $acf_previous_quantity = get_field('acf_previous_quantity',$id);
                //現在の在庫数
                 $acf_pure_spirit_stock = get_field('acf_pure_spirit_stock',$acf_pure_spirit_type);
               
                update_field("acf_pure_spirit_stock",$acf_previous_quantity + $acf_pure_spirit_stock, $acf_pure_spirit_type);
            }
        }
        
    }


    /****************************************************
	**  管理シートを入金に変更
	******************************************************/
	public function changeSpiritSheetPayment( $id )
	{
        update_field("acf_purespirit_user_status", SpiritUserClass::MEMBER_STATUS_NOT_INFOMATION, $id); //情報未入力に変王

        //依頼確定日を確定にする
        date_default_timezone_set('Asia/Tokyo');
        update_field("acf_purespirit_request_confirmation_date", date("Y-m-d"), $id);

        //決済日を入れる
        update_field("acf_purespirit_payment_date", date("Y-m-d"), $id);
    }


    /****************************************************
	**  管理シートの質問配列保存
	******************************************************/
	public function setSpiritSheetAnswer( $id , $save_data)
	{
         //JSON番号を保存
         $json_data = json_encode($save_data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);


         update_field("acf_purespirit_array", $save_data, $id);//配列

    }


    /****************************************************
	**  管理シートの質問配列取得
	******************************************************/
	public function getSpiritSheetAnswer( $id )
	{
         //JSON番号を保存
        $json_data = get_field('acf_purespirit_array',$id);    //jsonデータ取得

        //var_dump($json_data);
        
        $decoded_data = "";
        
        if($json_data == "")
        {
            $decoded_data = array();
        }
        else{
            $decoded_data = $json_data;//json_decode($json_data, true);  //jsonデータ戻し
        }

        return $decoded_data;

    }
	
    
    /****************************************************
	**  管理シートのキャンセル
	******************************************************/
	public function cancelSpiritSheet( $id , $user_id )
	{


        //すでにキャンセルの場合は何もしない
        if(get_field('acf_purespirit_user_status',$id) == SpiritUserClass::MEMBER_STATUS_CANCEL)
        {
            return;
        }

        //キャンセルに変更
        update_field('acf_purespirit_user_status', SpiritUserClass::MEMBER_STATUS_CANCEL, $id);

        //キャンセル日を入れる
        //東京タイム
        date_default_timezone_set('Asia/Tokyo');
        update_field('acf_previous_cancel_day', date("Y-m-d"), $id);

    }



	/****************************************************
	 **  管理ステータス取得
	 ******************************************************/
	public function getSpiritAdminStatus($status_cpt)
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => $status_cpt,//'cpt_spirit_status', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


		//ソート用
        $sort_array = "";

		//データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                if ($sort_array == "") {
                    $sort_array = array();
                }

                $num = get_field('acf_pure_spirit_status_sort');

                $sort_array[$num]["ID"] = get_the_ID();
                $sort_array[$num]["title"] = get_field('acf_pure_spirit_status_name');
                $sort_array[$num]["disp_title"] = get_field('acf_pure_spirit_status_disp_name');

                if($sort_array[$num]["disp_title"] == "")
                {
                    $sort_array[$num]["disp_title"] = $sort_array[$num]["title"];
                }

            endwhile;
        endif;


        if($sort_array != "")
        {
            //ソート
            ksort($sort_array);
        }
        //var_dump($sort_array);

        return $sort_array;

	}

    /****************************************************
	 **  管理ステータス取得 ID順
	 ******************************************************/
	public function getSpiritAdminStatusID($status_cpt)
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => $status_cpt,//'cpt_spirit_status', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


		//ソート用
        $sort_array = "";

		//データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                if ($sort_array == "") {
                    $sort_array = array();
                }

                $num = get_the_ID();

                $sort_array[$num]["ID"] = get_the_ID();
                $sort_array[$num]["title"] = get_field('acf_pure_spirit_status_name');
                $sort_array[$num]["disp_title"] = get_field('acf_pure_spirit_status_disp_name');

                if($sort_array[$num]["disp_title"] == "")
                {
                    $sort_array[$num]["disp_title"] = $sort_array[$num]["title"];
                }

            endwhile;
        endif;

        //var_dump($sort_array);

        return $sort_array;

	}
   

    /****************************************************
	 **  管理ステータス並び順を更新
	 ******************************************************/
	public function saveSpiritAdminStatusSort( $sortArray )
	{
         foreach ($sortArray as $key => $value) {
               update_field("acf_pure_spirit_status_sort", $key + 1, $value);
         }

    }

    /****************************************************
	 **  管理ステータスを編集
	 ******************************************************/
	public function saveSpiritAdminStatusTitle( $id , $inputData ,$inputtextColor,$inputBackColor,$disp_title = "" )
	{
        update_field("acf_pure_spirit_status_name", $inputData, $id);
        update_field("acf_pure_spirit_status_text_color", $inputtextColor, $id);
        update_field("acf_pure_spirit_status_back_color", $inputBackColor, $id);
        update_field("acf_pure_spirit_status_disp_name", $disp_title, $id);
    }

    /****************************************************
	 **  管理ステータスDで同じものがあるかどうかを調べる
	 ******************************************************/
	public function checkSpiritAdminStatus( $id , $title ,$status_cpt )
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => $status_cpt,//'cpt_spirit_status', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        $check_title =  str_replace(' ','', $title);
        $check_title =  str_replace('　','', $check_title);

		//データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                if ($id != get_the_ID()) 
                {

                    $base_title =  str_replace(' ','', get_field('acf_pure_spirit_status_name'));
                    $base_title =  str_replace('　','', $base_title);

                    if($base_title == $check_title)
                    {
                        return true;
                    }
                }

            endwhile;
        endif;

        return false;

	}


    /****************************************************
	**  管理ステータスの新規登録
	******************************************************/
	public function newSpiritAdminStatus( $title , $inputtextColor , $inputBackColor ,$status_cpt,$disp_title = "" )
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => $status_cpt,//'cpt_spirit_status', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        $count = 0;

		//一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                $num = get_field('acf_pure_spirit_status_sort');

                if( $count < $num)
                {
                    $count = $num;
                }

            endwhile;
        endif;


        $user = wp_get_current_user();

        $my_post = array(
            'post_title' => $title,
            'post_type' => $status_cpt,//'cpt_spirit_status', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );

        $program_id = wp_insert_post($my_post);

        if ($program_id) {
            
            update_field("acf_pure_spirit_status_sort", $count + 1, $program_id);//内容
            update_field("acf_pure_spirit_status_name", $title, $program_id);//内容
            update_field("acf_pure_spirit_status_text_color", $inputtextColor, $program_id);
            update_field("acf_pure_spirit_status_back_color", $inputBackColor, $program_id);
            update_field("acf_pure_spirit_status_disp_name", $disp_title, $program_id);
        }

        
        return $program_id;
        // return false;

	}

    /****************************************************
	 **  管理ステータスを削除
	 ******************************************************/
	public function deleteSpiritAdminStatus( $id )
	{
        wp_delete_post($id, true);

    }



    
	/****************************************************
	 **  質問取得
	 ******************************************************/
	public function getSpiritQuestion( $id = 0 )
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_question', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


		//ソート用
        $sort_array = "";

		//データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                if ($sort_array == "") {
                    $sort_array = array();
                }

                $type = get_field('acf_question_number');
                
                //全部
                if($id == 0)
                {
                    if( !isset($sort_array[$type]))
                    {
                        $sort_array[$type] = array();
                    }
                }
                else{
                    //１つだけ
                    if($type != $id)
                    {
                        continue;
                    }

                    if( !isset($sort_array[$type]))
                    {
                        $sort_array[$type] = array();
                    }
                }



                $num = get_field('acf_question_sort');

               
               
                $sort_array[$type][$num]["ID"] = get_the_ID();
                $sort_array[$type][$num]["form_disp"] = get_field('acf_question_form_disp');
                $sort_array[$type][$num]["admin_disp"] = get_field('acf_question_admin_disp');
                $sort_array[$type][$num]["text"] = get_field('acf_question_text');
                $sort_array[$type][$num]["add"] = get_field('acf_question_alert');
                $sort_array[$type][$num]["type"] = get_field('acf_question_type');
                $sort_array[$type][$num]["required"] = get_field('acf_question_required');
                $sort_array[$type][$num]["img_choice"] = get_field('acf_question_img_choice');
                $sort_array[$type][$num]["counselor"] = get_field('acf_question_counselor_disp');

            endwhile;
        endif;

         
        if(!empty($sort_array))
        {

            foreach ($sort_array as $key => $value) {
                ksort($sort_array[$key]);
            }

        }
        //ソート
       

      // var_dump($sort_array);

        return $sort_array;

	}


     /****************************************************
	** 質問の新規登録
	******************************************************/
	public function newSpiritQuestion( $title ,  $inptut_type , $required_input , $form_input,$admin_input ,$question_number ,$add_text ,$wp_title ,$img_choice_array ,$counselor_disp )
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_question', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        $count = 0;

		//一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                $num = get_field('acf_question_sort');

                if( $count < $num)
                {
                    $count = $num;
                }

            endwhile;
        endif;


        $user = wp_get_current_user();

        $my_post = array(
            'post_title' => $title ."(" . $wp_title . ")",
            'post_type' => 'cpt_question', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );

        $program_id = wp_insert_post($my_post);

        if ($program_id) {
            
            $required = "";
            $form_on = "";
            $admin_on = "";
            
            if($form_input)
            {
                $form_on = 1;
            }

            if($admin_input)
            {
                $admin_on = 1;
            }


            if($required_input)
            {
                $required = 1;
            }

            update_field("acf_question_sort", $count + 1, $program_id);
            update_field("acf_question_form_disp", $form_on, $program_id);
            update_field("acf_question_admin_disp", $admin_on, $program_id);
            update_field("acf_question_text",$title, $program_id);
            update_field("acf_question_number",$question_number, $program_id);
            update_field("acf_question_required",$required, $program_id);
            update_field("acf_question_alert",$add_text, $program_id);
            update_field("acf_question_type",$inptut_type, $program_id);
            update_field("acf_question_img_choice",$img_choice_array, $program_id);
            update_field("acf_question_counselor_disp",$counselor_disp, $program_id);
        }

        
     //   return false;

	}


    /****************************************************
	 **  質問の編集
	 ******************************************************/
	public function saveSpiritQuestion( $id ,$inptut_type, $inputText, $add_text, $form_input,$admin_input, $required_input,$wp_title,$img_choice_array,$counselor_disp)
	{
        update_field("acf_question_text", $inputText, $id);

        $required = "";
        $form_on = "";
        $admin_on = "";
            
        if($form_input)
        {
            $form_on = 1;
        }

        if($admin_input)
        {
            $admin_on = 1;
        }

        if($required_input)
        {
            $required = 1;
        }

        update_field("acf_question_form_disp", $form_input, $id);
        update_field("acf_question_admin_disp", $admin_input, $id);
        update_field("acf_question_required",$required, $id);
        update_field("acf_question_alert", $add_text, $id);
        update_field("acf_question_type",$inptut_type, $id);
        update_field("acf_question_img_choice",$img_choice_array, $id);
        update_field("acf_question_counselor_disp",$counselor_disp, $id);

        //タイトル更新
        $post = array();
        $post["ID"] = $id;
        $post["post_title"] = $wp_title;
        wp_update_post( $post );
    }


    /****************************************************
	 **  質問を削除
	 ******************************************************/
	public function deleteSpiritQuestion( $id )
	{
        wp_delete_post($id, true);

    }

     /****************************************************
	 **  管理ステータス並び順を更新
	 ******************************************************/
	public function saveSpiritQuestionSort( $sortArray )
	{
         foreach ($sortArray as $key => $value) {
               update_field("acf_question_sort", $key + 1, $value);
         }

    }


    /****************************************************
	 **  汎用的なプルダウン項目の取得
	 ******************************************************/
	public function getGeneralPurposeData( $cpt_str )
	{
		$wp_query = new WP_Query();
        
        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => $cpt_str, //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


		//ソート用
        $sort_array = "";

		//データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                if ($sort_array == "") {
                    $sort_array = array();
                }

                $num = get_field('acf_generalpurpose_sort');

                

                $sort_array[$num]["ID"] = get_the_ID();
                $sort_array[$num]["title"] = get_field('acf_generalpurpose_name');

            endwhile;
        endif;


        if($sort_array != "")
        {
            //ソート
            ksort($sort_array);
        }
        //var_dump($sort_array);

        return $sort_array;

	}


     /****************************************************
	 **  汎用的なプルダウン項目の取得
	 ******************************************************/
	public function getGeneralPurposeDataKeyID( $cpt_str )
	{
		$wp_query = new WP_Query();
        
        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => $cpt_str, //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


		//ソート用
        $sort_array = "";

		//データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                if ($sort_array == "") {
                    $sort_array = array();
                }


                $sort_array[ get_the_ID()] = get_field('acf_generalpurpose_name');

            endwhile;
        endif;

        //ソート
        ksort($sort_array);

        //var_dump($sort_array);

        return $sort_array;

	}


    /****************************************************
	 **   汎用的なプルダウン項目を削除
	 ******************************************************/
	public function deleteGeneralPurposeData( $id )
	{
        wp_delete_post($id, true);

    }

     /****************************************************
	 **   汎用的なプルダウン項目の順番を更新
	 ******************************************************/
	public function saveGeneralPurposeDataSort( $sortArray  )
	{
         foreach ($sortArray as $key => $value) {
               update_field("acf_generalpurpose_sort", $key + 1, $value);
         }

    }


    /****************************************************
	**  汎用的なプルダウン項の新規登録
	******************************************************/
	public function newGeneralPurposeData( $title , $cpt_str )
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => $cpt_str, //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        $count = 0;

		//一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                $num = get_field('acf_generalpurpose_sort');

                if( $count < $num)
                {
                    $count = $num;
                }

            endwhile;
        endif;


        $user = wp_get_current_user();

        $my_post = array(
            'post_title' => $title,
            'post_type' => $cpt_str, //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );

        $program_id = wp_insert_post($my_post);

        if ($program_id) {
            
            update_field("acf_generalpurpose_sort", $count + 1, $program_id);//内容
            update_field("acf_generalpurpose_name", $title, $program_id);//内容
        }

        
     //   return false;

	}


    /****************************************************
	 **   汎用的なプルダウン項目の編集
	 ******************************************************/
	public function saveGeneralPurposeData( $id , $inputData )
	{
        update_field("acf_generalpurpose_name", $inputData, $id);


        //タイトル更新
        $post = array();
        $post["ID"] = $id;
        $post["post_title"] = $inputData;
        wp_update_post( $post );

    }


    /****************************************************
	**  汎用的なプルダウン項目で同じものがあるかどうかを調べる
	******************************************************/
	public function checkGeneralPurposeData( $id , $title ,$cpt_str)
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => $cpt_str, //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        $check_title =  str_replace(' ','', $title);
        $check_title =  str_replace('　','', $check_title);

		//データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                if ($id != get_the_ID()) 
                {

                    $base_title =  str_replace(' ','', get_field('acf_generalpurpose_name'));
                    $base_title =  str_replace('　','', $base_title);

                    if($base_title == $check_title)
                    {
                        return true;
                    }
                }

            endwhile;
        endif;

        return false;

	}


    /****************************************************
	**  質問の答えを入れていく
	******************************************************/
	public function saveQuestionAnser( $user_id , $sheet_id , $question_id , $question_type , $seve_data )
	{
        $question_data = $seve_data;

        //タイプによっては質問をまとめる
        if($question_type == SpiritSheetClass::QUESTION_TYPE_NAME  && !isset($_POST['teacher_comment'])) //名前
        {

            $question_data = "";

            foreach ($seve_data as $index => $value) {
                $question_data.= htmlspecialchars($value) . ",";
            }
        }
        else  if($question_type == SpiritSheetClass::QUESTION_TYPE_TEL && !isset($_POST['teacher_comment']))
        {

            $question_data = "";

            foreach ($seve_data as $index => $value) {
                $question_data.= htmlspecialchars($value) . ",";
            }

            //echo $question_data;
        }



        $wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_questionanser', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        $count = 0;

		//一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                if( $user_id ==  get_field('acf_questionqnser_user_id') &&  $sheet_id ==  get_field('acf_questionqnser_sheet_id') &&  $question_id ==  get_field('acf_questionqnser_number'))
                {

                    if($question_type == SpiritSheetClass::QUESTION_TYPE_IMG_CHOICE){
                        update_field("acf_questionqnser_img_choice_url", $question_data, get_the_ID()); //画像選択
                    }
                    else if(isset($_POST['teacher_comment'])){
                        // 先生のコメント設置
                        update_field("acf_questionqnser_teacher_comment", $question_data,  get_the_ID());
                    }else{
                        update_field("acf_questionqnser_text", $question_data, get_the_ID());
                    }
                    return get_the_ID();
                }

            endwhile;
        endif;

        //echo $question_data . "<br>";


        //セーブデータがなかったので新規作成
        
        $user = wp_get_current_user();

        $title = get_field('acf_pure_spirit_title',get_field('acf_acf_purespirit_type',$sheet_id)) ." " .$question_id ;

        $my_post = array(
            'post_title' => $title,
            'post_type' => 'cpt_questionanser', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );

        $program_id = wp_insert_post($my_post);


        if ($program_id) {

            update_field("acf_questionqnser_user_id", $user_id, $program_id);
            update_field("acf_questionqnser_sheet_id", $sheet_id, $program_id);
            update_field("acf_questionqnser_number", $question_id, $program_id);

           if($question_type == SpiritSheetClass::QUESTION_TYPE_IMG_DATA){
                //画像は別で保存
                 update_field("acf_questionqnser_img_url", $seve_data, $program_id);
            }
            else  if($question_type == SpiritSheetClass::QUESTION_TYPE_IMG_CHOICE){
                //画像は別で保存
                 update_field("acf_questionqnser_img_choice_url", $seve_data, $program_id);
            }
            else //画像以外
            {
                update_field("acf_questionqnser_text", $question_data, $program_id);
            }
             
            
        }

        //echo $program_id;

        return $program_id;
    }


    /****************************************************
	**  施術シートを取得する
	******************************************************/
	public function getSpiritSheet( $category_type = "" , $delete_user_check = false )
	{
        $wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_purification', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        

        $wp_query->query($param);


        $applicant_list = array();

        //一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                /*if($category_type != "")
                {
                    if(get_field('acf_acf_purespirit_type') != $category_type)
                    {
                        continue;
                    }
                }
                    */

                //削除されたユーザーのシートは表示しない
                if($delete_user_check)
                {
                    $usr_id = get_field('acf_purespirit_id');

                    if($usr_id == "")
                    {
                        continue;
                    }

                    //$usr_idが配列がどうか
                    if(is_array($usr_id))
                    {
                        continue; //ゴミが入っている
                    }

                    //$usr_idが番号じゃない
                    if(!is_numeric($usr_id))
                    {
                        continue;
                    }

                    $is_delete = get_user_meta($usr_id, 'is_delete', true);

                    if(get_user_meta($usr_id,'is_delete', true) != "")
                    {
                        continue;
                    }
                }


                $applicant_list[ get_the_ID() ] =  get_the_ID();

            endwhile;
        endif;


        return $applicant_list;

    }


    /****************************************************
	**  自分が申込者のものを取得する
	******************************************************/
	public function getSpritApplicant( $user_id )
	{
        $wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_purification', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


        $applicant_list = array();

        //一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                $app_licant = get_field('acf_applicant');//申込者

                if($app_licant == "")//申込者がない場合は基本的には作成者と同じ
                {
                    $app_licant = get_field('acf_purespirit_id');
                }

                if( $user_id ==  $app_licant)
                {

                    $applicant_list[ get_the_ID() ] =  get_field('acf_purespirit_requested_date');
                }

            endwhile;
        endif;


        return $applicant_list;
    }

     /****************************************************
	**  指定したタイプの浄霊シートを取得する
	******************************************************/
	public function getSpritApplicantSpiritType( $spirit_type_id )
	{
        $wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_purification', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC',
            'meta_query' => array(
                array(
                    'key' => 'acf_acf_purespirit_type',
                    'value' => $spirit_type_id,
                    'compare' => '='
                )
            )
        );

        

        $wp_query->query($param);


        $applicant_list = array();

        //一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

        
                $applicant_list[ get_the_ID() ] =  get_the_ID();

            endwhile;
        endif;


        return $applicant_list;
    }

    /****************************************************
	**  自分が申込者のものを取得する
	******************************************************/
	public function getSpritIntroducer( $user_id )
	{
        $wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_purification', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


        $applicant_list = array();

        //一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                if(get_field('acf_purespirit_id') == "")continue;
                if(!is_numeric(get_user_meta( get_field('acf_purespirit_id'),'input_introduction_id',true)))continue;

               // echo get_field('acf_purespirit_id') . "  " .get_user_meta( get_field('acf_purespirit_id'),'input_introduction_id',true) ."<br>";

                if( get_user_meta($user_id,'user_unique_id',true) ==  get_user_meta( get_field('acf_purespirit_id'),'input_introduction_id',true))
                {

                    $applicant_list[ get_the_ID() ] =  get_field('acf_purespirit_requested_date');
                }

            endwhile;
        endif;


        return $applicant_list;
    }


    /****************************************************
	**  リモート浄霊シート作成
	******************************************************/
	public function newRemoteSprit(  $user_id , $applicant_id , $target_array )
	{
		$wp_query = new WP_Query();

         date_default_timezone_set('Asia/Tokyo'); 

        $title = date("Y/m/d") . " " .  get_user_meta($applicant_id,'last_name',true) . " " .get_user_meta($applicant_id,'first_name',true);

        $my_post = array(
            'post_title' => $title,
            'post_type' => 'cpt_remote_sprit', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user_id,
        );

        $program_id = wp_insert_post($my_post);

        if ($program_id) {

            //申込者を入れる
            update_field("acf_remote_sprit_sheet_applicant_id" , $applicant_id, $program_id);

            //対象者を入れる
            $count = 1;
            
            foreach ($target_array as $target_key => $target_value) 
            { 

                if($target_value == "")continue;


                //対象者シートの作成
                $sheet_id = $this->newRemoteSpritTargetSheet(  $user_id , $applicant_id , $target_value ,$count );

                //sheetのIDを紐づける
                update_field("acf_remote_sprit_sheet_id_" .$count , $sheet_id, $program_id);//対象者

                $count++;
            }
            
        }

        
        return $program_id;
        // return false;

	}
    /****************************************************
	**  リモート浄霊シート作成(枠数のみ)
	******************************************************/
	public function newRemoteSpritSlots(  $user_id , $applicant_id , $target_count )
	{
		$wp_query = new WP_Query();

         date_default_timezone_set('Asia/Tokyo'); 

        $title = date("Y/m/d") . " " .  get_user_meta($applicant_id,'last_name',true) . " " .get_user_meta($applicant_id,'first_name',true);

        $my_post = array(
            'post_title' => $title,
            'post_type' => 'cpt_remote_sprit', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user_id,
        );

        $program_id = wp_insert_post($my_post);

        if ($program_id) {

            //申込者を入れる
            update_field("acf_remote_sprit_sheet_applicant_id" , $applicant_id, $program_id);

            //枠数保存
             update_field("acf_remote_sprit_sheet_target_slots" , $target_count, $program_id);

            //枠数を入れたので対象者シートを作成する(対象者の番号が決まってないので空で入れる)
            for($i=1;$i<=$target_count;$i++)
            {
                $sheet_id = $this->newRemoteSpritTargetSheet(  $user_id , $applicant_id , "" ,$i );

                //sheetのIDを紐づける
                update_field("acf_remote_sprit_sheet_id_" .$i , $sheet_id, $program_id);//対象者
            }
            
        }
        
        return $program_id;
        // return false;

	}

    /****************************************************
	**  リモート浄霊対象者シートの作成
	******************************************************/
	public function newRemoteSpritTargetSheet(  $user_id , $applicant_id , $target_id ,$sheet_num )
	{
		$wp_query = new WP_Query();

        $title = date("Y/m/d") . " " .  get_user_meta($applicant_id,'last_name',true) . " " .get_user_meta($applicant_id,'first_name',true) . "シート" .$sheet_num ;

        $my_post = array(
            'post_title' => $title,
            'post_type' => 'cpt_remote_target', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user_id,
        );

        $program_id = wp_insert_post($my_post);

        if ($program_id) {

            update_field("acf_remote_sprit_sheet_target_id", $target_id, $program_id);//対象者ID
            
        }

        
        return $program_id;
        // return false;

	}

     /********************************************************************************
	**  リモート浄霊対象者シートの対象者IDの取得をリモート浄霊シートの番号から取得
	*********************************************************************************/
	public function getRemoteSpritSheetID(  $sheet_id ,$sheet_in_number )
	{
        return  get_field("acf_remote_sprit_sheet_id_" . $sheet_in_number ,  $sheet_id) ;
	}

    /********************************************************************************
	**  リモート浄霊対象者シートの対象者IDの取得をリモート浄霊シートの番号から取得
	*********************************************************************************/
	public function getRemoteSpritTargetID(  $sheet_id ,$sheet_in_number )
	{
        return $this->getRemoteSpritTargetSheetID( get_field("acf_remote_sprit_sheet_id_" . $sheet_in_number ,  $sheet_id) );
	}

    /****************************************************
	**  リモート浄霊対象者シートの対象者IDの取得
	******************************************************/
	public function getRemoteSpritTargetSheetID(  $sheet_id )
	{
        return get_field("acf_remote_sprit_sheet_target_id" ,  $sheet_id);
	}


    /********************************************************************************
	**  リモート浄霊対象者シートの指定の情報の取得をリモート浄霊シートの番号から取得
	*********************************************************************************/
	public function getRemoteSpritTargetData( $acf_label ,  $sheet_id ,$sheet_in_number )
	{
        return $this->getRemoteSpritTargetSheetData( $acf_label , get_field("acf_remote_sprit_sheet_id_" . $sheet_in_number ,  $sheet_id) );
	}

    /****************************************************
	**  リモート浄霊対象者シートの対象者の指定の情報取得
	******************************************************/
	public function getRemoteSpritTargetSheetData( $acf_label ,  $sheet_id )
	{
        return get_field($acf_label ,  $sheet_id);
	}


    /********************************************************************************
	**  リモート浄霊対象者シートの指定の情報の取得をリモート浄霊シートの番号から保存
	*********************************************************************************/
	public function saveRemoteSpritTargetData( $acf_label ,  $sheet_id ,$sheet_in_number , $data_value )
	{
        $this->saveRemoteSpritTargetSheetData( $acf_label , get_field("acf_remote_sprit_sheet_id_" . $sheet_in_number ,  $sheet_id) , $data_value );
	}

    /****************************************************
	**  リモート浄霊対象者シートの対象者の指定の情報取得
	******************************************************/
	public function saveRemoteSpritTargetSheetData( $acf_label ,  $sheet_id , $data_value )
	{
         update_field( $acf_label, $data_value, $sheet_id);//対象者ID
	}


    /****************************************************
	**  リモート浄霊シート取得
	******************************************************/
	public function getRemoteSprit(  )
	{

        $wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_remote_sprit', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


        $applicant_list = array();

        //一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                $applicant_list[ get_the_ID() ] =  get_the_ID();

            endwhile;
        endif;


        return $applicant_list;

	}



    /****************************************************
	**  リモート浄霊仮シート作成
	******************************************************/
	public function newRemoteSpritTentative( $remote_array )
	{

        //echo $remote_array["acf_remote_sprit_sheet_applicant_id"];
		$wp_query = new WP_Query();


         $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_remote_temporary', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

         //ユニックスタイムを探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                if(get_field('acf_remote_sprit_sheet_save_unixtime') == $remote_array["acf_remote_sprit_sheet_save_unixtime"])
                {
                    return;
                }
              
            endwhile;
        endif;




        date_default_timezone_set('Asia/Tokyo'); 

        $title = date("Y/m/d") . " " .  get_user_meta($remote_array["acf_remote_sprit_sheet_applicant_id"],'last_name',true) . " " .get_user_meta($remote_array["acf_remote_sprit_sheet_applicant_id"],'first_name',true);

        $my_post = array(
            'post_title' => $title,
            'post_type' => 'cpt_remote_temporary', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => 1,
        );

        $program_id = wp_insert_post($my_post);

        if ($program_id) {


            $group_id = '3320';
            $fields = acf_get_fields($group_id);

            foreach ($fields as $field => $data) {

                if(isset( $remote_array[ $data["name"] ] ))
                {
                    update_field(  $data["name"] , $remote_array[ $data["name"] ] , $program_id);
                }

            }    
        }

        
        return $program_id;
        // return false;

	}


    /****************************************************
	**  リモート浄霊仮シート取得
	******************************************************/
	public function getRemoteSpritTentative(  )
	{

        $wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_remote_temporary', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


        $applicant_list = array();

        //一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                $applicant_list[ get_the_ID() ] =  get_the_ID();

            endwhile;
        endif;


        return $applicant_list;

	}


    /****************************************************
	**  自分がリモート浄霊の対象者になっているシート取得
	******************************************************/
	public function getRemoteSpritMyTarget( $user_id )
	{

        $wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_remote_sprit', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


        $applicant_list = array();

       
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                $slots = get_field('acf_remote_sprit_sheet_target_slots');


                for($i=1;$i<=$slots;$i++)
                {
                    //対象者になっているものを取得
                    if( $this->getRemoteSpritTargetSheetID( get_field('acf_remote_sprit_sheet_id_' . $i)) == $user_id)
                    {
                         $applicant_list[ get_the_ID() ] =  $i;
                    }
                }               

            endwhile;
        endif;


        return $applicant_list;

	}
   
    /****************************************************
	**  粗見用シート作成
	******************************************************/
	public function newAramiSheet( $post_array )
	{

		$wp_query = new WP_Query();


         $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_arami_sheet', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

         //ユニックスタイムを探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                if(get_field('acf_arami_unixtime') == $post_array["unix_time"])
                {
                    return "";
                }
              
            endwhile;
        endif;


        date_default_timezone_set('Asia/Tokyo'); 

        $title = date("Y/m/d") . " " .  $post_array["acf_arami_title"];

        $my_post = array(
            'post_title' => $title,
            'post_type' => 'cpt_arami_sheet', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => 1,
        );

        $program_id = wp_insert_post($my_post);

        if ($program_id) {
            update_field("acf_arami_type_num" , $post_array["acf_arami_type_num"], $program_id);//タイプ
            update_field("acf_arami_title" , $post_array["acf_arami_title"], $program_id);//タイトル
            update_field("acf_arami_make_day" ,  date("Y/m/d") , $program_id);//入力日
            update_field("acf_arami_unixtime" , $post_array["unix_time"], $program_id);//ユニックスタイム
        }

        
        return $program_id;
        // return false;

	}


    /****************************************************
	**  粗見用シート保存
	******************************************************/
	public function editAramiSheet( $sheet_id , $post_array  )
	{

        $group_id = '5528';//対象者
        $fields = acf_get_fields($group_id);

        foreach ($fields as $field => $data) {

            if(isset( $post_array[ $data["name"] ] ))
            {
                update_field(  $data["name"] , $post_array[ $data["name"] ] , $sheet_id);
            }
        }

        //全適用
        if(!isset( $post_array["acf_arami_status_all"] ))
        {
            update_field( "acf_arami_status_all" , "" , $sheet_id);
        }           

        if(!isset( $post_array["acf_arami_sheet_member_status_all"] ))
        {
            update_field( "acf_arami_sheet_member_status_all" , "" , $sheet_id);
        }
        
        if(!isset( $post_array["acf_arami_end_day_all"] ))
        {
            update_field( "acf_arami_end_day_all" , "" , $sheet_id);
        }           

        if(!isset( $post_array["acf_arami_scheduled_execution_date_all"] ))
        {
            update_field( "acf_arami_scheduled_execution_date_all" , "" , $sheet_id);
        }

        //一度、対象者の粗見シートチェックを外す

        $json_data = get_field("acf_arami_slots" , $sheet_id);//現在の粗見シート
        $target_array = array();

        if($json_data != "")
        {
            $target_array = json_decode($json_data, true);

            foreach( $target_array as $key => $value )
            {
                update_field( "acf_previous_target_arami" , "" , $value);
            }
        }

        //対象者を追加
        if(isset( $post_array["arami_sheet_target_id"] ))
        {    
            $target_new_array = array();

            if($json_data != "")
            {
                //前のがある
                $target_base_array = array();
                
                foreach( $post_array["arami_sheet_target_id"] as $target_id )
                {
                    // 配列が存在しない場合は空配列として初期化
                    if (!is_array($target_array)) {
                        $target_array = array();
                    }
                    $key = array_search($target_id, $target_array);

                    if($key !== false)
                    {
                        $target_base_array[$key] = $target_id;
                    }
                }

                //キーでソート
                ksort($target_new_array);

                //番号を入れなおす
                $count = 1;
                
                //先に入っているのがあるので、ここで番号を入れなおす
                foreach($target_base_array as $key => $value)
                {
                   $target_new_array[$count] =  $value;
                   update_field( "acf_previous_target_arami" , $sheet_id , $value);//粗見シートに番号を入れる
                   $this->updateAramiSheet( $value , $post_array , false ); //浄霊シートの更新
                   $count++;
                }

                //新規追加
                foreach( $post_array["arami_sheet_target_id"] as $target_id )
                {
                    //$target_base_arrayにIDが存在していなかったら、追加
                    if(!in_array($target_id , $target_base_array))
                    {
                        $target_new_array[$count] = $target_id;
                        update_field( "acf_previous_target_arami" , $sheet_id , $target_id);//粗見シートに番号を入れる
                        $this->updateAramiSheet( $target_id , $post_array , true ); //浄霊シートの更新
                        $count++;
                    }
                }

                //新しいのと比べて、番号がないものは、前のを元に戻す
                foreach( $target_array as $target_id )
                {
                    if(!in_array($target_id , $target_new_array))
                    {
                        $this->resetAramiSheet( $target_id );
                    }   
                }
            }
            else{
                //番号を入れなおす
                $count = 1;

                //ここは全部新規
                foreach( $post_array["arami_sheet_target_id"] as $target_id )
                {
                    $target_new_array[$count] = $target_id;
                    update_field( "acf_previous_target_arami" , $sheet_id , $target_id);//粗見シートに番号を入れる
                    $this->updateAramiSheet( $target_id , $post_array , true ); //浄霊シートの更新
                    $count++;
                }
            }

            //エンコード化
            $target_new_array = json_encode($target_new_array);

            update_field( "acf_arami_slots" , $target_new_array , $sheet_id);
        }
        else{
            //全部なくなっているので、前にあったものを元に戻す処理を入れる
            if($json_data != "" && is_array($target_array))
            {
                foreach( $target_array as $target_id )
                {
                    //$target_base_arrayにIDが存在していなかったら、追加
                    $this->resetAramiSheet( $target_id );
                }

            }


            update_field( "acf_arami_slots" , "" , $sheet_id);
        }

        
        
        


	}

    /****************************************************
	**  粗見用シート設定時に情報を更新 $new_add:新規で追加かどうか
	******************************************************/
	public function updateAramiSheet( $sprit_sheet_id , $post_array , $new_add = false )
	{

        //実行日
        if(isset( $post_array["acf_arami_end_day_all"] ))
        {
            update_field( "acf_purespirit_execution_date" , $post_array["acf_arami_end_day"] , $sprit_sheet_id);
           
        }

        //実行予定日
        if(isset( $post_array["acf_arami_scheduled_execution_date_all"] ))
        {
            update_field( "acf_purespirit_execution_confirmation_date" , $post_array["acf_arami_scheduled_execution_date"] , $sprit_sheet_id);
           
        }

        //管理者ステータス
        if(isset( $post_array["acf_arami_status_all"] ) && $post_array["acf_arami_sheet_status"] != "")
        {
            if($new_add){//過去のを保存
                update_field( "acf_previous_arami_befor_status" , get_field("acf_arami_title" , $sprit_sheet_id) , $sprit_sheet_id);
            }
            update_field( "acf_purespirit_status" , $post_array["acf_arami_sheet_status"] , $sprit_sheet_id);
           
        }

         //会員ステータス
         if(isset( $post_array["acf_arami_sheet_member_status_all"] ) && $post_array["acf_arami_sheet_member_status"] != "")
         {
            if($new_add){//過去のを保存
                update_field( "acf_previous_arami_befor_member_status" , get_field("acf_purespirit_user_status" , $sprit_sheet_id) , $sprit_sheet_id);
            }
             update_field( "acf_purespirit_user_status" , $post_array["acf_arami_sheet_member_status"] , $sprit_sheet_id);
            
         }
    }

     /****************************************************
	**  粗見用シートから削除されたので前のに戻す
	******************************************************/
	public function resetAramiSheet( $sprit_sheet_id )
	{
        update_field( "acf_purespirit_execution_date" , "", $sprit_sheet_id); //実行日
        update_field( "acf_purespirit_execution_confirmation_date" , "", $sprit_sheet_id); //実行予定日
        update_field( "acf_purespirit_status" , get_field("acf_previous_arami_befor_status" , $sprit_sheet_id) , $sprit_sheet_id); //管理者ステータス
        update_field( "acf_purespirit_user_status" , get_field("acf_previous_arami_befor_member_status" , $sprit_sheet_id) , $sprit_sheet_id); //会員ステータス
    }

    /****************************************************
	**  粗見用シート取得
	******************************************************/
	public function getAramiSheet(  )
	{

        $wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_arami_sheet', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


        $applicant_list = array();

        //一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                $applicant_list[ get_the_ID() ] =  get_the_ID();

            endwhile;
        endif;


        return $applicant_list;

	}

    /****************************************************
	**  粗見用シート情報セット
	******************************************************/
	public function setAramiSheet( $sheet_id )
	{
       
        // 自分が申込者
		$spiritStatusArray = $this->getSpiritAdminStatusID('cpt_spirit_status');//管理ステータス
		$spiritUSerStatusArray = $this->getSpiritAdminStatusID('cpt_spirit_usestatus');//会員ステータス


        $arami_sheet_array = array();

        $arami_sheet_array["ID"] = $sheet_id;
        $arami_sheet_array["タイトル"] = get_field("acf_arami_title" , $sheet_id);
        $arami_sheet_array["施術"] = get_field("acf_arami_type_num" , $sheet_id);
        $arami_sheet_array["登録者"] = get_field("acf_arami_slots" , $sheet_id);
        $arami_sheet_array["登録者数"] = 0; 
        //jsonデータを戻す
        if($arami_sheet_array["登録者"] != "" )
        {
            $json_data = json_decode($arami_sheet_array["登録者"], true);

            $arami_sheet_array["登録者"] = $json_data;
            $arami_sheet_array["登録者数"] = count($json_data);
        }

        $arami_sheet_array["ステータス"] = get_field("acf_arami_sheet_status" , $sheet_id);

        if($arami_sheet_array["ステータス"] == "")
        {
            $arami_sheet_array["ステータス表示"] = "未設定";
        }
        else{
            $arami_sheet_array["ステータス表示"] = $spiritStatusArray[$arami_sheet_array["ステータス"]]["title"];
        }

        $arami_sheet_array["会員ステータス"] = get_field("acf_arami_sheet_member_status" , $sheet_id);

        if($arami_sheet_array["会員ステータス"] == "")
        {
            $arami_sheet_array["会員ステータス表示"] = "未設定";
        }
        else{
            $arami_sheet_array["会員ステータス表示"] = $spiritUSerStatusArray[$arami_sheet_array["会員ステータス"]]["title"];
        }

        $arami_sheet_array["実行日"] = get_field("acf_arami_end_day" , $sheet_id);
        $arami_sheet_array["実行日年月日"] = "";
        
        if($arami_sheet_array["実行日"] != "")
        {
            $date_time = new DateTime($arami_sheet_array["実行日"]);
            $arami_sheet_array["実行日年月日"] = $date_time->format('Y年n月j日');
        }


        $arami_sheet_array["実行予定日"] = get_field("acf_arami_scheduled_execution_date" , $sheet_id);
        $arami_sheet_array["実行予定日年月日"] = "";
        
        if($arami_sheet_array["実行予定日"] != "")
        {
            $date_time = new DateTime($arami_sheet_array["実行予定日"]);
            $arami_sheet_array["実行予定日年月日"] = $date_time->format('Y年n月j日');
        }

        $arami_sheet_array["作成日"] = get_field("acf_arami_make_day" , $sheet_id);
        $arami_sheet_array["作成日年月日"] = "";

        if($arami_sheet_array["作成日"] != "")
        {
            $date_time = new DateTime($arami_sheet_array["作成日"]);
            $arami_sheet_array["作成日年月日"] = $date_time->format('Y年n月j日');
        }

        //全適用
        $arami_sheet_array["管理全適用"] = get_field("acf_arami_status_all" , $sheet_id);
        $arami_sheet_array["会員全適用"] = get_field("acf_arami_sheet_member_status_all" , $sheet_id);
        $arami_sheet_array["実行日全適用"] = get_field("acf_arami_end_day_all" , $sheet_id);
        $arami_sheet_array["実行予定日全適用"] = get_field("acf_arami_scheduled_execution_date_all" , $sheet_id);

        return $arami_sheet_array;
    }
    
    /****************************************************
	**  粗見用シートの対象者削除
	******************************************************/
	public function deleteAramiSheetUser( $sheet_id , $reset_taget_id )
	{
        
        $set_sheet_number = "";
        $set_sheet_in_number = "";

        echo $reset_taget_id ."<br>";

        for($i=1;$i<=SpiritSheetClass::MAX_ARAMI_NUMBER;$i++){

            $sheet_number =get_field("acf_arami_sheet_number_" .$i ,  $sheet_id);		//シートID

            if($sheet_number == "")
            {
                continue;
            }


			$sheet_in_number = get_field("acf_arami_sheet_in_number_" .$i ,  $sheet_id );//シート内の番号


			$target_id  =  get_field("acf_remote_sprit_sheet_id_" .$sheet_in_number ,  $sheet_number );// $this->getRemoteSpritTargetID($sheet_number ,  $sheet_in_number); //対象者番号の取得

            if($target_id != $reset_taget_id)
            {
                continue;
            }

			if($target_id == $reset_taget_id)
			{
				$this->resetAramiSheetUserData( $sheet_number , $sheet_in_number );//中身を削除

                update_field( "acf_arami_sheet_number_" .$i , "" , $sheet_id);
                update_field( "acf_arami_sheet_in_number_" .$i , "" , $sheet_id);

                $set_sheet_number = $sheet_number;
                $set_sheet_in_number = $sheet_in_number;

                //枠数を減らす
               // $slots = get_field("acf_arami_slots",  $sheet_id);


			}
            
        }

        
        //入れなおす
        if($set_sheet_number != "" && $set_sheet_in_number != "")
        {

            
            $id_arraty = array();
            $count = 0;

            for($i=1;$i<=SpiritSheetClass::MAX_ARAMI_NUMBER;$i++){


                $sheet_number =get_field("acf_arami_sheet_number_" .$i ,  $sheet_id);		//シートID

                if($sheet_number == "")
                {
                    continue;
                }

                $sheet_in_number = get_field("acf_arami_sheet_in_number_" .$i ,  $sheet_id );//シート内の番号

               $target_id  = $this->getRemoteSpritTargetID($sheet_number ,  $sheet_in_number); //対象者番号の取得

                if($target_id == "")
				{
					continue;
				}

                if($target_id == $reset_taget_id)
                {
                    continue;
                }

                $count++;

                $id_arraty[ $count ]["sheet"] = $sheet_number;
                $id_arraty[ $count ]["number"] = $sheet_in_number;

                //全部リセット
                update_field( "acf_arami_sheet_number_" .$i , "" , $sheet_id);
                update_field( "acf_arami_sheet_in_number_" .$i , "" , $sheet_id);

            }

            //全部入れなおす

            for($i=1;$i<=SpiritSheetClass::MAX_ARAMI_NUMBER;$i++){


                if(isset( $id_arraty[ $i ]))
                {
                    update_field( "acf_arami_sheet_number_" .$i ,  $id_arraty[ $i ]["sheet"] , $sheet_id);
                    update_field( "acf_arami_sheet_in_number_" .$i , $id_arraty[ $i ]["number"] , $sheet_id);
                }
            }

            //枠数を入れなおす
            update_field( "acf_arami_slots" ,  $count , $sheet_id);
            

        }

    }

    /****************************************************
	**  粗見用シートの対象者削除する際に浄霊シートの方のリセット
	******************************************************/
	public function resetAramiSheetUserData( $sheet_number , $sheet_in_number )
	{

        $this->saveRemoteSpritTargetData( "acf_remote_sprit_sheet_target_status" ,  $sheet_number ,$sheet_in_number ,"");//ステータス
        $this->saveRemoteSpritTargetData( "acf_remote_sprit_sheet_target_execution_schedule_date" ,  $sheet_number ,$sheet_in_number ,"");//実行予定日
        $this->saveRemoteSpritTargetData( "acf_remote_sprit_sheet_target_execution_date" ,  $sheet_number ,$sheet_in_number ,"");//実行日
        $this->saveRemoteSpritTargetData( "acf_remote_sprit_sheet_target_arami" ,  $sheet_number ,$sheet_in_number ,"");//粗見シートから削除

    }


    /****************************************************
	**  粗見用シートの順番を入れていく
	******************************************************/
	public function getAramiSheetTargetSort( $sheet_id , $sort_aray )
	{

        if($sort_aray == "")
        {
            return "";
        }

          //エンコード化
          $target_new_array = json_encode($sort_aray);

          update_field( "acf_arami_slots" , $target_new_array , $sheet_id);

    }


    /****************************************************
	**  画像をメディアライブラリに登録
	******************************************************/
	public function setMadeilibrary(  $file_url , $file_name, $file_path , $target)
	{
          // メディアライブラリに登録する
        $file_url = $file_url;
        $file_type = wp_check_filetype($file_name, null);

        // 添付ファイルのデータを用意
        $attachment = array(
            'guid'           => $file_url, // ファイルのURL
            'post_mime_type' => $file_type['type'], // ファイルのMIMEタイプ
            'post_title'     => $file_name,
            'post_content'   => '',
            'post_status'    => 'inherit',
        );

        // データベースに添付ファイルを登録
        $attachment_id = wp_insert_attachment($attachment, $file_path);

        // 添付ファイルのメタデータを生成して登録
        if (!is_wp_error($attachment_id)) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
            $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
            wp_update_attachment_metadata($attachment_id, $attachment_data);

            return esc_url(wp_get_attachment_url($attachment_id));
        } else {
            echo '<p>対象者' .$target .'の画像がメディアライブラリへの登録失敗しました。<br>' . $file_url . "の画像を手動で登録してください</p>";

            return "";
        }

    }

     /****************************************************
	**  年齢を返す
	******************************************************/
    function calculateAge($birthday) {
        // 入力の誕生日をDateTimeオブジェクトに変換
        $birthDate = new DateTime($birthday);

        date_default_timezone_set('Asia/Tokyo');

        // 現在の日付を取得
        $today = new DateTime();
        // 年齢を計算
        $age = $today->diff($birthDate)->y;

        return $age;
    }


    /****************************************************
	**  シートの対象者情報をリセット
	******************************************************/
    function resetSheetTagetData($sheet_id) {


        //シートOFF

        update_field( "acf_target_onoff" ,  "" , $sheet_id);

       
        $group_id = '7898';//対象者
        $fields = acf_get_fields($group_id);

        foreach ($fields as $field => $data) {

            update_field(  $data["name"] , "" , $sheet_id);
        }    
    }

    /****************************************************
	**  シートの対象者情報をセット
	******************************************************/
    function setSheetTagetData($sheet_id,$post) {


        //シートON

        update_field( "acf_target_onoff" ,  1 , $sheet_id);


        $group_id = '7898';//対象者
        $fields = acf_get_fields($group_id);

        foreach ($fields as $field => $data) {

            if(isset($post[ $data["name"] ]))
            {
                 update_field(  $data["name"] , $post[ $data["name"] ] , $sheet_id);
            }
        }    
        
    }
}









?>