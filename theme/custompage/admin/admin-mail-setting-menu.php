<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/mailTextClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
?>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="admin-menu-wrapper">
  <h1 class="admin-menu-title">メール設定メニュー</h1>

  <div class="admin-menu-section">
    <div class="admin-menu-section-title"><span class="material-icons">wifi</span>リモート依頼</div>

    <div style="width: 500px;font-size: 16px; font-weight: 700; color: #dc3545; padding: 8px 12px;border-bottom: 1px solid #dc3545;  border-left: 4px solid #dc3545; margin: 15px 0 10px 0; display: inline-block;">
        <span style="margin-right: 8px;"></span>申込直後
    </div>
    <div class="admin-menu-list">
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_TRANSFER;?>">
        <span class="material-icons">account_balance</span> 銀行振込
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_CONVENIENCE;?>">
        <span class="material-icons">store</span> コンビニ決済
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_CREDIT;?>">
        <span class="material-icons">credit_card</span> クレジットカード
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_E_MONEY;?>">
        <span class="material-icons">smartphone</span> 電子マネー
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_TRANSIT;?>">
        <span class="material-icons">directions_bus</span> 交通系決済
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_CASH;?>">
        <span class="material-icons">payments</span> 現金支払い
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_FREE;?>">
        <span class="material-icons">free_cancellation</span> 無料
      </a>
    </div>

    <div style="width: 500px;font-size: 16px; font-weight: 700; color: #dc3545;  padding: 8px 12px; border-bottom: 1px solid #dc3545; border-left: 4px solid #dc3545; margin: 35px 0 10px 0; display: inline-block;">
        <span style="margin-right: 8px;"></span>申込後
    </div>
    <div class="admin-menu-list">
      <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_COMPLETE_REPORT;?>">
          <span class="material-icons">check_circle</span> 施術完了報告
        </a>
        <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_SHEET_CONFIRM_REPORT;?>">
          <span class="material-icons">check_circle</span> シート確認完了報告
        </a>
        <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_SHEET_RETURN_REPORT;?>">
          <span class="material-icons">assignment_return</span> シート再提出報告
        </a>
        <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_PAYMENT_CONFIRM_REPORT;?>">
          <span class="material-icons">payment</span> 入金確認完了報告
        </a>
        <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_SCHEDULE_DECISION_REPORT;?>">
          <span class="material-icons">event</span> 施術日決定報告
        </a>
    </div>

  </div>

  <div class="admin-menu-section">
    <div class="admin-menu-section-title"><span class="material-icons">healing</span>鑑定</div>
    <div style="width: 500px;font-size: 16px; font-weight: 700; color: #dc3545; padding: 8px 12px;border-bottom: 1px solid #dc3545;  border-left: 4px solid #dc3545; margin: 15px 0 10px 0; display: inline-block;">
        <span style="margin-right: 8px;"></span>申込直後
    </div>
    <div class="admin-menu-list">
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_TRANSFER;?>">
        <span class="material-icons">account_balance</span> 銀行振込
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_CONVENIENCE;?>">
        <span class="material-icons">store</span> コンビニ決済
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_CREDIT;?>">
        <span class="material-icons">credit_card</span> クレジットカード
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_E_MONEY;?>">
        <span class="material-icons">smartphone</span> 電子マネー
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_TRANSIT;?>">
        <span class="material-icons">directions_bus</span> 交通系決済
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_CASH;?>">
        <span class="material-icons">payments</span> 現金支払い
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_FREE;?>">
        <span class="material-icons">free_cancellation</span> 無料
      </a>
    </div>
    <div style="width: 500px;font-size: 16px; font-weight: 700; color: #dc3545;  padding: 8px 12px; border-bottom: 1px solid #dc3545; border-left: 4px solid #dc3545; margin: 35px 0 10px 0; display: inline-block;">
        <span style="margin-right: 8px;"></span>申込後
    </div>
    <div class="admin-menu-list">
      <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_COMPLETE_REPORT;?>">
          <span class="material-icons">check_circle</span> 施術完了報告
        </a>
        <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_SHEET_CONFIRM_REPORT;?>">
          <span class="material-icons">check_circle</span> シート確認完了報告
        </a>
        <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_SHEET_RETURN_REPORT;?>">
          <span class="material-icons">assignment_return</span> シート再提出報告
        </a>
        <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_PAYMENT_CONFIRM_REPORT;?>">
          <span class="material-icons">payment</span> 入金確認完了報告
        </a>
        <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_SCHEDULE_DECISION_REPORT;?>">
          <span class="material-icons">event</span> 施術日決定報告
        </a>
    </div>
  </div>

  <div class="admin-menu-section">
    <div class="admin-menu-section-title"><span class="material-icons">event</span>日程確定依頼</div>
    <div style="width: 500px;font-size: 16px; font-weight: 700; color: #dc3545; padding: 8px 12px;border-bottom: 1px solid #dc3545;  border-left: 4px solid #dc3545; margin: 15px 0 10px 0; display: inline-block;">
        <span style="margin-right: 8px;"></span>申込直後
    </div>
    <div class="admin-menu-list">
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_DAY;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_TRANSFER;?>">
        <span class="material-icons">account_balance</span> 銀行振込
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_DAY;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_CONVENIENCE;?>">
        <span class="material-icons">store</span> コンビニ決済
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_DAY;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_CREDIT;?>">
        <span class="material-icons">credit_card</span> クレジットカード
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_DAY;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_E_MONEY;?>"> 
        <span class="material-icons">smartphone</span> 電子マネー
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_DAY;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_TRANSIT;?>">
        <span class="material-icons">directions_bus</span> 交通系決済
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_DAY;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_CASH;?>">
        <span class="material-icons">payments</span> 現金支払い
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_DAY;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_FREE;?>">
        <span class="material-icons">free_cancellation</span> 無料
      </a>
    </div>
    <div style="width: 500px;font-size: 16px; font-weight: 700; color: #dc3545;  padding: 8px 12px; border-bottom: 1px solid #dc3545; border-left: 4px solid #dc3545; margin: 35px 0 10px 0; display: inline-block;">
        <span style="margin-right: 8px;"></span>申込後
    </div>
    <div class="admin-menu-list">
      <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_DAY;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_COMPLETE_REPORT;?>">
          <span class="material-icons">check_circle</span> 施術完了報告
        </a>
        <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_DAY;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_SHEET_CONFIRM_REPORT;?>">
          <span class="material-icons">check_circle</span> シート確認完了報告
        </a>
        <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_DAY;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_SHEET_RETURN_REPORT;?>">
          <span class="material-icons">assignment_return</span> シート再提出報告
        </a>
        <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_DAY;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_PAYMENT_CONFIRM_REPORT;?>">
          <span class="material-icons">payment</span> 入金確認完了報告
        </a>
    </div>

  </div>

  <div class="admin-menu-section">
    <div class="admin-menu-section-title"><span class="material-icons">shopping_cart</span>物販</div>
    <div style="width: 500px;font-size: 16px; font-weight: 700; color: #dc3545; padding: 8px 12px;border-bottom: 1px solid #dc3545;  border-left: 4px solid #dc3545; margin: 15px 0 10px 0; display: inline-block;">
        <span style="margin-right: 8px;"></span>申込直後
    </div>
    <div class="admin-menu-list">
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SALES;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_TRANSFER;?>">
        <span class="material-icons">account_balance</span> 銀行振込
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SALES;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_CONVENIENCE;?>">
        <span class="material-icons">store</span> コンビニ決済
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SALES;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_CREDIT;?>">
        <span class="material-icons">credit_card</span> クレジットカード
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SALES;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_E_MONEY;?>">
        <span class="material-icons">smartphone</span> 電子マネー
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SALES;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_TRANSIT;?>">
        <span class="material-icons">directions_bus</span> 交通系決済
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SALES;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_CASH;?>">
        <span class="material-icons">payments</span> 現金支払い
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SALES;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_FREE;?>">
        <span class="material-icons">free_cancellation</span> 無料
      </a>
    </div>
    <div style="width: 500px;font-size: 16px; font-weight: 700; color: #dc3545;  padding: 8px 12px; border-bottom: 1px solid #dc3545; border-left: 4px solid #dc3545; margin: 35px 0 10px 0; display: inline-block;">
        <span style="margin-right: 8px;"></span>申込後
    </div>
    <div class="admin-menu-list">
      <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SALES;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_SALES_SEND_REPORT;?>">
          <span class="material-icons">check_circle</span> 送付完了報告
        </a>
        <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SALES;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_SHEET_CONFIRM_REPORT;?>">
          <span class="material-icons">check_circle</span> シート確認完了報告
        </a>
        <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SALES;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_SHEET_RETURN_REPORT;?>">
          <span class="material-icons">assignment_return</span> シート再提出報告
        </a>
        <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SALES;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_PAYMENT_CONFIRM_REPORT;?>">
          <span class="material-icons">payment</span> 入金確認完了報告
        </a>
    </div>
  </div>


  <div class="admin-menu-section">
    <div class="admin-menu-section-title"><span class="material-icons">psychology</span>遠隔・相談</div>
    <div style="width: 500px;font-size: 16px; font-weight: 700; color: #dc3545; padding: 8px 12px;border-bottom: 1px solid #dc3545;  border-left: 4px solid #dc3545; margin: 15px 0 10px 0; display: inline-block;">
        <span style="margin-right: 8px;"></span>申込直後
    </div>
    <div class="admin-menu-list">
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_TRANSFER;?>">
        <span class="material-icons">account_balance</span> 銀行振込
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_CONVENIENCE;?>">
        <span class="material-icons">store</span> コンビニ決済
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_CREDIT;?>">
        <span class="material-icons">credit_card</span> クレジットカード
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_E_MONEY;?>">
        <span class="material-icons">smartphone</span> 電子マネー
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_TRANSIT;?>">
        <span class="material-icons">directions_bus</span> 交通系決済
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_CASH;?>">
        <span class="material-icons">payments</span> 現金支払い
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN;?>&mailtype=<?php echo SpiritUserClass::PAYMENT_TYPE_FREE;?>">
        <span class="material-icons">free_cancellation</span> 無料
      </a>
    </div>
    <div style="width: 500px;font-size: 16px; font-weight: 700; color: #dc3545;  padding: 8px 12px; border-bottom: 1px solid #dc3545; border-left: 4px solid #dc3545; margin: 35px 0 10px 0; display: inline-block;">
        <span style="margin-right: 8px;"></span>申込後
    </div>
    <div class="admin-menu-list">
      <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_COMPLETE_REPORT;?>">
          <span class="material-icons">check_circle</span> 施術完了報告
        </a>
        <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_SHEET_CONFIRM_REPORT;?>">
          <span class="material-icons">check_circle</span> シート確認完了報告
        </a>
        <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_SHEET_RETURN_REPORT;?>">
          <span class="material-icons">assignment_return</span> シート再提出報告
        </a>
        <a class="admin-menu-link post-application" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN;?>&mailtype=<?php echo SpiritUserClass::MAIL_TYPE_PAYMENT_CONFIRM_REPORT;?>">
          <span class="material-icons">payment</span> 入金確認完了報告
        </a>
    </div>

    


  </div>

  <div class="admin-menu-section">
      <div class="admin-menu-section-title"><span class="material-icons">shopping_cart</span>その他</div>

      <div class="admin-menu-list">
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?&mailtype=9935">
          <span class="material-icons">assignment_return</span> 会員情報再提出依頼
        </a>

        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting'); ?>?&mailtype=9941">
          <span class="material-icons">check_circle</span> 会員情報完了報告
        </a>

      
      </div>
  </div>
  
</div>