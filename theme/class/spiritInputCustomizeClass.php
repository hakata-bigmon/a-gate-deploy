<?php 


class SpiritInputCustomizeClass
{


	/****************************************************
	 **  入力フォームの取得
	 ******************************************************/
	public function getInputCustomizeData( $type_id )
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_input_form_text', //カスタム投稿タイプの名称を入れる
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


                
                $type = get_field('acf_spirit_type');
                
               
                if($type == $type_id)
                {
                    if ($sort_array == "") {
                        $sort_array = array();
                    }
    
                    $sort_array["ID"] = get_the_ID();
                    $sort_array["type"] = get_field('acf_spirit_type');
                    $sort_array["acf_spirit_flow_text"] = get_field('acf_spirit_flow_text');
                    $sort_array["acf_spirit_start_text"] = get_field('acf_spirit_start_text');
                    $sort_array["acf_spirit_text_1"] = get_field('acf_spirit_text_1');
                    $sort_array["acf_spirit_before_input"] = get_field('acf_spirit_before_input');
                    $sort_array["acf_spirit_text_2"] = get_field('acf_spirit_text_2');
                    $sort_array["acf_spirit_text_3"] = get_field('acf_spirit_text_3');
                    $sort_array["acf_spirit_contact_information"] = get_field('acf_spirit_contact_information');
                    $sort_array["acf_spirit_contact_information_2"] = get_field('acf_spirit_contact_information_2');
                    $sort_array["acf_spirit_note_1"] = get_field('acf_spirit_note_1');
                    $sort_array["acf_spirit_note_2"] = get_field('acf_spirit_note_2');
                    $sort_array["acf_spirit_note_3"] = get_field('acf_spirit_note_3');
                    $sort_array["acf_spirit_note_4"] = get_field('acf_spirit_note_4');
                    $sort_array["acf_spirit_note_5"] = get_field('acf_spirit_note_5');

                    $sort_array["acf_spirit_note_title_1"] = get_field('acf_spirit_note_title_1');
                    $sort_array["acf_spirit_note_title_2"] = get_field('acf_spirit_note_title_2');
                    $sort_array["acf_spirit_note_title_3"] = get_field('acf_spirit_note_title_3');
                    $sort_array["acf_spirit_note_title_4"] = get_field('acf_spirit_note_title_4');
                    $sort_array["acf_spirit_note_title_5"] = get_field('acf_spirit_note_title_5');

                  
                     $sort_array["acf_spirit_note_personal_table"] = $this->getInputCustomizPpersonalData( $sort_array["ID"] );

                    break;
                }
               
            endwhile;
        endif;


        return $sort_array;

	}


    /****************************************************
	 **  入力フォームの個人情報取得
	 ******************************************************/
	public function getInputCustomizPpersonalData( $type_id )
	{
        $array_data = get_field('acf_spirit_note_personal_table', $type_id);

        $array = "";

        if ($array_data == "") {
            $array = array();
        } else {
            $array = json_decode($array_data, true);
        }


        return $array;
    }

     /****************************************************
	 **  入力フォームの個人情報保存
	 ******************************************************/
	public function saveInputCustomizPpersonalData( $type_id , $post_array)
	{
         //JSON化
        $json_data = json_encode($post_array, JSON_UNESCAPED_UNICODE);

        update_field("acf_spirit_note_personal_table", $json_data, $type_id);//更新者
    }

     /****************************************************
	** 入力フォームの新規登録
	******************************************************/
	public function newInputCustomizeData( $type_id  )
	{
		$wp_query = new WP_Query();

        $user = wp_get_current_user();

        $my_post = array(
            'post_title' => get_field('acf_pure_spirit_title',$type_id),
            'post_type' => 'cpt_input_form_text', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );

        $program_id = wp_insert_post($my_post);


        if ($program_id) {
            update_field(  "acf_spirit_type" , $type_id , $program_id);

        }

     //   return false;

	}
    /****************************************************
	 **  入力フォームの編集
	 ******************************************************/
	public function saveInputCustomizeData( $id ,$post_data)
	{
        
        //fieldを全取得
        $group_id = '277';
        $fields = acf_get_fields($group_id);

        foreach ($fields as $field => $data) {

            if(isset( $post_data[ $data["name"] ] ))
            {
                update_field(  $data["name"] , $post_data[ $data["name"] ] , $id);
            }

        }    
    }


     /****************************************************
	 **  取得した文章の文字列を変更
	 ******************************************************/
	public function changeInputCustomizeData( $text_data )
	{
        $text_data = str_replace('<R>', '<font color="red">', $text_data);
        $text_data = str_replace('</R>', '</font>', $text_data);
        $text_data = str_replace('<B>', '<b>', $text_data);
        $text_data = str_replace('</B>', '</b>', $text_data);
        $text_data = str_replace('<LY>', '<span style="background-color:yellow">', $text_data);
        $text_data = str_replace('</LY>', '</span>', $text_data);
        $text_data = str_replace('<RB>', '<font color="red"><b>', $text_data);
        $text_data = str_replace('</RB>', '</b></font>', $text_data);
        $text_data = str_replace('<B2>', '<b  style="font-size:20px;">', $text_data);
        $text_data = str_replace('</B2>', '</b>', $text_data);

        return $text_data;

    }


    /****************************************************
	 **  サンクスページの取得
	 ******************************************************/
	public function getThanksCustomizeData( $type_id )
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_thanks_text', //カスタム投稿タイプの名称を入れる
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


                
                $type = get_field('acf_thanks_spirit_type');
                
               
                if($type == $type_id)
                {
                    if ($sort_array == "") {
                        $sort_array = array();
                    }
    
                    $sort_array["ID"] = get_the_ID();
                    $sort_array["type"] = get_field('acf_thanks_spirit_type');
                    $sort_array["acf_thanks_spirit_start_text"] = get_field('acf_thanks_spirit_start_text');
                    $sort_array["acf_thanks_photo_text"] = get_field('acf_thanks_photo_text');
                    $sort_array["acf_thanks_photo_text_id"] = get_field('acf_thanks_photo_text_id');
                    $sort_array["acf_thanks_photo_text_on"] = get_field('acf_thanks_photo_text_on');
                    $sort_array["acf_thanks_text_1"] = get_field('acf_thanks_text_1');
                    $sort_array["acf_thanks_contact_information_1"] = get_field('acf_thanks_contact_information_1');
                    $sort_array["acf_thanks_text_2"] = get_field('acf_thanks_text_2');
                    $sort_array["acf_thanks_contact_information_2"] = get_field('acf_thanks_contact_information_2');
                    $sort_array["acf_thanks_notes"] = get_field('acf_thanks_notes');
                    $sort_array["acf_spirit_note_2"] = get_field('acf_spirit_note_2');

                    $sort_array["acf_thanks_text_left_1"] = get_field('acf_thanks_text_left_1');
                    $sort_array["acf_thanks_text_left_2"] = get_field('acf_thanks_text_left_2');
                   

                    break;
                }
               
            endwhile;
        endif;


        return $sort_array;

	}


    /****************************************************
	** サンクスページの新規登録
	******************************************************/
	public function newThanksCustomizeData( $type_id  )
	{
		$wp_query = new WP_Query();

        $user = wp_get_current_user();

        $my_post = array(
            'post_title' => get_field('acf_pure_spirit_title',$type_id),
            'post_type' => 'cpt_thanks_text', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );

        $program_id = wp_insert_post($my_post);


        //IDは保存
        if ($program_id) {
            update_field(  "acf_thanks_spirit_type" , $type_id , $program_id);

        }

        //詳細用のIDを作成
        $detail_post = array(
            'post_title' => get_field('acf_pure_spirit_title',$type_id),
            'post_type' => 'cpt_thanks_detail', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );

        $detail_id = wp_insert_post($detail_post);

        if ($program_id) {
            update_field(  "acf_thanks_photo_text_id" , $detail_id , $program_id);

        }

     //   return false;

	}

    /****************************************************
	** サンクスメールが連動しているかどうかの確認
	******************************************************/
	public function getIdThanksMail( $type_id  )
	{
        $wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_confirmationmail', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );


         $wp_query->query($param);


        //データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                $type = get_field('acf_ConfirmationMail_type_id');


                if( $type == $type_id )
                {
                    return get_the_ID();
                }
               
            endwhile;
        endif;


        //新しく作成する
        $user = wp_get_current_user();

        $my_post = array(
            'post_title' => get_field('acf_pure_spirit_title',$type_id),
            'post_type' => 'cpt_confirmationmail', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );

        $program_id = wp_insert_post($my_post);

        if ($program_id) {

            $base_title = get_field('acf_pure_spirit_disp_title',$type_id);

            if($base_title == "")
            {
                $base_title = get_field('acf_pure_spirit_title',$type_id);
            }

            $title = "【".  get_field('acf_pure_spirit_title',$type_id)  ."】フォームのご入力をありがとうございます！";

            update_field(  "acf_ConfirmationMail_type_id" , $type_id , $program_id);
            update_field(  "acf_ConfirmationMail_title" , $title , $program_id);
           

        }



        return "";







    }









     /****************************************************
	 **  入力フォームの編集
	 ******************************************************/
	public function saveThanksCustomizeData( $id ,$post_data)
	{
        
        //fieldを全取得
        $group_id = '347';
        $fields = acf_get_fields($group_id);

        foreach ($fields as $field => $data) {

            if(isset( $post_data[ $data["name"] ] ))
            {
                update_field(  $data["name"] , $post_data[ $data["name"] ] , $id);
            }

        }    
    }


    
    /****************************************************
	 **  個人情報入力取得
	 ******************************************************/
	public function getPersonalDataInput(  )
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_input_personal', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);



        $group_id = '475';
        $fields = acf_get_fields($group_id);
		
        //ソート用
        $sort_array = "";

		//データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                if ($sort_array == "") {
                    $sort_array = array();
                }

                $num = get_field('acf_input_personal_data_sort_number');


                if( !isset($sort_array[$num]))
                {
                    $sort_array[$num] = array();
                }
                
               
                $sort_array[$num]["ID"] = get_the_ID();


                 foreach ($fields as $field => $data) {

                    if(!isset(  $sort_array[$num][ $data["name"] ] ))
                    {
                        $sort_array[$num][ $data["name"] ] = get_field( $data["name"] );
                    }

                }    
               
            endwhile;
        endif;


        if(!empty($sort_array))
        {
            ksort($sort_array);
        }

        //var_dump($sort_array);
        
        return $sort_array;

	}

   

    /****************************************************
	**  個人情報入力の新規登録
	******************************************************/
	public function newPersonalDataInput( $title ,  $inptut_type  , $form_input , $target )
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_input_personal', //カスタム投稿タイプの名称を入れる
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

                $num = get_field('acf_input_personal_data_sort_number');

                if( $count < $num)
                {
                    $count = $num;
                }

            endwhile;
        endif;


        $user = wp_get_current_user();

        $my_post = array(
            'post_title' => $title,
            'post_type' => 'cpt_input_personal', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );

        $program_id = wp_insert_post($my_post);


        if ($program_id) {
          
            update_field("acf_input_personal_data_sort_number", $count + 1, $program_id);
            update_field("acf_input_personal_data_disp", $form_input, $program_id);
            update_field("acf_input_personal_data_title",$title, $program_id);
            update_field("acf_input_personal_data_disp_type",$inptut_type, $program_id);
            update_field("acf_input_target",$target, $program_id);
        }

     //   return false;

	}


     /****************************************************
	 **  個人情報入力の編集
	 ******************************************************/
	public function savePersonalDataInput( $id ,$inptut_type, $inputText, $form_input , $target )
	{
        
         update_field("acf_input_personal_data_title", $inputText, $id);

        $required = "";
      
        update_field("acf_input_personal_data_disp", $form_input, $id);
        update_field("acf_input_personal_data_disp_type",$inptut_type, $id);
        update_field("acf_input_target",$target, $id);

        //タイトル更新
        $post = array();
        $post["ID"] = $id;
        $post["post_title"] = $inputText;
        wp_update_post( $post );
    }

     /****************************************************
	 **  個人情報入力を更新
	 ******************************************************/
	public function sortPersonalDataInput( $sortArray )
	{
         foreach ($sortArray as $key => $value) {
               update_field("acf_input_personal_data_sort_number", $key + 1, $value);
         }

    }

    
    /****************************************************
	 **  個人情報入力を削除
	 ******************************************************/
	public function deletePersonalDataInput( $id )
	{
        wp_delete_post($id, true);

    }


    /****************************************************
	 **  返信メール情報の取得
	 ******************************************************/
	public function getConfirmationMailID( $type_id )
	{
        $wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_confirmationmail', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        //データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                
                $type = get_field('acf_ConfirmationMail_type_id');

                //浄霊・鑑定タイプ
                if($type == $type_id)
                {
                    return get_the_ID();
                }
                
               
            endwhile;
        endif;


        $user = wp_get_current_user();

        //ここまで来てるという事は新規作成になる
        $my_post = array(
            'post_title' => get_field('acf_pure_spirit_title',$type_id),
            'post_type' => 'cpt_confirmationmail', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );


        $program_id = wp_insert_post($my_post);

        if ($program_id) {
          
            update_field("acf_ConfirmationMail_type_id", $type_id, $program_id);
        }

        return $program_id;

    }
}









?>