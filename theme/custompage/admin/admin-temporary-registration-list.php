
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo get_template_directory_uri()."/custompage/admin/a-gate-script.js"?>"></script>

<?php

require_once ("a-gate-functions.php");

//require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
//require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");


require_once (dirname(__FILE__)."/../../class/TemporaryRegistrationClass.php");
require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");



 //自分の情報
 $user = wp_get_current_user();

$TemporaryRegistration = new TemporaryRegistrationClass(); //管理データ
$spiritSheet = new SpiritSheetClass(); //管理データ

//単発の新規登録
if( isset($_POST["save_registration_data"]) )
{

    if(!$TemporaryRegistration->IsTemporaryRegistrationOrderNumber($_POST["acf_temporary_order_num"]))
    {
        //同じオーダー番号がない
        $TemporaryRegistration->newTemporaryRegistration($_POST);
    }
    else{
        //同じオーダー番号がある
         $TemporaryRegistration->UpDataTemporaryRegistrationOrderNumber( $_POST );//上書き
    }
    
}

$delete_order_num = "";

//単発の削除
if(isset($_POST["delete_id"]))
{
   $delete_order_num = get_field("acf_temporary_order_num" , $_POST["delete_id"]);


   //削除に保存
   if(!$TemporaryRegistration->IsTemporaryDeleteOrderNumber( $delete_order_num ))//保存チェック
   {
       $TemporaryRegistration->newTemporaryDelete( $delete_order_num ,  get_field("acf_temporary_last_name", $_POST["delete_id"]) . " " .get_field("acf_temporary_first_name", $_POST["delete_id"])  );
   }

   wp_delete_post($_POST["delete_id"], true);
}

//一括の削除
if(isset($_POST["set_delete"]))
{
    foreach($_POST["temporary_delete"] as  $key => $value) {


        $order_num = get_field("acf_temporary_order_num" , $value);

        $delete_order_num .= $order_num . ",";

        //削除に保存
       if(!$TemporaryRegistration->IsTemporaryDeleteOrderNumber( $order_num ))//保存チェック
       {
           $TemporaryRegistration->newTemporaryDelete( $order_num ,  get_field("acf_temporary_last_name", $value) . " " .get_field("acf_temporary_first_name", $value)  );
       }


        wp_delete_post($value, true);

    }
}

//一括登録
if(isset($_POST["set_registration"]))
{

    $users = get_users();
    $user_data = array();

    foreach($users as  $key => $value) {

        $user_data[$value->user_email] = $value->ID;


    }

    $register_data = array();

   // var_dump($user_data);

    foreach($_POST["temporary_registration"] as  $key => $value) {
       
        //ユーザーに同じ名前がないかどうかを確認する
        if(!isset( $user_data[ get_field("acf_temporary_mail", $value) ] )) //メールアドレスで策に確認
        {
            $is_register = true;

            foreach($users as  $users_key => $users_value) {

                 if(  get_field("acf_temporary_last_name", $value) ==  get_user_meta($users_value,'last_name',true) &&  get_field("acf_temporary_first_name", $value) ==  get_user_meta($users_value,'first_name',true) )
                 {
                     $is_register = false;
                     break;
                 }

                 /*if(strpos(get_field("acf_temporary_item_name", $value), '名様用') == false)
                 {
                     $is_register = false;
                     break;
                 }
                 */
             }

             if($is_register)
             {

                 //枠数
                  $item = get_field("acf_temporary_item_name", $value);
                  $item_waku =  $TemporaryRegistration->getStringBetween($item, "（", "名様用");

                if($item_waku != null)
                {
                    $register_data[$value] = array();
                    $register_data[$value]["acf_temporary_last_name"] = get_field("acf_temporary_last_name", $value); //苗字
                    $register_data[$value]["acf_temporary_first_name"] = get_field("acf_temporary_first_name", $value);//名前
                    $register_data[$value]["acf_temporary_mail"] = get_field("acf_temporary_mail", $value);//メール
                    $register_data[$value]["acf_temporary_post_number"] = get_field("acf_temporary_post_number", $value);//郵便番号
                    $register_data[$value]["acf_temporary_address_1"] =get_field("acf_temporary_address_1", $value) . get_field("acf_temporary_address_2", $value);//住所


                    $tel = get_field("acf_temporary_tel", $value); //電話番号

                    $tel_result = $TemporaryRegistration->splitPhoneNumber($tel);

                    $register_data[$value]["tel1"] = $tel_result['area_code'];
                    $register_data[$value]["tel2"] = $tel_result['city_code'];
                    $register_data[$value]["tel3"] = $tel_result['subscriber_number'];
                    $register_data[$value]["tel_type"] = $tel_result['type'];

                    //枠数
                    $register_data[$value]["waku"] = $item_waku;

                    //オーダー日
                    $register_data[$value]["acf_temporary_order_day"] = get_field("acf_temporary_order_day", $value);

                    //決済完了日
                    $register_data[$value]["acf_temporary_payment_end"] = get_field("acf_temporary_payment_end", $value);


                    $order_num = get_field("acf_temporary_order_num" , $value);

                   // $delete_order_num .= $order_num . ",";


                      //削除に保存
                    if(!$TemporaryRegistration->IsTemporaryDeleteOrderNumber( $order_num ))//保存チェック
                    {
                        $TemporaryRegistration->newTemporaryDelete( $order_num ,  get_field("acf_temporary_last_name", $value) . " " .get_field("acf_temporary_first_name", $value)  );
                    }


                    wp_delete_post($value, true);

                  }


               

             }
           
        }
    }

    //登録がある
   foreach($register_data as  $key => $value) {

        //ユーザー登録
        $make_user_test = array(
            'input_last_name' => $value["acf_temporary_last_name"],
            'input_first_name' => $value["acf_temporary_first_name"],
            'input_user_email' => $value["acf_temporary_mail"],
            'input_tel_1' => $value["tel1"],
            'input_tel_2' =>  $value["tel2"],
            'input_tel_3' =>  $value["tel3"],
            'input_post_no' => $value["acf_temporary_post_number"],
            'input_address1' => $value["acf_temporary_address_1"],

        );
        $resdt = setRemoteUserRegistMiddle($make_user_test);


        //シートを作成(リモート浄霊追加)
		$add_id = $spiritSheet->newSpiritSheetUnixtime($resdt, 77 ,time());

        //ユーザーの配列を追加
		if($add_id != "")
		{
            //依頼sheetの作成
			$spiritSheet->newSpiritSheetUserAdd( $resdt , $add_id );


			//リモート浄霊シートの作成
			$target_array = array();

            //枠数
            $target_count = $value["waku"];

			$user = wp_get_current_user();

            $sheet_id = "";

            $sheet_id = $spiritSheet->newRemoteSpritSlots(   $user->ID , $resdt );
            $target_count = $target_count;


			//依頼日と申込者だけ入力する
            update_field("acf_temporary_payment_end", $value["acf_temporary_payment_end"], $add_id);//決済完了日
			update_field("acf_purespirit_requested_date", $value["acf_temporary_order_day"], $add_id);//申込日
			update_field("acf_applicant", $resdt, $add_id);//申込者
			update_field("acf_purespirit_remote_sprit_num", $sheet_id, $add_id);//浄霊シートID

            //浄霊シート番号を入れる
            update_field("acf_remote_sprit_id", $add_id, $sheet_id);
            //枠数を入れる
            update_field("acf_remote_sprit_sheet_target_slots", $target_count, $sheet_id);


        }

    }
    // var_dump($register_data);
}


//登録しているユーザーで登録する
if(isset($_POST["set_target_one"]))
{
    $resdt = $_POST["set_id"];

    //枠数
    $item = get_field("acf_temporary_item_name", $_POST["temporary_id"]);
    $item_waku =  $TemporaryRegistration->getStringBetween($item, "（", "名様用");



    if($item_waku != null && $item != "")
    {
        //シートを作成(リモート浄霊追加)
        $add_id = $spiritSheet->newSpiritSheetUnixtime($resdt, 77 ,time());

    
        //ユーザーの配列を追加
	    if($add_id != "")
	    {
            //依頼sheetの作成
		    $spiritSheet->newSpiritSheetUserAdd( $resdt , $add_id );


		    //リモート浄霊シートの作成
		    $target_array = array();

            //枠数
            $target_count = $item_waku;

		    $user = wp_get_current_user();

            $sheet_id = "";

            $sheet_id = $spiritSheet->newRemoteSpritSlots(   $user->ID , $resdt );
            $target_count = $target_count;


		    //依頼日と申込者だけ入力する
            update_field("acf_temporary_payment_end", get_field("acf_temporary_payment_end", $_POST["temporary_id"]), $add_id);//決済完了日
		    update_field("acf_purespirit_requested_date", get_field("acf_temporary_order_day", $_POST["temporary_id"]), $add_id);//申込日
		    update_field("acf_applicant", $resdt, $add_id);//申込者
		    update_field("acf_purespirit_remote_sprit_num", $sheet_id, $add_id);//浄霊シートID

            //浄霊シート番号を入れる
            update_field("acf_remote_sprit_id", $add_id, $sheet_id);
            //枠数を入れる
            update_field("acf_remote_sprit_sheet_target_slots", $target_count, $sheet_id);

            $order_num = get_field("acf_temporary_order_num" , $_POST["temporary_id"]);

            //$delete_order_num .= $order_num . ",";

            //仮登録ユーザーの削除
              //削除に保存
            if(!$TemporaryRegistration->IsTemporaryDeleteOrderNumber( $order_num ))//保存チェック
            {
                $TemporaryRegistration->newTemporaryDelete( $order_num ,  get_field("acf_temporary_last_name", $_POST["temporary_id"]) . " " .get_field("acf_temporary_first_name", $_POST["temporary_id"])  );
            }


            wp_delete_post($_POST["temporary_id"], true);

        }
    }

}




//検索用
if(isset($_POST["search_save"]))
{

    $search_array = array();


    foreach($_POST as  $key => $value) {


        $search_array[$key] = $value;

    }

    $json_data = json_encode($search_array, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);


    update_user_meta($user->ID,"temporary_search_save",$json_data);

    //var_dump($json_data);
}

  //検索リセット
  if(isset($_POST["search_reset"]))
  {
      update_user_meta($user->ID,"temporary_search_save","");
  }


  //検索の保存情報を取得する
  $search_json_data = get_user_meta( $user->ID,'temporary_search_save',true);

  $search_save_data = array();

  if($search_json_data != "")
  {
      $search_save_data = json_decode($search_json_data, true);

      //var_dump($search_save_data);
  }



 //候補用のユーザーデータの作成
 $users_data = get_users();
 $user_candidate_last_name = array();
 $user_candidate_first_name = array();
 $user_candidate_mail = array();


foreach($users_data as  $key => $value) {

    if(!isset($user_candidate_mail[$value->user_email]))$user_candidate_mail[$value->user_email] = $value->ID;
    if(!isset($user_candidate_last_name[ get_user_meta($value->ID,'last_name',true)]))$user_candidate_last_name[ get_user_meta($value->ID,'last_name',true)] = $value->ID;
    if(!isset($user_candidate_first_name[ get_user_meta($value->ID,'first_name',true)]))$user_candidate_first_name[ get_user_meta($value->ID,'first_name',true)] = $value->ID;

}



//var_dump($_POST);

?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/4.0.2/js/dataTables.fixedColumns.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/4.0.2/css/fixedColumns.dataTables.min.css">
    
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 
 <script>
    window.onload = function() {
        // PHPでの成功フラグを確認
        <?php if($delete_order_num != ""): ?>
            alert("オーダー番号【<?php echo $delete_order_num;?>】を削除しました");
        <?php endif; ?>
    }

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


<div class="admin-profile-edit-area" style="width: 100%;max-width: 100%;">


    <div class="admin-profile-edit-title-box">
        <?php if(isset($_POST["all_delete"])){ ?>
             <div class="admin-exorcism-menu-title" style="text-align: center;">枠数登録一覧(一括削除)</div>
        <?php }else if(isset($_POST["all_registration"])){ ?>
             <div class="admin-exorcism-menu-title" style="text-align: center;">枠数登録一覧(一括登録)</div>
        <?php }else{ ?>
            <div class="admin-exorcism-menu-title" style="text-align: center;">枠数登録一覧</div>
        <?php } ?>
    </div>

     <?php if(isset($_POST["all_registration"])){ ?>

        <div style="margin-top: 10px;font-weight: 900;color: red;text-align: center;font-size: larger;">登録候補がある場合は一括登録で選択できません。編集から登録するかどうかを選択してください</div>

     <?php } ?>


    <?php if(!isset($_POST["csvCheck"])){ ?>

        <?php 

            if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["csvFile"]) && $_POST["csvFile"] != "") {

                $filePath = dirname(__FILE__) .'/../../fileupload/' .$_POST['csvFile'];

                //echo $filePath;

                if (($handle = fopen($filePath, "r")) !== FALSE) {

                    $csvData = [];


                     // 最初の行でエンコーディングを自動検出
                    $firstLine = fgets($handle);
                    $encoding = mb_detect_encoding($firstLine, "SJIS-win,UTF-8,EUC-JP,JIS");

                      // ファイルポインタを最初に戻す
                    rewind($handle);

                    $csv_count = 0;

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        // エンコーディングをUTF-8に変換
                        $data = array_map(function($value) use ($encoding) {
                            // 文字列のエンコードを変換
                            $value = mb_convert_encoding($value, "UTF-8", $encoding);
                            // 制御文字や特殊文字を削除
                            return preg_replace('/[\x00-\x1F\x7F]/u', '', $value);
                        }, $data);


                        //オーダー日があるなら比べる
                        if($_POST["order_date"] != "" && $data[3] != "" && $csv_count > 0)
                        {
                            $d1 = new DateTime($_POST["order_date"]);
                            $d2 = new DateTime($data[3]);

                           // echo $d1 ." " .$d2 ."<br>";

                            if($d1 >= $d2)
                            {
                                continue;
                            }
                        }

                        //オーダー日があるなら比べる
                        if($_POST["pay_date"] != "" && $data[4] != "" && $csv_count > 0)
                        {
                            $d1 = new DateTime($_POST["pay_date"]);
                            $d2 = new DateTime($data[4]);

                             //echo $d1 ." " .$d2 ."<br>";

                            if($d1 >= $d2)
                            {
                                continue;
                            }
                        }

                        $csv_count++;

                        //データがちゃんと入っているものだけ
                        if(count($data) >= 55)
                        {
                            $csvData[] = $data;
                        }
                    }
                    fclose($handle);

                    //var_dump($csvData);
                }
    
                //保存データーに直す
                $up_date_array =  $TemporaryRegistration->ChengeCSVData( $csvData );

                foreach ($up_date_array as $key => $value) {

                    if(!$TemporaryRegistration->IsTemporaryRegistrationOrderNumber($value["acf_temporary_order_num"]))
                    {
                        //同じオーダー番号がない
                        $TemporaryRegistration->newTemporaryRegistration($value);
                    }
                    else{
                        //同じオーダー番号がある
                         $TemporaryRegistration->UpDataTemporaryRegistrationOrderNumber( $value );//上書き
                    }
                    

                }
               
            }

        
            //var_dump($_POST);
        
        
        ?>

        <?php 
        
            $temporary_data =  $TemporaryRegistration->getAllTemporaryRegistration( );
        
            $search_item_array = array();

            //検索用のアイテムを作る
            foreach ($temporary_data as $key => $value) {

                $item_namme =  get_field("acf_temporary_item_name", $key);

                if(!isset($search_item_array[ $item_namme ]))
                {
                    $search_item_array[ $item_namme ] = $item_namme;
                }
           
            }
            asort($search_item_array);
        ?>

        <style>
            .admin-spritsheet-search-flex{
                flex-wrap: wrap;
                gap: 20px;
            }

            @media screen and (max-width: 1200px) {
                .admin-spritsheet-search-flex{
                    flex-wrap: wrap;
                    gap: 20px;
                }
                .admin-spritsheet-search-date{
                    width: 400px;
                    margin-right: 0;
                }
                .admin-temporary-registration-post-area{
                    gap: 20px;
                }
                .admin-temporary-registration-new-post-area{

                    margin-left: 0;
                    margin-right: 0;
                }
                .admin-temporary-registration-new-post-submit{
                    width: 200px;
                }
            }
        </style>

        <?php if(count($temporary_data) >= 1){?>

            <div class="admin-temporary-search-area">

                <div class="admin-spritsheet-search-area">

                    <form action="<?php echo getURLSetSlag("admin-temporary-registration-list"); ?>" method="post">


                        <div class="admin-spritsheet-search-flex" style="margin-right: 10px;">
                            <div class="admin-spritsheet-search-type" >

                                <div class="admin-spritsheet-search-ravel" style="margin-right: 6px;">
                                    アイテム名
                                </div>

                                <select class="table-squeeze" name="search_sprit_type" id="">
                                    <option value="">すべて表示</option>
                                    <?php 
                                        foreach ($search_item_array as $key => $value) {
                                    ?>
                                    <option value="<?php echo $key; ?>" <?php if( isset($search_save_data['search_sprit_type']) && $search_save_data['search_sprit_type'] == $key) echo "selected"; ?>><?php echo $key;?></option>
                                    <?php 
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="admin-spritsheet-search-date" >

                                <div class="admin-spritsheet-search-ravel"  style="margin-right: 6px;">
                                    オーダー日
                                </div>


                                <div class="admin-spritsheet-search-date-start">
                                    <input type="date" id="" name="search_sprit_request_start" value="<?php if( isset($search_save_data['search_sprit_request_start'])){  echo $search_save_data['search_sprit_request_start']; }?>">
                                </div>

                                 <div class="admin-spritsheet-search-ravel" style="margin-left: 5px;margin-right: 5px;">～</div>

                                <div class="admin-spritsheet-search-date-end">
                                    <input type="date" id="" name="search_sprit_request_end" value="<?php if( isset($search_save_data['search_sprit_request_end'])){  echo $search_save_data['search_sprit_request_end']; }?>">
                                </div>

                            </div>

                            <div class="admin-spritsheet-search-date" >

                                <div class="admin-spritsheet-search-ravel"  style="margin-right: 6px;">
                                    決済完了日
                                </div>


                                <div class="admin-spritsheet-search-date-start">
                                    <input type="date" id="" name="search_sprit_requestconfirmed_start" value="<?php if( isset($search_save_data['search_sprit_requestconfirmed_start'])){  echo $search_save_data['search_sprit_requestconfirmed_start']; }?>">
                                </div>

                                 <div class="admin-spritsheet-search-ravel" style="margin-left: 5px;margin-right: 5px;">～</div>

                                <div class="admin-spritsheet-search-date-end">
                                    <input type="date" id="" name="search_sprit_requestconfirmed_end" value="<?php if( isset($search_save_data['search_sprit_requestconfirmed_end'])){  echo $search_save_data['search_sprit_requestconfirmed_end']; }?>">
                                </div>

                            </div>

                             <input type="hidden"  name="search_save">

                             <?php if(isset($_POST["all_delete"])){ ?>
                                <input type="hidden"  name="all_delete">
                            <?php } ?>

                            <div class="admin-spritsheet-search-submit-button" >
                                <input type="submit" value="絞り込む" class="admin-spritsheet-search-submit" style="width: 130px;">
                            </div>

                            <button type="button" class="admin-spritsheet-search-reset" onclick="document.getElementById('reset_btn').submit();" style="width: 100px;height: 30px;margin:0">リセット</button>


                        </div>

               
                    </form>
                </div>


                <div class="admin-spritsheet-search-area" style="display:none">

                    <form action="<?php echo getURLSetSlag("admin-temporary-registration-list"); ?>" method="post" id="reset_btn">

                         <input type="hidden"  name="search_reset">

                            <div class="admin-spritsheet-search-submit-button" >
                                <input type="submit" value="リセット" class="admin-spritsheet-search-reset" style="width: 100px;height: 30px;">
                            </div>

                    </form>
                </div>
            </div>

        <?php } ?>

       

        <div class="admin-temporary-registration-post-area">
            
        
            <?php if(isset($_POST["all_delete"])){ ?>
                <button onclick="toggleCheckboxes()" class="admin-temporary-registration-all-delete">削除<br>チェックON/OFF</button>
            <?php } ?>

            <?php if(isset($_POST["all_registration"])){ ?>
                <button onclick="toggleCheckboxes()" class="admin-temporary-registration-all-delete">登録<br>チェックON/OFF</button>
            <?php } ?>

            <div class="admin-temporary-registration-new-post-area">

                <?php if(!isset($_POST["all_delete"]) && !isset($_POST["all_registration"])){ ?>
                    <form action="<?php echo getURLSetSlag("admin-temporary-registration-new"); ?>" method="post" enctype="multipart/form-data">
                        <button type="submit"  class="admin-temporary-registration-new-post-submit">新規登録</button>
                    </form>
                <?php }else{ ?>
                    <form action="<?php echo getURLSetSlag("admin-temporary-registration-list"); ?>" method="post" enctype="multipart/form-data">
                        <button type="submit"  class="admin-temporary-registration-new-post-submit">一覧に戻る</button>
                    </form>


                <?php } ?>
                    

            </div>


             <div class="admin-temporary-registration-new-post-area">

                <?php if(!isset($_POST["all_delete"]) && !isset($_POST["all_registration"])){ ?>
                    <form action="<?php echo getURLSetSlag("admin-temporary-registration-list"); ?>" method="post" enctype="multipart/form-data">
                        <button type="submit"  class="admin-temporary-registration-new-post-submit" style="background-color: blue;color: white;">一括枠登録</button>
                        <input type="hidden"  name="all_registration">
                    </form>
                <?php }else if(isset($_POST["all_registration"])){ ?>
                
                    <form action="<?php echo getURLSetSlag("admin-temporary-registration-list"); ?>" method="post" enctype="multipart/form-data" onSubmit="return upload_check()">
                        <button type="submit"  class="admin-temporary-registration-new-post-submit" style="background-color: blue;color: white;">枠登録</button>
                        <input type="hidden"  name="set_registration">



                <?php } ?>
                    

            </div>


            <?php if(!isset($_POST["all_registration"])){ ?>
                 <div class="admin-temporary-registration-new-post-area">

                    <form action="<?php echo getURLSetSlag("admin-temporary-registration-list"); ?>" method="post" enctype="multipart/form-data" <?php if(isset($_POST["all_delete"])){ ?> onSubmit="return delete_check()"<?php } ?>>
                         <?php if(!isset($_POST["all_delete"])){ ?>
                            <input type="hidden"  name="all_delete">
                         <?php }else{ ?>
                             <input type="hidden"  name="set_delete">

                         <?php } ?>

                        <button type="submit"  class="admin-temporary-registration-new-post-submit" style="background-color: crimson;color: white;">一括削除</button>

                        <?php if(!isset($_POST["all_delete"])){ //通常時は画面以降のみ?>
                            </form>
                        <?php } ?>
                </div>
             <?php } ?>

        </div>


        

        <?php if(count($temporary_data) >= 1){?>

            <div class="admin-temporary-registration-check-table">

                <?php /* 
                    <table id="userTable" class="user-disp-table table table-bordered">
                        <thead>
                            <tr>
                                <?php if(isset($_POST["all_delete"])){ ?>
                                    <th style="">削除</th>
                                <?php } ?>

                                <?php if(isset($_POST["all_registration"])){ ?>
                                    <th style="">登録</th>
                                <?php } ?>

                                <th style="">オーダー番号</th>
                                <?php if(!isset($_POST["all_delete"]) && !isset($_POST["all_registration"])){ ?>
                                    <th>編集</th>
                                <?php } ?>
                                <th>ステータス</th>
                                <th>支払方法</th>
                                <th>オーダー日</th>
                                <th>決済完了日</th>
                                <th>アイテム名</th>
                                <th>品番</th>
                                <th>小計</th>
                                <th>名前</th>
                                <th>候補</th>
                                <th>住所</th>
                                <th>電話番号</th>
                                <th style="">メールアドレス</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($temporary_data as $key => $value) {?>


                                <?php
                                
                                    $temporary_status =  get_field("acf_temporary_item_name", $key);

                                    //依頼内容
                                    if( isset($search_save_data['search_sprit_type']) && $search_save_data['search_sprit_type'] != "" && isset( $search_item_array[ $search_save_data['search_sprit_type'] ]))
                                    {
                                        if($search_save_data['search_sprit_type'] != $temporary_status )
                                        {
                                            continue;
                                        }
                                    }
                                
                                    //オーダー日
                                    $order_date = get_field('acf_temporary_order_day',$key);


                                    //開始日
                                    if( isset($search_save_data['search_sprit_request_start']) && $search_save_data['search_sprit_request_start'] != "")
                                    {

                                        if($order_date == "")
                                        {
                                            continue;
                                        }

                                        $d1 = new DateTime($order_date);
                                        $d2 = new DateTime($search_save_data['search_sprit_request_start']);

                                        if($d1 < $d2)
                                        {
                                            continue;
                                        }
                                    }

                                    //終了日
                                    if( isset($search_save_data['search_sprit_request_end']) && $search_save_data['search_sprit_request_end'] != "")
                                    {

                                        if($order_date == "")
                                        {
                                            continue;
                                        }

                                        $d1 = new DateTime($order_date);
                                        $d2 = new DateTime($search_save_data['search_sprit_request_end']);

                                        if($d1 > $d2)
                                        {
                                            continue;
                                        }
                                    }
                                

                                    //決済完了日
                                    $payment_end = get_field('acf_temporary_payment_end',$key);


                                    //開始日
                                    if( isset($search_save_data['search_sprit_requestconfirmed_start']) && $search_save_data['search_sprit_requestconfirmed_start'] != "")
                                    {

                                        if($payment_end == "")
                                        {
                                            continue;
                                        }

                                        $d1 = new DateTime($payment_end);
                                        $d2 = new DateTime($search_save_data['search_sprit_requestconfirmed_start']);

                                        if($d1 < $d2)
                                        {
                                            continue;
                                        }
                                    }

                                    //終了日
                                    if( isset($search_save_data['search_sprit_requestconfirmed_end']) && $search_save_data['search_sprit_requestconfirmed_end'] != "")
                                    {

                                        if($payment_end == "")
                                        {
                                            continue;
                                        }

                                        $d1 = new DateTime($payment_end);
                                        $d2 = new DateTime($search_save_data['search_sprit_requestconfirmed_end']);

                                        if($d1 > $d2)
                                        {
                                            continue;
                                        }
                                    }



                                    //候補を選択
                                    $is_candidate = false;

                                    if(isset($user_candidate_last_name[get_field("acf_temporary_last_name", $key)]) && isset($user_candidate_first_name[get_field("acf_temporary_first_name", $key)])){ $is_candidate = true; }
                                    else if(isset($user_candidate_mail[ get_field("acf_temporary_mail", $key)])){ $is_candidate = true; }
                                ?>






                                <tr>
                                    <?php if(isset($_POST["all_delete"])){ ?>
                                        <td style=""><input type="checkbox" name="temporary_delete[]" value="<?php echo $key;?>"></td>
                                    <?php } ?>

                                    <?php if(isset($_POST["all_registration"])){ ?>
                                    
                                            <td style=""> <?php if(!$is_candidate){ //候補はない?><input type="checkbox" name="temporary_registration[]" value="<?php echo $key;?>"> <?php } ?></td>
                                    
                                    <?php } ?>

                                    <td style=""><?php echo  get_field("acf_temporary_order_num" , $key); ?></td>


                                    <?php if(!isset($_POST["all_delete"])&& !isset($_POST["all_registration"])){ ?>
                                        <td>
                                            <div class="" style="display: flex;">
                                                <div class="" style="display: flex;">
                                                    <button class="edit-mark" style="width: 30px;font-size: 15px;"><a href="<?php echo getURLSetSlag("admin-temporary-registration-new"); ?>?temporary_id=<?php echo $key;?>">編</a></button>
                                                </div>
                            
                                            </div>
                                        </td>
                                    <?php } ?>
                                    <td><?php echo  get_field("acf_temporary_status", $key); ?></td>
                                    <td><?php echo  get_field("acf_temporary_payment", $key); ?></td>
                                    <td><?php echo  get_field("acf_temporary_order_day", $key); ?></td>
                                    <td><?php echo  get_field("acf_temporary_payment_end", $key); ?></td>
                                    <td><?php echo  get_field("acf_temporary_item_name", $key); ?></td>
                                    <td><?php echo  get_field("acf_temporary_product_number", $key); ?></td>
                                    <td><?php echo  get_field("acf_temporary_subtotal", $key); ?></td>
                                    <td><?php echo  get_field("acf_temporary_last_name", $key) . " " .get_field("acf_temporary_first_name", $key); ?></td>
                                    <td>
                                        <?php 
                                
                                            if($is_candidate){echo "〇";}
                                        ?>
                                    </td>


                                    <td>
                                        <?php echo  get_field("acf_temporary_post_number", $key); ?><br>
                                        <?php echo  get_field("acf_temporary_address_1", $key); ?><?php echo  get_field("acf_temporary_address_2", $key); ?>
                                    </td>
                                    <td><?php echo  get_field("acf_temporary_tel", $key); ?></td>
                                    <td><?php echo  get_field("acf_temporary_mail", $key); ?></td>
                                    </tr>
                            <?php } ?>

                        </tbody>

                    </table>
                 */?>

                <table id="userTable0" class="user-disp-table table table-bordered">
                    <thead>
                        <tr>
                            <?php if(isset($_POST["all_delete"])){ ?>
                                <th style="">削除</th>
                            <?php } ?>

                             <?php if(isset($_POST["all_registration"])){ ?>
                                <th style="">登録</th>
                            <?php } ?>

                            <th style="">オーダー番号</th>
                            <?php if(!isset($_POST["all_delete"]) && !isset($_POST["all_registration"])){ ?>
                                <th>編集</th>
                            <?php } ?>
                            <th>ステータス</th>
                            <th>支払方法</th>
                            <th>オーダー日</th>
                            <th>決済完了日</th>
                            <th>アイテム名</th>
                            <th>品番</th>
                            <th>小計</th>
                            <th>名前</th>
                            <th>候補</th>
                            <th>住所</th>
                            <th>電話番号</th>
                            <th style="">メールアドレス</th>
                        </tr>
                    </thead>

                        <?php 
                            $remote_data = array();
                            $remote_data_no = 0;
                            foreach ($temporary_data as $key => $value) {
                                $temporary_status =  get_field("acf_temporary_item_name", $key);

                                //依頼内容
                                if( isset($search_save_data['search_sprit_type']) && $search_save_data['search_sprit_type'] != "" && isset( $search_item_array[ $search_save_data['search_sprit_type'] ]))
                                {
                                    if($search_save_data['search_sprit_type'] != $temporary_status )
                                    {
                                        continue;
                                    }
                                }

                                //オーダー日
                                $order_date = get_field('acf_temporary_order_day',$key);


                                //開始日
                                if( isset($search_save_data['search_sprit_request_start']) && $search_save_data['search_sprit_request_start'] != "")
                                {

                                    if($order_date == "")
                                    {
                                        continue;
                                    }

                                    $d1 = new DateTime($order_date);
                                    $d2 = new DateTime($search_save_data['search_sprit_request_start']);

                                    if($d1 < $d2)
                                    {
                                        continue;
                                    }
                                }

                                //終了日
                                if( isset($search_save_data['search_sprit_request_end']) && $search_save_data['search_sprit_request_end'] != "")
                                {

                                    if($order_date == "")
                                    {
                                        continue;
                                    }

                                    $d1 = new DateTime($order_date);
                                    $d2 = new DateTime($search_save_data['search_sprit_request_end']);

                                    if($d1 > $d2)
                                    {
                                        continue;
                                    }
                                }

                                
                                //決済完了日
                                $payment_end = get_field('acf_temporary_payment_end',$key);


                                //開始日
                                if( isset($search_save_data['search_sprit_requestconfirmed_start']) && $search_save_data['search_sprit_requestconfirmed_start'] != "")
                                {

                                    if($payment_end == "")
                                    {
                                        continue;
                                    }

                                    $d1 = new DateTime($payment_end);
                                    $d2 = new DateTime($search_save_data['search_sprit_requestconfirmed_start']);

                                    if($d1 < $d2)
                                    {
                                        continue;
                                    }
                                }

                                 //終了日
                                if( isset($search_save_data['search_sprit_requestconfirmed_end']) && $search_save_data['search_sprit_requestconfirmed_end'] != "")
                                {

                                    if($payment_end == "")
                                    {
                                        continue;
                                    }

                                    $d1 = new DateTime($payment_end);
                                    $d2 = new DateTime($search_save_data['search_sprit_requestconfirmed_end']);

                                    if($d1 > $d2)
                                    {
                                        continue;
                                    }
                                }

                                //候補を選択
                                $is_candidate = false;

                                if(isset($user_candidate_last_name[get_field("acf_temporary_last_name", $key)]) && isset($user_candidate_first_name[get_field("acf_temporary_first_name", $key)])){ $is_candidate = true; }
                                else if(isset($user_candidate_mail[ get_field("acf_temporary_mail", $key)])){ $is_candidate = true; }
                            
                                if(isset($_POST["all_delete"])){
                                    $remote_data[$remote_data_no]["acf_temporary_status"] = "<input type='checkbox' name='temporary_delete[]' value='$key'>";
                                }
                                if(isset($_POST["all_registration"])){
                                    if(!$is_candidate){

                                        $remote_data[$remote_data_no]["acf_temporary_registration"] = "<input type='checkbox' name='temporary_registration[]' value='$key'>";
                                    }else{

                                        $remote_data[$remote_data_no]["acf_temporary_registration"] = "";
                                    }
                                    
                                }
                                $remote_data[$remote_data_no]["acf_temporary_order_num"] = get_field("acf_temporary_order_num" , $key);    // オーダー番号

                                if(!isset($_POST["all_delete"])&& !isset($_POST["all_registration"])){
                                    $link_url = getURLSetSlag('admin-temporary-registration-new')."?temporary_id=$key";

                                    $remote_data[$remote_data_no]["edit_btn"] = "<div class='' style='display: flex;'>
                                            <div class='' style='display: flex;'>
                                                <button class='edit-mark' style='font-size: 15px;'><a href='$link_url'>編集</a></button>
                                            </div>
                                        </div>";
                                }

                                $remote_data[$remote_data_no]["acf_temporary_status"] = get_field("acf_temporary_status", $key);    // ステータス
                                $remote_data[$remote_data_no]["acf_temporary_payment"] = get_field("acf_temporary_payment", $key);;    // 支払方法
                                $remote_data[$remote_data_no]["acf_temporary_order_day"] = get_field("acf_temporary_order_day", $key);    // オーダー日
                                $remote_data[$remote_data_no]["acf_temporary_payment_end"] = get_field("acf_temporary_payment_end", $key);    // 決済完了日
                                $remote_data[$remote_data_no]["acf_temporary_item_name"] = get_field("acf_temporary_item_name", $key);    // アイテム名
                                $remote_data[$remote_data_no]["acf_temporary_product_number"] = get_field("acf_temporary_product_number", $key);    // 品番
                                $remote_data[$remote_data_no]["acf_temporary_subtotal"] = get_field("acf_temporary_subtotal", $key);    // 小計
                                $remote_data[$remote_data_no]["acf_temporary_name"] = get_field("acf_temporary_last_name", $key) . " " .get_field("acf_temporary_first_name", $key);    // 名前
                                if($is_candidate){

                                    $remote_data[$remote_data_no]["is_candidate"] = "〇";    // 候補
                                }else{

                                    $remote_data[$remote_data_no]["is_candidate"] = "";    // 候補
                                }
                                $remote_data[$remote_data_no]["acf_temporary_address"] = get_field("acf_temporary_post_number", $key)."<br>".get_field("acf_temporary_address_1", $key).get_field("acf_temporary_address_2", $key);    // 住所
                                $remote_data[$remote_data_no]["acf_temporary_tel"] = get_field("acf_temporary_tel", $key);    // 電話番号
                                $remote_data[$remote_data_no]["acf_temporary_mail"] = get_field("acf_temporary_mail", $key);    // メールアドレス
                                $remote_data_no++;

                            }

                            dispUserRemoteTabale($remote_data);
                        ?>

                    <tbody></tbody>

                </table>

                </div>




                <?php if( isset($_POST["all_delete"]) || isset($_POST["all_registration"])){ //一括時のなので削除の範囲を広げる?>
                    </form>
                <?php } ?>

        <?php } ?>






    <?php } else if(isset($_POST["csvCheck"])){ ?>


        <form action="<?php echo getURLSetSlag("admin-temporary-registration-list"); ?>" method="post" enctype="multipart/form-data" style="text-align: center;">  
            <button type="submit"  class="admin-temporary-registration-return-btn">一覧に戻る</button>
        </form>


    <?php } ?>

<?php
// PHPでファイルアップロードの処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] == 0) {

    //ERROR配列
    $err_data_array = array();
    
    $csvFile = $_FILES['csvFile']['tmp_name'];

   // var_dump($_FILES);

    if (($handle = fopen($csvFile, "r")) !== FALSE) {
        $csvData = [];


         // 最初の行でエンコーディングを自動検出
        $firstLine = fgets($handle);
        $encoding = mb_detect_encoding($firstLine, "SJIS-win,UTF-8,EUC-JP,JIS");

          // ファイルポインタを最初に戻す
        rewind($handle);

        $csv_count = 0;

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // エンコーディングをUTF-8に変換
            $data = array_map(function($value) use ($encoding) {
                // 文字列のエンコードを変換
                $value = mb_convert_encoding($value, "UTF-8", $encoding);
                // 制御文字や特殊文字を削除
                return preg_replace('/[\x00-\x1F\x7F]/u', '', $value);
            }, $data);


            //オーダー日があるなら比べる
            if($_POST["order_date"] != "" && $data[3] != "" && $csv_count > 0)
            {
                $d1 = new DateTime($_POST["order_date"]);
                $d2 = new DateTime($data[3]);

               // echo $d1 ." " .$d2 ."<br>";

                if($d1 >= $d2)
                {
                    continue;
                }
            }

            //オーダー日があるなら比べる
            if($_POST["pay_date"] != "" && $data[4] != "" && $csv_count > 0)
            {
                $d1 = new DateTime($_POST["pay_date"]);
                $d2 = new DateTime($data[4]);

                 //echo $d1 ." " .$d2 ."<br>";

                if($d1 >= $d2)
                {
                    continue;
                }
            }

            $csv_count++;

            //データがちゃんと入っているものだけ
            if(count($data) >= 55)
            {
                $csvData[] = $data;
            }
            else{
                $err_data_array[ $data[0] ] = $data;
            }
        }
        fclose($handle);

        //ファイルをuploadする
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // アップロードされたファイルが存在するか確認
            if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['csvFile']['tmp_name'];
                $fileName = $_FILES['csvFile']['name'];
                $fileSize = $_FILES['csvFile']['size'];
                $fileType = $_FILES['csvFile']['type'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                // CSVファイルのみを許可
                $allowedfileExtensions = array('csv');
                if (in_array($fileExtension, $allowedfileExtensions)) {
                    // 保存先ディレクトリ
                    $uploadFileDir = dirname(__FILE__) .'/../../fileupload/';
                    $dest_path = $uploadFileDir . $fileName;

                    // ファイルを保存
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        //echo "ファイルが正常にアップロードされました: $dest_path";
                    } else {
                        echo "ファイルのアップロードに失敗しました。";
                    }
                } else {
                    echo "無効なファイル形式です。CSVファイルのみをアップロードしてください。";
                }
            } else {
                echo "ファイルのアップロードに問題がありました。";
            }
        } else {
            echo "不正なリクエストです。";
        }




        if( isset($_POST["csvCheck"]))
        {
?>

            <div class="admin-temporary-registration-check-area">

                <?php if(count($err_data_array) >= 1){?>

                    <div class="admin-temporary-registration-check-err-text">

                         <div class="admin-temporary-registration-check-err-mes">
                            下記のオーダー番号のお客様の入力ができません。<br>
                            お客様の新規で入力するか、そのまま続行してCSVを登録するかを選択してください。
                        </div>

                        <div class="admin-temporary-registration-check-err-num">
                            【登録不可】<br><br>

                            <?php 
                                 foreach ($err_data_array as $key => $value) {
                            
                                    echo $value[0] ."　,　";
                                 }
                            ?>
                        </div>

                    </div>

                <?php }?>


               


            <?php 
                 //保存データーに直す
                $up_date_array =  $TemporaryRegistration->ChengeCSVData( $csvData );
            
            ?>


                <?php if(count($up_date_array) >= 1){?>

                    <div class="admin-temporary-registration-check-text">この情報を登録しますか？<br>現在登録されているものは情報が上書きされます。<br>すでに削除されたオーダー番号のものは登録されません</div>


                    <div class="">

                        <div class="">
                            <?php if( isset( $_POST["order_date"] ) ){?>
                                オーダー日 <?php echo $_POST["order_date"];?> 以降
                            <?php } ?>
                        </div>

                        <div class="">
                            <?php if( isset( $_POST["pay_date"] ) ){?>
                                決済完了日 <?php echo $_POST["pay_date"];?> 以降
                            <?php } ?>
                        </div>

                    </div>

                    <form action="<?php echo getURLSetSlag("admin-temporary-registration-list"); ?>" method="post" enctype="multipart/form-data" style="text-align: center;"  onSubmit="return upload_check()" >
                    
                         <input type="hidden" name="order_date" value="<?php echo $_POST["order_date"];?>">
                         <input type="hidden" name="pay_date" value="<?php echo $_POST["pay_date"];?>">
                         <input type="hidden" name="csvFile" value="<?php echo $_FILES['csvFile']['name'];?>">
                         <button type="submit"  class="admin-temporary-registration-file-upload-submit">アップロードする</button>
                    </form>

                    <div class="admin-temporary-registration-check-table">


                        <table id="userTable" class="user-disp-table table table-bordered">
                            <thead>
                                <tr>
                                    <th style="">オーダー番号</th>
                                    
                                    <th>ステータス</th>
                                    <th>支払方法</th>
                                    <th>オーダー日</th>
                                    <th>決済完了日</th>
                                    <th>アイテム名</th>
                                    <th>品番</th>
                                    <th>小計</th>
                                    <th>名前</th>
                                    <th>住所</th>
                                    <th>電話番号</th>
                                    <th style="">メールアドレス</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($up_date_array as $key => $value) {?>
                                    <tr>
                                        <td style=""><?php echo  $value["acf_temporary_order_num"]; ?></td>
                                        
                                        <td><?php echo  $value["acf_temporary_status"]; ?></td>
                                        <td><?php echo  $value["acf_temporary_payment"]; ?></td>
                                        <td><?php echo  $value["acf_temporary_order_day"]; ?></td>
                                        <td><?php echo  $value["acf_temporary_payment_end"]; ?></td>
                                        <td><?php echo  $value["acf_temporary_item_name"]; ?></td>
                                        <td><?php echo  $value["acf_temporary_product_number"]; ?></td>
                                        <td><?php echo  $value["acf_temporary_subtotal"]; ?></td>
                                        <td><?php echo  $value["acf_temporary_last_name"] . " " .$value["acf_temporary_first_name"]; ?></td>
                                        <td>
                                            <?php echo  $value["acf_temporary_post_number"]; ?><br>
                                            <?php echo  $value["acf_temporary_address_1"]; ?><?php echo  $value["acf_temporary_address_1"]; ?><?php echo  $value["acf_temporary_address_2"]; ?>
                                        </td>
                                        <td><?php echo  $value["acf_temporary_tel"]; ?></td>
                                        <td><?php echo  $value["acf_temporary_mail"]; ?></td>
                                     </tr>
                                <?php } ?>

                            </tbody>

                        </table>

                     </div>


                <?php } ?>
            </div>
           
<?php

    

        }
    }
}

?>



</div>




    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 

    


<script type="text/javascript"> 
<!-- 

function upload_check(){

	if(window.confirm('登録してもよろしいですか？')){ // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

	}
	else{ // 「キャンセル」時の処理

		window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止

	}

}



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
