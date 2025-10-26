<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritQuestionDispClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritInputCustomizeClass.php");

    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);


    //シートID
    $sheet_id = $_POST["sheet_id"];

    $userClass = new SpiritUserClass(); //ユーザー管理
    $spirit_sheet_data = new spiritSheetClass(); //質問データ
    $disp_questiont_class = new SpiritQuestionDispClass(); //表示データ
    $spirit_customize_data = new SpiritInputCustomizeClass(); //文字データ

    $spiritData = $userClass->getUserSpritApplicantSheet($user_id,$sheet_id);//浄霊情報
    $spiritSheetArray = $spirit_sheet_data->getSpiritQuestion($spiritData[$sheet_id]["質問"]);//質問データ
    //現在の質問番号を取得
  
    //対象者用
    $set_target_id = "";
   

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

    $chenge_status = false;

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

    /* モーダルの背景（オーバーレイ） */
    #modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }

    /* モーダルボックス */
    #modal-box {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        text-align: center;
        z-index: 1000;
        max-width: 400px;
        width: 90%;
    }

    #modal-box h2 {
        margin-top: 0;
        font-size: 20px;
        margin-bottom: 15px;
    }

    #modal-box p {
        margin-bottom: 20px;
        font-size: 14px;
        line-height: 1.6;
    }

    .modal-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 20px;
    }

    .modal-confirm-btn {
        padding: 12px 24px;
        background-color: #800A90;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.2s;
    }

    .modal-confirm-btn:hover {
        background-color: #600870;
    }

    .modal-cancel-btn {
        padding: 12px 24px;
        background-color: #ccc;
        color: black;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.2s;
    }

    .modal-cancel-btn:hover {
        background-color: #999;
    }

    .submit-btn {
        margin-top: 30px;
        color: white;
        background-color: #800A90;
        width: 220px;
        height: 50px;
        border-radius: 15px;
        border: 1px solid #800A90;
        cursor: pointer;
        font-size: 18px;
        font-weight: 600;
        transition: background-color 0.2s;
    }

    .submit-btn:hover {
        background-color: #600870;
    }

    .edit-btn {
        margin-top: 20px;
        color: #666;
        background-color: #f0f0f0;
        width: 220px;
        height: 50px;
        border-radius: 15px;
        border: 1px solid #ddd;
        cursor: pointer;
        font-size: 18px;
        font-weight: 600;
        transition: background-color 0.2s;
    }

    .edit-btn:hover {
        background-color: #e0e0e0;
    }

    .button-wrap {
        text-align: center;
    }
</style>




<div class="user-jorei-section-title-wrap">
    <div class="user-jorei-section-title"><?php echo $spiritData[$sheet_id]["依頼名前"];?>　最終確認</div>
</div>





<div class="input-form-area">

    <div class="input-form-contens" style="padding-top: 1px;">


         <div class="input-form-main">

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
                    <?php if($spiritData[$sheet_id]["対象者"]["対象者情報"]== false){ ?>

                                            
                        <?php foreach ($personal_input_array as $key => $value) { //対象者と申込者が同じ ?>


                            <?php 
                                if($value["acf_input_personal_data_disp"] == "" ){continue;} //対象者用のデータON・OFF
                                if($value["acf_input_target"] != "" ){continue;} //対象者用のデータON・OFF
                                if($value["ID"] == 490){continue;} //対象者用のデータON・OFF
                            ?>

                            <div class="orderform-question-box" style="padding-left: 0px;">
                                    <div class="orderform-question-input-area">


                                    <div class="orderform-question-title">
                                        <?php if($customize_data["acf_spirit_note_personal_table"][ $value["ID"] ][1] != ""){?>
                                            <div class="orderform-question-target-required-box" style="background-color: red;">必須</div>
                                        <?php } ?>
                                        <div class="orderform-question-title-right"><?php echo $value["acf_input_personal_data_title"] ; ?></div>
                                
                                    </div>

                                    <?php 
                    
                                        $disp_questiont_class->dispPersonalDataInputTargetForm( $value["ID"] , $customize_data["acf_spirit_note_personal_table"] , $userData , $userData , $chenge_status);
                                    ?>
                        
                                    </div>
                                </div>

                                <div class="orderform-question-line"></div>


                        <?php } ?>

                        <div style="color: red;font-size: 19px;margin-top: 10px;font-weight: 600;padding-bottom: 20px;">
                            申込者内容にお間違いがある場合は『<a href="<?php echo getURLSetSlag("users/user-acount-edit"); echo $get_url["add"]; ?>" style="color: #b75959;" style="color: red;text-decoration: underline;">会員情報</a>』から、ご変更してください
                        </div>

                    <?php }else{ ?> 

                        <div id="additionalFields" class="hidden">

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

                        </div>

                        <div style="color: red;font-size: 19px;margin-top: 10px;font-weight: 600;padding-bottom: 20px;">
                            対象者内容にお間違いがある場合は『<a href="<?php echo getURLSetSlag("users/user-contact"); echo $get_url["add"]; ?>" style="color: #b75959;" style="color: red;text-decoration: underline;">お問い合わせ</a>』から、ご連絡してください
                        </div>

                    <?php } ?>

                    
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


           
            <form action="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" method="post" id="submit-form">
                <input type="hidden" name="last_check_data" value="1">
                <input type="hidden" name="sheet_id" value="<?php echo $sheet_id; ?>">
            </form>
            
            <form action="<?php echo getURLSetSlag("users/user-spirit-question-edit"); echo $get_url["add"]; ?>" method="post" id="edit-form">
                <input type="hidden" name="edit_return" value="1">
                <input type="hidden" name="sheet_id" value="<?php echo $sheet_id; ?>">
            </form>

            <div class="button-wrap">
                <button type="button" class="submit-btn" onclick="showSubmitModal()">この内容で申請する</button>
            </div>
            
            <div class="button-wrap">
                <button type="button" class="edit-btn" onclick="showEditModal()">内容を修正する</button>
            </div>

         </div>


    </div>

</div>

<div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 20px;">
    <a href="<?php echo getURLSetSlag("users/user-in-progress-spirit-list"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">履歴一覧へ  &gt;</a>
</div>
<div class="user-account-edit-form-btn-wrap" style="margin-top: 10px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
    </div>    

<!-- 申請確認モーダル -->
<div id="modal-overlay">
    <div id="modal-box">
        <h2 id="modal-title"></h2>
        <p id="modal-message"></p>
        <div class="modal-buttons">
            <button class="modal-confirm-btn" id="modal-confirm-btn"></button>
            <button class="modal-cancel-btn" onclick="closeModal()">キャンセル</button>
        </div>
    </div>
</div>

<script>
function showSubmitModal() {
    document.getElementById('modal-title').textContent = '申請確認';
    document.getElementById('modal-message').innerHTML = 'この内容で申請しますか？<br><span style="color: #d9534f; font-size: 13px;">申請後は内容の変更ができません。</span>';
    document.getElementById('modal-confirm-btn').textContent = '申請する';
    document.getElementById('modal-confirm-btn').onclick = function() {
        document.getElementById('submit-form').submit();
    };
    document.getElementById('modal-overlay').style.display = 'block';
}

function showEditModal() {
    document.getElementById('modal-title').textContent = '編集確認';
    document.getElementById('modal-message').textContent = '入力内容を修正しますか？';
    document.getElementById('modal-confirm-btn').textContent = '修正する';
    document.getElementById('modal-confirm-btn').onclick = function() {
        document.getElementById('edit-form').submit();
    };
    document.getElementById('modal-overlay').style.display = 'block';
}

function closeModal() {
    document.getElementById('modal-overlay').style.display = 'none';
}

document.getElementById('modal-overlay').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
