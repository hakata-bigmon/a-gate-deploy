
<?php

require_once ("a-gate-functions.php");

require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");

$spiritType = new SpiritTypeClass(); //管理データ
$spiritTypeArray = $spiritType->getSpiritType();

$spiritSheet = new SpiritSheetClass(); //管理データ
$spiritStatusArray = $spiritSheet->getGeneralPurposeData("cpt_inflow");


// ユーザー情報
$check_user_id = $_GET['user_id'];
$user_last_name = get_user_meta($check_user_id,'last_name',true);
$user_first_name = get_user_meta($check_user_id,'first_name',true);

// 更新者情報
$user_edit_day = get_user_meta($check_user_id,'user_edit_day',true);    //jsonデータ取得
if($user_edit_day != ""){

    $decoded_data = json_decode($user_edit_day, true);  //jsonデータ戻し
    $decoded_data_index = array_keys($decoded_data);
    $max_index = max($decoded_data_index);

    $user_edit_changer = get_updata_user_info($decoded_data[$max_index]);
}



//シートの追加
if(isset($_POST['add_sheet'])){
    
    $add_id = $spiritSheet->newSpiritSheetUnixtime($_GET["user_id"],$_POST["add_sheet"] ,$_POST["add_sheet_unix"]);
   
    
    if($add_id != "")
    {
        $spiritSheet->newSpiritSheetUserAdd( $_GET["user_id"] ,$add_id);
        /*
        //JSON番号を保存
        $user_json_data = get_user_meta($_GET["user_id"],'spirit_data',true);    //jsonデータ取得
        
        $decoded_data = "";
        
        if($user_json_data == "")
        {
            $decoded_data = array();
        }
        else{
            $decoded_data = json_decode($user_json_data, true);  //jsonデータ戻し
        }

        array_push($decoded_data,$add_id);

        $json_data = json_encode($decoded_data, JSON_UNESCAPED_UNICODE);

        update_user_meta($_GET["user_id"],"spirit_data",$json_data);
        */
    }



    $add_sheet = array();   //追加情報

}


// シート削除
if(isset($_POST['sheet_delete'])){

    // 完全削除の処理はここにお願いします
    $spiritSheet->deleteSpiritSheet( $_POST['sheet_name'] , $check_user_id );
}
// シート非表示
if(isset($_POST['sheet_hidden'])){
    update_field("is_delete", true, $_POST['sheet_name']);

}

DeleteModalDisp();  //削除モーダル
?>


<div class="admin-profile-edit-area" style="max-width: 1200px;">

    <div class="admin-profile-edit-title-box">

        <div class="admin-exorcism-menu-title"><?php echo $user_last_name . " " . $user_first_name;?> プロフィール <?php if(isset($_GET["all_change_list"])) echo "変更履歴"; ?>詳細</div>
    </div>
    

    <?php if(!isset($_GET["all_change_list"])){?>

    <?php 

        require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");

        // 流入データ
        $spirit_sheet_data = new spiritSheetClass(); //管理データ
        $spiritStatusArray = $spirit_sheet_data->getGeneralPurposeData("cpt_inflow");

        // 浄霊データテーブル項目
        $user_split_data = json_decode(get_user_meta($check_user_id,'spirit_data',true));
        // makeUserSplitData($check_user_id);

        //ユーザー情報作成
        $users = get_userdata( $check_user_id );
        $user_unique_code = get_user_meta($check_user_id,'user_unique_id',true);
        $user_last_name_kana = get_user_meta($check_user_id,'last_name_kana',true);
        $user_first_name_kana = get_user_meta($check_user_id,'first_name_kana',true);
        $user_sex = get_user_meta($check_user_id,'sex',true);
        $user_tel1 = get_user_meta($check_user_id,'billing_phone',true);
        $user_tel2 = get_user_meta($check_user_id,'billing_phone2',true);
        $user_tel3 = get_user_meta($check_user_id,'billing_phone3',true);
        $user_none_phone = get_user_meta($check_user_id,'check_phone_none',true);
        $user_zip = get_user_meta($check_user_id,'billing_postcode',true);
        $user_address = get_user_meta($check_user_id,'billing_city',true);
        $user_address2 = get_user_meta($check_user_id,'billing_address_1',true);
        $user_line = get_user_meta($check_user_id,'line_id',true);
        $user_born_year = get_user_meta($check_user_id,'born_year',true);
        $user_born_month = get_user_meta($check_user_id,'born_month',true);
        $user_born_day = get_user_meta($check_user_id,'born_day',true);
        $user_report_born_year = get_user_meta($check_user_id,'report_born_year',true);
        $user_report_born_month = get_user_meta($check_user_id,'report_born_month',true);
        $user_report_born_day = get_user_meta($check_user_id,'report_born_day',true);
        $user_inflow = get_user_meta($check_user_id,'input_inflow',true);
        $user_inflow_remarks = get_user_meta($check_user_id,'inflow_remarks',true);
        $user_introduction_id = get_user_meta($check_user_id,'input_introduction_id',true);
        $user_introduction_name = get_user_meta($check_user_id,'input_introduction_name',true);
        $user_edit_day = get_user_meta($check_user_id,'user_edit_day',true);
        $user_edit_changer = get_user_meta($check_user_id,'user_edit_changer',true);
        $user_remarks = get_user_meta($check_user_id,'user_remarks',true);
        
    ?>

    <form class="user-input-area" action="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $_GET['user_id'];?>" method="post" name="post_new_user_input" id="post_new_user_input" onsubmit="return input_check()">

        
        <div class="admin-btn-area btn-flex" style="">
            <?php if(!judgeUserRole()){?>
            <button type="submit" id="form-submit" class="form-btn"  >プロフィール編集</button>
            <button type="submit" id="form-back" class="form-btn gray"><a href="<?php echo getURLSetSlag("admin-member-list"); ?>">顧客一覧に戻る</a></button>
            <?php } ?>
        </div>

        <input type="hidden" name="change_user" value="change_user" id="">

        <div class="user-table-flex">
            <div class="user-table-item">ID</div>
            <div class="user-table-data">
                <!-- <input class="data-check" type="text" name="user_id"  value="<?php echo $user_unique_code ?>" readonly> -->
                <?php echo $user_unique_code ?>
            </div>
            <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="navigator.clipboard.writeText('<?php echo $user_unique_code;?>')">コピー</button>
        </div>
        <div class="user-table-flex">
            <div class="user-table-item">名前</div>
            <div class="user-table-data">
                <?php echo $user_last_name . " ". $user_first_name ;?>
                
            </div>
            <button type="button" style="margin-left: auto;margin-right: 10px;"   onclick="navigator.clipboard.writeText('<?php echo $user_last_name . $user_first_name?>')">コピー</button>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">ヨミカタ</div>
            <div class="user-table-data">
                <!-- <input class="data-check" type="text" name="input_last_name_kana" value="<?php echo $user_last_name_kana?>" readonly>
                <input class="data-check" type="text" name="input_first_name_kana" value="<?php echo $user_first_name_kana?>" readonly> -->
                <?php echo $user_last_name_kana.$user_first_name_kana ?>
            </div>
            <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="navigator.clipboard.writeText('<?php echo $user_last_name_kana . $user_first_name_kana?>')">コピー</button>
        </div>
        
        <div class="user-table-flex">
            <div class="user-table-item">*性別</div>

            <?php 
            
                $disp_user_sex = "不明";
            
                if($user_sex == "M"){
                    $disp_user_sex = "男性";
                }
                else if($user_sex == "W"){
                    $disp_user_sex = "女性";
                }
                echo $disp_user_sex;
            ?>

            <!-- <input class="data-check" type="text" name="input_user_sex" id="" value="<?php echo $disp_user_sex;?>" readonly> -->
        </div>
        
        <?php if(!judgeUserRole()){?>
        <div class="user-table-flex">
            <div class="user-table-item">関連</div>
            <?php 
                // 関連ID
                $connect_id = get_user_meta($check_user_id,'connect_group',true);
                $post_title = getConnectionName($connect_id );

                // グループ
                $user_group_data = json_decode(get_user_meta($check_user_id,'group_data',true));
            ?>
            <input class="data-check" type="text" name="" id="" value="<?php echo $post_title;// 関連?>" readonly>
        </div>
        <div class="user-table-flex">
            <div class="user-table-item">グループ</div>


            <?php
                if($user_group_data != null && is_array($user_group_data)){

                    foreach ($user_group_data as $key => $value) {
                        if($value != "") echo $group_name[$value]."<br>";
                    }
                }
            ?>
        </div>
        <?php } ?>
        
        <div class="user-table-flex">
            <div class="user-table-item">*連絡先</div>
            <div class="user-table-data">
                <!-- <input class="data-check user-tel-input" type="text" name="input_tel_1" style="text-align: left;" id="" value="<?php echo $user_tel1?>" readonly>-
                <input class="data-check user-tel-input" type="text" name="input_tel_2" style="text-align: left;" id="" value="<?php echo $user_tel2?>" readonly>-
                <input class="data-check user-tel-input" type="text" name="input_tel_3" style="text-align: left;" id="" value="<?php echo $user_tel3?>" readonly> -->
                <?php echo $user_tel1."-". $user_tel2."-". $user_tel3; ?>
            </div>
            
            <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="navigator.clipboard.writeText('<?php echo $user_tel1 . $user_tel2 . $user_tel3?>')">コピー</button>
        </div>
        
        <div class="user-table-flex">
            <div class="user-table-item">電話番号なし</div>
            <!-- <input type="checkbox" name="check_phone_none" id="" <?php if($user_none_phone) echo "checked"?> onclick='return false;' readonly> -->
            <input type="checkbox" name="" id="" <?php if($user_none_phone) echo "checked"?> onclick='return false;' readonly>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">*郵便番号</div>
            <!-- <input class="data-check" type="text" name="input_post_no" id="" value="<?php echo $user_zip?>" readonly> -->
            <?php echo $user_zip?>
            <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="navigator.clipboard.writeText('<?php echo $user_zip?>')">コピー</button>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">*住所</div>
            <!-- <input class="data-check" type="text" name="input_address1" id="" value="<?php echo $user_address?>" readonly> -->
            <?php echo $user_address?>
            <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="navigator.clipboard.writeText('<?php echo $user_address?>')">コピー</button>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">マンション名など</div>
            <!-- <input class="data-check" type="text" name="input_address2" id="" value="<?php echo $user_address2?>" readonly> -->
            <?php echo $user_address2?>
            <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="navigator.clipboard.writeText('<?php echo $user_address2?>')">コピー</button>
        </div>

        <?php if(!judgeUserRole()){?>
            <?php 
                // ユニックスタイムで登録されていれば仮アドレス
                if(!checkUnix($users->user_email)){
            ?>
                <div class="user-table-flex" >
                    <div class="user-table-item">*メールアドレス</div>

                        <?php echo getParentAddress($users->user_email)?>
                        <button type="button" style="margin-left: auto;margin-right: 10px;"  onclick="navigator.clipboard.writeText('<?php echo getParentAddress($users->user_email)?>')">コピー</button>
                    
                </div>
            <?php } ?>

        <div class="user-table-flex">
            <div class="user-table-item">LINE ID</div>
            <!-- <input class="data-check" type="text" name="input_user_line_id" id="" value="<?php echo $user_line?>" readonly> -->
            <?php echo $user_line?>
            <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="navigator.clipboard.writeText('<?php echo $user_line?>')">コピー</button>
        </div>
        <?php } ?>

        <div class="user-table-flex">
            <div class="user-table-item">生年月日</div>
            
            <?php if($user_born_year != ""){?>
                <!-- <input class="data-check user-born-input" type="text" name="input_user_born_year" id="" value="<?php echo  $user_born_year?>" readonly>年
                <input class="data-check user-born-input" type="text" name="input_user_born_month" id="" value="<?php echo $user_born_month?>" readonly>月
                <input class="data-check user-born-input" type="text" name="input_user_born_day" id="" value="<?php echo   $user_born_day?>" readonly>日 -->
                <?php echo $user_born_year."年".$user_born_month."月".$user_born_day."日";?>
                <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="navigator.clipboard.writeText('<?php echo $user_born_year.'年'.$user_born_month.'月'.$user_born_day.'日'?>')">コピー</button>
            <?php } ?>
            
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">届け出日</div>

            <?php if($user_report_born_year != ""){?>
                <!-- <input class="data-check user-born-input" type="text" name="input_user_report_year" id="" value="<?php echo  $user_report_born_year?>" readonly>年
                <input class="data-check user-born-input" type="text" name="input_user_report_month" id="" value="<?php echo $user_report_born_month?>" readonly>月
                <input class="data-check user-born-input" type="text" name="input_user_report_day" id="" value="<?php echo   $user_report_born_day?>" readonly>日 -->
                <?php echo $user_report_born_year."年".$user_report_born_month."月".$user_report_born_day."日";?>
                <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="navigator.clipboard.writeText('<?php echo $user_report_born_year.'年'.$user_report_born_month.'月'.$user_report_born_day.'日'?>')">コピー</button>
            <?php } ?>
        </div>

        
        <?php if(!judgeUserRole()){?>
        <div class="user-table-flex">
            <div class="user-table-item" class="user-table-item">流入元</div>
            
            <?php 
            
                $disp_user_inflow = "";
            
                if( isset($spiritStatusArray[$user_inflow]) ){

                    $disp_user_inflow = $spiritStatusArray[$user_inflow]["title"];

                }
            
            ?>

            <!-- <input class="data-check" type="text" name="input_inflow" id="" value="<?php echo $disp_user_inflow?>" readonly> -->
            <?php echo $disp_user_inflow?>

        </div>

        <div class="user-table-flex">
            <div class="user-table-item">流入元追記</div>
            <?php echo $user_inflow_remarks?>
        </div>
        <?php } ?>

        <div class="user-table-flex">
            <div class="user-table-item">紹介者</div>
            <?php //echo $user_introduction_id;?>
            <?php echo 'ID' . get_user_meta($user_introduction_id, 'user_unique_id', true) . '：';?>
            
            <?php echo get_user_meta($user_introduction_id,'last_name',true) ?> <?php echo get_user_meta($user_introduction_id,'first_name',true) ?>
        </div>

        
        <?php if(!judgeUserRole()){?>
        <div class="user-table-flex">
            <div class="user-table-item">登録日</div>
            <input class="data-check " name="" id="" value="<?php echo $users->user_registered?>" readonly></input>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">最終更新日</div>
            <?php
                if($user_edit_day != ""){
                    echo $decoded_data[$max_index]['edit_date'] . '  ' .$user_edit_changer;
                }
            ?>
        </div>
        <?php } ?>

        <div class="user-table-flex">
            <div class="user-table-item">特記事項</div>
            <textarea class="data-check user-input-remarks" name="" id="" readonly><?php echo $user_remarks?></textarea>
        </div>

    </form>


    <?php if(!judgeUserRole()){?>
    <div class="admin-spirit-subtitle-center-box">
        <div class="admin-spirit-menu-title">浄霊各種（本人）</div>
    </div>

    <?php /* 浄霊内容 */?>
    <form action="<?php echo getURLSetSlag("admin-sprit-add"); ?>?user_id=<?php echo $_GET['user_id'];?>" method="post">
        <button type="submit" id="form-submit" class="form-btn orange" style="margin-bottom:30px;margin-top: 100px;">浄霊・鑑定を追加</button>
    </form>

    <?php 
        if($user_split_data != NULL){
    ?>
        <div class="btn-flex">
           
            <form class="btn-flex" action="" method="get">
                <input type="hidden" name="user_id" value="<?php echo $check_user_id?>">
                <select class="table-squeeze" name="select_sprit_type" id="">
                    <option value="">すべて表示</option>
                    <?php 
                        foreach ($spiritTypeArray as $key => $value) {
                            foreach ($value as $key_num => $value_num) {
                    ?>
                    <option value="<?php echo $value_num["ID"]; ?>" <?php if(isset($_GET['select_sprit_type']) && $_GET['select_sprit_type'] == $value_num["ID"]) echo "selected"; ?>><?php echo $value_num["title"];?></option>
                    <?php 
                            }
                        }
                    ?>
                </select>
                <button class="yn-btn" style="margin-left: 10px;">絞り込む</button>
            </form>
            <form action="" method="get">
            <input type="hidden" name="user_id" value="<?php echo $check_user_id?>">
    
                <button class="yn-btn gray">絞り込み解除</button>
            </form>
        </div>

    
        <?php } ?>
    <?php } ?>

</div>

<div style="margin-left: 30px;margin-right: 30px;">
    <?php 
        if($user_split_data != NULL){
    ?>
        <table id="" class="user-disp-table table table-bordered">
            <thead>
                <tr>
                    <th style="width:200px">依頼内容</th>
                    <th>詳細</th>
                    <th>単価</th>
                    <th>申込者</th>
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
        <tbody>
            <?php 
                $total_money = 0;   //合計金額
                foreach ($user_split_data as $key => $value) { 
                    $sprit_type = get_field('acf_acf_purespirit_type',$value);
                    $is_delete = get_field("is_delete", $value);

                    if($is_delete) continue;    //削除されたシートは表示しない
                    if(isset($_GET['select_sprit_type']) && $sprit_type != $_GET['select_sprit_type']  && "" != $_GET['select_sprit_type']) continue;
            ?>
                <tr>
                    <td style="text-align: center; vertical-align: middle;font-weight: 800;"><?php echo get_field('acf_pure_spirit_title',get_field('acf_acf_purespirit_type',$value));?></td>
                    <td>
                        <div class="" style="display: flex;">
                            <button class="edit-mark" style="width: 30px;margin: 0;font-size: 15px;"><a href="<?php echo getURLSetSlag("admin-spirit-detail"); ?>?user_id=<?php echo $_GET['user_id'];?>&sheet_name=<?php echo $value;?>">編</a></button>

                            <form id="hidden_sheet_<?php echo $value;?>" action="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $_GET['user_id'];?>" method="post" onclick="dispBtn(<?php echo $value;?>)">
                                <input type="hidden" name="sheet_name" value="<?php echo $value;?>">
                                <input type="hidden" name="sheet_hidden" value="sheet_hidden">
                                <button type="button" class="edit-mark gray"  style="width: 30px;margin: 0;margin-left: 5px;font-size: 15px;">削</button>
                            </form>

                            <form id="delete_sheet_<?php echo $value;?>" action="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $_GET['user_id'];?>" method="post" onclick="dispBtn(<?php echo $value;?>)">
                                <input type="hidden" name="sheet_name" value="<?php echo $value;?>">
                                <input type="hidden" name="sheet_delete" value="is_delete">
                            </form>
                        </div>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <?php 
                            $single_money = get_field('acf_purespirit_price',$value);
                            if($single_money == "")$single_money = 0;   //0設定

                            $total_money += $single_money;
                            echo "&yen;" .number_format($single_money);
                        ?>
                    </td>

                    <td style="text-align: center; vertical-align: middle;">
                        <?php 
                            
                             $status = get_field('acf_applicant',$value);

                             if($status != "")
                             {
                                 if($status == $check_user_id)
                                 {
                                     echo "本人";
                                 }
                                 else{
                                       echo get_user_meta($check_user_id,'last_name',true) . " " . get_user_meta($check_user_id,'first_name',true);
                                 }
                             }
                        
                        ?>
                    </td>


                    <td style="text-align: center; vertical-align: middle;">
                        <?php 
                            $date = get_field('acf_purespirit_requested_date',$value);

                            if($date != ""){
                                echo date('Y年m月d日',strtotime($date));
                            }
                        ?>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <?php 
                            $date = get_field('acf_purespirit_request_confirmation_date',$value);

                            if($date != ""){
                                echo date('Y年m月d日',strtotime($date));
                            }
                        ?>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                         <?php 
                            $date = get_field('acf_purespirit_payment_date',$value);

                            if($date != ""){
                                echo date('Y年m月d日',strtotime($date));
                            }
                        ?>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                         <?php 
                            $date = get_field('acf_purespirit_execution_date',$value);

                            if($date != ""){
                                echo date('Y年m月d日',strtotime($date));
                            }
                        ?>
                    </td>
                    <td ><?php echo get_field('acf_purespirit_add_text',$value);?></td>
                    <td style="text-align: center; vertical-align: middle;">
                    <?php 
                    
                        $status = get_field('acf_purespirit_status',$value);

                        if($status == "")
                        {
                            echo "未確認";
                        }
                        else
                        {
                            $spiritStatusArray = $spirit_sheet_data->getSpiritAdminStatusID('cpt_spirit_status');
                            echo $spiritStatusArray[$status]["title"];
                        }
                    
                    ?>
                    
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <?php 
                        
                            if(get_field('acf_agate_hands_on',$value) != "")
                            {
                                echo "〇";
                            }
                        
                        ?>
                    </td>
                </tr>
                
                <?php } ?>
                <tr>
                    <td></td>
                    <td style="text-align: center; vertical-align: middle;">合計金額</td>
                    <td style="font-weight: 700;">&yen;<?php echo number_format($total_money);?></td>
                </tr>
                
            </tbody>
        </table>
    <?php } else {?>
        

    <?php } ?>


   
    <?php 

         $applicant_split_data = $spirit_sheet_data->getSpritApplicant( $check_user_id );


        if($applicant_split_data != NULL){
    ?>


        <div class="admin-spirit-subtitle-center-box">
            <div class="admin-spirit-menu-title">浄霊各種（申込者）</div>
        </div>

        <div class="admin-spirit-delete-alert-text">シートの削除は対象者の詳細にて操作してください</div>


        <table id="" class="user-disp-table table table-bordered">
            <thead>
                <tr>
                    <th style="width:200px">依頼内容</th>
                    <th>詳細</th>
                    <th>単価</th>
                    <th style="width: 100px;">対象者</th>
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
        <tbody>
            <?php 
                $total_money = 0;   //合計金額
                foreach ($applicant_split_data as $key => $value) { 
                    $sprit_type = get_field('acf_acf_purespirit_type',$key);
                    $is_delete = get_field("is_delete", $key);

                    if($is_delete) continue;    //削除されたシートは表示しない
                    if(isset($_GET['select_sprit_type']) && $sprit_type != $_GET['select_sprit_type']  && "" != $_GET['select_sprit_type']) continue;
            ?>
                <tr>
                    <td style="text-align: center; vertical-align: middle;font-weight: 800;"><?php echo get_field('acf_pure_spirit_title',get_field('acf_acf_purespirit_type',$key));?></td>
                    <td>
                        <div class="" style="display: flex;">
                            <button class="edit-mark" style="width: 30px;font-size: 15px;"><a href="<?php echo getURLSetSlag("admin-spirit-detail"); ?>?user_id=<?php echo get_field('acf_purespirit_id',$key);?>&sheet_name=<?php echo $key;?>" target="_blank">編</a></button>
                        </div>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <?php 
                            $single_money = get_field('acf_purespirit_price',$key);
                            if($single_money == "")$single_money = 0;   //0設定

                            $total_money += $single_money;
                            echo "&yen;" .number_format($single_money);
                        ?>
                    </td>

                   
                     
                    <td style="text-align: center; vertical-align: middle;">
                        <a href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo get_field('acf_purespirit_id',$key);?>"  target="_blank"><?php echo get_user_meta(get_field('acf_purespirit_id',$key),'last_name',true) . " ". get_user_meta(get_field('acf_purespirit_id',$key),'first_name',true); ?></a>
                    </td>
                   
                    <td style="text-align: center; vertical-align: middle;">
                        <?php 
                            $date = get_field('acf_purespirit_requested_date',$key);

                            if($date != ""){
                                echo date('Y年m月d日',strtotime($date));
                            }
                        ?>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <?php 
                            $date = get_field('acf_purespirit_request_confirmation_date',$key);

                            if($date != ""){
                                echo date('Y年m月d日',strtotime($date));
                            }
                        ?>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                         <?php 
                            $date = get_field('acf_purespirit_payment_date',$key);

                            if($date != ""){
                                echo date('Y年m月d日',strtotime($date));
                            }
                        ?>
                    </td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo get_field('acf_purespirit_execution_date',$key);?>
                         <?php 
                            $date = get_field('acf_purespirit_execution_date',$value);

                            if($date != ""){
                                echo date('Y年m月d日',strtotime($date));
                            }
                        ?>
                    </td>
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
                            $spiritStatusArray = $spirit_sheet_data->getSpiritAdminStatusID('cpt_spirit_status');
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
                <tr>
                    <td></td>
                    <td style="text-align: center; vertical-align: middle;">合計金額</td>
                    <td style="font-weight: 700;">&yen;<?php echo number_format($total_money);?></td>
                </tr>
                
            </tbody>
        </table>
    <?php } else {?>
        

    <?php } ?>


    

    <?php 

         $applicant_split_data = $spirit_sheet_data->getSpritIntroducer( $check_user_id );


        if($applicant_split_data != NULL){
    ?>


        <div class="admin-spirit-subtitle-center-box">
            <div class="admin-spirit-menu-title">浄霊各種（紹介者）</div>
        </div>

        <div class="admin-spirit-delete-alert-text">シートの削除は対象者の詳細にて操作してください</div>

        <table id="" class="user-disp-table table table-bordered">
        <!-- <table id="userTable" class="user-disp-table table table-bordered"> -->
            <thead>
                <tr>
                    <th style="width:200px">依頼内容</th>
                    <th>詳細</th>
                    <th>単価</th>
                    <th style="width: 100px;">対象者</th>
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
        <tbody>
            <?php 
                $total_money = 0;   //合計金額
                foreach ($applicant_split_data as $key => $value) { 
                    $sprit_type = get_field('acf_acf_purespirit_type',$key);
                    $is_delete = get_field("is_delete", $key);

                    if($is_delete) continue;    //削除されたシートは表示しない
                    if(isset($_GET['select_sprit_type']) && $sprit_type != $_GET['select_sprit_type']  && "" != $_GET['select_sprit_type']) continue;
            ?>
                <tr>
                    <td style="text-align: center; vertical-align: middle;font-weight: 800;"><?php echo get_field('acf_pure_spirit_title',get_field('acf_acf_purespirit_type',$key));?></td>
                    <td>
                        <div class="" style="display: flex;">
                            <button class="edit-mark" style="width: 30px;font-size: 15px;"><a href="<?php echo getURLSetSlag("admin-spirit-detail"); ?>?user_id=<?php echo get_field('acf_purespirit_id',$key);?>&sheet_name=<?php echo $key;?>" target="_blank">編</a></button>

                           
                        </div>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <?php 
                            $single_money = get_field('acf_purespirit_price',$key);
                            if($single_money == "")$single_money = 0;   //0設定

                            $total_money += $single_money;
                            echo "&yen;" .number_format($single_money);
                        ?>
                    </td>
                     
                    <td style="text-align: center; vertical-align: middle;">
                        <a href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo get_field('acf_purespirit_id',$key);?>"  target="_blank"><?php echo get_user_meta(get_field('acf_purespirit_id',$key),'last_name',true) . " ". get_user_meta(get_field('acf_purespirit_id',$key),'first_name',true); ?></a>
                    </td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo get_field('acf_purespirit_requested_date',$key);?></td>
                    <td style="text-align: center; vertical-align: middle;"><?php echo get_field('acf_purespirit_request_confirmation_date',$key);?></td>
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
                            $spiritStatusArray = $spirit_sheet_data->getSpiritAdminStatusID('cpt_spirit_status');
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
                <tr>
                    <td></td>
                    <td style="text-align: center; vertical-align: middle;">合計金額</td>
                    <td style="font-weight: 700;">&yen;<?php echo number_format($total_money);?></td>
                </tr>
                
            </tbody>
        </table>
    <?php } else {?>
        

    <?php } ?>



    <?php 
    
        //リモート浄霊
        
        $applicant_remote_target_data = $spirit_sheet_data->getRemoteSpritMyTarget( $check_user_id );


        if($applicant_remote_target_data != NULL){
    
    
    ?>

            <div class="admin-spirit-subtitle-center-box">
                <div class="admin-spirit-menu-title">リモート浄霊（対象者）</div>
            </div>

            <div class="admin-spirit-delete-alert-text">シートの削除は申込者の詳細にて操作してください</div>

            <table id="" class="user-disp-table table table-bordered">
            <!-- <table id="userTable" class="user-disp-table table table-bordered"> -->
                <thead>
                    <tr>
                        <th>シートID</th>
                        <th>申込者</th>
                        <th>依頼日</th>
                        <th>入力完了日</th>
                        <th>実行日</th>
                        <th>ステータス</th>
                    </tr>
                </thead>
                <tbody>
             <tbody>
                <?php foreach ($applicant_remote_target_data as $key => $value) {
                
                     $applicant_id = get_field('acf_remote_sprit_sheet_applicant_id' ,$key);
                
                ?>

                    <tr>
                        <td>
                            <a href="<?php echo getURLSetSlag("admin-spirit-detail"); ?>?user_id=<?php echo $applicant_id;?>&sheet_name=<?php echo get_field('acf_remote_sprit_id' ,$key); ?>"  target="_blank">
                                <?php echo $key;?>
                             </a>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <a href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $applicant_id;?>"  target="_blank">
                                <?php echo get_user_meta($applicant_id,'last_name',true); ?>　<?php echo get_user_meta($applicant_id,'first_name',true); ?>
                            </a>
                        </td>
                         <td style="text-align: center; vertical-align: middle;">
                            <?php 
                                $date = get_field('acf_purespirit_requested_date' ,get_field('acf_remote_sprit_id' ,$key));

                                if($date != ""){
                                    echo date('Y年m月d日',strtotime($date));
                                }
                            ?>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <?php 
                                $date = get_field('acf_remote_sprit_sheet_target_input_date_' . $value ,$key);

                                if($date != ""){
                                    echo date('Y年m月d日',strtotime($date));
                                }
                            ?>
                        </td>
                         <td style="text-align: center; vertical-align: middle;">
                            <?php 
                                $date = get_field('acf_remote_sprit_sheet_target_execution_date_' . $value ,$key);

                                if($date != ""){
                                    echo date('Y年m月d日',strtotime($date));
                                }
                            ?>
                        </td>
                         <td style="text-align: center; vertical-align: middle;">
                            <?php 
                            
                                if(get_field('acf_remote_sprit_sheet_target_status_' . $value ,$key) =="")
                                {
                                    echo "未設定";
                                }else{
                                    echo get_field('acf_remote_sprit_sheet_target_status_' . $value ,$key);
                                }
                            ?>
                        </td>


                    </tr>

                <?php } ?>
            </tbody>
            </table>

    <?php } ?>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 

    <script>
    
        $(document).ready( function () {

            $('#userTable').DataTable({
                
                "order": [[0, "asc"]], // 第2列（インデックス2）を昇順（asc）にソート
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
                }
            });
        });

        document.getElementById("form-submit").addEventListener("click", function() {
            var hiddenInput = document.getElementById('hidden_input');
            hiddenInput.name = 'create_member';
            hiddenInput.value = 'create_member';

            // document.getElementById("action").value = "submit";
            document.getElementById("post_new_user_input").value = "submit";
        });

        // 戻るボタン
        function changeInputAndSubmit() {
            // フォーム内のhidden inputのnameとvalueを変更
            var hiddenInput = document.getElementById('hidden_input');
            hiddenInput.name = 'create_back';
            hiddenInput.value = 'create_back';

            // フォームを送信
            document.getElementById('post_new_user_input').submit();
        }
    </script>

    <?php /* 変更履歴をすべて見る */?>
    <?php }else{ ?>
        

        <div class="save-log-area" style="padding-top:30px;">
        <?php 
            $update_data = array();
            $data_no = 0;
            for ($i=$max_index; $i >=0 ; $i--) { 

                $update_data[$data_no]['edit_date'] = $decoded_data[$i]['edit_date'];
                $update_data[$data_no]['edit_user'] = get_updata_user_info($decoded_data[$i]);
                $update_content = "";

                foreach ($decoded_data[$i]['edit_content'] as $key => $value) {
                    $update_content .= '　　' .$key.'変更';
                }


                $update_data[$data_no]['edit_content'] = $update_content;


                // $update_list = $decoded_data[$i]['edit_date'] . '　　' .get_updata_user_info($decoded_data[$i]);
                $data_no++;

            }
        ?>

    </div>
    <table id="userTable" class="user-disp-table table table-bordered" >
        <thead>
            <tr>
                <th>変更日付</th>
                <th>変更者</th>
                <th>変更内容</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <button type="button" id="form-back" class="form-btn gray" onclick="window.close()">閉じる</button>

    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/4.0.2/js/dataTables.fixedColumns.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/4.0.2/css/fixedColumns.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 
    <script>
            var userData = <?php echo json_encode($update_data); ?>;

            $(document).ready( function () {

                $('#userTable').DataTable({
                    data: userData,
                    columns: [
                        { data: 'edit_date' },
                        { data: 'edit_user' },
                        { data: 'edit_content' },
                        
                    ],
                    
                    "order": [[0, "dec"]], // 第2列（インデックス2）を昇順（asc）にソート
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
                    }

                });
            } );

    </script>

    <?php } ?>

</div>