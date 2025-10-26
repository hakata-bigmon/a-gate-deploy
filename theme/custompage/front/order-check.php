
<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../custompage/front/order-form-disp-function.php");
    require_once (dirname(__FILE__)."/../../class/spiritInputCustomizeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");


    $spirit_sheet_data = new spiritSheetClass(); //質問データ
    $spirit_customize_data = new SpiritInputCustomizeClass(); //文字データ


     //ユーザーの全データを取得
	$users = get_users( array('orderby'=>'ID','order'=>'ASC') ); 

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
    $registered_data = "";

    //登録者のID
    $registered_id = ""; 

    if(isset($_GET["registered"]))
    {
        $registered = $_GET["registered"];
        $registered_url = "&registered=true";
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


                //echo $mail .$_POST["input_registered_mail"] ."aaa<br>";
                if(get_the_author_meta('user_email',$usermeta[$i]) == $mail)
                {
                     $registered_id =  $usermeta[$i];
                     //echo $registered_id;
                }
            }
            
        }

    }

    //入力フォーム情報(文章)
    $customize_data = $spirit_customize_data->getInputCustomizeData( $question_num );
    //質問情報
    $spiritSheetArray = $spirit_sheet_data->getSpiritQuestion($question_num);

    //個人情報の項目
    $personal_input_array = $spirit_customize_data->getPersonalDataInput( $question_num );


   // echo "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
   if(get_current_user_id() == 1){

      // var_dump($_FILES);
       //var_dump($_POST);
   }


  
   $question_imp_array = array();

   //画像の保存
   if (isset($_POST['question_updataimg_question'])) {

        foreach ($_POST["question_updataimg_question"] as $key => $value) 
        {


            $FILE_NAME =  "question_".$value ."_" .$_POST["question_updataimg_target"][ $key ] ."_uploaded_files";

            if(isset( $_FILES[ $FILE_NAME ] ))
            {
                $upload_dir = wp_upload_dir();
                $today_date = date('Y-m-d'); // 今日の日付（例: 2025-01-09）
                // 今日の日付フォルダを作成
                $custom_dir = $upload_dir['basedir'] . '/custom_images/' . $today_date;
                $custom_url = $upload_dir['baseurl'] . '/custom_images/' . $today_date;

                // ディレクトリがなければ作成
                if (!file_exists($custom_dir)) {
                    wp_mkdir_p($custom_dir);
                }

               // var_dump($_FILES[ $FILE_NAME ]);
                // アップロードファイルを処理
               // foreach ($_FILES[ $FILE_NAME ] as $id => $file_group) {
               $file = $_FILES[$FILE_NAME];
                //echo $file['name'];
                if (!empty($file['name'])) {

                    $file_new_name = sanitize_file_name($file['name'] ); // ファイル名を安全な形式に変換
                    $file_name = sanitize_file_name($_POST["question_updataimg_target"][ $key ] . "_" .$file['name'] ); // ファイル名を安全な形式に変換
                    $file_tmp = $file['tmp_name'];
                    $file_path = $custom_dir . '/' . $file_name;

                    // ファイルを保存
                    if (move_uploaded_file($file_tmp, $file_path)) {

                        //画像URL保存ファイルを入れる
                        if(!isset($question_imp_array[$value]))
                        {
                            $question_imp_array[$value] = array();
                        }

                         //画像URL保存ファイルを入れる
                        if(!isset($question_imp_array[$value][ $_POST["question_updataimg_target"][ $key ] ]))
                        {
                            $question_imp_array[$value][ $_POST["question_updataimg_target"][ $key ] ] = array();
                        }

                        $question_imp_array[$value][ $_POST["question_updataimg_target"][ $key ] ]["URL"] = esc_url($custom_url . '/' . $file_name);
                        $question_imp_array[$value][ $_POST["question_updataimg_target"][ $key ] ]["FILE"] =$file_name;
                        $question_imp_array[$value][ $_POST["question_updataimg_target"][ $key ] ]["FILE_NAME"] =$file_new_name;
                        $question_imp_array[$value][ $_POST["question_updataimg_target"][ $key ] ]["PATH"] =$file_path;

                        //echo '<p>アップロード成功: ' . esc_url($custom_url . '/' . $file_name) . '</p>';
                    } else {
                        //echo '<p>アップロード失敗: ' . esc_html($file_name) . '</p>';

                        //失敗した場合は戻らせる
                    }
                }
                    
               // }
            }




        }

       

    /*$upload_dir = wp_upload_dir();
    $custom_dir = $upload_dir['basedir'] . '/custom_images';
    $custom_url = $upload_dir['baseurl'] . '/custom_images';

    // ディレクトリがなければ作成
    if (!file_exists($custom_dir)) {
        wp_mkdir_p($custom_dir);
    }

    // アップロードファイルを処理
    foreach ($_FILES['uploaded_files'] as $id => $file_group) {
        foreach ($file_group as $index => $file) {
            if (!empty($file['name'])) {
                $file_name = sanitize_file_name($file['name']);
                $file_tmp = $file['tmp_name'];
                $file_path = $custom_dir . '/' . $file_name;

                if (move_uploaded_file($file_tmp, $file_path)) {
                    echo '<p>アップロード成功: ' . esc_url($custom_url . '/' . $file_name) . '</p>';
                } else {
                    echo '<p>アップロード失敗: ' . esc_html($file_name) . '</p>';
                }
            }
        }
    }*/
}


?>

<title>Form Validation</title>




<?php if($registered_url != "" && $registered_id == ""){ //コードが違う ?>

     <div class="input-form-area">

        <div class="input-form-contens">

             <div class="input-form-header-img">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hituyou.png" alt="Image 1" style="width: 100%;">
             </div>

             <div class="input-form-main">

                <div class="orderform-area" style="margin-top: 20px;">

                    <div class="input-form-title">
                         <div class="input-form-title-str">【　<?php echo get_field('acf_pure_spirit_disp_title',$question_num); ?>　】</div>
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


<?php }else{ ?>
    <?php //var_dump($_POST); ?>

    <div class="input-form-area">


        <div class="input-form-contens">


             <div class="input-form-header-img">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hituyou.png" alt="Image 1" style="width: 100%;">
             </div>


             <div class="input-form-main">


                 <div class="orderform-area" style="margin-top: 20px;">

                     <div class="input-form-title">
                         <div class="input-form-title-str">【　<?php echo get_field('acf_pure_spirit_disp_title',$question_num); ?>　】</div>
                         <div class="input-form-title-str">入力情報を確認してください</div>
                     </div>


                     <div class="orderform-cointens">

                        <form id="orderForm" name="orderForm" action="<?php echo  getURLSetSlag( "thanks" );?>?type_id=<?php echo $question_num . $registered_url;?>" method="post" >


                            <?php foreach ($personal_input_array as $key => $value) {?>


                                <?php 
                                    if(!isset($customize_data["acf_spirit_note_personal_table"][ $value["ID"]] ) ){continue;}   //個人情報の表示ON・OFF
                                    if($customize_data["acf_spirit_note_personal_table"][ $value["ID"] ][0] == ""){continue;}   //個人情報の表示ON・OFF

                                    if($value["acf_input_target"] != ""  ){continue;} 
                                
                                    if($registered_url != "" && $customize_data["acf_spirit_note_personal_table"][ $value["ID"] ][2] == ""){continue;}
                                
                                ?>



                                <div class="orderform-question-box">
                                     <div class="orderform-question-input-area">


                                        <div class="orderform-question-title">
                                            <?php if($customize_data["acf_spirit_note_personal_table"][ $value["ID"] ][1] != ""){?>
                                                <div class="orderform-question-title-required-box">必須</div>
                                            <?php } ?>
                                            <div class="orderform-question-title-right"><?php echo $value["acf_input_personal_data_title"];?></div>
                                      
                                        </div>

                                        <?php 
                                            if($value["acf_input_target"] == "" ){
                                                dispPersonalDataInputForm( $value["ID"] , $customize_data["acf_spirit_note_personal_table"] , true , $registered_id);
                                            }
                                        ?>

                                     </div>
                                 </div>

                                  <div class="orderform-question-line"></div>

                            <?php } ?>


                            <div id="additionalFields">

                                <?php foreach ($personal_input_array as $key => $value) {?>


                                    <?php 
                                        if(!isset($customize_data["acf_spirit_note_personal_table"][ $value["ID"]] ) ){continue;}
                                        if($customize_data["acf_spirit_note_personal_table"][ $value["ID"] ][0] == ""){continue;}
                                        if($value["acf_input_target"] == "" ){continue;}
                                        if( !isset($_POST["input-applicant-check"] ) ){continue;}
                                        if( $_POST["input-applicant-check"]  != 1 ){continue;} 
                                    ?>

                                    <div class="orderform-question-box">
                                         <div class="orderform-question-input-area">


                                            <div class="orderform-question-title">
                                                <?php if($customize_data["acf_spirit_note_personal_table"][ $value["ID"] ][1] != ""){?>
                                                    <div class="orderform-question-target-required-box">必須</div>
                                                <?php } ?>
                                                <div class="orderform-question-title-right"><?php echo $value["acf_input_personal_data_title"];?></div>
                                      
                                            </div>

                                            <?php 
                                
                                                if($value["acf_input_target"] != ""  ){
                                                     dispPersonalDataInputTargetForm( $value["ID"] , $customize_data["acf_spirit_note_personal_table"],true);
                                                }
                                            ?>
                              
                                         </div>
                                     </div>

                                      <div class="orderform-question-line"></div>

                                <?php } ?>
                            </div>

                            <?php 

                         

                                $count = 1;

                                foreach ($spiritSheetArray[$question_num] as $key => $value) {?>


                                    <?php  if($value["form_disp"]){?>

                                        <div class="orderform-question-box">


                                            <?php if($value["type"] != 10){?>

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

                                                <div class="orderform-input-textbox">
                                                    <?php echo getQuestionInputData( $value["ID"] ,$_POST ,$value["type"] , $value["add"] , $question_imp_array); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if($value["type"] != 10){?>
                                            <div class="orderform-question-line"></div>
                                        <?php } ?>
                                        
                                        <?php $count++; ?>
                                        
                                        <?php } ?>
                                 <?php } ?>

                              <input type="hidden" name="type_id"  value="<?php echo $question_num; ?>" />

                              <?php foreach ($_POST as $key => $value) {?>

                                    <input type="hidden" name="<?php echo $key; ?>"  value="<?php echo $value; ?>" />

                              <?php } ?>

                              <input type="hidden" name="unixtime"  value="<?php echo time(); ?>" />

                              <?php if($registered_url != ""){?>

                                 <input type="hidden" name="rgst"  value="<?php echo $registered_id;?>" />
                              <?php } ?>
            

                              <?php
                            
                                //画像ファイルがある場合はここで対応
                                if(count($question_imp_array) > 0){

                                    foreach ($question_imp_array as $key => $value) {

                                        foreach ($value as $targt_key => $target_value) {
                                        

                                            $url = $target_value["URL"];
                                            $file_name = $target_value["FILE"];
                                            $file_path = $target_value["PATH"];
                                            $file_name_user = $target_value["FILE_NAME"];
                                ?>

                                     <input type="hidden" name="img_url[]"  value="<?php echo $url;?>" />
                                     <input type="hidden" name="img_file[]"  value="<?php echo $file_name;?>" />
                                     <input type="hidden" name="img_path[]"  value="<?php echo $file_path;?>" />
                                     <input type="hidden" name="img_file_user[]"  value="<?php echo $file_name_user;?>" />

                                <?php

                                        }
                                    }


                                }
                                ?>

                        </form>

                     </div>
                 </div>


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
                    <button class="input-send-button" id="submitBtn" type="button" 　>上記の情報を送信する</button>
                </div>

         


                <div class="orderform-return-cointens">

                    <form id="orderForm" name="returnForm" action="<?php echo  getURLSetSlag( "order-form" );?>?type_id=<?php echo $question_num . $registered_url;?><?php if(isset($_GET["member"])){ ?>&member=<?php echo $_GET["member"];?><?php } ?>" method="post" >


                        <?php foreach ($_POST as $key => $value) {?>


                                <?php if( is_array($value)){?>

                                    <?php foreach ($value as $array_key => $array_value) {?>

                                        <input type="hidden" name="<?php echo $key; ?>[]"  value="<?php echo $array_value; ?>" />

                                    <?php } ?>

                                <?php }else{ ?>
                                    <input type="hidden" name="<?php echo $key; ?>"  value="<?php echo $value; ?>" />
                                <?php } ?>

                        <?php } ?>


                            <?php
                            
                                //画像ファイルがある場合はここで対応
                                if(count($question_imp_array) > 0){

                                    foreach ($question_imp_array as $key => $value) {

                                        foreach ($value as $targt_key => $target_value) {
                                        

                                            $url = $target_value["URL"];
                                            $file_name = $target_value["FILE"];
                                            $file_path = $target_value["PATH"];
?>
                           

                                     <input type="hidden" name="img_url[]"  value="<?php echo $url;?>" />
                                     <input type="hidden" name="img_file[]"  value="<?php echo $file_name;?>" />
                                     <input type="hidden" name="img_path[]"  value="<?php echo $file_path;?>" />
                            <?php

                                        }
                                    }


                                }
                            ?>


                            <div class="orderform-return-send-area">
                                <input type="submit" value="入力に戻る" class="orderform-return-send"/>
                            </div>
                            <div class="orderform-return-alert-text">＊画像がある場合は再度、選択する必要があるのでご注意ください</div>
                    </form>

                </div>


            </div>

        </div>

    </div>

    <script>
        document.getElementById('submitBtn').addEventListener('click', function(event) {
            // フォーム要素を取得
            var form = document.getElementById('orderForm');
            // 必須入力項目を取得
            var requiredFields = form.querySelectorAll('[required]');
            var formIsValid = true;

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

            // フォームが無効な場合
            if (!formIsValid) {
                event.preventDefault(); // フォーム送信を防止
                alert('必須入力をしていない項目があります'); // ダイアログを表示
            } else {
                form.submit(); // フォーム送信を実行
            }
        });


        function validateNumberInput(input) {
            input.value = input.value.replace(/[^0-9]/g, "");
        }

    </script>

<?php } ?>