

<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritPlaceClass.php");




    $spiritPlaceData = new SpiritPlsceClass(); //場所データ


    $edit_no = "";


    //編集番号
    if(isset($_POST["edit_place"]))
    {
        $edit_no = $_POST["edit_place"];
    }


    //保存
    if(isset($_POST["save"]))
    {


        if(!isset($_POST["edit_place"]))
        {
            $edit_no = $spiritPlaceData->newSpritPlace($_POST);//新しく保存
        }
        else{
             $spiritPlaceData->saveSpritPlace( $edit_no , $_POST);
        }


    }


?>

<div class="admin-exorcism-button-area" style="margin-bottom: 240px;">

   <div class="admin-title">
		<?php echo "浄霊場所作成・編集"; ?>
	</div>

    <div class="">
       
        <div style="margin-bottom: 20px;text-align: right;">
            <button class="admin-preview-button" type="button"  style="background-color: lightgray;cursor: pointer;"  onclick="window.location.href='<?php echo getURLSetSlag("admin-spirit-place-list"); ?>'">一覧に戻る</button>
        </div>
    </div>


    <form action="<?php echo getURLSetSlag("admin-spirit-place-edit"); ?>" method="post" onSubmit="">


        <input type="hidden" name="save" value="">
        <input type="hidden" name="unixtime" value="<?php echo time();?>">
        
        <?php if($edit_no != ""){ ?>

             <input type="hidden" name="edit_place" value="<?php echo $edit_no;?>">

        <?php } ?>


        <div class="user-table-flex">
			<div class="user-table-item"  style="width: 135px;">場所名<font color="red">(+必須)</font></div>
			<input type="text" name="place_name" value="<?php if($edit_no != ""){ echo get_field('acf_spirit_palce_name' ,$edit_no);} ?>" style="width: 750px;" required>
		</div>

         <div class="user-table-flex">
			<div class="user-table-item"  style="width: 135px;">場所住所</div>
			<input type="text" name="place_address" value="<?php if($edit_no != ""){ echo get_field('acf_spirit_palce_address' ,$edit_no);} ?>" style="width: 750px;">
		</div>

        <div class="user-table-flex">
			<div class="user-table-item"  style="width: 135px;">場所ホームページURL</div>
			<input type="text" name="place_url" value="<?php if($edit_no != ""){ echo get_field('acf_spirit_palce_url' ,$edit_no);} ?>" style="width: 750px;">
		</div>

        <div class="user-table-flex">
			<div class="user-table-item"  style="width: 135px;">場所GoogleMap URL</div>
			<input type="text" name="place_map" value="<?php if($edit_no != ""){ echo get_field('acf_spirit_palce_googlemap' ,$edit_no);} ?>" style="width: 750px;">
		</div>


        <div class="user-table-flex">
			<div class="user-table-item"  style="width: 135px;">場所アクセス</div>
			<textarea id="" name="place_access" rows="12" cols="33" style="width: 752px;"><?php if($edit_no != ""){ echo get_field('acf_spirit_palce_access' ,$edit_no);} ?></textarea>
		</div>


        <div style="max-width: 500px;margin-left: auto;margin-right: auto;margin-top: 30px;" >
			<button  type="submit" class="admin-remote-make-arami-button" style="width: 100%;font-size: 18px;" >保存する</button>
		</div>

    </form>
</div>