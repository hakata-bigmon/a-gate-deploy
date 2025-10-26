<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="<?php echo get_template_directory_uri()."/custompage/admin/a-gate-script.js"?>"></script>

<?php 
    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");


    $category_type ="";
    $payment = "";
    $teacher_check = "";
    $free_check = "";

    if(isset($_GET["category_type"]))
    {
        $category_type = $_GET["category_type"];
    }

    if(isset($_GET["payment"]))
    {
        $payment = $_GET["payment"];
    }

    if(isset($_GET["teacher_check"]))
    {
        $teacher_check = $_GET["teacher_check"];
    }
    if(isset($_GET["free_check"]))
    {
        $free_check = $_GET["free_check"];
    }

    //var_dump($_POST);

    // 流入データ
    $spirit_sheet_data = new spiritSheetClass(); //管理データ
    $spiritType = new SpiritTypeClass(); //管理データ
    $userClass = new SpiritUserClass();
    $spiritTypeArray = $spiritType->getSpiritType();
    $spiritStatusArray = $userClass->spirit_status_array;
    //$spiritStatusArray = $spirit_sheet_data->getSpiritAdminStatusID('cpt_spirit_status');
    $spiritMemberStatusArray = $userClass->spirit_uSerStatus_array;
    //$spiritMemberStatusArray = $spirit_sheet_data->getSpiritAdminStatusID('cpt_spirit_usestatus');

    //var_dump($spiritTypeArray);

    
    //シートデータ
    $spiritSheetBaseArray = $spirit_sheet_data->getSpiritSheet($category_type,true);


    //保存

    if(isset($_POST["save_search"]))
    {

        //タイムゾーンを東京で設定
        date_default_timezone_set('Asia/Tokyo');
        $today = date("Y-m-d H:i:s");

        foreach($spiritSheetBaseArray as $key => $value)
        {
            $save_id = false;

            //管理者ステータス
            if(isset($_POST["admin_status_".$key]))
            {
                $save_id = true;
                update_field("acf_purespirit_status", $_POST["admin_status_".$key], $key);
            }

            //会員ステータス
            if(isset($_POST["member_status_".$key]))
            {
                $save_id = true;
                update_field("acf_purespirit_user_status", $_POST["member_status_".$key], $key);
            }

            if($save_id)
            {
                update_field("acf_previous_change_last_day",$today, $key);
                update_field("acf_previous_change_last_id", get_current_user_id(), $key);
            }




            $search_array[$key] = $value;
        }
    }


    $category_array = array();

    if($category_type == "")
    {

        foreach($spiritTypeArray as $key => $value)
        {
            foreach($value as $key_num => $value_num)
            {
                $category_array[$value_num["ID"]] = $value_num["title"];
            }
        }

        $spiritSheetArray = $spiritSheetBaseArray;
    }
    else{

        foreach($spiritTypeArray[$category_type] as $key => $value)
        {
            $category_array[$value["ID"]] = $value["title"];
        }

        $spiritSheetArray = $spiritSheetBaseArray;
    }


    //$paymentがfalseの場合、絞り込みの初期化を設定する
    $defaultVisibleColumns = array();
    if($payment == "false")
    {
        // 未入金の場合：ID、振込予定日、依頼内容、決済方法、ステータス、会員ステータス、申込者、フリガナ、メールのみ表示
        $defaultVisibleColumns = array(0, 2, 5, 6, 7, 8, 9, 10, 12, 16);
    }
    else if($teacher_check == "true") //先生確認
    {
       
        $defaultVisibleColumns = array(3, 4, 5, 7, 8, 9, 10, 11, 15, 16);
    }
    else{ //入金済みの場合：全て表示
        $defaultVisibleColumns = array(0, 1, 3, 4, 5, 7, 8, 9, 10, 11, 12, 15, 16, 17);
    }

    //var_dump($category_array);
?>



<div class="admin-profile-edit-area">

 
    <div class="admin-profile-edit-title-box">
        <div class="admin-exorcism-menu-title" style="text-align: center;">
        <?php if($category_type == ""){ ?>
            浄霊・施術管理
        <?php }else{ ?>
            <?php echo $spiritType->getSpiritTypeName($category_type); ?>管理
        <?php } ?>
        <?php if($teacher_check !=""){ ?>
            (先生依頼)
        <?php } ?>
       
        </div>
    </div>

     <?php if($teacher_check ==""){ ?>
         <div class="payment-filter-buttons">
             <?php if($payment == "false" && $free_check == ""): ?>
                 <span class="payment-btn unpaid-btn disabled">未入金者一覧</span>
             <?php else: ?>
                 <a href="<?php echo getURLSetSlag("admin-spirit-sheets-list"); ?>?category_type=<?php echo $category_type; ?>&payment=false" class="payment-btn unpaid-btn">未入金者一覧</a>
             <?php endif; ?>
             
             <?php if($payment == "true" && $free_check == ""): ?>
                 <span class="payment-btn paid-btn disabled">入金済み一覧</span>
             <?php else: ?>
                 <a href="<?php echo getURLSetSlag("admin-spirit-sheets-list"); ?>?category_type=<?php echo $category_type; ?>&payment=true" class="payment-btn paid-btn">入金済み一覧</a>
             <?php endif; ?>
             
             <?php if($category_type == "2"){ ?>

               <?php if($free_check == "true"): ?>
                   <span class="payment-btn free-btn disabled">無料鑑定</span>
               <?php else: ?>
                   <a href="<?php echo getURLSetSlag("admin-spirit-sheets-list"); ?>?category_type=<?php echo $category_type; ?>&free_check=true" class="payment-btn free-btn">無料鑑定</a>
               <?php endif; ?>

            <?php } ?>
             <?php if($payment == "" && $free_check == ""): ?>
                 <span class="payment-btn all-btn disabled">すべて</span>
             <?php else: ?>
                 <a href="<?php echo getURLSetSlag("admin-spirit-sheets-list"); ?>?category_type=<?php echo $category_type; ?>" class="payment-btn all-btn">すべて</a>
             <?php endif; ?>
         </div>
     <?php } ?>



    <div class="accordion-container">
        <div class="accordion-item">
            <div class="accordion-header" onclick="toggleAccordion('columnAccordion')">
                <h4>表示する列を選択してください</h4>
                <span class="accordion-icon" id="columnAccordionIcon">▼</span>
            </div>
            <div class="accordion-content" id="columnAccordion">
            <div class="column-checkboxes">
                <?php 
                $columnNames = array(
                    0 => 'ID', 1 => '決済日', 2 => '振込予定日', 3 => '依頼日', 4 => '依頼確定日',
                    5 => '依頼内容', 6 => '決済方法', 7 => 'ステータス', 8 => '会員ステータス',
                    9 => '申込者名', 10 => 'フリガナ', 11 => '対象者', 12 => 'メール',
                    13 => '連絡先', 14 => 'グループ', 15 => '実行日', 16 => '最終変更日', 17 => '最終変更者'
                );
                
                for($i = 0; $i <= 17; $i++):
                    $isChecked = '';
                    $isChecked = in_array($i, $defaultVisibleColumns) ? 'checked' : '';
                ?>
                    <label><input type="checkbox" data-column="<?php echo $i; ?>" <?php echo $isChecked; ?>> <?php echo $columnNames[$i]; ?></label>
                <?php endfor; ?>
            </div>
                <div class="column-control-buttons">
                    <button type="button" id="showAllColumns" class="btn btn-sm btn-outline-primary">全て表示</button>
                    <button type="button" id="hideAllColumns" class="btn btn-sm btn-outline-secondary">全て非表示</button>
                </div>
            </div>
        </div>


        <?php if($teacher_check ==""){ ?>
            <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion('filterAccordion')">
                    <h4>絞り込み条件</h4>
                    <span class="accordion-icon" id="filterAccordionIcon">▼</span>
                </div>
                <div class="accordion-content" id="filterAccordion">
                    <div class="filter-controls">
                        <div class="filter-group">
                            <label for="statusFilter">ステータス：</label>
                            <select id="statusFilter" class="filter-select">
                                <option value="">全て</option>
                                <?php foreach($spiritStatusArray as $key => $value){ ?>
                                    <option value="<?php echo $value['title']; ?>"><?php echo $value['title']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="memberStatusFilter">会員ステータス：</label>
                            <select id="memberStatusFilter" class="filter-select">
                                <option value="">全て</option>
                                <?php foreach($spiritMemberStatusArray as $key => $value){ ?>
                                    <option value="<?php echo $value['title']; ?>"><?php echo $value['title']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="paymentTypeFilter">決済方法：</label>
                            <select id="paymentTypeFilter" class="filter-select">
                                <option value="">全て</option>
                                <?php foreach($userClass->payment_type_field as $key => $value){ ?>
                                    <?php if($key != SpiritUserClass::PAYMENT_TYPE_NOT_SET){ ?>
                                        <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="categoryFilter">依頼内容：</label>
                            <select id="categoryFilter" class="filter-select">
                                <option value="">全て</option>
                                <?php foreach($category_array as $key => $value){ ?>
                                    <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="date-filter-section">
                        <h5>日付範囲絞り込み：</h5>
                        <div class="date-filter-controls">
                            <div class="date-filter-group">
                                <label for="paymentDateFrom">決済日：</label>
                                <div class="date-range-inputs">
                                    <input type="date" id="paymentDateFrom" class="date-input">
                                    <span class="date-separator">～</span>
                                    <input type="date" id="paymentDateTo" class="date-input">
                                </div>
                            </div>
                            
                            <div class="date-filter-group">
                                <label for="requestDateFrom">依頼日：</label>
                                <div class="date-range-inputs">
                                    <input type="date" id="requestDateFrom" class="date-input">
                                    <span class="date-separator">～</span>
                                    <input type="date" id="requestDateTo" class="date-input">
                                </div>
                            </div>
                            
                            <div class="date-filter-group">
                                <label for="confirmDateFrom">依頼確定日：</label>
                                <div class="date-range-inputs">
                                    <input type="date" id="confirmDateFrom" class="date-input">
                                    <span class="date-separator">～</span>
                                    <input type="date" id="confirmDateTo" class="date-input">
                                </div>
                            </div>
                            
                            <div class="date-filter-group">
                                <label for="executionDateFrom">実行日：</label>
                                <div class="date-range-inputs">
                                    <input type="date" id="executionDateFrom" class="date-input">
                                    <span class="date-separator">～</span>
                                    <input type="date" id="executionDateTo" class="date-input">
                                </div>
                            </div>
                        </div>
                        <div class="filter-buttons">
                            <button type="button" id="clearFilters" class="btn btn-sm btn-outline-secondary">条件クリア</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <div>

        <form id="statusUpdateForm" action="<?php echo getURLSetSlag("admin-spirit-sheets-list"); ?>?category_type=<?php echo $category_type; ?>&payment=<?php echo $payment; ?>&teacher_check=<?php echo $teacher_check; ?>" method="post">
            
            <input type="hidden" name="save_search" value="true">

            <button type="submit" id="saveButton" class="btn btn-primary btn-lg" style="font-weight: bold; padding: 12px 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">保　存</button>
        
            <table id="spiritSheetsTable" class="table table-striped table-hover">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>決済日</th>
                        <th>振込予定日</th>
                        <th>依頼日</th>
                        <th>依頼確定日</th>
                        <th>依頼内容</th>
                        <th>決済方法</th>
                        <th>ステータス</th>
                        <th>会員ステータス</th>
                        <th>申込者名</th>
                        <th>フリガナ</th>
                        <th>対象者</th>
                        <th>メール</th>
                        <th>連絡先</th>
                        <th>グループ</th>
                        <th>実行日</th>
                        <th>最終変更日</th>
                        <th>最終変更者</th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach($spiritSheetArray as $key => $value){ ?>


                    <?php 
                    

                        $user_id = get_field('acf_purespirit_id',$value);

                        $sheet_get_array = array("決済日","振込予定日","依頼日","依頼確定日","実行日","対象者","依頼内容追記","支払いタイプ","振込予定日");
                        $sheet_user_data = $userClass->getUserSpritApplicantSheet($user_id,$key,$sheet_get_array);

                        $user_data = get_user_by('id',$user_id);

                        //var_dump($user_data);
                        
                        if($sheet_user_data == "") continue;
                        
                        

                        $sheet_data = $sheet_user_data[$key];

                        
                        //鑑定のみ有料と無料でわける

                        if($category_type == "2")
                        {
                            //無料枠
                            if($free_check)
                            {
                                if($sheet_data["価格"] != 0) continue;
                            }
                            else{
                                if($sheet_data["価格"] == 0) continue;
                            }

                        }
                        
                        //$category_arrayの配列の値の中にsheet_data["依頼管理名前"]がない場合はcontinue
                        if(!in_array($sheet_data["依頼管理名前"],$category_array))
                        {
                            continue;
                        }

                        //入金未入金
                        if($payment !="")
                        {
                            if($payment == "false") //未入金
                            {
                                if($sheet_data["決済日年月日"] != "" || $sheet_data["会員ステータス"] != SpiritUserClass::MEMBER_STATUS_WATING_PAYMENT) continue;
                            }
                            else //入金済み
                            {
                                if($sheet_data["決済日年月日"] == "" || $sheet_data["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_WATING_PAYMENT) continue;
                            }
                        }

                        //先生確認
                        if($teacher_check !="")
                        {
                            if($sheet_data["会員ステータス"] != SpiritUserClass::MEMBER_STATUS_CURRNTLY_REQUESTING) continue;
                        }

                        //var_dump($sheet_data);
                    ?>

                    <tr>
                        <td>
                            <a href="<?php echo home_url(); ?>/admin-spirit-detail/?user_id=<?php echo $user_id; ?>&sheet_name=<?php echo $key; ?>" target="_blank"><?php echo $key; ?></a>
                        </td>
                        <td><?php echo $sheet_data["決済日年月日"]; ?></td>
                        <td><?php echo $sheet_data["振込予定日年月日"]; ?></td>
                        <td><?php echo $sheet_data["依頼日年月日"]; ?></td>
                        <td><?php echo $sheet_data["依頼確定日年月日"]; ?></td>
                        <td>
                        <a href="<?php echo home_url(); ?>/admin-spirit-detail/?user_id=<?php echo $user_id; ?>&sheet_name=<?php echo $key; ?>" target="_blank"><?php echo $sheet_data["依頼管理名前"]; ?></a>
                        </td>
                        <td><?php echo $sheet_data["支払いタイプ表示"]; ?></td>
                        <td>
                            <select name="admin_status_<?php echo $key; ?>" id="admin_status">

                               
                                <?php foreach($spiritStatusArray as $status_key => $status_value){ ?>
                                    <?php if($sheet_data["管理者ステータス"] != $status_value['ID']){ continue; } ?>
                                    <option value="<?php echo $status_value['ID']; ?>" <?php if($sheet_data["管理者ステータス"] == $status_value['ID']){ echo "selected"; }?>><?php echo $status_value['title']; ?></option>
                                <?php } ?>
                                <?php foreach($spiritStatusArray as $status_key => $status_value){ ?>
                                    <?php if($sheet_data["管理者ステータス"] == $status_value['ID']){ continue; } ?>
                                    <option value="<?php echo $status_value['ID']; ?>" <?php if($sheet_data["管理者ステータス"] == $status_value['ID']){ echo "selected"; }?>><?php echo $status_value['title']; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                        <select name="member_status_<?php echo $key; ?>" id="member_status">

                                <?php foreach($spiritMemberStatusArray as $status_key => $status_value){ ?>
                                    <?php if($sheet_data["会員ステータス"] != $status_value['ID']){ continue; } ?>
                                    <option value="<?php echo $status_value['ID']; ?>" <?php if($sheet_data["会員ステータス"] == $status_value['ID']){ echo "selected"; }?>><?php echo $status_value['title']; ?></option>
                        
                                <?php } ?>

                                <?php foreach($spiritMemberStatusArray as $status_key => $status_value){ ?>
                                    <?php if($sheet_data["会員ステータス"] == $status_value['ID']){ continue; } ?>
                                    <option value="<?php echo $status_value['ID']; ?>" <?php if($sheet_data["会員ステータス"] == $status_value['ID']){ echo "selected"; }?>><?php echo $status_value['title']; ?></option>
                        
                                <?php } ?>
                            </select>
                        
                        </td>
                        <td>
                            <a href="<?php echo home_url(); ?>/admin-member-edit/?user_id=<?php echo $user_id; ?>" target="_blank"><?php echo $sheet_data["苗字"] .  $sheet_data["名前"]; ?></a>
                    </td>
                        <td><?php echo $sheet_data["ミョウジ"] .  $sheet_data["ナマエ"]; ?></td>

                        <td>
                            <?php if($sheet_data["対象者有無"] == "1"){ ?>
                                <?php echo $sheet_data["対象者"]["苗字"] . $sheet_data["対象者"]["名前"]; ?>
                            <?php } ?>
                        </td>


                        <td><?php echo $user_data->user_email; ?></td>
                        <?php 
                        
                            $user_tel1 = get_user_meta($user_id,'billing_phone',true);
                            $user_tel2 = get_user_meta($user_id,'billing_phone2',true);
                            $user_tel3 = get_user_meta($user_id,'billing_phone3',true);
                        
                        ?>
                        <td><?php echo $user_tel1 . "-" . $user_tel2 . "-" . $user_tel3; ?></td>

                        <td></td>
                        <td><?php echo $sheet_data["実行日年月日"]; ?></td>
                        <td><?php echo $sheet_data["最終変更日年月日"]; ?></td>
                        <td><?php echo $sheet_data["最終変更者名"]; ?></td>
                    
                    </tr>
                <?php } ?>

                </tbody>
            </table>

            <button type="submit" id="saveButtonBottom" class="btn btn-primary btn-lg" style="font-weight: bold; padding: 12px 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);margin-top: 24px;">保　存</button>
        </form>
    </div>


</div>

<style>

body {
    overflow-x: auto !important;
    min-width: 100%;
}
.admin-profile-edit-area {
    max-width: none !important; /* !importantで強制的に無効化 */
    width: 100% !important;
    overflow-x: auto !important;
}
    .payment-filter-buttons {
        display: flex;
        gap: 15px;
        
        justify-content: flex-start;
        flex-wrap: wrap;
        margin-top: 10px;
    }
    
    .payment-btn {
        display: inline-block;
        padding: 3px 24px;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: 2px solid;
        text-align: center;
        min-width: 120px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .unpaid-btn {
        background-color: #e9ecef;
        border-color: #adb5bd;
        color: #495057;
    }
    
    .unpaid-btn:hover {
        background-color: #dee2e6;
        border-color: #adb5bd;
        color: #495057;
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-decoration: none;
    }
    
    .paid-btn {
        background-color: #e9ecef;
        border-color: #adb5bd;
        color: #495057;
    }
    
    .paid-btn:hover {
        background-color: #dee2e6;
        border-color: #adb5bd;
        color: #495057;
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-decoration: none;
    }
    
    .all-btn {
        background-color: #e9ecef;
        border-color: #adb5bd;
        color: #495057;
    }
    
    .all-btn:hover {
        background-color: #dee2e6;
        border-color: #adb5bd;
        color: #495057;
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-decoration: none;
    }
    
    .free-btn {
        background-color: #e9ecef;
        border-color: #adb5bd;
        color: #495057;
    }
    
    .free-btn:hover {
        background-color: #dee2e6;
        border-color: #adb5bd;
        color: #495057;
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-decoration: none;
    }
    
    .payment-btn.unpaid-btn.disabled {
        background-color: #ff6b6b !important;
        border-color: #ff6b6b !important;
        color: #ffffff !important;
        cursor: not-allowed;
        opacity: 1;
    }
    
    .payment-btn.unpaid-btn.disabled:hover {
        background-color: #ff6b6b !important;
        border-color: #ff6b6b !important;
        color: #ffffff !important;
        transform: none;
        box-shadow: none;
        text-decoration: none;
    }
    
    .payment-btn.paid-btn.disabled {
        background-color: #51cf66 !important;
        border-color: #51cf66 !important;
        color: #ffffff !important;
        cursor: not-allowed;
        opacity: 1;
    }
    
    .payment-btn.paid-btn.disabled:hover {
        background-color: #51cf66 !important;
        border-color: #51cf66 !important;
        color: #ffffff !important;
        transform: none;
        box-shadow: none;
        text-decoration: none;
    }
    
    .payment-btn.all-btn.disabled {
        background-color: #339af0 !important;
        border-color: #339af0 !important;
        color: #ffffff !important;
        cursor: not-allowed;
        opacity: 1;
    }
    
    .payment-btn.all-btn.disabled:hover {
        background-color: #339af0 !important;
        border-color: #339af0 !important;
        color: #ffffff !important;
        transform: none;
        box-shadow: none;
        text-decoration: none;
    }
    
    .payment-btn.free-btn.disabled {
        background-color: #9c27b0 !important;
        border-color: #9c27b0 !important;
        color: #ffffff !important;
        cursor: not-allowed;
        opacity: 1;
    }
    
    .payment-btn.free-btn.disabled:hover {
        background-color: #9c27b0 !important;
        border-color: #9c27b0 !important;
        color: #ffffff !important;
        transform: none;
        box-shadow: none;
        text-decoration: none;
    }
    
    .accordion-container {
        margin: 10px 0;
        max-width: 1300px;
    }
    
    .accordion-item {
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        margin-bottom: 10px;
        overflow: hidden;
    }
    
    .accordion-header {
        background: #e9ecef;
        padding: 0px 20px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background-color 0.2s ease;
        border-bottom: 1px solid #dee2e6;
    }
    
    .accordion-header:hover {
        background: #dee2e6;
    }
    
    .accordion-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }
    
    .accordion-icon {
        font-size: 14px;
        color: #666;
        transition: transform 0.3s ease;
    }
    
    .accordion-icon.rotated {
        transform: rotate(180deg);
    }
    
    .accordion-content {
        padding: 20px;
        display: none;
        animation: slideDown 0.3s ease;
    }
    
    .accordion-content.active {
        display: block;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            max-height: 0;
        }
        to {
            opacity: 1;
            max-height: 1000px;
        }
    }
    
    .column-control-panel {
        background: #f8f9fa;
        padding: 20px;
        margin: 20px 0;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    
    .column-control-panel h4 {
        margin: 0 0 15px 0;
        color: #333;
        font-size: 16px;
        font-weight: 600;
    }
    
    .column-checkboxes {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .column-checkboxes label {
        display: flex;
        align-items: center;
        margin: 0;
        font-size: 14px;
        cursor: pointer;
        padding: 5px 10px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        transition: all 0.2s ease;
    }
    
    .column-checkboxes label:hover {
        background: #f0f0f0;
        border-color: #A078D0;
    }
    
    .column-checkboxes input[type="checkbox"] {
        margin-right: 8px;
        transform: scale(1.1);
    }
    
    .column-control-buttons {
        display: flex;
        gap: 10px;
    }
    
    .btn {
        padding: 6px 12px;
        border: 1px solid;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s ease;
    }
    
    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
    }
    
    .btn-outline-primary {
        color: #A078D0;
        border-color: #A078D0;
        background: white;
       
    }
    
    .btn-outline-primary:hover {
        background: #A078D0;
        color: white;
    }
    
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
        background: white;
    }
    
    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
    }
    
    .btn-primary {
        background: #A078D0;
        border-color: #A078D0;
        color: white;
    }
    
    .btn-primary:hover {
        background: #8B5BB3;
        border-color: #8B5BB3;
    }
    
    .filter-panel {
        background: #f8f9fa;
        padding: 20px;
        margin: 20px 0;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    
    .filter-panel h4 {
        margin: 0 0 15px 0;
        color: #333;
        font-size: 16px;
        font-weight: 600;
    }
    
    .filter-controls {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: end;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        min-width: 200px;
    }
    
    .filter-group label {
        margin-bottom: 5px;
        font-weight: 500;
        color: #555;
        font-size: 14px;
    }
    
    .filter-select {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: white;
        font-size: 14px;
        color: #333;
        cursor: pointer;
        transition: border-color 0.2s ease;
    }
    
    .filter-select:focus {
        outline: none;
        border-color: #A078D0;
        box-shadow: 0 0 0 2px rgba(160, 120, 208, 0.2);
    }
    
    .filter-panel .filter-buttons {
        display: flex;
        gap: 10px;
        margin-left: auto;
    }
    
    .date-filter-section {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #dee2e6;
    }
    
    .date-filter-section h5 {
        margin: 0 0 15px 0;
        color: #333;
        font-size: 14px;
        font-weight: 600;
    }
    
    .date-filter-controls {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 15px;
    }
    
    .date-filter-group {
        display: flex;
        flex-direction: column;
        min-width: 250px;
    }
    
    .date-filter-group label {
        margin-bottom: 8px;
        font-weight: 500;
        color: #555;
        font-size: 14px;
    }
    
    .date-range-inputs {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .date-input {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: white;
        font-size: 14px;
        color: #333;
        cursor: pointer;
        transition: border-color 0.2s ease;
        flex: 1;
    }
    
    .date-input:focus {
        outline: none;
        border-color: #A078D0;
        box-shadow: 0 0 0 2px rgba(160, 120, 208, 0.2);
    }
    
    .date-separator {
        color: #666;
        font-weight: 500;
        font-size: 14px;
        white-space: nowrap;
    }
    
    @media (max-width: 768px) {
        .filter-controls {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-group {
            min-width: auto;
        }
        
        .filter-panel .filter-buttons {
            margin-left: 0;
            justify-content: center;
        }
        
        .date-filter-controls {
            flex-direction: column;
            gap: 15px;
        }
        
        .date-filter-group {
            min-width: auto;
        }
        
        .date-range-inputs {
            flex-direction: column;
            gap: 5px;
        }
        
        .date-separator {
            text-align: center;
        }
    }
    
    .dataTables_wrapper {
        margin-top: 20px;
    }
    
    .dataTables_length,
    .dataTables_filter {
        display: inline-flex;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .dataTables_length {
        float: left;
    }
    
    .dataTables_filter {
        float: right;
    }
    
    .dataTables_filter input {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-left: 10px;
        height: 38px;
    }
    
    .dataTables_length select {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin: 0 5px;
        height: 38px;
    }
    
    .dataTables_length label,
    .dataTables_filter label {
        display: flex;
        align-items: center;
        margin: 0;
        font-size: 14px;
        color: #333;
    }
    
    .dataTables_wrapper::after {
        content: "";
        display: table;
        clear: both;
    }
    
    .dataTables_info {
        margin-top: 20px;
        font-size: 14px;
        color: #666;
    }
    
    .dataTables_paginate {
        margin-top: 20px;
        text-align: center;
    }
    
    .dataTables_paginate .paginate_button {
        display: inline-block;
        padding: 8px 12px;
        margin: 0 2px;
        border: 1px solid #ddd;
        background: #fff;
        color: #333;
        text-decoration: none;
        border-radius: 4px;
        cursor: pointer;
        vertical-align: middle;
    }
    
    .dataTables_paginate .paginate_button:hover {
        background: #f5f5f5;
    }
    
    .dataTables_paginate .paginate_button.current {
        background: #A078D0;
        color: white;
        border-color: #A078D0;
    }
    
    .dataTables_paginate .paginate_button.disabled {
        color: #999;
        cursor: not-allowed;
    }
    
    .dataTables_paginate .paginate_button.disabled:hover {
        background: #fff;
    }
    
    .dataTables_paginate .ellipsis {
        display: inline-block;
        padding: 8px 4px;
        margin: 0 2px;
        color: #666;
    }
    
    #spiritSheetsTable {
        width: 100%;
        border-collapse: collapse;
        table-layout: auto;
        border: 1px solid #ddd;
    }
    
    #spiritSheetsTable th,
    #spiritSheetsTable td {
        padding: 12px 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        border-right: 1px solid #ddd;
        font-size: 14px;
        white-space: nowrap;
    }
    
    /* 最後の列の右ボーダーを削除 */
    #spiritSheetsTable th:last-child,
    #spiritSheetsTable td:last-child {
        border-right: none;
    }
    
    /* selectボックスがあるセルは自動幅に調整 */
    #spiritSheetsTable td:has(select) {
        width: auto;
        min-width: 150px;
    }
    
    #spiritSheetsTable th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
        position: relative;
        border-bottom: 2px solid #bbb;
    }
    
    /* 絞り込みが発動している列のthのスタイル */
    #spiritSheetsTable th.filtered {
        background-color: #fff3cd;
        border-left: 4px solid #ffc107;
        color: #856404;
    }
    
    /* 絞り込みアイコン */
    #spiritSheetsTable th.filtered::after {
        content: "🔍";
        position: absolute;
        top: 2px;
        right: 5px;
        font-size: 12px;
        opacity: 0.7;
    }
    
    #spiritSheetsTable tbody tr:hover {
        background-color: #f5f5f5;
    }
    
    #spiritSheetsTable select {
        width: 100%;
        padding: 4px 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: white;
        font-size: 14px;
        min-width: 120px;
    }
    
    /* 会員ステータスのselectボックス専用スタイル */
    #spiritSheetsTable select[name="member_status"] {
        min-width: 150px;
        width: auto;
    }
    
    /* 管理者ステータスのselectボックス専用スタイル */
    #spiritSheetsTable select[name="admin_status"] {
        min-width: 130px;
        width: auto;
    }
    
    #spiritSheetsTable select:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
    }
    
    /* クリックされた行のスタイル */
    #spiritSheetsTable tbody tr.clicked {
        background-color: #e3f2fd !important;
        border-left: 4px solid #2196f3;
    }
    
    #spiritSheetsTable tbody tr.clicked:hover {
        background-color: #bbdefb !important;
    }
    
    /* クリックされた行内のリンクのスタイル */
    #spiritSheetsTable tbody tr.clicked a {
        color: #1976d2;
        font-weight: 600;
    }
    
    #spiritSheetsTable tbody tr.clicked a:hover {
        color: #0d47a1;
        text-decoration: underline;
    }
    
    /* ブラウザの横スクロールを有効にする */
    body {
        overflow-x: auto;
        min-width: 2200px; /* テーブル全体が表示される最小幅を設定（余裕を持たせて） */
    }
    
    .dataTables_wrapper {
        overflow-x: visible;
        overflow-y: visible;
        position: relative;
        width: 100%;
    }
    
    /* テーブルを自動幅表示 */
    #spiritSheetsTable {
        width: auto !important;
        min-width: 100% !important;
        max-width: none !important;
        table-layout: auto !important;
        display: table !important;
        border-collapse: collapse !important;
    }
    
    /* テーブル行の高さを固定 */
    #spiritSheetsTable tbody tr {
        height: 35px !important;
    }
    
    /* 列の幅を自動調整 */
    #spiritSheetsTable th,
    #spiritSheetsTable td {
        white-space: nowrap !important; /* テキストの折り返しを禁止 */
        overflow: visible !important; /* はみ出した部分も表示 */
        text-overflow: clip !important; /* 省略記号なし */
        min-width: auto;
        max-width: none;
        padding: 8px 4px; /* パディングを調整してスペースを節約 */
        font-size: 13px; /* フォントサイズを少し小さく */
        line-height: 1.3; /* 行間を調整 */
        vertical-align: middle !important; /* セルの中央揃え */
    }
    
    /* 列幅をコンテンツに応じて自動調整 */
    #spiritSheetsTable th,
    #spiritSheetsTable td {
        width: auto !important;
        min-width: auto !important;
        max-width: none !important;
    }
    
    
    /* 保存ボタンの無効化スタイル */
    #saveButton.disabled, #saveButtonBottom.disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: #fff !important;
    }
    
    #saveButton.disabled:hover, #saveButtonBottom.disabled:hover {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: #fff !important;
    }
    
    /* 有効な保存ボタンのホバー効果 */
    #saveButton:not(.disabled):hover, #saveButtonBottom:not(.disabled):hover {
        background-color: #0056b3;
        border-color: #0056b3;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    /* DataTablesの件数表示と検索ボックスの間隔調整 */
    .dataTables_length {
        margin-bottom: 15px !important;
    }
    
    .dataTables_filter {
        text-align: center !important;
        display: block !important;
        width: 100% !important;
        margin: 15px 0 10px 0 !important;
        float: none !important;
        padding-left: 20px !important;
    }
    
    .dataTables_filter input {
        display: inline-block;
        margin: 0 auto;
        width: 300px;
    }
    
    /* DataTablesの情報表示とページネーションの中央寄せ */
    .dataTables_info {
        text-align: center !important;
        display: block !important;
        width: 100% !important;
        margin: 10px 0 !important;
    }
    
    .dataTables_paginate {
        text-align: center !important;
        display: block !important;
        width: 100% !important;
        margin: 10px 0 !important;
        float: none !important;
    }
    
    .dataTables_paginate .paginate_button {
        display: inline-block;
        margin: 0 2px;
        vertical-align: middle;
    }
    
    /* 変更されたselect要素のスタイル */
    #spiritSheetsTable select.changed-select {
        background-color: #fff3cd !important;
        border-color: #ffc107 !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
    }
    
    #spiritSheetsTable select.changed-select:focus {
        background-color: #fff3cd !important;
        border-color: #ffc107 !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
    }
    
    @media (max-width: 768px) {
        .dataTables_wrapper {
            overflow-x: auto;
        }
        
        #spiritSheetsTable {
            min-width: 1800px;
        }
        
        .dataTables_filter,
        .dataTables_length {
            margin-bottom: 15px;
        }
    }

    
</style>

<script>
// 元のスクリプト部分を以下のように修正してください

$(document).ready(function() {
    // 日本語日付形式のカスタムソート機能を最初に定義
    $.fn.dataTable.ext.type.detect.unshift(function(data) {
        // 日本語日付形式を検出
        if (data && typeof data === 'string') {
            // HTMLタグを除去
            var cleanData = data.replace(/<[^>]*>/g, '').trim();
            
            // 日本語日付形式（YYYY年MM月DD日）をチェック
            if (cleanData.match(/^\d{4}年\d{1,2}月\d{1,2}日$/)) {
                return 'japanese-date';
            }
        }
        return null;
    });

    // 日本語日付のソート関数を定義
    $.fn.dataTable.ext.type.order['japanese-date-pre'] = function(data) {
        if (!data || typeof data !== 'string') {
            return 0;
        }
        
        // HTMLタグを除去
        var cleanData = data.replace(/<[^>]*>/g, '').trim();
        
        if (cleanData === '' || cleanData === '-' || cleanData === 'なし') {
            return 0; // 空の値は最も小さい値として扱う
        }
        
        // 日本語日付形式（YYYY年MM月DD日）をISO形式に変換
        var japaneseDateRegex = /(\d{4})年(\d{1,2})月(\d{1,2})日/;
        var match = cleanData.match(japaneseDateRegex);
        
        if (match) {
            var year = parseInt(match[1]);
            var month = parseInt(match[2]);
            var day = parseInt(match[3]);
            
            // 有効な日付かチェック
            var date = new Date(year, month - 1, day);
            if (date.getFullYear() === year && 
                date.getMonth() === month - 1 && 
                date.getDate() === day) {
                return date.getTime();
            }
        }
        
        // その他の形式の場合
        var normalDate = new Date(cleanData);
        if (!isNaN(normalDate.getTime())) {
            return normalDate.getTime();
        }
        
        console.log('日付変換に失敗:', cleanData);
        return 0;
    };

    // 元のスクリプト部分を以下のように修正してください
    var originalValues = {};
    
    $('#spiritSheetsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/ja.json"
        },
        "pageLength": 50,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "全て"]],
        "order": [[16, "desc"]],
        "ordering": true,
        "searching": true,
        "paging": true,
        "info": true,
        "columnDefs": [
            {
                "targets": [0],
                "orderable": true,
                "searchable": true,
                "type": "num"
            },
            {
                // 日本語日付列を明示的に指定
                "targets": [1, 2, 3, 4, 15, 16],
                "orderable": true,
                "searchable": true,
                "type": "japanese-date"
            },
            {
                "targets": [5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 17],
                "orderable": true,
                "searchable": true,
                "type": "string"
            }
        ],
        "responsive": false,
        "autoWidth": false,
        "scrollX": false,
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"<"text-center"f>>>' +
               '<"row"<"col-sm-12"tr>>' +
               '<"row"<"col-sm-12"<"text-center"i>>>' +
               '<"row"<"col-sm-12"<"text-center"p>>>',
        "pagingType": "full_numbers",
        "initComplete": function() {
            console.log('DataTable initialized with Japanese date sorting');
            
            // 未入金の場合の初期表示設定
            var visibleColumns = <?php echo json_encode($defaultVisibleColumns); ?>;
            var table = $('#spiritSheetsTable').DataTable();
            
            // 全列を一度非表示にしてから、表示する列のみ表示
            table.columns().visible(false);
            for (var i = 0; i < visibleColumns.length; i++) {
                table.column(visibleColumns[i]).visible(true);
            }
            
            // 初期値を保存
            saveOriginalValues();
            
            // イベントハンドラーをここで設定
            setupEventHandlers();
            
            // マウスホイールによる横スクロール機能を無効化（全列表示のため）
            // setupHorizontalScroll();
            
            // フォーム変更チェック機能を追加
            setupFormChangeCheck();
        }
    });
    
    // 元の値を保存する関数
    function saveOriginalValues() {
        $('#spiritSheetsTable tbody tr').each(function(index) {
            var $row = $(this);
            var rowId = $row.find('td:first a').attr('href'); // IDリンクをユニークキーとして使用
            
            if (rowId) {
                originalValues[rowId] = {
                    adminStatus: $row.find('select[name="admin_status"] option:selected').text(),
                    memberStatus: $row.find('select[name="member_status"] option:selected').text()
                };
            }
            
            // select要素の元の値をdata属性として保存
            var adminStatusSelect = $row.find('select[name*="admin_status"]');
            if (adminStatusSelect.length > 0) {
                adminStatusSelect.data('original-value', adminStatusSelect.val());
            }
            
            var memberStatusSelect = $row.find('select[name*="member_status"]');
            if (memberStatusSelect.length > 0) {
                memberStatusSelect.data('original-value', memberStatusSelect.val());
            }
        });
        console.log('元の値を保存:', originalValues);
    }
    
    // 列の表示/非表示制御
    var table = $('#spiritSheetsTable').DataTable();
    
    // イベントハンドラー設定関数
    function setupEventHandlers() {
        console.log('イベントハンドラーを設定中...');
        
        // チェックボックスの変更イベント
        $('.column-checkboxes input[type="checkbox"]').on('change', function() {
            var columnIndex = parseInt($(this).data('column'));
            var isVisible = $(this).is(':checked');
            
            if (isVisible) {
                table.column(columnIndex).visible(true);
            } else {
                table.column(columnIndex).visible(false);
            }
        });
        
        // 全て表示ボタン
        $('#showAllColumns').on('click', function() {
            $('.column-checkboxes input[type="checkbox"]').prop('checked', true);
            table.columns().visible(true);
        });
        
        // 全て非表示ボタン
        $('#hideAllColumns').on('click', function() {
            $('.column-checkboxes input[type="checkbox"]').prop('checked', false);
            table.columns().visible(false);
        });
        
        // 条件クリアボタン
        $('#clearFilters').on('click', function(e) {
            e.preventDefault();
            console.log('条件クリアボタンがクリックされました');
            clearCustomFilters();
        });
        
        // セレクトボックスと日付入力の変更時に自動でフィルター適用
        $('#statusFilter, #memberStatusFilter, #paymentTypeFilter, #categoryFilter').on('change', function() {
            console.log('ステータスフィルターが変更されました');
            // クリック状態をクリア
            $('#spiritSheetsTable tbody tr').removeClass('clicked');
            $.fn.dataTable.ext.search.pop();
            applyCustomFilters();
        });
        
        // テーブル内のselectボックスが変更された時の処理を削除
        // フィルター処理は行わず、元の値を保持
        $('#spiritSheetsTable tbody').on('change', 'select[name="admin_status"], select[name="member_status"]', function() {
            console.log('テーブル内のselectが変更されました（フィルター処理なし）');
            // ここではフィルター処理を行わない
            // 保存処理などが必要な場合はここに追加
        });
        
        // 行のクリックイベント（リンククリック時）
        $('#spiritSheetsTable tbody').on('click', 'tr', function(e) {
            // リンクがクリックされた場合は行のクリック状態を更新
            if ($(e.target).is('a') || $(e.target).closest('a').length > 0) {
                // 他の行のクリック状態をクリア
                $('#spiritSheetsTable tbody tr').removeClass('clicked');
                // 現在の行にクリック状態を追加
                $(this).addClass('clicked');
                console.log('行がクリックされました:', $(this).find('td:first a').attr('href'));
            }
        });
        
        // リンククリック時の処理
        $('#spiritSheetsTable tbody').on('click', 'a', function(e) {
            // リンクのクリック状態を更新
            var $row = $(this).closest('tr');
            $('#spiritSheetsTable tbody tr').removeClass('clicked');
            $row.addClass('clicked');
            console.log('リンクがクリックされました:', $(this).attr('href'));
        });
        
        // 日付入力の変更時に自動でフィルター適用
        $('#paymentDateFrom, #paymentDateTo, #requestDateFrom, #requestDateTo, #confirmDateFrom, #confirmDateTo, #executionDateFrom, #executionDateTo').on('change input', function() {
            console.log('日付フィルターが変更されました');
            // クリック状態をクリア
            $('#spiritSheetsTable tbody tr').removeClass('clicked');
            $.fn.dataTable.ext.search.pop();
            applyCustomFilters();
        });
    }
    
    // 日付を比較する関数
    function compareDate(dateString, fromDate, toDate) {
        if (!dateString || dateString.trim() === '') {
            return true;
        }
        
        var convertedDate = convertJapaneseDateToISO(dateString);
        if (!convertedDate) {
            console.log('日付変換に失敗:', dateString);
            return true;
        }
        
        console.log('日付比較:', {
            original: dateString,
            converted: convertedDate,
            fromDate: fromDate,
            toDate: toDate
        });
        
        // 日付文字列を直接比較（YYYY-MM-DD形式）
        var dateStr = convertedDate;
        var fromStr = fromDate;
        var toStr = toDate;
        
        // 開始日の比較
        if (fromStr && dateStr < fromStr) {
            console.log('開始日より前:', dateStr, '<', fromStr);
            return false;
        }
        
        // 終了日の比較
        if (toStr && dateStr > toStr) {
            console.log('終了日より後:', dateStr, '>', toStr);
            return false;
        }
        
        console.log('日付範囲内:', dateStr, 'between', fromStr, 'and', toStr);
        return true;
    }
    
    // 日本語日付をISO形式に変換
    function convertJapaneseDateToISO(dateString) {
        if (!dateString || dateString.trim() === '') return null;

        console.log('変換前の日付文字列:', dateString);
        
        // 日本語形式：2025年9月18日、2025年09月18日
        var match1 = dateString.match(/(\d{4})年(\d{1,2})月(\d{1,2})日/);
        if (match1) {
            var year = match1[1];
            var month = match1[2].padStart(2, '0');
            var day = match1[3].padStart(2, '0');
            var result = year + '-' + month + '-' + day;
            console.log('日本語形式変換結果:', result);
            return result;
        }
        
        // スラッシュ形式：2025/9/18
        var match2 = dateString.match(/(\d{4})\/(\d{1,2})\/(\d{1,2})/);
        if (match2) {
            var year = match2[1];
            var month = match2[2].padStart(2, '0');
            var day = match2[3].padStart(2, '0');
            return year + '-' + month + '-' + day;
        }
        
        // 既にISO形式
        if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
            return dateString;
        }
        
        console.log('変換できませんでした:', dateString);
        return null;
    }
    
    // フィルター機能（元の値を使用してフィルタリング）
    function applyCustomFilters() {
        var statusFilter = $('#statusFilter').val();
        var memberStatusFilter = $('#memberStatusFilter').val();
        var paymentTypeFilter = $('#paymentTypeFilter').val();
        var categoryFilter = $('#categoryFilter').val();
        var paymentDateFrom = $('#paymentDateFrom').val();
        var paymentDateTo = $('#paymentDateTo').val();
        var requestDateFrom = $('#requestDateFrom').val();
        var requestDateTo = $('#requestDateTo').val();
        var confirmDateFrom = $('#confirmDateFrom').val();
        var confirmDateTo = $('#confirmDateTo').val();
        var executionDateFrom = $('#executionDateFrom').val();
        var executionDateTo = $('#executionDateTo').val();
        
        console.log('フィルター条件:', {
            statusFilter: statusFilter,
            memberStatusFilter: memberStatusFilter,
            paymentTypeFilter: paymentTypeFilter,
            categoryFilter: categoryFilter,
            paymentDateFrom: paymentDateFrom,
            paymentDateTo: paymentDateTo,
            requestDateFrom: requestDateFrom,
            requestDateTo: requestDateTo,
            confirmDateFrom: confirmDateFrom,
            confirmDateTo: confirmDateTo,
            executionDateFrom: executionDateFrom,
            executionDateTo: executionDateTo
        });
        
        // カスタム検索関数
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var statusMatch = true;
            var memberStatusMatch = true;
            var paymentTypeMatch = true;
            var categoryMatch = true;
            var paymentDateMatch = true;
            var requestDateMatch = true;
            var confirmDateMatch = true;
            var executionDateMatch = true;
            
            // 行の元のIDを取得
            var $row = table.row(dataIndex).node();
            var rowId = null;
            if ($row) {
                var $idLink = $($row).find('td:first a');
                if ($idLink.length > 0) {
                    rowId = $idLink.attr('href');
                }
            }
            
            // ステータスフィルター（列7）- 元の値を使用
            if (statusFilter !== '') {
                var statusValue = '';
                if (rowId && originalValues[rowId]) {
                    statusValue = originalValues[rowId].adminStatus;
                } else {
                    // 元の値が見つからない場合は現在の値を使用
                    statusValue = data[7];
                    if (statusValue && statusValue.indexOf('<select') !== -1) {
                        try {
                            if ($row) {
                                var $select = $($row).find('select[name="admin_status"]');
                                if ($select.length > 0) {
                                    statusValue = $select.find('option:selected').text();
                                }
                            }
                        } catch (e) {
                            console.log('ステータスフィルター処理エラー:', e);
                        }
                    }
                }
                statusMatch = (statusValue && statusValue.indexOf(statusFilter) !== -1);
                console.log('ステータスフィルター:', statusFilter, '元の値:', statusValue, 'マッチ:', statusMatch);
            }
            
            // 会員ステータスフィルター（列8）- 元の値を使用
            if (memberStatusFilter !== '') {
                var memberStatusValue = '';
                if (rowId && originalValues[rowId]) {
                    memberStatusValue = originalValues[rowId].memberStatus;
                } else {
                    // 元の値が見つからない場合は現在の値を使用
                    memberStatusValue = data[8];
                    if (memberStatusValue && memberStatusValue.indexOf('<select') !== -1) {
                        try {
                            if ($row) {
                                var $select = $($row).find('select[name="member_status"]');
                                if ($select.length > 0) {
                                    memberStatusValue = $select.find('option:selected').text();
                                }
                            }
                        } catch (e) {
                            console.log('会員ステータスフィルター処理エラー:', e);
                        }
                    }
                }
                memberStatusMatch = (memberStatusValue && memberStatusValue.indexOf(memberStatusFilter) !== -1);
                console.log('会員ステータスフィルター:', memberStatusFilter, '元の値:', memberStatusValue, 'マッチ:', memberStatusMatch);
            }
            
            // 決済方法フィルター（列6）
            if (paymentTypeFilter !== '') {
                var paymentTypeValue = data[6];
                paymentTypeMatch = (paymentTypeValue.indexOf(paymentTypeFilter) !== -1);
            }
            
            // 依頼内容フィルター（列5）
            if (categoryFilter !== '') {
                var categoryValue = data[5];
                categoryMatch = (categoryValue.indexOf(categoryFilter) !== -1);
            }
            
            // 決済日フィルター（列1）
            if (paymentDateFrom !== '' || paymentDateTo !== '') {
                var paymentDateValue = data[1];
                if (paymentDateValue && paymentDateValue.trim() !== '') {
                    paymentDateMatch = compareDate(paymentDateValue, paymentDateFrom, paymentDateTo);
                } else {
                    paymentDateMatch = false; // 空の場合は除外
                }
            }
            
            // 依頼日フィルター（列3）
            if (requestDateFrom !== '' || requestDateTo !== '') {
                var requestDateValue = data[3];
                if (requestDateValue && requestDateValue.trim() !== '') {
                    requestDateMatch = compareDate(requestDateValue, requestDateFrom, requestDateTo);
                } else {
                    requestDateMatch = false; // 空の場合は除外
                }
            }
            
            // 依頼確定日フィルター（列4）
            if (confirmDateFrom !== '' || confirmDateTo !== '') {
                var confirmDateValue = data[4];
                if (confirmDateValue && confirmDateValue.trim() !== '') {
                    confirmDateMatch = compareDate(confirmDateValue, confirmDateFrom, confirmDateTo);
                } else {
                    confirmDateMatch = false; // 空の場合は除外
                }
            }
            
            // 実行日フィルター（列15）
            if (executionDateFrom !== '' || executionDateTo !== '') {
                var executionDateValue = data[15];
                if (executionDateValue && executionDateValue.trim() !== '') {
                    executionDateMatch = compareDate(executionDateValue, executionDateFrom, executionDateTo);
                } else {
                    executionDateMatch = false; // 空の場合は除外
                }
            }
            
            return statusMatch && memberStatusMatch && paymentTypeMatch && categoryMatch && paymentDateMatch && requestDateMatch && confirmDateMatch && executionDateMatch;
        });
        
        table.draw();
        
        // 絞り込み状態をthに反映
        updateFilteredHeaders();
    }
    
    // 絞り込み状態をthに反映する関数
    function updateFilteredHeaders() {
        // 全てのthからfilteredクラスを削除
        $('#spiritSheetsTable th').removeClass('filtered');
        
        var statusFilter = $('#statusFilter').val();
        var memberStatusFilter = $('#memberStatusFilter').val();
        var paymentTypeFilter = $('#paymentTypeFilter').val();
        var categoryFilter = $('#categoryFilter').val();
        var paymentDateFrom = $('#paymentDateFrom').val();
        var paymentDateTo = $('#paymentDateTo').val();
        var requestDateFrom = $('#requestDateFrom').val();
        var requestDateTo = $('#requestDateTo').val();
        var confirmDateFrom = $('#confirmDateFrom').val();
        var confirmDateTo = $('#confirmDateTo').val();
        var executionDateFrom = $('#executionDateFrom').val();
        var executionDateTo = $('#executionDateTo').val();
        
        // 実際に表示されているthの配列を取得
        var visibleHeaders = $('#spiritSheetsTable thead th:visible').toArray();
        
        // 列名とインデックスのマッピング
        var columnMapping = {
            'ID': 0,
            '決済日': 1,
            '振込予定日': 2,
            '依頼日': 3,
            '依頼確定日': 4,
            '依頼内容': 5,
            '決済方法': 6,
            'ステータス': 7,
            '会員ステータス': 8,
            '申込者名': 9,
            'フリガナ': 10,
            '対象者': 11,
            'メール': 12,
            '連絡先': 13,
            'グループ': 14,
            '実行日': 15,
            '最終変更日': 16,
            '最終変更者': 17
        };
        
        // 実際に表示されている列のインデックスを取得する関数
        function getVisibleColumnIndex(columnName) {
            for (var i = 0; i < visibleHeaders.length; i++) {
                if ($(visibleHeaders[i]).text().trim() === columnName) {
                    return i;
                }
            }
            return -1;
        }
        
        // ステータスフィルターが適用されている場合
        if (statusFilter !== '') {
            var index = getVisibleColumnIndex('ステータス');
            if (index !== -1) {
                $(visibleHeaders[index]).addClass('filtered');
            }
        }
        
        // 会員ステータスフィルターが適用されている場合
        if (memberStatusFilter !== '') {
            var index = getVisibleColumnIndex('会員ステータス');
            if (index !== -1) {
                $(visibleHeaders[index]).addClass('filtered');
            }
        }
        
        // 決済方法フィルターが適用されている場合
        if (paymentTypeFilter !== '') {
            var index = getVisibleColumnIndex('決済方法');
            if (index !== -1) {
                $(visibleHeaders[index]).addClass('filtered');
            }
        }
        
        // 依頼内容フィルターが適用されている場合
        if (categoryFilter !== '') {
            var index = getVisibleColumnIndex('依頼内容');
            if (index !== -1) {
                $(visibleHeaders[index]).addClass('filtered');
            }
        }
        
        // 決済日フィルターが適用されている場合
        if (paymentDateFrom !== '' || paymentDateTo !== '') {
            var index = getVisibleColumnIndex('決済日');
            if (index !== -1) {
                $(visibleHeaders[index]).addClass('filtered');
            }
        }
        
        // 依頼日フィルターが適用されている場合
        if (requestDateFrom !== '' || requestDateTo !== '') {
            var index = getVisibleColumnIndex('依頼日');
            if (index !== -1) {
                $(visibleHeaders[index]).addClass('filtered');
            }
        }
        
        // 依頼確定日フィルターが適用されている場合
        if (confirmDateFrom !== '' || confirmDateTo !== '') {
            var index = getVisibleColumnIndex('依頼確定日');
            if (index !== -1) {
                $(visibleHeaders[index]).addClass('filtered');
            }
        }
        
        // 実行日フィルターが適用されている場合
        if (executionDateFrom !== '' || executionDateTo !== '') {
            var index = getVisibleColumnIndex('実行日');
            if (index !== -1) {
                $(visibleHeaders[index]).addClass('filtered');
            }
        }
        
        console.log('絞り込み状態をthに反映しました（表示されている列のみ）');
    }
    
    // フィルタークリア関数
    function clearCustomFilters() {
        // クリック状態をクリア
        $('#spiritSheetsTable tbody tr').removeClass('clicked');
        $.fn.dataTable.ext.search.pop(); // カスタム検索関数を削除
        $('#statusFilter').val('');
        $('#memberStatusFilter').val('');
        $('#paymentTypeFilter').val('');
        $('#categoryFilter').val('');
        $('#paymentDateFrom').val('');
        $('#paymentDateTo').val('');
        $('#requestDateFrom').val('');
        $('#requestDateTo').val('');
        $('#confirmDateFrom').val('');
        $('#confirmDateTo').val('');
        $('#executionDateFrom').val('');
        $('#executionDateTo').val('');
        table.draw();
        
        // 絞り込み状態をthに反映
        updateFilteredHeaders();
    }
    
    // 元の値を更新する関数（保存後などに呼び出す）
    function updateOriginalValues() {
        saveOriginalValues();
        // フィルターが適用されている場合は再適用
        if ($('#statusFilter').val() !== '' || $('#memberStatusFilter').val() !== '' || 
            $('#paymentTypeFilter').val() !== '' || $('#categoryFilter').val() !== '' ||
            $('#paymentDateFrom').val() !== '' || $('#paymentDateTo').val() !== '' ||
            $('#requestDateFrom').val() !== '' || $('#requestDateTo').val() !== '' ||
            $('#confirmDateFrom').val() !== '' || $('#confirmDateTo').val() !== '' ||
            $('#executionDateFrom').val() !== '' || $('#executionDateTo').val() !== '') {
            $.fn.dataTable.ext.search.pop();
            applyCustomFilters();
        }
    }
    
    // マウスホイールによる横スクロール機能を設定する関数
    function setupHorizontalScroll() {
        var $wrapper = $('.dataTables_wrapper');
        var isScrolling = false;
        
        // テーブル部分のみでマウスホイールイベントを追加
        $('#spiritSheetsTable').on('wheel', function(e) {
            // Shiftキーが押されている場合のみ横スクロール
            if (e.shiftKey) {
                e.preventDefault();
                
                if (!isScrolling) {
                    isScrolling = true;
                    
                    var delta = e.originalEvent.deltaY || e.originalEvent.deltaX || 0;
                    var scrollAmount = delta > 0 ? 100 : -100;
                    
                    // 親要素の横スクロールを実行
                    $wrapper[0].scrollLeft += scrollAmount;
                    
                    // スクロール完了後にフラグをリセット
                    setTimeout(function() {
                        isScrolling = false;
                    }, 50);
                }
            }
        });
        
        console.log('テーブル部分のみでマウスホイールによる横スクロール機能を設定しました');
    }
    
    // フォーム変更チェック機能を設定する関数
    function setupFormChangeCheck() {
        var $form = $('#statusUpdateForm');
        var $saveButton = $('#saveButton');
        var $saveButtonBottom = $('#saveButtonBottom');
        var hasChanges = false;
        var changedElements = new Set(); // 変更された要素を追跡
        
        // 保存ボタンの初期状態を設定（変更なしの場合は無効化）
        $saveButton.prop('disabled', true).addClass('disabled').text('保存（変更なし）');
        $saveButtonBottom.prop('disabled', true).addClass('disabled').text('保存（変更なし）');
        
        // select要素の変更を監視
        $form.find('select').on('change', function() {
            var $this = $(this);
            var originalValue = $this.data('original-value');
            var currentValue = $this.val();
            
            if (originalValue !== currentValue) {
                changedElements.add(this.name); // 変更された要素のnameを記録
                $this.addClass('changed-select'); // 変更されたselectにクラスを追加
            } else {
                changedElements.delete(this.name); // 元に戻った場合は削除
                $this.removeClass('changed-select'); // 変更クラスを削除
            }
            
            hasChanges = changedElements.size > 0;
            updateSaveButtons();
        });
        
        // フォーム送信時のチェックと処理
        $form.on('submit', function(e) {
            if (!hasChanges || changedElements.size === 0) {
                e.preventDefault();
                alert('変更がありません。保存する必要はありません。');
                return false;
            }
            
            // 変更がある場合のみ確認ダイアログを表示
            if (!confirm('変更を保存しますか？\n変更された項目数: ' + changedElements.size)) {
                e.preventDefault();
                return false;
            }
            
            // 変更されていない要素をフォームから除外
            $form.find('select').each(function() {
                var $this = $(this);
                if (!changedElements.has(this.name)) {
                    // 変更されていない要素は無効化して送信対象から除外
                    $this.prop('disabled', true);
                }
            });
        });
        
        // 保存ボタンの状態を更新する関数
        function updateSaveButtons() {
            if (hasChanges && changedElements.size > 0) {
                $saveButton.prop('disabled', false).removeClass('disabled').text('保　存 (' + changedElements.size + '件変更)');
                $saveButtonBottom.prop('disabled', false).removeClass('disabled').text('保　存 (' + changedElements.size + '件変更)');
            } else {
                $saveButton.prop('disabled', true).addClass('disabled').text('保　存（未変更）');
                $saveButtonBottom.prop('disabled', true).addClass('disabled').text('保　存（未変更）');
            }
        }
        
        console.log('フォーム変更チェック機能を設定しました');
    }
    
    // グローバルスコープに関数を公開（必要に応じて）
    window.updateOriginalValues = updateOriginalValues;
});

// アコーディオン開閉関数
function toggleAccordion(accordionId) {
    var content = document.getElementById(accordionId);
    var icon = document.getElementById(accordionId + 'Icon');
    
    if (content.classList.contains('active')) {
        content.classList.remove('active');
        icon.classList.remove('rotated');
    } else {
        content.classList.add('active');
        icon.classList.add('rotated');
    }
}
</script>