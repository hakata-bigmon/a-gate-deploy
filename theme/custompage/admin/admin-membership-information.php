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
    require_once (dirname(__FILE__)."/../../class/spiritNewsClass.php");
    require_once (dirname(__FILE__)."/../../class/mailTextClass.php");

    $category_type ="";
    $payment = "";
    $teacher_check = "";

    //var_dump($_POST);

    // 流入データ
    $spirit_sheet_data = new spiritSheetClass(); //管理データ
    $spiritType = new SpiritTypeClass(); //管理データ
    $userClass = new SpiritUserClass();
    $newsClass = new SpiritNewsClass();//ニュースデータ
    $mailText = new MailTextClass();

    $check_type = isset($_GET["type"]) ? $_GET["type"] : ""; 


    $membership_information_type = 1;


    //未入力
    if($check_type == "1")
    {
        $membership_information_type = "";
    }
    //再申請
    if($check_type == "2")
    {
        $membership_information_type = 3;
    }



    if(isset($_POST["save_action"]))
    {

        if($_POST["save_action"] == "status_update")
        {
            $user_data = get_user_by('id',$_POST["user_id"]);
            update_user_meta($_POST["user_id"],"user_date_complete",2);
            update_user_meta($_POST["user_id"],"last_up_date",date("Y-m-d H:i:s"));
            update_user_meta($_POST["user_id"],"last_up_date_id",get_current_user_id());

            //差し戻しデータを取得
            $mail_text_array = $mailText->sendCommonMail( $_POST["user_id"] , 9941,true,true);

            $unix_time_array["mail_send_unixtime"] = $_POST["update_unixtime"];
            //ニュースデータの作成
            $news_id = $newsClass->createNewsDataMail($_POST["user_id"],$unix_time_array,$mail_text_array);

            //お知らせが作成されていなかったので差し戻しに変更（二重送信防止）
            if($news_id != "")
            {
                //メールを送信する
                $mailText->sendCommonMail( $_POST["user_id"] , 9941,false);
            }

            //ここでリロードを入れる
            echo "<script>window.location.href='" . getURLSetSlag("admin-membership-information") . "';</script>";
        }
        else if($_POST["save_action"] == "status_update_back") //差し戻し
        {


            $user_data = get_user_by('id',$_POST["user_id"]);
            update_user_meta($_POST["user_id"],"user_date_complete",3);
            update_user_meta($_POST["user_id"],"membersip_back_reason",$_POST["back_reason"]);
            update_user_meta($_POST["user_id"],"last_up_date",date("Y-m-d H:i:s"));
            update_user_meta($_POST["user_id"],"last_up_date_id",get_current_user_id());

            //差し戻しデータを取得
            $mail_text_array = $mailText->sendMemberStatusUpdateMail( $_POST["user_id"] , 9935,true,true);

            $unix_time_array["mail_send_unixtime"] = $_POST["back_reason_unixtime"];
            //ニュースデータの作成
            $news_id = $newsClass->createNewsDataMail($_POST["user_id"],$unix_time_array,$mail_text_array);

            //お知らせが作成されていなかったので差し戻しに変更（二重送信防止）
            if($news_id != "")
            {
                //メールを送信する
                $mailText->sendMemberStatusUpdateMail( $_POST["user_id"] , 9935,false);
            }

            //ここでリロードを入れる
            echo "<script>window.location.href='" . getURLSetSlag("admin-membership-information") . "';</script>";
        }
     
    }


    //会員情報申請中のユーザーを取得
   $membership_information_users = get_users(array(
        'meta_query' => array(
            array(
                'key' => 'user_date_complete',
                'value' => $membership_information_type,
            )
        )
    ));
   
    //var_dump($category_array);
?>



<div class="admin-profile-edit-area">

 
    <div class="admin-profile-edit-title-box">
        <div class="admin-exorcism-menu-title" style="text-align: center;">会員情報申請一覧</div>
    </div>

    <div class="payment-filter-buttons">
        <?php $type = isset($_GET["type"]) ? $_GET["type"] : ""; ?>
        
        <?php if($type == ""): ?>
            <span class="payment-btn unpaid-btn disabled">確認申請</span>
        <?php else: ?>
            <a href="<?php echo getURLSetSlag("admin-membership-information"); ?>" class="payment-btn unpaid-btn">確認申請</a>
        <?php endif; ?>
        
        <?php if($type == "1"): ?>
            <span class="payment-btn paid-btn disabled">未入力</span>
        <?php else: ?>
            <a href="<?php echo getURLSetSlag("admin-membership-information"); ?>?type=1" class="payment-btn paid-btn">未入力</a>
        <?php endif; ?>
        
        <?php if($type == "2"): ?>
            <span class="payment-btn all-btn disabled">再申請</span>
        <?php else: ?>
            <a href="<?php echo getURLSetSlag("admin-membership-information"); ?>?type=2" class="payment-btn all-btn">再申請</a>
        <?php endif; ?>
    </div>

    <div>
        
        <table id="spiritSheetsTable" class="table table-striped table-hover">

            <thead>
                <tr>
                    <th>状態</th>
                    <th>確認</th>
                    <?php if($check_type == ""){?>
                        <th>承認</th>
                        <th>差戻</th>
                    <?php }?>
                    <th>ID</th>
                    <th>名前</th>
                    <th>フリガナ</th>
                    <th>メール</th>
                    <th>連絡先</th>
                    <th>差し戻し理由</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach($membership_information_users as $key => $value){ ?>


                <?php 
                

                    $user_id = $value->ID;

                    $user_data = get_user_by('id',$user_id);

                    //var_dump($user_data);
                    
                    
                ?>

                <tr id="user_row_<?php echo $user_id; ?>">
                    <td>
                        <?php if($user_data->user_date_complete == 1){?>
                            <span style="color: red;">申請中</span>
                        <?php }else if($user_data->user_date_complete == 2){?>
                            <span style="color: green;">承認</span>
                        <?php }else if($user_data->user_date_complete == 3){?>
                            <span style="color: blue;">再申請中</span>
                        <?php }?>
                        <?php if($user_data->user_date_complete == ""){?>
                            <span style="color: gray;">未入力</span>
                        <?php }?>
                    </td>
                    <td>
                        <form id="statusUpdateForm" action="<?php echo getURLSetSlag("users/user-acount-edit/"); ?>?check_user=<?php echo $user_id; ?>" method="post" target="_blank">
                            <button type="submit" class="btn btn-info btn-sm check-btn" data-user-id="<?php echo $user_id; ?>">確認</button>
                        </form>
                    </td>
                    <?php if($check_type == ""){?>
                        <td>
                            <form id="approveForm_<?php echo $user_id; ?>" action="<?php echo getURLSetSlag("admin-membership-information"); ?>" method="post">
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                <input type="hidden" name="save_action" value="status_update">
                                <input type="hidden" name="update_unixtime" value="<?php echo time(); ?>">
                                <button type="button" class="btn btn-success btn-sm approve-btn" data-user-id="<?php echo $user_id; ?>" data-user-name="<?php echo $user_data->last_name . $user_data->first_name; ?>">承認</button>
                            </form>
                        </td>
                        <td>
                            <form id="rejectForm_<?php echo $user_id; ?>" action="<?php echo getURLSetSlag("admin-membership-information"); ?>" method="post">
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                <input type="hidden" name="save_action" value="status_update_back">
                                <input type="hidden" name="back_reason" id="back_reason_hidden_<?php echo $user_id; ?>" value="">
                                <input type="hidden" name="back_reason_unixtime" value="<?php echo time(); ?>">
                                <button type="button" class="btn btn-warning btn-sm reject-btn" data-user-id="<?php echo $user_id; ?>">差戻</button>
                            </form>
                        </td>
                    <?php }?>
                    <td>
                        <a href="<?php echo home_url(); ?>//admin-member-edit/?user_id=<?php echo $user_id; ?>" target="_blank"><?php echo $user_id; ?></a>
                    </td>
                    
                
                    <td> <a href="<?php echo home_url(); ?>/admin-member-edit/?user_id=<?php echo $user_id; ?>" target="_blank"><?php echo $user_data->last_name .  $user_data->first_name; ?></a></td>
                    <td><?php echo $user_data->last_name_kana .  $user_data->first_name_kana; ?></td>

                
                    <td><?php echo $user_data->user_email; ?></td>
                    <?php 
                    
                        $user_tel1 = get_user_meta($user_id,'billing_phone',true);
                        $user_tel2 = get_user_meta($user_id,'billing_phone2',true);
                        $user_tel3 = get_user_meta($user_id,'billing_phone3',true);
                    
                    ?>
                    <td><?php echo $user_tel1 . "-" . $user_tel2 . "-" . $user_tel3; ?></td>

                    <td>
                        <?php if($check_type == ""){?>
                            <textarea name="back_reason_display" id="back_reason_<?php echo $user_id; ?>" rows="3" cols="50"><?php echo $user_data->membersip_back_reason; ?></textarea>
                        <?php }else{?>
                            <?php echo $user_data->back_reason; ?>
                        <?php }?>
                    </td>
                    
                </tr>
            <?php } ?>

            </tbody>
        </table>

        
    </div>


</div>

<!-- 承認確認モーダル -->
<div id="approveModal" class="approve-modal">
    <div class="approve-modal-content">
        <div class="approve-modal-header">
            <h3>承認確認</h3>
            <span class="approve-modal-close">&times;</span>
        </div>
        <div class="approve-modal-body">
            <p id="approveConfirmMessage"></p>
        </div>
        <div class="approve-modal-footer">
            <button class="btn btn-secondary" id="approveCancel">キャンセル</button>
            <button class="btn btn-success" id="approveConfirm">承認</button>
        </div>
    </div>
</div>

<!-- 差戻確認モーダル -->
<div id="rejectModal" class="reject-modal">
    <div class="reject-modal-content">
        <div class="reject-modal-header">
            <h3>差戻確認</h3>
            <span class="reject-modal-close">&times;</span>
        </div>
        <div class="reject-modal-body">
            <p id="rejectConfirmMessage" style="display: none;">本当に差し戻しますか？</p>
            <p class="reject-modal-error" id="rejectError" style="display: none; color: red; font-weight: bold;">差し戻し理由を入力してください。</p>
        </div>
        <div class="reject-modal-footer">
            <button class="btn btn-secondary" id="rejectCancel">キャンセル</button>
            <button class="btn btn-warning" id="rejectConfirm" style="display: none;">差し戻す</button>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    let currentUserId = null;
    let currentUserName = '';
    
    // 確認ボタンクリック時（行の色を変更）
    $('.check-btn').on('click', function() {
        // 以前に選択されていた行の色をリセット
        $('.row-checked').removeClass('row-checked');
        
        // 新しい行の色を変更
        const userId = $(this).data('user-id');
        $('#user_row_' + userId).addClass('row-checked');
    });
    
    // 承認ボタンクリック時
    $('.approve-btn').on('click', function() {
        currentUserId = $(this).data('user-id');
        currentUserName = $(this).data('user-name');
        
        // メッセージを設定
        $('#approveConfirmMessage').text(currentUserName + '　様の会員情報を承認しても宜しいですか？');
        
        // モーダル表示
        $('#approveModal').fadeIn();
    });
    
    // 承認確定ボタン
    $('#approveConfirm').on('click', function() {
        if (currentUserId) {
            // 行の色をリセット
            $('#user_row_' + currentUserId).removeClass('row-checked');
            // フォーム送信
            $('#approveForm_' + currentUserId).submit();
        }
    });
    
    // 承認キャンセルボタン
    $('#approveCancel').on('click', function() {
        $('#approveModal').fadeOut();
        currentUserId = null;
        currentUserName = '';
    });
    
    // 承認モーダルの×ボタン
    $('.approve-modal-close').on('click', function() {
        $('#approveModal').fadeOut();
        currentUserId = null;
        currentUserName = '';
    });
    
    // 承認モーダル外クリックで閉じる
    $(window).on('click', function(e) {
        if ($(e.target).is('#approveModal')) {
            $('#approveModal').fadeOut();
            currentUserId = null;
            currentUserName = '';
        }
    });
    
    // 差戻ボタンクリック時
    $('.reject-btn').on('click', function() {
        currentUserId = $(this).data('user-id');
        const backReason = $('#back_reason_' + currentUserId).val().trim();
        
        // 差し戻し理由が未入力の場合
        if (backReason === '') {
            $('#rejectError').show();
            $('#rejectConfirmMessage').hide();
            $('#rejectConfirm').hide();
            $('#rejectModal').fadeIn();
            return;
        }
        
        // 理由が入力されている場合
        $('#rejectError').hide();
        $('#rejectConfirmMessage').show();
        $('#rejectConfirm').show();
        $('#rejectModal').fadeIn();
    });
    
    // 差し戻し確定ボタン
    $('#rejectConfirm').on('click', function() {
        if (currentUserId) {
            const backReason = $('#back_reason_' + currentUserId).val().trim();
            
            // hiddenフィールドに値をセット
            $('#back_reason_hidden_' + currentUserId).val(backReason);
            
            // 行の色をリセット
            $('#user_row_' + currentUserId).removeClass('row-checked');
            
            // フォーム送信
            $('#rejectForm_' + currentUserId).submit();
        }
    });
    
    // 差戻キャンセルボタン
    $('#rejectCancel').on('click', function() {
        $('#rejectModal').fadeOut();
        $('#rejectError').hide();
        $('#rejectConfirmMessage').hide();
        $('#rejectConfirm').hide();
        currentUserId = null;
    });
    
    // 差戻モーダルの×ボタン
    $('.reject-modal-close').on('click', function() {
        $('#rejectModal').fadeOut();
        $('#rejectError').hide();
        $('#rejectConfirmMessage').hide();
        $('#rejectConfirm').hide();
        currentUserId = null;
    });
    
    // 差戻モーダル外クリックで閉じる
    $(window).on('click', function(e) {
        if ($(e.target).is('#rejectModal')) {
            $('#rejectModal').fadeOut();
            $('#rejectError').hide();
            $('#rejectConfirmMessage').hide();
            $('#rejectConfirm').hide();
            currentUserId = null;
        }
    });
});
</script>

<style>

body {
    overflow-x: auto !important;
    min-width: 100%;
}
.admin-profile-edit-area {
    max-width: 1300px!important;
    margin-left: 100px!important;
}
    .payment-filter-buttons {
        display: flex;
        gap: 15px;
        
        justify-content: flex-start;
        flex-wrap: wrap;
        margin-top: 10px;
        margin-bottom: 17px;
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
    
    .btn-info {
        background: #9b59b6;
        border-color: #9b59b6;
        color: white;
    }
    
    .btn-info:hover {
        background: #8e44ad;
        border-color: #8e44ad;
    }
    
    .btn-success {
        background: #ff6b6b;
        border-color: #ff6b6b;
        color: white;
    }
    
    .btn-success:hover {
        background: #ff5252;
        border-color: #ff5252;
    }
    
    .btn-warning {
        background: #339af0;
        border-color: #339af0;
        color: white;
    }
    
    .btn-warning:hover {
        background: #228be6;
        border-color: #228be6;
        color: white;
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
    
    /* 確認ボタンのサイズ調整 */
    .check-btn {
        padding: 1px 8px !important;
        font-size: 12px !important;
        min-width: auto !important;
        width: auto !important;
    }
    
    /* 確認ボタンクリック後の行のスタイル */
    #spiritSheetsTable tbody tr.row-checked {
        background-color: #fff3cd !important;
        border-left: 4px solid #ffc107;
    }
    
    #spiritSheetsTable tbody tr.row-checked:hover {
        background-color: #ffe69c !important;
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
    
    /* 承認確認モーダルのスタイル */
    .approve-modal {
        display: none;
        position: fixed;
        z-index: 10000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }
    
    .approve-modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        animation: modalSlideDown 0.3s ease;
    }
    
    .approve-modal-header {
        padding: 20px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .approve-modal-header h3 {
        margin: 0;
        color: #333;
        font-size: 20px;
        font-weight: 600;
    }
    
    .approve-modal-close {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        line-height: 1;
        transition: color 0.2s ease;
    }
    
    .approve-modal-close:hover,
    .approve-modal-close:focus {
        color: #000;
    }
    
    .approve-modal-body {
        padding: 30px 20px;
    }
    
    .approve-modal-body p {
        margin: 0;
        font-size: 16px;
        color: #333;
    }
    
    .approve-modal-footer {
        padding: 15px 20px;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        border-radius: 0 0 8px 8px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    
    /* 差戻確認モーダルのスタイル */
    .reject-modal {
        display: none;
        position: fixed;
        z-index: 10000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }
    
    .reject-modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        animation: modalSlideDown 0.3s ease;
    }
    
    @keyframes modalSlideDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .reject-modal-header {
        padding: 20px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .reject-modal-header h3 {
        margin: 0;
        color: #333;
        font-size: 20px;
        font-weight: 600;
    }
    
    .reject-modal-close {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        line-height: 1;
        transition: color 0.2s ease;
    }
    
    .reject-modal-close:hover,
    .reject-modal-close:focus {
        color: #000;
    }
    
    .reject-modal-body {
        padding: 30px 20px;
    }
    
    .reject-modal-body p {
        margin: 0 0 15px 0;
        font-size: 16px;
        color: #333;
    }
    
    .reject-modal-footer {
        padding: 15px 20px;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        border-radius: 0 0 8px 8px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    
    .btn-secondary {
        background: #6c757d;
        border-color: #6c757d;
        color: white;
        padding: 8px 16px;
        border: 1px solid;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s ease;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
        border-color: #5a6268;
    }

    
</style>
