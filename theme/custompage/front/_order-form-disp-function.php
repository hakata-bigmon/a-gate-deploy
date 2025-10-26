<?php 


    /****************************************************************************************************
    **                      登録者コード
    *****************************************************************************************************/
	function dispPersonalCordInputForm()
	{
?>

        <div class="orderform-question-box">

            <div class="orderform-question-input-area">


                <div class="orderform-question-title">
                    <div class="orderform-question-title-required-box">必須</div>
                    <div class="orderform-question-title-right">識別コード</div>
                                      
                </div>

                <div class="orderform-input-textbox">
                    <input type="number" name="input_registered_id" id="input_registered_id"  style="width: 150px;" value="<?php if( isset( $_POST["input_registered_id"] )){ echo $_POST[ "input_registered_id"];  } ?>" placeholder="数字のみ" required >
                </div>

                <div class="orderform-input-calendar-message">すでに１度でも登録された方は過去のメールにて送信している識別コードを入力してください</div>
                <div class="orderform-input-calendar-message">住所等の入力の手間が省けます</div>
                <div class="orderform-input-calendar-message">識別コードがわからない場合は、もう一方のURLからご入力ください</div>

                <div id="input_registered_id-error" class="error-container"></div>

            </div>
        </div>

        <div class="orderform-question-line"></div>


        <div class="orderform-question-box">

            <div class="orderform-question-input-area">


            <div class="orderform-question-title">
                <div class="orderform-question-title-required-box">必須</div>
                <div class="orderform-question-title-right">登録メールアドレス</div>
                                      
            </div>

            <div class="orderform-input-mail">
                <input type="mail" name="input_registered_mail" id="input_registered_mail" value="<?php if( isset( $_POST["input_registered_mail"] )){ echo $_POST[ "input_registered_mail"];  } ?>" placeholder="" required/>　
            </div>

            <div class="orderform-input-calendar-message">すでに１度でも登録されたメールアドレスを入力してください</div>
            
            <div id="input_registered_mail-error" class="error-container"></div>

            </div>
        </div>

        <div class="orderform-question-line"></div>

<?php

    }



    
    /****************************************************************************************************
    **                個人情報の情報取得関数
    *****************************************************************************************************/
	function getPersonalDataInputData($id,$post_data,$registered_id = "")
	{
        if($id == 481)//名前
        {
            $last_name = "";
            $first_name = "";

             if($registered_id == ""){

                if( isset($post_data["input_last_name"] )){ $last_name =  $post_data["input_last_name"];}
                if( isset($post_data["input_first_name"] )){ $first_name =  $post_data["input_first_name"];}
                
             }else{
                 $last_name = get_user_meta( $registered_id,'last_name',true);
                 $first_name = get_user_meta( $registered_id,'first_name',true);
             }

             return $last_name . "　" . $first_name;
        }
        else if($id == 482)//名前(フリガナ)
		{
            $last_name = "";
            $first_name = "";

             if($registered_id == ""){

                if( isset($post_data["input_last_name_kana"] )){ $last_name =  $post_data["input_last_name_kana"];}
                if( isset($post_data["input_first_name_kana"] )){ $first_name =  $post_data["input_first_name_kana"];}
                
             }else{
                 $last_name = get_user_meta( $registered_id,'last_name_kana',true);
                 $first_name = get_user_meta( $registered_id,'first_name_kana',true);
             }

             return $last_name . "　" . $first_name;

        }
        else if($id == 483)//メール
		{
            if($registered_id == ""){
                if( isset($post_data["input_user_email"] )){ return $post_data["input_user_email"];}
            }else{
                return get_the_author_meta( 'user_email' , $registered_id);
            }
        }
        else if($id == 484)//LINE ID
		{
            if($registered_id == ""){
                if( isset($post_data["input_user_line_id"] )){ return $post_data["input_user_line_id"];}
            }else{
                return get_user_meta( $registered_id,'line_id',true);
            }
        }
        else if($id == 485)//郵便番号
		{
            $post_no_1 =  "";
            $post_no_2 =  "";

            if($registered_id == ""){
                if( isset($post_data["input_post_no_1"] )){
                    $post_no_1 = $post_data["input_post_no_1"];
                }

                if( isset($post_data["input_post_no_2"] )){ 
                    $post_no_2 =  $post_data["input_post_no_2"];
                }


                return $post_no_1 . "-" . $post_no_2;

            }else{
                return get_user_meta( $registered_id,'billing_postcode',true);
            }
        }
        else if($id == 486)//住所
		{
            if($registered_id == ""){

                $input_address1 = "";
                $input_address2 = "";

                if( isset($post_data["input_address1"] )){ $input_address1 =  $post_data["input_address1"];}
                if( isset($post_data["input_address2"] )){ $input_address2 =  $post_data["input_address2"];}


                return $input_address1 . $input_address2;

            }else{
                return get_user_meta( $registered_id,'billing_city',true) .get_user_meta( $registered_id,'billing_state',true);
            }

        }
        else if($id == 509)//実家の住所
		{
            if($registered_id == ""){
                $input_address1 = "";
                $input_address2 = "";

                if( isset($post_data["input_parents_address1"] )){ $input_address1 =  $post_data["input_parents_address1"];}
                if( isset($post_data["input_parents_address2"] )){ $input_address2 =  $post_data["input_parents_address2"];}
                
                return $input_address1 . $input_address2;

            }else{
                return get_user_meta( $registered_id,'billing_parents_address_1',true) .get_user_meta( $registered_id,'billing_parents_address_2',true);
             }

        }

        else if($id == 487)//誕生日
		{
            $born_year = "";
            $born_month = "";
            $born_day = "";

            if($registered_id == ""){

                if( isset($post_data["input_user_born"] )){

                    if( $post_data["input_user_born"] == "")
                    {
                        return "";
                    }

                    $born_array = explode("-", $post_data["input_user_born"]);

                    $born_year = $born_array[0];
                    $born_month = $born_array[1];
                    $born_day = $born_array[2];

                }
             
             }else{
                $born_year =   get_user_meta( $registered_id,'born_year',true);
                $born_month =   get_user_meta( $registered_id,'born_month',true);
                $born_day =   get_user_meta( $registered_id,'born_day',true);
             }

            return $born_year ."年" .$born_month ."月" .$born_day ."日";

        }
        else if($id == 488)//申込者の届け出
		{
            $born_year = "";
            $born_month = "";
            $born_day = "";

            if($registered_id == ""){

                if( isset($post_data["input_user_report"] )){


                    if( $post_data["input_user_report"] == "")
                    {
                        return "";
                    }

                    $born_array = explode("-", $post_data["input_user_report"]);

                    $born_year = $born_array[0];
                    $born_month = $born_array[1];
                    $born_day = $born_array[2];

                }

             }else{
                $born_year =   get_user_meta( $registered_id,'report_born_year',true);
                $born_month =   get_user_meta( $registered_id,'report_born_month',true);
                $born_day =   get_user_meta( $registered_id,'report_born_day',true);
             }

            return $born_year ."年" .$born_month ."月" .$born_day ."日";

        }
        else if($id == 501)//電話番号
		{
            $billing_phone = "";
            $billing_phone2 = "";
            $billing_phone3 = "";

            if($registered_id == ""){
                if( isset($post_data["input_tel_1"] )){ $billing_phone =  $post_data["input_tel_1"];}
                if( isset($post_data["input_tel_2"] )){ $billing_phone2 =  $post_data["input_tel_2"];}
                if( isset($post_data["input_tel_3"] )){ $billing_phone3 =  $post_data["input_tel_3"];}
            }else{
                $billing_phone =   get_user_meta( $registered_id,'billing_phone',true);
                $billing_phone2 =   get_user_meta( $registered_id,'billing_phone2',true);
                $billing_phone3 =   get_user_meta( $registered_id,'billing_phone3',true);
            }

            return $billing_phone ."-" .$billing_phone2 ."-" .$billing_phone3;
        }
        else if($id == 489)//紹介者コード
		{
            $cord = "";

            if($registered_id == ""){

                if( isset($post_data["input_introduction_id"] )){ $cord =  $post_data["input_introduction_id"];}
                if( isset($post_data["input_introduction_name"] )){
                    
                    if( $post_data["input_introduction_name"] != ""){
                        $cord .=  $_POST["input_introduction_name"];
                    }
                }

                return $cord;

            }
            else{
                //識別コードを入力の際は紹介者コードはない
            }
        }
        else if($id == 507)//性別
		{
            if($registered_id == ""){
                        
                if( isset( $post_data["input_sex"] ))
                {
                    if($post_data["input_sex"] == "M"){ return "男性";}
                    else if($post_data["input_sex"] == "W"){ return "女性";}
                }
            }else{

                $sex = get_user_meta( $registered_id,'sex',true);
                            
                if($sex == "M"){ return "男性";}
                else if($sex == "W"){ return "女性";}
            }

        }
        else if($id == 490)//申込者と対象者が別
		{
            $checked = "";

            if( isset( $post_data["input-applicant-check"] )){

                if($post_data["input-applicant-check"]  == 0)
                {
                    $checked = 0;
                }
                else if($post_data["input-applicant-check"]  == 1)
                {
                    $checked = 1;
                }

                if( $checked == 0){  return "申込者と対象者が同じ";} 
                else  if( $checked == 1){  return "申込者と対象者が違う";} 
            }
        }
        else if($id == 491)//対象者の名前
		{
            $last_name = "";
            $first_name = "";

            if( isset($post_data["input_target_last_name"] )){ $last_name =  $post_data["input_target_last_name"];}
            if( isset($post_data["input_target_first_name"] )){ $first_name =  $post_data["input_target_first_name"];}

            return $last_name . "　" . $first_name;
        }
        else if($id == 492)//対象者の名前(フリガナ)
		{

            $last_name = "";
            $first_name = "";

            if( isset($post_data["input_target_last_name_kana"] )){ $last_name =  $post_data["input_target_last_name_kana"];}
            if( isset($post_data["input_target_first_name_kana"] )){ $first_name =  $post_data["input_target_first_name_kana"];}

            return $last_name . "　" . $first_name;
        }
        else if($id == 493)//対象者のメール
		{
             if( isset($post_data["input_target_email"] )){ return $post_data["input_target_email"];}

        }
        else if($id == 494)//対象者のLINE ID
		{
            if( isset($post_data["input_user_target_line_id"] )){ return $post_data["input_user_target_line_id"];}

        }
         else if($id == 495)//対象者の郵便番号
		{
            $post_no_1 =  "";
            $post_no_2 =  "";

            if( isset($post_data["input_target_post_no_1"] )){
                $post_no_1 = $post_data["input_target_post_no_1"];
            }

            if( isset($post_data["input_target_post_no_2"] )){ 
                $post_no_2 =  $post_data["input_target_post_no_2"];
            }

            return $post_no_1 . "-" . $post_no_2;
        }
        else if($id == 496)//対象者の住所
		{
            $input_address1 = "";
            $input_address2 = "";

            if( isset($post_data["input_target_address1"] )){ $input_address1 =  $post_data["input_target_address1"];}
            if( isset($post_data["input_target_address2"] )){ $input_address2 =  $post_data["input_target_address2"];}


            return $input_address1 . $input_address2;

        }
        else if($id == 510)//対象者の実家住所
		{
            $input_address1 = "";
            $input_address2 = "";

            if( isset($post_data["input_target_parents_address1"] )){ $input_address1 =  $post_data["input_target_parents_address1"];}
            if( isset($post_data["input_target_parents_address2"] )){ $input_address2 =  $post_data["input_target_parents_address2"];}


            return $input_address1 . $input_address2;
        }
        else if($id == 497)//対象者の誕生日
		{
            $born_year = "";
            $born_month = "";
            $born_day = "";

            if( isset($post_data["input_target_born"] )){

                if( $post_data["input_target_born"] == "")
                {
                    return "";
                }

                $born_array = explode("-", $post_data["input_target_born"]);

                $born_year = $born_array[0];
                $born_month = $born_array[1];
                $born_day = $born_array[2];

            }


            return $born_year ."年" .$born_month ."月" .$born_day ."日";

        }
        else if($id == 498)//対象者の届け出
		{
            $born_year = "";
            $born_month = "";
            $born_day = "";

            if( isset($post_data["input_target_report"] )){

                if( $post_data["input_target_report"] == "")
                {
                    return "";
                }

                $born_array = explode("-", $post_data["input_target_report"]);

                $born_year = $born_array[0];
                $born_month = $born_array[1];
                $born_day = $born_array[2];

            }


            return $born_year ."年" .$born_month ."月" .$born_day ."日";
        }
        else if($id == 502)//対象者の電話番号
		{

            $billing_phone = "";
            $billing_phone2 = "";
            $billing_phone3 = "";

            if( isset($post_data["input_target_tel_1"] )){ $billing_phone =  $post_data["input_target_tel_1"];}
            if( isset($post_data["input_target_tel_2"] )){ $billing_phone2 =  $post_data["input_target_tel_2"];}
            if( isset($post_data["input_target_tel_3"] )){ $billing_phone3 =  $post_data["input_target_tel_3"];}
            

            return $billing_phone ."-" .$billing_phone2 ."-" .$billing_phone3;
        }
        else if($id == 508)//対象者の性別
		{

            if( isset( $post_data["input_target_sex"] ))
            {
                if($post_data["input_target_sex"] == "M"){ return "男性";}
                else if($post_data["input_target_sex"] == "W"){ return "女性";}
            }
        }
      
        return "";
    }


    /****************************************************************************************************
    **                質問内容の回答の関数
    *****************************************************************************************************/
	function getQuestionInputData($id,$post_data,$question_type , $add )
	{

        if($question_type == 0 || $question_type == ""){ //テキスト

            if( isset($post_data["question_" .$id ] )){ 
                return nl2br($post_data["question_" .$id]);
            }

         }else if($question_type == 1){ //テキストエリア

            if( isset($post_data["question_" .$id ] )){ 
                return nl2br($post_data["question_" .$id]);
            }
         }else if($question_type == 2){ //カレンダー

            $year = "";
            $month = "";
            $day = "";




            if( isset($post_data["question_" .$id."_1"] )){ $year =  $post_data["question_" .$id."_1"];}
            if( isset($post_data["question_" .$id."_2"] )){ $month =  $post_data["question_" .$id."_2"];}
            if( isset($post_data["question_" .$id."_3"] )){ $day =  $post_data["question_" .$id."_3"];}

            return $year ."年" .$month ."月" .$day ."日";

         }else if($question_type == 3){ //名前

            $sei = ""+
            $mei = "";

            if( isset($post_data["question_" .$id."_sei"] )){ $sei =  $post_data["question_" .$id ."_sei"];}
            if( isset($post_data["question_" .$id."_mei"] )){ $mei =  $post_data["question_" .$id ."_mei"];}

            return $sei ." " .$mei;

         }else if($question_type == 4){ 

             if( isset($post_data["question_" .$id ] )){
             
                return $post_data["question_" .$id ] . "　" . $add;
             
             } 

         }else if($question_type == 5){ 

            if( isset($post_data["question_" .$id ] )){ 
                return $post_data["question_" .$id];
            }
         }else if($question_type == 6){ 

            $tel_1 = "";
            $tel_2 = "";
            $tel_3 = "";


            if( isset($post_data["question_" .$id."_tel_1"] )){ $tel_1 =  $post_data["question_" .$id."_tel_1"];}
            if( isset($post_data["question_" .$id."_tel_2"] )){ $tel_2 =  $post_data["question_" .$id."_tel_2"];}
            if( isset($post_data["question_" .$id."_tel_3"] )){ $tel_3 =  $post_data["question_" .$id."_tel_3"];}

            return $tel_1 ."-" .$tel_2 ."-" .$tel_3;

         }
         else if($question_type == 7){ 

            if( isset($post_data["question_" .$id ] )){ 
                return $post_data["question_" .$id];
            }
         }
         else if($question_type == 8){ 

            $post_check_array = array();

            if( isset($post_data["question_" .$id ] )){ 

               $post_check_array = $post_data["question_" .$id ];

               $post_check_str = "";

               foreach ($post_check_array as $check_key => $check_value) {

                    $post_check_str .= $check_value ."<br>";

               }

               return $post_check_str;

            }

         }
         else if($question_type == 9){ 

           

         }
         else if($question_type == 10){ //対象者追加 

           
            for($i=1;$i<=10;$i++)
            {
                if( isset($_POST["question_".$id . "_" .$i ."_sei"]) )
                {
?>

                    <div class="orderform-question-title">
                         <div class="orderform-question-title-required-box">必須</div>
                         <div class="orderform-question-title-right">対象者<?php echo $i;?>の名前</div>
                    </div>

                                    
                    <div class="orderform-question-input-area">
                        <div class="orderform-input-textbox"><?php echo $post_data["question_" .$id . "_" .$i ."_sei" ];?>　<?php echo $post_data["question_" .$id . "_" .$i ."_mei" ];?>
                        </div>
                    </div>

                     <div class="orderform-question-line"></div>


                    <div class="orderform-question-title">
                         <div class="orderform-question-title-required-box">必須</div>
                         <div class="orderform-question-title-right">対象者<?php echo $i;?>の名前(フリガナ)</div>
                    </div>

                                    
                    <div class="orderform-question-input-area">
                        <div class="orderform-input-textbox"><?php echo $post_data["question_" .$id . "_" .$i ."_sei_kana" ];?>　<?php echo $post_data["question_" .$id . "_" .$i ."_mei_kana" ];?>
                        </div>
                    </div>

                     <div class="orderform-question-line"></div>


                    <div class="orderform-question-title">
                         <div class="orderform-question-title-required-box">必須</div>
                         <div class="orderform-question-title-right">対象者<?php echo $i;?>と申込者との関係</div>
                    </div>

                                    
                    <div class="orderform-question-input-area">
                        <div class="orderform-input-textbox"><?php echo $post_data["question_" .$id . "_" .$i ."_parents" ];?>
                        </div>
                    </div>

                     <div class="orderform-question-line"></div>


                     <div class="orderform-question-title">
                         <div class="orderform-question-title-required-box">必須</div>
                         <div class="orderform-question-title-right">対象者<?php echo $i;?>の生年月日</div>
                    </div>

                                    
                    <div class="orderform-question-input-area">
                        <div class="orderform-input-textbox"><?php echo $post_data["question_" .$id . "_" .$i ."_date" ];?>
                        </div>
                    </div>

                     <div class="orderform-question-line"></div>
<?php
                }

            }




         }



         return "";             

                                     
    }



    /****************************************************************************************************
    **                      申込者の入力フォーム
    *****************************************************************************************************/
	function dispPersonalDataInputForm($id,$data_array, $is_check, $registered_id = "")
	{
		if($id == 481)//名前
		{
?>

            <?php if($is_check == false){?>

                <?php 
                
                    $input_last_name = "";
                    $input_first_name = "";


                    if($registered_id != "")
                    {
                        $input_last_name = get_user_meta($registered_id,'last_name',true);
                        $input_first_name = get_user_meta($registered_id,'first_name',true);
                    }
                    else{
                        if( isset( $_POST["input_last_name"] )){ $input_last_name =  $_POST[ "input_last_name" ];  }
                        if( isset(  $_POST["input_first_name"] )){ $input_first_name =  $_POST[ "input_first_name"];  }

                    }

                
                ?>


                <div class="orderform-input-name-textbox">
                    <div class="orderform-input-name-textbox-str-box">
                        <div class="orderform-input-name-textbox-str">姓:</div>
                        <input type="text" name="input_last_name" id="input_name" value="<?php echo $input_last_name; ?>" placeholder="姓"  required/>
                    </div>
                    <div class="orderform-input-name-textbox-str-box">
                        <div class="orderform-input-name-textbox-str">名:</div>
                        <input type="text" name="input_first_name" id="input_name" value="<?php echo $input_first_name; ?>" placeholder="名"  required/>
                    </div>
                </div>


                <div id="input_name-error" class="error-container"></div>

            <?php }else{ ?>

                <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,$registered_id); ?>
                </div>

            <?php } ?>

<?php

		}
        else if($id == 482)//名前(フリガナ)
		{
?>

             <?php if($is_check == false){?>


                <?php 
                
                    $input_last_name = "";
                    $input_first_name = "";


                    if($registered_id != "")
                    {
                        $input_last_name = get_user_meta($registered_id,'last_name_kana',true);
                        $input_first_name = get_user_meta($registered_id,'first_name_kana',true);
                    }
                    else{
                        if( isset( $_POST["input_last_name_kana"] )){ $input_last_name =  $_POST[ "input_last_name_kana" ];  }
                        if( isset(  $_POST["input_first_name_kana"] )){ $input_first_name =  $_POST[ "input_first_name_kana"];  }

                    }

                
                ?>

                <div class="orderform-input-name-textbox">
                    <div class="orderform-input-name-textbox-str-box">
                        <div class="orderform-input-name-textbox-str">姓:</div>
                        <input type="text" name="input_last_name_kana" id="input_name_kana" value="<?php  echo $input_last_name; ?>" placeholder="セイ"  required/>
                    </div>
                    <div class="orderform-input-name-textbox-str-box">
                        <div class="orderform-input-name-textbox-str">名:</div>
                        <input type="text" name="input_first_name_kana" id="input_name_kana" value="<?php  echo $input_first_name; ?>" placeholder="メイ"  required/>
                    </div>
                </div>
                                       
                <div id="input_name_kana-error" class="error-container"></div>


            <?php }else{ ?>

                <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,$registered_id); ?>
                </div>


            <?php } ?>
<?php

		}
        else if($id == 483)//メール
		{
?>

            <?php if($is_check == false){?>


                <?php 
                
                    $input_user_email = "";


                    if($registered_id != "")
                    {
                        $user = get_userdata( $registered_id );

                        $input_user_email =$user->user_email;
                    }
                    else{
                        if( isset( $_POST["input_user_email"] )){ $input_user_email =  $_POST[ "input_user_email" ];  }

                    }
                ?>


                <div class="orderform-input-mail">
                    <input type="email" name="input_user_email" id="input_user_email" value="<?php  echo $input_user_email;?>" placeholder=""  <?php if($data_array[$id][1] != ""){?>required <?php } ?>/>　
                </div>
                
                <div class="orderform-input-calendar-message"><font color="red">@icloud.comは使用できません</font></div>

                <div id="input_user_email-error" class="error-container"></div>

                <script>
                    const emailInput = document.getElementById('input_user_email');
                    
                    // 入力イベントを監視
                    emailInput.addEventListener('input', () => {
                    // 入力値からひらがなを削除
                    emailInput.value = emailInput.value.replace(/[\u3040-\u309F]/g, '');
                    });
                </script>

            <?php }else{ ?>

                <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,$registered_id); ?>
                </div>


            <?php } ?>
<?php

		}
        else if($id == 484)//LINE ID
		{
?>

              <?php if($is_check == false){?>


                    <?php 
                
                        $input_user_line_id = "";

                        if($registered_id != "")
                        {
                            $input_user_line_id = get_user_meta($registered_id,'line_id',true);
                        }
                        else{
                            if( isset( $_POST["input_user_line_id"] )){ $input_user_line_id =  $_POST[ "input_user_line_id" ];  }

                        }

                
                    ?>



                    <div class="orderform-input-mail">
                        <input type="text" name="input_user_line_id" id="input_user_line_id" value="<?php  echo $input_user_line_id; ?>" placeholder="@からご入力ください"  <?php if($data_array[$id][1] != ""){?>required <?php } ?>/>　
                    </div>
                                       
                    <div id="input_user_line_id-error" class="error-container"></div>

              <?php }else{ ?>

                    <div class="orderform-input-name-textbox">
                       <?php echo getPersonalDataInputData($id,$_POST,$registered_id); ?>
                    </div>

              <?php } ?>
<?php

		}
        else if($id == 485)//郵便番号
		{
?>

            <?php if($is_check == false){?>


                 <?php 
                
                        $input_post_no_1 = "";
                        $input_post_no_2 = "";


                        if($registered_id != "")
                        {
                            //$input_user_line_id = get_user_meta($registered_id,'line_id',true);


                            $post_cord =  get_user_meta($registered_id,'billing_postcode',true);

                            if( $post_cord != "" )
                            {
                                if (preg_match('/^(\d{3})(\d{4})$/', $post_cord, $matches)) {
                                    $input_post_no_1 = $matches[1];
                                    $input_post_no_2 = $matches[2];
                                }
                            }

                        }
                        else{
                            if( isset( $_POST["input_post_no_1"] )){ $input_post_no_1 =  $_POST[ "input_post_no_1" ];  }
                            if( isset( $_POST["input_post_no_2"] )){ $input_post_no_2 =  $_POST[ "input_post_no_2" ];  }

                        }

                
                 ?>



                <div class="orderform-input-post-no">
                    <input type="text" name="input_post_no_1" id="input_post_no" value="<?php  echo $input_post_no_1; ?>" oninput="validateNumberInput(this);"  <?php if($data_array[$id][1] != ""){?>required <?php } ?> style=""/> ―
                    <input type="text" name="input_post_no_2" id="input_post_no" value="<?php  echo $input_post_no_2; ?>" oninput="validateNumberInput(this);"  <?php if($data_array[$id][1] != ""){?>required <?php } ?> style=""/>　
                    <button type="button" id="button" onclick="getAddressArgument('input_post_no_1','input_post_no_2','input_address1')"  class="target_button">検索</button>
                </div>
                                       
                <div id="input_post_no-error" class="error-container"></div>

            <?php }else{ ?>

                <div class="orderform-input-name-textbox">
                     <?php echo getPersonalDataInputData($id,$_POST,$registered_id); ?>
                </div>


            <?php } ?>
<?php

		}
        else if($id == 486)//住所
		{
?>


            <?php if($is_check == false){?>

                 <?php 
                
                        $input_address1 = "";
                        $input_address2 = "";


                        if($registered_id != "")
                        {
                            $input_address1 =  get_user_meta($registered_id,'billing_city',true);
                            $input_address2 =  get_user_meta($registered_id,'billing_address_1',true);
                            

                        }
                        else{
                            if( isset( $_POST["input_address1"] )){ $input_address1 =  $_POST[ "input_address1" ];  }
                            if( isset( $_POST["input_address2"] )){ $input_address2 =  $_POST[ "input_address2" ];  }

                        }

                
                 ?>


                <div class="orderform-input-textbox">
                    都道府県・市区町村
                    <input type="text" name="input_address1" id="input_address" value="<?php  echo $input_address1;?>" placeholder=""  <?php if($data_array[$id][1] != ""){?>required <?php } ?> style=""/>　
                </div>


                <div class="orderform-input-textbox" style="margin-top: 17px;">
                    番地・マンション等
                    <input type="text" name="input_address2" id="input_address" value="<?php  echo $input_address2;?>" placeholder=""  />　
                </div>
                                       
                <div id="input_address-error" class="error-container"></div>

            <?php }else{ ?>

                 <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,$registered_id); ?>
                </div>

            <?php } ?>

<?php

		}
        else if($id == 509)//実家の住所
		{
?>


            <?php if($is_check == false){?>


                 <?php 
                
                        $input_address1 = "";
                        $input_address2 = "";


                        if($registered_id != "")
                        {
                            $input_address1 =  get_user_meta($registered_id,'billing_parents_address_1',true);
                            $input_address2 =  get_user_meta($registered_id,'billing_parents_address_2',true);
                        }
                        else{
                            if( isset( $_POST["input_parents_address1"] )){ $input_address1 =  $_POST[ "input_parents_address1" ];  }
                            if( isset( $_POST["input_parents_address2"] )){ $input_address2 =  $_POST[ "input_parents_address2" ];  }

                        }

                
                 ?>


                <div class="orderform-input-textbox">
                    都道府県・市区町村
                    <input type="text" name="input_parents_address1" id="input_parents_address" value="<?php  echo $input_address1; ?>" placeholder="現住所と同じの場合は記載しないでください"  <?php if($data_array[$id][1] != ""){?>required <?php } ?> style=""/>　
                </div>


                <div class="orderform-input-textbox" style="margin-top: 17px;">
                    番地・マンション等
                    <input type="text" name="input_parents_address2" id="input_parents_address" value="<?php  echo $input_address2; ?>" placeholder=""  />　
                </div>
                                       
                <div id="input_address-error" class="error-container"></div>

            <?php }else{ ?>

                 <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,$registered_id); ?>
                </div>

            <?php } ?>

<?php

		}
         else if($id == 487)//誕生日
		{
?>

            <?php if($is_check == false){?>

                <?php 
                
                    $input_user_born = "";


                    if($registered_id != "")
                    {
                        $year = get_user_meta($registered_id,'born_year',true);
                        $month = get_user_meta($registered_id,'born_month',true);
                        $day = get_user_meta($registered_id,'born_day',true);


                        $set_month = ($month < 10) ? "0" . $month : (string) $month;
                        $set_say = ($day < 10) ? "0" . $day : (string) $day;

                        if($year != "")
                        {
                            $input_user_born =  $year . "-" .$set_month . "-" .$set_say;
                        }
                    }
                    else{
                        if( isset( $_POST["input_user_born"] )){ $input_user_born =  $_POST[ "input_user_born" ];  }

                    }

                 ?>

            
                <div class="orderform-input-calendar">

                    <input type="date" name="input_user_born" id="input_user_born" value="<?php  echo $input_user_born; ?>" placeholder="" style="width: 120px;border: 1px solid black;"  <?php if($data_array[$id][1] != ""){?>required <?php } ?>/>
                
                </div>
                <div id="input_user_born-error" class="error-container"></div>


             <?php }else{ ?>

                <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,$registered_id); ?>
                </div>
                    
             <?php } ?>
<?php

		}
         else if($id == 488)//申込者の届け出
		{
?>


            <?php if($is_check == false){?>


                 <?php 
                
                    $input_user_born = "";


                    if($registered_id != "")
                    {
                        $year = get_user_meta($registered_id,'report_born_year',true);
                        $month = get_user_meta($registered_id,'report_born_month',true);
                        $day = get_user_meta($registered_id,'report_born_day',true);


                        $set_month = ($month < 10) ? "0" . $month : (string) $month;
                        $set_say = ($day < 10) ? "0" . $day : (string) $day;

                        if($year != "")
                        {
                            $input_user_born =  $year . "-" .$set_month . "-" .$set_say;
                        }
                    }
                    else{
                        if( isset( $_POST["input_user_report"] )){ $input_user_born =  $_POST[ "input_user_report" ];  }

                    }

                 ?>

                <div class="orderform-input-calendar">

                      <input type="date" name="input_user_report" id="input_user_report" value="<?php if( isset( $_POST["input_user_report"] )){ echo $_POST[ "input_user_report"];  } ?>" placeholder="" style="width: 120px;border: 1px solid black;"  <?php if($data_array[$id][1] != ""){?>required <?php } ?>/>
                </div>
                <div class="orderform-input-calendar-message">誕生日と出生届が違う場合にご記入ください</div>

                <div id="input_user_report-error" class="error-container"></div>


             <?php }else{ ?>

                <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,$registered_id); ?>
                </div>

             <?php } ?>
<?php

		}
        else if($id == 501)//電話番号
		{
?>

            <?php if($is_check == false){?>

                <?php 
                
                    $tel1 = "";
                    $tel2 = "";
                    $tel3 = "";


                    if($registered_id != "")
                    {
                        $tel1 = get_user_meta($registered_id,'billing_phone',true);
                        $tel2 = get_user_meta($registered_id,'billing_phone2',true);
                        $tel3 = get_user_meta($registered_id,'billing_phone3',true);
                    }
                    else{
                        if( isset( $_POST["input_tel_1"] )){ $tel1 =  $_POST[ "input_tel_1" ];  }
                        if( isset( $_POST["input_tel_2"] )){ $tel2 =  $_POST[ "input_tel_2" ];  }
                        if( isset( $_POST["input_tel_3"] )){ $tel3 =  $_POST[ "input_tel_3" ];  }

                    }

                 ?>


                <div class="orderform-input-tel">
                    <input type="text" name="input_tel_1" id="input_tel" value="<?php  echo $tel1;  ?>" oninput="validateNumberInput(this);"  placeholder="数字のみ" <?php if($data_array[$id][1] != ""){?>required <?php } ?>>　-　
                    <input type="text" name="input_tel_2" id="input_tel" value="<?php  echo $tel2;  ?>" oninput="validateNumberInput(this);"  placeholder="数字のみ" <?php if($data_array[$id][1] != ""){?>required <?php } ?>>　-　
                    <input type="text" name="input_tel_3" id="input_tel" value="<?php  echo $tel3;  ?>" oninput="validateNumberInput(this);"  placeholder="数字のみ" <?php if($data_array[$id][1] != ""){?>required <?php } ?>>
                </div>

                <div id="input_tel-error" class="error-container"></div>

            <?php }else{ ?>

                <?php 
                    if($registered_id == ""){
                        if(  !isset($_POST["input_tel_1"])  ||  !isset($_POST["input_tel_2"]) ||  !isset($_POST["input_tel_3"]) ){ return; }
                        if(  $_POST["input_tel_1"] == ""  ||  $_POST["input_tel_2"] == ""  ||  $_POST["input_tel_3"] == ""  ){ return; }
                    }
                    
                
                ?>


                <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,$registered_id); ?>
                </div>
             <?php } ?>

<?php

		}
        else if($id == 489)//紹介者コード
		{
?>

             <?php if($is_check == false){?>

                <?php 
                
                    $input_introduction_id = "";
                    $input_introduction_name = "";


                    if($registered_id != "")
                    {
                        $input_introduction_id = get_user_meta($registered_id,'input_introduction_id',true);
                        $input_introduction_name = get_user_meta($registered_id,'input_introduction_name',true);
                    }
                    else{
                        if( isset( $_POST["input_introduction_id"] )){ $input_introduction_id =  $_POST[ "input_introduction_id" ];  }
                        if( isset( $_POST["input_introduction_name"] )){ $input_introduction_name =  $_POST[ "input_introduction_name" ];  }

                    }

                 ?>


                <div class="orderform-input-textbox">
                    <input type="number" name="input_introduction_id" id="input_introduction_id" value="<?php  echo $input_introduction_id; ?>" placeholder="数字のみ" ?>
                </div>

                <div class="orderform-input-calendar-message">紹介者が登録されている場合は紹介者コードをご入力ください。</div>
                <div class="orderform-input-calendar-message">わからない場合はフルネームを下記にご入力ください</div>


                <div class="orderform-input-textbox">
                    <input type="text" name="input_introduction_name" id="input_introduction_name" value="<?php  echo $input_introduction_name; ?>" placeholder="紹介者名" style="width: 250px;margin-top: 10px;" ?>
                </div>


            <?php }else{ ?>

                <div class="orderform-input-name-textbox">
                   <?php echo getPersonalDataInputData($id,$_POST,$registered_id); ?>
                </div>

            <?php } ?>

<?php

		}
        else if($id == 507)//性別
		{
?>

             <?php if($is_check == false){?>


                <?php 
                
                    $input_sex = "";


                    if($registered_id != "")
                    {
                        $input_sex = get_user_meta($registered_id,'sex',true);
                    }
                    else{
                        if( isset( $_POST["input_sex"] )){ $input_sex =  $_POST[ "input_sex" ];  }
                    }

                 ?>



                <div class="orderform-input-select">
                
                    <select  name="input_sex" id="input_sex" <?php if($data_array[$id][1] != ""){?>required <?php } ?>>
                        <option value=""></option>
                        <option value="M" <?php  if( $input_sex == "M" ){ echo "selected"; } ?>>男性</option>
                        <option value="W" <?php  if( $input_sex == "W" ){ echo "selected"; } ?>>女性</option>
          
                    </select>

                </div>

                <div id="input_sex-error" class="error-container"></div>


            <?php }else{ ?>

                <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,$registered_id); ?>
                </div>


            <?php } ?>

<?php

		} 
        else if($id == 490)//申込者と対象者が別
		{
?>
             <?php 

                $checked = "";

                if( isset( $_POST["input-applicant-check"] )){

                    if($_POST["input-applicant-check"]  == 0)
                    {
                        $checked = 0;
                    }
                    else if($_POST["input-applicant-check"]  == 1)
                    {
                        $checked = 1;
                    }

                }

            ?>

            <?php if($is_check == false){?>

               


                <div class="orderform-input-radio">
                    <label style="display:block;">
                        <input type="radio" name="input-applicant-check" id="input-applicant-check" value="0" <?php if( $checked == 0 ){ echo "checked";  } ?>  <?php if($data_array[$id][1] != ""){?>required <?php } ?>> <b>申込者と対象者が同じ</b>
                    </label>
                    <label style="display:block;    margin-top: 10px;">
                        <input type="radio" name="input-applicant-check" id="input-applicant-check" value="1" <?php if( $checked == 1 ){ echo "checked";  } ?>> <b>申込者と対象者が違う</b>
                     </label>
                </div>

                <div id="input-applicant-check-error" class="error-container"></div>

                <div class="orderform-input-calendar-message" style="    margin-top: 10px;">対象者が違う場合はこのチェック後に対象者入力項目が表示されます</div>


            <?php }else{ ?>

                 <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,$registered_id); ?>
                </div>

            <?php } ?>

<?php

		}

	}

    
    /****************************************************************************************************
    **                      対象者の入力フォーム
    *****************************************************************************************************/
	function dispPersonalDataInputTargetForm($id,$data_array,$is_check=false)
	{

		if($id == 491)//名前
		{
?>
            <?php if($is_check == false){?>

                 <div class="orderform-input-name-textbox">
                    <div class="orderform-input-name-textbox-str-box">
                        <div class="orderform-input-name-textbox-str">姓:</div>
                        <input type="text" name="input_target_last_name" id="input_target_name" value="<?php if( isset( $_POST["input_target_last_name"] )){ echo $_POST[ "input_target_last_name" ];  } ?>" placeholder="姓"  class="required"/>
                    </div>
                    <div class="orderform-input-name-textbox-str-box">
                        <div class="orderform-input-name-textbox-str">名:</div>
                        <input type="text" name="input_target_first_name" id="input_target_name" value="<?php if( isset(  $_POST["input_target_first_name"] )){ echo $_POST[ "input_target_first_name"];  } ?>" placeholder="名"  class="required"/>
                    </div>
                </div>
                                       
                <div id="input_target_name-error" class="error-container"></div>

            <?php }else{ ?>

                 <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,""); ?>
                </div>

            <?php } ?>


<?php

		}
        else if($id == 492)//名前(フリガナ)
		{
?>

             <?php if($is_check == false){?>

                 <div class="orderform-input-name-textbox">
                    <div class="orderform-input-name-textbox-str-box">
                        <div class="orderform-input-name-textbox-str">姓:</div>
                        <input type="text" name="input_target_last_name_kana" id="input_target_name_kana" value="<?php if( isset( $_POST["input_target_last_name_kana"] )){ echo $_POST[ "input_target_last_name_kana" ];  } ?>" placeholder="セイ"  class="required"/>
                    </div>
                    <div class="orderform-input-name-textbox-str-box">
                        <div class="orderform-input-name-textbox-str">名:</div>
                        <input type="text" name="input_target_first_name_kana" id="input_target_name_kana" value="<?php if( isset(  $_POST["input_target_first_name_kana"] )){ echo $_POST[ "input_target_first_name_kana"];  } ?>" placeholder="メイ"  class="required"/>
                    </div>
                </div>
                                       
                <div id="input_target_name_kana-error" class="error-container"></div>

             <?php }else{ ?>

                 <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,""); ?>
                </div>


             <?php } ?>
<?php

		}
        else if($id == 493)//メール
		{
?>

             <?php if($is_check == false){?>

                <div class="orderform-input-textbox">
                    <button type="button" id="button" onclick="copyValue('input_user_email','input_target_email')" class="target_button">申込者と同じ</button>
                </div>


                <div class="orderform-input-mail" style="margin-top: 17px;">
                    <input type="mail" name="input_target_email" id="input_target_email" value="<?php if( isset( $_POST["input_target_email"] )){ echo $_POST[ "input_target_email"];  } ?>" placeholder=""  <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?>/>　
                </div>
                
                <div class="orderform-input-calendar-message"><font color="red">@icloud.comは使用できません</font></div>

                <div id="input_target_email-error" class="error-container"></div>


            <?php }else{ ?>

                <div class="orderform-input-name-textbox">
                   <?php echo getPersonalDataInputData($id,$_POST,""); ?>
                </div>

             <?php } ?>
<?php

		}
        else if($id == 494)//LINE ID
		{
?>

            <?php if($is_check == false){?>

                <div class="orderform-input-textbox">
                    <button type="button" id="button" onclick="copyValue('input_user_line_id','input_user_target_line_id')"  class="target_button">申込者と同じ</button>
                </div>

                <div class="orderform-input-mail" style="margin-top: 17px;">
                    <input type="text" name="input_user_target_line_id" id="input_user_target_line_id" value="<?php if( isset( $_POST["input_user_target_line_id"] )){ echo $_POST[ "input_user_target_line_id"];  } ?>" placeholder="@からご入力ください"  <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?>/>　
                </div>
                                       
                <div id="input_user_target_line_id-error" class="error-container"></div>


            <?php }else{ ?>

                <div class="orderform-input-name-textbox">
                   <?php echo getPersonalDataInputData($id,$_POST,""); ?>
                </div>

            <?php } ?>

<?php

		}
        else if($id == 495)//郵便番号
		{

?>
            <?php if($is_check == false){?>

                 <div class="orderform-input-post-no">
                    <input type="text" name="input_target_post_no_1" id="input_target_post_no" value="<?php if( isset( $_POST["input_target_post_no_1"] )){ echo $_POST[ "input_target_post_no_1"];  } ?>" oninput="validateNumberInput(this);"  <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?> style=""/> ―
                    <input type="text" name="input_target_post_no_2" id="input_target_post_no" value="<?php if( isset( $_POST["input_target_post_no_2"] )){ echo $_POST[ "input_target_post_no_2"];  } ?>" oninput="validateNumberInput(this);"  <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?> style=""/>　
                     <button type="button" id="button" onclick="getAddressArgument('input_target_post_no_1','input_target_post_no_2','input_target_address1')"  class="target_button">検索</button>
                 </div>

                                       
                <div id="input_target_post_no-error" class="error-container"></div>


            <?php }else{ ?>


                <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,""); ?>
                </div>

            <?php } ?>
<?php

		}
        else if($id == 496)//住所
		{
?>
            <?php if($is_check == false){?>

                <div class="orderform-input-textbox">
                    <button type="button" id="button"  class="target_button" onclick="copyAddressValue('input_post_no_1','input_post_no_2','input_address1','input_address2','input_target_post_no_1','input_target_post_no_2','input_target_address1','input_target_address2')">申込者の住所と同じ</button>
                </div>

                <div class="orderform-input-textbox"  style="margin-top: 17px;">
                    都道府県・市区町村
                    <input type="text" name="input_target_address1" id="input_target_address" value="<?php if( isset( $_POST["input_target_address1"] )){ echo $_POST[ "input_target_address1"];  } ?>" placeholder=""  <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?> style=""/>　
                </div>


                <div class="orderform-input-textbox" style="margin-top: 17px;">
                    番地・マンション等
                    <input type="text" name="input_target_address2" id="input_target_address" value="<?php if( isset( $_POST["input_target_address2"] )){ echo $_POST[ "input_target_address2"];  } ?>" placeholder=""  />　
                </div>
                                       
                <div id="input_target_address-error" class="error-container"></div>


            <?php }else{ ?>

                 <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,""); ?>
                </div>

            <?php } ?>

<?php

		}
        else if($id == 510)//実感住所
		{
?>
            <?php if($is_check == false){?>

                <div class="orderform-input-textbox">
                    都道府県・市区町村
                    <input type="text" name="input_target_parents_address1" id="input_target_parents_address" value="<?php if( isset( $_POST["input_target_parents_address1"] )){ echo $_POST[ "input_target_parents_address1"];  } ?>" placeholder="現住所と同じの場合は記載しないでください"  <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?> style=""/>　
                </div>


                <div class="orderform-input-textbox" style="margin-top: 17px;">
                    番地・マンション等
                    <input type="text" name="input_target_parents_address2" id="input_target_parents_address" value="<?php if( isset( $_POST["input_target_parents_address2"] )){ echo $_POST[ "input_target_parents_address2"];  } ?>" placeholder=""  />　
                </div>
                                       
                <div id="input_target_address-error" class="error-container"></div>


            <?php }else{ ?>

                <div class="orderform-input-name-textbox">
                    <<?php echo getPersonalDataInputData($id,$_POST,""); ?>
                </div>

            <?php } ?>
<?php

		}
        else if($id == 497)//誕生日
		{
?>

             <?php if($is_check == false){?>

                <div class="orderform-input-calendar">

                    <?php /*
                    <input type="number" name="input_target_born_year" id="input_target_born" value="<?php if( isset( $_POST["input_target_born_year"] )){ echo $_POST[ "input_target_born_year"];  } ?>" placeholder="" style="width: 80px;border: 1px solid black;"  <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?>/>
                                             
                    <div class="order-calendar-placeholder">年</div>


                    <input type="text" name="input_target_born_month"  id="input_target_born" list="month" placeholder="" autocomplete="off" style="width: 40px;border: 1px solid black;" value="<?php if( isset( $_POST["input_target_born_month"] )){ echo $_POST[ "input_target_born_month"];  } ?>"  <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?>>
                    <datalist id="month">
                        <?php for($i=1;$i<=12;$i++){?>
                            <option value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php } ?>
                    </datalist>

                    <div class="order-calendar-placeholder">月</div>

                    <input type="text" name="input_target_born_day"  id="input_target_born" list="day" placeholder="" autocomplete="off" style="width: 40px;border: 1px solid black;" value="<?php if( isset( $_POST["input_target_born_day"] )){ echo $_POST[ "input_target_born_day"];  } ?>"  <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?>>
                    <datalist id="day">
                        <?php for($i=1;$i<=31;$i++){?>
                            <option value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php } ?>
                    </datalist>

                    <div class="order-calendar-placeholder">日</div>
                    */?>

                      <input type="date" name="input_target_born" id="input_target_born" value="<?php if( isset( $_POST["input_target_born"] )){ echo $_POST[ "input_target_born"];  } ?>" placeholder="" style="width: 120px;border: 1px solid black;"  <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?>/>
                </div>
                                        
                <?php /*<div class="orderform-input-calendar-message">『年』は入力、『月』『日』は入力もしくは選択できます</div> */?>

                <div id="input_target_born-error" class="error-container"></div>


             <?php }else{ ?>


                <?php 
                
                    if(  !isset($_POST["input_target_born_year"])  ||  !isset($_POST["input_target_born_month"]) ||  !isset($_POST["input_target_born_day"]) ){ return; }
                    if(  $_POST["input_target_born_year"] == ""  ||  $_POST["input_target_born_month"] == ""  ||  $_POST["input_target_born_day"] == ""  ){ return; }
                
                ?>

                <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,""); ?>
                </div>

            <?php } ?>

<?php

		}
        else if($id == 498)//対象者の届け出
		{
?>


            <?php if($is_check == false){?>

                <div class="orderform-input-calendar">

                    <?php /*
                        <input type="number" name="input_target_report_year" id="input_target_report" value="<?php if( isset( $_POST["input_target_report_year"] )){ echo $_POST[ "input_target_report_year"];  } ?>" placeholder="" style="width: 80px;border: 1px solid black;"  <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?>/>
                                             
                        <div class="order-calendar-placeholder">年</div>


                        <input type="text" name="input_target_report_month"  id="input_target_report" list="month" placeholder="" autocomplete="off" style="width: 40px;border: 1px solid black;" value="<?php if( isset( $_POST["input_user_report_month"] )){ echo $_POST[ "input_user_report_month"];  } ?>"  <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?>>
                        <datalist id="month">
                            <?php for($i=1;$i<=12;$i++){?>
                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                            <?php } ?>
                        </datalist>

                        <div class="order-calendar-placeholder">月</div>

                        <input type="text" name="input_target_report_day"  id="input_target_report" list="day" placeholder="" autocomplete="off" style="width: 40px;border: 1px solid black;" value="<?php if( isset( $_POST["input_target_report_day"] )){ echo $_POST[ "input_target_report_day"];  } ?>"  <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?>>
                        <datalist id="day">
                            <?php for($i=1;$i<=31;$i++){?>
                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                            <?php } ?>
                        </datalist>

                        <div class="order-calendar-placeholder">日</div>
                     */?>
                    <input type="number" name="input_target_report" id="input_target_report" value="<?php if( isset( $_POST["input_target_report"] )){ echo $_POST[ "input_target_report"];  } ?>" placeholder="" style="width: 120px;border: 1px solid black;"  <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?>/>
                        

                </div>
                                        
                <?php /* <div class="orderform-input-calendar-message">『年』は入力、『月』『日』は入力もしくは選択できます</div> */?>
                <div class="orderform-input-calendar-message">誕生日と出生届が違う場合にご記入ください</div>

                <div id="input_target_report-error" class="error-container"></div>

            <?php }else{ ?>


                 <?php 
                
                    if(  !isset($_POST["input_target_report_year"])  ||  !isset($_POST["input_target_report_month"]) ||  !isset($_POST["input_target_report_day"]) ){ return; }
                    if(  $_POST["input_target_report_year"] == ""  ||  $_POST["input_target_report_month"] == ""  ||  $_POST["input_target_report_day"] == ""  ){ return; }
                
                ?>

                <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,""); ?>
                </div>

            <?php } ?>

<?php

		}
        else if($id == 502)//電話番号
		{
?>
             <?php if($is_check == false){?>


                 <div class="orderform-input-textbox">
                    <button type="button" id="button" onclick="copyTelValue('input_tel_1','input_tel_2','input_tel_3','input_target_tel_1','input_target_tel_2','input_target_tel_3')"  class="target_button">申込者と同じ</button>
                </div>


                <div class="orderform-input-tel"  style="margin-top: 17px;">
                    <input type="text" name="input_target_tel_1" id="input_target_tel" value="<?php if( isset( $_POST["input_target_tel_1"] )){ echo $_POST[ "input_target_tel_1"];  } ?>" oninput="validateNumberInput(this);"  placeholder="数字のみ" <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?>>　-　
                    <input type="text" name="input_target_tel_2" id="input_target_tel" value="<?php if( isset( $_POST["input_target_tel_2"] )){ echo $_POST[ "input_target_tel_2"];  } ?>" oninput="validateNumberInput(this);"  placeholder="数字のみ" <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?>>　-　
                    <input type="text" name="input_target_tel_3" id="input_target_tel" value="<?php if( isset( $_POST["input_target_tel_3"] )){ echo $_POST[ "input_target_tel_3"];  } ?>" oninput="validateNumberInput(this);"  placeholder="数字のみ" <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?>>
                </div>

                <div id="input_target_tel-error" class="error-container"></div>


             <?php }else{ ?>


                <?php 
                
                    if(  !isset($_POST["input_target_tel_1"])  ||  !isset($_POST["input_target_tel_2"]) ||  !isset($_POST["input_target_tel_3"]) ){ return; }
                    if(  $_POST["input_target_tel_1"] == ""  ||  $_POST["input_target_tel_2"] == ""  ||  $_POST["input_target_tel_3"] == ""  ){ return; }
                
                ?>

                <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,""); ?>
                </div>

             <?php } ?>

<?php

		} 
        else if($id == 508)//性別
		{
?>

             <?php if($is_check == false){?>

                <div class="orderform-input-select">
                
                    <select  name="input_target_sex" id="input_target_sex" <?php if($data_array[$id][1] != ""){?>class="required" <?php } ?>>
                        <option value=""></option>
                        <option value="M">男性</option>
                        <option value="W">女性</option>
          
                    </select>

                </div>

                <div id="input_target_sex-error" class="error-container"></div>


            <?php }else{ ?>

                <div class="orderform-input-name-textbox">
                    <?php echo getPersonalDataInputData($id,$_POST,""); ?>
                </div>


            <?php } ?>

<?php

		} 

	}





























?>