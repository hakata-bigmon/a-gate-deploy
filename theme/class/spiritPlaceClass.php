<?php 


class SpiritPlsceClass
{

   
	

    /****************************************************
	 **  場所データを作成
	 ******************************************************/
	public function newSpritPlace($post_data)
	{

        //同じUNIXTIMEがあるなら作成しない
        $unix_id = $this->checkSpritPlaceUnixtime( $post_data["unixtime"] );

        if($unix_id != "")
        {
            return $unix_id;
        }

        $user = wp_get_current_user();

        date_default_timezone_set('Asia/Tokyo'); 

        //今日の日付
        $today = date("Y-m-d H:i:s");

        $title =  $post_data["place_name"] . " " . $today;


        $my_post = array(
            'post_title' => $title,
            'post_type' => 'cpt_spriit_place', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );

        $program_id = wp_insert_post($my_post);

        if ($program_id) {
            
            update_field("acf_spirit_palce_name", $post_data["place_name"], $program_id);//場所名前
            update_field("acf_spirit_palce_url", $post_data["place_url"], $program_id);//場所URL
            update_field("acf_spirit_palce_googlemap", $post_data["place_map"], $program_id);//MAP URL
            update_field("acf_spirit_palce_access", $post_data["place_access"], $program_id);//アクセス
            update_field("acf_spirit_palce_unixtime", $post_data["unixtime"], $program_id);//ユニックスタイム
            update_field("acf_spirit_palce_address", $post_data["place_address"], $program_id);//住所

        }

        
        return $program_id;
    }


    /****************************************************
	 **  場所データを保存
	 ******************************************************/
	public function saveSpritPlace( $place_num , $post_data)
	{
       
        if ($place_num) {
            
            update_field("acf_spirit_palce_name", $post_data["place_name"], $place_num);//場所名前
            update_field("acf_spirit_palce_url", $post_data["place_url"], $place_num);//場所URL
            update_field("acf_spirit_palce_googlemap", $post_data["place_map"], $place_num);//MAP URL
            update_field("acf_spirit_palce_access", $post_data["place_access"], $place_num);//アクセス
            update_field("acf_spirit_palce_address", $post_data["place_address"], $place_num);//住所

        }

        return $place_num;
    }


     /****************************************************
	**  場所のユニックスタイム検索
	******************************************************/
	public function checkSpritPlaceUnixtime( $unixtime )
	{
        $wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_spriit_place', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        $count = 0;

        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                $num = get_field('acf_spirit_palce_unixtime');

                if( $unixtime == $num)
                {
                    return get_the_ID();
                }

            endwhile;
        endif;

        return "";
    }


    /****************************************************
	 **  場所データの取得
	 ******************************************************/
	public function getSpritPlaceList()
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_spriit_place',//'cpt_spirit_status', //カスタム投稿タイプの名称を入れる
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

                $sort_array[get_the_ID()] = get_the_ID();

            endwhile;
        endif;

        return $sort_array;

	}
}









?>