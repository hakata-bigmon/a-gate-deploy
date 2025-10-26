<?php

require_once ("a-gate-functions.php");

require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");


$spiritType = new SpiritTypeClass(); //管理データ
$spiritTypeArray = $spiritType->getSpiritType();

$spiritSheet = new SpiritSheetClass(); //管理データ
$spiritStatusArray = $spiritSheet->getGeneralPurposeData("cpt_inflow");

//ユーザークラス
$userClass = new SpiritUserClass(); //ユーザー管理


// ユーザー情報
if (isset($_GET['user_id'])) {
    $check_user_id = $_GET['user_id'];
}
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

//var_dump($_POST);

//シートの追加
if(isset($_POST['add_sheet'])){

    //物販の場合は個数、その他の場合はその回数を回す
    $spiritTypeNum = $spiritType->getSpiritTypeKeyTypeNum();

    if($spiritTypeNum[$_POST["category_type"]]["group"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES)
    {
        $add_id = $spiritSheet->newSpiritSheet($_POST);

        $spiritSheet->newSpiritSheetUserAdd( $_GET["user_id"] ,$add_id);
    }
    else
    {
        for( $i=0; $i<$_POST["target_slots"]; $i++)
        {
            //ユニックスタイムだけ変更
            $_POST["add_sheet_unix"] = $_POST["add_sheet_unix"] + $i;
            
            $add_id = $spiritSheet->newSpiritSheet($_POST);

            $spiritSheet->newSpiritSheetUserAdd( $_GET["user_id"] ,$add_id);
        }
    }

}


// シート削除
if(isset($_POST['sheet_delete'])){

    // 完全削除の処理はここにお願いします
    $spiritSheet->deleteSpiritSheet( $_POST['sheet_name'] , $check_user_id );
}
// シート非表示
if(isset($_POST['sheet_hidden'])){
    //物販の数を戻す
    $spiritSheet->deleteSpiritSheetSalesStock( $_POST['sheet_name'] );
    //非表示
    update_field("is_delete", true, $_POST['sheet_name']);

}

DeleteModalDisp();  //削除モーダル

$last_up_date = get_user_meta($check_user_id,'last_up_date',true);
$last_up_date_id = get_user_meta($check_user_id,'last_up_date_id',true);
$last_up_date_name = "";

if($last_up_date_id != ""){
    $last_up_date_user = get_userdata($last_up_date_id);
    $last_up_date_name = $last_up_date_user->last_name . " " . $last_up_date_user->first_name;
}
else{
    $last_up_date_name = "";
}

?>


<div class="admin-profile-edit-area" style="max-width: 1200px;">
  <div class="admin-profile-card" style="box-shadow:0 2px 12px #b0c4de;border-radius:16px;background:#fff;padding:40px 32px 32px 32px;max-width:900px;margin:40px auto 60px auto;">
    <div class="admin-profile-edit-title-box" style="margin-bottom:32px;">
      <div class="admin-section-title admin-spirit-menu-title" style="font-size:2rem;color:#234a6f;letter-spacing:0.1em;">プロフィール情報</div>
    </div>

    <div style="text-align: right;">

    最終更新:<?php echo $last_up_date;?>　<?php echo $last_up_date_name;?>

    <div style="margin-top: 10px;">

        <form action="<?php echo getURLSetSlag("users/user-password-edit"); ?>?check_user=<?php echo $_GET['user_id'];?>" method="post" target="_blank">
            <button type="submit" class="admin-profile-edit-btn">パスワード変更</button>
        </form>
    </div>

    </div>

    <?php if(!isset($_GET["all_change_list"])){?>

    <?php 

        require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");

        // 流入データ
        $spirit_sheet_data = new spiritSheetClass(); //管理データ
        $spiritStatusArray = $spirit_sheet_data->getGeneralPurposeData("cpt_inflow");

        // 浄霊データテーブル項目
        $user_split_data = json_decode(get_user_meta($check_user_id,'spirit_data',true));

       // var_dump($user_split_data);
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

        <input type="hidden" name="change_user" value="change_user" id="">

        <div class="user-table-flex" style="margin-top: 60px;">
            <div class="user-table-item">ID</div>
            <div class="user-table-data">
                <!-- <input class="data-check" type="text" name="user_id"  value="<?php echo $user_unique_code ?>" readonly> -->
                <?php echo $user_unique_code ?>
            </div>
            <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="copyToClipboard('<?php echo $user_unique_code?>')">コピー</button>
        </div>
        <div class="user-table-flex">
            <div class="user-table-item">名前</div>
            <div class="user-table-data">
                <?php echo $user_last_name . " ". $user_first_name ;?>　　 <?php if(!judgeUserRole()){?>(<a href="<?php echo getURLSetSlag("users/user_top"); ?>?check_user=<?php echo $check_user_id;?>" target="_blank">会員ページへ</a>)<?php } ?>
                
            </div>
            <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="copyToClipboard('<?php echo $user_last_name . $user_first_name?>')">コピー</button>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">ヨミカタ</div>
            <div class="user-table-data">
                <!-- <input class="data-check" type="text" name="input_last_name_kana" value="<?php echo $user_last_name_kana?>" readonly>
                <input class="data-check" type="text" name="input_first_name_kana" value="<?php echo $user_first_name_kana?>" readonly> -->
                <?php echo $user_last_name_kana.$user_first_name_kana ?>
            </div>
            <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="copyToClipboard('<?php echo $user_last_name_kana . $user_first_name_kana?>')">コピー</button>
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

                if ($connection_id != "" || $connection_id != null) {

                    $group_no = 1;
                    foreach ($connection_id as $id) {
                        if (array_key_exists($id, $cgroup_data)) {
                            $cgroup_acf_data = json_decode(get_field('acf_connection_list', $id), true);    //続き柄取得

                            // グループ追加時にエラーが出た際の処理
                            //削除okd
                            if(get_current_user_id() == 1){
                                if(!isset($cgroup_acf_data[$user_id])){
                                    echo "設定エラー";
                                    var_dump($cgroup_data[$id]);  //削除okd
                                    
                                    // 設定エラー時は削除する
                                    $res = $connection_group_data->DeleteTargetUserConnectGroup($user_id);
                                    
                                    continue;
                                }
                                
                            }


                            $relationship = $connection_group_data->getRelationship($cgroup_acf_data[$user_id]['relationship_data']);
                            if ($relationship == "") {
                                $relationship = "未設定";
                            }
                            // echo $cgroup_data[$id]."　".$connection_group_data->getRelationship($cgroup_acf_data[$user_id]['relationship_data'])."<br>";
                            echo $group_no . ":" . $cgroup_data[$id] . "　続柄:";
                            echo $relationship . "<br>";
                            $group_no++;
                        }
                    }
                }


                // $post_title = getConnectionName($connect_id );

                // グループ
                $user_group_data = json_decode(get_user_meta($check_user_id,'group_data',true));
            ?>
            <!-- <input class="data-check" type="text" name="" id="" value="<?php echo $post_title;// 関連?>" readonly> -->
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
            
            <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="copyToClipboard('<?php echo $user_tel1 . $user_tel2 . $user_tel3?>')">コピー</button>
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
            <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="copyToClipboard('<?php echo $user_zip?>')">コピー</button>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">*住所</div>
            <!-- <input class="data-check" type="text" name="input_address1" id="" value="<?php echo $user_address?>" readonly> -->
            <?php echo $user_address?>
            <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="copyToClipboard('<?php echo $user_address?>')">コピー</button>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">マンション名など</div>
            <!-- <input class="data-check" type="text" name="input_address2" id="" value="<?php echo $user_address2?>" readonly> -->
            <?php echo $user_address2?>
            <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="copyToClipboard('<?php echo $user_address2?>')">コピー</button>
        </div>

        <?php if(!judgeUserRole()){?>
            <?php 
                // ユニックスタイムで登録されていれば仮アドレス
                if(!checkUnix($users->user_email)){
            ?>
                <div class="user-table-flex" >
                    <div class="user-table-item">*メールアドレス</div>

                        <?php echo getParentAddress($users->user_email)?>
                        <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="copyToClipboard('<?php echo getParentAddress($users->user_email)?>')">コピー</button>
                    
                </div>
            <?php } ?>

        <div class="user-table-flex">
            <div class="user-table-item">LINE ID</div>
            <!-- <input class="data-check" type="text" name="input_user_line_id" id="" value="<?php echo $user_line?>" readonly> -->
            <?php echo $user_line?>
            <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="copyToClipboard('<?php echo $user_line?>')">コピー</button>
        </div>
        <?php } ?>

        <div class="user-table-flex">
            <div class="user-table-item">生年月日</div>
            
            <?php if($user_born_year != ""){?>
                <!-- <input class="data-check user-born-input" type="text" name="input_user_born_year" id="" value="<?php echo  $user_born_year?>" readonly>年
                <input class="data-check user-born-input" type="text" name="input_user_born_month" id="" value="<?php echo $user_born_month?>" readonly>月
                <input class="data-check user-born-input" type="text" name="input_user_born_day" id="" value="<?php echo   $user_born_day?>" readonly>日 -->
                <?php echo $user_born_year."年".$user_born_month."月".$user_born_day."日";?>
                <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="copyToClipboard('<?php echo $user_born_year.'年'.$user_born_month.'月'.$user_born_day.'日'?>')">コピー</button>
            <?php } ?>
            
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">届け出日</div>

            <?php if($user_report_born_year != ""){?>
                <!-- <input class="data-check user-born-input" type="text" name="input_user_report_year" id="" value="<?php echo  $user_report_born_year?>" readonly>年
                <input class="data-check user-born-input" type="text" name="input_user_report_month" id="" value="<?php echo $user_report_born_month?>" readonly>月
                <input class="data-check user-born-input" type="text" name="input_user_report_day" id="" value="<?php echo   $user_report_born_day?>" readonly>日 -->
                <?php echo $user_report_born_year."年".$user_report_born_month."月".$user_report_born_day."日";?>
                <button type="button" style="margin-left: auto;margin-right: 10px;" onclick="copyToClipboard('<?php echo $user_report_born_year.'年'.$user_report_born_month.'月'.$user_report_born_day.'日'?>')">コピー</button>
            <?php } ?>
        </div>

        
        <?php if(!judgeUserRole()){?>
        <div class="user-table-flex">
            <div class="user-table-item" class="user-table-item">流入元</div>


            <?php 

                
            
                if($user_inflow != "")
                {
                    $spiritInfrowArray = $spirit_sheet_data->getGeneralPurposeDataKeyID("cpt_inflow");
                    echo $user_inflow;

                    // if(isset($spiritInfrowArray[$user_inflow]))
                    // {
                    //     // echo $spiritInfrowArray[$user_inflow];
                    //     echo $user_inflow;
                    // }
                }
            
            ?>

        </div>

        <div class="user-table-flex">
            <div class="user-table-item">流入元追記</div>
            <?php echo $user_inflow_remarks?>
        </div>
        <?php } ?>

        <div class="user-table-flex">
            <div class="user-table-item">紹介者</div>
            <?php //echo $user_introduction_id;?>
            <?php 
                $introduction_name = get_user_meta($user_introduction_id, 'last_name', true) . " " . get_user_meta($user_introduction_id, 'first_name', true);

                if ($user_introduction_id == 'x') {

                    echo $user_introduction_name;
                } else {
                    $disp_user_intro_id = get_user_meta($user_introduction_id, 'user_unique_id', true);
                    
                
                    if ($disp_user_intro_id != "") {
                        echo "ID" . $disp_user_intro_id. '：';
                        echo get_user_meta($user_introduction_id, 'last_name', true);
                        echo get_user_meta($user_introduction_id, 'first_name', true);
                    }else{
                        echo get_user_meta($user_id, 'input_introduction_name', true);
                    }
                }
            ?>
            <?php // echo 'ID' . get_user_meta($user_introduction_id, 'user_unique_id', true) . '：';?>
            
            <?php // echo get_user_meta($user_introduction_id,'last_name',true) ?> <?php echo get_user_meta($user_introduction_id,'first_name',true) ?>
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


        <?php if(!judgeUserRole()){?>
            <div style="margin-top: 100px;">
                <button type="submit" id="form-submit" class="form-btn"  style="width: 500px;font-size: 20px;background-color: beige;">プロフィールを編集する</button>
            </div>
        <?php } ?>
    </form>

    

    <style>
        /* セルのテキスト折り返し設定 */
        table td, 
        table th {
            word-wrap: break-word; /* 単語単位で折り返す */
            white-space: normal; /* 折り返しを許可 */
            overflow-wrap: break-word; /* ブラウザ対応 */
            max-width: 200px; /* 必要に応じて最大幅を指定 */
            vertical-align: middle; /* 上下中央揃え */
            padding: 10px; /* セル内の余白 */
            text-align: center; /* 横方向も中央揃え（必要なら） */
            border: 1px solid #ccc; /* 罫線の追加（デザイン調整用） */
        }
        /* .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {

            vertical-align: middle;
        } */
        table.table-bordered.dataTable tbody td {
            
            vertical-align: middle;
        }
        .cancel-row {
            background: rgba(0, 0, 0, 0.63) !important;
            color: white !important;
        }
        #member-table {
          font-size: 16px;
        }
        #member-table th, #member-table td {
          font-size: 16px;
          text-align: center;
          vertical-align: middle;
          padding: 10px;
        }
        #member-table th:nth-child(1), #member-table td:nth-child(1) { /* 依頼内容 */
          width: 150px;
          max-width: 150px;
          min-width: 150px;
          word-break: break-all;
          white-space: normal;
        }
        #member-table th:nth-child(8), #member-table td:nth-child(8) { /* 依頼内容追記 */
          width: 200px;
          max-width: 200px;
          min-width: 200px;
          word-break: break-all;
          white-space: normal;
          font-size: 14px;
        }
        #member-table th:not(:nth-child(1)):not(:nth-child(8)),
        #member-table td:not(:nth-child(1)):not(:nth-child(8)) {
          white-space: nowrap;
        }
        #member-other-table, #sales-table {
          font-size: 14px;
        }
        #member-other-table th, #member-other-table td,
        #sales-table th, #sales-table td {
          font-size: 14px;
          text-align: center;
          vertical-align: middle;
          padding: 10px;
        }
        #member-other-table th:nth-child(1), #member-other-table td:nth-child(1),
        #sales-table th:nth-child(1), #sales-table td:nth-child(1) {
          width: 150px;
          max-width: 150px;
          min-width: 150px;
          word-break: break-all;
          white-space: normal;
        }
        #member-other-table th:nth-child(9), #member-other-table td:nth-child(9),
        #sales-table th:nth-child(9), #sales-table td:nth-child(9) {
          width: 200px;
          max-width: 200px;
          min-width: 200px;
          word-break: break-all;
          white-space: normal;
        }
        #member-other-table th:not(:nth-child(1)):not(:nth-child(9)),
        #member-other-table td:not(:nth-child(1)):not(:nth-child(9)),
        #sales-table th:not(:nth-child(1)):not(:nth-child(9)),
        #sales-table td:not(:nth-child(1)):not(:nth-child(9)) {
          white-space: nowrap;
        }
        .ellipsis-2lines {
          display: -webkit-box;
          -webkit-line-clamp: 2;
          -webkit-box-orient: vertical;
          overflow: hidden;
          text-overflow: ellipsis;
          white-space: normal;
          max-width: 150px;
          text-align: left;
          cursor: pointer;
        }
        .custom-tooltip {
          position: fixed;
          z-index: 99999;
          background: #222;
          color: #fff;
          padding: 8px 16px;
          border-radius: 6px;
          font-size: 14px;
          box-shadow: 0 2px 8px rgba(0,0,0,0.18);
          pointer-events: none;
          opacity: 0.97;
          max-width: 400px;
          word-break: break-all;
          white-space: pre-line;
        }
        .action-btn {
          display: inline-flex;
          align-items: center;
          gap: 4px;
          border: none;
          border-radius: 6px;
          font-size: 15px;
          font-weight: bold;
          padding: 6px 16px;
          margin: 0 4px;
          cursor: pointer;
          box-shadow: 0 2px 6px rgba(0,0,0,0.07);
          transition: background 0.2s, color 0.2s;
          text-decoration: none;
        }
        .edit-btn {
          background: #e3f2fd;
          color: #1976d2;
        }
        .edit-btn:hover {
          background: #bbdefb;
          color: #0d47a1;
        }
        .delete-btn {
          background: #ffebee;
          color: #d32f2f;
        }
        .delete-btn:hover {
          background: #ffcdd2;
          color: #b71c1c;
        }
        .material-icons {
          font-size: 18px;
        }
        .icon-btn {
          width: 30px;
          height: 30px;
          min-width: 30px;
          min-height: 30px;
          max-width: 30px;
          max-height: 30px;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          border: none;
          border-radius: 50%;
          font-size: 18px;
          margin: 0 2px;
          cursor: pointer;
          background: #e3f2fd;
          color: #1976d2;
          box-shadow: 0 1px 3px rgba(0,0,0,0.07);
          transition: background 0.2s, color 0.2s;
          text-decoration: none;
          padding: 0;
        }
        .icon-btn.edit-btn:hover {
          background: #bbdefb;
          color: #0d47a1;
        }
        .icon-btn.delete-btn {
          background: #ffebee;
          color: #d32f2f;
        }
        .icon-btn.delete-btn:hover {
          background: #ffcdd2;
          color: #b71c1c;
        }
        .material-icons {
          font-size: 20px;
        }
    </style>

    <div id="split-contentn"></div>
    

</div>
</div>


<div style="margin-left: 30px;margin-right: 30px;">

    <?php if(!judgeUserRole()){?>
      
        <div class="admin-spirit-subtitle-center-box" style="margin-right: auto;margin-left: auto;">
            <div class="admin-spirit-menu-title">施術依頼（本人が対象）</div>
        </div>

        <!-- 依頼内容絞り込みセレクト -->
        <div style="margin-bottom: 10px;">
            <select id="filter-request-title">
                <option value="">すべて表示</option>
                <?php
                $request_titles = [];
                foreach ($user_split_data as $key => $value) {
                    $is_delete = get_field("is_delete", $value);
                    if($is_delete) continue;
                    $user_split_detail = $userClass->getUserSpritApplicantSheet($check_user_id,$value);
                    if($user_split_detail == "") continue;
                    if($user_split_detail[$value]["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES) continue;
                    $title = $user_split_detail[$value]["依頼名前"];
                    $request_titles[$title] = true;

                    
                }
                foreach (array_keys($request_titles) as $title) {
                    echo '<option value="'.htmlspecialchars($title).'">'.htmlspecialchars($title).'</option>';
                }
                ?>
            </select>
        </div>




        <?php $total_price = 0; ?>
        <div class="user-member-table-flex">
            <style>
                #member-table {
                    font-size: 14px;
                }
                #member-table th, #member-table td {
                    font-size: 16px;
                    text-align: center;
                    vertical-align: middle;
                    padding: 10px;
                }
                #member-table th:nth-child(1), #member-table td:nth-child(1) { /* 依頼内容 */
                    max-width: 150px;
                    word-break: break-all;
                    white-space: normal;
                    font-size: 14px;
                }
                #member-table th:nth-child(2), #member-table td:nth-child(2) { /* 詳細 */
                    max-width: 200px;
                    word-break: break-all;
                    white-space: normal;
                    font-size: 14px;
                }
                #member-table th:not(:nth-child(1)):not(:nth-child(2)),
                #member-table td:not(:nth-child(1)):not(:nth-child(2)) {
                    white-space: nowrap;
                    font-size: 14px;
                }
            </style>
            <table id="member-table" class="user-member-table-item">
                <thead>
                    <tr>
                        <th>依頼内容</th>
                        <th>詳細</th>
                        <th>単価</th>
                        <th>依頼日</th>
                        <th>依頼確定日</th>
                        <th>決済日</th>
                        <th>実行日</th>
                        <th>依頼内容追記</th>
                        <th>管理ステ</th>
                        <th>会員ステ</th>
                    </tr>
                </thead>
                <tbody>

                    <?php if($user_split_data != "" && count($user_split_data) > 0){?>
                        <?php foreach ($user_split_data as $key => $value) { ?> 
                            <?php 
                                $is_delete = get_field("is_delete", $value);
                                
                                if($is_delete) continue;    //削除されたシートは表示しない
                                if($value == "") continue;
                                //このPOST番号が存在するかどうか
                            

                                $user_split_detail = $userClass->getUserSpritApplicantSheet($check_user_id,$value);

                                //日程と相談はスケジュールが入っていないと省く(バグ防止)
                                if($user_split_detail[$value]["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY || $user_split_detail[$value]["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN)
                                {
                                    if($user_split_detail[$value]["スケジュール"] == "")
                                    {
                                        continue;
                                    }
                                }

                                //物販は表示しない
                                if($user_split_detail[$value]["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES) continue;

                                if($user_split_detail[$value]["対象者有無"] != ""){
                                    if($user_split_detail[$value]["対象者"]["対象者情報"]){
                                        continue;
                                    }
                                }

                                $a_url = getURLSetSlag("admin-spirit-detail").'?user_id='.$check_user_id.'&sheet_name='.$value;
                                $action_url = getURLSetSlag("admin-member-edit").'?user_id='.$check_user_id;
                                $is_cancel = (
                                    $user_split_detail[$value]["管理者ステータス"] == SpiritUserClass::ADMIN_STATUS_CANCEL ||
                                    $user_split_detail[$value]["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_CANCEL
                                );
                                $total_price += $user_split_detail[$value]["価格元"];
                            ?>
                            <tr>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>>
                                    <span class="ellipsis-2lines tooltip-target" data-tooltip="<?php echo htmlspecialchars($user_split_detail[$value]["依頼管理名前"]); ?>"><?php echo $user_split_detail[$value]["依頼管理名前"]; ?></span>
                                </td>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>>
                                    <div class="" style="display: flex;justify-content: center;">
                                        <a href="<?php echo $a_url; ?>" class="icon-btn edit-btn" title="編集"><span class="material-icons">edit</span></a>
                                    
                                        <?php if($user_split_detail[$value]["粗見シート"] == ""){?>
                                            <form id="hidden_sheet_<?php echo $value; ?>" action="<?php echo $action_url; ?>" method="post" onclick="dispBtn(<?php echo $value; ?>)" style="display:inline;">
                                                <input type="hidden" name="sheet_name" value="<?php echo $value; ?>">
                                                <input type="hidden" name="sheet_hidden" value="sheet_hidden">
                                                <button type="button" class="icon-btn delete-btn" title="削除"><span class="material-icons">delete</span></button>
                                            </form>
                                            <form id="delete_sheet_<?php echo $value; ?>" action="<?php echo $action_url; ?>" method="post" onclick="dispBtn(<?php echo $value; ?>)" style="display:inline;">
                                                <input type="hidden" name="sheet_name" value="<?php echo $value; ?>">
                                                <input type="hidden" name="sheet_delete" value="is_delete">
                                            </form>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["価格"]; ?>円</td>  
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["依頼日年月日"]; ?></td>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["依頼確定日年月日"]; ?></td>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["決済日年月日"]; ?></td>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["実行日年月日"]; ?></td>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?> style="word-break: break-all;white-space: normal;text-align: left;"><?php echo $user_split_detail[$value]["依頼内容追記"]; ?></td>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["管理者ステータス表示"]; ?></td>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["会員ステータス表示"]; ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align:center;font-weight:bold;">依頼合計金額</td>
                        <td style="font-weight:bold;"><?php echo number_format($total_price); ?>円</td>
                        <td colspan="7"></td>
                    </tr>
                </tfoot>
            </table>
        </div>


        <?php 
        
             //施術者別のを配列で作り替える
             $user_split_data_by_user_array = array();

             if($user_split_data != "" && count($user_split_data) > 0){
                foreach ($user_split_data as $key => $value) {

                    $is_delete = get_field("is_delete", $value);
                    if($value == "") continue;
                    if($is_delete) continue;    //削除されたシートは表示しない

                    $user_split_detail = $userClass->getUserSpritApplicantSheet($check_user_id,$value);
                    

                    //物販は表示しない
                    if($user_split_detail[$value]["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES) continue;
                    if($user_split_detail[$value]["対象者有無"] == "")continue;
                    
                    if($user_split_detail[$value]["対象者有無"] != ""){
                        if(!$user_split_detail[$value]["対象者"]["対象者情報"]){
                            continue;
                        }
                    }

                
                    if(!isset($user_split_data_by_user_array[$user_split_detail[$value]["対象者"]["フル名前"]]))
                    {
                        $user_split_data_by_user_array[$user_split_detail[$value]["対象者"]["フル名前"]] = array();
                    }

                    $user_split_data_by_user_array[$user_split_detail[$value]["対象者"]["フル名前"]][$value] = $user_split_detail[$value];

                    

                }
            }
            // var_dump($user_split_data_by_user_array);
        
        ?>


        <?php foreach ($user_split_data_by_user_array as $key => $value) { ?>
            
            <div class="admin-spirit-subtitle-center-box" style="margin-right: auto;margin-left: auto;">
                <div class="admin-spirit-menu-title">施術対象者（<?php echo $key; ?>）</div>
            </div>

            <?php $total_price = 0; ?>

            <div class="user-member-table-flex" style="">
                <table id="member-by-user-table" class="user-member-table-item" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>依頼内容</th>
                            <th>詳細</th>
                            <th>対象者</th>
                            <th>単価</th>
                            <th>依頼日</th>
                            <th>依頼確定日</th>
                            <th>決済日</th>
                            <th>実行日</th>
                            <th>依頼内容追記</th>
                            <th>管理ステ</th>
                            <th>会員ステ</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($value as $by_key => $by_value) { ?>
                        
                            <?php 
                            
                                $user_split_detail = $by_value;

                                
                                $a_url = getURLSetSlag("admin-spirit-detail").'?user_id='.$check_user_id.'&sheet_name='.$by_key;
                                $action_url = getURLSetSlag("admin-member-edit").'?user_id='.$check_user_id;
                                $is_cancel = (
                                    $user_split_detail["管理者ステータス"] == SpiritUserClass::ADMIN_STATUS_CANCEL ||
                                    $user_split_detail["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_CANCEL
                                );
                                $total_price += $user_split_detail["価格元"];
                            ?>

                            <tr>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>>
                                    <span class="ellipsis-2lines tooltip-target" data-tooltip="<?php echo htmlspecialchars($user_split_detail["依頼名前"]); ?>"><?php echo $user_split_detail["依頼名前"]; ?></span>
                                </td>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>>
                                    <div class="" style="display: flex;justify-content: center;">
                                        <a href="<?php echo $a_url; ?>" class="icon-btn edit-btn" title="編集"><span class="material-icons">edit</span></a>
                                    
                                        <?php if($user_split_detail["粗見シート"] == ""){?>
                                            <form id="hidden_sheet_<?php echo $by_key; ?>" action="<?php echo $action_url; ?>" method="post" onclick="dispBtn(<?php echo $by_key; ?>)" style="display:inline;">
                                                <input type="hidden" name="sheet_name" value="<?php echo $by_key; ?>">
                                                <input type="hidden" name="sheet_hidden" value="sheet_hidden">
                                                <button type="button" class="icon-btn delete-btn" title="削除"><span class="material-icons">delete</span></button>
                                            </form>
                                            <form id="delete_sheet_<?php echo $by_key; ?>" action="<?php echo $action_url; ?>" method="post" onclick="dispBtn(<?php echo $by_key; ?>)" style="display:inline;">
                                                <input type="hidden" name="sheet_name" value="<?php echo $by_key; ?>">
                                                <input type="hidden" name="sheet_delete" value="is_delete">
                                            </form>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail["対象者"]["フル名前"]; ?></td> 
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail["価格"]; ?>円</td>  
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail["依頼日年月日"]; ?></td>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail["依頼確定日年月日"]; ?></td>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail["決済日年月日"]; ?></td>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail["実行日年月日"]; ?></td>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?> style="word-break: break-all;white-space: normal;text-align: left;"><?php echo $user_split_detail["依頼内容追記"]; ?></td>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail["管理者ステータス表示"]; ?></td>
                                <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail["会員ステータス表示"]; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align:center;font-weight:bold;">依頼合計金額</td>
                            <td style="font-weight:bold;"><?php echo number_format($total_price); ?>円</td>
                            <td colspan="7"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
     




        <?php } ?>


<?php /*
        <div class="admin-spirit-subtitle-center-box" style="margin-right: auto;margin-left: auto;">
            <div class="admin-spirit-menu-title">施術依頼（本人とは対象が別）</div>
        </div>

        <!-- 施術依頼（本人とは対象が別） 依頼内容絞り込みセレクト -->
        <div style="margin-bottom: 10px;">
            <select id="filter-member-other-title">
                <option value="">すべて表示</option>
                <?php
                $other_titles = [];
                foreach ($user_split_data as $key => $value) {
                    $is_delete = get_field("is_delete", $value);
                    if($is_delete) continue;
                    if($value == "") continue;
                    $user_split_detail = $userClass->getUserSpritApplicantSheet($check_user_id,$value);
                    if($user_split_detail[$value]["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES) continue;
                    if($user_split_detail[$value]["対象者有無"] == "") continue;
                    if($user_split_detail[$value]["対象者有無"] != ""){
                        if(!$user_split_detail[$value]["対象者"]["対象者情報"]){
                            continue;
                        }
                    }
                    $title = $user_split_detail[$value]["依頼名前"];
                    $other_titles[$title] = true;
                }
                foreach (array_keys($other_titles) as $title) {
                    echo '<option value="'.htmlspecialchars($title).'">'.htmlspecialchars($title).'</option>';
                }
                ?>
            </select>
        </div>

        
        <?php $total_price = 0; ?>
        <div class="user-member-table-flex">
            <table id="member-other-table" class="user-member-table-item">
                <thead>
                    <tr>
                        <th>依頼内容</th>
                        <th>詳細</th>
                        <th>対象者</th>
                        <th>単価</th>
                        <th>依頼日</th>
                        <th>依頼確定日</th>
                        <th>決済日</th>
                        <th>実行日</th>
                        <th>依頼内容追記</th>
                        <th>管理ステ</th>
                        <th>会員ステ</th>
                        <th>A-GETE<br>確認</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($user_split_data as $key => $value) { ?> 
                    <?php 
                        $is_delete = get_field("is_delete", $value);
                        if($value == "") continue;
                        if($is_delete) continue;    //削除されたシートは表示しない

                        

                        $user_split_detail = $userClass->getUserSpritApplicantSheet($check_user_id,$value);

                        //物販は表示しない
                        if($user_split_detail[$value]["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES) continue;
                        if($user_split_detail[$value]["対象者有無"] == "")continue;
                           
                        if($user_split_detail[$value]["対象者有無"] != ""){
                            if(!$user_split_detail[$value]["対象者"]["対象者情報"]){
                                continue;
                            }
                        }

                        $a_url = getURLSetSlag("admin-spirit-detail").'?user_id='.$check_user_id.'&sheet_name='.$value;
                        $action_url = getURLSetSlag("admin-member-edit").'?user_id='.$check_user_id;
                        $is_cancel = (
                            $user_split_detail[$value]["管理者ステータス"] == SpiritUserClass::ADMIN_STATUS_CANCEL ||
                            $user_split_detail[$value]["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_CANCEL
                        );
                        $total_price += $user_split_detail[$value]["価格元"];
                    ?>
                    <tr>
                        <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>>
                            <span class="ellipsis-2lines tooltip-target" data-tooltip="<?php echo htmlspecialchars($user_split_detail[$value]["依頼名前"]); ?>"><?php echo $user_split_detail[$value]["依頼名前"]; ?></span>
                        </td>
                        <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>>
                            <div class="" style="display: flex;justify-content: center;">
                                <a href="<?php echo $a_url; ?>" class="icon-btn edit-btn" title="編集"><span class="material-icons">edit</span></a>
                            
                                <?php if($user_split_detail[$value]["粗見シート"] == ""){?>
                                    <form id="hidden_sheet_<?php echo $value; ?>" action="<?php echo $action_url; ?>" method="post" onclick="dispBtn(<?php echo $value; ?>)" style="display:inline;">
                                        <input type="hidden" name="sheet_name" value="<?php echo $value; ?>">
                                        <input type="hidden" name="sheet_hidden" value="sheet_hidden">
                                        <button type="button" class="icon-btn delete-btn" title="削除"><span class="material-icons">delete</span></button>
                                    </form>
                                    <form id="delete_sheet_<?php echo $value; ?>" action="<?php echo $action_url; ?>" method="post" onclick="dispBtn(<?php echo $value; ?>)" style="display:inline;">
                                        <input type="hidden" name="sheet_name" value="<?php echo $value; ?>">
                                        <input type="hidden" name="sheet_delete" value="is_delete">
                                    </form>
                                <?php } ?>
                            </div>
                        </td>
                        <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["対象者"]["フル名前"]; ?></td> 
                        <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["価格"]; ?>円</td>  
                        <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["依頼日年月日"]; ?></td>
                        <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["依頼確定日年月日"]; ?></td>
                        <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["決済日年月日"]; ?></td>
                        <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["実行日年月日"]; ?></td>
                        <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?> style="word-break: break-all;white-space: normal;text-align: left;"><?php echo $user_split_detail[$value]["依頼内容追記"]; ?></td>
                        <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["管理者ステータス表示"]; ?></td>
                        <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["会員ステータス表示"]; ?></td>
                        <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php if(get_field('acf_agate_hands_on',$value) != ""){ echo "〇";} ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:center;font-weight:bold;">依頼合計金額</td>
                        <td style="font-weight:bold;"><?php echo number_format($total_price); ?>円</td>
                        <td colspan="8"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

 */?>
        <div class="admin-spirit-subtitle-center-box" style="margin-right: auto;margin-left: auto;">
            <div class="admin-spirit-menu-title">物販依頼</div>
        </div>

       


        <!-- 物販依頼 依頼内容絞り込みセレクト -->
        <div style="margin-bottom: 10px;">
            <select id="filter-sales-title">
                <option value="">すべて表示</option>
                <?php
                $sales_titles = [];
                foreach ($user_split_data as $key => $value) {
                    $is_delete = get_field("is_delete", $value);
                    if($value == "") continue;
                    if($is_delete) continue;
                    $user_split_detail = $userClass->getUserSpritApplicantSheet($check_user_id,$value);
                    if($user_split_detail[$value]["依頼タイプ"] != SpiritTypeClass::SPIRIT_TYPE_NAME_SALES) continue;
                    $title = $user_split_detail[$value]["依頼名前"];
                    $sales_titles[$title] = true;
                }
                foreach (array_keys($sales_titles) as $title) {
                    echo '<option value="'.htmlspecialchars($title).'">'.htmlspecialchars($title).'</option>';
                }
                ?>
            </select>
        </div>

        
        <?php $total_price = 0; ?>
        <div class="user-member-table-flex">
            <table id="sales-table" class="user-member-table-item">
                <thead>
                    <tr>
                        <th>依頼内容</th>
                        <th>詳細</th>
                        <th>価格</th>
                        <th>個数</th>
                        <th>依頼日</th>
                        <th>依頼確定日</th>
                        <th>決済日</th>
                        <th>実行日</th>
                        <th>依頼内容追記</th>
                        <th>管理ステ</th>
                        <th>会員ステ</th>
                    </tr>
                </thead>
                <tbody>
                <?php if($user_split_data != "" && count($user_split_data) > 0){?>
                    <?php foreach ($user_split_data as $key => $value) { ?> 
                        <?php 
                            $is_delete = get_field("is_delete", $value);
                            if($value == "") continue;
                            if($is_delete) continue;    //削除されたシートは表示しない

                            

                            $user_split_detail = $userClass->getUserSpritApplicantSheet($check_user_id,$value);

                            //物販は表示しない
                            if($user_split_detail[$value]["依頼タイプ"] != SpiritTypeClass::SPIRIT_TYPE_NAME_SALES) continue;

                            $a_url = getURLSetSlag("admin-spirit-detail").'?user_id='.$check_user_id.'&sheet_name='.$value;
                            $action_url = getURLSetSlag("admin-member-edit").'?user_id='.$check_user_id;
                            $is_cancel = (
                                $user_split_detail[$value]["管理者ステータス"] == SpiritUserClass::ADMIN_STATUS_CANCEL ||
                                $user_split_detail[$value]["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_CANCEL
                            );
                            $total_price += $user_split_detail[$value]["価格元"];
                        ?>
                        <tr>
                            <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>>
                                <span class="ellipsis-2lines tooltip-target" data-tooltip="<?php echo htmlspecialchars($user_split_detail[$value]["依頼名前"]); ?>"><?php echo $user_split_detail[$value]["依頼名前"]; ?></span>
                            </td>
                            <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>>
                                <div class="" style="display: flex;justify-content: center;">
                                <a href="<?php echo $a_url; ?>" class="icon-btn edit-btn" title="編集"><span class="material-icons">edit</span></a>
                                
                                <?php if($user_split_detail[$value]["粗見シート"] == ""){?>
                                    <form id="hidden_sheet_<?php echo $value; ?>" action="<?php echo $action_url; ?>" method="post" onclick="dispBtn(<?php echo $value; ?>)" style="display:inline;">
                                        <input type="hidden" name="sheet_name" value="<?php echo $value; ?>">
                                        <input type="hidden" name="sheet_hidden" value="sheet_hidden">
                                        <button type="button" class="icon-btn delete-btn" title="削除"><span class="material-icons">delete</span></button>
                                    </form>
                                    <form id="delete_sheet_<?php echo $value; ?>" action="<?php echo $action_url; ?>" method="post" onclick="dispBtn(<?php echo $value; ?>)" style="display:inline;">
                                        <input type="hidden" name="sheet_name" value="<?php echo $value; ?>">
                                        <input type="hidden" name="sheet_delete" value="is_delete">
                                    </form>
                                <?php } ?>
                                </div>
                            </td>
                            <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["価格"]; ?>円</td>  
                            <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["販売個数"]; ?>個</td>  
                            <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["依頼日年月日"]; ?></td>
                            <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["依頼確定日年月日"]; ?></td>
                            <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["決済日年月日"]; ?></td>
                            <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["実行日年月日"]; ?></td>
                            <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?> style="word-break: break-all;white-space: normal;text-align: left;"><?php echo $user_split_detail[$value]["依頼内容追記"]; ?></td>
                            <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["管理者ステータス表示"]; ?></td>
                            <td <?php if ($is_cancel){ ?> class="cancel-row" <?php } ?>><?php echo $user_split_detail[$value]["会員ステータス表示"]; ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align:center;font-weight:bold;">依頼合計金額</td>
                        <td style="font-weight:bold;"><?php echo number_format($total_price); ?>円</td>
                        <td colspan="8"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php } ?>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"/>
<script>
    $(document).ready(function() {
        // カスタムフィルタ関数を定義
        function createCustomFilter(filterSelector) {
            return function(settings, data, dataIndex) {
                var selected = $(filterSelector).val();
                var title = data[0]; // 1列目が依頼内容
                if (selected === "" || title === selected) {
                    return true;
                }
                return false;
            };
        }

        // メインテーブルの初期化
        if ($('#member-table').length && $('#member-table tbody tr').length > 0) {
            $('#member-table').DataTable({
                pageLength: 50,
                order: [[3, "desc"]],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
                }
            });
            
            // カスタムフィルタを追加
            $.fn.dataTable.ext.search.push(createCustomFilter('#filter-request-title'));
            
            $('#filter-request-title').on('change', function() {
                $('#member-table').DataTable().draw();
            });
        }

        // 物販テーブルの初期化
        if ($('#sales-table').length && $('#sales-table tbody tr').length > 0) {
            $('#sales-table').DataTable({
                pageLength: 50,
                order: [[4, "desc"]],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
                }
            });
            
            // 物販テーブル用のカスタムフィルタ
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    if (settings.nTable && settings.nTable.id === 'sales-table') {
                        var selected = $('#filter-sales-title').val();
                        var title = data[0];
                        if (selected === "" || title === selected) {
                            return true;
                        }
                        return false;
                    }
                    return true;
                }
            );
            
            $('#filter-sales-title').on('change', function() {
                $('#sales-table').DataTable().draw();
            });
        }

        // 対象者別テーブルの初期化
        if ($('#member-by-user-table').length && $('#member-by-user-table tbody tr').length > 0) {
            $('#member-by-user-table').DataTable({
                pageLength: 50,
                order: [[4, "desc"]],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
                }
            });
        }
    });
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
    <!-- <table id="userTable" class="user-disp-table table table-bordered" > -->
    <table id="" class="user-disp-table table table-bordered" >
        <thead>
            <tr>
                <th>変更日付</th>
                <th>変更者</th>
                <th>変更内容</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($update_data as $value) {
            ?>
            <tr>
                <td><?php echo $value['edit_date'] ?></td>
                <td><?php echo $value['edit_user'] ?></td>
                <td><?php echo $value['edit_content'] ?></td>
            </tr>

            <?php 
            }?>
        </tbody>
    </table>
    <button type="button" id="form-back" class="form-btn gray" onclick="window.close()">閉じる</button>

    

    <?php } ?>

</div>

<!-- 古いDataTablesライブラリは削除済み --> 
<script>
    $(document).ready(function() {
        <?php if(isset($all_split_table) && $all_split_table != ""){?>
            // 全浄霊テーブルの初期化
            var js_all_split_table = <?php echo json_encode($all_split_table); ?>;
            
            if ($('#all_split_table').length && js_all_split_table.length > 0) {
                $('#all_split_table').DataTable({
                    searching: false,
                    paging: false,
                    data: js_all_split_table,
                    columns: [
                        { data: 'title' },
                        { data: 'btn' },
                        { data: 'money' },
                        { data: 'request_day' },
                        { data: 'request_ok_day' },
                        { data: 'request_payday' },
                        { data: 'request_execute_day' },
                        { data: 'request_content' },
                        { data: 'request_status' },
                        { data: 'a-gate' }
                    ],
                    order: [[0, "desc"]],
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
                    }
                });
            }
        <?php } ?>
        
        <?php if(isset($target_split_table) && $target_split_table != ""){?>
            // 対象者テーブルの初期化
            var js_target_split_table = <?php echo json_encode($target_split_table); ?>;
            
            if ($('#target_split_table').length && js_target_split_table.length > 0) {
                $('#target_split_table').DataTable({
                    searching: false,
                    paging: false,
                    data: js_target_split_table,
                    columns: [
                        { data: 'title' },
                        { data: 'btn' },
                        { data: 'money' },
                        { data: 'target' },
                        { data: 'request_day' },
                        { data: 'request_ok_day' },
                        { data: 'request_payday' },
                        { data: 'request_execute_day' },
                        { data: 'request_content' },
                        { data: 'request_status' },
                        { data: 'a-gate' }
                    ],
                    order: [[0, "desc"]],
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
                    }
                });
            }
        <?php } ?>

        <?php if(isset($intro_split_table) && $intro_split_table != ""){?>
            // 紹介者テーブルの初期化
            var js_intro_split_table = <?php echo json_encode($intro_split_table); ?>;
            
            if ($('#intro_split_table').length && js_intro_split_table.length > 0) {
                $('#intro_split_table').DataTable({
                    searching: false,
                    paging: false,
                    data: js_intro_split_table,
                    columns: [
                        { data: 'title' },
                        { data: 'btn' },
                        { data: 'money' },
                        { data: 'target' },
                        { data: 'request_day' },
                        { data: 'request_ok_day' },
                        { data: 'request_payday' },
                        { data: 'request_execute_day' },
                        { data: 'request_content' },
                        { data: 'request_status' },
                        { data: 'a-gate' }
                    ],
                    order: [[0, "desc"]],
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
                    }
                });
            }
        <?php } ?>
    });
</script>

<!-- トースト通知用 -->
<div id="copy-toast" style="display:none;position:fixed;right:32px;bottom:32px;z-index:9999;background:rgba(60,60,60,0.95);color:#fff;padding:16px 32px;border-radius:8px;font-size:18px;box-shadow:0 2px 8px #333;pointer-events:none;transition:opacity 0.3s;opacity:0;"></div>
<script>
function copyToClipboard(text) {
  navigator.clipboard.writeText(text);
  const toast = document.getElementById('copy-toast');
  if (toast) {
    toast.textContent = 'コピーしました！';
    toast.style.display = 'block';
    toast.style.opacity = '1';
    setTimeout(() => {
      toast.style.opacity = '0';
      setTimeout(() => { toast.style.display = 'none'; }, 300);
    }, 1500);
  }
}
</script>
<script>
(function(){
  let tooltipDiv = null;
  document.addEventListener('mouseover', function(e) {
    const target = e.target.closest('.tooltip-target');
    if (target && target.dataset.tooltip) {
      if (!tooltipDiv) {
        tooltipDiv = document.createElement('div');
        tooltipDiv.className = 'custom-tooltip';
        document.body.appendChild(tooltipDiv);
      }
      tooltipDiv.textContent = target.dataset.tooltip;
      tooltipDiv.style.display = 'block';
      positionTooltip(e);
    }
  });
  document.addEventListener('mousemove', function(e) {
    if (tooltipDiv && tooltipDiv.style.display === 'block') {
      positionTooltip(e);
    }
  });
  document.addEventListener('mouseout', function(e) {
    const target = e.target.closest('.tooltip-target');
    if (target && tooltipDiv) {
      tooltipDiv.style.display = 'none';
    }
  });
  function positionTooltip(e) {
    if (!tooltipDiv) return;
    const padding = 12;
    let x = e.clientX + padding;
    let y = e.clientY + padding;
    // 画面端で折り返し
    if (x + tooltipDiv.offsetWidth > window.innerWidth) {
      x = window.innerWidth - tooltipDiv.offsetWidth - padding;
    }
    if (y + tooltipDiv.offsetHeight > window.innerHeight) {
      y = window.innerHeight - tooltipDiv.offsetHeight - padding;
    }
    tooltipDiv.style.left = x + 'px';
    tooltipDiv.style.top = y + 'px';
  }
})();
</script>