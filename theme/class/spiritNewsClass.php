<?php 


class SpiritNewsClass
{

	const TOP_NEWS_NUM = 3;

	
	/****************************************************
	 **  ニュースデーターを作成（メール送信とセット）
	 ******************************************************/
	public function createNewsDataMail($user_id,$post_array,$mail_data)
	{

		//acf_news_save_unixtimeが$post_array["mail_send_unixtime"]と同じものがある場合は更新しない
		$news_data = get_posts(array(
			'post_type' => 'cpt_news',
			'meta_key' => 'acf_news_save_unixtime',
			'meta_value' => $post_array["mail_send_unixtime"],
		));
		
		if(count($news_data) > 0){
			return "";
		}

		$current_user = wp_get_current_user();


		$wp_query = new WP_Query();
     
        $my_post = array(
            'post_title' => $mail_data["mail_subject"],
            'post_type' => 'cpt_news', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $current_user->ID,
        );

        $program_id = wp_insert_post($my_post);

		if($program_id != 0)
		{
			//メールデータをセット
			update_field('acf_news_save_unixtime', $post_array["mail_send_unixtime"], $program_id);
			update_field('acf_news_all_post', "specific_users", $program_id);

			$target_array = array($user_id);

			$json_data = json_encode($target_array, JSON_UNESCAPED_UNICODE);//JSON化

			update_field('acf_news_member', $json_data, $program_id);


			update_field('acf_news_post', "1", $program_id);
			update_field('acf_news_disp', "1", $program_id);
			update_field('acf_news_disp_date', "", $program_id);

			//番号の投稿(post_contens)にbodyを入れる
			$post_content = get_post($program_id);
			$post_content->post_content = $mail_data["body"];
			wp_update_post($post_content);

			return $program_id;
		
		}
		
		return "";
	}


	/****************************************************
	 **  ニュースデーターを取得（通常）
	 ******************************************************/
	public function getNewsData()
	{
		
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_news', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


        $news_list = array();


		//データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                $news_list[ get_the_ID() ]["post_user"] = get_field('acf_news_all_post');//全員か個別送信か
                $news_list[ get_the_ID() ]["news_member"] = get_field('acf_news_member');//個別送信送信者
                $news_list[ get_the_ID() ]["news_post"] = get_field('acf_news_post');//送信したかどうか（会員確認フラグ）
                $news_list[ get_the_ID() ]["news_disp"] = get_field('acf_news_disp');//会員表示
                $news_list[ get_the_ID() ]["news_title"] = get_the_title();//タイトル
				
				$date_time = new DateTime(get_the_date());
				$date = $date_time->format('Y年n月j日');
                $news_list[ get_the_ID() ]["news_date"] = $date;//投稿作成日付
                $news_list[ get_the_ID() ]["news_disp_date"] = get_field('acf_news_disp_date');//告知日付
            endwhile;
        endif;


        return $news_list;
	}


    /****************************************************
	 **  送信可能な会員の取得(先に取得)
	 ******************************************************/
	public function getNewsPostUSer($no_mail)
	{
		
		$users = get_users();
        $user_names[] = array();


        foreach ($users as $users_key => $users_value) {


            $city = get_user_meta($users_value->ID, 'billing_city', true);
            $billing_address  =  get_user_meta($users_value->ID, 'billing_address_1', true);

            $user_names[ $users_value->ID ] = get_user_meta($users_value->ID, 'last_name', true) . " " . get_user_meta($users_value->ID, 'first_name', true);

            if($no_mail == true)
            {
                $user_names[ $users_value->ID ] .= "(" .$users_value->user_email . ")"; 
            }


        }

        return $user_names;
	}


    /****************************************************
	**  閲覧可能なユーザーの配列
	******************************************************/
	public function getEnablePostUserID($id)
	{
		
		$news_member_array = array();

		$news_member = get_field('acf_news_member', $id);

		//空じゃない場合、デコード
		if($news_member != "")
		{
			$news_member = json_decode($news_member, true);  //jsonデータ戻し

			//var_dump($user_data);

			foreach ($news_member as $key => $value) {
						
				$news_member_array[$value] = get_user_meta($value, 'last_name', true) . " " . get_user_meta($value, 'first_name', true);;
			}
		}

        return $news_member_array;
	}


    /****************************************************
	** 表示日付を取得(漢字)
	******************************************************/
	public function getPostDate($id)
	{
		$disp_date = get_field('acf_news_disp_date', $id);//告知日

		if($disp_date == "")
		{
			return get_the_date('Y.n.j',$id);//作成日
		}
		else{
			$date_time = new DateTime($disp_date);

            return $date_time->format('Y.n.j');
		}
	}

	/****************************************************
	** 表示日付を取得(ハイフン)
	******************************************************/
	public function getPostDateHyphen($id)
	{
		$disp_date = get_field('acf_news_disp_date', $id);//告知日

		if($disp_date == "")
		{
			return get_the_date('',$id);//作成日
		}
		else{
			$date_time = new DateTime($disp_date);

            return $date_time->format('Y-M-d');
		}
	}

	/****************************************************
	** 表示日付が今日どうかを比べる
	******************************************************/
	public function isPostDate($id)
	{
		$disp_date = get_field('acf_news_disp_date', $id);//告知日
		
		if($disp_date == "")
		{
			//$date_time = new DateTime(get_the_date());
			//$date = $date_time->format('Y年n月j日');

			$disp_date = get_the_date('',$id);//作成日
		}
		date_default_timezone_set('Asia/Tokyo');

		$today = date("Y-m-d") . " 00:00:00";

		$d1 = new DateTime($today);
        $d2 = new DateTime($disp_date. " 00:00:00");

       
		//今日の方が後
        if($d1 < $d2)
        {
            return false;
        }
		
		
		 return true;

	}


	/****************************************************
	** そのユーザーの見れるニュースを返していく
	******************************************************/
	public function getNewsPostDate($user_id)
	{
		$wp_query = new WP_Query();
        
        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_news', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);
		
		
		$news_array = array();


		$news_array["all"] = array(); //日付別（キー:日付）
		$news_array["whole"] = array();//個人宛日付別（キー:日付）
		$news_array["individual"] = array();//全員宛日付別（キー:日付）

		$news_array["unread"] = 0;//未読件数

		//データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


				$id = get_the_ID();

				//表示しない場合は見れない
				if(get_field('acf_news_disp', $id) == "")
				{
					continue;
				}
				
				

				//告知日で見れるかどうの確認
				if(!$this->isPostDate($id))
				{
					continue;
				}

				//告知日を取得
				$disp_day = $this->getPostDateHyphen($id);

				$date_unix_base = new DateTime($disp_day);

				//ユニックスタイム変換
				$date_unix = $date_unix_base->format('U');

		
				$post_user = get_field('acf_news_all_post', $id);

				if($post_user == "all_users")
				{
					//全員なので見れる

					//格納
					if(!isset($news_array["all"][$date_unix]))
					{
						$news_array["all"][$date_unix] = array();
					}

					if(!isset($news_array["whole"][$date_unix]))
					{
						$news_array["whole"][$date_unix] = array();
					}
					
					array_push($news_array["all"][$date_unix] , $id);
					array_push($news_array["whole"][$date_unix] , $id);


					//未読かどうか
					if(!$this->checkUnreadPost($user_id , $id))
					{
						$news_array["unread"]++;
					}

				}
				else if($post_user == "no_users" || $post_user == "")
				{
					//指定されてないので見れない
					continue;
				}
				else if($post_user == "specific_users")
				{

					$member_array = $this->getEnablePostUserID($id);//ユーザーのデータ取得

					if(!isset($member_array[$user_id]))
					{
						//指定されてなかったら見れない
						continue;
					}

					//格納
					if(!isset($news_array["all"][$date_unix]))
					{
						$news_array["all"][$date_unix] = array();
					}

					if(!isset($news_array["individual"][$date_unix]))
					{
						$news_array["individual"][$date_unix] = array();
					}

					array_push($news_array["all"][$date_unix] , $id);
					array_push($news_array["individual"][$date_unix] , $id);

					//未読かどうか
					if(!$this->checkUnreadPost($user_id , $id))
					{
						$news_array["unread"]++;
					}
				}

				

            endwhile;
        endif;



		//それぞれ大きい方でソートしていく
		if(count($news_array["all"]) > 0)
		{
			krsort($news_array["all"]);
		}

		if(count($news_array["whole"]) > 0)
		{
			krsort($news_array["whole"]);
		}

		if(count($news_array["individual"]) > 0)
		{
			krsort($news_array["individual"]);
		}

		
		return $news_array;

	}

	/****************************************************
	** ニュース配列をIDをキーにして取得する
	******************************************************/
	public function getNewsPostDateNoSort($user_id)
	{
		$news_array =  $this->getNewsPostDate($user_id);//確認可能の全て―タを取得


		$no_sort_array = array();


		$count = 0;

		foreach ($news_array["all"] as $key => $value) {

            foreach ($value as $news_key => $news_value) {

				$no_sort_array[ $news_value ] = $count;

				$count++;
			}
		}

		return $no_sort_array;

	}


	/****************************************************
	** そのユーザーが既読したかどうか
	******************************************************/
	public function setUnreadPost($user_id , $post_id)
	{
		//告知している場合のみ
		if(get_field('acf_news_post', $post_id) != "")
		{

			$user = get_userdata($user_id);

			//ログイン以降のものだけを調べる
			$disp_date = $this->getPostDateHyphen($post_id);//表示日
			$user_registered = $user->user_registered;


			$d1 = new DateTime($user_registered);
			$d2 = new DateTime($disp_date. " 00:00:00");

			//表示日が小さい時は既読済み
			if($d1 > $d2)
			{
				return;
			}

			//ユーザーメタが存在しているかどうか(存在していると既読)
			if ( !metadata_exists( 'user', $user_id, "news_" .$post_id ) ) {
				//ユーザーメタを作成
				add_user_meta( $user_id, "news_" .$post_id, 1 , true );

			}
		}

		return;//既読、もしくは関係ない

	}

	/****************************************************
	** そのユーザーが未読かどうか
	******************************************************/
	public function checkUnreadPost($user_id , $post_id)
	{
		//告知している場合
		if(get_field('acf_news_post', $post_id) != "")
		{

			$user = get_userdata($user_id);

			//ログイン以降のものだけを調べる
			$disp_date = $this->getPostDateHyphen($post_id);//表示日
			$user_registered = $user->user_registered;


			$d1 = new DateTime($user_registered);
			$d2 = new DateTime($disp_date. " 00:00:00");

			//表示日が小さい時は既読済み
			if($d1 > $d2)
			{
				return true;
			}

			//ユーザーメタが存在しているかどうか(存在していると既読)
			if ( !metadata_exists( 'user', $user_id, "news_" .$post_id ) ) {
				//存在していないので未読
				return false;
			}
		}

		return true;//既読、もしくは関係ない

	}

	/****************************************************
	** そのユーザーの既読を削除
	******************************************************/
	public function deleteUnreadPost($user_id , $post_id)
	{
		//ユーザーメタが存在しているかどうか(存在していると削除)
		if ( metadata_exists( 'user', $user_id, "news_" .$post_id ) ) {
			//存在していないので未読
			delete_user_meta( $user_id, "news_" .$post_id);
		}
	}

	/****************************************************
	** 全ユーザーの既読を削除
	******************************************************/
	public function deleteUnreadPostAllUser($post_id)
	{

		$users = get_users();

		foreach ($users as $users_key => $users_value) {
			$this->deleteUnreadPost($users_value->ID , $post_id);
		}
	}


	/****************************************************
	**  ユーザーページのお知らせの絞り込み保存
	******************************************************/
	public function saveUserNewsSearch($user_id,$target_id,$unread_num)
	{

		//ユーザーメタが存在しているかどうか(
		if ( !metadata_exists( 'user', $user_id, "search_user_page_news_unread_" .$target_id) ) {
			//ユーザーメタを作成
			add_user_meta( $user_id,  "search_user_page_news_unread_" .$target_id, $unread_num , true ); //新規作成

		}
		else{
			//存在している
			update_user_meta( $user_id, "search_user_page_news_unread_" .$target_id , $unread_num);

			
		}

		
	}


	/****************************************************
	**  ユーザーページのお知らせの絞り込み取得
	******************************************************/
	public function getUserNewsSearch($user_id,$target_id)
	{


		$search_array = array();

		//ユーザーメタが存在しているかどうか(存在している）
		if ( metadata_exists( 'user', $user_id, "search_user_page_news_unread_" .$target_id) ) {
			//ユーザーメタを作成
			$search_array["news_unread"] = get_user_meta( $user_id , "search_user_page_news_unread_" .$target_id , true);
		}
		else{
			//存在している
			$search_array["news_unread"] = 0;

			
		}

		return $search_array;
	}


	/****************************************************
	** 個別のニュースデータを取得
	******************************************************/
	public function getNewsPostData($post_id)
	{

		$post_data = array();

		//タイトル
		$post_data["title"] = get_the_title($post_id);

		//表示日
		$post_data["disp_date"] = $this->getPostDateHyphen($post_id);

		//表示日が８日以内かどうか
		$post_data["is_new"] = false;

		//今日と表示日が8以内かどうかを調べる
		$today = new DateTime();
		$disp_date = new DateTime($post_data["disp_date"]);

		if($today->diff($disp_date)->days <= 8)
		{
			$post_data["is_new"] = true;
		}

		//表示日年月日
		$post_data["disp_date_year_month_day"] = $this->getPostDate($post_id);

		//個別かどうか
		$post_data["is_individual"] = get_field('acf_news_member', $post_id);


		return $post_data;
	}
}









?>


