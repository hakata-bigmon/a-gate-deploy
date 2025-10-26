<?php 


class SpiritContensQuestionClass
{
   
    /****************************************************
	 **  質問作成
	 ******************************************************/
	public function createQuestion($post_data){



        //acf_contens_question_titleを使用して同じタイトルがある場合は作成しない
        $check_title = get_posts(array(
            'post_type' => 'cpt_contact_question',
            'meta_query' => array(
                array(
                    'key' => 'acf_contens_question_title',
                    'value' => $post_data["acf_contens_question_title"],
                )
            )
        )); 

        //var_dump($check_title);

        if(count($check_title) > 0){
            return "";
        }

        $user = wp_get_current_user();

        $my_post = array(
            'post_title' => $post_data["acf_contens_question_title"],
            'post_type' => 'cpt_contact_question', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );

        $program_id = wp_insert_post($my_post);

        if ($program_id) {
            
            update_field("acf_contens_question_title", $post_data["acf_contens_question_title"], $program_id);//タイトル
            update_field("acf_contens_question_result", $post_data["acf_contens_question_result"], $program_id);//結果
            update_field("acf_contens_question_category", $post_data["acf_contens_question_category"], $program_id);//カテゴリー
            update_field("acf_contens_question_sort", "", $program_id);//並び順
        
        }

        return $program_id;
    }

    /****************************************************
	 **  質問保存
	 ******************************************************/
	public function saveQuestion($question_id, $post_data){


        //タイトルも変更する
        wp_update_post(array(
            'ID' => $question_id,
            'post_title' => $post_data["acf_contens_question_title"],
        ));

        update_field("acf_contens_question_title", $post_data["acf_contens_question_title"], $question_id);//タイトル
        update_field("acf_contens_question_result", $post_data["acf_contens_question_result"], $question_id);//結果
        update_field("acf_contens_question_category", $post_data["acf_contens_question_category"], $question_id);//カテゴリー

    }

    /****************************************************
	 **  質問一覧
	 ******************************************************/
	public function getQuestionList(){

       
        $wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_contact_question', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        $question_list = array();
        $post_list = array();
        $count = 0;

        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                if( get_field('acf_contens_question_sort') != ""){
                    $question_list[ get_field('acf_contens_question_sort') ] = get_the_ID();
                }
                else{
                    $question_list[ $count + 100 ] = get_the_ID();
                    $count++;
                }
               
            endwhile;
        endif;


        //キーでソート
        ksort($question_list);

        return $question_list;


    }
    /****************************************************
    **  質問登録
    ******************************************************/
    public function saveContacts($user_id,$post_data){

        //cpt_contactsのacf_contacts_unixtimeに同じものがあるかチェック
        $check_unixtime = get_posts(array(
            'post_type' => 'cpt_contacts',
            'meta_query' => array(
                array(
                    'key' => 'acf_contacts_unixtime',
                    'value' => $post_data["acf_contacts_unixtime"],
                )
            )
        ));

        if(count($check_unixtime) > 0){
            return "";
        }

        $user_data = get_user_by('ID', $user_id);

        $title = $post_data["acf_contacts_title"] . "|" . $user_data->display_name;

        $my_post = array(
            'post_title' => $title,
            'post_type' => 'cpt_contacts', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user_id,
        );

        $program_id = wp_insert_post($my_post); 

        if ($program_id) {
            update_field("acf_contacts_title", $post_data["acf_contacts_title"], $program_id);//タイトル
            update_field("acf_contacts_txt", $post_data["acf_contacts_txt"], $program_id);//問い合わせ内容
            update_field("acf_contacts_unixtime", $post_data["acf_contacts_unixtime"], $program_id);//unixtime

            //今日の日付（東京）
            $today = new DateTime('Asia/Tokyo');
            update_field("acf_contacts_date", $today->format('Y-m-d'), $program_id);//日付
           
            update_field("acf_contacts_user", $user_id, $program_id);//ユーザーID
            
            //
            if(isset($post_data["acf_contacts_parent_number"])){
                update_field("acf_contacts_parent_number", $post_data["acf_contacts_parent_number"], $program_id);//親番号
            }

            if(isset($post_data["acf_contacts_return_number"])){
                update_field("acf_contacts_return_number", $post_data["acf_contacts_return_number"], $program_id);//返信番号
            }

            
        }

        return $program_id;
    }


    /****************************************************
    **  質問編集
    ******************************************************/
    public function editContacts($user_id,$contact_id,$post_data){


        update_field("acf_contacts_title", $post_data["acf_contacts_title"], $contact_id);//タイトル
        update_field("acf_contacts_txt", $post_data["acf_contacts_txt"], $contact_id);//問い合わせ内容

        //今日の日付（東京）
        $today = new DateTime('Asia/Tokyo');
        update_field("acf_contacts_date", $today->format('Y-m-d'), $contact_id);//日付

    }

    /****************************************************
	 **  お問い合わせ一覧
	 ******************************************************/
	public function getContactsList($user_id){


        $wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_contacts', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        $contacts_list = array();

        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                if($user_id == get_field("acf_contacts_user",get_the_ID()) ||$user_id == "" ){

                    if(get_field("acf_contacts_parent_number",get_the_ID()) == ""){
                        $contacts_list[get_the_ID()] = get_the_ID();
                    }
                }

               

            endwhile;
        endif;
        
    
        return $contacts_list;  
    }



    /****************************************************
	 **  お問い合わせ詳細
	 ******************************************************/
	public function getContactsDetail($contact_id){


        $contact_data = array();


        $contact_data["ID"] = $contact_id;

        $contact_data["件名"] = get_field("acf_contacts_title",$contact_id);

        $contact_data["質問日付"] = get_field("acf_contacts_date",$contact_id);

        if($contact_data["質問日付"] != ""){
            $contact_data["質問日付年月日"] = date("Y年m月d日",strtotime($contact_data["質問日付"]));
        }

        $contact_data["質問内容"] = get_field("acf_contacts_txt",$contact_id);

        $contact_data["問い合わせ番号"]  = get_field("acf_contacts_unixtime",$contact_id)  . get_field("acf_contacts_user",$contact_id);

        $contact_data["質問者"] = get_field("acf_contacts_user",$contact_id);

        $contact_data["書き込み終了"] = get_field("acf_contacts_end",$contact_id);

        $contact_data["親番号"] = get_field("acf_contacts_parent_number",$contact_id);

        $contact_data["返信番号"] = get_field("acf_contacts_return_number",$contact_id);



        $parent_number = $contact_data["親番号"];

        if($parent_number == "")
        {
            $parent_number = $contact_id; //親
        }

        $contact_data["返信"] = $this->getContactsParentNumber($parent_number);

        //一番最新の質問日付を取得

        $last_date = $contact_data["質問日付"];

        if(count($contact_data["返信"]) > 0){
            foreach($contact_data["返信"] as $key => $value){
                $contact_last_date = get_field("acf_contacts_date",$value);

                //$last_dateと比べて、$contact_last_dateが新しい場合は$last_dateを更新
                if($contact_last_date != "" && strtotime($contact_last_date) > strtotime($last_date)){
                    $last_date = $contact_last_date;
                }
            }
            $contact_data["返信日付"] = $last_date;
            $contact_data["返信日付年月日"] = date("Y年m月d日",strtotime($last_date));
        }

        $contact_data["返信日付"] = $last_date;
        $contact_data["返信日付年月日"] = date("Y年m月d日",strtotime($last_date));


        $contact_data["管理者確認"] = get_field("acf_contacts_admin_check",$contact_id);
        $contact_data["質問者確認"] = get_field("acf_contacts_user_check",$contact_id);

        return $contact_data;


    }


    /****************************************************
	 **  お問い合わせの親番号の配列を取得
	 ******************************************************/
	public function getContactsParentNumber($parent_id){


        //echo $parent_id;
        $check_parent_number = get_posts(array(
            'post_type' => 'cpt_contacts',
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'ASC',
            'meta_query' => array(
                
                array(
                    'key' => 'acf_contacts_parent_number',
                    'value' => $parent_id,
                )
            )
        ));

        $parent_number_list = array();

       // var_dump($check_parent_number);

        if (count($check_parent_number) > 0):

            foreach($check_parent_number as $key => $value){

                $parent_number_list[$value->ID] = $value->ID;

            }
        endif;

        return $parent_number_list;
    }

    /****************************************************
	 **  お問い合わせで未回答の質問を取得
	 ******************************************************/
	public function getContactsUnansweredQuestion(){

        //親のみ取得
        $parent_number_list = $this->getContactsList("");

        $no_end_list = array();


        foreach($parent_number_list as $key => $value){

            $last_id = $key;

            $parent_number = get_field("acf_contacts_parent_number",$key);

            if($parent_number == "")
            {
                $parent_number = $key; //親
            }

            //返信番号を取得
            $contact_data = $this->getContactsParentNumber($parent_number);

            //一番最新の質問日付を取得
            $end_flag = false;

            $last_date =  get_field("acf_contacts_date",$key);

            if(count($contact_data) > 0){
                //返信から終了フラグが立っていないものを調べる
                foreach($contact_data as $rkey => $rvalue){
                    if(get_field("acf_contacts_end",$rvalue) != ""){
                        $end_flag = true;
                        break;
                    }
                }

                //最後のIDを取得
                $contact_last_date = get_field("acf_contacts_date",$rvalue);

                //$last_dateと比べて、$contact_last_dateが新しい場合は$last_dateを更新
                if($contact_last_date != "" && strtotime($contact_last_date) > strtotime($last_date)){
                    $last_date = $contact_last_date;
                    $last_id = $rvalue; //最後のID
                }
            }

            if($end_flag){
               continue;
            }
            else{


                //最後のIDの作成者が管理者の場合は入れない
                $user_data = get_user_by('ID', get_field("acf_contacts_user",$last_id));
                if($user_data->roles[0] == "administrator"){
                    continue;
                }


            }

            //ここまで来たら入れる
            $no_end_list[$key] = $value;
        }
            
       
        return $no_end_list;

    }

    /****************************************************
    **  カテゴリー一覧
    ******************************************************/
    public function getCategoryList($is_sort = false){


        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_question_categoy', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );


        $wp_query = new WP_Query();

        $wp_query->query($param);

        $category_list = array();

       // var_dump($category_list);


       $count = 0;

       if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                if(!$is_sort){
                    $category_list[get_the_ID()] = get_field("acf_question_category_name");
                }
                else{

                    $sort_number = get_field("acf_question_category_sort");

                    if($sort_number == ""){
                        $category_list[ $count + 100] = get_the_ID();
                        $count++;
                    }
                    else{
                        $category_list[$sort_number] = get_the_ID();
                    }
                }

            endwhile;
        endif;
        
        if($is_sort)
        {
            //キーでソート
            ksort($category_list);
        }

        return $category_list;

    }

    /****************************************************
    **  よくある質問カテゴリー作成
    ******************************************************/
    public function createCategory($post_data){


        //同じacf_question_category_save_unix_timeがあるなら保存しない
        $check_save_unix_time = get_posts(array(
            'post_type' => 'cpt_question_categoy',
            'meta_query' => array(
                array(
                    'key' => 'acf_question_category_save_unix_time',
                    'value' => $post_data["acf_question_category_save_unix_time"],
                )   
            )
        ));

        if(count($check_save_unix_time) > 0){
            return "";
        }

        $my_post = array(
            'post_title' => $post_data["acf_question_category_name"],
            'post_type' => 'cpt_question_categoy',
            'post_status' => 'publish',
        );

        $program_id = wp_insert_post($my_post);


        if ($program_id) {
            update_field("acf_question_category_name", $post_data["acf_question_category_name"], $program_id);//カテゴリー名
            update_field("acf_question_category_save_unix_time", $post_data["acf_question_category_save_unix_time"], $program_id);//保存日時
        }

        return $program_id;
    }

    /****************************************************
    **  よくある質問カテゴリー編集
    ******************************************************/
    public function editCategory($category_id,$post_data){

        update_field("acf_question_category_name", $post_data["acf_question_category_name"], $category_id);//カテゴリー名

        //タイトル変更
        wp_update_post(array(
            'ID' => $category_id,
            'post_title' => $post_data["acf_question_category_name"],
        ));

        return $category_id;

    }

    /****************************************************
    **  よくある質問カテゴリー削除
    ******************************************************/
    public function deleteCategory($category_id){

        wp_delete_post($category_id);

    }

}










?>