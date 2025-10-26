


<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");


    //シートデータ
    $spirit_sheet_data = new spiritSheetClass(); //管理データ

    //タイプデータ
    $spiritType = new SpiritTypeClass(); //管理データ


    $spiritTypeArray = $spiritType->getSpiritType();
    $spiritTypeNameArray = $spiritType->getSpiritTypeNameArary();
    $spiritSalesCategoryArray = $spirit_sheet_data->getGeneralPurposeData("cpt_sales_category");

    //保存
    if(isset($_POST["save"]))
    {
         foreach ($spiritTypeArray as $key => $value) {

            foreach ($value as $key_num => $value_num) {

                 $spiritType->saveSpiritTypePrice( $value_num["ID"] , $_POST );
            }

         }

         //再読み込み
         $spiritTypeArray = $spiritType->getSpiritType();
    }


    //var_dump($_POST);
?>

<div class="">


    <div class="admin-exorcism-button-area" style="width: 100%;max-width: 1500px;">

        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">施術詳細設定</div></div>


        <div style="margin-top: 30px;font-size: 14px;font-weight: bold;color: red;">
            物販以外の在庫数が0の場合は、申込ページより表示されなくなります。<br>
            物販以外の在庫数は購入されても在庫数の変動はありません。<br>
            <br>
            物販以外の最大個数は、１度に購入できる最大個数になります。
        </div>


        <form action="<?php echo  getURLSetSlag( "admin-exorcism-price-setting" );?>" name="save_data" method="post" id="save_data">


            <input type="hidden" name="save">

            <?php 
    
             foreach ($spiritTypeArray as $key => $value) {
            ?>

                <div class="admin-exorcism-button-type-title">
                    <?php echo $spiritType->getSpiritTypeName($key);?>
                     <?php if( $key == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){ ?>
                            <span style="margin-left: 15px;">(<a href="<?php echo getURLSetSlag("admin-sales-category"); ?>">カテゴリー編集ページへ</a>)</span>
                    <?php } ?>
                </div>

                <div class="admin-exorcism-button-type-area">

                    <table class="admin-exorcism-price-table">

                        <tr>
                            <th style="min-width: 350px;">施術名</th>
                            <th>施術カテゴリー</th>
                            <?php if( $key == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){ ?>
                                 <th>物販カテゴリー</th>
                            <?php } ?>
                            <th>価格</th>
                            <?php if( $key == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){ ?>
                                 <th>時間</th>
                            <?php } ?>
                            <?php if( $key != SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){ ?>
                                 <th style="font-size: 12px;">最終<br>チェック</th>
                            <?php } ?>
                            <th>最小個数</th>
                            <th>最大個数</th>
                            <th>在庫数</th>
                            

                        </tr>

                        <?php 
                            foreach ($value as $key_num => $value_num) {
                        ?>
                    
                            <tr>
                                <td style="font-size: 13px; text-align: left;white-space: normal;font-weight: 600;"><?php echo $value_num["title"]; ?></td>

                                <td style="white-space:nowrap;">
                                    <select name="admin-exorcism-sprit-category-<?php echo $value_num["ID"];?>" style="">

                                    <?php  foreach ($spiritTypeNameArray as $category_key => $category_value) {?>
                                            <option value="<?php echo $category_key; ?>" <?php if( $category_key == $value_num["group"]) { echo "selected"; }?>><?php echo $category_value; ?></option>
                                        <?php } ?>

                                    </select>

                                       
                                </td>



                                
                                <?php if( $key == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){ ?>
                                    <td style="white-space:nowrap;">
                                         <select name="admin-exorcism-sales-category-<?php echo $value_num["ID"];?>" style="">

                                            <option value="" >未選択</option>

                                            <?php  foreach ($spiritSalesCategoryArray as $category_key => $category_value) {?>
                                                 <option value="<?php echo $category_value["ID"]; ?>" <?php if( $category_value["ID"] == $value_num["sales_category"]) { echo "selected"; }?>><?php echo $category_value["title"]; ?></option>
                                             <?php } ?>

                                         </select>

                                       
                                    </td>
                                <?php } ?>
                                <td style="max-width: 100px;">
                                    <input type="number"  name="admin-exorcism-price-<?php echo $value_num["ID"];?>"  style="text-align: right;width: 80px;max-width: 100px;" value="<?php echo $value_num["price"]; ?>">円
                                </td>
                                <?php if( $key == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){ ?>
                                    <td style="max-width: 100px;">
                                        <input type="number"  name="admin-exorcism-time-<?php echo $value_num["ID"];?>"  style="text-align: right;width: 80px;max-width: 100px;" value="<?php echo $value_num["time"]; ?>">分
                                    </td>
                                <?php } ?>
                                <?php if( $key != SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){ ?>
                                    <td style="max-width: 30px;">
                                        <input type="checkbox"  name="acf_pure_last_check-<?php echo $value_num["ID"];?>"  style="" value="<?php echo $value_num["last_check"]; ?>" <?php if( $value_num["last_check"] != ""){ echo "checked"; }?>>
                                    </td>
                                <?php } ?>

                                <td>
                                    <input type="number"  name="admin-exorcism-min-<?php echo $value_num["ID"];?>"  style="text-align: right;width: 50px;<?php if( $key != SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){ echo "background-color: gainsboro;"; }?>" value="<?php echo $value_num["min"]; ?>" <?php if( $key != SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){ echo "readonly"; }?>>
                                </td>
                                <td>
                                     <input type="number"  name="admin-exorcism-max-<?php echo $value_num["ID"];?>"  style="text-align: right;width: 50px;" value="<?php echo $value_num["max"]; ?>"  > 
                                </td>
                                <td>
                                     <input type="number"  name="admin-exorcism-stock-<?php echo $value_num["ID"];?>"  style="text-align: right;width: 50px;" value="<?php echo $value_num["stock"]; ?>"> 
                                </td>
                            </tr>

                        <?php
                            }
                        ?>

                    </table>

                 </div>

                 <div class="admin-exorcism-price-button-area">
                    <input class="admin-exorcism-price-button" type="submit" value="保存する" onclick=""/>
                </div>
            <?php
             }
            ?>
           
        </form>


    </div>


</div>