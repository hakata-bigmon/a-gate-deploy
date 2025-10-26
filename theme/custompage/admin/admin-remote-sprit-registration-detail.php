<?php

	require_once ("a-gate-functions.php");

	require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
	require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");

	$spiritType = new SpiritTypeClass(); //管理データ
	$spiritTypeArray = $spiritType->getSpiritType();

	$spiritSheet = new SpiritSheetClass(); //管理データ
	
   //var_dump($_POST);


   $sheet_id = $_POST["sheet_id"];

   $applicant_id = get_field('acf_remote_sprit_sheet_applicant_id' ,$sheet_id);


   //ユーザーIDで保存
   if(isset($_POST["set_target"]))
   {
        update_field(  "acf_remote_sprit_sheet_target_number_" . $_POST["target_number"] ,$_POST["set_id"] , $sheet_id);
   }

   //ユーザーIDを削除
   if(isset($_POST["delete_target"]))
   {
        update_field(  "acf_remote_sprit_sheet_target_number_" . $_POST["target_number"] ,"" , $sheet_id);
   }




     //ユーザーの全データを取得
	$user = get_userdata( $applicant_id ); 

     //ユーザーの全データを取得
	$users = get_users( array('orderby'=>'ID','order'=>'ASC') ); 

    //var_dump($users);
?>



<?php if( isset($_POST["target_list"])){ //IDからリスト選択 ?>


    <div class="admin-user-table-area">

        



        <?php if(!isset($_POST["target_list_on"])){ //通常登録 ?>


            <div class="admin-title">
		        <?php echo "リモート浄霊 対象者選択"; ?>　<?php echo get_user_meta($applicant_id,'last_name',true); ?>　<?php echo get_user_meta($applicant_id,'first_name',true); ?>　様
	        </div>

            <div class="admin-spirit-subtitle-box">
                <div class="admin-spirit-menu-title">対象者<?php echo $_POST["target_number"];?></div>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">名前</div>
                <?php echo get_field('acf_remote_sprit_sheet_last_name_'. $_POST["target_number"] ,$sheet_id); ?>　<?php echo get_field('acf_remote_sprit_sheet_first_name_'. $_POST["target_number"] ,$sheet_id); ?> ( <?php echo get_field('acf_remote_sprit_sheet_last_name_kana_'. $_POST["target_number"] ,$sheet_id); ?>　<?php echo get_field('acf_remote_sprit_sheet_first_name_kana_'. $_POST["target_number"] ,$sheet_id); ?>  )
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">誕生日</div>
                <?php echo get_field('acf_remote_sprit_sheet_target_birthday_'. $_POST["target_number"] ,$sheet_id); ?>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">対象者との関係</div>
                <?php echo get_field('acf_remote_sprit_sheet_target_relationship_'. $_POST["target_number"] ,$sheet_id); ?>
            </div>

           

            <form action="<?php echo getURLSetSlag("admin-remote-sprit-registration-detail"); ?>" method="post">
                <input type="hidden" name="sheet_id" value="<?php echo $sheet_id;?>">
                <button type="submit" class="edit-mark" style="width: 300px;font-size: 20px;background-color: gray;margin: 0;margin-top: 5px;">編集に戻る</button>
            </form>
        <?php }else if(isset($_POST["target_list_on"])){  ?>

            <div class="admin-title">
		        <?php echo "登録者対象者選択"; ?>
	        </div>


            <div class="admin-spirit-subtitle-box">
                <div class="admin-spirit-menu-title">登録者</div>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">名前</div>
                <?php echo get_field('acf_temporary_last_name' ,$_POST["sheet_id"]); ?>　<?php echo get_field('acf_temporary_first_name' ,$_POST["sheet_id"]); ?>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">住所</div>
                <?php echo get_field('acf_temporary_post_number' ,$_POST["sheet_id"]); ?>　<?php echo get_field('acf_temporary_address_1' ,$_POST["sheet_id"]); ?><?php echo get_field('acf_temporary_address_2' ,$_POST["sheet_id"]); ?>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">電話番号</div>
                <?php echo get_field('acf_temporary_tel' ,$_POST["sheet_id"]); ?>
            </div>

             <div class="user-table-flex">
                <div class="user-table-item">メールアドレス</div>
                <?php echo get_field('acf_temporary_mail' ,$_POST["sheet_id"]); ?>
            </div>

            <form action="<?php echo getURLSetSlag("admin-temporary-registration-new"); ?>?temporary_id=<?php echo $_POST["sheet_id"];?>" method="post">
                <button type="submit" class="edit-mark" style="width: 300px;font-size: 20px;background-color: gray;margin: 0;margin-top: 5px;">枠数登録 編集に戻る</button>
            </form>

        <?php } ?>

    </div>

   




    <div class="admin-temporary-registration-check-table" style="padding-left: 20px;padding-right: 20px;">

            <?php 


                $target_array = array();

                $slots = get_field('acf_remote_sprit_sheet_target_slots' ,$sheet_id);

                for($i=1;$i<=$slots;$i++){

                    if(get_field('acf_remote_sprit_sheet_target_number_' . $i ,$sheet_id) != "")
                    {
                        $target_array[get_field('acf_remote_sprit_sheet_target_number_' . $i ,$sheet_id)] = get_field('acf_remote_sprit_sheet_target_number_' . $i ,$sheet_id);
                    }
                }
            
            
            ?>


            <?php if(isset($_POST["target_list_on"])){ //通常登録 ?>

                <div style="ext-align: center;font-size: 22px;color: red;margin-bottom: 25px;">登録者の候補を選択してください</div>


            <?php } ?>


			<table id="userTable" class="user-disp-table table table-bordered">
                <thead>
                    <tr>
                        <th>ユーザーID</th>
                        <th></th>
                        <th>名前</th>
                        <th>関連</th>
                        <th>グループ</th>
                        <th>性別</th>
                        <th>連絡先</th>
                        <th>住所</th>
                        <th>生年月日</th>
                        <th>メールアドレス</th>
                        <th>紹介者</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($users as $key => $value) {

                           if($value->roles[0] != "subscriber")continue;

                            if(isset( $target_array[$value->ID] ))continue;

                            
                    ?>
                    <tr  <?php if(isset($_GET["sheet_name"])){ if( isset($sheet_set_array[$value->ID])){  ?> style="background-color: #cfd1cb;" <?php }} ?> >
                        <td><?php echo get_user_meta($value->ID,'user_unique_id',true); ?></td>
                        <td>

                            <?php if(!isset($_POST["target_list_on"])){ //通常登録 ?>

                                 <form action="<?php echo getURLSetSlag("admin-remote-sprit-registration-detail"); ?>" method="post" onSubmit="return register_check()">
                                    <input type="hidden" name="sheet_id" value="<?php echo $sheet_id;?>">
                                    <input type="hidden" name="set_id" value="<?php echo $value->ID;?>">
                                    <input type="hidden" name="target_number" value="<?php echo  $_POST["target_number"];?>">
                                    <input type="hidden" name="set_target" value="">
                                    <button type="submit" class="edit-mark" style="width: 75px;font-size: 14px;">選択</button>
                                </form>

                           <?php }else if(isset($_POST["target_list_on"])){  ?>

                                <form action="<?php echo getURLSetSlag("admin-temporary-registration-list"); ?>" method="post" onSubmit="return register_check()">
                                    <input type="hidden" name="set_id" value="<?php echo $value->ID;?>">
                                    <input type="hidden" name="temporary_id" value="<?php echo $_POST["sheet_id"];?>">
                                    <input type="hidden" name="set_target_one" value="">
                                    <button type="submit" class="edit-mark" style="width: 75px;font-size: 14px;">選択</button>
                                </form>

                           <?php } ?>
                        </td>
                        <td>
                            <a href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $value->ID;?>"  target="_blank">
                                <?php echo get_user_meta($value->ID,'last_name',true); ?>　<?php echo get_user_meta($value->ID,'first_name',true); ?>
                            </a>
                        </td>
                        <td><?php echo get_user_meta($value->ID,'user_connection_disp',true); ?></td>
                        <td></td>
                        <td style=" text-align: center;"><?php echo get_user_meta($value->ID,'sex',true); ?></td>
                        <td>
                            <?php if(get_user_meta($value->ID,'billing_phone',true) != ""){?>
                                <?php echo get_user_meta($value->ID,'billing_phone',true); ?>-<?php echo get_user_meta($value->ID,'billing_phone2',true); ?>-<?php echo get_user_meta($value->ID,'billing_phone3',true); ?>
                            <?php } ?>
                        </td>
                        <td><?php echo get_user_meta($value->ID,'billing_postcode',true); ?> <?php echo get_user_meta($value->ID,'billing_city',true); ?><?php echo get_user_meta($value->ID,'billing_address_1',true); ?></td>
                        <td>
                            <?php if(get_user_meta($value->ID,'born_year',true) != ""){?>
                                <?php echo get_user_meta($value->ID,'born_year',true); ?>/<?php echo get_user_meta($value->ID,'born_month',true); ?>/<?php echo get_user_meta($value->ID,'born_day',true); ?>
                            <?php } ?>
                        </td>
                        <td><?php echo $value->user_email; ?></td>
                        <td><?php echo get_user_meta($value->ID,'input_introduction_name',true); ?></td>
                    </tr>
                    <?php } ?>

                </tbody>

            </table>


		</div>


<?php }else{ //詳細 ?>

    <div class="admin-user-table-area">



        
	    <div class="admin-title">
		    <?php echo "リモート浄霊仮登録詳細"; ?>　<?php echo get_user_meta($applicant_id,'last_name',true); ?>　<?php echo get_user_meta($applicant_id,'first_name',true); ?>　様
	    </div>


        <form action="<?php echo getURLSetSlag("admin-remote-sprit-registration-list"); ?>" method="post">
            <button type="submit" class="edit-mark" style="width: 300px;font-size: 20px;background-color: gray;margin: 0;margin-top: 5px;">仮登録一覧に戻る</button>
        </form>

        <div class="admin-spirit-subtitle-box">
            <div class="admin-spirit-menu-title">登録者</div>
        </div>

	    <div class="user-table-flex">
           <div class="user-table-item">名前</div>
           <?php echo get_user_meta($applicant_id,'last_name',true); ?>　<?php echo get_user_meta($applicant_id,'first_name',true); ?> ( <?php echo get_user_meta($applicant_id,'last_name_kana',true); ?>　<?php echo get_user_meta($applicant_id,'first_name_kana',true); ?> )
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">連絡先</div>
            <?php if(get_user_meta($applicant_id,'billing_phone',true) != ""){?>
                <?php echo get_user_meta($applicant_id,'billing_phone',true); ?>-<?php echo get_user_meta($applicant_id,'billing_phone2',true); ?>-<?php echo get_user_meta($applicant_id,'billing_phone3',true); ?>
            <?php } ?>
        </div>

        <div class="user-table-flex" >
            <div class="user-table-item">メールアドレス</div><?php echo $user->user_email; ?>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">住所</div>
            〒<?php echo get_user_meta($applicant_id,'billing_postcode',true); ?> <?php echo get_user_meta($applicant_id,'billing_city',true); ?><?php echo get_user_meta($applicant_id,'billing_address_1',true); ?>
        </div>


        <?php 
    
            $slots = get_field('acf_remote_sprit_sheet_target_slots' ,$sheet_id);

            for($i=1;$i<=$slots;$i++){

            
                $target_num = get_field('acf_remote_sprit_sheet_target_number_' . $i ,$sheet_id);
        ?>

            <div class="admin-spirit-subtitle-box">
                <div class="admin-spirit-menu-title">対象者<?php echo $i;?></div>
            </div>


            <?php if($target_num == ""){?>


                <div class="user-table-flex">
                   <div class="user-table-item">名前</div>
                   <?php echo get_field('acf_remote_sprit_sheet_last_name_'. $i ,$sheet_id); ?>　<?php echo get_field('acf_remote_sprit_sheet_first_name_'. $i ,$sheet_id); ?> ( <?php echo get_field('acf_remote_sprit_sheet_last_name_kana_'. $i ,$sheet_id); ?>　<?php echo get_field('acf_remote_sprit_sheet_first_name_kana_'. $i ,$sheet_id); ?>  )
                </div>

                <div class="user-table-flex">
                   <div class="user-table-item">誕生日</div>
                   <?php echo get_field('acf_remote_sprit_sheet_target_birthday_'. $i ,$sheet_id); ?>
                </div>

                <div class="user-table-flex">
                   <div class="user-table-item">対象者との関係</div>
                   <?php echo get_field('acf_remote_sprit_sheet_target_relationship_'. $i ,$sheet_id); ?>
                </div>
                <div class="user-table-flex">
                    <div class="user-table-item">対象者の画像</div>
                    <?php if(get_field('acf_remote_sprit_sheet_target_img_'. $i ,$sheet_id) != ""){?><img src="<?php echo get_field('acf_remote_sprit_sheet_target_img_'. $i ,$sheet_id);?>" style="max-width: 300px;"><?php } ?>  
                </div>


                <?php 

                    $user_candidate = array();
            
                    //候補を探す
                    foreach ($users as $key => $value) 
                    {
                         $id = $value->ID;

                         $last_name = get_user_meta($id,'last_name',true);
                         $first_name = get_user_meta($id,'first_name',true);

                         $last_name_kana = get_user_meta($id,'last_name_kana',true);
                         $first_name_kana = get_user_meta($id,'first_name_kana',true);

                        if($last_name == get_field('acf_remote_sprit_sheet_last_name_'. $i ,$sheet_id) && $first_name == get_field('acf_remote_sprit_sheet_first_name_'. $i ,$sheet_id))
                        {
                            array_push($user_candidate,$id);
                            continue;
                        }

                        if($last_name_kana == get_field('acf_remote_sprit_sheet_last_name_kana_'. $i ,$sheet_id) && $first_name_kana == get_field('acf_remote_sprit_sheet_first_name_kana_'. $i ,$sheet_id))
                        {
                            array_push($user_candidate,$id);
                            continue;
                        }
                    }

                    //var_dump($user_candidate);
            
                ?>

                <div class="admin-remote-sprit-registration-setnumber-area">

                        <div class="admin-remote-sprit-registration-setnumber-title">対象者番号を設定する</div>
                        <div class="admin-remote-sprit-registration-setnumber-str">すでに登録されている場合は登録者を選んでください。登録されていない場合は一斉浄霊登録時に新規登録されます</div>


                        <?php if(count($user_candidate) > 0){?>

                            <table class="admin-remote-sprit-registration-setnumber-table">

                                <tr>
                                    <th>ID</th>
                            
                                    <th>氏名</th>
                                    <th>シメイ</th>
                                    <th>生年月日</th>
                                    <th>性別</th>
                                    <th>メールアドレス</th>
                                    <th>連絡先</th>
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
                                        <td><?php echo get_user_meta($value,'last_name_kana',true); ?>　<?php echo get_user_meta($value,'first_name_kana',true); ?></td>
                                        <td><?php echo get_user_meta($value,'born_year',true); ?>/<?php echo get_user_meta($value,'born_month',true); ?>/<?php echo get_user_meta($value,'born_day',true); ?></td>
                                        <td><?php echo get_user_meta($value,'sex',true); ?></td>
                                        <td><?php echo $target_user->user_email; ?></td>
                                        <td><?php echo get_user_meta($value,'billing_phone',true); ?>-<?php echo get_user_meta($value,'billing_phone2',true); ?>-<?php echo get_user_meta($value,'billing_phone3',true); ?></td>
                                        <td>
                                            <form action="<?php echo getURLSetSlag("admin-remote-sprit-registration-detail"); ?>" method="post" onSubmit="return register_check()">
                                                <input type="hidden" name="sheet_id" value="<?php echo $sheet_id;?>">
                                                <input type="hidden" name="set_id" value="<?php echo $value;?>">
                                                <input type="hidden" name="target_number" value="<?php echo $i;?>">
                                                <input type="hidden" name="set_target" value="">
                                                <button type="submit" class="edit-mark" style="width: 110px;font-size: 14px;">候補を選択</button>
                                            </form>
                                        </td>
                                     </tr>
                               <?php } ?>

                            </table>
                        <?php } ?>

                        <form action="<?php echo getURLSetSlag("admin-remote-sprit-registration-detail"); ?>" method="post" >
                            <input type="hidden" name="sheet_id" value="<?php echo $sheet_id;?>">
                            <input type="hidden" name="target_number" value="<?php echo $i;?>">
                            <input type="hidden" name="target_list" value="">
                            <button type="submit" class="edit-mark" style="width: 190px;font-size: 14px;background-color: #ef6363;margin: 0;margin-top: 5px;">ユーザー一覧から選択する</button>
                        </form>
                </div>



            <?php }else{ ?>

                <?php  $target_user = get_userdata( $target_num );  ?>

                 <div class="user-table-flex">
                   <div class="user-table-item">ID</div>
                   <?php echo get_user_meta($target_num,'user_unique_id',true);; ?>
                </div>

                <div class="user-table-flex">
                   <div class="user-table-item">名前</div>
                   <a href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $target_num;?>"  target="_blank">
                        <?php echo get_user_meta($target_num,'last_name',true); ?>　<?php echo get_user_meta($target_num,'first_name',true); ?> ( <?php echo get_user_meta($target_num,'last_name_kana',true); ?>　<?php echo get_user_meta($target_num,'first_name_kana',true); ?>  )
                    </a>
                  </div>

                <div class="user-table-flex">
                   <div class="user-table-item">連絡先</div>
                   <?php echo get_user_meta($target_num,'billing_phone',true); ?>-<?php echo get_user_meta($target_num,'billing_phone2',true); ?>-<?php echo get_user_meta($target_num,'billing_phone3',true); ?>
                </div>

                <div class="user-table-flex">
                   <div class="user-table-item">メールアドレス</div>
                   <?php echo $target_user->user_email; ?>
                </div>

                <div class="user-table-flex">
                   <div class="user-table-item">誕生日</div>
                   <?php if(get_user_meta($target_num,'born_year',true) != ""){?>
                        <?php echo get_user_meta($target_num,'born_year',true); ?>/<?php echo get_user_meta($target_num,'born_month',true); ?>/<?php echo get_user_meta($target_num,'born_day',true); ?>
                   <?php } ?>
                </div>

                <div class="user-table-flex">
                   <div class="user-table-item">対象者との関係</div>
                   <?php echo get_field('acf_remote_sprit_sheet_target_relationship_'. $i ,$sheet_id); ?>
                </div>

                <div class="user-table-flex">
                    <div class="user-table-item">対象者の画像</div>
                    <?php if(get_field('acf_remote_sprit_sheet_target_img_'. $i ,$sheet_id) != ""){?><img src="<?php echo get_field('acf_remote_sprit_sheet_target_img_'. $i ,$sheet_id);?>" style="max-width: 300px;"><?php } ?>  
                </div>

                <form action="<?php echo getURLSetSlag("admin-remote-sprit-registration-detail"); ?>" method="post" onSubmit="return delete_check()">
                    <input type="hidden" name="sheet_id" value="<?php echo $sheet_id;?>">
                    <input type="hidden" name="target_number" value="<?php echo $i;?>">
                    <input type="hidden" name="delete_target" value="">
                    <button type="submit" class="edit-mark" style="width: 190px;font-size: 14px;background-color: gray;margin: 0;margin-left: 14px;margin-top: 30px;">ユーザーIDを削除する</button>
                </form>


            <?php } ?>
        <?php
    
            }
        ?>


         <div class="admin-title" style="margin-top: 100px;">
		    <?php echo "浄霊シート登録"; ?>
	    </div>

        <?php 

            $applicant_split_data = $spiritSheet->getSpritApplicant( $applicant_id );


            if($applicant_split_data != NULL){


                

        ?>
            <table id="" class="user-disp-table table table-bordered">
                <thead>
                    <tr>
                        <th style="width:200px">依頼内容</th>
                        <th style="width: 150px;">詳細</th>
                        <th>単価</th>
                        <th style="width: 100px;">申込者</th>
                        <th style="width: 100px;">依頼日</th>
                        <th style="width: 100px;">決済日</th>
                        <th style="width: 100px;">実行日</th>
                        <th>依頼内容追記</th>
                        <th style="min-width: 120px;">ステータス</th>
                      
                    </tr>
                </thead>
                <tbody>
            
                     <?php 
                
                        foreach ($applicant_split_data as $key => $value) { 
                            $sprit_type = get_field('acf_acf_purespirit_type',$key);

                            if($sprit_type != 77)continue;//一斉浄霊以外は表示しない

                            $is_delete = get_field("is_delete", $key);

                            if($is_delete) continue;    //削除されたシートは表示しない
                    
                            if(isset($_GET['select_sprit_type']) && $sprit_type != $_GET['select_sprit_type']  && "" != $_GET['select_sprit_type']) continue;



                            //申込枠が同じものだけを表示
                            $slots = get_field('acf_remote_sprit_sheet_target_slots',$sheet_id);

                            //一斉浄霊シートのID
                            $sprit_sheet_id = get_field('acf_purespirit_remote_sprit_num',$key);

                            if($slots != get_field('acf_remote_sprit_sheet_target_slots',$sprit_sheet_id))
                            {
                                continue;
                            }
                            
                            //対象者のIDが全て埋まっているものは表示しない
                            $check_slots = get_field('acf_remote_sprit_sheet_target_slots',$sprit_sheet_id);

                            $slot_id = true;

                            for($i=1;$i<$check_slots;$i++)
                            {
                                $target_num = get_field('acf_remote_sprit_sheet_id_' . $i ,$sprit_sheet_id);

                                //echo $sprit_sheet_id . " " .$target_num . " ".$i."<br>";

                                if($target_num != "")
                                {
                                    $slot_id = false; 
                                }
                            }

                            if(!$slot_id)continue;
                            

                     ?>
                        <tr>
                            <td style="text-align: center; vertical-align: middle;font-weight: 800;">
                                <a href="<?php echo getURLSetSlag("admin-spirit-detail"); ?>?user_id=<?php echo get_field('acf_purespirit_id',$key);?>&sheet_name=<?php echo $key;?>"  target="_blank">
                                    <?php echo get_field('acf_pure_spirit_title',get_field('acf_acf_purespirit_type',$key));?>
                                </a>
                            </td>
                            <td>
                                <form action="<?php echo getURLSetSlag("admin-spirit-detail"); ?>?user_id=<?php echo $applicant_id;?>&sheet_name=<?php echo $key;?>" method="post" onSubmit="return set_check()">
                                    <input type="hidden" name="remote_sheet_id" value="<?php echo $sheet_id;?>">
                                    <input type="hidden" name="set_remote" value="">
                                    <button type="submit" class="edit-mark" style="width: 100px;font-size: 14px;background-color: red;">選択</button>
                                </form>
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                <?php 
                                    $single_money = get_field('acf_purespirit_price',$key);
                                    if($single_money == "")$single_money = 0;   //0設定

                                    echo "&yen;" .number_format($single_money);
                                ?>
                            </td>
                     
                            <td style="text-align: center; vertical-align: middle;">
                                <a href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo get_field('acf_purespirit_id',$key);?>"  target="_blank"><?php echo get_user_meta(get_field('acf_purespirit_id',$key),'last_name',true) . " ". get_user_meta(get_field('acf_purespirit_id',$key),'first_name',true); ?></a>
                            </td>
                            <td style="text-align: center; vertical-align: middle;"><?php echo get_field('acf_purespirit_requested_date',$key);?></td>
                           
                            <td style="text-align: center; vertical-align: middle;"><?php echo get_field('acf_purespirit_payment_date',$key);?></td>
                            <td style="text-align: center; vertical-align: middle;"><?php echo get_field('acf_purespirit_execution_date',$key);?></td>
                            <td ><?php echo get_field('acf_purespirit_add_text',$key);?></td>
                            <td style="text-align: center; vertical-align: middle;">
                            <?php 
                    
                                $status = get_field('acf_purespirit_status',$key);

                                if($status == "")
                                {
                                    echo "未確認";
                                }
                                else
                                {
                                    $spiritStatusArray = $spiritSheet->getSpiritAdminStatusID('cpt_spirit_status');
                                    echo $spiritStatusArray[$status]["title"];
                                }
                    
                            ?>
                    
                            </td>
                        </tr>
                
                    <?php } ?>

                </tbody>
            </table>


        <?php }else{ ?>


            <div style="font-size: 24px;font-weight: 600;text-align: center;color: black;">登録枠が存在しません</div>

        <?php } ?>


        <div class="admin-title" style="margin-top: 100px;">
		    <?php echo "浄霊シート削除"; ?>
	    </div>


        <form action="<?php echo getURLSetSlag("admin-remote-sprit-registration-list"); ?>" method="post" onSubmit="return sheet_delete_check()" >
            <input type="hidden" name="sheet_id" value="<?php echo $sheet_id;?>">
            <input type="hidden" name="sheet_delete" value="">
            <button type="submit" class="edit-mark" style="background-color:blue; width: 600px;font-size: 27px;height: 45px;border-radius: 15px;">削除</button>
        </form>

    </div>


<?php } ?>

<script type="text/javascript"> 
<!-- 


function register_check(){

	if(window.confirm('このユーザーを登録してもよろしいですか？')){ // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

	}
	else{ // 「キャンセル」時の処理

		window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止

	}

}

function delete_check(){

	if(window.confirm('このユーザーを登録から削除してもよろしいですか？')){ // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

	}
	else{ // 「キャンセル」時の処理

		window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止

	}

}


function sheet_delete_check(){

	if(window.confirm('このシートを削除してもよろしいですか？')){ // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

	}
	else{ // 「キャンセル」時の処理

		window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止

	}

}


function set_check(){

	if(window.confirm('この情報を登録しても宜しいですか？\nIDから選択されていない対象者は新規で作成されます')){ // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

	}
	else{ // 「キャンセル」時の処理

		window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止

	}

}


// -->
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 

    
    <script>

    // 浄霊シートデータをここに全て格納
    var userData = <?php echo json_encode($up_date_array); ?>;

    /*$(document).ready( function () {

        $('#userTable').DataTable({
            data: userData,
            scrollX: true,
            columns: [
                { data: 'acf_pure_spirit_title' },
                { data: 'user_edit_url' },          //詳細ボタン
                { data: 'purespirit_name' },          //依頼者
                { data: 'applicant_name' },          //申込者
                { data: 'introduction_name' },          //紹介者
                { data: 'acf_purespirit_requested_date' },          //依頼日
                { data: 'acf_purespirit_request_confirmation_date' },          //依頼鑑定日
                { data: 'acf_purespirit_payment_date' },          //決済日
                { data: 'acf_purespirit_execution_date' },          //実行日
                { data: 'acf_purespirit_add_text' },          //実行日依頼内容追記
                { data: 'status' },          //実行日依頼内容追記ステータス
                { data: 'a-gate' },          //a-gate
            ],
            
            "order": [[0, "dec"]], // 第2列（インデックス2）を昇順（asc）にソート
            "paging": false, // ページネーションを非表示
            "pageLength": -1,  // 初期表示件数
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
            }

        });
    } );
    */

</script>