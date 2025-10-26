
<?php 

 var_dump($_POST);  //削除okd
    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../custompage/front/order-form-disp-function.php");
    require_once (dirname(__FILE__)."/../../class/spiritInputCustomizeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");

    $question_num = 0;

    if(isset($_GET["type_id"]))
    {
        $question_num = $_GET["type_id"];
    }
    else if(isset($_POST["type_id"]))
    {
        $question_num = $_POST["type_id"];
    }


    //入力コードあり
    $registered = "";
    $registered_url = "";


    if(isset($_GET["registered"]))
    {
        $registered = $_GET["registered"];
        $registered_url = "&registered=true";
    }

  

   /* $set_url = getURLSetSlag( "order-check" ) ."?type_id=" .$question_num . $registered_url;

            if(isset($_GET["member"])){
               $set_url.=   "&member=" .$_GET["member"];
            }
            wp_redirect($set_url);
            exit;
   */
    


    $spirit_sheet_data = new spiritSheetClass(); //質問データ
    $spirit_customize_data = new SpiritInputCustomizeClass(); //文字データ


   



    //識別コードのエラー
    $cord_err = false;

    //識別コードのユーザー
    $registered_id = "";

    if(isset($_POST["check_code"]))
    {

          //ユーザーの全データを取得
	    $users = get_users( array('orderby'=>'ID','order'=>'ASC') ); 

        foreach ($users as $key => $value) {
            $usermeta[] = $value->ID;//ユーザーIDを取得して配列に格納
        }

        //ユーザーID
        $user_cord = -1;

        if(isset($_POST["input_registered_id"]))
        {
             if($_POST["input_registered_id"] != "")
             {
                 $user_cord = $_POST["input_registered_id"];
             }
        }
        
        //ユーザー数を取得してループ
        for($i=0;$i<count($usermeta);$i++){
            $get = get_user_meta( $usermeta[$i],'user_unique_id',true);


            if($get == $user_cord){ //ユーザーコード一致

                $mail = $_POST["input_registered_mail"];
                $mail = str_replace(" ","",$mail);
                $mail = str_replace("　","",$mail);
                $mail = preg_replace('/\s+/u', '', $mail);

                // 家族アドレスで登録済みからID取得できるように変更　241225
                $regist_mail = CheckFamilyMail(get_the_author_meta('user_email',$usermeta[$i]));

                // if(get_the_author_meta('user_email',$usermeta[$i]) == $mail)
                if($regist_mail == $mail)
                {
                     $registered_id =  $usermeta[$i];
                     break;
                     //echo $registered_id;
                }
            }
            
        }

        if($registered_id == "")
        {
            $cord_err = true;

            $registered_url  = "&registered=true";
        }
    }
     //echo $registered_id;


    $customize_data = $spirit_customize_data->getInputCustomizeData( $question_num );
    $spiritSheetArray = $spirit_sheet_data->getSpiritQuestion($question_num);

    //個人情報の項目
    $personal_input_array = $spirit_customize_data->getPersonalDataInput( $question_num );


    $postData = $_POST;
 
    // $registed_users_email = getUserEmails();    //登録ユーザーメアド取得
    $registed_users_email = json_encode(getUserEmails());    //登録ユーザーメアド取得

   // var_dump($registed_users_email);  //削除okd
   // echo "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
   //var_dump($_POST);
?>
 <style>
    .hidden {
        display: none;
    }

    .input-area.hidden {
        display: none;
    }
    .disabled-button {
        background-color: gray;
        color: white;
        cursor: not-allowed;

    }
</style>
<title>Form Validation</title>

<?php
            
    $title = get_field('acf_pure_spirit_disp_title',$question_num); 

    if($title == "")
    {
        $title =  get_field('acf_pure_spirit_title',$question_num); 
    }
            
?>


<?php if(!$cord_err){?>

    <div class="input-form-area">

        <div class="input-form-contens">


             <div class="input-form-header-img">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hituyou.png" alt="Image 1" style="width: 100%;">
             </div>


             <div class="input-form-main">


                 <div class="input-form-title">
                     <div class="input-form-title-str">【　<?php echo $title; ?>　】</div>
                 </div>


                 <?php if($customize_data["acf_spirit_start_text"] !=  ""){ //開始文 ?>

                    <div class="input-form-text-box">

                         <div class="input-form-text"><?php echo nl2br($spirit_customize_data->changeInputCustomizeData( $customize_data["acf_spirit_start_text"]));?></div>

                    </div>


                 <?php } ?>


                 <?php if($customize_data["acf_spirit_flow_text"] !=  ""){ //～〇〇流れ ?>

                    <div class="input-form-flow-box">


                        <div class="input-form-flow-title">
                             <div class="input-form-flow-title-str"><?php echo $title; ?>の流れ</div>
                        </div>

                         <div class="input-form-flow-text"><?php echo nl2br($spirit_customize_data->changeInputCustomizeData( $customize_data["acf_spirit_flow_text"]));?></div>

                    </div>


                 <?php } ?>




                 <?php if($customize_data["acf_spirit_text_1"] !=  ""){ //テキスト差し込み1 ?>

                    <div class="input-form-text-box">

                         <div class="input-form-text"><?php echo nl2br($spirit_customize_data->changeInputCustomizeData( $customize_data["acf_spirit_text_1"]));?></div>

                    </div>


                 <?php } ?>


                 <?php if($customize_data["acf_spirit_contact_information"] !=  ""){ //お問い合わせメール１ ?>


               

                    <div class="input-form-text-box">

                         <div class="input-form-contact-str">■お問い合わせ先■</div>

                         <div class="input-form-text" ><?php echo nl2br($spirit_customize_data->changeInputCustomizeData( $customize_data["acf_spirit_contact_information"]));?></div>

                    </div>


                 <?php } ?>


                  <?php if($customize_data["acf_spirit_before_input"] !=  ""){ //フォームにご入力頂く前に ?>

                    <div class="input-form-flow-box">


                        <div class="input-form-flow-title">
                             <div class="input-form-flow-title-str">フォームにご入力頂く前に</div>
                        </div>

                         <div class="input-form-flow-text"><?php echo nl2br($spirit_customize_data->changeInputCustomizeData( $customize_data["acf_spirit_before_input"]));?></div>

                    </div>


                 <?php } ?>


                 <?php if($customize_data["acf_spirit_text_2"] !=  ""){ //テキスト差し込み2 ?>

                    <div class="input-form-text-box">

                         <div class="input-form-text" ><?php echo nl2br($spirit_customize_data->changeInputCustomizeData( $customize_data["acf_spirit_text_2"]));?></div>

                    </div>


                 <?php } ?>


                 <?php if($customize_data["acf_spirit_contact_information_2"] !=  ""){ //お問い合わせメール2 ?>


               

                    <div class="input-form-text-box">

                         <div class="input-form-contact-str">■お問い合わせ先■</div>

                         <div class="input-form-text"><?php echo nl2br($spirit_customize_data->changeInputCustomizeData( $customize_data["acf_spirit_contact_information_2"]));?></div>

                    </div>


                 <?php } ?>


                 <div class="orderform-area">

                     <div class="input-form-title">
                         <div class="input-form-title-str">必要事項をご入力ください</div>
                     </div>


                     <div class="orderform-cointens">

                         <?php 
                        
                            if($registered)
                            {
                            
                         ?>

                            <form id="orderForm" name="orderForm" action="<?php echo  getURLSetSlag( "order-form" );?>?type_id=<?php echo $question_num;?><?php if(isset($_GET["member"])){ ?>&member=<?php echo $_GET["member"];?><?php } ?>" method="post" >
                                <?php 
                        
                    
                           
                            
                                        dispPersonalCordInputForm(); //コード入力確認
                            
                        
                                ?>

                                 <input type="hidden"  name="check_code" value="">

                                 <div class="input-send-button-area">
                                    <button class="input-send-button" id="submitBtn" type="button">コード入力する</button>
                                </div>

                            </fomr>

                        <?php } ?>

                        <?php if($registered == ""){?>


                            <form id="orderForm" name="orderForm" action="<?php echo  getURLSetSlag( "order-check" );?>?type_id=<?php echo $question_num . $registered_url;?><?php if(isset($_GET["member"])){ ?>&member=<?php echo $_GET["member"];?><?php } ?>" method="post"  enctype="multipart/form-data">

                            
                                <?php 
                                    // 既存IDの情報更新処理の為のユーザー情報POST　250113
                                    if(isset($_POST["input_registered_id"]))
                                    {
                                        if($_POST["input_registered_id"] != "")
                                        {
                                            ?>
                                            <input type="hidden" name="input_registered_id" value="<?php echo $user_cord; ?>">
                                            <?php 
                                        }
                                    }
                                
                                ?>
                           
                                <?php foreach ($personal_input_array as $key => $value) { // 申込者入力?>


                                    <?php 
                                
                                        if(!isset($customize_data["acf_spirit_note_personal_table"][ $value["ID"]] ) ){continue;}
                                        if($customize_data["acf_spirit_note_personal_table"][ $value["ID"] ][0] == ""){continue;}
                                        if($value["acf_input_target"] != ""  ){continue;} 
                                        if($registered && $value["ID"] != 490){continue;} //登録codeあり
                                    ?>




                                    <div class="orderform-question-box">
                                         <div class="orderform-question-input-area">


                                            <div class="orderform-question-title">
                                                <?php if($customize_data["acf_spirit_note_personal_table"][ $value["ID"] ][1] != ""){?>
                                                    <div class="orderform-question-title-required-box">必須</div>
                                                <?php } ?>
                                                <div class="orderform-question-title-right"><?php echo $value["acf_input_personal_data_title"]; //echo  $value["ID"]; ?></div>
                                      
                                            </div>

                                            <?php 
                                                if($value["acf_input_target"] == "" ){
                                                    dispPersonalDataInputForm( $value["ID"] , $customize_data["acf_spirit_note_personal_table"] , false , $registered_id);
                                                }
                                            ?>

                                         </div>
                                     </div>

                                      <div class="orderform-question-line"></div>

                                <?php } ?>


                                <div id="additionalFields" class="hidden">

                                    <?php foreach ($personal_input_array as $key => $value) { //対象者と申込者が違う?>


                                        <?php 
                                            if(!isset($customize_data["acf_spirit_note_personal_table"][ $value["ID"]] ) ){continue;} 
                                            if($customize_data["acf_spirit_note_personal_table"][ $value["ID"] ][0] == ""){continue;}
                                            if($value["acf_input_target"] == "" ){continue;} 
                                        ?>

                                        <div class="orderform-question-box">
                                             <div class="orderform-question-input-area">


                                                <div class="orderform-question-title">
                                                    <?php if($customize_data["acf_spirit_note_personal_table"][ $value["ID"] ][1] != ""){?>
                                                        <div class="orderform-question-target-required-box">必須</div>
                                                    <?php } ?>
                                                    <div class="orderform-question-title-right"><?php echo $value["acf_input_personal_data_title"]; //echo  $value["ID"]; ?></div>
                                      
                                                </div>

                                                <?php 
                                
                                                    if($value["acf_input_target"] != "" ){
                                                         dispPersonalDataInputTargetForm( $value["ID"] , $customize_data["acf_spirit_note_personal_table"]);
                                                    }
                                                ?>
                              
                                             </div>
                                         </div>

                                          <div class="orderform-question-line"></div>

                                    <?php } ?>
                                </div>

                                <?php 

                         

                                    $count = 1;

                                    foreach ($spiritSheetArray[$question_num] as $key => $value) {

                                        if($value["form_disp"]){

                                          

                                 ?>

                                    <div class="orderform-question-box">

                                
                                        <?php if($value["type"] != 10){ //対象者の追加はタイトルは別  ?>
                                            <div class="orderform-question-title">
                                   
                                    
                                                <?php if($value["required"]){?>
                                                     <div class="orderform-question-title-required-box">必須</div>
                                                <?php }else{ ?>


                                                    <div class="orderform-question-title-left"> </div>

                                                <?php } ?>

                                                <div class="orderform-question-title-right">
                                    
                                                    <?php echo $value["text"];?>
                                    
                                                </div>

                                            </div>
                                        <?php } ?>


                                        <div class="orderform-question-input-area">

                                            <?php if($value["type"] == 0 || $value["type"] == ""){ //テキスト?>

                                                <div class="orderform-input-textbox">
                                                     <input type="text" name="question_<?php echo $value["ID"]; ?>" id="question_<?php echo $value["ID"]; ?>" value="<?php if( isset( $_POST["question_" .$value["ID"]] )){ echo $_POST[ "question_". $value["ID"]];  } ?>" placeholder="<?php echo $value["add"];  ?>"    <?php if($value["required"]){?>required <?php } ?> />
                                             
                                                </div>

                                                <div class="orderform-input-textbox-placeholder-area">
                                                    <div class="orderform-input-alert-text"><?php echo nl2br($value["add"]);  ?></div>
                                            
                                                </div>
                                       
                                                <div id="question_<?php echo $value["ID"]; ?>-error" class="error-container"></div>


                                            <?php }else if($value["type"] == 1){ //テキストエリア ?>
                                   
                                                <div class="orderform-input-texarea">
                                                    <textarea id="question_<?php echo $value["ID"]; ?>" name="question_<?php echo $value["ID"]; ?>" rows="8" cols="33" style="width: 90%;border: 1px solid #000;"  <?php if($value["required"]){?>required <?php } ?>><?php if( isset( $_POST["question_" .$value["ID"]] )){ echo $_POST[ "question_". $value["ID"]];  } ?></textarea>
                                                    <div class="orderform-input-alert-text"><?php echo nl2br($value["add"]);  ?></div>
                                                    <div id="question_<?php echo $value["ID"]; ?>-error" class="error-container"></div>
                                                </div>
                                       
                                            <?php }else if($value["type"] == 2){ //カレンダー?>

                                                <div class="orderform-input-calendar">


                                                         <input type="date" id="question_<?php echo $value["ID"]; ?>" name="question_<?php echo $value["ID"]; ?>" value="<?php if( isset( $_POST["question_" .$value["ID"]] )){ echo $_POST[ "question_". $value["ID"]];  } ?>">

                                                </div>
                                        
                                                 <div class="orderform-input-calendar-message">『年』は入力、『月』『日』は入力もしくは選択できます</div>

                                                 <div id="question_<?php echo $value["ID"]; ?>-error" class="error-container"></div>
                                        

                                            <?php }else if($value["type"] == 3){ //名前?>


                                                 <div class="orderform-input-name-textbox">
                                                    <div class="orderform-input-name-textbox-str-box">
                                                        <div class="orderform-input-name-textbox-str">姓:</div>
                                                        <input type="text" name="question_<?php echo $value["ID"]; ?>_sei" id="question_<?php echo $value["ID"]; ?>" value="<?php if( isset( $_POST["question_" .$value["ID"] ."_sei"] )){ echo $_POST[ "question_". $value["ID"]  ."_sei" ];  } ?>" placeholder="姓"  <?php if($value["required"]){?>required <?php } ?>/>
                                                    </div>
                                                    <div class="orderform-input-name-textbox-str-box">
                                                        <div class="orderform-input-name-textbox-str">名:</div>
                                                        <input type="text" name="question_<?php echo $value["ID"]; ?>_mei" id="question_<?php echo $value["ID"]; ?>" value="<?php if( isset( $_POST["question_" .$value["ID"]  ."_mei"] )){ echo $_POST[ "question_". $value["ID"] ."_mei"];  } ?>" placeholder="名"  <?php if($value["required"]){?>required <?php } ?>/>
                                                    </div>
                                                </div>
                                       
                                                <div id="question_<?php echo $value["ID"]; ?>-error" class="error-container"></div>


                                            <?php }else if($value["type"] == 4){ //数字?>

                                                <div class="orderform-input-textbox">
                                                     <input type="number" name="question_<?php echo $value["ID"]; ?>" id="question_<?php echo $value["ID"]; ?>" value="<?php if( isset( $_POST["question_" .$value["ID"]] )){ echo $_POST[ "question_". $value["ID"]];  } ?>" placeholder=""  <?php if($value["required"]){?>required <?php } ?>/>　<?php echo $value["add"];  ?>
                                                </div>
                                      
                                                <div id="question_<?php echo $value["ID"]; ?>-error" class="error-container"></div>

                                            <?php }else if($value["type"] == 5){ //メール?>

                                                 <div class="orderform-input-mail">
                                                     <input type="mail" name="question_<?php echo $value["ID"]; ?>" id="question_<?php echo $value["ID"]; ?>" value="<?php if( isset( $_POST["question_" .$value["ID"]] )){ echo $_POST[ "question_". $value["ID"]];  } ?>" placeholder="<?php echo $value["add"];  ?>"  <?php if($value["required"]){?>required <?php } ?>/>　
                                                </div>
                                       
                                                <div id="question_<?php echo $value["ID"]; ?>-error" class="error-container"></div>

                                           <?php }else if($value["type"] == 6){ //電話番号?>

                                                <div class="orderform-input-tel">
                                                     <input type="text" name="question_<?php echo $value["ID"]; ?>_tel_1" id="question_<?php echo $value["ID"]; ?>" value="<?php if( isset( $_POST["question_" .$value["ID"] ."_tel_1"] )){ echo $_POST[ "question_". $value["ID"] ."_tel_1"];  } ?>" oninput="validateNumberInput(this);"  placeholder="数字のみ" <?php if($value["required"]){?>required <?php } ?>/>　-　
                                                     <input type="text" name="question_<?php echo $value["ID"]; ?>_tel_2" id="question_<?php echo $value["ID"]; ?>" value="<?php if( isset( $_POST["question_" .$value["ID"] ."_tel_2"] )){ echo $_POST[ "question_". $value["ID"] ."_tel_2"];  } ?>" oninput="validateNumberInput(this);"  placeholder="数字のみ" <?php if($value["required"]){?>required <?php } ?>/>　-　
                                                     <input type="text" name="question_<?php echo $value["ID"]; ?>_tel_3" id="question_<?php echo $value["ID"]; ?>" value="<?php if( isset( $_POST["question_" .$value["ID"] ."_tel_3"] )){ echo $_POST[ "question_". $value["ID"] ."_tel_3"];  } ?>" oninput="validateNumberInput(this);"  placeholder="数字のみ" <?php if($value["required"]){?>required <?php } ?>/>
                                                </div>
                                                <div class="orderform-input-alert-text"><?php echo nl2br($value["add"]);  ?></div>
                                                <div id="question_<?php echo $value["ID"]; ?>-error" class="error-container"></div>
                                    
                                            <?php }else if($value["type"] == 7){ //選択?>


                                                <?php 
                                        
                                            

                                                    $select_text =  nl2br($value["add"]);

                                                    $select_text = str_replace(array("\r\n", "\r", "\n"), "\n", $select_text);
                                                    $select_array = array();
                                                    $select_array = explode("\n", $select_text);

                                                ?>

                                                <div class="orderform-input-select">
                                                     <select name="question_<?php echo $value["ID"]; ?>" id="question_<?php echo $value["ID"]; ?>" <?php if($value["required"]){?>required <?php } ?>>
                                                         <option value=""></option>
                                                        <?php  foreach ($select_array as $select_key => $select_value) { ?>
                                                            <option value="<?php echo $select_value; ?>" <?php if( isset( $_POST["question_" .$value["ID"]] )){ if( $_POST["question_" .$value["ID"]] == $select_value ){ echo "selected"; }} ?>><?php echo $select_value; ?></option>
                                                        <?php } ?>


                                                      </select>
                                                </div>
                                       
                                                <div id="question_<?php echo $value["ID"]; ?>-error" class="error-container"></div>

                                            <?php }else if($value["type"] == 8){ //チェックボックス?>


                                                <?php 

                                                    $select_text =  nl2br($value["add"]);

                                                    $select_text = str_replace(array("\r\n", "\r", "\n"), "\n", $select_text);
                                                    $select_array = array();
                                                    $select_array = explode("\n", $select_text);


                                                    $check_post = array();

                                                    //var_dump($_POST["question_" .$value["ID"]]);

                                                    if(isset( $_POST["question_" .$value["ID"]] )){ 

                                                        foreach ($_POST["question_" .$value["ID"]] as $check_key => $check_value) {

                                                           $check_post[ $check_value ] = $check_value;

                                                        }

                                                    }

                                                ?>

                                                <div class="orderform-input-select">
                                                    <?php  foreach ($select_array as $select_key => $select_value) { ?>
                                                        <input type="checkbox" name="question_<?php echo $value["ID"]; ?>[]" id="question_<?php echo $value["ID"]; ?>" value="<?php echo $select_value; ?>"    <?php if(isset( $check_post[ $select_value ] ) ){ echo "checked";} ?>   ><?php echo $select_value; ?>
                                                    <?php } ?>
                                                </div>
                                       
                                                <div id="question_<?php echo $value["ID"]; ?>-error" class="error-container"></div>

                                            <?php }else if($value["type"] == 9){ //ラジオボックス ?>


                                                <?php 

                                                    $select_text =  nl2br($value["add"]);

                                                    $select_text = str_replace(array("\r\n", "\r", "\n"), "\n", $select_text);
                                                    $select_array = array();
                                                    $select_array = explode("\n", $select_text);


                                                    $check_post = "";

                                                    //var_dump($_POST["question_" .$value["ID"]]);

                                                    if(isset( $_POST["question_" .$value["ID"]] )){ 

                                                        $check_post = $_POST["question_" .$value["ID"]];

                                                    }
                                                    else{
                                                        $check_post = $select_array[0];
                                                    }

                                                ?>

                                                <div class="orderform-input-radio">
                                                    <?php  foreach ($select_array as $select_key => $select_value) { ?>
                                                        <label style="margin-top: 10px;">
                                                            <input type="radio" name="question_<?php echo $value["ID"]; ?>" id="question_<?php echo $value["ID"]; ?>" value="<?php echo $select_value; ?>"  <?php if($check_post == $select_value  ){ echo "checked";} ?> > <b><?php echo $select_value; ?></b>
                                                        </label>
                                                    <?php } ?>
                                           
                                                </div>

                                            <?php }else if($value["type"] == 10){ //対象追加 ?>


                                       
                                                <?php 
                                        
                                                    $target_member = 1;

                                                    if(isset($_GET["member"]) && $_GET["member"] != ""){

                                                        $target_member = $_GET["member"];
                                                    }
                                        
                                        
                                                ?>


                                                <?php for($i=1;$i<=$target_member;$i++){?>
                                        
                                        
                                                    <div class="input-area <?php if(($i > 1) && (!isset($_GET["member"]) || $_GET["member"] == "")){ echo " hidden";} ?>" id="inputArea<?php echo $i;?>">

                                                        <div class="orderform-question-title">
                                   
                                    
                                                            <?php if($value["required"]){?>
                                                                 <div class="orderform-question-title-required-box">必須</div>
                                                            <?php }else{ ?>


                                                                <div class="orderform-question-title-left"> </div>

                                                            <?php } ?>

                                                            <div class="orderform-question-title-right">対象者<?php echo $i;?>の氏名</div>

                                                        </div>

                                                        <div class="orderform-input-name-textbox">
                                                            <div class="orderform-input-name-textbox-str-box">
                                                                <div class="orderform-input-name-textbox-str">姓:</div>
                                                                <input type="text" name="question_<?php echo $value["ID"] ."_" .$i;  ?>_sei" id="question_<?php echo $value["ID"] ."_" .$i; ?>_name" value="<?php if( isset( $_POST["question_" .$value["ID"] ."_" .$i ."_sei"] )){ echo $_POST[ "question_". $value["ID"]  ."_" .$i  ."_sei" ];  } ?>" placeholder="姓" <?php if($value["type"] == 1){ ?> required <?php }?>/>
                                                            </div>
                                                            <div class="orderform-input-name-textbox-str-box">
                                                                <div class="orderform-input-name-textbox-str">名:</div>
                                                                <input type="text" name="question_<?php echo $value["ID"] ."_" .$i;  ?>_mei" id="question_<?php echo $value["ID"] ."_" .$i; ?>_name" value="<?php if( isset( $_POST["question_" .$value["ID"]  ."_" .$i  ."_mei"] )){ echo $_POST[ "question_". $value["ID"]  ."_" .$i ."_mei"];  } ?>" placeholder="名"  <?php if($value["type"] == 1){ ?> required <?php }?>/>
                                                            </div>
                                                        </div>

                                                        <div id="question_<?php echo $value["ID"]   ."_" .$i; ?>_name-error" class="error-container"></div>

                                                        <div class="orderform-question-line"></div>

                                                        <div class="orderform-question-title">
                                   
                                                            <?php if($value["required"]){?>
                                                                 <div class="orderform-question-title-required-box">必須</div>
                                                            <?php }else{ ?>
                                                                <div class="orderform-question-title-left"> </div>
                                                             <?php } ?>

                                                             <div class="orderform-question-title-right">対象者<?php echo $i;?>の氏名(フリガナ)</div>

                                                        </div>

                                                        <div class="orderform-input-name-textbox">
                                                            <div class="orderform-input-name-textbox-str-box">
                                                                <div class="orderform-input-name-textbox-str">姓:</div>
                                                                <input type="text" name="question_<?php echo $value["ID"] ."_" .$i; ?>_sei_kana" id="question_<?php echo $value["ID"]; ?>_kana" value="<?php if( isset( $_POST["question_" .$value["ID"]  ."_" .$i ."_sei_kana"] )){ echo $_POST[ "question_". $value["ID"]  ."_" .$i  ."_sei_kana" ];  } ?>" placeholder="セイ"  <?php if($value["type"] == 1){ ?> required <?php }?>/>
                                                            </div>
                                                            <div class="orderform-input-name-textbox-str-box">
                                                                <div class="orderform-input-name-textbox-str">名:</div>
                                                                <input type="text" name="question_<?php echo $value["ID"] ."_" .$i; ?>_mei_kana" id="question_<?php echo $value["ID"]; ?>_kana" value="<?php if( isset( $_POST["question_" .$value["ID"]   ."_" .$i ."_mei_kana"] )){ echo $_POST[ "question_". $value["ID"] ."_" .$i  ."_mei_kana"];  } ?>" placeholder="メイ"  <?php if($value["type"] == 1){ ?> required <?php }?>/>
                                                            </div>
                                                        </div>

                                                         <div id="question_<?php echo $value["ID"]   ."_" .$i; ?>_kana-error" class="error-container"></div>

                                                        <div class="orderform-question-line"></div>

                                                        <div class="orderform-question-title">
                                   
                                                            <?php if($value["required"]){?>
                                                                 <div class="orderform-question-title-required-box">必須</div>
                                                            <?php }else{ ?>
                                                                <div class="orderform-question-title-left"> </div>
                                                             <?php } ?>

                                                             <div class="orderform-question-title-right">対象者<?php echo $i;?>と申込者の関係</div>

                                                        </div>


                                                        <div class="orderform-input-textbox">
                                                            <input type="text" name="question_<?php echo $value["ID"]  ."_" .$i ."_parents" ; ?>" id="question_<?php echo $value["ID"]  ."_" .$i; ?>_parents" value="<?php if( isset( $_POST["question_" .$value["ID"]  ."_" .$i ."_parents" ] )){ echo $_POST[ "question_". $value["ID"]  ."_" .$i ."_parents" ];  } ?>" placeholder="本人、長男、母親など"   required />
                                                        </div>

                                           
                                       
                                                        <div id="question_<?php echo $value["ID"]   ."_" .$i; ?>_parents-error" class="error-container"></div>

                                                        <div class="orderform-question-line"></div>

                                                          <div class="orderform-question-title">
                                   
                                                            <?php if($value["required"]){?>
                                                                 <div class="orderform-question-title-required-box">必須</div>
                                                            <?php }else{ ?>
                                                                <div class="orderform-question-title-left"> </div>
                                                             <?php } ?>

                                                             <div class="orderform-question-title-right">対象者<?php echo $i;?>の生年月日</div>

                                                        </div>

                                                        <div class="orderform-input-textbox">
                                                            <input type="date" id="question_<?php echo $value["ID"]  ."_" .$i ; ?>_date" name="question_<?php echo $value["ID"]  ."_" .$i ; ?>_date" value="<?php if( isset( $_POST["question_" .$value["ID"]  ."_" .$i ."_date"] )){ echo $_POST[ "question_". $value["ID"]  ."_" .$i ."_date" ];  } ?>" <?php if($value["type"] == 1){ ?> required <?php }?> >
                                                        </div>


                                                         <div id="question_<?php echo $value["ID"]   ."_" .$i; ?>_date-error" class="error-container"></div>


                                                        <div class="orderform-question-line"></div>

                                                          <div class="orderform-question-title">
                                   
                                                            <?php if($value["required"]){?>
                                                                 <div class="orderform-question-title-required-box">必須</div>
                                                            <?php }else{ ?>
                                                                <div class="orderform-question-title-left"> </div>
                                                             <?php } ?>

                                                             <div class="orderform-question-title-right">対象者<?php echo $i;?>の画像</div>

                                                        </div>

                                                        <div class="input-img-upload-form-box" style="display: block;">
                                                            <div class="orderform-remote-question-target-execution-date" style="text-align: left;">
                                                                <label for="fileInput_<?php echo $i; ?>"></label>
                                                                <input type="file" id="fileInput_<?php echo $i; ?>" name="question_<?php echo $value["ID"]  ."_" .$i ; ?>_uploaded_files" accept="image/*" onchange="previewSingleImage(this, <?php echo $i; ?>)" style="margin-bottom: 30px;margin-top: 20px;">
                                                            </div>
                                                            <div>
                                                                <div id="preview_<?php echo $i; ?>" class="image-preview-box" style="display: flex; flex-wrap: wrap;">
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <input type="hidden" name="question_updataimg_question[]" value="<?php echo $value["ID"]; ?>" >
                                                        <input type="hidden" name="question_updataimg_target[]" value="<?php echo $i; ?>" >

                                                        <div class="orderform-question-line" style="border-top: 3px solid black;"></div>


                                                    </div>

                                                <?php } ?>


                                                <?php if(!isset($_GET["member"]) || $_GET["member"] == ""){?>
                                                    <button type="button" class="AreaAddButton" id="addButton" onclick="showNextInputArea()">エリアを追加</button>
                                                    <button type="button" class="AreaRemoveButton" id="removeButton" onclick="hideLastInputArea()">エリアを削除</button>
                                                <?php } ?>

                                          

                                            <?php }else if($value["type"] == 11){ // ?>
                                                <div class="orderform-input-alert-text"><?php echo nl2br($value["add"]);  ?></div>
                                                <button type="button" class="imgButton" id="imgButton">画像を追加</button>

                                            <?php } ?>

                                         </div>


                                    </div>

                                    <?php if($value["type"] != 10){ //対象者の追加はタイトルは別  ?>
                                        <div class="orderform-question-line"></div>
                                    <?php } ?>

                                 <?php 
                                                $count++;
                                            }
                                        } 
                                   ?>

                                  <input type="hidden" name="type_id"  value="<?php echo $question_num; ?>" />
            
                            </form>

                        <?php } ?>

                     </div>
                 </div>


                 <?php if($registered == ""){?>

                     <?php if($customize_data["acf_spirit_text_3"] !=  ""){ //テキスト差し込み2 ?>

                        <div class="input-form-text-box">

                             <div class="input-form-text"><?php echo nl2br($spirit_customize_data->changeInputCustomizeData( $customize_data["acf_spirit_text_3"]));?></div>

                        </div>


                     <?php } ?>


                     <?php 

                        $note_count = 0;
             
                       //注意が１つ以上あるかどうか
                       if( $customize_data["acf_spirit_note_1"] != "" )$note_count++;
                       if( $customize_data["acf_spirit_note_2"] != "" )$note_count++;
                       if( $customize_data["acf_spirit_note_3"] != "" )$note_count++;
                       if( $customize_data["acf_spirit_note_4"] != "" )$note_count++;
                       if( $customize_data["acf_spirit_note_5"] != "" )$note_count++;



                       $note_disp_count = 1;
             
                     ?>

<?php /*
                     <?php if($note_count > 0){ //注意文章有 ?>

                        <div class="input-form-note-area">

                            <div class="input-form-note-area-title">
                                 <div class="input-form-note-area-title-str">【 ご注意 】</div>
                            </div>

                            <?php for($i=1;$i<6;$i++){?>
                
                                <?php if($customize_data["acf_spirit_note_" .$i] !=  ""){ //注意１ ?>

                    
                                     <?php if($note_count == 1){ //１つだけ ?>

                                        <?php if( $customize_data["acf_spirit_note_title_" .$i] !=  ""){ //注意タイトル ?>
                                            <div class="input-form-note-title-box">
                                                <div class="input-form-note-title-str"><?php echo $customize_data["acf_spirit_note_title_" .$i]; ?></div>
                                            </div>
                                         <?php } ?>

                                     <?php }else{ ?>

                            
                                        <div class="input-form-note-title-box">
                                            <div class="input-form-note-title-str">～　その<?php echo $note_disp_count; $note_disp_count++; ?> <?php if( $customize_data["acf_spirit_note_title_" .$i] !=  ""){ ?>【<?php echo $customize_data["acf_spirit_note_title_" .$i]; ?>】<?php } ?>　～</div>
                                        </div>


                                     <?php } ?>

                                    <div class="input-form-note-box">

                                         <div class="input-note-text"><?php echo nl2br($spirit_customize_data->changeInputCustomizeData( $customize_data["acf_spirit_note_" .$i]));?></div>

                                    </div>


                                 <?php } ?>

                            <?php } ?>


                        </div>

                     <?php } ?>



                     <div class="input-send-button-area">
                        <button class="input-send-button" id="submitBtn" type="button" 　>上記の確認画面へ</button>
                    </div>

                    <?php */ ?>

                <?php } ?>

              </div>

        </div>

    </div>

<?php }else{ //識別コードのエラー ?>

     <div class="input-form-area">

        <div class="input-form-contens">

             <div class="input-form-header-img">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hituyou.png" alt="Image 1" style="width: 100%;">
             </div>

             <div class="input-form-main">

                <div class="orderform-area" style="margin-top: 20px;">

                    <div class="input-form-title">
                         <div class="input-form-title-str">【　<?php echo $title; ?>　】</div>
                         <div class="input-form-title-str" style="color:red;">入力した識別コードの情報がありません</div>
                    </div>


                    
                    <div class="orderform-return-cointens">

                        <div class="input-form-registered-err">

                            識別コードと登録したメールアドレスが一致していません。<br>
                            <br>
                            入力画面に戻り、再度、識別コードを入れなおすか、<a href="<?php echo  getURLSetSlag( "order-form" );?>?type_id=<?php echo $question_num;?><?php if(isset($_GET["member"])){ ?>&member=<?php echo $_GET["member"];?><?php } ?>">こちら</a>より新規での入力を行ってください。


                        </div>


                        <form id="orderForm" name="returnForm" action="<?php echo  getURLSetSlag( "order-form" );?>?type_id=<?php echo $question_num . $registered_url;?><?php if(isset($_GET["member"])){ ?>&member=<?php echo $_GET["member"];?><?php } ?>" method="post" >


                            <?php foreach ($_POST as $key => $value) {?>

                                <?php 
                                
                                    //チェックコードは外す
                                    if($key == "check_code")
                                    {
                                        continue;
                                    }

                                ?>

                                <?php if( is_array($value)){?>

                                    <?php foreach ($value as $array_key => $array_value) {?>

                                        <input type="hidden" name="<?php echo $key; ?>[]"  value="<?php echo $array_value; ?>" />

                                    <?php } ?>

                                <?php }else{ ?>
                                    <input type="hidden" name="<?php echo $key; ?>"  value="<?php echo $value; ?>" />
                                <?php } ?>

                            <?php } ?>

                                <div class="orderform-return-send-area">
                                    <input type="submit" value="入力に戻る" class="orderform-return-send"/>
                                </div>

                        </form>

                    </div>

                </div>

             </div>


        </div>

     </div>


<?php } 

ob_end_flush(); // 出力バッファを送信して終了
?>



     <script>
         document.getElementById('submitBtn').addEventListener('click', function(event) {
            // フォーム要素を取得
            var form = document.getElementById('orderForm');
            // 必須入力項目を取得
            var requiredFields = form.querySelectorAll('[required]');
            var formIsValid = true;

            
            var u_mal = document.getElementById("input_user_email");


            if(!u_mal)
            {
               u_mal = document.getElementById("input_registered_mail");

               if(!u_mal)
               {
                   return;
               }
            }

            const regex = /^[a-zA-Z0-9_.+-]+@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/;   // メアドチェック
        

            // 以前のエラーメッセージを削除
            var errorContainers = form.querySelectorAll('.error-container');
            errorContainers.forEach(function(container) {
                container.textContent = ''; // エラーメッセージをクリア
            });

            // 必須入力項目をチェック
            requiredFields.forEach(function(field) {
                if (!field.value) {
                    formIsValid = false;
                    var errorContainer = document.getElementById(field.id + '-error');
                    if (errorContainer) {
                        errorContainer.textContent = '入力がありません';
                        errorContainer.classList.add('error-message');
                    }
                }
            });

            if(!regex.test(u_mal.value) ){
                console.log(u_mal);
                alert("メールアドレスが正しく入力されていません");
                check_ok = false;
            }

            // フォームが無効な場合
            else if (!formIsValid) {
                event.preventDefault(); // フォーム送信を防止
                alert('必須入力をしていない項目があります'); // ダイアログを表示
            } else {
                form.submit(); // フォーム送信を実行
            }
        });


         function validateEmail(inputId, errorMessageId) {
            var emailInput = document.getElementById(inputId);
            var emailValue = emailInput.value;
            var errorMessageDiv = document.getElementById(errorMessageId);

            // var php_registed_users_email = JSON.parse(<?php echo $registed_users_email ?>);
            var php_registed_users_email = <?php echo $registed_users_email ?>;

            // 24/12/16　登録できるように変更
            // if(php_registed_users_email.includes(emailValue)){
                
            //     alert('すでにこちらのメールアドレスは登録されています。違うメールアドレスで登録するか、すでに一度でも登録されているお客様は登録済みの入力フォームから、お送りしているIDを使用し、入力してください。\\nIDがわからない場合はsaito-masako@earth-a-gate.comまでお問い合わせください');
            //     emailInput.value = ''; // 入力をクリア
            //     emailInput.focus(); // フォーカスを戻す
            // }

            // icloud.comドメインのチェック
            if (emailValue.endsWith('@icloud.com')) {
                //errorMessageDiv.textContent = '@icloud.comは使用できません';
                alert('@icloud.comは使用できません');
                emailInput.value = ''; // 入力をクリア
                emailInput.focus(); // フォーカスを戻す
            } else {
                //errorMessageDiv.textContent = ''; // エラーメッセージをクリア
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var u_mal = document.getElementById("input_user_email");
            if (u_mal) {
                    validateEmail('input_user_email', 'input_user_email-error');
            } else {
               // console.error("input_user_emailが見つかりません");
            }
        });
        /*
        document.getElementById('input_user_email').addEventListener('blur', function() {
            validateEmail('input_user_email', 'input_user_email-error');
        });
        */

          document.addEventListener('DOMContentLoaded', function() {
            var u_mal = document.getElementById("input_target_email");
            if (u_mal) {
                    validateEmail('input_target_email', 'input_target_email-error');
            } else {
               // console.error("input_user_emailが見つかりません");
            }
        });

        /*
         document.getElementById('input_target_email').addEventListener('blur', function() {
            validateEmail('input_target_email', 'input_target_email-error');
        });
        */
        function validateNumberInput(input) {
            input.value = input.value.replace(/[^0-9]/g, "");
        }


        
// 郵便番号取得
        function getAddressArgument(zipcorde_1, zipcorde_2, address_id ) {

            
            var zip1 = document.querySelector(`input[name="${zipcorde_1}"]`).value;
            var zip2 = document.querySelector(`input[name="${zipcorde_2}"]`).value;

            
            var zipcode = zip1 + zip2;

            if (!zip1 || !zip2) {
                alert("郵便番号を入力してください");
                return;
            }

           // 郵便番号の形式をチェック
            if (zipcode.length === 7 && /^[0-9]+$/.test(zipcode)) {
                // Fetch APIを使用して日本郵便のAPIから住所を取得
                fetch(`https://zipcloud.ibsnet.co.jp/api/search?zipcode=${zipcode}`)
                    .then(response => response.json())
                    .then(data => {
                        setAddress(data, address_id);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('住所の取得に失敗しました。');
                    });
            } else {
                alert('正しい郵便番号を入力してください。');
            }
        }

      function setAddress(response, addressName) {
            if (response.status === 200 && response.results) {
                var result = response.results[0];
                var address = result.address1 + result.address2 + result.address3;
                document.querySelector(`input[name="${addressName}"]`).value = address;
            } else {
                alert('住所が見つかりません。');
            }
        }



        function copyAddressValue(sourcePost1,sourcePost2,sourceName1,sourceName2, destinationPost1,destinationPost2, destinationName1,destinationName2) {
            // コピー元の値を取得
            var postValue1 = document.querySelector(`input[name="${sourcePost1}"]`).value;
            var postValue2 = document.querySelector(`input[name="${sourcePost2}"]`).value;

            var sourceValue1 = document.querySelector(`input[name="${sourceName1}"]`).value;
            var sourceValue2 = document.querySelector(`input[name="${sourceName2}"]`).value;

            // コピー先に値を設定
            document.querySelector(`input[name="${destinationPost1}"]`).value = postValue1;
            document.querySelector(`input[name="${destinationPost2}"]`).value = postValue2;
            document.querySelector(`input[name="${destinationName1}"]`).value = sourceValue1;
            document.querySelector(`input[name="${destinationName2}"]`).value = sourceValue2;
        }



        function copyTelValue(sourceTel1,sourceTel2,sourceTel3, destinationTel1,destinationTel2, destinationTel3) {
            // コピー元の値を取得
            
            var sourceValue1 = document.querySelector(`input[name="${sourceTel1}"]`).value;
            var sourceValue2 = document.querySelector(`input[name="${sourceTel2}"]`).value;
            var sourceValue3 = document.querySelector(`input[name="${sourceTel3}"]`).value;

            // コピー先に値を設定
            document.querySelector(`input[name="${destinationTel1}"]`).value = sourceValue1;
            document.querySelector(`input[name="${destinationTel2}"]`).value = sourceValue2;
            document.querySelector(`input[name="${destinationTel3}"]`).value = sourceValue3;
        }


        function copyValue(sourceName1, destinationName1) {
            // コピー元の値を取得
            var sourceValue1 = document.querySelector(`input[name="${sourceName1}"]`).value;

            // コピー先に値を設定
            document.querySelector(`input[name="${destinationName1}"]`).value = sourceValue1;
        }

    </script>

     <script>
        // フォーム要素の取得
        var form = document.getElementById('orderForm');
        var additionalFields = document.getElementById('additionalFields');
        var radioButtons = form.querySelectorAll('input[name="input-applicant-check"]');


        // ラジオボタンの初期選択状態をチェック
        var selectedRadio = form.querySelector('input[name="input-applicant-check"]:checked');
        if (selectedRadio && selectedRadio.value === "1") {
            additionalFields.classList.remove('hidden');
            additionalFields.querySelectorAll('.required').forEach(function(input) {
                input.setAttribute('required', 'required');
            });
        }

        // ラジオボタンの選択状態を監視
        radioButtons.forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (radio.value === "1") {
                    // 追加項目を表示し、必須フィールドに必須属性を追加
                    additionalFields.classList.remove('hidden');
                    additionalFields.querySelectorAll('.required').forEach(function(input) {
                        input.setAttribute('required', 'required');
                    });
                } else {
                    // 追加項目を非表示にし、必須フィールドの必須属性を削除
                    additionalFields.classList.add('hidden');
                    additionalFields.querySelectorAll('.required').forEach(function(input) {
                        input.removeAttribute('required');
                    });
                }
            });
        });
       
    </script>


    <script>
         let inputCount = 1; // 最初のエリアは常に表示
        const maxInputs = 10;

        function updateButtons() {
            const addButton = document.getElementById('addButton');
            const removeButton = document.getElementById('removeButton');

            if(!removeButton)
            {
                return;
            }

            if (inputCount <= 1) {
                removeButton.classList.add('disabled-button');
                removeButton.disabled = true;
            } else {
                removeButton.classList.remove('disabled-button');
                removeButton.disabled = false;
            }

            if (inputCount >= maxInputs) {
                addButton.classList.add('disabled-button');
                addButton.disabled = true;
            } else {
                addButton.classList.remove('disabled-button');
                addButton.disabled = false;
            }
        }

        function showNextInputArea() {
            if (inputCount < maxInputs) {
                inputCount++;
                const nextArea = document.getElementById('inputArea' + inputCount);
                if (nextArea) {
                    nextArea.classList.remove('hidden');
                    // フィールドを有効にし、必須にする
                    const inputs = nextArea.querySelectorAll('input');
                    inputs.forEach(input => {
                        input.disabled = false;
                        input.setAttribute('required', 'required');
                    });
                }
            }
            updateButtons();
        }

        function hideLastInputArea() {
            if (inputCount > 1) { // 最初のエリアは削除不可
                const lastArea = document.getElementById('inputArea' + inputCount);
                if (lastArea) {
                    lastArea.classList.add('hidden');
                    // フィールドを無効にし、必須を解除する
                    const inputs = lastArea.querySelectorAll('input');
                    inputs.forEach(input => {
                        input.disabled = true;
                        input.removeAttribute('required');
                    });
                }
                inputCount--;
            }
            updateButtons();
        }

         // 初期状態で最初のエリアのフィールドは有効、他は無効
        document.addEventListener('DOMContentLoaded', () => {
            // ここでURLパラメータをチェックしてPOSTデータを確認
            const postData = <?php echo json_encode($postData); ?>;
            // postData.forEach((element) => console.log(element));

            //const urlParams = new URLSearchParams(window.location.search);
            for (let i = 2; i <= maxInputs; i++) {
                const area = document.getElementById('inputArea' + i);


                if(!area)
                {
                    continue;
                }

                //iput要素を全て取得
                const inputs = area.querySelectorAll('input');

              

                const hasValue = Array.from(inputs).some(input => postData[input.name]);

               // alert(area.textContent);

                if (hasValue) {
                    area.classList.remove('hidden');
                    inputs.forEach(input => {
                        //input.disabled = false;
                        input.setAttribute('required', 'required');
                    });
                    inputCount = i; // 最後に開いたエリアまでのカウントを更新
                } else {
                    inputs.forEach(input => {
                        //input.disabled = true;
                        input.removeAttribute('required');
                    });
                }
            }
            updateButtons();
        });
    </script>

    <script>
       function previewSingleImage(input, index) {
            const previewContainer = document.getElementById(`preview_${index}`);
            previewContainer.innerHTML = ''; // プレビューをリセット

            if (input.files && input.files.length > 0) {
                const file = input.files[0];
                const reader = new FileReader();
                reader.onload = function (e) {
                    // 画像のラッパー要素
                    const wrapper = document.createElement('div');
                    wrapper.style.position = 'relative';
                    wrapper.style.display = 'inline-block';
                    wrapper.style.margin = '5px';

                    // 画像要素
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '200px';
                    img.style.maxHeight = '200px';
                    img.style.display = 'block';

                    // 削除ボタン
                    const removeButton = document.createElement('button');
                    removeButton.textContent = '×';
                    removeButton.style.position = 'absolute';
                    removeButton.style.top = '0';
                    removeButton.style.right = '0';
                    removeButton.style.background = 'red';
                    removeButton.style.color = 'white';
                    removeButton.style.border = 'none';
                    removeButton.style.borderRadius = '50%';
                    removeButton.style.cursor = 'pointer';
                    removeButton.style.width = '20px';
                    removeButton.style.height = '20px';

                    // 削除ボタンのクリックでプレビューを消去し、inputをリセット
                    removeButton.onclick = function () {
                        wrapper.remove(); // プレビューを削除
                        input.value = ''; // inputをリセット
                    };

                    // ラッパーに追加
                    wrapper.appendChild(img);
                    wrapper.appendChild(removeButton);
                    previewContainer.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            }
        }
    </script>