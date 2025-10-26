

<?php 

    require_once ("a-gate-functions.php");


?>

<div class="admin-exorcism-button-area" style="margin-bottom: 240px;">

    <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">粗見シート管理</div></div>

        <div class="admin-profile-menu-button-box">
            <button type="button" class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag('admin-arami-sheet-list'); ?>'" style="width: 100%;font-size: 30px;height: 80px;">粗見シート一覧</button>
        </div>
        <div class="admin-profile-menu-button-box">
            <button type="button" class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag('admin-arami-sheet-make'); ?>'" style="width: 100%;font-size: 30px;height: 80px;">粗見シート作成</button>
        </div>
        
        
        <?php /*>
        <div class="admin-profile-menu-button-box">
            <button type="button" class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag('admin-remote-sprit-registration-list'); ?>'" style="width: 100%;font-size: 30px;height: 80px;">仮登録(WEBから登録したものを選択)</button>
        </div>

        <div class="admin-profile-menu-button-box">
            <button type="button" class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag('admin-temporary-registration-list'); ?>'" style="width: 100%;font-size: 30px;height: 80px;">枠数登録（CSV登録）</button>
        </div>

        */?>

      

    </div>


</div>