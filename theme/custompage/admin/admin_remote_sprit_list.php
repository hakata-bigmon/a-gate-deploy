<?php

	require_once ("a-gate-functions.php");

	require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
	require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");


	$spiritType = new SpiritTypeClass(); //管理データ
	$spiritTypeArray = $spiritType->getSpiritType();

	$spiritSheet = new SpiritSheetClass(); //管理データ
	

      //ユーザークラス
    $userClass = new SpiritUserClass(); //ユーザー管理


    //会員ステータス情報
    $spiritMemberStatusArray = $spiritSheet->getSpiritAdminStatusID('cpt_spirit_usestatus');
  
    //var_dump($_POST);




   //保存
   
   if(isset($_POST["list_save"]))
   {

        foreach ($_POST["request_sheet"] as $key => $post_sheet_id) {

           $post_target_number = $_POST["request_target"][ $key ];

           //依頼日


           //入力完了日
           if(isset($_POST["input_end_date"]))
           {
               $spiritSheet->saveRemoteSpritTargetData( "acf_remote_sprit_sheet_target_input_date" ,  $post_sheet_id ,$post_target_number , $_POST["input_end_date"][ $key ] );
           }
           //実行予定日
           if(isset($_POST["input_schedule_date"]))
           {
               $spiritSheet->saveRemoteSpritTargetData( "acf_remote_sprit_sheet_target_execution_schedule_date" ,  $post_sheet_id ,$post_target_number , $_POST["input_schedule_date"][ $key ] );
           }

           //実行日
           if(isset($_POST["input_execution_date"]))
           {
               $spiritSheet->saveRemoteSpritTargetData( "acf_remote_sprit_sheet_target_execution_date" ,  $post_sheet_id ,$post_target_number , $_POST["input_execution_date"][ $key ] );
           }

           //ステータス
           if(isset($_POST["input_status"]))
           {
               $spiritSheet->saveRemoteSpritTargetData( "acf_remote_sprit_sheet_target_status" ,  $post_sheet_id ,$post_target_number , $_POST["input_status"][ $key ] );
           }

        }
   }
   

   
   //登録
   if(isset($_POST["applicant_id"]))
   {
       if($_POST["target_slots"] != ""){


            for($i=1;$i<=$_POST["target_slots"];$i++){
                
                //シートを作成(リモート浄霊追加)
		        $add_id = $spiritSheet->newSpiritSheetUnixtime($_POST["applicant_id"], 77 ,$_POST["unix_time"] + ($i - 1));

		        //ユーザーの配列を追加
		        if($add_id != "")
		        {
                    //依頼sheetの作成
			        $spiritSheet->newSpiritSheetUserAdd( $_POST["applicant_id"] , $add_id );


			        //リモート浄霊シートの作成
			        $target_array = array();

                    //枠数
                    $target_count = 0;

                    /*
                    if(!isset($_POST["set_slots_only"])){
                        foreach ($_POST["target_id"] as $target_key => $target_value) {

				            if($target_value != "")
				            {
					            $target_array[ $target_value ] = $target_value;
                                $target_count++;
				            }
                        }
			        }
                    */

			        $user = wp_get_current_user();

                    $sheet_id = "";

                    //それぞれ１個ずつ登録
                     $target_count = 1;
                     $sheet_id = $spiritSheet->newRemoteSpritSlots(   $user->ID , $_POST["applicant_id"] , $target_count);//枠のみ

                    /*
                    if(isset($_POST["set_slots_only"]))
                    {
                        $target_count = $_POST["target_slots"];
                        $sheet_id = $spiritSheet->newRemoteSpritSlots(   $user->ID , $_POST["applicant_id"] , $target_count);//枠のみ
                
                    }
                    else{
			            $sheet_id = $spiritSheet->newRemoteSprit(   $user->ID , $_POST["applicant_id"] , $target_array );
                    }
                    */


			        //依頼日と申込者だけ入力する
			        update_field("acf_purespirit_requested_date", date("Y-m-d"), $add_id);//申込日
			        update_field("acf_applicant", $_POST["applicant_id"], $add_id);//申込者
			        update_field("acf_purespirit_remote_sprit_num", $sheet_id, $add_id);//浄霊シートID

                    //浄霊シート番号を入れる
                    update_field("acf_remote_sprit_id", $add_id, $sheet_id);
                    //枠数を入れる
                    update_field("acf_remote_sprit_sheet_target_slots", $target_count, $sheet_id);

                    //会員のステータスを入金待ちにする(どういう状況かわからない為)
                    update_field("acf_purespirit_user_status", SpiritUserClass::MEMBER_STATUS_WATING_PAYMENT, $add_id);


		        }

       
            }

        }
   }



   //粗見用シートで戻ってきた時

$arami_sheet_check_array = array();


if(isset($_POST["make_arami"])){

    if(isset($_POST["arami_user"])){

        foreach ($_POST["arami_user"] as $key => $value) {

            $arami_sheet_check_array[ $value ] = $value;

        }
    }
}





   //検索登録
   if(isset($_POST["search"]))
   {
       $search_array = array();

       foreach ($_POST as $key => $value) {
           $search_array[ $key ] = $value;
       }

       update_user_meta(get_current_user_id(),'search_remote_list',json_encode($search_array, JSON_UNESCAPED_UNICODE));
   }
    //検索リセット
   if(isset($_POST["search_reset"]))
   {
       update_user_meta(get_current_user_id(),'search_remote_list',"");
   }

   //検索取得
   $current_user_search_data = get_user_meta(get_current_user_id(),'search_remote_list',true);//表示設定取得
   
   if($current_user_search_data != "-" && $current_user_search_data != null){

        $current_user_search_data = json_decode($current_user_search_data, true);//表示設定取得
   }

   if($current_user_search_data == "")
   {
       $current_user_search_data = array();
   }

   //リモート浄霊一覧を取得
   $remote_array = $spiritSheet->getRemoteSprit(  );


   //表示絞り込み
   $label_array = $spiritSheet->getRemoteListSearchTable();//array("依頼日", '入力完了日', '実行予定日', '実行日', 'ステータス', '生年月日', '関係', '動画番号', '動画URL');


   //保存
   if(isset($_POST["disp_limit"]))
   {
       $label_data_array = array();

        foreach ($_POST["disp_label"] as $key => $value) {
           $label_data_array[ $value ] = $value;
        }
    
       update_user_meta(get_current_user_id(),'label_remote_disp',json_encode($label_data_array, JSON_UNESCAPED_UNICODE));
   }


   $disp_label_array_data = get_user_meta(get_current_user_id(),'label_remote_disp',true);//表示設定取得
   
   $disp_label_array = array();

   foreach ($label_array as $key => $value) 
   {
       $disp_label_array[ $value ] = 1;
   }
   
   if($disp_label_array_data != "")
   {
        $disp_label_array_data = json_decode($disp_label_array_data, true);

       foreach ($disp_label_array as $key => $value) 
       {
           if( !isset( $disp_label_array_data[ $key ] ) )
           {
               $disp_label_array[ $key ] = 0;
           }
       }
   }


   //作成時の最大数のマイナス
   $minus_make_target = 0;

    if(isset($_POST["add_arami"])){

        $minus_make_target = get_field('acf_arami_slots', $_POST["add_arami"]);
    }

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/4.0.2/js/dataTables.fixedColumns.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/4.0.2/css/fixedColumns.dataTables.min.css">

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 




 <script>
   
  

    function toggleCheckboxes() {
            // チェックボックスのリストを取得
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            // 全てのチェックボックスがチェックされているか確認
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            
            // 全てチェック済みならOFF、1つでも未チェックならON
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        }



</script>


<div class="admin-user-table-area">

    <?php if(isset($_POST["make_arami"])){ ?>
        <?php if(!isset($_POST["add_arami"])){ ?>
            <div class="admin-title">
		        <?php echo "粗見用シート作成"; ?>
	        </div>
         <?php }else{ ?>
            <div class="admin-title">
		        <?php echo get_field('acf_arami_title', $_POST["add_arami"]) . " 粗見用シート対象者追加"; ?>
	        </div>

         <?php } ?>
    <?php }else{ ?>
	    <div class="admin-title">
		    <?php echo "リモート浄霊一覧"; ?>
	    </div>
    <?php } ?>
   


	<?php if(count($remote_array) > 0){ ?>

       
        <?php 
            /* 
            
            <div class="admin-temporary-registration-search-area">
    
                <div style="display: flex;justify-content: left;" >
                    <div class="admin-temporary-registration-search-flex" style="border: 5px solid lightskyblue;padding: 5px;margin-bottom: 18px;width: 930px;">
    
                        <form action="<?php echo getURLSetSlag("admin_remote_sprit_list"); ?>" method="post">
    
                            <div class="admin-temporary-registration-search-flex">
    
                                <?php foreach ($disp_label_array as $key => $value) { ?>
    
                                    <div class="admin-temporary-registration-check-box"><input type="checkbox" name="disp_label[]" value="<?php echo $key;?>" <?php if( $value == 1){ echo  "checked";}?>><?php echo $key;?></div>
    
                                <?php } ?>
        
    
                                <button class="admin-remote-search-button" style="margin-top: 0;margin-left: 32px;width: 110px;">表示</button>
    
                                <input type="hidden" name="disp_limit" value="">
    
                                <?php if(isset($_POST["make_arami"])){ ?>
                                    <input type="hidden" name="make_arami" value="">
                                <?php } ?>
    
                                    <?php if(isset($_POST["add_arami"])){ ?>
                                    <input type="hidden" name="add_arami" value="<?php echo $_POST["add_arami"];?>">
                                <?php } ?>
                            
                            </div>
    
                        </form>
    
                    </div>
    
                    <?php if(!isset($_POST["make_arami"])){ ?>
                    
                        <form action="<?php echo getURLSetSlag("admin_remote_sprit_list"); ?>" method="post" style="text-align: center;margin-left: 10px;">
    
                            <button class="admin-remote-make-arami-button" style="margin-top: 0;font-size: 20px;width: 200px;">粗見用ユーザー選択</button>
    
                            <input type="hidden" name="make_arami" value="">
                        </form>
    
    
                        <form action="<?php echo getURLSetSlag("admin-arami-sheet-list"); ?>" method="post" style="text-align: center;margin-left: 10px;">
    
                            <button class="admin-remote-make-arami-button" style="margin-top: 0;font-size: 20px;width: 200px;background-color: crimson;">粗見用シートへ</button>
    
                            <input type="hidden" name="make_arami" value="">
                        </form>
    
                    <?php } ?>
    
                    </div>
    
    
                <div class="admin-temporary-registration-search-flex">
    
                    <form action="<?php echo getURLSetSlag("admin_remote_sprit_list"); ?>" method="post">
    
                        <div class="admin-temporary-registration-search-flex">
    
                            <div class="admin-temporary-registration-input-box">
    
                                <div class="admin-temporary-registration-input-label">依頼日</div>
    
                                <div class="admin-temporary-registration-input-flex">
                                    <div class="admin-temporary-registration-input-form">
                                        <input type="date" id="" name="remote_list_search_requested_start" value="<?php  if(isset($current_user_search_data["remote_list_search_requested_start"])){ echo $current_user_search_data["remote_list_search_requested_start"];}?>">
                                    </div>
                    
                                    <div style="margin-left: 10px;margin-right: 10px;">～</div>
    
                                    <div class="admin-temporary-registration-input-form">
                                        <input type="date" id="" name="remote_list_search_requested_end" value="<?php if(isset($current_user_search_data["remote_list_search_requested_end"])){echo $current_user_search_data["remote_list_search_requested_end"];}?>">
                                    </div>
                                </div>
    
                            </div>
    
                            <div class="admin-temporary-registration-input-box">
                                <div class="admin-temporary-registration-input-label">入力完了日</div>
    
                                <div class="admin-temporary-registration-input-flex">
                                    <div class="admin-temporary-registration-input-form">
                                        <input type="date" id="" name="remote_list_search_input_date_start" value="<?php if(isset($current_user_search_data["remote_list_search_input_date_start"])){echo $current_user_search_data["remote_list_search_input_date_start"];}?>">
                                    </div>
    
                                    <div style="margin-left: 10px;margin-right: 10px;">～</div>
    
                                    <div class="admin-temporary-registration-input-form">
                                        <input type="date" id="" name="remote_list_search_input_date_end" value="<?php if(isset($current_user_search_data["remote_list_search_input_date_end"])){echo $current_user_search_data["remote_list_search_input_date_end"];}?>">
                                    </div>
                                </div>
                            </div>
    
    
                            <div class="admin-temporary-registration-input-box">
                                <div class="admin-temporary-registration-input-label">実行予定日</div>
    
                                <div class="admin-temporary-registration-input-flex">
                                    <div class="admin-temporary-registration-input-form">
                                        <input type="date" id="" name="remote_list_search_execution_schedule_date_start" value="<?php if(isset($current_user_search_data["remote_list_search_execution_schedule_date_start"])){echo $current_user_search_data["remote_list_search_execution_schedule_date_start"];}?>">
                                    </div>
                    
                                    <div style="margin-left: 10px;margin-right: 10px;">～</div>
    
                                    <div class="admin-temporary-registration-input-form">
                                        <input type="date" id="" name="remote_list_search_execution_schedule_date_end" value="<?php if(isset($current_user_search_data["remote_list_search_execution_schedule_date_end"])){echo $current_user_search_data["remote_list_search_execution_schedule_date_end"];}?>">
                                    </div>
                                </div>
                            </div>
    
                            <div class="admin-temporary-registration-input-box">
                                <div class="admin-temporary-registration-input-label">実行日</div>
    
                                <div class="admin-temporary-registration-input-flex">
                                    <div class="admin-temporary-registration-input-form">
                                        <input type="date" id="" name="remote_list_search_execution_date_start" value="<?php if(isset($current_user_search_data["remote_list_search_execution_date_start"])){echo $current_user_search_data["remote_list_search_execution_date_start"];}?>">
                                    </div>
                    
                                    <div style="margin-left: 10px;margin-right: 10px;">～</div>
    
                                    <div class="admin-temporary-registration-input-form">
                                        <input type="date" id="" name="remote_list_search_execution_date_end" value="<?php if(isset($current_user_search_data["remote_list_search_execution_date_end"])){echo $current_user_search_data["remote_list_search_execution_date_end"];}?>">
                                    </div>
                                </div>
                            </div>
                        </div>
    
    
    
                        <div class="admin-temporary-registration-search-flex" style="margin-top: 10px;">
    
                            <div class="admin-temporary-registration-input-box">
                                <div class="admin-temporary-registration-input-label">ステータス</div>
    
                                <div class="admin-temporary-registration-input-flex">
                                    <div class="admin-temporary-registration-input-form">
                                        <select name="remote_list_search_execution_status" style="font-size: 16px;margin: 3px;margin-left: 0px;">
                                            <option value="" >全て表示</option>
                                            <option value="0" <?php if(isset( $current_user_search_data["remote_list_search_execution_status"])) {if( $current_user_search_data["remote_list_search_execution_status"] == 0) { echo "selected"; }}?>>未設定</option>
                                            <option value="1" <?php if(isset( $current_user_search_data["remote_list_search_execution_status"])) {if( $current_user_search_data["remote_list_search_execution_status"] == 1) { echo "selected"; }}?>>完了</option>
                                            <option value="2" <?php if(isset( $current_user_search_data["remote_list_search_execution_status"])) {if( $current_user_search_data["remote_list_search_execution_status"] == 2) { echo "selected"; }}?>>キャンセル</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        
    
                        
    
                            <div class="admin-temporary-registration-input-box">
                                <div class="admin-temporary-registration-input-label">対象者</div>
    
                                <div class="admin-temporary-registration-input-flex">
                                    <div class="admin-temporary-registration-input-form">
                                        <select name="remote_list_search_target_setting" style="font-size: 16px;margin: 3px;margin-left: 0px;">
                                            <option value="" >全て表示</option>
                                            <option value="0" <?php if(isset( $current_user_search_data["remote_list_search_target_setting"])) {if( $current_user_search_data["remote_list_search_target_setting"] == 0) { echo "selected"; }}?>>設定ありのみ</option>
                                            <option value="1" <?php if(isset( $current_user_search_data["remote_list_search_target_setting"])) {if( $current_user_search_data["remote_list_search_target_setting"] == 1) { echo "selected"; }}?>>設定なしのみ</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
    
    
                            <button class="admin-remote-search-button">絞り込む</button>
    
                            <input type="hidden" name="search" value="">
    
                            <?php if(isset($_POST["make_arami"])){ ?>
                                <input type="hidden" name="make_arami" value="">
                            <?php } ?>
    
                            <?php if(isset($_POST["add_arami"])){ ?>
                                <input type="hidden" name="add_arami" value="<?php echo $_POST["add_arami"];?>">
                            <?php } ?>
                    </form>
    
                            <form action="<?php echo getURLSetSlag("admin_remote_sprit_list"); ?>" method="post">
    
                                <button class="admin-remote-search-reset-button">リセット</button>
    
                                <?php if(isset($_POST["make_arami"])){ ?>
                                    <input type="hidden" name="make_arami" value="">
                                <?php } ?>
    
                                <?php if(isset($_POST["add_arami"])){ ?>
                                    <input type="hidden" name="add_arami" value="<?php echo $_POST["add_arami"];?>">
                                <?php } ?>
    
                                <input type="hidden" name="search_reset" value="">
                            </form>
                        </div>
            </div>
            */
        ?>

        <div class="btn-flex flex-st">

            <button type="button" class="admin-remote-make-arami-button" style="color:black;background-color: aliceblue;margin-top: 0;font-size: 20px;width: 200px;" onclick="setting_modal()" >表示設定</button>
            <?php if(!isset($_POST["make_arami"])){ ?>
    
                <form action="<?php echo getURLSetSlag("admin_remote_sprit_list"); ?>" method="post" style="text-align: center;margin-left: 10px;">
    
                    <button class="admin-remote-make-arami-button" style="margin-top: 0;font-size: 20px;width: 200px;">粗見用ユーザー選択</button>
    
                    <input type="hidden" name="make_arami" value="">
                </form>
    
    
                <form action="<?php echo getURLSetSlag("admin-arami-sheet-list"); ?>" method="post" style="text-align: center;margin-left: 10px;">
    
                    <button class="admin-remote-make-arami-button" style="margin-top: 0;font-size: 20px;width: 200px;background-color: crimson;">粗見用シートへ</button>
    
                    <input type="hidden" name="make_arami" value="">
                </form>
    
            <?php } ?>
        </div>

        <style>
            .modal__content {
                width: 1000px;
                height: auto;
            }
            .modal-text-area {
                height: 80px;
                font-weight: bold;
                font-size: 20px;
            }
            .admin-temporary-registration-search-flex{
                
                justify-content: center;
            }
            .admin-temporary-registration-input-box{
                margin-top: 10px;
            }
            .check-flex{
                margin:20px auto;
                align-items: flex-end;
            }
        </style>
        <div class="admin-temporary-registration-search-area modal" id="remote-modal">
            <div class="modal__bg" id="over-close"></div>


            <div class="modal__content pt20 pm20 pl20 pr20">
                <div class="" id="btn-close">X</div>

                    <form action="<?php echo getURLSetSlag("admin_remote_sprit_list"); ?>" method="post">

                        <div class="modal-text-area" id="disp-text">表示設定</div>

                            <div class="check-flex">
                                <?php foreach ($disp_label_array as $key => $value) { ?>
    
                                    <div class="check-box">
                                        <input type="checkbox" name="disp_label[]" value="<?php echo $key;?>" <?php if( $value == 1){ echo  "checked";}?>>
                                        <label for=""><?php echo $key;?></label>

                                    </div>
                                <?php } ?>
                                <button class="check-btn ">表示</button>
                            </div>


                            <input type="hidden" name="disp_limit" value="">

                            <?php if(isset($_POST["make_arami"])){ ?>
                                <input type="hidden" name="make_arami" value="">
                            <?php } ?>

                            <?php if(isset($_POST["add_arami"])){ ?>
                                <input type="hidden" name="add_arami" value="<?php echo $_POST["add_arami"];?>">
                            <?php } ?>

                    </form>
    
                    <div class="admin-temporary-registration-search-flex">
        
                        <form action="<?php echo getURLSetSlag("admin_remote_sprit_list"); ?>" method="post">
        
                            <div class="check-flex">
        
                                <div class="admin-temporary-registration-input-box">
        
                                    <div class="admin-temporary-registration-input-label">依頼日</div>
        
                                    <div class="admin-temporary-registration-input-flex">
                                        <div class="admin-temporary-registration-input-form">
                                            <input type="date" id="" name="remote_list_search_requested_start" value="<?php  if(isset($current_user_search_data["remote_list_search_requested_start"])){ echo $current_user_search_data["remote_list_search_requested_start"];}?>">
                                        </div>
                        
                                        <div style="margin-left: 10px;margin-right: 10px;">～</div>
        
                                        <div class="admin-temporary-registration-input-form">
                                            <input type="date" id="" name="remote_list_search_requested_end" value="<?php if(isset($current_user_search_data["remote_list_search_requested_end"])){echo $current_user_search_data["remote_list_search_requested_end"];}?>">
                                        </div>
                                    </div>
        
                                </div>
        
                                <div class="admin-temporary-registration-input-box">
                                    <div class="admin-temporary-registration-input-label">入力完了日</div>
        
                                    <div class="admin-temporary-registration-input-flex">
                                        <div class="admin-temporary-registration-input-form">
                                            <input type="date" id="" name="remote_list_search_input_date_start" value="<?php if(isset($current_user_search_data["remote_list_search_input_date_start"])){echo $current_user_search_data["remote_list_search_input_date_start"];}?>">
                                        </div>
        
                                        <div style="margin-left: 10px;margin-right: 10px;">～</div>
        
                                        <div class="admin-temporary-registration-input-form">
                                            <input type="date" id="" name="remote_list_search_input_date_end" value="<?php if(isset($current_user_search_data["remote_list_search_input_date_end"])){echo $current_user_search_data["remote_list_search_input_date_end"];}?>">
                                        </div>
                                    </div>
                                </div>
        
        
                                <div class="admin-temporary-registration-input-box">
                                    <div class="admin-temporary-registration-input-label">実行予定日</div>
        
                                    <div class="admin-temporary-registration-input-flex">
                                        <div class="admin-temporary-registration-input-form">
                                            <input type="date" id="" name="remote_list_search_execution_schedule_date_start" value="<?php if(isset($current_user_search_data["remote_list_search_execution_schedule_date_start"])){echo $current_user_search_data["remote_list_search_execution_schedule_date_start"];}?>">
                                        </div>
                        
                                        <div style="margin-left: 10px;margin-right: 10px;">～</div>
        
                                        <div class="admin-temporary-registration-input-form">
                                            <input type="date" id="" name="remote_list_search_execution_schedule_date_end" value="<?php if(isset($current_user_search_data["remote_list_search_execution_schedule_date_end"])){echo $current_user_search_data["remote_list_search_execution_schedule_date_end"];}?>">
                                        </div>
                                    </div>
                                </div>
        
                                <div class="admin-temporary-registration-input-box">
                                    <div class="admin-temporary-registration-input-label">実行日</div>
        
                                    <div class="admin-temporary-registration-input-flex">
                                        <div class="admin-temporary-registration-input-form">
                                            <input type="date" id="" name="remote_list_search_execution_date_start" value="<?php if(isset($current_user_search_data["remote_list_search_execution_date_start"])){echo $current_user_search_data["remote_list_search_execution_date_start"];}?>">
                                        </div>
                        
                                        <div style="margin-left: 10px;margin-right: 10px;">～</div>
        
                                        <div class="admin-temporary-registration-input-form">
                                            <input type="date" id="" name="remote_list_search_execution_date_end" value="<?php if(isset($current_user_search_data["remote_list_search_execution_date_end"])){echo $current_user_search_data["remote_list_search_execution_date_end"];}?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="admin-temporary-registration-input-box">
                                    <div class="admin-temporary-registration-input-label">入力</div>
        
                                    <div class="admin-temporary-registration-input-flex">
                                        <div class="admin-temporary-registration-input-form">
                                            <select name="remote_list_search_inputend_setting" style="font-size: 16px;margin: 3px;margin-left: 0px;">
                                                <option value="" >全て表示</option>
                                                <option value="0" <?php if(isset( $current_user_search_data["remote_list_search_inputend_setting"])) {if( $current_user_search_data["remote_list_search_inputend_setting"] == 0) { echo "selected"; }}?>>入力完了のみ</option>
                                                <option value="1" <?php if(isset( $current_user_search_data["remote_list_search_inputend_setting"])) {if( $current_user_search_data["remote_list_search_inputend_setting"] == 1) { echo "selected"; }}?>>入力未完了のみ</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="admin-temporary-registration-input-box">
                                    <div class="admin-temporary-registration-input-label">ステータス</div>
        
                                    <div class="admin-temporary-registration-input-flex">
                                        <div class="admin-temporary-registration-input-form">
                                            <select name="remote_list_search_execution_status" style="font-size: 16px;margin: 3px;margin-left: 0px;">
                                                <option value="" >全て表示</option>
                                                <option value="0" <?php if(isset( $current_user_search_data["remote_list_search_execution_status"])) {if( $current_user_search_data["remote_list_search_execution_status"] == 0) { echo "selected"; }}?>>未設定</option>
                                                <option value="1" <?php if(isset( $current_user_search_data["remote_list_search_execution_status"])) {if( $current_user_search_data["remote_list_search_execution_status"] == 1) { echo "selected"; }}?>>完了</option>
                                                <option value="2" <?php if(isset( $current_user_search_data["remote_list_search_execution_status"])) {if( $current_user_search_data["remote_list_search_execution_status"] == 2) { echo "selected"; }}?>>キャンセル</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="admin-temporary-registration-input-box">
                                    <div class="admin-temporary-registration-input-label">会員ステータス</div>
        
                                    <div class="admin-temporary-registration-input-flex">
                                        <div class="admin-temporary-registration-input-form">
                                            <select name="remote_list_search_execution_member_status" style="font-size: 16px;margin: 3px;margin-left: 0px;">
                                                <option value="" >全て表示</option>

                                                <?php foreach ($spiritMemberStatusArray as $key => $value) {  ?>

                                                    <option value="<?php echo $key;?>" <?php if(isset( $current_user_search_data["remote_list_search_execution_member_status"])) {if( $current_user_search_data["remote_list_search_execution_member_status"] == $key) { echo "selected"; }}?>><?php echo $value["title"];?></option>

                                                <?php } ?>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="admin-temporary-registration-input-box">
                                    <div class="admin-temporary-registration-input-label">対象者</div>
        
                                    <div class="admin-temporary-registration-input-flex">
                                        <div class="admin-temporary-registration-input-form">
                                            <select name="remote_list_search_target_setting" style="font-size: 16px;margin: 3px;margin-left: 0px;">
                                                <option value="" >全て表示</option>
                                                <option value="0" <?php if(isset( $current_user_search_data["remote_list_search_target_setting"])) {if( $current_user_search_data["remote_list_search_target_setting"] == 0) { echo "selected"; }}?>>設定ありのみ</option>
                                                <option value="1" <?php if(isset( $current_user_search_data["remote_list_search_target_setting"])) {if( $current_user_search_data["remote_list_search_target_setting"] == 1) { echo "selected"; }}?>>設定なしのみ</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


        
                                <button class="check-btn" style="margin-right:20px;margin-top: 20px;">絞り込む</button>
        
                                <input type="hidden" name="search" value="">
        
                                <?php if(isset($_POST["make_arami"])){ ?>
                                    <input type="hidden" name="make_arami" value="">
                                <?php } ?>
        
                                <?php if(isset($_POST["add_arami"])){ ?>
                                    <input type="hidden" name="add_arami" value="<?php echo $_POST["add_arami"];?>">
                                <?php } ?>

                                
                                <button class="check-btn red" type="button" onclick="reset_search()">リセット</button>
                            </div>
        
                        </form>
        
                    </div>

                    <form action="<?php echo getURLSetSlag("admin_remote_sprit_list"); ?>" id="reset_setting_btn" method="post">

                        <?php if(isset($_POST["make_arami"])){ ?>
                            <input type="hidden" name="make_arami" value="">
                        <?php } ?>

                        <?php if(isset($_POST["add_arami"])){ ?>
                            <input type="hidden" name="add_arami" value="<?php echo $_POST["add_arami"];?>">
                        <?php } ?>

                        <input type="hidden" name="search_reset" value="">
                    </form>
            </div>
        </div>



        <script>

            function reset_search(){
                
                document.getElementById('reset_setting_btn').submit();
            }
        
            // ボタン表示
            function setting_modal(){

                document.getElementById('remote-modal').style.display = 'block';

                document.getElementById('over-close').addEventListener('click', function() {
                    document.getElementById('remote-modal').style.display = 'none';
                });
                document.getElementById('btn-close').addEventListener('click', function() {
                    document.getElementById('remote-modal').style.display = 'none';
                });

            }
            
        </script>


          


            <?php if(isset($_POST["make_arami"])){ ?>
                

                <div class="admin-remote-arami-button-flex" style="justify-content:start">

                    <?php if(!isset($_POST["add_arami"])){ ?>

                        <form action="<?php echo getURLSetSlag("admin_remote_sprit_list"); ?>" method="post" style="text-align: center;">

                            <button type="submit" class="admin-remote-list-return-button">リモート浄霊一覧に戻る</button>

                        </form>

                        <form action="<?php echo getURLSetSlag("admin-arami-sheet-edit"); ?>" id="aramiForm" method="post" style="text-align: center;margin-left:10px;">

                            <button class="admin-remote-make-arami-button">粗見用シート作成</button>

                            <input type="hidden" name="make_arami" value="">

                   <?php }else{ ?>

                         <form action="<?php echo getURLSetSlag("admin-arami-sheet-edit"); ?>" method="post" style="text-align: center;">
                            <input type="hidden" name="arami_sheet_id" value="<?php echo $_POST["add_arami"];?>">
                            <button type="submit" class="admin-remote-list-return-button">粗見シートに戻る</button>

                        </form>

                        <form action="<?php echo getURLSetSlag("admin-arami-sheet-edit"); ?>" id="aramiForm" method="post" style="text-align: center;continue;margin-left:10px;">

                            <button class="admin-remote-make-arami-button">対象者の追加</button>
                            <input type="hidden" name="arami_sheet_id" value="<?php echo $_POST["add_arami"];?>">
                            <input type="hidden" name="add_arami" value="">


                   <?php } ?>
                   
                   <button type="button" onclick="toggleCheckboxes()" class="admin-temporary-registration-all-delete">候補<br>チェックON/OFF</button>
                </div>

            <?php }else{ ?>

                 <form action="<?php echo getURLSetSlag("admin_remote_sprit_list"); ?>" id="" method="post"  onSubmit="return save_check()">


                     <button class="admin-remote-make-arami-button" style="background-color: red;width: 800px;">シート保存</button>

                     <input type="hidden" name="list_save" value="">
               
            <?php } ?>

        </div>

       

    <div class="admin-temporary-registration-check-table">

        <?php 
            // データテーブルだとPOST様にカスタマイズが難しいのでデータテーブル使わない
            $data_table = array();
            $data_table_all = array();
            $data_table_no = 0;


            //var_dump($disp_label_array);


            foreach ($remote_array as $key => $value) {

                $requested_date = get_field('acf_purespirit_requested_date' ,get_field('acf_remote_sprit_id' ,$value)); //依頼日


                if($requested_date != "")
                {
                    $requested_date =  date('Y-m-d',strtotime($requested_date));
                }

                //依頼日絞り込み
                if(isset($current_user_search_data["remote_list_search_requested_start"]) && $disp_label_array["依頼日"] == 1)
                {

                    if($current_user_search_data["remote_list_search_requested_start"] != "")
                    {
                        if($requested_date == "")
                        {
                            continue;
                        }

                        $time1 = strtotime($current_user_search_data["remote_list_search_requested_start"]);
                        $time2 = strtotime($requested_date);

                            

                        if($time1 > $time2)
                        {
                                continue;
                        }
                    }

                }


                if(isset($current_user_search_data["remote_list_search_requested_end"]) && $disp_label_array["依頼日"] == 1)
                {

                    if($current_user_search_data["remote_list_search_requested_end"] != "")
                    {
                        if($requested_date == "")
                        {
                            continue;
                        }

                        $time1 = strtotime($current_user_search_data["remote_list_search_requested_end"]);
                        $time2 = strtotime($requested_date);

                            

                        if($time1 < $time2)
                        {
                                continue;
                        }
                    }

                }


                $applicant_id = get_field('acf_remote_sprit_sheet_applicant_id' ,$value);

                //リモート浄霊シート情報の取得
                $remote_sheet_data = $userClass->getRemoteSpiritData( $value );


                //var_dump($remote_sheet_data);
                //枠素取得
                $slots = get_field('acf_remote_sprit_sheet_target_slots',$value);

                //全体シート情報を取
             


                for ($i=1;$i<=$slots;$i++) {
                    
                    //対象者IDを取得
                    $target_id = $spiritSheet->getRemoteSpritTargetID(  $value ,$i );

                    //対象者設定のみ
                    if(isset($current_user_search_data["remote_list_search_target_setting"]))
                    {
                        if($current_user_search_data["remote_list_search_target_setting"] == 0)
                        {
                            continue;
                        }

                        if($current_user_search_data["remote_list_search_target_setting"] == 1)
                        {
                            continue;
                        }
                    }

                    if(isset($_POST["make_arami"])){

                        if(get_field('acf_remote_sprit_sheet_target_arami_' . $i ,$value) != "")
                        {
                            continue;
                        }

                    }


                    //絞込み用
                    $input_date = $spiritSheet->getRemoteSpritTargetData( 'acf_remote_sprit_sheet_target_input_date' ,  $value ,$i ); //入力完了日


                    if($input_date != "")
                    {
                        $input_date =  date('Y-m-d',strtotime($input_date));
                    }

                    $execution_schedule_date = $spiritSheet->getRemoteSpritTargetData( 'acf_remote_sprit_sheet_target_execution_schedule_date' ,  $value ,$i ); //実行予定日


                    if($execution_schedule_date != "")
                    {
                        $execution_schedule_date =  date('Y-m-d',strtotime($execution_schedule_date));
                    }

                    $execution_date = $spiritSheet->getRemoteSpritTargetData( 'acf_remote_sprit_sheet_target_execution_date' ,  $value ,$i );//実行日


                    if($execution_date != "")
                    {
                        $execution_date =  date('Y-m-d',strtotime($execution_date));
                    }

                    $status = $spiritSheet->getRemoteSpritTargetData( 'acf_remote_sprit_sheet_target_status' ,  $value ,$i );//ステータス

                    //入力完了日絞り込み
                    if(isset($current_user_search_data["remote_list_search_input_date_start"]) && $disp_label_array["入力完了日"] == 1)
                    {

                        if($current_user_search_data["remote_list_search_input_date_start"] != "")
                        {
                            if($input_date == "")
                            {
                                continue;
                            }

                            $time1 = strtotime($current_user_search_data["remote_list_search_input_date_start"]);
                            $time2 = strtotime($input_date);

                            

                            if($time1 > $time2)
                            {
                                    continue;
                            }
                        }

                    }


                    if(isset($current_user_search_data["remote_list_search_input_date_end"])  && $disp_label_array["入力完了日"] == 1)
                    {

                        if($current_user_search_data["remote_list_search_input_date_end"] != "")
                        {
                            if($input_date == "")
                            {
                                continue;
                            }

                            $time1 = strtotime($current_user_search_data["remote_list_search_input_date_end"]);
                            $time2 = strtotime($input_date);

                            

                            if($time1 < $time2)
                            {
                                    continue;
                            }
                        }

                    }

                    //実行予定日絞り込み
                    if(isset($current_user_search_data["remote_list_search_execution_schedule_date_start"]) && $disp_label_array["実行予定日"] == 1)
                    {

                        if($current_user_search_data["remote_list_search_execution_schedule_date_start"] != "")
                        {
                            if($execution_schedule_date == "")
                            {
                                continue;
                            }

                            $time1 = strtotime($current_user_search_data["remote_list_search_execution_schedule_date_start"]);
                            $time2 = strtotime($execution_schedule_date);

                            

                            if($time1 > $time2)
                            {
                                    continue;
                            }
                        }

                    }

                    if(isset($current_user_search_data["remote_list_search_execution_schedule_date_end"]) && $disp_label_array["実行予定日"] == 1)
                    {

                        if($current_user_search_data["remote_list_search_execution_schedule_date_end"] != "")
                        {
                            if($execution_schedule_date == "")
                            {
                                continue;
                            }

                            $time1 = strtotime($current_user_search_data["remote_list_search_execution_schedule_date_end"]);
                            $time2 = strtotime($execution_schedule_date);

                            

                            if($time1 < $time2)
                            {
                                    continue;
                            }
                        }

                    }

                    //実行日絞り込み
                    if(isset($current_user_search_data["remote_list_search_execution_date_start"]) && $disp_label_array["実行日"] == 1)
                    {

                        if($current_user_search_data["remote_list_search_execution_date_start"] != "")
                        {
                            if($execution_date == "")
                            {
                                continue;
                            }

                            $time1 = strtotime($current_user_search_data["remote_list_search_execution_date_start"]);
                            $time2 = strtotime($execution_date);

                            

                            if($time1 > $time2)
                            {
                                    continue;
                            }
                        }

                    }


                    if(isset($current_user_search_data["remote_list_search_execution_date_end"]) && $disp_label_array["実行日"] == 1)
                    {

                        if($current_user_search_data["remote_list_search_execution_date_end"] != "")
                        {
                            if($execution_date == "")
                            {
                                continue;
                            }

                            $time1 = strtotime($current_user_search_data["remote_list_search_execution_date_end"]);
                            $time2 = strtotime($execution_date);

                            

                            if($time1 < $time2)
                            {
                                    continue;
                            }
                        }

                    }

                    //スタータス
                    if(isset($current_user_search_data["remote_list_search_execution_status"]) && $disp_label_array["ステータス"] == 1)
                    {
                        if($current_user_search_data["remote_list_search_execution_status"] != "")
                        {
                            if($current_user_search_data["remote_list_search_execution_status"] == 1 && $status != "完了") continue;
                            else if($current_user_search_data["remote_list_search_execution_status"] == 0 && ($status != "未完了" && $status != "")) continue;
                            else if($current_user_search_data["remote_list_search_execution_status"] == 2 && $status != "キャンセル") continue;
                        }
                    }


                    //会員スタータス
                    if(isset($current_user_search_data["remote_list_search_execution_member_status"]) && $disp_label_array["会員ステータス"] == 1)
                    {
                        if($current_user_search_data["remote_list_search_execution_member_status"] != "")
                        {
                            if( $current_user_search_data["remote_list_search_execution_member_status"] != get_field('acf_purespirit_user_status',get_field('acf_remote_sprit_id' ,$value))) continue;
                            
                        }
                    }


                    //入力未完了
                    if(isset($current_user_search_data["remote_list_search_inputend_setting"]) && $disp_label_array["入力完了日"] == 1)
                    {
                        if($current_user_search_data["remote_list_search_inputend_setting"] != "")
                        {
                            if($current_user_search_data["remote_list_search_inputend_setting"] == 0 && $remote_sheet_data[$i]["入力完了日年月日"] == "") continue;
                            else if($current_user_search_data["remote_list_search_inputend_setting"] == 1 && $remote_sheet_data[$i]["入力完了日年月日"] != "") continue;
                        }
                    }


                    $user_data = get_userdata($applicant_id);

                    // シートID
                    $link_url = getURLSetSlag("admin-spirit-detail")."?user_id=$applicant_id&sheet_name=".get_field('acf_remote_sprit_id' ,$value);
                    
                    $data_table[$value][$i]['input_date'] = $input_date;
                    $data_table[$value][$i]['user_email'] = $user_data->user_email;
                    $data_table[$value][$i]['applicant_id'] = $applicant_id;

                    $data_table[$value][$i]['target_id'] = $target_id;
                    $data_table[$value][$i]['acf_remote_sprit_id'] = get_field('acf_remote_sprit_id' ,$value);
                    $data_table[$value][$i]['acf_remote_sprit_id_url'] = $link_url;

                    $data_table_all[$data_table_no]['i'] = $i;
                    $data_table_all[$data_table_no]['value'] = $value;
                    $data_table_all[$data_table_no]['applicant_name'] = get_user_meta($applicant_id,'last_name',true)."　".get_user_meta($applicant_id,'first_name',true) . "（" .$applicant_id  ."）";// 申込者




                    $data_table_all[$data_table_no]['target_name'] = $remote_sheet_data[$i]["フル名前"];// 対象者

                    if($data_table_all[$data_table_no]['target_name'] != "")
                    {
                         $data_table_all[$data_table_no]['target_url']  = getURLSetSlag("admin-spirit-detail") ."?user_id=" .$applicant_id ."&sheet_name=" .get_field('acf_remote_sprit_id' ,$value) . "#target_id" .$remote_sheet_data[$i]["シート内番号"];
                    }


                    $data_table_all[$data_table_no]['input_date'] = $remote_sheet_data[$i]["入力完了日年月日"];
                    $data_table_all[$data_table_no]['user_email'] = $user_data->user_email;
                    $data_table_all[$data_table_no]['applicant_id'] = $applicant_id;
                    $data_table_all[$data_table_no]['target_id'] = $target_id;

                    


                    $data_table_all[$data_table_no]['acf_remote_sprit_id'] = get_field('acf_remote_sprit_id' ,$value);;

                    // 粗見シート
                    $data_table_all[$data_table_no]['arami'] = "";
                    $data_table_all[$data_table_no]['arami_url'] = "";

                    // メール
                    $data_table[$value][$i]['mail'] = $user_data->user_email;
                    
                    // 依頼日
                    $data_table_all[$data_table_no]['request_date'] = get_field('acf_purespirit_requested_date',get_field('acf_remote_sprit_id' ,$value));


                    
                    $data_table_all[$data_table_no]['end_date'] =  $remote_sheet_data[$i]["入力完了日"];// 入力完了日
                    $data_table_all[$data_table_no]['schedule_date'] =  $remote_sheet_data[$i]["実行予定日"];  // 実行予定日
                    $data_table_all[$data_table_no]['execution_date'] =  $remote_sheet_data[$i]["実行日"]; // 実行日
                    $data_table_all[$data_table_no]['status'] =  $remote_sheet_data[$i]["ステータス"];// ステータス
                    $data_table_all[$data_table_no]['member_status'] =  get_field('acf_purespirit_user_status',get_field('acf_remote_sprit_id' ,$value));// 申込者ステータス

                    $data_table_all[$data_table_no]['born'] =  $remote_sheet_data[$i]["誕生日年月日"];
                    $data_table_all[$data_table_no]['relationship'] =  $remote_sheet_data[$i]["関係"];

                    
                    if($disp_label_array["粗見シート"] == 1){
                        
                        $arami_sheet_id =  $spiritSheet->getRemoteSpritTargetData( 'acf_remote_sprit_sheet_target_arami' ,  $value ,$i ); //粗見シートに入っているかどうか
                        $link_url = getURLSetSlag("admin-arami-sheet-list")."?arami_sheet_id=$arami_sheet_id&arami_easy_list=1";

                        $data_table_all[$data_table_no]['arami_id'] = $arami_sheet_id;
                        $data_table_all[$data_table_no]['arami'] = get_field('acf_arami_title', $arami_sheet_id);
                        $data_table_all[$data_table_no]['arami_url'] = $link_url;

                    }

                    $data_table_no++;

                        if(isset($_POST["make_arami"]) ){}
                        else{
                    ?>
                        <input type="hidden" name="request_sheet[]" value="<?php echo $value;?>">
                        <input type="hidden" name="request_target[]" value="<?php echo $i; ?>">
                    <?php }
                    }
            }

            // var_dump($_POST);  //削除okd

        ?>
        <style>
            .remote-teble{
                width: 1000px;
                /* margin: auto; */
                /* margin-left: 60px; */
            }
            .remote-table-container{

                overflow-x: auto; /* 横スクロールを許可 */
                white-space: nowrap; /* テーブルの折り返しを防ぐ */
                margin-left: 60px;
            }
            
            @media screen and (max-width: 1030px) {
                .admin-temporary-registration-check-table{
                    width: 95%;
                }
            }
            table {
                border-collapse: collapse; /* セルのボーダーを結合 */
                width: 100%; /* 必要なら固定幅を指定 */
            }

            .sticky-col {
                position: sticky; /* 固定する */
                left: 0; /* 固定位置を指定 */
                background-color: #fff; 背景色を指定（必須）
                z-index: 2; /* 他のセルの上に表示 */
                border-right: 1px solid #ddd; /* 右側のボーダーを再描画 */
            }

            /* 2列目以降を調整 */
            .sticky-col:nth-child(2) {
                <?php if(!isset($_POST["add_arami"]) && !isset($_POST["make_arami"])){ ?>
                    left: 80px;
                <?php }else{ ?>

                    left: 40px; /* 1列目の幅 */
                <?php } ?>
                z-index: 2;
            }

            .sticky-col:nth-child(3) {
                <?php if(!isset($_POST["add_arami"]) && !isset($_POST["make_arami"])){ ?>
                    left: 160px;
                <?php }else{ ?>

                    left: 120px; /* 1列目＋2列目の幅 */
                <?php } ?>
                z-index: 2;
            }

            .sticky-col:nth-child(4) {
                <?php if(!isset($_POST["add_arami"]) && !isset($_POST["make_arami"])){ ?>
                    left: 370px;
                <?php }else{ ?>

                    left: 200px; /* 1列目＋2列目＋3列目の幅 */
                <?php } ?>

                z-index: 2;
            }
            /* .sticky-col:nth-child(3) ::after {
                content: "";
                position: absolute;
                top: 0;
                right: 0;
                width: 3px;
                height: 100%;
                background-color: #ddd;
                z-index: 3;
            } */
            .sticky-col:nth-child(4)::after {
                content: ""; /* 擬似要素で区切り線を作成 */
                position: absolute;
                top: 0;
                right: 0;
                width: 1px;
                height: 100%;
                background-color: #ddd; /* 区切り線の色 */
                z-index: 3; /* 他要素の上に表示 */
            }
        </style>
        <div class="remote-table-container">

            <table id="" class="user-disp-table table table-bordered  remote-teble">
                <thead>
                    <tr>
                        <?php if(isset($_POST["make_arami"])){ ?>
                            <th class=" sticky-col"></th>
                        <?php } ?>
                        <th class="thbtn sticky-col">
                            <button type="button" class="tbbtn">
                                <?php $data_table_all = dispUserRemoteThbtn($data_table_all,"id_sort","シートID");?>
                            </button>
                        </th>
                        <th class="thbtn sticky-col">
                            <button type="button" class="tbbtn">
                                <?php $data_table_all = dispUserRemoteThbtn($data_table_all,"applicant_name","申込者");?>
                            </button>
                        </th>
                        <th class="thbtn sticky-col">
                            <button type="button" class="tbbtn">
                                <?php $data_table_all = dispUserRemoteThbtn($data_table_all,"user_email","メール");?>
                            </button>
                        </th>
                        <th class="thbtn sticky-col">
                            <button type="button" class="tbbtn">
                                <?php $data_table_all = dispUserRemoteThbtn($data_table_all,"target_name","対象者");?>
                            </button>
                        </th>
                        <?php if($disp_label_array["粗見シート"] == 1){?>
                            <th class="thbtn">
                            <button type="button" class="tbbtn">
                                <?php $data_table_all = dispUserRemoteThbtn($data_table_all,"arami","粗見シート");?>
                            </button>
                        </th>
                        <?php } ?>
    
                        <?php if($disp_label_array["依頼日"] == 1){?>
                            <th class="thbtn">
                                <button type="button" class="tbbtn">
                                    <?php $data_table_all = dispUserRemoteThDatebtn($data_table_all,"request_date","依頼日");?>
                                </button>
                            </th>
                        <?php } ?>
                        <?php if($disp_label_array["入力完了日"] == 1){?>
                            <th class="thbtn">
                                <button type="button" class="tbbtn">
                                    <?php $data_table_all = dispUserRemoteThDatebtn($data_table_all,"end_date","入力完了日");?>
                                </button>
                            </th>
                        <?php } ?>
                        <?php if($disp_label_array["実行予定日"] == 1){?>
                            <th class="thbtn">
                                <button type="button" class="tbbtn">
                                    <?php $data_table_all = dispUserRemoteThDatebtn($data_table_all,"schedule_date","実行予定日");?>
                                </button>
                            </th>
                        <?php } ?>
                       <?php if($disp_label_array["実行日"] == 1){?>
                         <!-- <th>実行日</th> -->
                         <th class="thbtn">
                                <button type="button" class="tbbtn">
                                    <?php $data_table_all = dispUserRemoteThDatebtn($data_table_all,"execution_date","実行日");?>
                                </button>
                            </th>
                        <?php } ?>
                       <?php if($disp_label_array["ステータス"] == 1){?>
                            <th class="thbtn">
                                <button type="button" class="tbbtn">
                                    <?php $data_table_all = dispUserRemoteThbtn($data_table_all,"status","ステータス");?>
                                </button>
                            </th>
                        <?php } ?>
                        <?php if($disp_label_array["会員ステータス"] == 1){?>
                            <th class="thbtn">
                                <button type="button" class="tbbtn">
                                    <?php $data_table_all = dispUserRemoteThbtn($data_table_all,"member_status","会員ステータス");?>
                                </button>
                            </th>
                        <?php } ?>
                       <?php if($disp_label_array["生年月日"] == 1){?>
                            <th class="thbtn">
                                <button type="button" class="tbbtn">
                                    <?php $data_table_all = dispUserRemoteThbtn($data_table_all,"born","生年月日");?>
                                </button>
                            </th>
                        <?php } ?>
                       
                       <?php if($disp_label_array["関係"] == 1){?>
                        <th class="thbtn">
                                <button type="button" class="tbbtn">
                                    <?php $data_table_all = dispUserRemoteThbtn($data_table_all,"relationship","関係");?>
                                </button>
                            </th>
                        <?php } ?>
                      
                    </tr>
                </thead>
                <tbody>
                    <?php 
                            foreach ($data_table_all as $key => $sheet) {

                                $applicant_id = $sheet['applicant_id'];
                                $input_date = $sheet['input_date'];
                                $execution_date = $sheet['execution_date'];
                                $execution_schedule_date = $sheet['schedule_date'];
                                $target_id = $sheet['target_id'];
                                $requested_date = $sheet['request_date'];
                                $i = $sheet['i'];
                                $value = $sheet['value'];

                               // var_dump($sheet);



                    ?>
    
                    <tr <?php if(isset($_POST["make_arami"])){ if($input_date == "" ||  ( $sheet['arami'] != "")){ ?> style="background-color:darkgray;"   <?php }} ?>>
                                    
                        <?php if(isset($_POST["make_arami"])){ ?>
                            <td class=" sticky-col" style=" text-align: center;<?php if(isset($_POST["make_arami"]) && ( ( $input_date == "") ||  ( $sheet['arami'] != "")) ){ echo 'background-color:darkgray'; }?>" >
                                <?php if($input_date != "" && $sheet['arami'] == ""){ ?><input type="checkbox" name="arami_user[]" value="<?php echo $value;?>_<?php echo $i; ?>" <?php if( isset($arami_sheet_check_array[ $value ."_" .$i ] ) ){ ?> checked <?php } ?>   ><?php } ?>
                            </td>
                        <?php } ?>
    
                        <td  class=" sticky-col" style=" text-align: center;<?php if(isset($_POST["make_arami"]) && ( ( $input_date == "") ||  ( $sheet['arami'] != ""))){ echo 'background-color:darkgray'; }?>" >
                            <a href="<?php echo getURLSetSlag("admin-spirit-detail"); ?>?user_id=<?php echo $applicant_id;?>&sheet_name=<?php echo get_field('acf_remote_sprit_id' ,$value); ?>"  target="_blank">
                                <?php echo get_field('acf_remote_sprit_id' ,$value); ?>
                            </a>
                        </td>
                        <td class=" sticky-col" style="<?php if(isset($_POST["make_arami"]) && ( ( $input_date == "") ||  ( $sheet['arami'] != ""))){ echo 'background-color:darkgray'; }?>">
                            <a href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $applicant_id;?>"  target="_blank">
                                <?php echo $sheet['applicant_name']; ?>
                            </a>
                        </td>
                        
                        <td class=" sticky-col" style="<?php if(isset($_POST["make_arami"]) && ( ( $input_date == "") ||  ( $sheet['arami'] != ""))){ echo 'background-color:darkgray'; }?>">
                            <?php echo $sheet['user_email']; ?>
                        </td>




                        <td class=" sticky-col" style="<?php if(isset($_POST["make_arami"]) && ( ( $input_date == "") ||  ( $sheet['arami'] != ""))){ echo 'background-color:darkgray'; }?>">
                            <?php if($input_date != ""){?>

                                <a href="<?php echo $sheet['target_url']; ?>"  target="_blank">
                                    <?php echo $sheet['target_name']; ?>
                                </a>
                            <?php }else{ ?>
    
                               
    
                            <?php } ?>
                        </td>
                        
                        <?php if($disp_label_array["粗見シート"] == 1){ ?>
    
                            <td>
    
                                <?php 
                                
                                    
                                    // if($arami_sheet_id != ""){
                                    if($sheet['arami_id'] != ""){
                                ?>
                                    <a href="<?php echo getURLSetSlag("admin-arami-sheet-list"); ?>?arami_sheet_id=<?php echo $sheet['arami_id'];?>&arami_easy_list=1">
                                        <?php echo $sheet['arami'];?>
                                    </a>
                                <?php
                                    }
                                
                                
                                ?>
    
    
                            </td>
                        <?php } ?>
                        
                        <?php if($disp_label_array["依頼日"] == 1){ ?>
    
                            <td  style=" text-align: center;">
                                <?php 
    
                                    if($requested_date != ""){
                                        echo date('Y/m/d',strtotime($requested_date));
                                    }
    
                                ?>
                            </td>
                        <?php } ?>
    
                        <?php if($disp_label_array["入力完了日"] == 1){ ?>
    
                            <td  style=" text-align: center;">
                                <?php 
    
                                    if(isset($_POST["make_arami"])){
                                        if($input_date != ""){
                                            // echo date('Y/m/d',strtotime($input_date));
                                            echo $sheet['end_date'];
                                        }
                                    }else{
                                ?>
                                    <input type="date" name="input_end_date[]" value="<?php echo  $sheet['end_date']; ?>" style="border: 0;">
                                <?php
                                    }
                                ?>
                            </td>
                        <?php } ?>
                        
                        <?php if($disp_label_array["実行予定日"] == 1){ ?>
    
                            <td  style=" text-align: center;">
    
                                <?php 
    
                                    if(isset($_POST["make_arami"])){
                                        if($execution_schedule_date != ""){
                                            echo date('Y/m/d',strtotime($execution_schedule_date));
                                        }
                                    }else{
                                ?>
                                    <input type="date"  name="input_schedule_date[]" value="<?php echo $execution_schedule_date; ?>" style="border: 0;">
                                <?php
                                    }
                                ?>
    
                            </td>
    
                        <?php } ?>
                        <?php if($disp_label_array["実行日"] == 1){ ?>
    
                            <td  style=" text-align: center;">
    
                                <?php 
    
                                    if(isset($_POST["make_arami"]) ){
                                        if($execution_date != ""){
                                            echo date('Y/m/d',strtotime($execution_date));
                                        }
                                    }else{
                                ?>
                                    <input type="date" name="input_execution_date[]" value="<?php echo $execution_date; ?>" style="border: 0;">
                                <?php
                                    }
                                ?>
    
                            </td>
    
                        <?php } ?>
                        <?php if($disp_label_array["ステータス"] == 1){ ?>
    
                            <td style=" text-align: center;">
                                <?php 
    
                                    $status_disp = $sheet['status'];
                                    if(isset($_POST["make_arami"]) ){
                                        echo $status_disp;
                                    }else{
                                ?>
                                    <select name="input_status[]" style="font-size: 16px;margin: 3px;margin-left: 0px;border: 0;">
                                            <option value="未完了" <?php if( $status_disp == "未完了") { echo "selected"; }?>>未設定</option>
                                            <option value="完了" <?php if( $status_disp == "完了") { echo "selected"; }?>>完了</option>
                                            <option value="キャンセル" <?php if( $status_disp == "キャンセル") { echo "selected"; }?>>キャンセル</option>
                                        </select>
                                <?php
                                    }
                                ?>
    
                            </td>
    
                        <?php } ?>

                        <?php if($disp_label_array["会員ステータス"] == 1){ ?>
    
                            <td style=" text-align: center;">
                                <?php 
    
                                    $status_disp = $sheet['member_status'];

                                    if($status_disp != "")
                                    {
                                        echo $spiritMemberStatusArray[$status_disp]["title"];
                                    }
                                    else{
                                        echo "未確認";
                                    }
                                ?>
    
                            </td>
    
                        <?php } ?>
                        <?php if($disp_label_array["生年月日"] == 1){ ?>
                            <td style=" text-align: center;"><?php echo $sheet["born"]; ?></td>
                        <?php } ?>

                        <?php if($disp_label_array["関係"] == 1){ ?>
                            <td style=" text-align: center;"><?php echo $sheet["relationship"];?></td>
                        <?php } ?>
                            
                       
                            
                    </tr>
    
    
                    <?php 
                        // }
                    }
                    ?>
    
                </tbody>
    
            </table>
        </div>


        </form>

	<?php } ?>


</div>


 <script>
   
    document.getElementById('aramiForm').addEventListener('submit', function(event) {
        // 最大選択数の設定
        const maxSelections = 20 - <?php echo $minus_make_target;?>;

        const minSelections = 1; // 最小選択数
            
        // 選択されたチェックボックスの数をカウント
        const selectedCount = document.querySelectorAll('input[name="arami_user[]"]:checked').length;

          // チェックが1つもない場合のエラー処理
        if (selectedCount < minSelections) {
            alert(`最低${minSelections}つ以上選択してください。`);
            event.preventDefault(); // フォーム送信をキャンセル
            return; // 処理終了
        }

        // 上限を超えている場合に送信を阻止
        if (selectedCount > maxSelections) {
            alert(`最大${maxSelections}つまでしか選択できません。現在の選択数: ${selectedCount}`);
            event.preventDefault(); // フォーム送信をキャンセル
        }
    });


</script>

    <script>




    // 浄霊シートデータをここに全て格納
    var userData = <?php echo json_encode($data_table); ?>;

    $(document).ready( function () {

        $('#userTable0').DataTable({
            data: userData,
            scrollX: true,
            columns: [
                { data: 'acf_remote_sprit_id' },
                { data: 'name' },   // 申込者
                { data: 'mail' },
                { data: 'target_name' },
                { data: 'arami' },
                { data: 'request_date' },   // 依頼日
                { data: 'end_date' },   // 入力完了日
                { data: 'schedule_date' },   // 実行予定日
                { data: 'execution_date' },   // 実行日
                { data: 'status' },   // 実行日
                { data: 'born' },
                { data: 'relationship' },
                
            ],
            
            "order": [[0, "dec"]], // 第2列（インデックス2）を昇順（asc）にソート
            "paging": false, // ページネーションを非表示
            "pageLength": -1,  // 初期表示件数
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
            }

        });
    } );
    
    
function save_check(){

	if(window.confirm('保存してもよろしいですか？')){ // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

	}
	else{ // 「キャンセル」時の処理

		window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止

	}

}
</script>

