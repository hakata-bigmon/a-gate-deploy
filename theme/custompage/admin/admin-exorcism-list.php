


<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");


    $spiritType = new SpiritTypeClass(); //管理データ


    $spiritTypeArray = $spiritType->getSpiritType();


    //var_dump($spiritTypeArray);
?>

<div class="">


    <div class="admin-exorcism-button-area">

        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">施術設定</div></div>


        <div class="admin-exorcism-menu-status-box">
             <button type=“button” class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag("admin_spirit_status"); ?>'" style="width: 100%;font-size: 30px;height: 80px;">管理者　浄霊・鑑定ステータス編集</button>
        </div>

        <div class="admin-exorcism-menu-status-box">
             <button type=“button” class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag("admin_spirit_user_status"); ?>'" style="width: 100%;font-size: 30px;height: 80px;">会員　浄霊・鑑定ステータス編集</button>
        </div>

        <div class="admin-exorcism-menu-status-box">
             <button type=“button” class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag("admin-exorcism-sort"); ?>'" style="width: 100%;font-size: 30px;height: 80px;">施術表示順番</button>
        </div>

        <div class="admin-exorcism-menu-status-box">
             <button type=“button” class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag("admin-exorcism-price-setting"); ?>'" style="width: 100%;font-size: 30px;height: 80px;">価格・最大個数・最小個数設定</button>
        </div>

        <div class="admin-exorcism-menu-status-box">
             <button type=“button” class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag("admin-sales-category"); ?>'" style="width: 100%;font-size: 30px;height: 80px;">物販販売カテゴリー設定</button>
        </div>

        <div class="admin-exorcism-menu-status-box">
             <button type=“button” class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag("admin-profile-menu"); ?>'" style="width: 100%;font-size: 30px;height: 80px;background-color: gainsboro;">管理設定に戻る</button>
        </div>

    </div>


</div>