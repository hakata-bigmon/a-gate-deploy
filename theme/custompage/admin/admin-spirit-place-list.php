

<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritPlaceClass.php");




    $spiritPlaceData = new SpiritPlsceClass(); //場所データ


    //削除
    if(isset($_POST["place_delete"]))
    {
         wp_delete_post($_POST["place_delete"], true);
    }



    $place_list = $spiritPlaceData->getSpritPlaceList();//場所取得

?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<div class="admin-exorcism-button-area" style="margin-bottom: 240px;max-width: 1500px;">

    <div class="admin-title">
		<?php echo "浄霊場所一覧"; ?>
	</div>

    <div class="">
       
        <div style="margin-bottom: 20px;text-align: right;">
            <button class="admin-preview-button" type="button"  style="background-color: lightgray;cursor: pointer;"  onclick="window.location.href='<?php echo getURLSetSlag("admin-spirit-place-edit"); ?>'">新規作成</button>
        </div>




    <?php if(count($place_list) >= 1){?>

        <div class="admin-temporary-registration-check-table" style="">

                
            <table id="sort-table" class="display">

                <thead>
                    <tr>
                        
                        <th style="width: 160px;text-align: center;border: 1px solid black;"></th>
                        <th style="border: 1px solid black;text-align: center;">場所</th>
                        <th style="border: 1px solid black;text-align: center;">住所</th>
                        <th style="border: 1px solid black;text-align: center;">URL</th>
                        <th style="border: 1px solid black;text-align: center;">MAP</th>
                        <th style="border: 1px solid black;text-align: center;">アクセス</th>
                     
                    </tr>
                </thead>
                <tbody>
                       
                    <?php  foreach ($place_list as $key => $value) {?>
                        <tr>
                           
                            <td style="border: 1px solid black;">
                            
                                <div style="display: flex;width: 160px; justify-content: space-around;">

                                    <form action="<?php echo getURLSetSlag("admin-spirit-place-edit"); ?>" method="post" style="margin-left: 3px;">
					                    <input type="hidden" name="edit_place" value="<?php echo $key;?>">
					                    <button type="submit"  class="admin-temporary-registration-new-post-submit" style="width: 50px;height: 30px;">編</button>
				                    </form>

                                    <form action="<?php echo getURLSetSlag("admin-spirit-place-edit"); ?>" method="post" style="margin-left: 3px;" onSubmit="return delete_check()">
					                    <input type="hidden" name="place_delete" value="<?php echo $key;?>">
					                    <button type="submit"  class="admin-temporary-registration-new-post-submit" style="width: 50px;height: 30px;background-color: red;color: white;">削</button>
				                    </form>
                                 </div>
                            
                            </td>

                            <td  style="border: 1px solid black;;text-align: left;vertical-align: middle;font-size: 14px;">
                                <?php echo get_field('acf_spirit_palce_name' ,$key);?>
                            </td>

                             <td  style="border: 1px solid black;white-space: normal;;text-align: left;vertical-align: middle;font-size: 14px;">
                                <?php echo get_field('acf_spirit_palce_address' ,$key);?>
                            </td>

                            <td  style="border: 1px solid black;text-align: center;vertical-align: middle;">

                                <?php if(get_field('acf_spirit_palce_url' ,$key) != ""){?>

                                    <a href="<?php echo get_field('acf_spirit_palce_url' ,$key); ?>" target="_blank">〇</a>

                                <?php }else{ ?>
                                    
                                <?php } ?>

                            </td>

                            <td  style="border: 1px solid black;text-align: center;vertical-align: middle;">

                                <?php if(get_field('acf_spirit_palce_googlemap' ,$key) != ""){?>

                                    <a href="<?php echo get_field('acf_spirit_palce_googlemap' ,$key); ?>"  target="_blank">〇</a>

                                <?php }else{ ?>
                                    
                                <?php } ?>

                            </td>

                             <td  style="border: 1px solid black;vertical-align: middle;">

                                <?php if(get_field('acf_spirit_palce_access' ,$key) != ""){?>

                                    記載あり

                                <?php }else{ ?>
                                    記載なし
                                <?php } ?>

                            </td>
                           
                           
                        </tr>
                    <?php } ?>
                </tbody>


            </table>

		</div>

	<?php } ?>


</div>






<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#sort-table').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/ja.json"
            },
            "pageLength": 20,  // 1ページあたりの行数
            "lengthMenu": [20, 50, 100]  // 選択できる件数
        });
    });
</script>



<script>
function delete_check(){

	if(window.confirm('削除してもよろしいですか？')){ // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

	}
	else{ // 「キャンセル」時の処理

		window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止

	}

}
</script>