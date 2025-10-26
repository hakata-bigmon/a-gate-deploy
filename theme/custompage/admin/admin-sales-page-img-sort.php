


<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");

    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSalesClass.php");


    $spirit_sheet_data = new spiritSheetClass(); //管理データ


     //タイプデータ
    $spiritType = new SpiritTypeClass(); //管理データ

    //販売ページデータ
    $spiritSales = new SpiritSalesClass(); //管理データ


     //指定データ
    $spiritTypeArray = $spiritType->getSpiritTypeKeyTypeNum();

    $type = "";

    if(isset($_GET["type_id"]))
    {
        $type = $_GET["type_id"];
    }

    //商品自体のデータ
    $type_data = $spiritTypeArray[ $type ];


    $page_id = $type_data["sales_page"];


     

    

    //並び順保存
    if(isset($_POST["order"]))
    {
        $sort_array = array();
        $sort_reset_array = array();

        $sort_array = explode(',', $_POST["order"]);


        foreach ($sort_array as $key => $value) {

            $sort_reset_array[$key + 1] = $value;
        }

        //var_dump($sort_reset_array);

        $spiritSales->saveSalesPageImgSort( $page_id , $sort_reset_array);

    }

   

     //販売ページデータ
    $saledata = $spiritSales->getSalesPage($spiritTypeArray[ $type ] , $page_id);
?>

<div class="admin-exorcism-area">




  
    <div class="admin-exorcism-button-area">

        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">画像並び替え</div></div>


        <div class="admin-spirit-edit-area">

           


            <div class="admin-spirit-edit-box">

                <div class="admin-spirit-edit-flex">


                    <div class="admin-spirit-edit-title">表示順の変更</div>
                  
                    <div class="admin-spirit-edit-input-area">
                        <div class="admin-spirit-edit-input-str">リストの▲▼をクリックし、任意の順番に並べ替えたら右の保存を押してください。</div>
                    </div>

                    <div class="admin-spirit-edit-inputbutton-area">
                        <input class="admin-spirit-edit-button" type="button" value="並び順を保存" onclick="saveOrder()"/>
                    </div>

                </div>
               
            </div> 
            

        </div>

        <div class="admin-spirit-edit-list-area">


            
                <form id="orderForm" action="<?php echo getURLSetSlag("admin-sales-page-img-sort"); ?>?type_id=<?php echo $type; ?>" method="post" >
                      <input type="hidden" name="order" id="orderInput">
                </form>

                <table id="adminSpiritEditTableID" class="adminSpiritEditTable" >

                    <tbody>

                    <?php 
    
                        $count = 1;

                        foreach ($saledata["画像並び"] as $key => $value) {
                    ?>

                            <tr data-id="<?php echo $value;?>" >

                                <td style="width: 60px;">
                                    <div class="admin-spirit-edit-list-id">画像<?php echo $value;?></div>
                                </td>
                                <td style="width: 30px;">
                                    <span class="admin-spirit-edit-updown" onclick="moveUp(this)">⬆️</span>
                                </td>
                                <td style="width: 30px;">
                                   <span class="admin-spirit-edit-updown" onclick="moveDown(this)">⬇️</span>
                                </td>

                                <td>
                                  <img  src="<?php echo $saledata["画像" . $value]; ?>" style="margin-top: 15px;max-width: 100px;width: 100%;margin-left: 20px;">
                                </td>

                               
                            </tr>
                     <?php
                            $count++;

                         }
                    ?>

                      
                       

                    </tbody>

                </table>


                 <div class="admin-spirit-return-button-area">
                     <button type=“button” class="admin-spirit-return-button" onclick="location.href='<?php echo getURLSetSlag("admin-sales-page-edit"); ?>?type_id=<?php echo $type; ?>'">戻る</button>
                 </div>

               
         </div>


    
   


    </div>


</div>

