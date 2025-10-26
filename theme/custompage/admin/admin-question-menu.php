

<?php 

    require_once ("a-gate-functions.php");


?>

<div class="admin-exorcism-button-area" style="margin-bottom: 240px;">

    <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">質問設定</div></div>


        <div class="admin-profile-menu-button-box">
            <button type="button" class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag('admin-input-custom'); ?>'" style="width: 100%;font-size: 30px;height: 80px;">入力設定</button>
        </div>

         <div class="admin-profile-menu-button-box">
            <button type="button" class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag('admin-question-list'); ?>'" style="width: 100%;font-size: 30px;height: 80px;">質問設定</button>
        </div>

        <div class="admin-profile-menu-button-box">
            <button type="button" class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag('admin-thanks-custom'); ?>'" style="width: 100%;font-size: 30px;height: 80px;">サンクス作成</button>
        </div>

       <div class="admin-profile-menu-button-box">
            <button type="button" class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag('admin-sales-list'); ?>'" style="width: 100%;font-size: 30px;height: 80px;">物販販売ページ編集</button>
        </div>

    </div>


</div>