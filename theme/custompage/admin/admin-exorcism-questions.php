<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");


    $spirit_sheet_data = new spiritSheetClass(); //管理データ



    $question_num = 0;

    if(isset($_GET["type_id"]))
    {
        $question_num = $_GET["type_id"];
    }


    $new_err = "";
    $edit_err = "";

      //var_dump($_POST);

     //新規
    if(isset($_POST["input_add_name"]))
    {

        if($_POST["input_add_name"] != "")
        {
            $required = "";
            $form_on = "";
            $admin_on = "";
            $choice_img = "";
            $counselor_disp = "";
            
            if(isset($_POST["acf_question_form_disp"]))
            {
                $form_on = 1;
            }

            if(isset($_POST["acf_question_admin_disp"]))
            {
                $admin_on = 1;
            }

            if(isset($_POST["acf_question_required"]))
            {
                $required = 1;
            }

            if(isset($_POST["acf_questionqnser_img_choice"]))
            {
                $choice_img = $_POST["acf_questionqnser_img_choice"];
            }

            if(isset($_POST["acf_question_counselor_disp"]))
            {
                $counselor_disp = 1;
            }
            
           // var_dump($_POST);
           $spirit_sheet_data->newSpiritQuestion( $_POST["input_add_name"] , $_POST["input_select_type"] , $required , $form_on , 
                $admin_on , $_POST["question_number"] , $_POST["input_add_text"] , 
                get_field('acf_pure_spirit_title',$question_num),$choice_img ,$counselor_disp );
        }
        else{

            $new_err = "空白は登録できません";

        }
    }


    //編集
    if(isset($_POST["input_edit_name"]))
    {

        if($_POST["input_edit_name"] != "")
        {

            $required = "";
            $form_on = "";
            $admin_on = "";
            $choice_img = "";
            $counselor_disp = "";

            if(isset($_POST["acf_question_form_disp"]))
            {
                $form_on = 1;
            }

            if(isset($_POST["acf_question_admin_disp"]))
            {
                $admin_on = 1;
            }

            if(isset($_POST["acf_question_required"]))
            {
                $required = 1;
            }

            if(isset($_POST["acf_questionqnser_img_choice"]))
            {
                $choice_img = $_POST["acf_questionqnser_img_choice"];
            }

            if(isset($_POST["acf_question_counselor_disp"]))
            {
                $counselor_disp = 1;
            }

            $spirit_sheet_data->saveSpiritQuestion( $_POST["edit_no"] , $_POST["input_select_type"] , $_POST["input_edit_name"], 
                $_POST["input_add_text"] , $form_on , $admin_on, $required , 
                $_POST["input_edit_name"] . "(".get_field('acf_pure_spirit_title',$question_num).")", $choice_img,$counselor_disp);
        }
        else{

            $edit_err = "空白は登録できません";

        }
    }

    //並び順保存
    if(isset($_POST["order"]))
    {
        $sort_array = array();

        $sort_array = explode(',', $_POST["order"]);

       

        $spirit_sheet_data->saveSpiritQuestionSort( $sort_array );

    }

    //削除
    if(isset($_POST["delete_no"]))
    {
        $spirit_sheet_data->deleteSpiritQuestion( $_POST["delete_no"] );

    }


     $spiritStatusArray = $spirit_sheet_data->getSpiritQuestion($question_num);

    // var_dump($spiritStatusArray);
?>

<div class="admin-exorcism-area">




  
    <div class="admin-exorcism-button-area">

        <div class="admin-exorcism-menu-title-box">
            <div class="admin-exorcism-menu-title"><?php echo get_field('acf_pure_spirit_title',$question_num); ?>質問集</div>
        </div>


        <div class="admin-spirit-edit-area">

            <div class="admin-spirit-edit-box">

                <form action="<?php echo  getURLSetSlag( "admin-exorcism-questions" );?>?type_id=<?php echo $question_num;?>" name="add_item" method="post" id="add_item">

                    <div class="admin-spirit-edit-flex">


                        <div class="admin-spirit-edit-title">新規追加質問</div>
                  
                        <div class="admin-spirit-edit-input-box">

                            <div class="admin-spirit-edit-select-area">

                                <select name="input_select_type">
                                    <option value="0">テキスト</option>
                                    <option value="1">テキストエリア</option>
                                    <option value="2">カレンダー</option>
                                    <option value="3">名前</option>
                                    <option value="4">数字</option>
                                    <option value="5">メールアドレス</option>
                                    <option value="6">電話番号</option>
                                    <option value="7">選択</option>
                                  <?php /*  <option value="8">チェックボックス</option> */?>
                                    <option value="9">ラジオボタン</option>

                                    <?php if( $question_num == 77) {?>
                                        <option value="10">対象者追加</option>
                                    <?php } ?>
                                    <option value="11">画像</option>
                                    <option value="12">送付先</option>
                                    <option value="13">代表画像選択</option>
                                </select>
                            </div>


                            <div class="admin-spirit-edit-input-area">
                                <input type="text" name="input_add_name" id="input_add_name" value=""/>
                            </div>

                             <div class="admin-spirit-edit-input-checkbox">
                                <input type="checkbox" id="acf_question_required_id" name="acf_question_required" />必須項目
                            </div>

                            <div class="admin-spirit-edit-input-checkbox">
                                <input type="checkbox" id="acf_question_form_disp_id" name="acf_question_form_disp" />入力フォーム表示
                            </div>
                            <div class="admin-spirit-edit-input-checkbox">
                                <input type="checkbox" id="acf_question_admin_disp_id" name="acf_question_admin_disp" />管理画面表示
                            </div>

                            <div class="admin-spirit-edit-input-checkbox">
                                <input type="checkbox" id="acf_question_counselor_disp_id" name="acf_question_counselor_disp" />カウンセラー表示
                            </div>

                            <div id="input_add_text_area">
                                <div class="admin-spirit-edit-input-add-title">
                                    入力時の補足(テキストの際は入力欄に記載、テキストエリアの時は入力外、数字の場合は単位として表示されます。選択とセレクトボックス、ラジオボックスの場合は改行で一行ずつ入力してください)
                                </div>
                                <div class="admin-spirit-edit-input-add-box">
                                    <textarea id="input_add_text" name="input_add_text" rows="5" cols="33" style="width: 100%;"></textarea>
                                </div>
                            </div>

                            <div id="img_choice_area" style="display:none;">
                                <?php 
                                    if(!empty($spiritStatusArray)) {
                                ?>
                                    <div class="admin-spirit-edit-input-add-title" style="margin-bottom: 10px;margin-top: 20px;">
                                        代表画像選択する質問を選んでください（2個以上をチェックすると、その質問の画像から１つ選択できます）
                                    </div>
                                    <?php 
                                        foreach ($spiritStatusArray[$question_num] as $key => $value) { 
                                        
                                            if($value["type"] == 13){ continue; } //画像選択は入らない
                                            if($value["type"] != 11){ continue; } //画像選択はi以外は入らない
                                    ?>
                                        <div>
                                            <input type="checkbox" name="acf_questionqnser_img_choice[]" id="acf_questionqnser_img_choice" value="<?php echo $value["ID"]?>" /><?php echo $value["text"]?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>

                        </div>

                        <input type="hidden" name="question_number" id="question_number" value="<?php echo $question_num;?>" />

                        <div class="admin-spirit-edit-inputbutton-area">
                            <input class="admin-spirit-edit-button" type="button" value="質問追加" onclick="saveItemAddData()"/>
                        </div>

                      </div>

                </form>

                <?php if($new_err != ""){ //エラー ?>
                    <div class="admin-spirit-edit-err-etr"><?php echo $new_err;?></div>
                <?php } ?>
            </div>


            <div class="admin-spirit-edit-box">

                <div class="admin-spirit-edit-flex">


                    <div class="admin-spirit-edit-title">表示順の変更</div>
                  
                    <div class="admin-spirit-edit-input-area">
                        <div class="admin-spirit-edit-input-str">リストの▲▼をクリックし、任意の順番に並べ替えたら右の保存を押してください。</div>
                    </div>

                    <div class="admin-spirit-edit-inputbutton-area">
                        <input class="admin-spirit-edit-button" type="button" value="並び順を保存" onclick="saveOrder()"/>
                    </div>

                </div>
               
            </div> 

            <div class="admin-spirit-edit-box">

               
                <form action="<?php echo  getURLSetSlag( "admin-exorcism-questions" );?>?type_id=<?php echo $question_num;?>" name="add_item" method="post" id="edit_item">
                    <div class="admin-spirit-edit-flex">

                    
                        <div class="admin-spirit-edit-title">内容の編集</div>
                  
                        <?php  if(!isset($_POST["edit_no"])){?>
                            <div class="admin-spirit-edit-input-area">
                                <div class="admin-spirit-edit-input-str">リストから変更したい項目の右にある『編集』を押すと編集画面に切り替わります。</div>
                            </div>
                        <?php }else{ ?>

                       
                            <div class="admin-spirit-edit-input-box">


                                 <div class="admin-spirit-edit-select-area">


                                    <?php 
                                    
                                        $select_input_type = get_field('acf_question_type' ,$_POST["edit_no"] );
                                    
                                    ?>

                                    <select name="input_select_type">
                                            <option value="0" <?php if($select_input_type == 0 || $select_input_type == ""){ echo "selected"; }?>>テキスト</option>
                                            <option value="1" <?php if($select_input_type == 1){ echo "selected"; }?>>テキストエリア</option>
                                            <option value="2" <?php if($select_input_type == 2){ echo "selected"; }?>>カレンダー</option>
                                            <option value="3" <?php if($select_input_type == 3){ echo "selected"; }?>>名前</option>
                                            <option value="4" <?php if($select_input_type == 4){ echo "selected"; }?>>数字</option>
                                            <option value="5" <?php if($select_input_type == 5){ echo "selected"; }?>>メールアドレス</option>
                                            <option value="6" <?php if($select_input_type == 6){ echo "selected"; }?>>電話番号</option>
                                            <option value="7" <?php if($select_input_type == 7){ echo "selected"; }?>>選択</option>
                                             <?php /*  <option value="8" <?php if($select_input_type == 8){ echo "selected"; }?>>チェックボックス</option> */ ?>
                                            <option value="9" <?php if($select_input_type == 9){ echo "selected"; }?>>ラジオボタン</option>
                                            <?php if( $question_num == 77) {?>
                                                <option value="10" <?php if($select_input_type == 10){ echo "selected"; }?>>対象者追加</option>
                                            <?php } ?>
                                            <option value="11" <?php if($select_input_type == 11){ echo "selected"; }?>>画像</option>
                                            <option value="12" <?php if($select_input_type == 12){ echo "selected"; }?>>送付先</option>
                                            <option value="13" <?php if($select_input_type == 13){ echo "selected"; }?>>代表画像選択</option>
                                    </select>
                                </div>

                                <div class="admin-spirit-edit-input-area">
                                    <input type="text" name="input_edit_name" id="input_edit_name" value="<?php echo get_field('acf_question_text' ,$_POST["edit_no"] );?>"/>
                                </div>

                            

                                 <div class="admin-spirit-edit-input-checkbox">
                                    <input type="checkbox" id="acf_question_required_id" name="acf_question_required" <?php if(get_field('acf_question_required' ,$_POST["edit_no"] )){ echo "checked";} ?>/>必須項目
                                </div>

                                <div class="admin-spirit-edit-input-checkbox">
                                    <input type="checkbox" id="acf_question_form_disp_id" name="acf_question_form_disp" <?php if(get_field('acf_question_form_disp' ,$_POST["edit_no"] )){ echo "checked";} ?>/>入力フォーム表示
                                </div>
                                <div class="admin-spirit-edit-input-checkbox">
                                    <input type="checkbox" id="acf_question_admin_disp_id" name="acf_question_admin_disp" <?php if(get_field('acf_question_admin_disp' ,$_POST["edit_no"] )){ echo "checked";} ?>/>管理画面表示
                                </div>
                                <div class="admin-spirit-edit-input-checkbox">
                                    <input type="checkbox" id="acf_question_counselor_disp_id" name="acf_question_counselor_disp" <?php if(get_field('acf_question_counselor_disp' ,$_POST["edit_no"] )){ echo "checked";} ?>/>カウンセラー表示
                                </div>

                                <div id="input_edit_text_area">
                                    <div class="admin-spirit-edit-input-add-title">
                                        入力時の補足テキストの際は入力欄に記載、テキストエリアの時は入力外に数字の場合は単位として表示されます。選択とセレクトボックス、ラジオボックスの場合は改行で一行ずつ入力してください)
                                    </div>
                                    <div class="admin-spirit-edit-input-add-box">
                                         <textarea id="input_add_text" name="input_add_text" rows="5" cols="33" style="width: 100%;"><?php echo get_field('acf_question_alert' ,$_POST["edit_no"] );?></textarea>
                                    </div>
                                </div>

                                <div id="img_edit_choice_area" style="display:none;">
                                    <?php 
            
                                        if(!empty($spiritStatusArray))
                                        {

                                            $img_choice_array = get_field('acf_question_img_choice',$_POST["edit_no"]);
                                    ?>
                                            <div class="admin-spirit-edit-input-add-title" style="margin-bottom: 10px;margin-top: 20px;">
                                                代表画像選択する質問を選んでください（2個以上をチェックすると、その質問の画像から１つ選択できます）
                                            </div>

                                    <?php
                                            if($img_choice_array == "")
                                            {
                                                $img_choice_array = array();
                                            }
                                            foreach ($spiritStatusArray[$question_num] as $key => $value) {

                                                if($value["type"] == 13){ continue; } //画像選択は入らない
                                                if($value["type"] != 11){ continue; } //画像選択はi以外は入らない
                                    ?>
                                        <div>
                                            <input type="checkbox" name="acf_questionqnser_img_choice[]" id="acf_questionqnser_img_choice" value="<?php echo $value["ID"];?>" <?php if(in_array($value["ID"],$img_choice_array)){ echo "checked";} ?> /><?php echo $value["text"];?>
                                        </div>
                                    <?php 
                                                
                                            }
                                        } 
                                    ?>

                                </div>

                            </div>

                            <input type="hidden" name="edit_no" id="edit_no" value="<?php echo $_POST["edit_no"];?>" />
						    <input type="hidden" name="chenge_id" id="chenge_id" value="<?php echo $_POST["edit_no"];?>" />
                            <input type="hidden" name="question_number" id="question_number" value="<?php echo $question_num;?>" />

                            <div class="admin-spirit-edit-inputbutton-area">
                                <input class="admin-spirit-edit-button" type="button" value="変更" onclick="saveItemData()"/>
                            </div>
                        

                        <?php } ?>

                    </div>

                    <?php if($edit_err != ""){ //エラー ?>

                         <div class="admin-spirit-edit-err-etr"><?php echo $edit_err;?></div>
                    <?php } ?>

                </form>
            </div>

        </div>

        <div class="admin-spirit-edit-list-area">


            
                <form id="orderForm" action="<?php echo  getURLSetSlag( "admin-exorcism-questions" );?>?type_id=<?php echo $question_num;?>" method="post" >
                      <input type="hidden" name="order" id="orderInput">
                </form>

                <table id="adminSpiritEditTableID" class="adminSpiritEditTable" >

                    <thead>
                        <tr>
                            <th>番号</th>
                            <th></th>
                            <th></th>
                            <th style="font-size: 15px;">タイプ</th>
                            <th>質問</th>
                            <th style="font-size: 11px;">必須</th>
                            <th style="font-size: 11px;">入力</th>
                            <th style="font-size: 11px;">管理</th>
                            <th style="font-size: 11px;">対応</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php 
    
                        $count = 1;

                        if(!empty($spiritStatusArray))
                        {

                            foreach ($spiritStatusArray[$question_num] as $key => $value) {
                    ?>

                            <tr data-id="<?php echo $value["ID"];?>" >

                                <td style="width: 60px;">
                                    <div class="admin-spirit-edit-list-id"><?php echo $count;?></div>
                                </td>
                                <td style="width: 30px;">
                                    <span class="admin-spirit-edit-updown" onclick="moveUp(this)">⬆️</span>
                                </td>
                                <td style="width: 30px;">
                                   <span class="admin-spirit-edit-updown" onclick="moveDown(this)">⬇️</span>
                                </td>
                                <td style="text-align: center;width: 60px;">
                                    <div class="admin-spirit-edit-type-str">
                                        <?php 
                                            if($value["type"] == 0 || $value["type"] == ""){ 
                                                echo "テキスト";
                                            }else if($value["type"] == 1){
                                                echo "テキスト<br>エリア";
                                            }else if($value["type"] == 2){
                                                echo "カレンダー";
                                            }else if($value["type"] == 4){
                                                echo "数字";
                                            }else if($value["type"] == 5){
                                                echo "メール";
                                            }else if($value["type"] == 6){
                                                echo "電話番号";
                                            }else if($value["type"] == 7){
                                                echo "選択";
                                            /*}else if($value["type"] == 8){
                                                echo "チェックボックス"; */ 
                                            }else if($value["type"] == 9){
                                                echo "ラジオボタン";
                                            }else if($value["type"] == 10 ){
                                                echo "対象者追加";
                                            }else if($value["type"] == 11 ){
                                                echo "画像";
                                            }else if($value["type"] == 12 ){
                                                echo "送付先";
                                            }else if($value["type"] == 13 ){
                                                echo "画像選択";
                                            }else{ 
                                                echo "名前";
                                            }
                                         ?>
                                   
                                   </div>

                                </td>
                                <td>
                                   <div class="admin-spirit-edit-list-title"><?php echo $value["text"];?></div>
                                   <?php if( $value["add"] != ""){ ?><div class="admin-spirit-edit-list-add-text">*<?php echo nl2br($value["add"]);?></div><?php } ?>
                                   <?php if( $value["img_choice"] != ""){ ?>
                                        <div class="admin-spirit-edit-list-add-text">
                                        
                                        <?php 
                                        
                                            $choice_array = $value["img_choice"];
                                        
                                            if(!empty($choice_array))
                                            {
                                                foreach($choice_array as $choice_key => $choice_value)
                                                {
                                                    echo get_field('acf_question_text',$choice_value) .",";
                                                }
                                            }
                                        
                                        ?>
                                        
                                    </div>
                                    <?php } ?>
                                </td>
                               <td style="width: 30px;text-align: center;">
                                   <?php if($value["required"]){echo "✅";}else{ echo "☐";}?>
                                </td>
                                <td style="width: 30px;text-align: center;">
                                   <?php if($value["form_disp"]){echo "✅";}else{ echo "☐";}?>
                                </td>
                                 <td style="width: 30px;text-align: center;">
                                   <?php if($value["admin_disp"]){echo "✅";}else{ echo "☐";}?>
                                </td>
                                <td style="width: 30px;text-align: center;">
                                   <?php if($value["counselor"]){echo "✅";}else{ echo "☐";}?>
                                </td>

                                <td style="width: 70px;">
                                     <form action="<?php echo  getURLSetSlag( "admin-exorcism-questions" );?>?type_id=<?php echo $question_num;?>" method="post" style="display:inline;">
                                        <input type="hidden" name="edit_no" id="edit_no" value="<?php echo  $value["ID"]; ?>" />
                                        <button class="admin-spirit-edit-list-edit-button" type="submit">編集</button>
                                    </form>
                                </td>
                                <td style="width: 70px;">
                                     <form action="<?php echo  getURLSetSlag( "admin-exorcism-questions" );?>?type_id=<?php echo $question_num;?>" id="delete_item_<?php echo  $value["ID"]; ?>" method="post" style="display:inline;">
                                        <input type="hidden" name="delete_no" id="delete_no" value="<?php echo  $value["ID"]; ?>" />
                                        <button  class="admin-spirit-edit-list-delete-button" type="button" onclick="saveItemDeleteData('<?php echo  $value["ID"]; ?>','<?php echo $value["text"];?>')">削除</button>
                                    </form>
                                </td>

                            </tr>
                     <?php
                                $count++;
                            }

                         }
                    ?>

                      
                       

                    </tbody>

                </table>


                 <div class="admin-spirit-return-button-area">
                     <button type=“button” class="admin-spirit-return-button" onclick="location.href='<?php echo getURLSetSlag("admin-question-list"); ?>'">戻る</button>
                 </div>

               
         </div>


    
   


    </div>


</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 新規追加フォーム
    var select1 = document.querySelector('form#add_item select[name="input_select_type"]');
    var addTextArea = document.getElementById('input_add_text_area');
    var imgChoiceArea = document.getElementById('img_choice_area');
    function updateArea1() {
        if (select1 && addTextArea && imgChoiceArea) {
            if (select1.value == '13') {
                addTextArea.style.display = 'none';
                imgChoiceArea.style.display = '';
            } else {
                addTextArea.style.display = '';
                imgChoiceArea.style.display = 'none';
            }
        }
    }
    if (select1) {
        select1.addEventListener('change', updateArea1);
        updateArea1();
    }
    // 編集フォーム
    var select2 = document.querySelector('form#edit_item select[name="input_select_type"]');
    var editTextArea = document.getElementById('input_edit_text_area');
    var imgEditChoiceArea = document.getElementById('img_edit_choice_area');
    function updateArea2() {
        if (select2 && editTextArea && imgEditChoiceArea) {
            if (select2.value == '13') {
                editTextArea.style.display = 'none';
                imgEditChoiceArea.style.display = '';
            } else {
                editTextArea.style.display = '';
                imgEditChoiceArea.style.display = 'none';
            }
        }
    }
    if (select2) {
        select2.addEventListener('change', updateArea2);
        updateArea2();
    }
});
</script>

