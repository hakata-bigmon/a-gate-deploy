<?php 

class TemporaryRegistrationClass
{
    /****************************************************
    **  仮登録の全データを取得し、同じ番号があるかを調べる
	******************************************************/
	public function getAllTemporaryRegistration( )
	{
		$wp_query = new WP_Query();
      
        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_temporary_reg', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        $all_data = array();

		//一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                $all_data[ get_the_ID() ] = get_field('acf_temporary_order_day');

            endwhile;
        endif;

        arsort($all_data);
        
        return $all_data;
        // return false;

	}



    /****************************************************
	**  仮登録の全データを取得し、同じ番号があるかを調べる
	******************************************************/
	public function getTemporaryRegistration( )
	{
		$wp_query = new WP_Query();
      
        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_temporary_reg', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

		//一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                $num = get_field('acf_temporary_order_num');

                if( $order_number == $num)
                {
                    return true;
                }

            endwhile;
        endif;

        
        return false;
        // return false;

	}
	
    /****************************************************
	**  仮登録の新規登録
	******************************************************/
	public function newTemporaryRegistration( $post_data )
	{
		$wp_query = new WP_Query();
      
        $user = wp_get_current_user();

        $my_post = array(
            'post_title' => $post_data["acf_temporary_order_num"],
            'post_type' => 'cpt_temporary_reg', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );


        $group_id = '936';
        $fields = acf_get_fields($group_id);

      

        $program_id = wp_insert_post($my_post);

        if ($program_id) {
            
            foreach ($fields as $field => $data) {

                if(isset( $post_data[ $data["name"] ] ))
                {
                    update_field(  $data["name"] , $post_data[ $data["name"] ] , $program_id);
                }
            }

        }

        
        return $program_id;
        // return false;

	}


    /****************************************************
	**  同じオーダー番号があるかどうか
	******************************************************/
	public function IsTemporaryRegistrationOrderNumber( $order_number )
	{
		$wp_query = new WP_Query();
      
        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_temporary_reg', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

		//一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                $num = get_field('acf_temporary_order_num');

                if( $order_number == $num)
                {
                    return true;
                }

            endwhile;
        endif;

        
        return false;
        // return false;

	}

    /****************************************************
	**  同じオーダー番号のものを上書き
	******************************************************/
	public function UpDataTemporaryRegistrationOrderNumber( $post_data )
	{
		$wp_query = new WP_Query();
      
        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_temporary_reg', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        $order_id = "";

		//一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                $num = get_field('acf_temporary_order_num');

                if( $post_data["acf_temporary_order_num"] == $num)
                {

                    $order_id = get_the_ID();
                    break;
                }

            endwhile;
        endif;


        if($order_id != "")
        {
            $group_id = '936';
            $fields = acf_get_fields($group_id);

            foreach ($fields as $field => $data) {

                if(isset( $post_data[ $data["name"] ] ))
                {
                    update_field(  $data["name"] , $post_data[ $data["name"] ] , $order_id);
                }
            }
        }
       

	}

    /****************************************************
	**  CSVファイルのデータを直す
	******************************************************/
	public function ChengeCSVData( $csvData )
	{
         $up_date_array = array();

            
        foreach ($csvData as $key => $value) {

            if($key == 0)
            {
                continue;
            }

            //削除したものは省く
            if($this->IsTemporaryDeleteOrderNumber( $value[0] ))
            {
                 continue;
            }


            //すでに登録があるものは上書きする
            if(($value[1] == "キャンセル" || $value[1] == "返金済み")  && !$this->IsTemporaryRegistrationOrderNumber(  $value[0] ))
            //キャンセル/返金済み	は省く
            {
                continue;
            }

            

                    

            $up_date_array[ $key ] = array();

            $up_date_array[ $key ]["acf_temporary_order_num"] = $value[0];
            $up_date_array[ $key ]["acf_temporary_status"] = $value[1];

            $up_date_array[ $key ]["acf_temporary_payment"] = $value[2];
            $up_date_array[ $key ]["acf_temporary_order_day"] = $value[3];
            $up_date_array[ $key ]["acf_temporary_payment_end"] = $value[4];
            $up_date_array[ $key ]["acf_temporary_item_name"] = $value[8];
            $up_date_array[ $key ]["acf_temporary_product_number"] = $value[9];
            $up_date_array[ $key ]["acf_temporary_subtotal"] = $value[13];
            $up_date_array[ $key ]["acf_temporary_type"] = $value[15];
            $up_date_array[ $key ]["acf_temporary_total_pay"] = $value[26];//合計

            $up_date_array[ $key ]["acf_temporary_last_name"] = $value[42];
            $up_date_array[ $key ]["acf_temporary_first_name"] = $value[43];
            $up_date_array[ $key ]["acf_temporary_post_number"] = $value[44];
            $up_date_array[ $key ]["acf_temporary_address_1"] = $value[45];
            $up_date_array[ $key ]["acf_temporary_address_2"] = $value[46];
            $up_date_array[ $key ]["acf_temporary_tel"] = $value[47];
            $up_date_array[ $key ]["acf_temporary_mail"] = $value[48];

            //var_dump($value);
            //echo "<br>";
        }

        return $up_date_array;

    }


    /****************************************************
	**  仮登録削除番号に同じオーダー番号があるかどうか
	******************************************************/
	public function IsTemporaryDeleteOrderNumber( $order_number )
	{
		$wp_query = new WP_Query();
      
        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_temporary_delete', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

		//一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                $num = get_field('acf_temporary_delete_order_number');

                if( $order_number == $num)
                {
                    return true;
                }

            endwhile;
        endif;

        
        return false;
        // return false;

	}

    /****************************************************
	**  仮登録削除の新規登録
	******************************************************/
	public function newTemporaryDelete( $order_num,$order_name )
	{
		$wp_query = new WP_Query();
      
        $user = wp_get_current_user();

        $my_post = array(
            'post_title' => $order_num,
            'post_type' => 'cpt_temporary_delete', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );


        $program_id = wp_insert_post($my_post);

        if ($program_id) {
            
           update_field( "acf_temporary_delete_order_number" ,$order_num , $program_id);
           update_field( "acf_temporary_delete_name", $order_name , $program_id);

        }

        
        return $program_id;
        // return false;

	}

    /****************************************************
	**  電話番号を分割
	******************************************************/
    function splitPhoneNumber($phoneNumber) {
       

         // ハイフンやスペースを除去
        $phoneNumber = preg_replace('/[^\d]/', '', $phoneNumber);

        // 携帯電話の場合（先に判定）
        if (preg_match('/^(070|080|090)(\d{4})(\d{4})$/', $phoneNumber, $matches)) {
            $areaCode = $matches[1];
            $cityCode = $matches[2];
            $subscriberNumber = $matches[3];
            $type = "スマホ";
        }
        // 固定電話の場合
        elseif (preg_match('/^(0\d{1,4})(\d{1,4})(\d{4})$/', $phoneNumber, $matches)) {
            $areaCode = $matches[1];
            $cityCode = $matches[2];
            $subscriberNumber = $matches[3];
             $type = "固定";
        } else {
            // 該当しない形式の場合
            return [
                'error' => 'Invalid phone number format',
            ];
        }

        return [
            'area_code' => $areaCode,
            'city_code' => $cityCode,
            'subscriber_number' => $subscriberNumber,
            'type' => $type,
        ];

    }

    /****************************************************
	**  文字と文字の間を切り抜く
	******************************************************/
    function getStringBetween($string, $start, $end) {
        // 正規表現を作成
        $pattern = '/'. preg_quote($start, '/') . '(.*?)' . preg_quote($end, '/') . '/';
        if (preg_match($pattern, $string, $matches)) {
            return $matches[1];
        }
        return null; // 該当部分がない場合
    }

}

?>