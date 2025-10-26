


<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritInputCustomizeClass.php");


    $spirit_sheet_data = new SpiritInputCustomizeClass(); //管理データ



    $question_num = 0;

   


    $new_err = "";
    $edit_err = "";

      //var_dump($_POST);

     //新規
    if(isset($_POST["input_add_name"]))
    {

        if($_POST["input_add_name"] != "")
        {
            $target = "";
            $form_on = "";
            
            
            if(isset($_POST["acf_input_personal_data_disp"]))
            {
                $form_on = 1;
            }

            if(isset($_POST["acf_input_target"]))
            {
                $target = 1;
            }

            
            $spirit_sheet_data->newPersonalDataInput( $_POST["acf_input_personal_data_title"] ,  $_POST["acf_input_personal_data_disp_type"]  , $form_on ,$target);
        }
        else{

            $new_err = "空白は登録できません";

        }
    }


    //編集
    if(isset($_POST["chenge_id"]))
    {

        if($_POST["chenge_id"] != "")
        {

            $target = "";
            $form_on = "";
            
            if(isset($_POST["acf_input_personal_data_disp"]))
            {
                $form_on = 1;
            }

            if(isset($_POST["acf_input_target"]))
            {
                $target = 1;
            }


            $spirit_sheet_data->savePersonalDataInput( $_POST["edit_no"] ,$_POST["acf_input_personal_data_disp_type"], $_POST["acf_input_personal_data_title"], $form_on , $target );
            
            
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

       

        $spirit_sheet_data->sortPersonalDataInput( $sort_array );

    }

    //削除
    if(isset($_POST["delete_no"]))
    {
        $spirit_sheet_data->deletePersonalDataInput( $_POST["delete_no"] );

    }


     $spiritStatusArray = $spirit_sheet_data->getPersonalDataInput();

    // var_dump($spiritStatusArray);
?>

<div class="admin-exorcism-area">




  
    <div class="admin-exorcism-button-area">

        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">個人情報入力管理</div></div>


        <div class="admin-spirit-edit-area">

        <?php /*
            <div class="admin-spirit-edit-box">

                <form action="<?php echo  getURLSetSlag( "admin-exorcism-personaldata" );?>" name="add_item" method="post" id="add_item">

                    <div class="admin-spirit-edit-flex">


                        <div class="admin-spirit-edit-title">新規追加質問</div>
                  
                        <div class="admin-spirit-edit-input-box">

                            <div class="admin-spirit-edit-select-area">

                                <select name="acf_input_personal_data_disp_type">
                                    <option value="0">テキスト</option>
                                    <option value="1">カレンダー</option>
                                    <option value="2">名前</option>
                                    <option value="3">数字</option>
                                    <option value="4">メールアドレス</option>
                                    <option value="5">電話番号</option>
                                    <option value="6">住所</option>
                                    <option value="99">特殊</option>
                                </select>
                            </div>


                            <div class="admin-spirit-edit-input-area">
                                <input type="text" name="acf_input_personal_data_title" id="acf_input_personal_data_title" value=""/>
                            </div>
                            
                            <div class="admin-spirit-edit-input-checkbox">
                                <input type="checkbox" id="acf_input_personal_data_disp" name="acf_input_personal_data_disp" checked/>項目表示
                            </div>

                            <div class="admin-spirit-edit-input-checkbox">
                                <input type="checkbox" id="acf_input_target" name="acf_input_target" />対象者（申込者じゃない情報）
                            </div>

                         
                        </div>

                        
                        <input type="hidden" name="input_add_name" value="input_add_name" />
                    
                        <div class="admin-spirit-edit-inputbutton-area">
                            <input class="admin-spirit-edit-button" type="button" value="項目追加" onclick="saveItemAddData()"/>
                        </div>

                      </div>

                </form>

                <?php if($new_err != ""){ //エラー ?>
                    <div class="admin-spirit-edit-err-etr"><?php echo $new_err;?></div>
                <?php } ?>
            </div>
        */ ?>

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

               
                <form action="<?php echo  getURLSetSlag( "admin-exorcism-personaldata" );?>?" name="add_item" method="post" id="edit_item">
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
                                    
                                        $select_input_type = get_field('acf_input_personal_data_disp_type' ,$_POST["edit_no"] );
                                    
                                    ?>

                                    <select name="acf_input_personal_data_disp_type">
                                            <option value="0" <?php if($select_input_type == 0 || $select_input_type == ""){ echo "selected"; }?>>テキスト</option>
                                            <option value="1" <?php if($select_input_type == 1){ echo "selected"; }?>>カレンダー</option>
                                            <option value="2" <?php if($select_input_type == 2){ echo "selected"; }?>>名前</option>
                                            <option value="3" <?php if($select_input_type == 3){ echo "selected"; }?>>数字</option>
                                            <option value="4" <?php if($select_input_type == 4){ echo "selected"; }?>>メールアドレス</option>
                                            <option value="5" <?php if($select_input_type == 5){ echo "selected"; }?>>電話番号</option>
                                            <option value="6" <?php if($select_input_type == 6){ echo "selected"; }?>>住所</option>
                                            <option value="99" <?php if($select_input_type == 99){ echo "selected"; }?>>特殊</option>
                                    </select>
                                </div>

                                <div class="admin-spirit-edit-input-area">
                                    <input type="text" name="acf_input_personal_data_title" id="acf_input_personal_data_title" value="<?php echo get_field('acf_input_personal_data_title' ,$_POST["edit_no"] );?>"/>
                                </div>

                                 <div class="admin-spirit-edit-input-checkbox">
                                    <input type="checkbox" id="acf_input_personal_data_disp" name="acf_input_personal_data_disp" <?php if(get_field('acf_input_personal_data_disp' ,$_POST["edit_no"] )){ echo "checked";} ?>/>項目表示
                                </div>

                                <div class="admin-spirit-edit-input-checkbox">
                                    <input type="checkbox" id="acf_input_target" name="acf_input_target" <?php if(get_field('acf_input_target' ,$_POST["edit_no"] )){ echo "checked";} ?>/>対象者（申込者じゃない情報）
                                </div>
                            
                              
                            </div>

                            <input type="hidden" name="edit_no" id="edit_no" value="<?php echo $_POST["edit_no"];?>" />
						    <input type="hidden" name="chenge_id" id="chenge_id" value="<?php echo $_POST["edit_no"];?>" />

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


            
                <form id="orderForm" action="<?php echo  getURLSetSlag( "admin-exorcism-personaldata" );?>" method="post" >
                      <input type="hidden" name="order" id="orderInput">
                </form>

                <table id="adminSpiritEditTableID" class="adminSpiritEditTable" >

                    <thead>
                        <tr>
                            <th>番号</th>
                            <th></th>
                            <th></th>
                            <th style="font-size: 15px;">タイプ</th>
                            <th>項目</th>
                            <th style="font-size: 11px;">項目表示</th>
                            <th style="font-size: 14px;">対象者</th>
                            <th></th>
                           <?php /* <th></th> */ ?>
                        </tr>
                    </thead>

                    <tbody>

                    <?php 
    
                        $count = 1;

                        if(!empty($spiritStatusArray))
                        {

                            foreach ($spiritStatusArray as $key => $value) {
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
                                <td style="text-align: center;width: 100px;">
                                    <div class="admin-spirit-edit-type-str">
                                        <?php 
                                            if($value["acf_input_personal_data_disp_type"] == 0 || $value["acf_input_personal_data_disp_type"] == ""){ 
                                                echo "テキスト";
                                            }else if($value["acf_input_personal_data_disp_type"] == 1){
                                                echo "カレンダー";
                                            }else if($value["acf_input_personal_data_disp_type"] == 2){
                                                echo "名前";
                                            }else if($value["acf_input_personal_data_disp_type"] == 3){
                                                echo "数字";
                                            }else if($value["acf_input_personal_data_disp_type"] == 4){
                                                echo "メール";
                                           
                                            }else if($value["acf_input_personal_data_disp_type"] == 5){
                                                echo "電話番号";
                                            }else if($value["acf_input_personal_data_disp_type"] == 6){
                                                echo "住所";
                                            }else if($value["acf_input_personal_data_disp_type"] == 99){
                                                echo "特殊";
                                            }else{ 
                                                echo "";
                                            }
                                         ?>
                                   
                                   </div>

                                </td>
                                <td>
                                   <div class="admin-spirit-edit-list-title"><?php echo $value["acf_input_personal_data_title"];?></div>
                                </td>

                                <td style="width: 50px;text-align: center;">
                                   <?php if($value["acf_input_personal_data_disp"]){echo "✅";}else{ echo "☐";}?>
                                </td>

                                <td style="width: 50px;text-align: center;">
                                   <?php if($value["acf_input_target"]){echo "✅";}else{ echo "☐";}?>
                                </td>

                                 
                                <td style="width: 70px;">
                                     <form action="<?php echo  getURLSetSlag( "admin-exorcism-personaldata" );?>" method="post" style="display:inline;">
                                        <input type="hidden" name="edit_no" id="edit_no" value="<?php echo  $value["ID"]; ?>" />
                                        <button class="admin-spirit-edit-list-edit-button" type="submit">編集</button>
                                    </form>
                                </td>
                                <?php /*
                                <td style="width: 70px;">
                                     <form action="<?php echo  getURLSetSlag( "admin-exorcism-personaldata" );?>" id="delete_item_<?php echo  $value["ID"]; ?>" method="post" style="display:inline;">
                                        <input type="hidden" name="delete_no" id="delete_no" value="<?php echo  $value["ID"]; ?>" />
                                        <button  class="admin-spirit-edit-list-delete-button" type="button" onclick="saveItemDeleteData('<?php echo  $value["ID"]; ?>','<?php echo $value["acf_input_personal_data_title"];?>')">削除</button>
                                    </form>
                                </td>
                                */ ?>

                            </tr>
                     <?php
                                $count++;
                            }

                         }
                    ?>

                      
                       

                    </tbody>

                </table>


                 <div class="admin-spirit-return-button-area">
                     <button type=“button” class="admin-spirit-return-button" onclick="location.href='<?php echo getURLSetSlag("admin-profile-menu"); ?>'">戻る</button>
                 </div>

               
         </div>


    
   


    </div>


</div>