<?php 

class GroupSettingClass
{

    /****************************************************
    **  現在のデータ取得
    ******************************************************/
    public function getGroupData($id_key_type = false)
    {
        $wp_query = new WP_Query();
        
         $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_group_data', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

       
        //ソート用
        $sort_array = "";

        $count = 0;

        //データを入れる
        if($wp_query->have_posts()): while($wp_query->have_posts()) : $wp_query->the_post();

            if(get_field( 'is_delete') == true) continue;

            if($sort_array == "")
            {
                $sort_array = array();
            }
           

            if(get_field( 'acf_group_setting_sort_id') == "")
            {
                $count++;
            }
            else{
                $count = get_field( 'acf_group_setting_sort_id');
            }

            if($id_key_type)
            {
                $count = get_the_ID();
            }


            $sort_array[ $count ]["ID"] = get_the_ID();
            $sort_array[ $count ]["sort"] = get_field( 'acf_group_setting_sort_id');
            $sort_array[ $count ]["title"] = get_field( 'acf_group_setting_name');
            $sort_array[ $count ]["is_delete"] = get_field( 'is_delete');

        endwhile; endif;


        //ソート
        if($sort_array != "")
        {
            ksort($sort_array);
        }


        return $sort_array;

    }
    

    /****************************************************
    **  グループタイトル取得
    ******************************************************/
    public function getGroupName()
    {
        $wp_query = new WP_Query();
        
         $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_group_data', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

       
        //ソート用
        $sort_array = "";

        $count = 0;

        //データを入れる
        if($wp_query->have_posts()): while($wp_query->have_posts()) : $wp_query->the_post();


            if($sort_array == "")
            {
                $sort_array = array();
            }

            $sort_array[ get_the_ID() ] = get_field( 'acf_group_setting_name');

        endwhile; endif;



        return $sort_array;

    }
    
    /****************************************************
    **  新規項目追加
    ******************************************************/
    public function makeGroup($title ,$unixtime){
        if($this->checkUnixTime( $unixtime ) != "")
        {
            return "";
        }
        

        $user = wp_get_current_user();
                            
        $my_post = array(
            'post_title' => $title,
            'post_type' => 'cpt_group_data', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );

        
        $program_id = wp_insert_post($my_post);

        if($program_id)
        {
            $last_num = $this->getGroupSettingNum() + 1;//最後のソート番号

            update_field( "acf_group_setting_sort_id", $last_num , $program_id);//ソート番号
            update_field( "acf_group_setting_name", $title , $program_id);//名前
            update_field( "acf_group_setting_unixtime", $unixtime , $program_id);//UNIXTIME
            update_field( "is_delete", false , $program_id);//UNIXTIME
        }


        return $program_id;
    }

    
    /****************************************************
	 **  同じものがあるかどうかを調べる
	 ******************************************************/
	public function checkGroupStatus( $id , $title )
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_group_data', //カスタム投稿タイプの名称を入れる
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

                    $base_title =  str_replace(' ','', get_field('acf_group_setting_name'));
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
	 **  管理ステータスを編集
	 ******************************************************/
	public function saveGroupTitle( $id , $inputData )
	{
        update_field("acf_group_setting_name", $inputData, $id);

    }

    /****************************************************
	 **  管理ステータスを編集
	 ******************************************************/
    public function deleteGrouptatus( $id )
	{
        update_field("is_delete", true, $id);

    }
    
    /****************************************************
    **  同じUNIXTIMEがあるかどうかをチェック
    ******************************************************/
    public function checkUnixTime( $unixtime )
    {
        
         $wp_query = new WP_Query();
        
         $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_group_data', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'meta_query' => array( 
                array(
                    'key' => 'acf_group_setting_unixtime', // カスタムフィールドのキー。
                    'value' => $unixtime, // カスタムフィールドの値 (注意 compareの値が'IN'、'NOT IN'、'BETWEEN'、'NOT BETWEEN'のみ配列をサポート)
                    'compare' => '=',
                ),
                
            ),
        );

        $wp_query->query($param);

        if($wp_query->have_posts()): while($wp_query->have_posts()) : $wp_query->the_post();

            return get_the_ID();

        endwhile; endif;

        return "";

    }

    /*********************************************************
    **  順番並び替え
    *********************************************************/
    public function saveGroupStatusSort( $sortArray )
	{
         foreach ($sortArray as $key => $value) {
               update_field("acf_group_setting_sort_id", $key + 1, $value);
         }

    }

    
    /*********************************************************
    **  ソート番号の最後の番号を取得（一緒に並べなおしもする)
    *********************************************************/
    public function getGroupSettingNum()
    {
        $wp_query = new WP_Query();
        
        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_group_data', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        //ソート用
        $sort_num = 0;

        //データを入れる
        if($wp_query->have_posts()): while($wp_query->have_posts()) : $wp_query->the_post();


            if($sort_num < get_field( 'acf_group_setting_sort_id'))
            {
                $sort_num = get_field( 'acf_group_setting_sort_id');
            }

        endwhile; endif;


        return $sort_num;

    }

}

?>