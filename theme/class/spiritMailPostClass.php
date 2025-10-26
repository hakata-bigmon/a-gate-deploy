<?php 


class SpiritMailPostClass
{
	
	/****************************************************
	 **  登録時の確認メール
	 ******************************************************/
	public function sendMail($mail_address,$title,$mail_text)
	{
		
		 //$user = get_userdata($user_id);

		mb_language("Japanese");
        mb_internal_encoding("UTF-8");


		$mail_title = $title;

        $headers  = 'From:info@a-gate-kanri.com' . "\r\n";
        $mail_to = $mail_address;
        $mail_subject = $mail_title;


		 //送信元
        $mail_from =  "a-gate";

        // 送信元メールアドレス
        $from_mail = "info@a-gate-kanri.com";

        // 送信者名
        $from_name = "株式会社A-GATE（エーゲート）";
        //$bcc_mail = "support@vintagecardjapan.com";

        $headers .= "BCC: info@a-gate-kanri.com\r\n";


		$res = mb_send_mail( $mail_to, $mail_subject, $mail_text ,$headers );
	}


	/****************************************************
	 **  登録時のThanksメール
	 ******************************************************/
	public function setUserThanksMail($user_id,$type_id)
	{

        //echo $user_id . "<br>";
        //echo $type_id . "<br>";



		$wp_query = new WP_Query();

        $user = get_userdata($user_id);

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_confirmationmail', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

		if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                
                $type = get_field('acf_ConfirmationMail_type_id');
                
               
                if($type == $type_id)
                {
                    //開始直後の文章
                    $post_contens = get_field('acf_ConfirmationMail_start', get_the_ID());


                    //ユーザーID追加
                    $post_contens .= "\r\nお客様のご登録IDは" .get_user_meta($user_id,'user_unique_id',true) ."となります。\r\n次回以降のご入力の際はこちらのIDと今回、ご使用になられたメールアドレスで情報が省略されますので、ご利用ください。\r\n";


                    $post_contens .= get_post_field('post_content', get_the_ID());


                    // ２，HTMLタグをすべて取り除く
                    $post_contens = wp_strip_all_tags( $post_contens );

                    // ３．ショートコードを取り除く
                    $post_contens = strip_shortcodes( $post_contens );

                    //改行コードを変換
                    $post_contens = str_replace('&nbsp;', "\r\n\r\n", $post_contens);
                    

                    //送付先情報を差し替える
                    if(strpos($post_contens, '【送付先】') > 0)
                    {
                        $message = "";

                        $message .= "郵便番号：" .get_user_meta($user_id,'billing_postcode',true) ."\r\n\r\n";
                        $message .= "ご住所：" .get_user_meta($user_id,'billing_city',true) .get_user_meta($user_id,'billing_address_1',true) ."\r\n\r\n";
                        $message .= "お名前：" .get_user_meta($user_id,'last_name',true) ."　" .get_user_meta($user_id,'first_name',true) ."\r\n\r\n";
                        $message .= "フリガナ：" .get_user_meta($user_id,'last_name_kana',true)."　" .get_user_meta($user_id,'first_name_kana',true) ."\r\n\r\n";
                        $message .= "メールアドレス：" .$user->user_email ."\r\n\r\n";
                        $message .= "電話番号：" .get_user_meta($user_id,'billing_phone',true) ."-" .get_user_meta($user_id,'billing_phone2',true) ."-" .get_user_meta($user_id,'billing_phone3',true) ."\r\n\r\n";

                        $post_contens = str_replace('【送付先】', $message, $post_contens);
                    }



                    $post_contens = $user->last_name ." 様\r\n\n\n" . $post_contens;


                    //フッターを調べる
                    if(get_post_field('acf_ConfirmationMail_isfooter', get_the_ID()) != 0)
                    {
                         $post_contens .= "\r\n\n\n" . get_field('acf_ConfirmationMail_footer');
                    }
                    else{
                        //共通
                        $footer_contens = get_post_field('post_content', 669);


                        // ２，HTMLタグをすべて取り除く
                        $footer_contens = wp_strip_all_tags( $footer_contens );

                        // ３．ショートコードを取り除く
                        $footer_contens = strip_shortcodes( $footer_contens );


                        $post_contens .= "\r\n\n\n" . $footer_contens;
                    }

                    //echo $post_contens;

                    

                    $this->sendMail($user->user_email,get_field('acf_ConfirmationMail_title'),$post_contens);

                    return;
                }
               
            endwhile;
        endif;


        //return $sort_array;

	//sendMail($mail_address,$title,$mail_text)
	}
   /****************************************************
	 **  確認メール
	 ******************************************************/
	public function setUserConfirmationMail($user_id,$type_id,$personal_input_array,$personal_disp,$spiritsheetarray, $registered_id)
	{
        require_once (dirname(__FILE__)."/../custompage/front/order-form-disp-function.php");


         $user = get_userdata($user_id);


         $base_title = get_field('acf_pure_spirit_disp_title',$type_id);

        if($base_title == "")
        {
            $base_title = get_field('acf_pure_spirit_title',$type_id);
        }

        $title = "【" . $base_title ."】ご入力確認";

        $message = "";

        //個人情報の作成
        foreach ($personal_input_array as $key => $value) {

            if(!isset($personal_disp[ $value["ID"] ]) ){continue;}   //個人情報の表示ON・OFF
            if($personal_disp[ $value["ID"] ][0] == ""){continue;}   //個人情報の表示ON・OFF
            if($value["acf_input_target"] != ""  ){continue;} 
            if($registered_id != "" && $personal_disp[ $value["ID"] ][2] == ""){continue;}


            $message .= "■" .$value["acf_input_personal_data_title"] ."\r\n\r\n";

            $message .= getPersonalDataInputData( $value["ID"] , $_POST , $registered_id) ."\r\n\r\n";

        }


         //個人情報の作成
        foreach ($personal_input_array as $key => $value) {

            if(!isset($personal_disp[ $value["ID"]] ) ){continue;}
            if($personal_disp[ $value["ID"] ][0] == ""){continue;}
            if($value["acf_input_target"] == "" ){continue;}
            if( !isset($_POST["input-applicant-check"] ) ){continue;}
            if( $_POST["input-applicant-check"]  != 1 ){continue;} 


            $message .= "■" .$value["acf_input_personal_data_title"] ."\r\n\r\n";

            $message .= getPersonalDataInputData( $value["ID"] , $_POST , $registered_id) ."\r\n\r\n";

             

        }



        foreach ($spiritsheetarray as $key => $value) {

            if($value["type"] != 10)
            {

                $message .=  "■" .$value["text"]."\r\n\r\n";

                $message .=  getQuestionInputData( $value["ID"] ,$_POST ,$value["type"] , $value["add"] , "" )."\r\n\r\n";
            }
            else if($value["type"] == 10){ //対象者追加（一斉浄霊のみ）*特殊

                for($i=1;$i<10;$i++)
                {
                    if( isset($_POST["question_".$value["ID"] . "_" .$i ."_sei"]) )
                    {

                         $message .=  "■対象者" .$i ."の名前\r\n\r\n";

                         $message .=  $_POST["question_" .$value["ID"] . "_" .$i ."_sei" ] . " " .$_POST["question_" .$value["ID"] . "_" .$i ."_mei" ]."\r\n\r\n";


                         $message .=  "■対象者" .$i ."の名前(フリガナ)\r\n\r\n";

                         $message .=  $_POST["question_" .$value["ID"] . "_" .$i ."_sei_kana" ] . " " .$_POST["question_" .$value["ID"] . "_" .$i ."_mei_kana" ]."\r\n\r\n";

                         $message .=  "■対象者" .$i ."と申込者の関係\r\n\r\n";

                         $message .= $_POST["question_" .$value["ID"] . "_" .$i ."_parents" ]."\r\n\r\n";


                         $message .=  "■対象者" .$i ."の誕生日\r\n\r\n";

                         $message .= $_POST["question_" .$value["ID"] . "_" .$i ."_date" ]."\r\n\r\n";

                         $message .=  "■対象者" .$i ."の画像名\r\n\r\n";

                         $message .= $_POST["img_file_user" ][$i - 1]."\r\n\r\n";


                    }

                }



            }
        }


        //共通
        $footer_contens = get_post_field('post_content', 670);


        // ２，HTMLタグをすべて取り除く
        $footer_contens = wp_strip_all_tags( $footer_contens );

        // ３．ショートコードを取り除く
        $footer_contens = strip_shortcodes( $footer_contens );


        $message .= "\r\n\n\n" . $footer_contens;


        $this->sendMail($user->user_email,$title,$message);

        //echo $message;
    }
}









?>