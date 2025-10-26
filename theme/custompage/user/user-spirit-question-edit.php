<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritQuestionDispClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritInputCustomizeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");

    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);


    //シートID
    $sheet_id = "";
    if(isset($_POST["sheet_id"]))
    {
        $sheet_id = $_POST["sheet_id"];
    }
  

    //情報がとれてない
    if($sheet_id == "")
    {
?>

        <div class="user-jorei-section-title-wrap" style="margin-bottom: 400px;margin-top: 50px;">
            <div class="user-jorei-section-title">エラーが発生しました</div>
        </div>
        
        



<?php
        return;
    }

    $userClass = new SpiritUserClass(); //ユーザー管理
    $spirit_sheet_data = new spiritSheetClass(); //質問データ
    $disp_questiont_class = new SpiritQuestionDispClass(); //表示データ
    $spirit_customize_data = new SpiritInputCustomizeClass(); //文字データ

    //内容修正のため、入力未入力に戻す
    if(isset($_POST["edit_return"]))
    {
        update_field("acf_purespirit_user_status",SpiritUserClass::MEMBER_STATUS_NOT_INFOMATION,$sheet_id);//確認待ち
    }
   

    $spiritData = $userClass->getUserSpritApplicantSheet($user_id,$sheet_id);//浄霊情報
    $spiritSheetArray = $spirit_sheet_data->getSpiritQuestion($spiritData[$sheet_id]["質問"]);//質問データ
    //現在の質問番号を取得
  
    //対象者用
    $set_target_id = "";
   


    
    //施術データ
    $spiritType = new SpiritTypeClass(); //施術データ
    $spiritTypeArray = $spiritType->getSpiritTypeKeyTypeNum();
   
  // var_dump($spiritSheetArray[$spiritData[$sheet_id]["質問"]]);

    //保存は入力の時のみ
    if(isset($_POST["save_data"]) &&
         (($spiritData[$sheet_id]["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_NOT_INFOMATION 
            || $spiritData[$sheet_id]["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_RE_INFOMATION) || current_user_can('administrator')))
    {

       // var_dump($_POST);

        //対象者
        if(isset($_POST["input-applicant-check"]))
        {


            if($_POST["input-applicant-check"] == 0)//対象者OFF
            {
                $spirit_sheet_data->resetSheetTagetData($sheet_id);
            }
            else if($_POST["input-applicant-check"] == 1)//対象者ON
            {
                $spirit_sheet_data->setSheetTagetData($sheet_id,$_POST);
            }

            //対象者情報の作成上書き
            if($_POST["input-applicant-save"] == 1)
            {
                $set_target_id = $userClass->saveTargetQuestiontData($user_id,$_POST); //保存・上書き
            }
        }


        $anser_number = array();
        $anser = "";

        //f($spiritData[$sheet_id]["質問"] != 77) //リモート浄霊以外
        {
            //回答配列を取得
            $anser = $spirit_sheet_data->getSpiritSheetAnswer($sheet_id );
        }

        //var_dump($spiritSheetArray[$spiritData[$sheet_id]["質問"]]);
        
        foreach ($spiritSheetArray[$spiritData[$sheet_id]["質問"]] as $key => $value) {

            if(isset($_POST["question_" .$value["ID"]]))
            {
                //新規保存（ある場合は上書き）
                $update_text = $_POST["question_" .$value["ID"]];

                if($value["type"] == SpiritSheetClass::QUESTION_TYPE_POST_ADDRESS){ //送付先

                    //送付用情報に変更する
                    $post_address_array = $userClass->getQuestionPostAddressArray();//配列取得

                    $post_address_array["苗字"] =  $_POST["question_" .$value["ID"] ."_last_name"];
                    $post_address_array["名前"] =  $_POST["question_" .$value["ID"] ."_first_name"];
                    $post_address_array["電話番号1"] =  $_POST["question_" .$value["ID"] ."_billing_phone"];
                    $post_address_array["電話番号2"] =  $_POST["question_" .$value["ID"] ."_billing_phone2"];
                    $post_address_array["電話番号3"] =  $_POST["question_" .$value["ID"] ."_billing_phone3"];
                    $post_address_array["郵便番号"] =  $_POST["question_" .$value["ID"] ."_billing_postcode"];
                    $post_address_array["住所1"] =  $_POST["question_" .$value["ID"] ."_billing_city"];
                    $post_address_array["住所2"] =  $_POST["question_" .$value["ID"] ."_billing_address_1"];

                    $json_data = json_encode($post_address_array, JSON_UNESCAPED_UNICODE);

                    $update_text = $json_data;

                }

                // 質問更新確認
                if(in_array($value["ID"], array_keys($anser)) && CheckSpiritPostData($value["ID"], $anser[$value["ID"]])){
                    
                    $update_field['質問事項'] = true;
                }
                $question_number = $spirit_sheet_data->saveQuestionAnser(  $user_id , $sheet_id , $value["ID"] , $value["type"] , $update_text );


                
                $anser_number[$value["ID"]] = $question_number;

                //array_push($anser_number , $question_number);

            }
            else if($value["type"] == SpiritSheetClass::QUESTION_TYPE_IMG_DATA){ //画像

                if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["question_" .$value["ID"]])) {


                     $upload_dir = dirname(__FILE__) . "/../../user-img-folder";
                     $upload_user_dir = dirname(__FILE__) . "/../../user-img-folder/" .$user_id;
                     $upload_sheet_dir = $upload_user_dir . "/" .$sheet_id;
                     $upload_question_dir = $upload_sheet_dir . "/" .$value["ID"];

                     //echo $upload_user_dir ."<br>";

                     //ユーザーフォルダ作成
                     if (!file_exists($upload_user_dir)) {
                         mkdir($upload_user_dir, 0777, true);
                     }

                     //シートフォルダ作成
                     if (!file_exists($upload_sheet_dir)) {
                         mkdir($upload_sheet_dir, 0777, true);
                     }

                     //質問フォルダ作成
                     if (!file_exists($upload_question_dir)) {
                         mkdir($upload_question_dir, 0777, true);
                     }


                     //質問フォルダ内のファイルをいったん削除
                     if (is_dir($upload_question_dir)) {
                        foreach (glob($upload_question_dir . "/" . "*") as $file) {
                            if (is_file($file)) {
                                unlink($file); // ファイル削除
                            }
                        }
                     }
                  
                    //保存
                    if (!empty($_FILES["question_" .$value["ID"]]["name"][0])) {

                        foreach ($_FILES["question_" .$value["ID"]]["tmp_name"] as $index => $tmp_name) {

                            if ($_FILES["question_" .$value["ID"]]["error"][$index] === UPLOAD_ERR_OK) {

                                $file_name = basename($_FILES["question_" .$value["ID"]]["name"][$index]);
                                $target_path = $upload_question_dir . "/" . $file_name;

                              
                                if (move_uploaded_file($tmp_name, $target_path)) {
                                   // echo "<p>アップロード成功: {$file_name}</p>";
                                } else {
                                    //echo "<p style='color: red;'>画像のアップロードに失敗しました。</p>";
                                    //exit;
                                }
                                
                            }
                        }
                    }
                    else{
                        //空の場合は全部削除
                    }
                    
                    $question_number = $spirit_sheet_data->saveQuestionAnser(  $user_id , $sheet_id , $value["ID"] , $value["type"] , $upload_question_dir );


                
                    $anser_number[$value["ID"]] = $question_number;
                    

                }
            }



            
        }

        //質問回答を保存
        $spirit_sheet_data->setSpiritSheetAnswer( $sheet_id , $anser_number);

        //保存の場合は申請する
        if(isset($_POST["submitType"]) && $_POST["submitType"] == "confirm")
        {
            update_field("acf_purespirit_status",SpiritUserClass::ADMIN_STATUS_CONFIRMATION_INFOMATION,$sheet_id); //確認待ち


            if($spiritTypeArray[$spiritData[$sheet_id]["依頼ID"]]["last_check"] == "1")
            {
                update_field("acf_purespirit_user_status",SpiritUserClass::MEMBER_STATUS_LAST_CONFIRMATION,$sheet_id);//最終確認
            }
            else
            {
                update_field("acf_purespirit_user_status",SpiritUserClass::MEMBER_STATUS_CONFIRMATION_INFOMATION,$sheet_id);//確認待ち
            }
            


        }

        //もう一度取得
        $spiritData = $userClass->getUserSpritApplicantSheet($user_id,$sheet_id);//浄霊情報
        $spiritSheetArray = $spirit_sheet_data->getSpiritQuestion($spiritData[$sheet_id]["質問"]);//質問データ
    }





    


    $userData = $userClass->getUserAcountData($user_id);//ユーザー情報
    $targetList = $userClass->getTargetAcountData($user_id);//ターゲットリスト

    //対象者情報を入れる
    $targetArray = array();
    $targetAllArray = array();//常に全セット

    //リモート浄霊だけは最初に本人を入れておく
    if($spiritData[$sheet_id]["リモート浄霊"] != "")
    {
        $targetArray[0] = $userData;
    }

    $targetAllArray[0] = $userData;

    foreach ($targetList as $key => $value) {

        $targetArray[ $value ] = $userClass->getUserTargetData($user_id , $value);//対象者情報
        $targetAllArray[ $value ] =  $targetArray[ $value ];
    }
    

    $customize_data = $spirit_customize_data->getInputCustomizeData( $spiritData[$sheet_id]["質問"] );
    $personal_input_array = $spirit_customize_data->getPersonalDataInput( $spiritData[$sheet_id]["質問"] );

    //echo "<br>";
    //var_dump($spiritData[$sheet_id]);
    // var_dump($_POST);

    $question_result_array = $spiritData[$sheet_id]["質問回答"];


    //シートを入力できるかどうか
   
    $check_input_sheet = $userClass->checkInputQuestionSheet( $spiritData[$sheet_id]["会員ステータス"] , $spiritData[$sheet_id]["実行日"] ); //入力可能かどうか

    $chenge_status = $check_input_sheet["入力"];
    $after_day = $check_input_sheet["日数"];

    //入力可能の時のみ   
    
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




<div class="user-jorei-section-title-wrap">
    <div class="user-jorei-section-title"><?php echo $spiritData[$sheet_id]["依頼名前"];?>　入力シート</div>
</div>


<?php if($after_day != "" && $after_day > 0 && $after_day < 1000 && $chenge_status){ ?>

    <div style="color: red;font-weight: 600;margin-bottom: 10px;text-align: center;">
        入力期限はあと<?php echo $after_day;?>日です。入力期限を過ぎると、入力シートは編集できません。
    </div>


<?php } ?>


<?php if($spiritData[$sheet_id]["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_RE_INFOMATION){?>


    <?php if($chenge_status){ //入力可能の時のみ ?>

        <div style="margin-top: 30px;">
	        <div class="">【再提出依頼要望】</div>
            <div class="">
                <?php echo nl2br($spiritData[$sheet_id]["再提出依頼"]);?>
            </div>

        </div>

    <?php } ?>

<?php } ?>

<?php if($spiritData[$sheet_id]["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_RE_INFOMATION || $spiritData[$sheet_id]["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_NOT_INFOMATION){?>


    <?php if($after_day <= 0) { //締め切り後 ?>

        <div>
            入力期限が過ぎました。
        </div>

    <?php } ?>


<?php } ?>

<div class="input-form-area">

    <div class="input-form-contens" style="padding-top: 1px;">


         <div class="input-form-main">

            <form id="customForm" method="POST" enctype="multipart/form-data">


                <input type="hidden" id="" name="save_data" value="1">
                <input type="hidden" id="" name="sheet_id" value="<?php echo $sheet_id;?>">

                

                <?php
                
                    $target_on = false;
                    



                    foreach ($personal_input_array as $key => $value) {

                        if(!isset($customize_data["acf_spirit_note_personal_table"][ $value["ID"]] ) ){continue;}
                        if($customize_data["acf_spirit_note_personal_table"][ $value["ID"] ][0] == ""){continue;}
                        if($value["acf_input_target"] != ""  ){continue;} 


                        //対象者と申込者が違うのがある
                        if($value["ID"] == 490)
                        {
                            $target_on = true;
                            break;
                        }
                     }

                ?>


                <?php if($target_on){ ?>

                    <div style="margin-left: 10px;">

                        <div class="orderform-question-title" style="margin-top: 10px;">
                            <div class="orderform-question-title-required-box">必須</div>
                            <div class="orderform-question-title-right">申込者と対象者が別</div>
                        </div>

                        <?php 


                             $disp_questiont_class->dispTargetOnOff( 1 , $spiritData[$sheet_id]["対象者"]["対象者情報"] , $chenge_status);
                    
                        ?>


                        <div id="additionalFields" class="hidden">



                            <?php if($chenge_status){ //入力可能の時のみ ?>

                                <?php if(count($targetArray) > 0){ //設定した対象者?>

                                    <div class="orderform-target-select-area">

                                        <div class="orderform-target-select-flex">

                                             <div class="orderform-target-select">

                                                 <select  name="input_target_set" id="categorySelect" class="orderform-target-selectbox">
                                                    <option value=""></option>

                                    
                                                    <?php  foreach ($targetArray as $key => $value) {?>
                                                        <option value="<?php echo $key;?>" <?php if($set_target_id == $key){?> selected <?php } ?>><?php echo $value["フル名前"];?></option>
                                                    <?php } ?>

                                                 </select>

                                             </div>

                                             <div class="orderform-target-select-button">
                              
                                                <div class="orderform-input-textbox">
                                                    <button type="button"  id="insertButton" onclick=""  class="target_button">対象者情報をコピーする</button>
                                                </div>

                                             </div>
                                        </div>

                                        <?php if($set_target_id != ""){ ?>

                                            <div class="orderform-target-select-save-tex">作成・上書きしました</div>

                                        <?php } ?>

                                        <div class="orderform-question-line"></div>
                                    </div>

                                <?php } ?>

                            <?php } ?>
                             


                            <?php foreach ($personal_input_array as $key => $value) { //対象者と申込者が違う?>


                                <?php 
                                    if(!isset($customize_data["acf_spirit_note_personal_table"][ $value["ID"] ] ) ){continue;} 
                                    if($customize_data["acf_spirit_note_personal_table"][ $value["ID"] ][0] == ""){continue;}//対象者用のデータON・OFF
                                    if($value["acf_input_target"] == "" ){continue;} //対象者用のデータON・OFF
                                ?>

                                <div class="orderform-question-box">
                                        <div class="orderform-question-input-area">


                                        <div class="orderform-question-title">
                                            <?php if($customize_data["acf_spirit_note_personal_table"][ $value["ID"] ][1] != ""){?>
                                                <div class="orderform-question-target-required-box">必須</div>
                                            <?php } ?>
                                            <div class="orderform-question-title-right"><?php echo $value["acf_input_personal_data_title"]; ?></div>
                                      
                                        </div>

                                        <?php 
                                
                                            if($value["acf_input_target"] != "" ){
                                                    $disp_questiont_class->dispPersonalDataInputTargetForm( $value["ID"] , $customize_data["acf_spirit_note_personal_table"] , $userData , $spiritData[$sheet_id]["対象者"] , $chenge_status);
                                            }
                                        ?>
                              
                                        </div>
                                    </div>

                                    <div class="orderform-question-line"></div>

                            <?php } ?>


                            <?php if($chenge_status){ //入力可能の時のみ ?>
                                <div class="orderform-question-title" style="margin-top: 10px;">
                                    <div class="orderform-question-title-right">対象者の保存</div>
                                </div>

                                <?php 
                                    $disp_questiont_class->dispTargetSave( 0 ); //保存するかどうか
                                ?>

                                 <div class="orderform-question-line"></div>

                            <?php } ?>

                        </div>

                         
                     </div>
                <?php } ?>



                <?php //var_dump($spiritSheetArray[$spiritData[$sheet_id]["質問"]]);?>

                <?php foreach ( $spiritSheetArray[$spiritData[$sheet_id]["質問"]] as $key => $value) { ?>

                    <?php if(!$value["form_disp"] && !$value["admin_disp"]){continue;} //どっちも表示しない?>

                    <?php if( ($value["form_disp"]) ||  ( current_user_can('administrator') || current_user_can('Editor')) ){ //管理者の時様用に表示する?>

                         <div class="orderform-question-box">

                                
                            <?php  if($value["type"] != 10){ //対象者の追加はタイトルは別  ?>


                                <?php
                                
                                    $admin_only = false;
                                
                                    if( !$value["form_disp"] && $value["admin_disp"]){
                                        $admin_only = true;
                                    }
                                ?>

                                <?php $disp_questiont_class->dispQuestionTitle(  $value["required"] , $value["text"] , $admin_only );//タイトル表示  ?>

                               

                            <?php } ?>

                            <?php //質問の内容?>
                            <div class="orderform-question-input-area">


                                <?php 
                                    //表示の内容
                                    $disp_value = "";

                                    if( isset( $question_result_array[ $value["ID"] ] ) ){

                                        if($value["type"] == SpiritSheetClass::QUESTION_TYPE_IMG_DATA){//画像は画像URLを渡す
                                            $disp_value = get_field("acf_questionqnser_img_url" ,  $question_result_array[ $value["ID"] ]);
                                        }
                                        else{
                                            $disp_value = get_field("acf_questionqnser_text" ,  $question_result_array[ $value["ID"] ]);
                                        }
                                    }

                                    $disp_questiont_class->dispQuestionByType( $userClass , $value , $question_result_array, $disp_value  , $chenge_status , $targetAllArray);

                                ?>

                                <?php if(!$chenge_status){ //入力可能の時のみ ?>
                                    <div class="orderform-question-line"></div>
                                <?php } ?>

                             </div>

                         </div>
         
                    <?php } ?>
                <?php } ?>



                <?php if($chenge_status){ //入力可能の時のみ ?>


                    <input type="hidden" id="submitType" name="submitType" value="">

                    <div class="user-orderform-button-area">
                        <div class="user-orderform-button">
                            <button type="button" class="submit-button" data-draft="true" style="width: 100%;font-size: 32px;border-radius: 8px;background-color: aquamarine;">下書き保存</button>
                            <div class="user-orderform-button-text">
                                ＊必須項目を入力しなくても一時保存が可能です。
                            </div>
                        </div>
                    
                        <?php if($spiritData[$sheet_id]["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_RE_INFOMATION || $spiritData[$sheet_id]["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_NOT_INFOMATION){?>
                            <div class="user-orderform-button" style="margin-top: 80px;">
                                <button type="button" class="submit-button" data-draft="false" style="width: 100%;font-size: 32px;border-radius: 8px;background-color: red;color: white;">入力内容を送る</button>
                                <?php if($spiritTypeArray[$spiritData[$sheet_id]["依頼ID"]]["last_check"] == "1"){?>
                                    <div class="user-orderform-submit-text">
                                        ＊入力内容送信後に最終確認がございます。TOPページより、最終確認から内容を確認し、申請をお願いいたします。
                                    </div>
                                <?php }else{ ?>
                                    <div class="user-orderform-submit-text">
                                        ＊依頼後は変更ができなくなります。<br>ただし、最低提出の場合は再度変更が可能になります。
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>

                <?php } ?>
            </form>

            <!-- 🔹 確認モーダル -->
            <div id="confirmModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
                <div style="background: white; padding: 20px; border-radius: 5px; text-align: center;">
                    <p id="modalMessage">入力内容を送信してもよろしいですか？</p>
                    <button id="confirmYes">はい</button>
                    <button id="confirmNo">いいえ</button>
                </div>
            </div>

         </div>


    </div>

</div>

<?php if($spiritData[$sheet_id]["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_LAST_CONFIRMATION ){?>

    
    <form action="<?php echo getURLSetSlag("users/user-spirit-question-check"); echo $get_url["add"];?>" method="post">
         <button type="submit"  class="user-account-edit-return-btn">最終確認 ＞</button>
        <input type="hidden" name="sheet_id" value="<?php echo $sheet_id; ?>">
    </form>

<?php } else { ?>

<?php } ?>

<div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 20px;">
    <a href="<?php echo getURLSetSlag("users/user-in-progress-spirit-list"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">履歴一覧へ  &gt;</a>
</div>
<div class="user-account-edit-form-btn-wrap" style="margin-top: 10px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
    </div>    

<script>


const targetData  = <?php echo json_encode($targetArray, JSON_UNESCAPED_UNICODE); ?>;
const targetAllData  = <?php echo json_encode($targetAllArray, JSON_UNESCAPED_UNICODE); ?>

//ボタンを押すと対象者情報を取得
const insertButton = document.getElementById('insertButton');

    if (insertButton) {
        insertButton.addEventListener('click', function() {
            let category = document.getElementById('categorySelect').value;

            let targetFields = {
                'input_target_last_name': '苗字',
                'input_target_first_name': '名前',
                'input_target_last_name_kana': 'ミョウジ',
                'input_target__first_name_kana': 'ナマエ',
                'input_target_email': 'メール',
                'input_user_target_line_id': 'LINEID',
                'input_target_tel_1': '電話番号1',
                'input_target_tel_2': '電話番号2',
                'input_target_tel_3': '電話番号3',
                'input_target_post_no': '郵便番号',
                'input_target_address1': '住所1',
                'input_target_address2': '住所2',
                'input_target_born_year': '誕生日年',
                'input_target_born_month': '誕生日月',
                'input_target_born_day': '誕生日日',
                'input_target_report_born_year': '届け出日年',
                'input_target_report_born_month': '届け出日月',
                'input_target_report_born_day': '届け出日日',
                'input_target_sex': '性別値',
                'input_target_relationship': '関係'
            };

            Object.keys(targetFields).forEach(id => {
                let inputField = document.getElementById(id);
                if (inputField) {
                    inputField.value = category && targetData[category] ? targetData[category][targetFields[id]] : "";
                }
            });
        });
    }
    

//ボタンを押すと対象者情報を取得
const insertPostButton = document.getElementById('insertPostButton');

    if (insertPostButton) {
        insertPostButton.addEventListener('click', function() {
            let category = document.getElementById('categoryPostSelect').value;

            let targetFields = {
                'input_post_last_name': '苗字',
                'input_post_first_name': '名前',
                'input_post_tel_1': '電話番号1',
                'input_post_tel_2': '電話番号2',
                'input_post_tel_3': '電話番号3',
                'input_post_zipcord': '郵便番号',
                'input_post_address1': '住所1',
                'input_post_address2': '住所2',
            };

            Object.keys(targetFields).forEach(id => {
                let inputField = document.getElementById(id);
                if (inputField) {
                    inputField.value = category && targetAllData[category] ? targetAllData[category][targetFields[id]] : "";
                }
            });
        });
    }

    function copyValue(sourceData,setByID) {
        document.getElementById(setByID).value = sourceData;
    
    }

 
    function copyValue3( sourceData1 , sourceData2 , sourceData3 , setByID1 , setByID2 ,setByID3) {

        document.getElementById(setByID1).value = sourceData1;
        document.getElementById(setByID2).value = sourceData2;
        document.getElementById(setByID3).value = sourceData3;
    
    }

function copyTargetData(questionID, targetNumber) {
    let insertCopyButton = document.getElementById(`insertButton_${questionID}_${targetNumber}`);
    let category = document.getElementById(`categorySelect_${questionID}_${targetNumber}`).value;

    let targetFields = {
        [`question_${questionID}_${targetNumber}_sei`]: '苗字',
        [`question_${questionID}_${targetNumber}_mei`]: '名前',
        [`question_${questionID}_${targetNumber}_sei_kana`]: 'ミョウジ',
        [`question_${questionID}_${targetNumber}_mei_kana`]: 'ナマエ',
        [`question_${questionID}_${targetNumber}_born_year`]: '誕生日年',
        [`question_${questionID}_${targetNumber}_born_month`]: '誕生日月',
        [`question_${questionID}_${targetNumber}_born_day`]: '誕生日日',
        [`question_${questionID}_${targetNumber}_parents`]: '関係',
    };


    Object.keys(targetFields).forEach(id => {
        let inputField = document.getElementById(id);
        if (inputField) {
            if (targetData && targetData[category]) { // targetDataが存在するか確認
                inputField.value = targetData[category][targetFields[id]] || "";
            } else {
                inputField.value = "";
            }
        }
    });
}

   　// 郵便番号取得
    function getAddressArgumentData(zipcorde_1, address_id ) {

            
        var zip1 = document.querySelector(`input[name="${zipcorde_1}"]`).value;

            
        var zipcode = zip1;

        if (!zip1) {
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


</script>
<script>

document.addEventListener("DOMContentLoaded", function () {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    const allInputs = document.querySelectorAll('input, select, textarea');
    let selectedFiles = {};  // 🔹 新規追加した画像
    let initialFiles = {};  // 🔹 初期画像（サーバーから取得）
    let requiredInputs = []; // 🔹 必須入力項目を記録
    let isDraft = false; // 🔹 下書き送信フラグ
    let form = document.getElementById("customForm");
    let modalMessage = document.getElementById("modalMessage");
    let confirmModal = document.getElementById("confirmModal");
    let confirmYes = document.getElementById("confirmYes");
    let confirmNo = document.getElementById("confirmNo");
    let submitTypeInput = document.getElementById("submitType");

    confirmModal.style.display = "none";
    confirmModal.style.visibility = "hidden";

    if (sessionStorage.getItem("modalOpen") === "true") {
        sessionStorage.removeItem("modalOpen");
    }

    // **必須入力の要素を保存**
    allInputs.forEach(input => {
        if (input.hasAttribute("required")) {
            requiredInputs.push(input);
        }
    });

    fileInputs.forEach(input => {
        const inputName = input.name;
        selectedFiles[inputName] = [];
        initialFiles[inputName] = [];

        updateFileLabel(input);
        updatePreview(input);

        // **画像を新しく追加したときの処理**
        input.addEventListener("change", function (event) {
            const files = Array.from(event.target.files);

            if (!selectedFiles[inputName]) {
                selectedFiles[inputName] = [];
            }

            // **既存の `selectedFiles[inputName]` に `push()` で追加**
            files.forEach(file => selectedFiles[inputName].push(file));

            // **`updateFileInput()` で `initialFiles` も含めたリストを反映**
            updateFileInput(input);
            updateFileLabel(input);
            updatePreview(input);

            //event.target.value = ""; // **同じファイルを再選択可能にするためリセット**
        });
    });


    function updateFileInput(input) {
        let dataTransfer = new DataTransfer();
        const inputName = input.name;

        // **初期画像 + 追加画像をDataTransferにセット**
        [...(initialFiles[inputName] || []), ...(selectedFiles[inputName] || [])].forEach(file => {
            if (!(file instanceof File)) {
                file = new File([file], file.name, { type: file.type });
            }
            dataTransfer.items.add(file);
        });

        input.files = dataTransfer.files;
    }

    function updateFileLabel(input) {
        const inputName = input.name;
        let fileCount = (selectedFiles[inputName] || []).length + (initialFiles[inputName] || []).length;
        input.title = fileCount > 0 ? `${fileCount}枚選択` : "選択されていません";
    }

    function updatePreview(input) {
        const inputName = input.name;
        const previewContainer = document.getElementById(`imagePreview${input.id.replace('imageInput', '')}`);
        if (!previewContainer) return;

        previewContainer.innerHTML = "";
        let allFiles = [...(initialFiles[inputName] || []), ...(selectedFiles[inputName] || [])];

        allFiles.forEach((file, index) => {
            let reader = new FileReader();
            reader.onload = function (e) {
                let imgDiv = document.createElement("div");
                imgDiv.classList.add("preview-container");

                let img = document.createElement("img");
                img.src = e.target.result;
                img.classList.add("preview-img");

                <?php if($chenge_status){?>
                    let imgRemoveBtn = document.createElement("button");
                    imgRemoveBtn.textContent = "×";

                
                    imgRemoveBtn.classList.add("remove-btn");
                

                    imgRemoveBtn.addEventListener("click", function () {
                        removeFile(input, index);
                    });
                <?php } ?>

                imgDiv.appendChild(img);

                <?php if($chenge_status){?>
                    imgDiv.appendChild(imgRemoveBtn);
                <?php } ?>

                previewContainer.appendChild(imgDiv);
            };
            reader.readAsDataURL(file);
        });
    }

    function removeFile(input, index) {
        const inputName = input.name;
        let allFiles = [...(initialFiles[inputName] || []), ...(selectedFiles[inputName] || [])];

        if (index < initialFiles[inputName].length) {
            initialFiles[inputName].splice(index, 1);
        } else {
            selectedFiles[inputName].splice(index - initialFiles[inputName].length, 1);
        }

        updateFileInput(input);
        updateFileLabel(input);
        updatePreview(input);
    }

    // **フォーム送信時に最新の `fileInput.files` を適用**
    form.addEventListener("submit", function () {
        fileInputs.forEach(input => {
            updateFileInput(input);
        });

        console.log("🚀 送信時の `fileInput.files`:", fileInputs[0].files);
    });

    // **初期画像のセット**
    window.initImageUpload = function (savedImages, rquestionId) {
        const inputId = `imageInput${rquestionId}`;
        const input = document.getElementById(inputId);
        const inputName = input.name;

        async function fetchBlobFromURL(url) {
            try {
                const encodedURL = encodeURI(url);
                const response = await fetch(encodedURL);
                if (!response.ok) throw new Error(`Failed to fetch: ${response.statusText}`);
                const blob = await response.blob();
                const filename = decodeURIComponent(url.split('/').pop());
                return new File([blob], filename, { type: blob.type });
            } catch (error) {
                console.error(`❌ 画像の取得に失敗: ${url}`, error);
                return null;
            }
        }

        async function initializeFiles() {
            let filePromises = savedImages.map(url => fetchBlobFromURL(url));
            let files = await Promise.all(filePromises);
            initialFiles[inputName] = files.filter(file => file !== null);

            updatePreview(input);
            updateFileInput(input);
        }

        initializeFiles();
    };

    // **モーダル関連の処理**
    document.querySelectorAll(".submit-button").forEach(button => {
        button.addEventListener("click", function (event) {
            isDraft = this.dataset.draft === "true";


            if(modalMessage)
            {
                modalMessage.textContent = isDraft
                    ? "下書きを保存してもよろしいですか？"
                    <?php if($spiritTypeArray[$spiritData[$sheet_id]["依頼ID"]]["last_check"] == "1"){?>
                        : "最終確認に進みますか？";
                    <?php }else{ ?>
                        : "確認依頼を送信してもよろしいですか？";
                    <?php } ?>
                    
            }

            //どちらで送信したか
            if(submitTypeInput)
            {
                submitTypeInput.value = isDraft ? "draft" : "confirm";
            }

            requiredInputs.forEach(input => {
                if (isDraft) {
                    input.removeAttribute("required");
                } else {
                    input.setAttribute("required", "required");
                }
            });

            if (!isDraft && !validateFormBeforeModal()) {
                return;
            }

            sessionStorage.setItem("modalOpen", "true");
            confirmModal.style.display = "flex";
            confirmModal.style.visibility = "visible";
        });
    });

    confirmYes.addEventListener("click", function () {
        sessionStorage.removeItem("modalOpen");
        confirmModal.style.display = "none";
        confirmModal.style.visibility = "hidden";
        submitForm();
    });

    confirmNo.addEventListener("click", function (event) {
        event.preventDefault();
        sessionStorage.removeItem("modalOpen");
        confirmModal.style.display = "none";
        confirmModal.style.visibility = "hidden";
    });

    function submitForm() {
        fileInputs.forEach(input => {
            updateFileInput(input);
        });

        console.log("🚀 送信時の `fileInput.files`:", fileInputs[0].files);
        form.submit();
    }

    function validateFormBeforeModal() {
        let isValid = true;
        let missingFields = [];

        requiredInputs.forEach(input => {
            if (!isDraft && !input.value.trim()) {
                missingFields.push(input.name);
            }
        });

        if (missingFields.length > 0) {
                       alert("必須項目を入力してください:");

            return false;
        }

        return true;
    }
});




</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var form = document.getElementById('customForm');
        if (!form) return; // 🔹 formが存在しない場合は処理をしない

        var additionalFields = document.getElementById('additionalFields');
        var radioButtons = form.querySelectorAll('input[name="input-applicant-check"]');

        var selectedRadio = form.querySelector('input[name="input-applicant-check"]:checked');
        if (selectedRadio && selectedRadio.value === "1") {
            additionalFields.classList.remove('hidden');
            additionalFields.querySelectorAll('.required').forEach(function(input) {
                input.setAttribute('required', 'required');
            });
        }

        radioButtons.forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (radio.value === "1") {
                    additionalFields.classList.remove('hidden');
                    additionalFields.querySelectorAll('.required').forEach(function(input) {
                        input.setAttribute('required', 'required');
                    });
                } else {
                    additionalFields.classList.add('hidden');
                    additionalFields.querySelectorAll('.required').forEach(function(input) {
                        input.removeAttribute('required');
                    });
                }
            });
        });
    });

    function validateFileInputs() {
        console.log("ファイルの検証を実行");
    }

       
 </script>