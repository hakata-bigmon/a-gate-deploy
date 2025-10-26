
<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritInputCustomizeClass.php");


    $spirit_sheet_data = new SpiritInputCustomizeClass(); //管理データ



    $question_num = 0;

    if(isset($_GET["type_id"]))
    {
        $question_num = $_GET["type_id"];
    }
    else if(isset($_POST["type_id"]))
    {
        $question_num = $_POST["type_id"];
    }

    $customize_data = $spirit_sheet_data->getThanksCustomizeData( $question_num );


    $post_contens = get_post_field('post_content', $customize_data["acf_thanks_photo_text_id"]);


   // echo $post_contens;
   // echo "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
  //  var_dump($_POST);
?>



<div class="input-form-area">


    <div class="input-form-contens">


         <div class="input-form-header-img">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hituyou-thanks.png" alt="Image 1" style="width: 100%;">
         </div>


         <div class="input-form-main">


             <div class="input-form-title">
                 <div class="input-form-title-str">【　<?php echo get_field('acf_pure_spirit_title',$question_num); ?>　】</div>
             </div>


             <?php if($_POST["acf_thanks_spirit_start_text"] !=  ""){ //開始文 ?>

                <div class="input-form-text-box">

                     <div class="input-form-text"><?php echo nl2br($spirit_sheet_data->changeInputCustomizeData( $_POST["acf_thanks_spirit_start_text"]));?></div>

                </div>


             <?php } ?>


             <div class="input-form-id-box">

                 <div class="input-form-id-flex">
                    <div class="input-form-id-text">
                        お客様のIDは
                    </div>
                    <div class="input-form-id">
                        〇〇〇〇〇〇
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
                         <div class="input-form-flow-title-str"><?php echo get_field('acf_pure_spirit_title',$question_num); ?>に必要な写真</div>
                    </div>

                     <div class="input-form-flow-text"><?php echo  wp_kses($post_contens, $allowed_tags);?></div>

                </div>


             <?php } ?>


              <?php if($_POST["acf_thanks_photo_text"] !=  ""){ //～〇〇に必要な写真(詳細) ?>

                <div class="input-form-flow-box">


                    <div class="input-form-flow-title">
                         <div class="input-form-flow-title-str"><?php echo get_field('acf_pure_spirit_title',$question_num); ?>に必要な写真</div>
                    </div>

                     <div class="input-form-flow-text"><?php echo nl2br($spirit_sheet_data->changeInputCustomizeData( $_POST["acf_thanks_photo_text"]));?></div>

                </div>


             <?php } ?>



             <?php if($_POST["acf_thanks_text_1"] !=  ""){ //テキスト差し込み1 ?>

                <div class="input-form-text-box">

                     <div class="input-form-text" <?php if($_POST["acf_thanks_text_left_1"] !=  ""){?> style="text-align: left;" <?php } ?>><?php echo nl2br($spirit_sheet_data->changeInputCustomizeData( $_POST["acf_thanks_text_1"]));?></div>

                </div>


             <?php } ?>


             <?php if($_POST["acf_thanks_contact_information_1"] !=  ""){ //お問い合わせメール１ ?>


               

                <div class="input-form-text-box">

                     <div class="input-form-contact-str">■お問い合わせ先■</div>

                     <div class="input-form-text"><?php echo nl2br($spirit_sheet_data->changeInputCustomizeData( $_POST["acf_thanks_contact_information_1"]));?></div>

                </div>


             <?php } ?>


              <?php if($_POST["acf_thanks_notes"] !=  ""){ //注意事項 ?>

                <div class="input-form-flow-box">


                    <div class="input-form-flow-title">
                         <div class="input-form-flow-title-str">注意事項</div>
                    </div>

                     <div class="input-form-flow-text"><?php echo nl2br($spirit_sheet_data->changeInputCustomizeData( $_POST["acf_thanks_notes"]));?></div>

                </div>


             <?php } ?>


             <?php if($_POST["acf_thanks_text_2"] !=  ""){ //テキスト差し込み2 ?>

                <div class="input-form-text-box">

                     <div class="input-form-text" <?php if($_POST["acf_thanks_text_left_2"] !=  ""){?> style="text-align: left;" <?php } ?>><?php echo nl2br($spirit_sheet_data->changeInputCustomizeData( $_POST["acf_thanks_text_2"]));?></div>

                </div>


             <?php } ?>


             <?php if($_POST["acf_thanks_contact_information_2"] !=  ""){ //お問い合わせメール2 ?>


               

                <div class="input-form-text-box">

                     <div class="input-form-contact-str">■お問い合わせ先■</div>

                     <div class="input-form-text"><?php echo nl2br($spirit_sheet_data->changeInputCustomizeData( $_POST["acf_thanks_contact_information_2"]));?></div>

                </div>


             <?php } ?>


            
          </div>

    </div>

</div>


