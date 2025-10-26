<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");


    $spiritType = new SpiritTypeClass(); //管理データ


    $spiritTypeArray = $spiritType->getSpiritType();


    //var_dump($spiritTypeArray);
?>

<div class="">


    <div class="admin-exorcism-button-area">

        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">入力フォーム設定</div></div>


        <div class="admin-exorcism-menu-status-box">
             <button type=“button” class="admin-exorcism-menu-status-button" onclick="location.href='<?php echo getURLSetSlag("admin-order-form-list"); ?>'">入力フォームURL一覧</button>
        </div>

    <?php 
    
         foreach ($spiritTypeArray as $key => $value) {
    ?>


        <div class="admin-exorcism-button-type-title">
            <?php echo $spiritType->getSpiritTypeName($key);?>
        </div>

         <div class="admin-exorcism-button-type-area">

            <?php 
                foreach ($value as $key_num => $value_num) {
            ?>

                <div class="admin-exorcism-menu-button-box">
                    <button type="button" class="admin-exorcism-menu-button" data-id="<?php echo $value_num["ID"]; ?>" onclick="handleButtonClick(this)"><?php echo $value_num["title"]; ?></button>
                </div>
            <?php
                }
            ?>
         </div>
    <?php
         }
    ?>

   


    </div>


</div>

<style>
/* ボタンのアクティブ状態のスタイル */
.admin-exorcism-menu-button.active {
    background-color: #007bff !important;
    color: white !important;
    border-color: #007bff !important;
    box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3) !important;
}

.admin-exorcism-menu-button.active:hover {
    background-color: #0056b3 !important;
    border-color: #0056b3 !important;
}
</style>

<script>
function handleButtonClick(button) {
    // 全てのボタンからactiveクラスを削除
    document.querySelectorAll('.admin-exorcism-menu-button').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // クリックされたボタンにactiveクラスを追加
    button.classList.add('active');
    
    // 新しいウィンドウで開く
    var typeId = button.getAttribute('data-id');
    window.open('<?php echo getURLSetSlag("admin-input-text"); ?>?type_id=' + typeId, '_blank');
}
</script>