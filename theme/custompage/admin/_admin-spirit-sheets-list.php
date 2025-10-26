<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo get_template_directory_uri()."/custompage/admin/a-gate-script.js"?>"></script>

<?php 



    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");

    // 流入データ
    $spirit_sheet_data = new spiritSheetClass(); //管理データ

    $spiritType = new SpiritTypeClass(); //管理データ
    $spiritTypeArray = $spiritType->getSpiritType();
    $spiritStatusArray = $spirit_sheet_data->getSpiritAdminStatusID();

   // var_dump($spiritTypeArray);

   //自分の情報
   $user = wp_get_current_user();

    //全員のユーザー情報作成
    $users = get_users();


    $user_split_data = array();

    foreach($users as $user) {
        $uid = $user->ID; 


        if(get_user_meta($uid,'spirit_data',true) != "")
        {
            
            $spiritTypeJsonArray = json_decode(get_user_meta($uid,'spirit_data',true));
        
        
            foreach ($spiritTypeJsonArray as $key => $value) {


                $user_split_data[$value] = get_field('acf_purespirit_requested_date',$value);;

            }
        }
    }

    arsort($user_split_data);

   // echo "<br>";
  //  var_dump($user_split_data);



  if(isset($_POST["search_save"]))
  {

    $search_array = array();


    foreach($_POST as  $key => $value) {


        $search_array[$key] = $value;

    }

    $json_data = json_encode($search_array, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);


    update_user_meta($user->ID,"sprit_sheet_search",$json_data);

    //var_dump($json_data);
  }

  //検索リセット
  if(isset($_POST["search_reset"]))
  {
      update_user_meta($user->ID,"sprit_sheet_search","");
  }


  //検索の保存情報を取得する

  $search_json_data = get_user_meta( $user->ID,'sprit_sheet_search',true);

  $search_save_data = array();

  if($search_json_data != "")
  {
      $search_save_data = json_decode($search_json_data, true);

      //var_dump($search_save_data);
  }
    
?>





<div class="admin-profile-edit-area" style="max-width: 1700px;">


    <div class="admin-profile-edit-title-box">
        <div class="admin-exorcism-menu-title" style="text-align: center;">浄霊シート一覧</div>
    </div>



    <?php  if($user_split_data != NULL){?>

        <div class="admin-spirit-delete-alert-text">シートの削除は対象者の詳細にて操作してください</div>


        <div class="admin-spritsheet-search-area">

            <form action="<?php echo getURLSetSlag("admin-spirit-sheets-list"); ?>" method="post">


                <div class="admin-spritsheet-search-flex" >
                    <div class="admin-spritsheet-search-type" >

                        <div class="admin-spritsheet-search-ravel">
                            依頼内容
                        </div>

                        <select class="table-squeeze" name="search_sprit_type" id="">
                            <option value="">すべて表示</option>
                            <?php 
                                foreach ($spiritTypeArray as $key => $value) {
                                    foreach ($value as $key_num => $value_num) {
                            ?>
                            <option value="<?php echo $value_num["ID"]; ?>" <?php if( isset($search_save_data['search_sprit_type']) && $search_save_data['search_sprit_type'] == $value_num["ID"]) echo "selected"; ?>><?php echo $value_num["title"];?></option>
                            <?php 
                                    }
                                }
                            ?>
                        </select>
                    </div>

                    <div class="admin-spritsheet-search-type" >

                        <div class="admin-spritsheet-search-ravel">
                            ステータス
                        </div>

                        <select class="table-squeeze" name="search_sprit_status" id="">
                            <option value="0">すべて表示</option>
                            <option value="" <?php if( isset($search_save_data['search_sprit_status']) && $search_save_data['search_sprit_status'] == "") echo "selected"; ?>>未確認</option>
                            <?php 
                                foreach ($spiritStatusArray as $key => $value) {
                               
                            ?>
                            <option value="<?php echo $key; ?>" <?php if( isset($search_save_data['search_sprit_status']) && $search_save_data['search_sprit_status'] == $key) echo "selected"; ?>><?php echo $value["title"];?></option>
                            <?php 
                                }
                            ?>
                        </select>

                    </div>

                </div>

                <div class="admin-spritsheet-search-flex" >

                    <div class="admin-spritsheet-search-date" >

                        <div class="admin-spritsheet-search-ravel">
                            依頼日
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

                        <div class="admin-spritsheet-search-ravel">
                            依頼確定日
                        </div>


                        <div class="admin-spritsheet-search-date-start">
                            <input type="date" id="" name="search_sprit_requestconfirmed_start" value="<?php if( isset($search_save_data['search_sprit_requestconfirmed_start'])){  echo $search_save_data['search_sprit_requestconfirmed_start']; }?>">
                        </div>

                         <div class="admin-spritsheet-search-ravel" style="margin-left: 5px;margin-right: 5px;">～</div>

                        <div class="admin-spritsheet-search-date-end">
                            <input type="date" id="" name="search_sprit_requestconfirmed_end" value="<?php if( isset($search_save_data['search_sprit_requestconfirmed_end'])){  echo $search_save_data['search_sprit_requestconfirmed_end']; }?>">
                        </div>

                    </div>

                    <div class="admin-spritsheet-search-date" >

                        <div class="admin-spritsheet-search-ravel">
                            実行日
                        </div>


                        <div class="admin-spritsheet-search-date-start">
                            <input type="date" id="" name="search_sprit_execution_start" value="<?php if( isset($search_save_data['search_sprit_execution_start'])){  echo $search_save_data['search_sprit_execution_start']; }?>">
                        </div>

                         <div class="admin-spritsheet-search-ravel" style="margin-left: 5px;margin-right: 5px;">～</div>

                        <div class="admin-spritsheet-search-date-end">
                            <input type="date" id="" name="search_sprit_execution_end" value="<?php if( isset($search_save_data['search_sprit_execution_end'])){  echo $search_save_data['search_sprit_execution_end']; }?>">
                        </div>

                    </div>

                     <input type="hidden"  name="search_save">

                    <div class="admin-spritsheet-search-submit-button" >
                        <input type="submit" value="絞り込む" class="admin-spritsheet-search-submit">
                    </div>


                </div>

               
            </form>
        </div>


        <div class="admin-spritsheet-search-area">

            <form action="<?php echo getURLSetSlag("admin-spirit-sheets-list"); ?>" method="post">

                 <input type="hidden"  name="search_reset">

                    <div class="admin-spritsheet-search-submit-button" >
                        <input type="submit" value="検索をリセット" class="admin-spritsheet-search-reset">
                    </div>

            </form>
        </div>

        <table id="" class="user-disp-table table table-bordered" style="margin-top: 50px;">
            <!-- <table id="userTable" class="user-disp-table table table-bordered"> -->
            <thead>
                <tr>
                    <th style="width:200px">依頼内容</th>
                    <th>詳細</th>
                    <th>依頼者</th>
                    <th>申込者</th>
                    <th>紹介者</th>
                    <th style="width: 100px;">依頼日</th>
                    <th style="width: 100px;">依頼確定日</th>
                    <th style="width: 100px;">決済日</th>
                    <th style="width: 100px;">実行日</th>
                    <th>依頼内容追記</th>
                    <th style="min-width: 120px;">ステータス</th>
                    <th style="font-size: 10px;width: 70px;">A-GETE<br>確認</th>
                </tr>
            </thead>
            <tbody>
                <?php  
            
                    foreach ($user_split_data as $key => $value) {  
                
                        $sprit_type = get_field('acf_acf_purespirit_type',$value);
                        $is_delete = get_field("is_delete", $value);

                        if(get_field('acf_pure_spirit_title',get_field('acf_acf_purespirit_type',$key)) == "")continue;

                        if($is_delete) continue;    //削除されたシートは表示しない


                        //絞り込みを表示

                        //依頼内容

                        $search_sprit_type = get_field('acf_acf_purespirit_type',$key);

                        if( isset($search_save_data['search_sprit_type']) && $search_save_data['search_sprit_type'] != "")
                        {

                            if($search_save_data['search_sprit_type'] != $search_sprit_type)
                            {
                                continue;
                            }
                        }

                        //status
                        $status = get_field('acf_purespirit_status',$key);


                        if( isset($search_save_data['search_sprit_status']) && $search_save_data['search_sprit_status'] != 0)
                        {
                            if($search_save_data['search_sprit_status'] != $status)
                            {
                                continue;
                            }
                        }

                        //依頼日
                        $requested_date = get_field('acf_purespirit_requested_date',$key);


                        //開始日
                        if( isset($search_save_data['search_sprit_request_start']) && $search_save_data['search_sprit_request_start'] != "")
                        {

                            if($requested_date == "")
                            {
                                continue;
                            }

                            $d1 = new DateTime($requested_date);
                            $d2 = new DateTime($search_save_data['search_sprit_request_start']);

                            if($d1 < $d2)
                            {
                                continue;
                            }
                        }

                         //終了日
                        if( isset($search_save_data['search_sprit_request_end']) && $search_save_data['search_sprit_request_end'] != "")
                        {

                            if($requested_date == "")
                            {
                                continue;
                            }

                            $d1 = new DateTime($requested_date);
                            $d2 = new DateTime($search_save_data['search_sprit_request_end']);

                            if($d1 > $d2)
                            {
                                continue;
                            }
                        }


                        //依頼確定日
                        $confirmation_date = get_field('acf_purespirit_request_confirmation_date',$key);


                        //開始日
                        if( isset($search_save_data['search_sprit_requestconfirmed_start']) && $search_save_data['search_sprit_requestconfirmed_start'] != "")
                        {

                            if($confirmation_date == "")
                            {
                                continue;
                            }

                            $d1 = new DateTime($confirmation_date);
                            $d2 = new DateTime($search_save_data['search_sprit_requestconfirmed_start']);

                            if($d1 < $d2)
                            {
                                continue;
                            }
                        }

                         //終了日
                        if( isset($search_save_data['search_sprit_requestconfirmed_end']) && $search_save_data['search_sprit_requestconfirmed_end'] != "")
                        {

                            if($confirmation_date == "")
                            {
                                continue;
                            }

                            $d1 = new DateTime($confirmation_date);
                            $d2 = new DateTime($search_save_data['search_sprit_requestconfirmed_end']);

                            if($d1 > $d2)
                            {
                                continue;
                            }
                        }

                        //決済日
                        $payment_date = get_field('acf_purespirit_payment_date',$key);


                        //開始日
                        if( isset($search_save_data['search_sprit_execution_start']) && $search_save_data['search_sprit_execution_start'] != "")
                        {

                            if($payment_date == "")
                            {
                                continue;
                            }

                            $d1 = new DateTime($payment_date);
                            $d2 = new DateTime($search_save_data['search_sprit_execution_start']);

                            if($d1 < $d2)
                            {
                                continue;
                            }
                        }

                         //終了日
                        if( isset($search_save_data['search_sprit_execution_end']) && $search_save_data['search_sprit_execution_end'] != "")
                        {

                            if($payment_date == "")
                            {
                                continue;
                            }

                            $d1 = new DateTime($payment_date);
                            $d2 = new DateTime($search_save_data['search_sprit_execution_end']);

                            if($d1 > $d2)
                            {
                                continue;
                            }
                        }
            
                ?>
                         <tr>
                        <td style="text-align: center; vertical-align: middle;font-weight: 800;"><?php echo get_field('acf_pure_spirit_title',$search_sprit_type);?></td>
                        <td>
                            <div class="" style="display: flex;">
                                 <div class="" style="display: flex;">
                                    <button class="edit-mark" style="width: 30px;font-size: 15px;"><a href="<?php echo getURLSetSlag("admin-spirit-detail"); ?>?user_id=<?php echo get_field('acf_purespirit_id',$key);?>&sheet_name=<?php echo $key;?>" target="_blank">編</a></button>
                                </div>
                          
                            </div>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <?php 
                                 $user_last_name_kana = get_user_meta(get_field('acf_purespirit_id',$key),'last_name',true);
                                 $user_first_name_kana = get_user_meta(get_field('acf_purespirit_id',$key),'first_name',true);
                            ?>
                                <a href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo get_field('acf_purespirit_id',$key);?>"  target="_blank"><?php echo $user_last_name_kana . " " . $user_first_name_kana; ?></a>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <?php 

                                if(get_field('acf_applicant',$key) != "")
                                {
                                    $user_last_name_kana = get_user_meta(get_field('acf_applicant',$key),'last_name',true);
                                    $user_first_name_kana = get_user_meta(get_field('acf_applicant',$key),'first_name',true);
                            ?>
                                
                                    <a href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo get_field('acf_applicant',$key);?>"  target="_blank"><?php echo $user_last_name_kana . " " . $user_first_name_kana; ?></a>
                            <?php } ?>
                             
                           
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <?php 

                                
                                $user_introduction_id = get_user_meta(get_field('acf_purespirit_id',$key),'input_introduction_id',true);

                                if($user_introduction_id != "")
                                {

                                    $user_last_name_kana = get_user_meta($user_introduction_id,'last_name',true);
                                    $user_first_name_kana = get_user_meta($user_introduction_id,'first_name',true);

                            ?>
                                    
                                    <a href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $user_introduction_id;?>"  target="_blank"><?php echo $user_last_name_kana . " " . $user_first_name_kana; ?></a>
                            <?php 
                                }
                                else{

                                     $user_introduction_name = get_user_meta(get_field('acf_purespirit_id',$key),'input_introduction_name',true);

                                     if($user_introduction_name != "")
                                     {
                                        echo $user_introduction_name;
                                     }

                                }
                            ?>
                        </td>
                        <td style="text-align: center; vertical-align: middle;"><?php echo get_field('acf_purespirit_requested_date',$key);?></td>
                        <td style="text-align: center; vertical-align: middle;"><?php echo get_field('acf_purespirit_request_confirmation_date',$key);?></td>
                        <td style="text-align: center; vertical-align: middle;"><?php echo get_field('acf_purespirit_payment_date',$key);?></td>
                        <td style="text-align: center; vertical-align: middle;"><?php echo get_field('acf_purespirit_execution_date',$key);?></td>
                        <td ><?php echo get_field('acf_purespirit_add_text',$key);?></td>
                        <td style="text-align: center; vertical-align: middle;">
                        <?php 
                    
                            

                            if($status == "")
                            {
                                echo "未確認";
                            }
                            else
                            {
                               
                                echo $spiritStatusArray[$status]["title"];
                            }
                    
                        ?>
                    
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <?php 
                        
                                if(get_field('acf_agate_hands_on',$key) != "")
                                {
                                    echo "〇";
                                }
                        
                            ?>
                        </td>
                    </tr>
                

                <?php } ?>


            </tbody>

        </table>

    <?php }?>

</div>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 

   