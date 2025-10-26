
<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritInputCustomizeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritMailPostClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");

    $spirit_customize_data = new SpiritInputCustomizeClass(); //管理データ
    $spirit_sheet_data = new spiritSheetClass(); //質問データ
    $mail_post_data = new SpiritMailPostClass(); //管理データ


    $question_num = 0;

    if(isset($_GET["type_id"]))
    {
        $question_num = $_GET["type_id"];
    }
    else if(isset($_POST["type_id"]))
    {
        $question_num = $_POST["type_id"];
    }

    //サンクスフォーム情報(文章)
    $customize_data = $spirit_customize_data->getThanksCustomizeData( $question_num );

     //入力フォーム情報(文章)
    $customize_input_data = $spirit_customize_data->getInputCustomizeData( $question_num );

     //質問情報
    $spiritSheetArray = $spirit_sheet_data->getSpiritQuestion($question_num);
    //個人情報の項目
    $personal_input_array = $spirit_customize_data->getPersonalDataInput( $question_num );

  



    $post_contens = get_post_field('post_content', $customize_data["acf_thanks_photo_text_id"]);
   

    

    //登録とメールの作成
    if(isset($_POST["unixtime"])) //UNIXtimeあり
    {
          $registered = "";

          if(isset($_POST["rgst"]))
          {
               $registered = $_POST["rgst"];
          }

          // $res['application_user'] 申込者ID
          // $res['target_user'] 対象者ID
         $res = makeRegistFormData($_POST);
        //echo "<br>登録データ<br>";
        // var_dump($res);  //削除okd
        //削除okd
        if(get_current_user_id() == 1){
            echo "ID１のみ表示";
            echo "<pre>";
            var_dump($res);  //削除okd
            echo "</pre>";

            // var_dump($_POST);
        }

         $input_pattern = 0;


         //対象者がある
         if(isset( $res['target_user']) )
         {
             $input_pattern = 1;
         }
         //一斉浄霊系
         else if(isset( $res['remote_user']) )
         {
             $input_pattern = 2;
         }

         $applicant_user = "";


        if($question_num != 77) //リモート浄霊以外
        {

            foreach ($res as $res_key => $res_value) {


                //対象者のみシートを作成
                if( $res_key == "application_user")
                {
                    $applicant_user = $res_value[0];
                }

            
                //対象者のみシートを作成
                if($input_pattern == 1 && $res_key == "application_user")
                {
                    continue;
                }
        

                foreach ($res_value as $id_key => $id_value) 
                {

                    $user_id = $id_value;


                    //シートを作成し、登録する

                    //シートの作成を３パターンで考える
                    //申込者のみ→申込者だけシートを作成、質問も申込者に入力
                    //申込者と対象者が違う→対象者にシートを作成、質問hは対象者に入力、申込者のIDを入力
                    //一斉浄霊→申込者と対象者にシートを作成、質問がある場合は申込者に入力、対象者の申込者のIDを入力

                
                    //質問sheetの作成
                    $add_id = $spirit_sheet_data->newSpiritSheetUnixtime($user_id,$question_num ,$_POST["unixtime"]);
   
        
                    if($add_id != "")
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
        


                    //対象者のみシートを作成
                    if($input_pattern != 0)
                    {
                        //申込者を設定
                        update_field("acf_applicant", $res['application_user'], $add_id);
                    }
       


                    //質問を登録する
                    $anser_number = array();

                    foreach ($spiritSheetArray[$question_num] as $key => $value) {

                        if(isset($_POST["question_" .$value["ID"]]))
                        {
                            //新規保存（ある場合は上書き）
                            $question_number = $spirit_sheet_data->saveQuestionAnser(  $user_id , $add_id , $value["ID"] , $value["type"] , $_POST["question_" .$value["ID"]] );


                            $anser_number[$value["ID"]] = $question_number;

                        }

                    }

                    //質問番号を上書き
                    $spirit_sheet_data->setSpiritSheetAnswer( $add_id , $anser_number);
        
                
        
                }
            }

        }
        else  if($question_num == 77){//リモート浄霊は別の登録を行う

            //リモート浄霊の仮登録を作成

            date_default_timezone_set('Asia/Tokyo');

             $applicant_user =$res['application_user'][0];

            //最大10人
            $remonte_array = array();

            //申込者を入れる
            $remonte_array["acf_remote_sprit_sheet_applicant_id"] = $res['application_user'][0];

            //ユニックスタイムを入れる
            $remonte_array["acf_remote_sprit_sheet_save_unixtime"] = $_POST['unixtime'];

            //入力日は今
            $remonte_array["acf_remote_sprit_sheet_target_input_date"] = date("Y/m/d");

            

            //リモート浄霊の質問番号は587
            $remote_question = 587;


            $user_count = 0;

            //対象者を入れる
            for($i=1;$i<=10;$i++)
            {
                if(!isset( $_POST["question_".$remote_question ."_" .$i ."_parents"]))
                {
                    continue;
                }

                $target_array = array();

                $remonte_array["acf_remote_sprit_sheet_last_name_" . $i] = $_POST["question_".$remote_question ."_" .$i ."_sei"];//対象者の名前
                $remonte_array["acf_remote_sprit_sheet_last_name_kana_" . $i] =  $_POST["question_".$remote_question ."_" .$i ."_sei_kana"];//対象者の名前
                $remonte_array["acf_remote_sprit_sheet_first_name_" . $i] =  $_POST["question_".$remote_question ."_" .$i ."_mei"];//対象者の名前
                $remonte_array["acf_remote_sprit_sheet_first_name_kana_" . $i] =  $_POST["question_".$remote_question ."_" .$i ."_mei_kana"];//対象者の名前


                $remonte_array["acf_remote_sprit_sheet_target_relationship_" . $i] = $_POST["question_".$remote_question ."_" .$i ."_parents"];//対象者との関係
                $remonte_array["acf_remote_sprit_sheet_target_birthday_" . $i] = $_POST["question_".$remote_question ."_" .$i ."_date"];//対象者の誕生日
                $remonte_array["acf_remote_sprit_sheet_target_img_" . $i] = $_POST["img_url"][$i -1];//対象者のURL
                $remonte_array["acf_remote_sprit_sheet_target_img_path_" . $i] = $_POST["img_path"][$i -1];//対象者のPATH
                $remonte_array["acf_remote_sprit_sheet_target_img_name_" . $i] = $_POST["img_file"][$i -1];//対象者のP画像名

               // $remonte_array[$i] = $target_array;

                $user_count++;

            }

            //枠数
            $remonte_array["acf_remote_sprit_sheet_target_slots"] = $user_count;
            
            $spirit_sheet_data->newRemoteSpritTentative($remonte_array);

            //var_dump($remonte_array);
        }


        //確認メール（申込者のみ）
        $confirmation_mail = $mail_post_data->setUserConfirmationMail( $applicant_user,$question_num , $personal_input_array, $customize_input_data["acf_spirit_note_personal_table"], $spiritSheetArray[$question_num] ,$registered);

        //サンキューメール（申込者のみ）
        $confirmationmail_id = $mail_post_data->setUserThanksMail( $applicant_user,$question_num  ); //サンキューメール


}


$user_id = get_current_user_id();



//返信メールの確認
// 


// echo $post_contens;
// echo "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
// var_dump($_POST);

// getPersonalDataInputData($user_id,$_POST);
?>


<?php
            
    $title = get_field('acf_pure_spirit_disp_title',$question_num); 

    if($title == "")
    {
        $title =  get_field('acf_pure_spirit_title',$question_num); 
    }
            
?>


<div class="input-form-area">


    <div class="input-form-contens">


         <div class="input-form-header-img">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hituyou-thanks.png" alt="Image 1" style="width: 100%;">
         </div>


         <div class="input-form-main">


             <div class="input-form-title">
                 <div class="input-form-title-str">【　<?php echo $title; ?>　】</div>
             </div>


             <?php if($customize_data["acf_thanks_spirit_start_text"] !=  ""){ //開始文 ?>

                <div class="input-form-text-box">

                     <div class="input-form-text"><?php echo nl2br($spirit_customize_data->changeInputCustomizeData( $customize_data["acf_thanks_spirit_start_text"]));?></div>

                </div>


             <?php } ?>



             <div class="input-form-id-box">

                 <div class="input-form-id-flex">
                    <div class="input-form-id-text">
                        お客様のIDは
                    </div>
                    <div class="input-form-id">
                       <?php echo  get_user_meta($applicant_user,'user_unique_id',true);?>
                    </div>
                     <div class="input-form-id-text">
                        となります。
                    </div>
                 </div>

                 <div class="input-form-id-add">
                    次回以降はこちらのIDとご入力されたメールアドレスで情報等が省略されますので、ご使用ください。<br>
                    (すでに登録されているお客様は同じＩＤとなります)

                 </div>


              </div>


             <?php if($post_contens !=  ""){ //～〇〇に必要な写真 ?>


                <?php 
                
                     $post_contens = wpautop($post_contens);

	                 // 許可するHTML要素と属性を指定
	                $allowed_tags = wp_kses_allowed_html('post');
	                $allowed_tags['img'] = array(
		                'src' => true,
		                'alt' => true,
		                'title' => true,
		                'width' => true,
		                'height' => true,
	                );
                
                
                ?>


                <div class="input-form-flow-box">


                    <div class="input-form-flow-title">
                         <div class="input-form-flow-title-str"><?php echo $title; ?>に必要な写真</div>
                    </div>

                     <div class="input-form-flow-text"><?php echo  wp_kses($post_contens, $allowed_tags);?></div>

                </div>


             <?php } ?>


              <?php if($customize_data["acf_thanks_photo_text"] !=  ""){ //～〇〇に必要な写真(詳細) ?>

                <div class="input-form-flow-box">


                    <div class="input-form-flow-title">
                         <div class="input-form-flow-title-str"><?php echo $title; ?>に必要な写真</div>
                    </div>

                     <div class="input-form-flow-text"><?php echo nl2br($spirit_customize_data->changeInputCustomizeData( $customize_data["acf_thanks_photo_text"]));?></div>

                </div>


             <?php } ?>



             <?php if($customize_data["acf_thanks_text_1"] !=  ""){ //テキスト差し込み1 ?>

                <div class="input-form-text-box">

                     <div class="input-form-text" <?php if($customize_data["acf_thanks_text_left_1"] !=  ""){?> style="text-align: left;" <?php } ?>><?php echo nl2br($spirit_customize_data->changeInputCustomizeData( $customize_data["acf_thanks_text_1"]));?></div>

                </div>


             <?php } ?>


             <?php if($customize_data["acf_thanks_contact_information_1"] !=  ""){ //お問い合わせメール１ ?>


               

                <div class="input-form-text-box">

                     <div class="input-form-contact-str">■お問い合わせ先■</div>

                     <div class="input-form-text"><?php echo nl2br($spirit_customize_data->changeInputCustomizeData( $customize_data["acf_thanks_contact_information_1"]));?></div>

                </div>


             <?php } ?>


              <?php if($customize_data["acf_thanks_notes"] !=  ""){ //注意事項 ?>

                <div class="input-form-flow-box">


                    <div class="input-form-flow-title">
                         <div class="input-form-flow-title-str">注意事項</div>
                    </div>

                     <div class="input-form-flow-text"><?php echo nl2br($spirit_customize_data->changeInputCustomizeData( $customize_data["acf_thanks_notes"]));?></div>

                </div>


             <?php } ?>


             <?php if($customize_data["acf_thanks_text_2"] !=  ""){ //テキスト差し込み2 ?>

                <div class="input-form-text-box">

                     <div class="input-form-text" <?php if($customize_data["acf_thanks_text_left_2"] !=  ""){?> style="text-align: left;" <?php } ?>><?php echo nl2br($spirit_customize_data->changeInputCustomizeData( $customize_data["acf_thanks_text_2"]));?></div>

                </div>


             <?php } ?>


             <?php if($customize_data["acf_thanks_contact_information_2"] !=  ""){ //お問い合わせメール2 ?>


               

                <div class="input-form-text-box">

                     <div class="input-form-contact-str">■お問い合わせ先■</div>

                     <div class="input-form-text"><?php echo nl2br($spirit_customize_data->changeInputCustomizeData( $customize_data["acf_thanks_contact_information_2"]));?></div>

                </div>


             <?php } ?>


            
          </div>

    </div>

</div>


