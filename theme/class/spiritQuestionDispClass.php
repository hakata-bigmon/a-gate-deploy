<?php 

require_once ("spiritSheetClass.php");


//質問の表示class
class SpiritQuestionDispClass
{
	/****************************************************
	**  タイトル表示
	******************************************************/
	public function dispQuestionTitle(  $required , $title_text , $admin_only = false)
	{
?>
		<div class="orderform-question-title" style="margin-top: 10px;">
                                   
            <?php if($required){?>

                <div class="orderform-question-title-required-box">必須</div>

            <?php }else{ ?>

                <div class="orderform-question-title-left"> </div>

            <?php } ?>

            <div class="orderform-question-title-right">
                                    
                <?php echo $title_text;?><?php if($admin_only){?> (ユーザー非表示)<?php } ?>
                                    
            </div>

        </div>

<?php

	}

    /****************************************************
	**  質問別の表示
	******************************************************/
	public function dispQuestionByType( $userClass , $question_data , $question_result_arary , $disp_value , $chenge_status , $targetAllArray = array())
	{

        $type = $question_data["type"];
        $question_id = $question_data["ID"];
        $add_data = $question_data["add"];
        $required = $question_data["required"];
        $img_choice = $question_data["img_choice"];
       
        
        if($type == SpiritSheetClass::QUESTION_TYPE_TEXT || $type == ""){ //テキスト
                                   
            $this->dispQuestionText(  $question_id , $add_data , $required , $disp_value  , $chenge_status ); //テキスト

        }else if($type == SpiritSheetClass::QUESTION_TYPE_TEXT_AREA){ //テキストエリア
                                   
            $this->dispQuestionTextArea(  $question_id , $add_data , $required , $disp_value, $chenge_status ); //テキストエリア

        }else if($type == SpiritSheetClass::QUESTION_TYPE_CALENDAR){ //カレンダー

            $this->dispQuestionCalendar(  $question_id , $add_data , $required , $disp_value , $chenge_status); //カレンダー

        }else if($type == SpiritSheetClass::QUESTION_TYPE_NAME){ //名前

            $this->dispQuestionName(  $question_id , $add_data , $required , $disp_value , $chenge_status ); //名前

        }else if($type == SpiritSheetClass::QUESTION_TYPE_NUMBER){ //数字

            $this->dispQuestionNumber(  $question_id , $add_data , $required , $disp_value , $chenge_status ); //数字
                                      
        }else if($type == SpiritSheetClass::QUESTION_TYPE_MAIL){ //メール

            $this->dispQuestionMail(  $question_id , $add_data , $required , $disp_value , $chenge_status ); //メール

        }else if($type == SpiritSheetClass::QUESTION_TYPE_TEL){ //電話番号 

            $this->dispQuestionTell(  $question_id , $add_data , $required , $disp_value , $chenge_status); //電話番号
    
        }else if($type == SpiritSheetClass::QUESTION_TYPE_SELECT){ //選択

            $this->dispQuestionSelectBox(  $question_id , $add_data , $required , $disp_value , $chenge_status); //セレクトボックス
                                
        }else if($type == SpiritSheetClass::QUESTION_TYPE_RADIO){ //ラジオボックス

            $this->dispQuestionRadio(  $question_id , $add_data , $required , $disp_value , $chenge_status); //ラジオボックス

        }else if($type == SpiritSheetClass::QUESTION_TYPE_IMG_DATA){ //画像

            $this->dispQuestionImgUp( $question_id , $add_data , $required , $disp_value , $chenge_status);//画像

        }else if($type == SpiritSheetClass::QUESTION_TYPE_POST_ADDRESS){ //送付先

            //データを戻す
            $value_array = $userClass->getQuestionPostAddressArray();//配列取得

            if($disp_value != "")
            {
                $value_array = json_decode($disp_value, true);  //jsonデータ戻し
            }
                                    
            $this->dispQuestionPostAddress(  $targetAllArray  , $question_id , $add_data , $required , $value_array , $chenge_status);//送付先
        }
        else if($type == SpiritSheetClass::QUESTION_TYPE_IMG_CHOICE){ //画像選択

            $this->dispQuestionImgChoice(  $question_id , $question_result_arary , $img_choice , $required , $chenge_status);//画像選択

        }


    }


    /****************************************************
	**  申込者と対象者が違う場合の処理
	******************************************************/
	public function dispTargetOnOff( $required , $value ,$resdonly )
	{

?>


         <div class="orderform-input-radio" <? if(!$resdonly){?>style="margin-bottom: 10px;"<?php } ?>>
            <label style="display:block;">
                <input type="radio" name="input-applicant-check" id="input-applicant-check" value="0" <?php if($value == 0 ){ echo "checked";  } ?> <? if(!$resdonly){?>class="radio-readonly"<?php  echo " disabled";} ?>  <?php if($required){?>required <?php } ?>> <b>申込者と対象者が同じ</b>
            </label>
            <label style="display:block;    margin-top: 10px;">
                <input type="radio" name="input-applicant-check" id="input-applicant-check" value="1" <?php if( $value == 1){ echo "checked";  }  ?> <? if(!$resdonly){?>class="radio-readonly"<?php  echo " disabled";} ?> > <b>申込者と対象者が違う</b>
            </label>
         </div>


        <?php if($resdonly){ ?>
            <div class="orderform-input-calendar-message" style="    margin-top: 10px;">対象者が違う場合はこのチェック後に対象者入力項目が表示されます</div>
        <?php } ?>


<?php

    }

     /****************************************************
	**  対象者を上書きする
	******************************************************/
	public function dispTargetSave( $value)
	{

?>

         <div class="orderform-input-radio">
            <label style="display:block;">
                <input type="radio" name="input-applicant-save" id="input-applicant-save" value="0" <?php if($value == 0 ){ echo "checked";  } ?>> <b>作成（上書き）しない</b>
            </label>
            <label style="display:block;    margin-top: 10px;">
                <input type="radio" name="input-applicant-save" id="input-applicant-save" value="1" <?php if( $value == 1){ echo "checked";  } ?>> <b>作成（上書き）する</b>
            </label>
         </div>

         <div class="orderform-input-calendar-message" style="    margin-top: 10px;">メールアドレス、苗字、名前が同じ場合は、その情報に上書きされます</div>


<?php

    }

    /****************************************************
	**  質問のテキスト入力
	******************************************************/
	public function dispQuestionText(  $rquestion_id , $add_text , $required , $value ,$resdonly)
	{
?>

        <?php if($resdonly){?>

		    <div class="orderform-input-textbox">

                <input type="text" name="question_<?php echo $rquestion_id; ?>" id="question_<?php echo $rquestion_id; ?>" value="<?php echo $value ?>" placeholder="<?php echo $add_text;  ?>"    <?php if($required){?> required <?php } ?> />
                                             
            </div>

            <div class="orderform-input-textbox-placeholder-area">

                <div class="orderform-input-alert-text"><?php echo nl2br($add_text);  ?></div>
                                            
            </div>

        <?php }else{ ?>

             <div class="orderform-input-readonly-question">

                <?php echo $value; ?>
                                             
            </div>


        <?php } ?>
                                       
<?php

	}


     /****************************************************
	**  質問のテキストエリア
	******************************************************/
	public function dispQuestionTextArea(  $rquestion_id , $add_text , $required , $value , $resdonly)
	{

?>

         <?php if($resdonly){?>

		     <div class="orderform-input-texarea">
                <textarea id="question_<?php echo $rquestion_id; ?>" name="question_<?php echo $rquestion_id; ?>" rows="8" cols="33" style="width: 90%;border: 1px solid #000;"  <?php if($required){?>required <?php } ?>><?php echo $value; ?></textarea>
                <div class="orderform-input-alert-text"><?php echo nl2br($add_text);  ?></div>
                <div id="question_<?php echo $rquestion_id; ?>-error" class="error-container"></div>
            </div>

        <?php }else{ ?>

            <div class="orderform-input-readonly-textarea">

                <?php echo nl2br($value); ?>
                                             
            </div>

        <?php } ?>   
<?php

	}

    /****************************************************
	**  質問のカレンダー
	******************************************************/
	public function dispQuestionCalendar(  $rquestion_id , $add_text , $required , $value , $resdonly)
	{
?>

        <?php if($resdonly){?>

            <div class="orderform-input-textbox">
                <input type="date" name="question_<?php echo $rquestion_id; ?>" id="question_<?php echo $rquestion_id; ?>" value="<?php  echo $value; ?>" placeholder="<?php echo $add_text;  ?>"    <?php if($required){?>required <?php } ?> />                                 
            </div>

            <div class="orderform-input-textbox-placeholder-area">
                <div class="orderform-input-alert-text"><?php echo nl2br($add_text);  ?></div>
                                            
            </div>
                    
        <?php }else{ ?>

            <div class="orderform-input-readonly-question">

                <?php echo $value; ?>
                                             
            </div>

        <?php } ?>   

<?php

	}

    /****************************************************
	**  質問の名前
	******************************************************/
	public function dispQuestionName(  $rquestion_id , $add_text , $required , $value , $resdonly)
	{

        $name_sei = "";
        $name_mei = "";
                    
        if($value != "")
        {
            $nameStr = explode(",", $value);

            $name_sei = $nameStr[0];
            $name_mei = $nameStr[1];
        }

?>


        <?php if($resdonly){?>

            <div class="orderform-input-name-textbox">
                <div class="orderform-input-name-textbox-str-box">
                    <div class="orderform-input-name-textbox-str">姓:</div>
                    <input type="text" name="question_<?php echo $rquestion_id; ?>[]" id="question_<?php echo $rquestion_id; ?>" value="<?php  echo $name_sei;?>" placeholder="姓"  <?php if($required){?>required <?php } ?>/>
                </div>
                <div class="orderform-input-name-textbox-str-box">
                    <div class="orderform-input-name-textbox-str">名:</div>
                    <input type="text" name="question_<?php echo $rquestion_id; ?>[]" id="question_<?php echo $rquestion_id; ?>" value="<?php  echo $name_mei;  ?>" placeholder="名"  <?php if($required){?>required <?php } ?>/>
                </div>
            </div>

        <?php }else{ ?>

            <div class="orderform-input-readonly-question">

                姓: <?php echo $name_sei; ?>　名: <?php echo $name_mei; ?>
                                             
            </div>


        <?php } ?>                              

<?php

	}

    /****************************************************
	**  質問の数字
	******************************************************/
	public function dispQuestionNumber(  $rquestion_id , $add_text , $required , $value , $resdonly)
	{

?>

        <?php if($resdonly){?>

            <div class="orderform-input-textbox">
                <input type="number" name="question_<?php echo $rquestion_id; ?>" id="question_<?php echo $$rquestion_id; ?>" value="<?php  echo $value; ?>" placeholder=""  <?php if($required){?>required <?php } ?>/>　<?php echo $add_text;  ?>
            </div>

        <?php }else{ ?>

            <div class="orderform-input-readonly-question">

                 <?php echo $value; ?>　 <?php echo $add_text; ?>
                                             
            </div>
        <?php } ?>

<?php

	}


    /****************************************************
	**  質問のメールアドレス
	******************************************************/
	public function dispQuestionMail(  $rquestion_id , $add_text , $required , $value  , $resdonly)
	{

?>


        <?php if($resdonly){?>

            <div class="orderform-input-mail">
                <input type="mail" name="question_<?php echo $rquestion_id; ?>" id="question_<?php echo $rquestion_id; ?>" value="<?php  echo $value; ?>" placeholder="<?php echo $add_text;  ?>"  <?php if($required){?>required <?php } ?>/>　
            </div>

        <?php }else{ ?>

            <div class="orderform-input-readonly-question">

                 <?php echo $value; ?>
                                             
            </div>
        <?php } ?>
                                       
<?php

	}

    /****************************************************
	**  質問の電話番号
	******************************************************/
	public function dispQuestionTell(  $rquestion_id , $add_text , $required , $value , $resdonly)
	{

        $tel_1 = "";
        $tel_2 = "";
        $tel_3 = "";
                    
        if($value != "")
        {
            $telStr = explode(",", $value);

            $tel_1 = $telStr[0];
            $tel_2 = $telStr[1];
            $tel_3 = $telStr[2];
        }


?>

        <?php if($resdonly){?>

            <div class="orderform-input-tel">
                <input type="text" name="question_<?php echo $rquestion_id; ?>[]" id="question_<?php echo $rquestion_id; ?>" value="<?php  echo $tel_1;  ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')"  placeholder="数字のみ" <?php if($required){?>required <?php } ?>/>　-　
                <input type="text" name="question_<?php echo $rquestion_id; ?>[]" id="question_<?php echo $rquestion_id; ?>" value="<?php  echo $tel_2;  ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')"  placeholder="数字のみ" <?php if($required){?>required <?php } ?>/>　-　
                <input type="text" name="question_<?php echo $rquestion_id; ?>[]" id="question_<?php echo $rquestion_id; ?>" value="<?php  echo $tel_3;  ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')"  placeholder="数字のみ" <?php if($required){?>required <?php } ?>/>
            </div>
            <div class="orderform-input-alert-text"><?php echo nl2br($add_text);  ?></div>

        <?php }else{ ?>

            <div class="orderform-input-readonly-question">

                 <?php echo $tel_1; ?>　-　<?php echo $tel_2; ?>　-　<?php echo $tel_3; ?>
                                             
            </div>
        <?php } ?>
                                    
<?php

	}

    /****************************************************
	**  質問のセレクトボックス
	******************************************************/
	public function dispQuestionSelectBox(  $rquestion_id , $add_text , $required , $value  , $resdonly)
	{

        $select_text =  nl2br($add_text);

        $select_text = str_replace(array("\r\n", "\r", "\n"), "\n", $select_text);
        $select_array = array();
        $select_array = explode("\n", $select_text);


        $select_array = array_map(fn($array_value) => preg_replace('/<br\s*\/?>/i', '', $array_value), $select_array);

        $select_data = "";

        if($value == "")
        {
            $select_data = $select_array[0];
        }
       else{
        $select_data = $value;
       }
        
?>

        <?php if($resdonly){?>

            <div class="orderform-input-select">

                <select name="question_<?php echo $rquestion_id; ?>" id="question_<?php echo $rquestion_id; ?>" <?php if($required){?>required <?php } ?>>
                    <option value=""></option>
                <?php  foreach ($select_array as $select_key => $select_value) { ?>
                    <option value="<?php echo $select_value; ?>" <?php if( $select_data == $select_value ){ echo "selected"; } ?>><?php echo $select_value; ?></option>
                <?php } ?>


                </select>
            </div>

        <?php }else{ ?>

            <div class="orderform-input-readonly-question">

                 <?php echo $select_data; ?>
                                             
            </div>
        <?php } ?>
                                                                           
<?php

	}


    /****************************************************
	**  質問のラジオボタン
	******************************************************/
	public function dispQuestionRadio(  $rquestion_id , $add_text , $required , $value , $resdonly)
	{

        $select_text =  nl2br($add_text);

        $select_text = str_replace(array("\r\n", "\r", "\n"), "\n", $select_text);
        $select_array = array();
        $select_array = explode("\n", $select_text);

        $select_array = array_map(fn($array_value) => preg_replace('/<br\s*\/?>/i', '', $array_value), $select_array);

        $check_post = "";

        //var_dump($_POST["question_" .$value["ID"]]);

        if($value != ""){ 

            $check_post = $value;

        }
        else{
            $check_post = $select_array[0];
        }


       

?>
       <div class="orderform-input-radio">
            <?php  foreach ($select_array as $select_key => $select_value) { ?>
                <label style="margin-top: 10px;">
                    <input type="radio" name="question_<?php echo $rquestion_id; ?>" id="question_<?php echo $rquestion_id; ?>" value="<?php echo $select_value; ?>"  <?php if($check_post == $select_value  ){ echo "checked";} ?> <? if(!$resdonly){?>class="radio-readonly"<?php  echo " disabled";} ?>> <b><?php echo $select_value; ?></b>
                </label>
            <?php } ?>
                                           
        </div>
                                                                           
<?php

	}



    /****************************************************
	**  質問の画像アップロード
	******************************************************/
	public function dispQuestionImgUp(  $rquestion_id , $add_text , $required , $value , $resdonly)
	{

       
        $saved_images = array();

        //echo $rquestion_id;
        
        if($value != "")
        {
            $file = glob($value ."/*.*");;
              foreach ($file as $path) {
                
                $result = get_stylesheet_directory_uri()  ."/" . strstr($path, "user-img-folder"); // "is a test string."


                array_push($saved_images,$result);
            }
        }

        // var_dump($saved_images);
        

?>

<style>
    .preview-container {
        position: relative;
        display: inline-block;
        margin: 10px;
    }
    .preview-img {
        width: 100%;
        height: auto;
        object-fit: cover;
        border: 1px solid #ccc;
        max-width: 200px;
        margin-top: 10px;
    }
    .remove-btn {
        position: absolute;
        top: 10px;
        right: 5px;
        background: red;
        color: white;
        border: none;
        cursor: pointer;
    }
</style>

    <div class="" style="text-align: left;margin-top: 30px;">

        <div class="orderform-input-readonly-question" style="color: #454141;margin-bottom: 10px;">

            <?php echo $add_text;?>
        </div>

        <?php if($resdonly){?>
            <label>画像選択:
                <input type="file" id="imageInput<?php echo $rquestion_id;?>" name="question_<?php echo $rquestion_id; ?>[]" multiple accept="image/*"  <?php if($required){?>required <?php } ?>  <? if(!$resdonly){?>disabled<?php } ?> >
            </label>
        <?php }else{ ?>

            <input type="hidden" id="imageInput<?php echo $rquestion_id;?>" name="question_<?php echo $rquestion_id; ?>[]" multiple accept="image/*"  <?php if($required){?>required <?php } ?>  <? if(!$resdonly){?>disabled<?php } ?> >
        <?php } ?>

        <div id="imagePreview<?php echo $rquestion_id;?>"></div> 

        <!--- <div id="imagePreview<?php echo $question_id; ?>" class="image-preview-container" data-images='<?php echo json_encode($saved_images); ?>'></div>---->

    </div>

    <script>
        window.addEventListener("DOMContentLoaded", function() {
            const savedImages = <?php echo json_encode($saved_images); ?>;
            const rquestionId = <?php echo json_encode($rquestion_id); ?>;
            
            // 画像プレビューを表示（編集可能・不可能に関わらず）
            const previewContainer = document.getElementById(`imagePreview${rquestionId}`);
            if (previewContainer && savedImages.length > 0) {
                previewContainer.innerHTML = "";
                savedImages.forEach((imageUrl, index) => {
                    const imgDiv = document.createElement("div");
                    imgDiv.classList.add("preview-container");
                    
                    const img = document.createElement("img");
                    img.src = imageUrl;
                    img.classList.add("preview-img");
                    
                    imgDiv.appendChild(img);
                    previewContainer.appendChild(imgDiv);
                });
            }
            
            // 編集可能な場合のみinitImageUploadを呼び出す
            <?php if($resdonly){ ?>
                if (typeof initImageUpload === 'function') {
                    initImageUpload(savedImages, rquestionId);
                }
            <?php } ?>
        });
    </script>




<?php

	}




    /****************************************************
	**  送付先住所
	******************************************************/
	public function dispQuestionPostAddress(  $targetArray , $rquestion_id , $add_text , $required , $value , $resdonly)
	{

        

        $name_str = "question_" .$rquestion_id;

        


?>

        <?php if($resdonly){ //入力可能の時のみ ?>

            <?php if(count($targetArray) > 0){ //設定した対象者?>

                <div class="orderform-target-select-area">

                    <div class="orderform-target-select-flex">

                            <div class="orderform-target-select">

                                <select  name="input_target_set" id="categoryPostSelect" class="orderform-target-selectbox">
                                <option value=""></option>

                                    
                                <?php  foreach ($targetArray as $target_key => $target_value) {?>
                                    <option value="<?php echo $target_key;?>"><?php echo $target_value["フル名前"];?></option>
                                <?php } ?>

                                </select>

                            </div>

                            <div class="orderform-target-select-button">
                              
                            <div class="orderform-input-textbox">
                                <button type="button"  id="insertPostButton" onclick=""  class="target_button">登録している情報からコピーする</button>
                            </div>

                            </div>
                    </div>
                   
                </div>

            <?php } ?>

        <?php } ?>



        <input type="hidden" name="question_<?php echo $rquestion_id; ?>" value="" >


        <div class="orderform-input-post-title">送り先 名前<font color="red">(必須)</font></div>

        <div class="orderform-input-name-textbox">

            <?php if($resdonly){ ?>

                <div class="orderform-input-name-textbox-str-box">
                    <div class="orderform-input-name-textbox-str">姓:</div>
                    <input type="text" name="<?php echo $name_str; ?>_last_name" id="input_post_last_name" value="<?php echo $value["苗字"]; ?>" placeholder="姓"  <?php if($required){?>required <?php } ?>/>
                </div>
                <div class="orderform-input-name-textbox-str-box">
                    <div class="orderform-input-name-textbox-str">名:</div>
                    <input type="text" name="<?php echo $name_str; ?>_first_name" id="input_post_first_name" value="<?php echo $value["名前"]; ?>" placeholder="名"  <?php if($required){?>required <?php } ?>/>
                </div>

            <?php }else{ ?>


                <?php echo $value["苗字"]; ?>　<?php echo $value["名前"]; ?>

            <?php } ?>
        </div>


        <div class="orderform-input-post-title">送り先 連絡先<font color="red">(必須)</font></div>

        <div class="orderform-input-tel"  style="">

            <?php if($resdonly){ ?>

                <input type="text" name="<?php echo $name_str; ?>_billing_phone" id="input_post_tel_1" value="<?php echo $value["電話番号1"]; ?>" placeholder="例: 090" oninput="this.value = this.value.replace(/[^0-9]/g, '')"  <?php if($required){?>required <?php } ?>>　-　
                <input type="text" name="<?php echo $name_str; ?>_billing_phone2" id="input_post_tel_2" value="<?php echo $value["電話番号2"]; ?>" placeholder="例: 1234" oninput="this.value = this.value.replace(/[^0-9]/g, '')" <?php if($required){?>required <?php } ?>>　-　
                <input type="text" name="<?php echo $name_str; ?>_billing_phone3" id="input_post_tel_3" value="<?php echo $value["電話番号3"]; ?>" placeholder="例: 5678" oninput="this.value = this.value.replace(/[^0-9]/g, '')" <?php if($required){?>required <?php } ?>>

            <?php }else{ ?>


               <?php if($value["電話番号1"] != ""){ ?>
                    <?php echo $value["電話番号1"]; ?>
                <?php } ?>
                 <?php if($value["電話番号2"] != ""){ ?>
                    　-　<?php echo $value["電話番号2"]; ?>
                <?php } ?>
                 <?php if($value["電話番号3"] != ""){ ?>
                    　-　<?php echo $value["電話番号3"]; ?>
                <?php } ?>

            <?php } ?>


        </div>


        <div class="orderform-input-post-title">送り先 郵便番号<font color="red">(必須)</font></div>

        <div class="orderform-input-post-no">

            <?php if($resdonly){ ?>

                <input type="text" name="<?php echo $name_str; ?>_billing_postcode" id="input_post_zipcord" value="<?php echo $value["郵便番号"]; ?>" style="width: 80px;" placeholder="5550000" oninput="this.value = this.value.replace(/[^0-9]/g, '')" <?php if($required){?>required <?php } ?>/>
                <button type="button" id="button" onclick="getAddressArgumentData('<?php echo $name_str; ?>_billing_postcode','<?php echo $name_str; ?>_billing_city')"  class="target_button">検索</button>

            <?php }else{ ?>

                
                <?php echo $value["郵便番号"]; ?>

            <?php } ?>

        </div>

        
        <div class="orderform-input-post-title">送り先 住所<font color="red">(必須)</font></div>


        <?php if($resdonly){ ?>

            <div class="orderform-input-textbox"  style="">
                都道府県・市区町村(必須)
                <?php if($resdonly){ ?>
                    <input type="text" name="<?php echo $name_str; ?>_billing_city" id="input_post_address1" value="<?php echo $value["住所1"]; ?>" placeholder=""  <?php if($required){?>required <?php } ?>/>
                <?php } ?>
            </div>


            <div class="orderform-input-textbox" style="margin-top: 17px;">
                番地・マンション等
                <?php if($resdonly){ ?>
                    <input type="text" name="<?php echo $name_str; ?>_billing_address_1" id="input_post_address2" value="<?php echo $value["住所2"]; ?>" placeholder=""  />
                <?php } ?>
            </div>

        <?php }else{ ?>

            <div class="orderform-input-readonly">
                <?php echo $value[ "住所1" ];?> <?php echo $value[ "住所2" ];?>
            </div>

        <?php } ?>

        <div class="orderform-question-line"></div>
<?php

	}



    /****************************************************
	**  画像選択
	******************************************************/
	public function dispQuestionImgChoice(  $rquestion_id , $question_result_arary , $choice_id , $required , $resdonly)
	{

        $img_url = "";


        //echo $question_result_arary[$rquestion_id];

        if(isset($question_result_arary[$rquestion_id])){
            $img_url = get_field("acf_questionqnser_img_choice_url" ,  $question_result_arary[$rquestion_id]);
        }

        if(!$resdonly){ //読込時は画像の代表表示

        ?>

            <?php if($img_url == ""){ ?>

                <div class="orderform-input-readonly-question">
                    選択されている画像はありません
                </div>

            <?php }else{ ?>

                <div class="question-img-radio-box" style="margin-right: 5px;margin-left: 5px;">
                    <div class="question-img-radio-box-img" style="max-width: 150px;">
                        <img src="<?php echo $img_url; ?>" alt="" style="width: 100%;height: 100%;">
                    </div>
                </div>

            <?php } ?>

        <?php   
        }
        else
        {

        
            $saved_images = array();

            $disp_value = array();

            if($choice_id != "")
            {
                foreach($choice_id as $choice)
                {
                    if(isset($question_result_arary[$choice])){
                        $disp_value[] = get_field("acf_questionqnser_img_url" ,  $question_result_arary[$choice]);
                    }
                }
            }
            //echo $rquestion_id;
            

            if(count($disp_value) > 0)
            {
                foreach($disp_value as $value)
                {
                    $file = glob($value ."/*.*");

                    foreach ($file as $path) {

                        $result = get_stylesheet_directory_uri()  ."/" . strstr($path, "user-img-folder"); // "is a test string."
                        array_push($saved_images,$result);
                    }
                }
            }

    
        ?>

            <div id="">

                <div style="margin-bottom: 10px;margin-top: 10px;">使用する画像にチェックを入れてください</div>


                <div class="question-img-radio-box-container" style="display: flex; flex-wrap: wrap; align-items: flex-end;">
                    <?php foreach($saved_images as $image_url){
                        $relative_path = str_replace(get_stylesheet_directory_uri(), get_stylesheet_directory(), $image_url);
                        if (!file_exists($relative_path)) continue;
                    ?>
                        <div class="question-img-radio-box" style="margin-right: 5px;margin-left: 5px;">
                            <div class="question-img-radio-box-img" style="max-width: 150px;">
                                <img src="<?php echo $image_url; ?>" alt="" style="width: 100%;height: 100%;">
                            </div>
                            <div class="question-img-radio-box-text">
                                <input type="radio" name="question_<?php echo $rquestion_id; ?>" value="<?php echo $image_url; ?>" <?php if($img_url == $image_url){?>checked<?php } ?>>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div> 

        


        <?php
        }
    }




    /****************************************************************************************************
    **                      対象者の入力フォーム
    *****************************************************************************************************/
	function dispPersonalDataInputTargetForm($id,$data_array,$userData,$spiritData , $resdonly)
	{
		if($id == 491 || $id == 481)//名前
		{
            $this->dispPersonalDataInputTargetName( $userData , $spiritData , $resdonly );

		}
        else if($id == 492 || $id == 482)//名前(フリガナ)
		{
            $this->dispPersonalDataInputTargetNameKana( $userData , $spiritData  , $resdonly);
		}
        else if($id == 493 || $id == 483)//メール
		{
            $this->dispPersonalDataInputTargetMail( $userData , $spiritData , $data_array[$id][1]  , $resdonly);
		}
        else if($id == 494 || $id == 484)//LINE ID
		{
            $this->dispPersonalDataInputTargetLineID( $userData , $spiritData , $data_array[$id][1] , $resdonly);
		}
        else if($id == 495 || $id == 485)//郵便番号
		{
            $this->dispPersonalDataInputTargetZipcord( $userData , $spiritData , $data_array[$id][1] , $resdonly);
		}
        else if($id == 496 || $id == 486)//住所   
		{
            $this->dispPersonalDataInputTargetAddress( $userData , $spiritData , $data_array[$id][1] , $resdonly );

		}
        else if($id == 510 || $id == 509)//実家住所
		{
            $this->dispPersonalDataInputTargetParentsAddress( $userData , $spiritData , $data_array[$id][1] , $resdonly );
		}
        else if($id == 497 || $id == 487)//誕生日
		{
            $this->dispPersonalDataInputTargetBirthday( $userData , $spiritData , $data_array[$id][1] , $resdonly );
		}
        else if($id == 498 || $id == 488)//対象者の届け出
		{
            $this->dispPersonalDataInputTargetReportBirthday( $userData , $spiritData , $data_array[$id][1] , $resdonly);
		}
        else if($id == 502 || $id == 501)//電話番号
		{
             $this->dispPersonalDataInputTargetReportTel( $userData , $spiritData , $data_array[$id][1] , $resdonly);
		} 
        else if($id == 508 || $id == 507)//性別
		{

            $this->dispPersonalDataInputTargetSex( $userData , $spiritData ,  $data_array[$id][1] , $resdonly );

		}
        else if($id == 8525)//関係
		{
            $this->dispPersonalDataInputTargetRelationship( $userData , $spiritData ,  $data_array[$id][1] , $resdonly );

		}

	}

    /****************************************************************************************************
    **                      対象者の名前フォーム
    *****************************************************************************************************/
	function dispPersonalDataInputTargetName( $userData , $spiritData , $resdonly )
	{

?>
        <div class="orderform-input-name-textbox">


            <?php if($resdonly){ ?>

                <div class="orderform-input-name-textbox-str-box">
                    <div class="orderform-input-name-textbox-str">姓:</div>
                    <input type="text" name="acf_target_last_name" id="input_target_last_name" value="<?php echo $spiritData[ "苗字" ];?>" placeholder="姓"  class="required"/>

                </div>
                <div class="orderform-input-name-textbox-str-box">
                    <div class="orderform-input-name-textbox-str">名:</div>
                    <input type="text" name="acf_target_first_name" id="input_target_first_name" value="<?php  echo $spiritData[ "名前"]; ?>" placeholder="名"  class="required"/>
                </div>

            <?php }else{ ?>

                 <div class="orderform-input-readonly"><?php echo $spiritData[ "苗字" ];?>　 <?php echo $spiritData[ "名前" ];?> </div>

            <?php } ?>
        </div>
                                       
        <div id="input_target_name-error" class="error-container"></div>

<?php

    }

    /****************************************************************************************************
    **                      対象者のナマエフォーム
    *****************************************************************************************************/
	function dispPersonalDataInputTargetNameKana( $userData ,  $spiritData , $resdonly )
	{

?>
        <div class="orderform-input-name-textbox">


            <?php if($resdonly){ ?>

                <div class="orderform-input-name-textbox-str-box">
                    <div class="orderform-input-name-textbox-str">姓:</div>
                    <input type="text" name="acf_target_last_name_kana" id="input_target_last_name_kana" value="<?php echo $spiritData[ "ミョウジ" ];?>" placeholder="セイ"  class="required"/>
                </div>
                <div class="orderform-input-name-textbox-str-box">
                    <div class="orderform-input-name-textbox-str">名:</div>
                    <input type="text" name="acf_target_first_name_kana" id="input_target__first_name_kana" value="<?php echo $spiritData[ "ナマエ" ];?>" placeholder="メイ"  class="required"/>
                </div>

             <?php }else{ ?>

                  <div class="orderform-input-readonly"><?php echo $spiritData[ "ミョウジ" ];?>　 <?php echo $spiritData[ "ナマエ" ];?> </div>

             <?php } ?>
        </div>
                                       
        <div id="input_target_name_kana-error" class="error-container"></div>

<?php

    }

    /****************************************************************************************************
    **                      対象者のメール
    *****************************************************************************************************/
	function dispPersonalDataInputTargetMail( $userData , $spiritData , $required  , $resdonly)
	{

?>  
        <?php if($resdonly){ //編集時のみ ?>
            <div class="orderform-input-textbox">
                <button type="button" id="button" onclick="copyValue('<?php echo $userData[ "メール" ];?>','input_target_email')" class="target_button">申込者と同じ</button>
            </div>
        <?php } ?>

        <div class="orderform-input-mail" style="margin-top: 17px;">

            <?php if($resdonly){ ?>

                <input type="mail" name="acf_target_user_email" id="input_target_email" value="<?php echo $spiritData[ "メール" ];?>" placeholder=""  <?php if($required){?>class="required" <?php } ?>/>

             <?php }else{ ?>

                <div class="orderform-input-readonly">
                    <?php echo $spiritData[ "メール" ];?>
                </div>

             <?php } ?>
        </div>
                
        <?php if($resdonly){ //編集時のみ ?>
            <div class="orderform-input-calendar-message"><font color="red">@icloud.comは使用できません</font></div>
        <?php } ?>

        <div id="input_target_email-error" class="error-container"></div>


<?php

    }

    /****************************************************************************************************
    **                    対象者 LINE ID
    *****************************************************************************************************/
	function dispPersonalDataInputTargetLineID( $userData , $spiritData , $required , $resdonly)
	{

?>
        <div class="orderform-input-mail" style="margin-top: 17px;">

            <?php if($resdonly){ ?>

                <input type="text" name="acf_target_line_id" id="input_user_target_line_id" value="<?php echo $spiritData[ "LINEID" ];?>" placeholder="@からご入力ください"  oninput="this.value = this.value.replace(/[^a-zA-Z0-9._-]/g, ''" <?php if($required){?>class="required" <?php } ?>/>　
        
            <?php }else{ ?>

                <div class="orderform-input-readonly">
                    <?php echo $spiritData[ "LINEID" ];?>
                </div>

            <?php } ?>
                
        </div>
                                       
        <div id="input_user_target_line_id-error" class="error-container"></div>

<?php

    }

    /****************************************************************************************************
    **                    対象者 郵便番号
    *****************************************************************************************************/
	function dispPersonalDataInputTargetZipcord( $userData , $spiritData , $required , $resdonly)
	{

?>
         <div class="orderform-input-post-no">

            <?php if($resdonly){ ?>
            
                <input type="text" name="acf_target_billing_postcode" id="input_target_post_no" value="<?php echo $spiritData[ "郵便番号" ];?>" style="width: 80px;" placeholder="5550000" oninput="this.value = this.value.replace(/[^0-9]/g, '')" <?php if($required){?>class="required" <?php } ?> style=""/>
                <button type="button" id="button" onclick="getAddressArgument('input_target_post_no_1','input_target_post_no_2','input_target_address1')"  class="target_button">検索</button>

            <?php }else{ ?>

                <div class="orderform-input-readonly">
                    <?php echo $spiritData[ "郵便番号" ];?>
                </div>

            <?php } ?>


         </div>

                                       
        <div id="input_target_post_no-error" class="error-container"></div>

<?php

    }

    /****************************************************************************************************
    **                    対象者 住所
    *****************************************************************************************************/
	function dispPersonalDataInputTargetAddress( $userData , $spiritData , $required , $resdonly)
	{

?>

        <?php if($resdonly){ ?>
            <div class="orderform-input-textbox">
                <button type="button" id="button"  class="target_button" onclick="copyValue3('<?php echo $userData[ "郵便番号" ];?>','<?php echo $userData[ "住所1" ];?>','<?php echo $userData[ "住所2" ];?>','input_target_post_no','input_target_address1','input_target_address2')">申込者の住所と同じ</button>
            </div>
        <?php } ?>


        <?php if($resdonly){ ?>

            <div class="orderform-input-textbox"  style="margin-top: 17px;">
                都道府県・市区町村
                <input type="text" name="acf_target_billing_city" id="input_target_address1" value="<?php echo $spiritData[ "住所1" ];?>" placeholder=""  <?php if($required){?>class="required" <?php } ?> style=""/>
            </div>


            <div class="orderform-input-textbox" style="margin-top: 17px;">
                番地・マンション等
                <input type="text" name="acf_target_billing_address_1" id="input_target_address2" value="<?php echo $spiritData[ "住所2" ];?>" placeholder=""  />
            </div>

        <?php }else{ ?>

            <div class="orderform-input-readonly" style="text-align: left;">
                <?php echo $spiritData[ "住所1" ];?>　 <?php echo $spiritData[ "住所2" ];?>
            </div>

        <?php } ?>
                                       
        <div id="input_target_address-error" class="error-container"></div>

<?php

    }

    /****************************************************************************************************
    **                    対象者 実家住所
    *****************************************************************************************************/
	function dispPersonalDataInputTargetParentsAddress( $userData , $spiritData , $required , $resdonly)
	{

?>

        <?php if($resdonly){ ?>
            <div class="orderform-input-textbox">
                <button type="button" id="button"  class="target_button" onclick="copyValue3('<?php echo $userData[ "実家郵便番号" ];?>','<?php echo $userData[ "住所1" ];?>','<?php echo $userData[ "住所2" ];?>','input_target_post_no','input_target_address1','input_target_address2')">申込者の住所と同じ</button>
            </div>
        <?php } ?>


        <?php if($resdonly){ ?>

            <div class="orderform-input-textbox"  style="margin-top: 17px;">
                都道府県・市区町村
                <input type="text" name="acf_target_billing_city" id="input_target_address1" value="<?php echo $spiritData[ "実家住所1" ];?>" placeholder=""  <?php if($required){?>class="required" <?php } ?> style=""/>
            </div>


            <div class="orderform-input-textbox" style="margin-top: 17px;">
                番地・マンション等
                <input type="text" name="acf_target_billing_address_1" id="input_target_address2" value="<?php echo $spiritData[ "実家住所2" ];?>" placeholder=""  />
            </div>

        <?php }else{ ?>

            <div class="orderform-input-readonly" style="text-align: left;">
                <?php echo $spiritData[ "実家住所1" ];?>　 <?php echo $spiritData[ "実家住所2" ];?>
            </div>

        <?php } ?>
                                       
        <div id="input_target_address-error" class="error-container"></div>

<?php

    }


    /****************************************************************************************************
    **                    対象者 誕生日
    *****************************************************************************************************/
	function dispPersonalDataInputTargetBirthday( $userData , $spiritData , $required ,$resdonly)
	{

?>

         <div class="orderform-input-calendar">
            <div style="display: flex;">

                <?php if($resdonly){ ?>

                    <div>
                        <input type="number" name="acf_target_born_year" id="input_target_born_year" value="<?php echo $spiritData[ "誕生日年" ];?>"  min="1"   style="width: 60px;"        <?php if($required){?>class="required" <?php } ?>/>年
                    </div>
                    <div>
                        <input type="number" name="acf_target_born_month" id="input_target_born_month" value="<?php echo $spiritData[ "誕生日月" ];?>"  max="12" min="1" style="width: 40px;" <?php if($required){?>class="required" <?php } ?>/>月
                    </div>
                    <div>
                        <input type="number" name="acf_target_born_day" id="input_target_born_day" value="<?php echo $spiritData[ "誕生日日" ];?>"  max="31" min="1" style="width: 40px;" <?php if($required){?>class="required" <?php } ?>/>日
                    </div>

                 <?php }else{ ?>

                    <div class="orderform-input-readonly">
                        <?php echo $spiritData[ "誕生日年" ];?>年
                    </div>
                    <div class="orderform-input-readonly">
                        <?php echo $spiritData[ "誕生日月" ];?>月
                    </div>
                    <div class="orderform-input-readonly">
                        <?php echo $spiritData[ "誕生日日" ];?>日
                    </div>

                <?php } ?>

            </div>
         </div>


        
                                       
        <div id="input_target_address-error" class="error-container"></div>

<?php

    }

     /****************************************************************************************************
    **                    対象者 届け出
    *****************************************************************************************************/
	function dispPersonalDataInputTargetReportBirthday( $userData , $spiritData , $required , $resdonly )
	{

?>

         <div class="orderform-input-calendar">
            <div style="display: flex;">


                <?php if($resdonly){ ?>

                    <div>
                        <input type="number" name="acf_target_report_born_year" id="input_target_report_born_year" value="<?php echo $spiritData[ "届け出日年" ];?>"  min="1"     style="width: 60px;"      <?php if($required){?>class="required" <?php } ?>/>年
                    </div>
                    <div>
                        <input type="number" name="acf_target_report_born_month" id="input_target_report_born_month" value="<?php echo $spiritData[ "届け出日月" ];?>"  max="12" min="1" style="width: 40px;" <?php if($required){?>class="required" <?php } ?>/>月
                    </div>
                    <div>
                        <input type="number" name="acf_target_report_born_day" id="input_target_report_born_day" value="<?php echo $spiritData[ "届け出日日" ];?>"  max="31" min="1" style="width: 40px;" <?php if($required){?>class="required" <?php } ?>/>日
                    </div>

                <?php }else if($spiritData[ "届け出日年" ] != ""){ ?>

                    <div class="orderform-input-readonly">
                        <?php echo $spiritData[ "届け出日年" ];?>年
                    </div>
                    <div class="orderform-input-readonly">
                        <?php echo $spiritData[ "届け出日月" ];?>月
                    </div>
                    <div class="orderform-input-readonly">
                        <?php echo $spiritData[ "届け出日日" ];?>日
                    </div>

                <?php } ?>

            </div>
         </div>


        
                                       
        <div id="input_target_address-error" class="error-container"></div>

<?php

    }

     /****************************************************************************************************
    **                    対象者 電話番号
    *****************************************************************************************************/
	function dispPersonalDataInputTargetReportTel( $userData , $spiritData , $required , $resdonly)
	{

?>

        <?php if($resdonly){ ?>

            <div class="orderform-input-textbox">
                <button type="button" id="button" onclick="copyValue3('<?php echo $userData[ "電話番号1" ];?>','<?php echo $userData[ "電話番号2" ];?>','<?php echo $userData[ "電話番号3" ];?>','input_target_tel_1','input_target_tel_2','input_target_tel_3')"  class="target_button">申込者と同じ</button>
            </div>
        
        <?php } ?>



        <div class="orderform-input-tel"  style="margin-top: 17px;">

            <?php if($resdonly){ ?>

                <input type="text" name="acf_target_billing_phone" id="input_target_tel_1" value="<?php echo $spiritData[ "電話番号1" ];?>" placeholder="例: 090" oninput="this.value = this.value.replace(/[^0-9]/g, '')"  <?php if($required){?>class="required" <?php } ?>>　-　
                <input type="text" name="acf_target_billing_phone2" id="input_target_tel_2" value="<?php echo $spiritData[ "電話番号2" ];?>" placeholder="例: 1234" oninput="this.value = this.value.replace(/[^0-9]/g, '')" <?php if($required){?>class="required" <?php } ?>>　-　
                <input type="text" name="acf_target_billing_phone3" id="input_target_tel_3" value="<?php echo $spiritData[ "電話番号3" ];?>" placeholder="例: 5678" oninput="this.value = this.value.replace(/[^0-9]/g, '')" <?php if($required){?>class="required" <?php } ?>>

            <?php }else{ ?>


                <div style="display: flex;">
                    <div class="orderform-input-readonly">
                        <?php echo $spiritData[ "電話番号1" ];?>　-　
                    </div>
                    <div class="orderform-input-readonly">
                        <?php echo $spiritData[ "電話番号2" ];?>　-　
                    </div>
                    <div class="orderform-input-readonly">
                        <?php echo $spiritData[ "電話番号3" ];?>
                    </div>
                </div>

            <?php } ?>


        </div>

        <div id="input_target_tel-error" class="error-container"></div>

<?php

    }

    /****************************************************************************************************
    **                    対象者 性別
    *****************************************************************************************************/
	function dispPersonalDataInputTargetSex( $userData , $spiritData , $required , $resdonly)
	{

?>

        <div class="orderform-input-select">
                

            <?php if($resdonly){ ?>
                <select  name="acf_target_sex" id="input_target_sex" <?php if($required){?>class="required" <?php } ?>>
                    <option value=""></option>
                    <option value="M" <?php if($spiritData[ "性別値" ] == "M") echo "selected"; ?>>男性</option>
                    <option value="W" <?php if($spiritData[ "性別値" ] == "W") echo "selected"; ?>>女性</option>
                    <?php if(!$required){ ?>
                        <option value="U" <?php if ($spiritData[ "性別値" ] == "U") echo "selected"; ?>>未設定</option>
                    <?php } ?>
                </select>

             <?php }else{ ?>

                <div class="orderform-input-readonly">
                    <?php echo $spiritData[ "性別" ];?>
                </div>
             <?php } ?>

        </div>

        <div id="input_target_sex-error" class="error-container"></div>

<?php

    }

    /****************************************************************************************************
    **                    対象者 申込者との関係
    *****************************************************************************************************/
	function dispPersonalDataInputTargetRelationship( $userData , $spiritData , $required , $resdonly)
	{

?>
        <div class="orderform-input-textbox" style="margin-top: 17px;">

            <?php if($resdonly){ ?>

                <input type="text" name="acf_target_relationship" id="input_target_relationship" value="<?php echo $spiritData[ "関係" ];?>" placeholder="@からご入力ください"  oninput="this.value = this.value.replace(/[^a-zA-Z0-9._-]/g, ''" <?php if($required){?>class="required" <?php } ?>/>　
        
            <?php }else{ ?>

                <div class="orderform-input-readonly">
                    <?php echo $spiritData[ "関係" ];?>
                </div>

            <?php } ?>
                
        </div>
                                       
        <div id="input_user_target_relationship-error" class="error-container"></div>

<?php

    }
    /****************************************************************************************************
    **                    リモート浄霊　名前
    *****************************************************************************************************/
	function dispRamoteSpiritName( $rquestion_id , $target_id , $add_text , $required , $value , $resdonly)
	{

?>
        <div class="orderform-question-title">
                                   
                                    
            <?php if($required){?>
                    <div class="orderform-question-title-required-box">必須</div>
            <?php }else{ ?>


                <div class="orderform-question-title-left"> </div>

            <?php } ?>

            <div class="orderform-question-title-right">対象者<?php echo $target_id;?>の氏名</div>

        </div>

        <div class="orderform-input-name-textbox">

            <?php if($resdonly){ ?>

                <div class="orderform-input-name-textbox-str-box">
                    <div class="orderform-input-name-textbox-str">姓:</div>
                    <input type="text" name="question_<?php echo $rquestion_id ."_" .$target_id;  ?>_sei" id="question_<?php echo $rquestion_id ."_" .$target_id; ?>_sei" value="<?php echo $value["苗字"]; ?>" placeholder="姓" <?php if($required){?>required <?php } ?> />
                </div>
                <div class="orderform-input-name-textbox-str-box">
                    <div class="orderform-input-name-textbox-str">名:</div>
                    <input type="text" name="question_<?php echo $rquestion_id ."_" .$target_id;  ?>_mei" id="question_<?php echo $rquestion_id ."_" .$target_id; ?>_mei" value="<?php echo $value["名前"]; ?>" placeholder="名"  <?php if($required){?>required <?php } ?> />
                </div>

            <?php }else{ ?>

                <?php echo $value["苗字"]; ?>　<?php echo $value["名前"]; ?>

            <?php }?>

        </div>

<?php

    }

    /****************************************************************************************************
    **                    リモート浄霊　フリガナ
    *****************************************************************************************************/
	function dispRamoteSpiritNameKana( $rquestion_id , $target_id , $add_text , $required , $value , $resdonly)
	{

?>
        <div class="orderform-question-title">
                                   
                                    
            <?php if($required){?>
                    <div class="orderform-question-title-required-box">必須</div>
            <?php }else{ ?>


                <div class="orderform-question-title-left"> </div>

            <?php } ?>

            <div class="orderform-question-title-right">対象者<?php echo $target_id;?>の氏名(フリガナ)</div>

        </div>

        <div class="orderform-input-name-textbox">


            <?php if($resdonly){ ?>

                <div class="orderform-input-name-textbox-str-box">
                    <div class="orderform-input-name-textbox-str">姓:</div>
                    <input type="text" name="question_<?php echo $rquestion_id ."_" .$target_id;  ?>_sei_kana" id="question_<?php echo $rquestion_id ."_" .$target_id; ?>_sei_kana" value="<?php echo $value["ミョウジ"]; ?>" placeholder="セイ" <?php if($required){?>required <?php } ?> />
                </div>
                <div class="orderform-input-name-textbox-str-box">
                    <div class="orderform-input-name-textbox-str">名:</div>
                    <input type="text" name="question_<?php echo $rquestion_id ."_" .$target_id;  ?>_mei_kana" id="question_<?php echo $rquestion_id ."_" .$target_id; ?>_mei_kana" value="<?php echo $value["ナマエ"]; ?>" placeholder="メイ"  <?php if($required){?>required <?php } ?> />
                </div>

            <?php }else{ ?>

                <?php echo $value["ミョウジ"]; ?>　<?php echo $value["ナマエ"]; ?>

            <?php }?>

        </div>

<?php

    }

    /****************************************************************************************************
    **                    リモート浄霊　関係
    *****************************************************************************************************/
	function dispRamoteSpiritRelationship( $rquestion_id , $target_id , $add_text , $required , $value , $resdonly)
	{

?>
        <div class="orderform-question-title">
                                   
                                    
            <?php if($required){?>
                    <div class="orderform-question-title-required-box">必須</div>
            <?php }else{ ?>


                <div class="orderform-question-title-left"> </div>

            <?php } ?>

            <div class="orderform-question-title-right">対象者<?php echo $target_id;?>と申込者の関係</div>

        </div>

       <div class="orderform-input-textbox">

            <?php if($resdonly){ ?>

                <input type="text" name="question_<?php echo $rquestion_id  ."_" .$target_id ."_parents" ; ?>" id="question_<?php echo $rquestion_id  ."_" .$target_id; ?>_parents" value="<?php  echo $value["関係"]; ?>" placeholder="本人、長男、母親など"    <?php if($required){?>required <?php } ?> />
       
            <?php }else{ ?>

                <?php echo $value["関係"]; ?>

            <?php }?>
       </div>

<?php

    }
    
    /****************************************************************************************************
    **                    リモート浄霊　生年月日
    *****************************************************************************************************/
	function dispRamoteSpiritBirthday( $rquestion_id , $target_id , $add_text , $required , $value , $resdonly)
	{

?>
        <div class="orderform-question-title">
                                   
                                    
            <?php if($required){?>
                    <div class="orderform-question-title-required-box">必須</div>
            <?php }else{ ?>


                <div class="orderform-question-title-left"> </div>

            <?php } ?>

            <div class="orderform-question-title-right">対象者<?php echo $target_id;?>の生年月日</div>

        </div>


        
        <div class="orderform-input-calendar">
            <div style="display: flex;">


                 <?php if($resdonly){ ?>

                        <div>
                            <input type="number" name="question_<?php echo $rquestion_id  ."_" .$target_id; ?>_born_year" id="question_<?php echo $rquestion_id  ."_" .$target_id; ?>_born_year" value="<?php echo $value[ "誕生日年" ];?>"  min="1"   style="width: 60px;"        <?php if($required){?>class="required" <?php } ?>/>年
                        </div>
                        <div>
                            <input type="number" name="question_<?php echo $rquestion_id  ."_" .$target_id; ?>_born_month" id="question_<?php echo $rquestion_id  ."_" .$target_id; ?>_born_month" value="<?php echo $value[ "誕生日月" ];?>"  max="12" min="1" style="width: 40px;" <?php if($required){?>class="required" <?php } ?>/>月
                        </div>
                        <div>
                            <input type="number" name="question_<?php echo $rquestion_id  ."_" .$target_id; ?>_born_day" id="question_<?php echo $rquestion_id  ."_" .$target_id; ?>_born_day" value="<?php echo $value[ "誕生日日" ];?>"  max="31" min="1" style="width: 40px;" <?php if($required){?>class="required" <?php } ?>/>日
                        </div>

                <?php }else{ ?>

                    <?php echo $value["誕生日年"]; ?>年<?php echo $value["誕生日月"]; ?>月<?php echo $value["誕生日日"]; ?>日

                <?php }?>


            </div>
         </div>

<?php

    }

     /****************************************************
	**  リモート浄霊　画像アップロード
	******************************************************/
	public function dispRamoteSpiritImgUp(  $rquestion_id , $target_id , $img_number , $add_text , $required , $value , $resdonly , $title)
	{

        $saved_images = array();

        //echo $value;
        
        if($value["画像" . $img_number] != "")
        {
            $file = glob($value["画像"  . $img_number] ."/*.*");
              foreach ($file as $path) {
                
                $result = get_stylesheet_directory_uri()  ."/" . strstr($path, "user-img-folder"); // "is a test string."


                array_push($saved_images,$result);
            }
        }

       //  var_dump($saved_images);
        
      
?>

<style>
    .preview-container {
        position: relative;
        display: inline-block;
        margin: 10px;
    }
    .preview-img {
        width: 100%;
        height: auto;
        object-fit: cover;
        border: 1px solid #ccc;
        max-width: 200px;
        margin-top: 10px;
    }
    .remove-btn {
        position: absolute;
        top: 10px;
        right: 5px;
        background: red;
        color: white;
        border: none;
        cursor: pointer;
    }
</style>


     <div class="orderform-question-title">
                                   
                                    
        <?php if($required){?>
                <div class="orderform-question-title-required-box">必須</div>
        <?php }else{ ?>


            <div class="orderform-question-title-left"> </div>

        <?php } ?>

        <div class="orderform-question-title-right">対象者<?php echo $target_id;?>の画像<?php echo $title;?></div>

    </div>


    <div class="" style="text-align: left;margin-top: 30px;">

        

        <?php if($resdonly){ ?>
            <label>画像選択:
                <input type="file" id="imageInput<?php echo $rquestion_id  ."_" .$target_id ."_" .$img_number; ?>" name="question_<?php echo $rquestion_id  ."_" .$target_id ."_" .$img_number; ?>[]" multiple accept="image/*"  <?php if($required){?>required <?php } ?>  <? if(!$resdonly){?>disabled<?php } ?> >
            </label>
        <?php }else{ ?>
             <input type="hidden" id="imageInput<?php echo $rquestion_id  ."_" .$target_id ."_" .$img_number; ?>" name="question_<?php echo $rquestion_id  ."_" .$target_id ."_" .$img_number; ?>[]" multiple accept="image/*"  <?php if($required){?>required <?php } ?>  <? if(!$resdonly){?>disabled<?php } ?> >
        <?php } ?>




        <div id="imagePreview<?php echo $rquestion_id  ."_" .$target_id ."_" .$img_number;?>"></div> 

    </div>

    <script>
        window.addEventListener("DOMContentLoaded", function() {
            const savedImages = <?php echo json_encode($saved_images); ?>;
            const rquestionId = <?php echo json_encode($rquestion_id  ."_" .$target_id ."_" .$img_number); ?>;
            
            // 画像プレビューを表示（編集可能・不可能に関わらず）
            const previewContainer = document.getElementById(`imagePreview${rquestionId}`);
            if (previewContainer && savedImages.length > 0) {
                previewContainer.innerHTML = "";
                savedImages.forEach((imageUrl, index) => {
                    const imgDiv = document.createElement("div");
                    imgDiv.classList.add("preview-container");
                    
                    const img = document.createElement("img");
                    img.src = imageUrl;
                    img.classList.add("preview-img");
                    
                    imgDiv.appendChild(img);
                    previewContainer.appendChild(imgDiv);
                });
            }
            
            // 編集可能な場合のみinitImageUploadを呼び出す
            <?php if($resdonly){ ?>
                if (typeof initImageUpload === 'function') {
                    initImageUpload(savedImages, rquestionId);
                }
            <?php } ?>
        });
    </script>
<?php

	}

}









?>


