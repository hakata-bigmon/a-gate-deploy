
<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/GroupSettingClass.php");
    $group_setting_data = new GroupSettingClass(); //グループクラス

    //エラー出力
    $new_err = "";
    $edit_err = "";
    $group_list = array();


    //新規登録
    if (isset($_POST["updata_unix_time"])) {
        $tilte = str_replace(" ", "", $_POST["input_group_name"]);
        $tilte = str_replace("　", "", $tilte);

        if ($tilte == "") {
            $err_cord = "グループ名が入力されていません";
        } else {
            if ($group_setting_data->makeGroup($_POST["input_group_name"], $_POST["updata_unix_time"])) {
                $err_cord = "新規でデータを追加しました";
            } else {
                $err_cord = "すでに同じデータが追加されています";
            }
        }

    }

    //編集
    if(isset($_POST["input_edit_name"]))
    {

        $name_check = $group_setting_data->checkGroupStatus( $_POST["edit_no"] , $_POST["input_edit_name"] );
        if($name_check)
        {
            $edit_err = "同じ名前のものは登録できません";
        }
        else if($_POST["input_edit_name"] != "")
        {
            $group_setting_data->saveGroupTitle( $_POST["edit_no"] , $_POST["input_edit_name"] );
        }
        else{

            $edit_err = "空白は登録できません";

        }

    }

    //削除
    if(isset($_POST["delete_no"]))
    {
        
        $group_setting_data->deleteGrouptatus( $_POST["delete_no"] );

    }

    //並び順保存
    if(isset($_POST["order"]))
    {
        $sort_array = array();
        
        $sort_array = explode(',', $_POST["order"]);

        $group_setting_data->saveGroupStatusSort($sort_array);
    }

    $group_list = $group_setting_data->getGroupData();

?>
<script src="<?php echo get_template_directory_uri()."/custompage/admin/a-gate-script.js"?>"></script>


<div class="admin-exorcism-button-area">

    <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">グループ項目編集</div></div>

	<div class="admin-spirit-edit-area">

		<div class="admin-spirit-edit-box">

            <form class="" action="" name="" method="post" id="add_item">

                <div class="admin-spirit-edit-flex">

                    <div class="admin-spirit-edit-title">新規追加項目</div>

                    <div class="admin-spirit-edit-input-area">

                        <input type="text" name="input_group_name" class="input-group-setting" />
                    </div>


                    <input type="hidden" name="updata_unix_time" id="updata_unix_time" value="<?php echo getUnixTime(); ?>" />

                    <div class="admin-spirit-edit-inputbutton-area">
                        <input class="admin-spirit-edit-button" type="button" value="項目追加" onclick="saveItemAddData()"/>
                    </div>
                    <!-- <button  class="tin-submit-btn">項目追加</button> -->

                </div>
                
                <?php if($new_err != ""){ //エラー ?>
                    <div class="admin-spirit-edit-err-etr"><?php echo $new_err;?></div>
                <?php } ?>
            </form>
        
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

            <form action="<?php echo  getURLSetSlag( "admin-profile-grope" );?>" name="add_item" method="post" id="edit_item">
                <div class="admin-spirit-edit-flex">

                
                    <div class="admin-spirit-edit-title">内容の編集</div>
            
                    <?php  if(!isset($_POST["edit_no"])){?>
                        <div class="admin-spirit-edit-input-area">
                            <div class="admin-spirit-edit-input-str">リストから変更したい項目の右にある『編集』を押すと編集画面に切り替わります。</div>
                        </div>
                    <?php }else{ ?>

                
                        <div class="admin-spirit-edit-input-area">
                            <input type="text" name="input_edit_name" id="input_edit_name" value="<?php echo get_field('acf_group_setting_name' ,$_POST["edit_no"] );?>"/>
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
            
    <form id="orderForm" action="<?php echo  getURLSetSlag( "admin-profile-grope" );?>" method="post" >
        <input type="hidden" name="order" id="orderInput">
    </form>

    <table id="adminSpiritEditTableID" class="adminSpiritEditTable" >
        <tbody>

            <?php 

                $count = 1;

                if(empty($group_list)){
                    echo "グループはありません。";
                } else{

                    foreach ($group_list as $key => $value) { 
                        if($value["is_delete"]) continue;
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
                        <form action="<?php echo  getURLSetSlag( "admin-profile-grope" );?>" method="post" style="display:inline;">
                            <input type="hidden" name="edit_no" id="edit_no" value="<?php echo  $value["ID"]; ?>" />
                            <button class="admin-spirit-edit-list-edit-button" type="submit">編集</button>
                        </form>
                    </td>
                    <td style="width: 70px;">
                        <form action="<?php echo  getURLSetSlag( "admin-profile-grope" );?>" id="delete_item_<?php echo  $value["ID"]; ?>" method="post" style="display:inline;">
                            <input type="hidden" name="delete_no" id="delete_no" value="<?php echo  $value["ID"]; ?>" />
                            <button  class="admin-spirit-edit-list-delete-button" type="button" onclick="saveItemDeleteData('<?php echo  $value['ID']; ?>','<?php echo $value['title'];?>')">削除</button>
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


</div>

<button class="form-btn"><a href="<?php echo getURLSetSlag("admin-profile-menu"); ?>" class="">プロフィール設定へ戻る</a></button>
