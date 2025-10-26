<?php

require_once ("a-gate-functions.php");

require_once (dirname(__FILE__)."/../../class/TemporaryRegistrationClass.php");
$TemporaryRegistration = new TemporaryRegistrationClass(); //管理データ




$emporary_registration_array = array();

//確認用
if( isset($_POST["re_edit"]) )
{
     foreach ($_POST as $key => $value) {

        $emporary_registration_array[ $key ] = $value;

     }
}



//編集
if( isset($_GET["temporary_id"]) )
{
    $group_id = '936';
    $fields = acf_get_fields($group_id);


    //編集保存
    if( isset($_POST["edit_data"]) )
    {
        foreach ($fields as $field => $data) {

            if(isset( $_POST[ $data["name"] ] ))
            {
                update_field(  $data["name"] , $_POST[ $data["name"] ] , $_GET["temporary_id"]);
            }
        }
    }

    foreach ($fields as $field => $data) {

        $emporary_registration_array[ $data["name"] ] = get_field( $data["name"], $_GET["temporary_id"]);

    }
}


?>


<div class="admin-profile-edit-area" style="max-width: 1700px;">

    <?php if(!isset($_POST["check_data"])){?>

        <div class="admin-profile-edit-title-box">
            <?php if( isset($_GET["temporary_id"]) ){ ?>
                <div class="admin-exorcism-menu-title" style="text-align: center;">枠数登録 編集</div>
            <?php }else{ ?>
                <div class="admin-exorcism-menu-title" style="text-align: center;">枠数登録 新規</div>
            <?php } ?>
        </div>

         <form action="<?php echo getURLSetSlag("admin-temporary-registration-list"); ?>" method="post" enctype="multipart/form-data" style="text-align: center;">  
            <button type="submit"  class="admin-temporary-registration-return-btn">一覧に戻る</button>
        </form>

        <?php if( !isset($_GET["temporary_id"]) ){ ?>
            <div class="admin-temporary-registration-file-post-area">

                <form action="<?php echo getURLSetSlag("admin-temporary-registration-list"); ?>" method="post" enctype="multipart/form-data">
                    
                <div style="display: flex;">
                     <div style="margin-right: 10px;">オーダー日<input type="date" name="order_date" value="" style="margin-left: 5px;margin-right: 5px;" required >以降</div >
                     <div style="margin-right: 10px;">決済完了日<input type="date" name="pay_date" value="" style="margin-left: 5px;margin-right: 5px;" >以降</div >　
                     <div style="margin-right: 10px;"><input type="file" name="csvFile" accept=".csv"  class="admin-temporary-registration-file-post-file" required ></div >
                     <input type="hidden" name="csvCheck" value>
                     <div><button type="submit"  class="admin-temporary-registration-file-post-submit">CSVをアップロード</button></div >
                </div >
                </form>
            </div>
        <?php } ?>


        <form action="<?php echo getURLSetSlag("admin-temporary-registration-new"); ?><?php if(isset($_GET["temporary_id"])){ ?>?temporary_id=<?php echo $_GET["temporary_id"];?><?php } ?>" method="post" enctype="multipart/form-data">
            <div class="admin-temporary-registration-input-area">

                <div class="user-table-flex">
                    <div class="user-table-item"><font color="red">*</font>オーダー番号</div>
                    <input class="" type="number" name="acf_temporary_order_num" value="<?php if(isset($emporary_registration_array["acf_temporary_order_num"])){ echo $emporary_registration_array["acf_temporary_order_num"]; }?>" required>
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item"><font color="red">*</font>支払い方法</div>
                    <input style="width: 500px;" type="text" name="acf_temporary_payment" value="<?php if(isset($emporary_registration_array["acf_temporary_payment"])){ echo $emporary_registration_array["acf_temporary_payment"]; }?>" required>
                </div>

                 <div class="user-table-flex">
                    <div class="user-table-item"><font color="red">*</font>オーダー日</div>
                    <input  type="text" name="acf_temporary_order_day" value="<?php if(isset($emporary_registration_array["acf_temporary_order_day"])){ echo $emporary_registration_array["acf_temporary_order_day"]; }?>" required>
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">決済完了日</div>
                    <input class="" type="text" name="acf_temporary_payment_end" value="<?php if(isset($emporary_registration_array["acf_temporary_payment_end"])){ echo $emporary_registration_array["acf_temporary_payment_end"]; }?>" >
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item"><font color="red">*</font>アイテム名</div>
                    <input style="width: 500px;" type="text" name="acf_temporary_item_name" value="<?php if(isset($emporary_registration_array["acf_temporary_item_name"])){ echo $emporary_registration_array["acf_temporary_item_name"]; }?>" required>
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">品板</div>
                    <input style="width: 500px;" type="text" name="acf_temporary_product_number" value="<?php if(isset($emporary_registration_array["acf_temporary_product_number"])){ echo $emporary_registration_array["acf_temporary_product_number"]; }?>" >
                </div>

                 <div class="user-table-flex">
                    <div class="user-table-item"><font color="red">*</font>小計</div>
                    <input class="" type="number" name="acf_temporary_subtotal" value="<?php if(isset($emporary_registration_array["acf_temporary_subtotal"])){ echo $emporary_registration_array["acf_temporary_subtotal"]; }?>" required>
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">販売タイプ</div>
                    <input style="width: 500px;" type="text" name="acf_temporary_type" value="<?php if(isset($emporary_registration_array["acf_temporary_type"])){ echo $emporary_registration_array["acf_temporary_type"]; }?>" >
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">合計</div>
                    <input class="" type="number" name="acf_temporary_total_pay" value="<?php if(isset($emporary_registration_array["acf_temporary_total_pay"])){ echo $emporary_registration_array["acf_temporary_total_pay"]; }?>" >
                </div>


                <div class="user-table-flex">
                    <div class="user-table-item"><font color="red">*</font>氏</div>
                    <input class="" type="text" name="acf_temporary_last_name" value="<?php if(isset($emporary_registration_array["acf_temporary_last_name"])){ echo $emporary_registration_array["acf_temporary_last_name"]; }?>" required>
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item"><font color="red">*</font>名</div>
                    <input class="" type="text" name="acf_temporary_first_name" value="<?php if(isset($emporary_registration_array["acf_temporary_first_name"])){ echo $emporary_registration_array["acf_temporary_first_name"]; }?>" required>
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">郵便番号</div>
                    <input class="" type="text" name="acf_temporary_post_number" value="<?php if(isset($emporary_registration_array["acf_temporary_post_number"])){ echo $emporary_registration_array["acf_temporary_post_number"]; }?>" >
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">都道府県</div>
                    <input class="" type="text" name="acf_temporary_address_1" value="<?php if(isset($emporary_registration_array["acf_temporary_address_1"])){ echo $emporary_registration_array["acf_temporary_address_1"]; }?>" >
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">住所</div>
                    <input style="width: 700px;" type="text" name="acf_temporary_address_2" value="<?php if(isset($emporary_registration_array["acf_temporary_address_2"])){ echo $emporary_registration_array["acf_temporary_address_2"]; }?>" >
                </div>

                 <div class="user-table-flex">
                    <div class="user-table-item"><font color="red">*</font>電話番号</div>
                    <input class="" type="text" name="acf_temporary_tel" value="<?php if(isset($emporary_registration_array["acf_temporary_tel"])){ echo $emporary_registration_array["acf_temporary_tel"]; }?>" required>
                </div>

                 <div class="user-table-flex">
                    <div class="user-table-item"><font color="red">*</font>メールアドレス</div>
                    <input style="width: 500px;" type="mail" name="acf_temporary_mail" value="<?php if(isset($emporary_registration_array["acf_temporary_mail"])){ echo $emporary_registration_array["acf_temporary_mail"]; }?>" required>
                </div>

            

                <?php if( isset($_GET["temporary_id"]) ){ ?>
                    <input type="hidden" name="edit_data" value>

                    <div class="admin-temporary-registration-submit-center">
                        <button type="submit"  class="admin-temporary-registration-check-post-submit">保存する</button>
                    </div>

                <?php }else{ ?>
                    <input type="hidden" name="check_data" value>

                    <div class="admin-temporary-registration-submit-center">
                        <button type="submit"  class="admin-temporary-registration-check-post-submit">確認画面へ</button>
                    </div>
                 <?php } ?>
            </div>
        </form>

        <div class="admin-profile-edit-title-box" style="margin-top: 50px;">
            <div class="admin-exorcism-menu-title" style="text-align: center;">ユーザー登録</div>
        </div>

        <?php 
        
            //ユーザーの全データを取得
	        $users = get_users( array('orderby'=>'ID','order'=>'ASC') );


            $user_candidate = array();


            $is_mail_user = false;
            
            //候補を探す
            foreach ($users as $key => $value) 
            {
                $id = $value->ID;

                $last_name = get_user_meta($id,'last_name',true);
                $first_name = get_user_meta($id,'first_name',true);

                $check_last_name = "";
                $check_first_name_kana = "";
                $check_mail = "";

                if(isset($emporary_registration_array["acf_temporary_last_name"])){ $check_last_name = $emporary_registration_array["acf_temporary_last_name"]; }
                if(isset($emporary_registration_array["acf_temporary_first_name"])){ $check_first_name_kana =  $emporary_registration_array["acf_temporary_first_name"]; }
                if(isset($emporary_registration_array["acf_temporary_mail"])){ $check_mail =  $emporary_registration_array["acf_temporary_mail"]; }


                if($check_mail ==  $value->user_email)
                {
                     $is_mail_user = true;
                }

                if($last_name == $check_last_name && $first_name == $check_first_name_kana)
                {
                    array_push($user_candidate,$id);
                    continue;
                }

                if($check_mail ==  $value->user_email)
                {
                   
                    array_push($user_candidate,$id);
                    continue;
                }
            }

            
        ?>

       
        

        <div style="max-width: 1000px;margin-left: auto;margin-right: auto;">

            <?php if (count($user_candidate) > 0) { ?>
                <table class="admin-remote-sprit-registration-setnumber-table">

                    <tr>
                        <th>ID</th>
                            
                        <th>氏名</th>
                        <th>メールアドレス</th>
                        <th>連絡先</th>
                        <th>住所</th>
                        <th></th>
                    </tr>


                    <?php  foreach ($user_candidate as $key => $value){?>

                        <?php 
                    
                            $target_user = get_userdata( $value ); 

                        ?>

                        <tr>
                            <td>
                                <a href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $value;?>"  target="_blank">
                                    <?php echo $value;?>
                                </a>
                            </td>
                        
                            <td>
                                <a href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $value;?>"  target="_blank">
                                    <?php echo get_user_meta($value,'last_name',true); ?>　<?php echo get_user_meta($value,'first_name',true); ?>
                                </a>
                            </td>
                            <td><?php echo $target_user->user_email; ?></td>
                            <td><?php echo get_user_meta($value,'billing_phone',true); ?>-<?php echo get_user_meta($value,'billing_phone2',true); ?>-<?php echo get_user_meta($value,'billing_phone3',true); ?></td>
                            <td>
                                 〒<?php echo get_user_meta($value,'billing_postcode',true); ?><br><?php echo get_user_meta($value,'billing_city',true); ?><?php echo get_user_meta($value,'billing_address_1',true); ?>
                            </td>
                            <td>
                                <form action="<?php echo getURLSetSlag("admin-temporary-registration-list"); ?>" method="post" onSubmit="return register_check()">
                                    <input type="hidden" name="set_id" value="<?php echo $value;?>">
                                    <input type="hidden" name="temporary_id" value="<?php echo $_GET["temporary_id"];?>">
                                    <input type="hidden" name="set_target_one" value="">
                                    <button type="submit" class="edit-mark" style="width: 110px;font-size: 14px;">候補を選択</button>
                                </form>
                            </td>
                            </tr>
                    <?php } ?>

                </table>
                <form action="<?php echo getURLSetSlag("admin-remote-sprit-registration-detail"); ?>" method="post" >
                    <input type="hidden" name="sheet_id" value="<?php echo $_GET["temporary_id"];?>">
                    <input type="hidden" name="target_list_on" value="">
                    <input type="hidden" name="target_list" value="">
                    <button type="submit" class="edit-mark" style="width: 190px;font-size: 14px;background-color: #ef6363;margin: 0;margin-top: 5px;">ユーザー一覧から選択する</button>
                </form>

            <?php } ?>
                <?php if($is_mail_user == false){?>
                    <form action="<?php echo getURLSetSlag("admin-temporary-registration-list"); ?>" method="post" onSubmit="return register_check()">
                        <input type="hidden"  name="set_registration">
                        <input type="hidden" name="temporary_registration[]" value="<?php echo $_GET["temporary_id"];?>">
                        <button type="submit" class="edit-mark" style="width: 500px;font-size: 20px;background-color: red;height: 56px;;margin-top: 40px;border-radius: 15px;">新規で登録する</button>
                    </form>
                <?php }else{ ?>

                    <div style="text-align: center;margin-top: 50px;font-size: 18px;color: red;">
                        メールアドレスが一致しているものがある場合は新規で登録する事はできません
                    </div>


                <?php } ?>

        </div>
       





        <div class="admin-profile-edit-title-box" style="margin-top: 50px;">
            <div class="admin-exorcism-menu-title" style="text-align: center;">削除</div>
        </div>


        <div class="admin-temporary-registration-submit-delete-area">
            <?php if( isset($_GET["temporary_id"]) ){ ?>
                <form action="<?php echo getURLSetSlag("admin-temporary-registration-list"); ?>" method="post" onSubmit="return delete_check()">
                     <input type="hidden" name="delete_id" value="<?php echo $_GET["temporary_id"];?>">
                     <button type="submit"  class="admin-temporary-registration-delete-post-submit">削除する</button>
                </form>
             <?php } ?>
         </div>


    <?php }else{ ?>


        <div class="admin-profile-edit-title-box">
            <div class="admin-exorcism-menu-title" style="text-align: center;">仮登録 確認</div>
        </div>

        <?php 
            if($TemporaryRegistration->IsTemporaryRegistrationOrderNumber( $_POST["acf_temporary_order_num"])){
        ?>
            <div class="admin-temporary-registration-updata-alert">
                このオーダー番号はすでに登録されている為、上書きされますので、ご確認ください。
            </div>

        <?php
            }
        
        
        ?>

        <form action="<?php echo getURLSetSlag("admin-temporary-registration-list"); ?>" method="post" onSubmit="return registration_check()" >
            <div class="admin-temporary-registration-input-area">

                <div class="user-table-flex">
                    <div class="user-table-item">オーダー番号</div>
                    <div class=""><?php  echo $_POST["acf_temporary_order_num"]; ?></div>
                    <input type="hidden" name="acf_temporary_order_num" value="<?php echo $_POST["acf_temporary_order_num"];?>" >
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">支払い方法</div>
                    <div class=""><?php  echo $_POST["acf_temporary_payment"]; ?></div>
                    <input type="hidden" name="acf_temporary_payment" value="<?php echo $_POST["acf_temporary_payment"];?>" >
                </div>

                 <div class="user-table-flex">
                    <div class="user-table-item">オーダー日</div>
                    <div class=""><?php  echo $_POST["acf_temporary_order_day"]; ?></div>
                    <input type="hidden" name="acf_temporary_order_day" value="<?php echo $_POST["acf_temporary_order_day"];?>" >
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">決済完了日</div>
                    <div class=""><?php  echo $_POST["acf_temporary_payment_end"]; ?></div>
                    <input type="hidden" name="acf_temporary_payment_end" value="<?php echo $_POST["acf_temporary_payment_end"];?>" >
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">アイテム名</div>
                    <div class=""><?php  echo $_POST["acf_temporary_item_name"]; ?></div>
                    <input type="hidden" name="acf_temporary_item_name" value="<?php echo $_POST["acf_temporary_item_name"];?>" >
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">品板</div>
                    <div class=""><?php  echo $_POST["acf_temporary_product_number"]; ?></div>
                    <input type="hidden" name="acf_temporary_product_number" value="<?php echo $_POST["acf_temporary_product_number"];?>" >
                </div>

                 <div class="user-table-flex">
                    <div class="user-table-item">小計</div>
                    <div class=""><?php  echo $_POST["acf_temporary_subtotal"]; ?></div>
                    <input type="hidden" name="acf_temporary_subtotal" value="<?php echo $_POST["acf_temporary_subtotal"];?>" >
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">販売タイプ</div>
                    <div class=""><?php  echo $_POST["acf_temporary_type"]; ?></div>
                    <input type="hidden" name="acf_temporary_type" value="<?php echo $_POST["acf_temporary_type"];?>" >
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">合計</div>
                    <div class=""><?php  echo $_POST["acf_temporary_total_pay"]; ?></div>
                    <input type="hidden" name="acf_temporary_total_pay" value="<?php echo $_POST["acf_temporary_total_pay"];?>" >
                </div>


                <div class="user-table-flex">
                    <div class="user-table-item">氏</div>
                    <div class=""><?php  echo $_POST["acf_temporary_last_name"]; ?></div>
                    <input type="hidden" name="acf_temporary_last_name" value="<?php echo $_POST["acf_temporary_last_name"];?>" >
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">名</div>
                    <div class=""><?php  echo $_POST["acf_temporary_first_name"]; ?></div>
                    <input type="hidden" name="acf_temporary_first_name" value="<?php echo $_POST["acf_temporary_first_name"];?>" >
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">郵便番号</div>
                    <div class=""><?php  echo $_POST["acf_temporary_post_number"]; ?></div>
                    <input type="hidden" name="acf_temporary_post_number" value="<?php echo $_POST["acf_temporary_post_number"];?>" >
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">都道府県</div>
                    <div class=""><?php  echo $_POST["acf_temporary_address_1"]; ?></div>
                    <input type="hidden" name="acf_temporary_address_1" value="<?php echo $_POST["acf_temporary_address_1"];?>" >
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">住所</div>
                    <div class=""><?php  echo $_POST["acf_temporary_address_2"]; ?></div>
                    <input type="hidden" name="acf_temporary_address_2" value="<?php echo $_POST["acf_temporary_address_2"];?>" >
                </div>

                 <div class="user-table-flex">
                    <div class="user-table-item">電話番号</div>
                    <div class=""><?php  echo $_POST["acf_temporary_tel"]; ?></div>
                    <input type="hidden" name="acf_temporary_tel" value="<?php echo $_POST["acf_temporary_tel"];?>" >
                </div>

                 <div class="user-table-flex">
                    <div class="user-table-item">メールアドレス</div>
                    <div class=""><?php  echo $_POST["acf_temporary_mail"]; ?></div>
                    <input type="hidden" name="acf_temporary_mail" value="<?php echo $_POST["acf_temporary_mail"];?>" >
                </div>

            
                <input type="hidden" name="save_registration_data" value>

               <div class="admin-temporary-registration-submit-center">
                    <button type="submit"  class="admin-temporary-registration-check-post-submit">登録する</button>
                </div>

            </div>
        </form>

        <form action="<?php echo getURLSetSlag("admin-temporary-registration-new"); ?>" method="post" enctype="multipart/form-data">

            <?php 
            
                foreach ($_POST as $key => $value) {

                    if($key != "check_data"){
            ?>

                    <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value;?>" >
            <?php
                    }

                }
            ?>    

            <input type="hidden" name="re_edit" value>
            <div class="admin-temporary-registration-submit-center">

                <button type="submit"  class="admin-temporary-registration-reedit-post-submit">編集に戻る</button>

            </div>
        </form>
    <?php } ?>
</div>


<script type="text/javascript"> 
<!-- 

function registration_check(){

	if(window.confirm('登録してもよろしいですか？')){ // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

	}
	else{ // 「キャンセル」時の処理

		window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止

	}

}


function register_check(){

	if(window.confirm('このユーザーを登録してもよろしいですか？')){ // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

	}
	else{ // 「キャンセル」時の処理

		window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止

	}

}
// -->
</script>


<script type="text/javascript"> 
<!-- 

function delete_check(){

	if(window.confirm('削除してもよろしいですか？')){ // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

	}
	else{ // 「キャンセル」時の処理

		window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止

	}

}

// -->
</script>