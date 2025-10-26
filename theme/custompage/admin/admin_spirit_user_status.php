


<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");


    $spirit_sheet_data = new spiritSheetClass(); //管理データ


    $new_err = "";
    $edit_err = "";

    //  var_dump($_POST);

     //新規
    if(isset($_POST["input_add_name"]))
    {

        if($spirit_sheet_data->checkSpiritAdminStatus( 0 , $_POST["input_add_name"] ,'cpt_spirit_usestatus') )
        {
            $new_err = "同じ名前のものは登録できません";
        }
        else if($_POST["input_add_name"] != "")
        {
            $spirit_sheet_data->newSpiritAdminStatus( $_POST["input_add_name"]   , $_POST["input_edit_text_color"] , $_POST["input_edit_back_color"] , 'cpt_spirit_usestatus', $_POST["input_add_disp_name"]);
        }
        else{

            $new_err = "空白は登録できません";

        }
    }


    //編集
    if(isset($_POST["input_edit_name"]))
    {

        if($spirit_sheet_data->checkSpiritAdminStatus( $_POST["edit_no"] , $_POST["input_edit_name"] , 'cpt_spirit_usestatus' ) )
        {
            $edit_err = "同じ名前のものは登録できません";
        }
        else if($_POST["input_edit_name"] != "")
        {
            $spirit_sheet_data->saveSpiritAdminStatusTitle( $_POST["edit_no"] , $_POST["input_edit_name"]  , $_POST["input_edit_text_color"] , $_POST["input_edit_back_color"], $_POST["input_edit_disp_name"]);
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

       

        $spirit_sheet_data->saveSpiritAdminStatusSort( $sort_array );

    }

    //削除
    if(isset($_POST["delete_no"]))
    {
        
        $spirit_sheet_data->deleteSpiritAdminStatus( $_POST["delete_no"] );

    }


     $spiritStatusArray = $spirit_sheet_data->getSpiritAdminStatus('cpt_spirit_usestatus');
?>

<div class="admin-exorcism-area">




  
    <div class="admin-exorcism-button-area">

        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">会員　浄霊・鑑定ステータス設定</div></div>


        <div class="admin-spirit-edit-area">

            <div class="admin-spirit-edit-box">

                <form action="<?php echo  getURLSetSlag( "admin_spirit_user_status" );?>" name="add_item" method="post" id="add_item">

                    <div class="admin-spirit-edit-flex">


                        <div class="admin-spirit-edit-title">新規追加項目</div>
                  
                        <div class="admin-spirit-edit-input-area">
                            管理者表示<input type="text" name="input_add_name" id="input_add_name" value=""/>
                            お客様表示<input type="text" name="input_add_disp_name" id="input_add_disp_name" value=""/>
                            

                            <div class="admin-spirit-edit-inputbutton-area" style="margin-left: 0;">
                               
                                <div class="admin-spirit-edit-input-color-area">
                                    文字　<input type="color" name="input_edit_text_color" id="newColorPicker" value="#000000"/>
                                </div>

                                <div class="admin-spirit-edit-input-color-area">
                                    背景　<input type="color" name="input_edit_back_color" id="newBackcolorPicker" value="#ffffff"/>

                                    <div class="admin-spirit-edit-input-back-color"  id="newBack_color" style="">
                                        <div id="newText_color" style="">文字色</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="admin-spirit-edit-inputbutton-area">
                            <input class="admin-spirit-edit-button" type="button" value="項目追加" onclick="saveItemAddData()"/>
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

               
                <form action="<?php echo  getURLSetSlag( "admin_spirit_user_status" );?>" name="add_item" method="post" id="edit_item">
                    <div class="admin-spirit-edit-flex">

                    
                        <div class="admin-spirit-edit-title">内容の編集</div>
                  
                        <?php  if(!isset($_POST["edit_no"])){?>
                            <div class="admin-spirit-edit-input-area">
                                <div class="admin-spirit-edit-input-str">リストから変更したい項目の右にある『編集』を押すと編集画面に切り替わります。</div>
                            </div>
                        <?php }else{ ?>

                       
                            <div class="admin-spirit-edit-input-area">
                                <div>
                                    管理者表示
                                    <input type="text" name="input_edit_name" id="input_edit_name" value="<?php echo get_field('acf_pure_spirit_status_name' ,$_POST["edit_no"] );?>"/>
                                </div>
                                <div>
                                    お客様表示
                                    <input type="text" name="input_edit_disp_name" id="input_edit_disp_name" value="<?php echo get_field('acf_pure_spirit_status_disp_name' ,$_POST["edit_no"] );?>"/>
                                </div>
                                <div class="admin-spirit-edit-input-color-area">
                                    <?php 
                                    
                                        $text_color = get_field('acf_pure_spirit_status_text_color' ,$_POST["edit_no"] );

                                        if($text_color =="")
                                        {
                                            $text_color = "#000000f";
                                        }
                                    
                                    ?>

                                    文字　<input type="color" name="input_edit_text_color" id="colorPicker" value="<?php echo $text_color;?>"/>
                                   
                                </div>
                                <div class="admin-spirit-edit-input-color-area">
                                    <?php 
                                    
                                        $back_color = get_field('acf_pure_spirit_status_back_color' ,$_POST["edit_no"] );

                                        if($back_color =="")
                                        {
                                            $back_color = "#ffffff";
                                        }
                                    
                                    ?>
                                    背景　<input type="color" name="input_edit_back_color" id="backcolorPicker" value="<?php echo $back_color;?>"/>
                                    <div class="admin-spirit-edit-input-back-color"  id="back_color" style="background-color:<?php echo $back_color;?>">
                                        <div id="text_color" style="color:<?php echo $text_color;?>">文字色</div>
                                    </div>
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


            
                <form id="orderForm" action="<?php echo  getURLSetSlag( "admin_spirit_user_status" );?>" method="post" >
                      <input type="hidden" name="order" id="orderInput">
                </form>

                <?php if($spiritStatusArray != ""){ ?>

                    <table id="adminSpiritEditTableID" class="adminSpiritEditTable" >

                        <tbody>

                        <?php 
    
                        
                            $count = 1;

                            foreach ($spiritStatusArray as $key => $value) {
                        ?>

                                <tr data-id="<?php echo $value["ID"];?>" >

                                    <td style="width: 60px;">
                                        <div class="admin-spirit-edit-list-id"><?php echo $key;?></div>
                                    </td>
                                    <td style="width: 30px;">
                                        <span class="admin-spirit-edit-updown" onclick="moveUp(this)">⬆️</span>
                                    </td>
                                    <td style="width: 30px;">
                                       <span class="admin-spirit-edit-updown" onclick="moveDown(this)">⬇️</span>
                                    </td>

                                    <td>
                                        <?php 
                                    
                                            $text_color = get_field('acf_pure_spirit_status_text_color' ,$value["ID"] );
                                            $back_color = get_field('acf_pure_spirit_status_back_color' ,$value["ID"] );

                                            if($back_color =="")
                                            {
                                                $back_color = "#ffffff";
                                            }

                                            if($text_color =="")
                                            {
                                                $text_color = "#000000";
                                            }
                                    
                                        ?>


                                       <div class="admin-spirit-edit-list-title" style="background-color:<?php echo $back_color;?>;color:<?php echo $text_color;?>;"><div style="padding-left: 10px;padding-top: 5px;"><?php echo $value["title"];?></div></div>
                                    </td>


                                    <td>
                                        <?php echo $value["disp_title"];?>
                                    </td>

                                    <td style="width: 70px;">
                                         <form action="<?php echo  getURLSetSlag( "admin_spirit_user_status" );?>" method="post" style="display:inline;">
                                            <input type="hidden" name="edit_no" id="edit_no" value="<?php echo  $value["ID"]; ?>" />
                                            <button class="admin-spirit-edit-list-edit-button" type="submit">編集</button>
                                        </form>
                                    </td>
                                    <td style="width: 70px;">
                                         <form action="<?php echo  getURLSetSlag( "admin_spirit_user_status" );?>" id="delete_item_<?php echo  $value["ID"]; ?>" method="post" style="display:inline;">
                                            <input type="hidden" name="delete_no" id="delete_no" value="<?php echo  $value["ID"]; ?>" />
                                            <button  class="admin-spirit-edit-list-delete-button" type="button" onclick="saveItemDeleteData('<?php echo  $value["ID"]; ?>','<?php echo $value["title"];?>')">削除</button>
                                        </form>
                                    </td>

                                </tr>
                         <?php
                                $count++;

                             }
                        ?>

                      
                       

                        </tbody>

                    </table>
                <?php } ?>

                 <div class="admin-spirit-return-button-area">
                     <button type=“button” class="admin-spirit-return-button" onclick="location.href='<?php echo getURLSetSlag("admin-exorcism-list"); ?>'">戻る</button>
                 </div>

               
         </div>


    
   


    </div>


</div>


<script>
        // カラー入力要素と文字要素を取得
        const newcolorPicker = document.getElementById('newColorPicker');
        const newbackcolorPicker = document.getElementById('newBackcolorPicker');
        const newtext = document.getElementById('newText_color');
        const newbacktext = document.getElementById('newBack_color');

        // カラーが変更されたときに文字色を変更
        newcolorPicker.addEventListener('input', function() {
            newtext.style.color = newcolorPicker.value;
        });
       
        
        // カラーが変更されたときに文字色を変更
        newbackcolorPicker.addEventListener('input', function() {
            newbacktext.style.backgroundColor  = newbackcolorPicker.value;
        });

</script>

<script>
        // カラー入力要素と文字要素を取得
        const colorPicker = document.getElementById('colorPicker');
        const backcolorPicker = document.getElementById('backcolorPicker');
        const text = document.getElementById('text_color');
        const backtext = document.getElementById('back_color');

        // カラーが変更されたときに文字色を変更
        colorPicker.addEventListener('input', function() {
            text.style.color = colorPicker.value;
        });
       
        
        // カラーが変更されたときに文字色を変更
        backcolorPicker.addEventListener('input', function() {
            backtext.style.backgroundColor  = backcolorPicker.value;
        });

</script>