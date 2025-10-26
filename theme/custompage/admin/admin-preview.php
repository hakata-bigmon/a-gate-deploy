
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

    $customize_data = $spirit_sheet_data->getInputCustomizeData( $question_num );

   // echo "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
  //  var_dump($_POST);
?>



<div class="input-form-area">


    <div class="input-form-contens">


         <div class="input-form-header-img">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hituyou.png" alt="Image 1" style="width: 100%;">
         </div>


         <div class="input-form-main">

         
            <?php
            
                $title = get_field('acf_pure_spirit_disp_title',$question_num); 

                if($title == "")
                {
                    $title =  get_field('acf_pure_spirit_title',$question_num); 
                }
            
            ?>



             <div class="input-form-title">
                 <div class="input-form-title-str">【　<?php echo $title; ?>　】</div>
             </div>


             <?php if($_POST["acf_spirit_start_text"] !=  ""){ //開始文 ?>

                <div class="input-form-text-box">

                     <div class="input-form-text"><?php echo nl2br($spirit_sheet_data->changeInputCustomizeData( $_POST["acf_spirit_start_text"]));?></div>

                </div>


             <?php } ?>


             <?php if($_POST["acf_spirit_flow_text"] !=  ""){ //～〇〇流れ ?>

                <div class="input-form-flow-box">


                    <div class="input-form-flow-title">
                         <div class="input-form-flow-title-str"><?php echo get_field('acf_pure_spirit_title',$question_num); ?>の流れ</div>
                    </div>

                     <div class="input-form-flow-text"><?php echo nl2br($spirit_sheet_data->changeInputCustomizeData( $_POST["acf_spirit_flow_text"]));?></div>

                </div>


             <?php } ?>



             <?php if($_POST["acf_spirit_text_1"] !=  ""){ //テキスト差し込み1 ?>

                <div class="input-form-text-box">

                     <div class="input-form-text"><?php echo nl2br($spirit_sheet_data->changeInputCustomizeData( $_POST["acf_spirit_text_1"]));?></div>

                </div>


             <?php } ?>


             <?php if($_POST["acf_spirit_contact_information"] !=  ""){ //お問い合わせメール１ ?>


               

                <div class="input-form-text-box">

                     <div class="input-form-contact-str">■お問い合わせ先■</div>

                     <div class="input-form-text" ><?php echo nl2br($spirit_sheet_data->changeInputCustomizeData( $_POST["acf_spirit_contact_information"]));?></div>

                </div>


             <?php } ?>


              <?php if($_POST["acf_spirit_before_input"] !=  ""){ //フォームにご入力頂く前に ?>

                <div class="input-form-flow-box">


                    <div class="input-form-flow-title">
                         <div class="input-form-flow-title-str">フォームにご入力頂く前に</div>
                    </div>

                     <div class="input-form-flow-text"><?php echo nl2br($spirit_sheet_data->changeInputCustomizeData( $_POST["acf_spirit_before_input"]));?></div>

                </div>


             <?php } ?>


             <?php if($_POST["acf_spirit_text_2"] !=  ""){ //テキスト差し込み2 ?>

                <div class="input-form-text-box">

                     <div class="input-form-text" ><?php echo nl2br($spirit_sheet_data->changeInputCustomizeData( $_POST["acf_spirit_text_2"]));?></div>

                </div>


             <?php } ?>


             <?php if($_POST["acf_spirit_contact_information_2"] !=  ""){ //お問い合わせメール2 ?>


               

                <div class="input-form-text-box">

                     <div class="input-form-contact-str">■お問い合わせ先■</div>

                     <div class="input-form-text"><?php echo nl2br($spirit_sheet_data->changeInputCustomizeData( $_POST["acf_spirit_contact_information_2"]));?></div>

                </div>


             <?php } ?>


             <div class="admin-inputcustomize-message-area">

                    ここに個人の入力が部分が入ります

             </div>


             <?php if($_POST["acf_spirit_text_3"] !=  ""){ //テキスト差し込み2 ?>

                <div class="input-form-text-box">

                     <div class="input-form-text"><?php echo nl2br($spirit_sheet_data->changeInputCustomizeData( $_POST["acf_spirit_text_3"]));?></div>

                </div>


             <?php } ?>


             <?php 

                $note_count = 0;
             
               //注意が１つ以上あるかどうか
               if( $_POST["acf_spirit_note_1"] != "" )$note_count++;
               if( $_POST["acf_spirit_note_2"] != "" )$note_count++;
               if( $_POST["acf_spirit_note_3"] != "" )$note_count++;
               if( $_POST["acf_spirit_note_4"] != "" )$note_count++;
               if( $_POST["acf_spirit_note_5"] != "" )$note_count++;



               $note_disp_count = 1;
             
             ?>

             <?php if($note_count > 0){ //注意文章有 ?>

                <div class="input-form-note-area">

                    <div class="input-form-note-area-title">
                         <div class="input-form-note-area-title-str">【 ご注意 】</div>
                    </div>

                    <?php for($i=1;$i<6;$i++){?>
                
                        <?php if($_POST["acf_spirit_note_" .$i] !=  ""){ //注意１ ?>

                    
                             <?php if($note_count == 1){ //１つだけ ?>

                                <?php if( $_POST["acf_spirit_note_title_" .$i] !=  ""){ //注意タイトル ?>
                                    <div class="input-form-note-title-box">
                                        <div class="input-form-note-title-str"><?php echo $_POST["acf_spirit_note_title_" .$i]; ?></div>
                                    </div>
                                 <?php } ?>

                             <?php }else{ ?>

                            
                                <div class="input-form-note-title-box">
                                    <div class="input-form-note-title-str">～　その<?php echo $note_disp_count; $note_disp_count++; ?> <?php if( $_POST["acf_spirit_note_title_" .$i] !=  ""){ ?>【<?php echo $_POST["acf_spirit_note_title_" .$i]; ?>】<?php } ?>　～</div>
                                </div>


                             <?php } ?>

                            <div class="input-form-note-box">

                                 <div class="input-note-text"><?php echo nl2br($spirit_sheet_data->changeInputCustomizeData( $_POST["acf_spirit_note_" .$i]));?></div>

                            </div>


                         <?php } ?>

                    <?php } ?>


                </div>

             <?php } ?>



             <div class="input-send-button-area">
                <button class="input-send-button" type="button" >上記の内容で送信する</button>
            </div>

          </div>

    </div>

</div>


