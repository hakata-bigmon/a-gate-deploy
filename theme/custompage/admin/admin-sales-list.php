


<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");


    $spiritType = new SpiritTypeClass(); //管理データ


    $spiritTypeArray = $spiritType->getSpiritType();


    $type = SpiritTypeClass::SPIRIT_TYPE_NAME_SALES;

    if(isset($_GET["type"]))
    {
        $type = $_GET["type"];
    }

    //var_dump($spiritTypeArray[$type]);
?>

<div class="">


    <div class="admin-exorcism-button-area">

        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title"><?php echo $spiritType->getSpiritTypeName($type);?>ページリスト</div></div>


        <?php if($type == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){?>
            <div class="admin-exorcism-menu-status-box">
                <button type=“button” class="admin-exorcism-menu-status-button" onclick="location.href='<?php echo getURLSetSlag("admin-sales-category"); ?>'">物販カテゴリーの作成・編集</button>
            </div>
        <?php }?>

    <?php 
    
         foreach ($spiritTypeArray as $key => $value) {

            if($key != $type )
            {
                continue;
            }

    ?>

         <div class="admin-exorcism-button-type-title">
            <?php echo $spiritType->getSpiritTypeName($key);?>
        </div>

         <div class="admin-exorcism-button-type-area">

            <?php 
                foreach ($value as $key_num => $value_num) {


                   

            ?>

                <div class="admin-exorcism-menu-button-box">
                    <button type=“button” class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag("admin-sales-page-edit"); ?>?type_id=<?php echo $value_num["ID"]; ?>'"><?php echo $value_num["title"]; ?></button>
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