


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

        if($spirit_sheet_data->checkGeneralPurposeData( 0 , $_POST["input_add_name"]  , "cpt_inflow"))
        {
            $new_err = "同じ名前のものは登録できません";
        }
        else if($_POST["input_add_name"] != "")
        {
            $spirit_sheet_data->newGeneralPurposeData( $_POST["input_add_name"] , "cpt_inflow");
        }
        else{

            $new_err = "空白は登録できません";

        }
    }


    //編集
    if(isset($_POST["input_edit_name"]))
    {

        if($spirit_sheet_data->checkGeneralPurposeData( $_POST["edit_no"] , $_POST["input_edit_name"]  , "cpt_inflow"))
        {
            $edit_err = "同じ名前のものは登録できません";
        }
        else if($_POST["input_edit_name"] != "")
        {
            $spirit_sheet_data->saveGeneralPurposeData( $_POST["edit_no"] , $_POST["input_edit_name"] );
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

       

        $spirit_sheet_data->saveGeneralPurposeDataSort( $sort_array );

    }

    //削除
    if(isset($_POST["delete_no"]))
    {
        
        $spirit_sheet_data->deleteGeneralPurposeData( $_POST["delete_no"] );

    }


     $spiritStatusArray = $spirit_sheet_data->getGeneralPurposeData("cpt_inflow");
?>

<div class="admin-exorcism-area">




  
    <div class="admin-exorcism-button-area">

        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">流入元設定</div></div>


        <div class="admin-spirit-edit-area">

            <div class="admin-spirit-edit-box">

                <form action="<?php echo  getURLSetSlag( "admin-profile-inflow" );?>" name="add_item" method="post" id="add_item">

                    <div class="admin-spirit-edit-flex">


                        <div class="admin-spirit-edit-title">新規追加項目</div>
                  
                        <div class="admin-spirit-edit-input-area">
                            <input type="text" name="input_add_name" id="input_add_name" value=""/>
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

               
                <form action="<?php echo  getURLSetSlag( "admin-profile-inflow" );?>" name="add_item" method="post" id="edit_item">
                    <div class="admin-spirit-edit-flex">

                    
                        <div class="admin-spirit-edit-title">内容の編集</div>
                  
                        <?php  if(!isset($_POST["edit_no"])){?>
                            <div class="admin-spirit-edit-input-area">
                                <div class="admin-spirit-edit-input-str">リストから変更したい項目の右にある『編集』を押すと編集画面に切り替わります。</div>
                            </div>
                        <?php }else{ ?>

                       
                            <div class="admin-spirit-edit-input-area">
                                <input type="text" name="input_edit_name" id="input_edit_name" value="<?php echo get_field('acf_generalpurpose_name' ,$_POST["edit_no"] );?>"/>
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


            
                <form id="orderForm" action="<?php echo  getURLSetSlag( "admin-profile-inflow" );?>" method="post" >
                      <input type="hidden" name="order" id="orderInput">
                </form>

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
                                   <div class="admin-spirit-edit-list-title"><?php echo $value["title"];?></div>
                                </td>

                                <td style="width: 70px;">
                                     <form action="<?php echo  getURLSetSlag( "admin-profile-inflow" );?>" method="post" style="display:inline;">
                                        <input type="hidden" name="edit_no" id="edit_no" value="<?php echo  $value["ID"]; ?>" />
                                        <button class="admin-spirit-edit-list-edit-button" style="height: 28px;font-size: 14px;" type="submit">編集</button>
                                    </form>
                                </td>
                                <td style="width: 70px;">
                                     <form action="<?php echo  getURLSetSlag( "admin-profile-inflow" );?>" id="delete_item_<?php echo  $value["ID"]; ?>" method="post" style="display:inline;">
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


                 <div class="admin-spirit-return-button-area">
                     <button type=“button” class="admin-spirit-return-button" onclick="location.href='<?php echo getURLSetSlag("admin-profile-menu"); ?>'">戻る</button>
                 </div>

               
         </div>


    
   


    </div>


</div>

