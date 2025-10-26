<?php 


/****************************************************
 **  YNモーダル表示
 ******************************************************/
function YNModalDisp()
{
	?>

	<?php //表示用    ?>
	<div id="modal" class="modal js-modal">
		<div class="modal__bg js-modal-close"></div>
		<div class="modal__content pt20 pm20 pl20 pr20">

			<div class="modal-text-area" id="disp-text"></div>
			<div class="flex-area">

				<div class="yn-btn orange js-modal-ok">はい</div>
				<div class="yn-btn gray js-modal-back" id="moda-back">戻る</div>
			</div>
		</div>
	</div>

<?php 
}
/****************************************************
 **  YNモーダル文字表示
 ******************************************************/
function YNMojiModalDisp($moji)
{
	?>

	<?php //表示用    ?>
	<div id="modal_moji" class=" js-modal">
		<div class="modal__bg js-modal-close"></div>
		<div class="modal__content pt20 pm20 pl20 pr20">

			<div class="modal-text-area" id="disp-text"><?php echo $moji ?></div>
			<div class="flex-area">

				<div class="yn-btn orange js-modal-ok" onclick='document.getElementById("modal_moji").style.display="none"'>OK</div>
			</div>
		</div>
	</div>

    <script>
        document.getElementById("modal_moji").style.display="block"
    </script>
<?php 
}

/****************************************************
 **  画像モーダル表示
 ******************************************************/

// 画像モーダル表示
function DispImgmodal(){
?>
    <style>
        .modal__content{
            width: auto;
            height: auto;
        }
    </style>

    <div id="img-modal" class="modal js-modal">
		<div class="modal__bg js-modal-close" id="img-modal-close"></div>
		<div class="modal__content pt20 pm20 pl20 pr20" >
            <img class="" id="disp-img" src="" style="">
		</div>
	</div>

	<script>
        

        // ボタン表示
        function img_modal(img){

            document.getElementById('img-modal').style.display = 'block';
            document.getElementById('disp-img').src = img;

            // 戻る
            document.getElementById('img-modal-close').addEventListener('click', function() {
                console.log("cli");
                document.getElementById('img-modal').style.display = 'none';
            });

        }

	</script>
<?php 


}




/****************************************************
 **  画像削除モーダル表示
 ******************************************************/
function DeleteImgModalDisp()
{
	?>

	<?php //表示用    ?>
	<div id="modal" class="modal js-modal">
		<div class="modal__bg js-modal-close"></div>
		<div class="modal__content pt20 pm20 pl20 pr20"  style="height:360px">

			<div class="modal-text-area" id="disp-text">この画像を本当に削除しても宜しいでしょうか？</div>
			<div class="flex-area">

				<div class="yn-btn orange " id="modal-go">はい</div>
				<!-- <div class="yn-btn orange " id="last-modal-delete">いいえ</div> -->
                <div class="yn-btn gray js-modal-back" id="modal-back">いいえ</div>
			</div>
		</div>
	</div>

	<script>
        

        // ボタン表示
        function dispBtn(id){

            document.getElementById('modal').style.display = 'block';

            // 戻る
            document.getElementById('modal-back').addEventListener('click', function() {
                document.getElementById('modal').style.display = 'none';
            });

            // 削除実行
            document.getElementById('modal-go').addEventListener('click', function() {
                
                document.getElementById('result_change').name = 'result_change'; // 新しい名前に変更
                document.getElementById('result_change').value = id;
                document.getElementById('treatment_result_form').submit();
            });

        }
        // 施術提出削除ボタン表示
        function dispTreatmentBtn(id){

            document.getElementById('modal').style.display = 'block';

            // 戻る
            document.getElementById('modal-back').addEventListener('click', function() {
                document.getElementById('modal').style.display = 'none';
            });

            // 削除実行
            document.getElementById('modal-go').addEventListener('click', function() {
                
                document.getElementById('upload_submit_result').name = 'upload_submit_change'; // 新しい名前に変更
                document.getElementById('upload_submit_result').value = id;
                document.getElementById('acf_treatment_result_image_field').submit();
            });

        }

	</script>

<?php 
}

/****************************************************
 **  削除モーダル表示
 ******************************************************/
function DeleteModalDisp()
{
	?>

	<?php //表示用    ?>
	<div id="modal" class="modal js-modal">
		<div class="modal__bg js-modal-close"></div>
		<div class="modal__content pt20 pm20 pl20 pr20"  style="height:360px">

			<div class="modal-text-area" id="disp-text">選択したシートを削除しますか？</div>
			<div class="flex-area">

				<div class="yn-btn orange " id="js-modal-visible">非表示にする</div>
				<div class="yn-btn orange " id="js-modal-delete" onclick="checkLastDelete()">完全削除する</div>
				<div class="yn-btn orange " id="last-modal-delete" style="display:none">完全削除する</div>
			</div>
            <div class="yn-btn gray js-modal-back" id="moda-back">戻る</div>
		</div>
	</div>

	<script>
        
        var text = document.getElementById('disp-text');
        var delete_btn = document.getElementById('js-modal-delete');
        var last_delete_btn = document.getElementById('last-modal-delete');

        // ボタン表示
        function dispBtn(id){

            document.getElementById('modal').style.display = 'block';
            // 戻る
            document.getElementById('moda-back').addEventListener('click', function() {
                document.getElementById('modal').style.display = 'none';
                
                // 文章戻す
                text.innerHTML = "選択したシートを削除しますか？";
                last_delete_btn.style.display = "none";
                delete_btn.style.display = "block";
            });

            // 非表示
            document.getElementById('js-modal-visible').addEventListener('click', function() {
                hiddenSheet(id);
            });

            // 完全削除
            document.getElementById('last-modal-delete').addEventListener('click', function() {
                deleteSheet(id);
                
            });
        }

        // 削除確認
        function checkLastDelete(){

            // 文章変更
            text.innerHTML = "本当に削除しますか？";
            last_delete_btn.style.display = "block";
            delete_btn.style.display = "none";

            // モーダルの再表示
            document.getElementById('modal').style.display = 'none';    //一旦非表示にして再度表示

            setTimeout(function() {
                document.getElementById('modal').style.display = 'block';
            }, 500); // 1000ミリ秒 = 1秒
        }

        // シート非表示
        function hiddenSheet(id){

            document.getElementById('hidden_sheet_'+id).submit();
        }

        // シート削除
        function deleteSheet(id){

            document.getElementById('delete_sheet_'+id).submit();
        }


	</script>
    
<?php 
    } 
    
/****************************************************
 **  表示設定モーダル表示
 ******************************************************/
function SettingModalDisp($spiritTypeArray,$disp_squeeze,$spirit_flg = "")
{
    if(isset($disp_squeeze->{1}) ) $disp_no = $disp_squeeze->{1};
    else $disp_no = "";

    if($spirit_flg == true) $url = getURLSetSlag("admin-spirit-sheets-list");
    else if($spirit_flg == "connection") $url = getURLSetSlag("admin-connection-member-list")."?group_id=".$_GET['group_id']."&group_top=".$_GET['group_top'];
    else $url = getURLSetSlag("admin-member-list");

    /*
        各キーを変更　→　datatableのスクリプトの検索対象外を数値からキーに変更

     */

	?>

	<?php //表示用    ?>
	<div id="setting-modal" class="modal">
		<div class="modal__bg" id="over-close"></div>
        <form action="<?php echo $url ?>" method="post">
            <input type="hidden" name="disp-squeeze">
            <div class="modal__content pt20 pm20 pl20 pr20">
                <div class="" id="btn-close">X</div>

                <div class="modal-text-area" id="disp-text">表示設定</div>
                <div class="check-flex">
                    <label for="">表示件数</label>
                    <select name="disp-squeeze-content[disp-no]" id="disp-no" style="margin-left:10px;margin-top:5px">
                        <option value="50" <?php if($disp_no == 50) echo "selected"; ?>>50</option>
                        <option value="100" <?php if($disp_no == 100) echo "selected"; ?>>100</option>
                        <option value="200" <?php if($disp_no == 200) echo "selected"; ?>>200</option>
                        <option value="500" <?php if($disp_no == 500) echo "selected"; ?>>500</option>
                    </select>
                    件
                </div>
                <div class="check-flex">
                    <label for="">表示項目</label>
                    <div class="ast-font">*チェックを外すと一覧の項目の表示が消えます</div>
                </div>

                <div class="js-btn-flex">

                    <button type="button" class="check-btn " onclick="disp_all_check(true)">全チェック</button>
                    <button type="button" class="check-btn " onclick="disp_all_check(false)">全チェックを外す</button>
                </div>

                <div class="check-flex check-area">

                    <?php if(!$spirit_flg){?>

                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[0]" id="disp-id" value="disp-id" <?php if(checkSqueeze("disp-id",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[user_unique_id]" id="disp-id" value="disp-id" <?php if(checkSqueeze("disp-id",$disp_squeeze)) echo "checked"?>>
                        <label for="disp-id">ID</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[2]" id="disp-last-name" value="disp-last-name" <?php if(checkSqueeze("disp_last_name",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp_last_name]" id="disp_last_name" value="disp_last_name" <?php if(checkSqueeze("disp_last_name",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_last_name">苗字</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[3]" id="disp-first-name" value="disp-first-name" <?php if(checkSqueeze("disp_first_name",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp_first_name]" id="disp_first_name" value="disp_first_name" <?php if(checkSqueeze("disp_first_name",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_first_name">名前</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[4]" id="disp-last-kana" value="disp-last-kana" <?php if(checkSqueeze("disp-last-kana",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp_last_kana]" id="disp_last_kana" value="disp_last_kana" <?php if(checkSqueeze("disp_last_kana",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_last_kana">ミョウジ（カタカナ）</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[5]" id="disp-first-kana" value="disp-first-kana" <?php if(checkSqueeze("disp-first-kana",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp_first_kana]" id="disp_first_kana" value="disp_first_kana" <?php if(checkSqueeze("disp_first_kana",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_first_kana">ナマエ（カタカナ）</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[6]" id="disp-connection" value="disp-connection" <?php if(checkSqueeze("disp-connection",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp-connection]" id="disp-connection" value="disp-connection" <?php if(checkSqueeze("disp-connection",$disp_squeeze)) echo "checked"?>>
                        <label for="disp-connection">関連</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[7]" id="disp-group" value="disp-group" <?php if(checkSqueeze("disp-group",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp-group]" id="disp-group" value="disp-group" <?php if(checkSqueeze("disp-group",$disp_squeeze)) echo "checked"?>>
                        <label for="disp-group">グループ</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[8]" id="disp-sex" value="disp-sex" <?php if(checkSqueeze("disp-sex",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp-sex]" id="disp-sex" value="disp-sex" <?php if(checkSqueeze("disp-sex",$disp_squeeze)) echo "checked"?>>
                        <label for="disp-sex">性別</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[9]" id="disp-tel" value="disp-tel" <?php if(checkSqueeze("disp-tel",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp_tel]" id="disp_tel" value="disp_tel" <?php if(checkSqueeze("disp_tel",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_tel">連絡先</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[10]" id="disp-post-no" value="disp-post-no" <?php if(checkSqueeze("disp-post-no",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp_post_no]" id="disp_post_no" value="disp_post_no" <?php if(checkSqueeze("disp_post_no",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_post_no">郵便番号</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[11]" id="disp-address" value="disp-address" <?php if(checkSqueeze("disp-address",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp_address]" id="disp_address" value="disp_address" <?php if(checkSqueeze("disp_address",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_address">住所</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[12]" id="disp-mail" value="disp-mail" <?php if(checkSqueeze("disp-mail",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp_mail]" id="disp_mail" value="disp_mail" <?php if(checkSqueeze("disp_mail",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_mail">メールアドレス</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[13]" id="disp-born" value="disp-born" <?php if(checkSqueeze("disp-born",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp_born]" id="disp_born" value="disp_born" <?php if(checkSqueeze("disp_born",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_born">生年月日</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[14]" id="disp-age" value="disp-age" <?php if(checkSqueeze("disp-age",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp_age]" id="disp_age" value="disp_age" <?php if(checkSqueeze("disp_age",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_age">年齢</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[15]" id="disp-regist" value="disp-regist" <?php if(checkSqueeze("disp-regist",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp_regist]" id="disp_regist" value="disp_regist" <?php if(checkSqueeze("disp_regist",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_regist">登録日</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[16]" id="disp-line" value="disp-line" <?php if(checkSqueeze("disp-line",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp_line]" id="disp_line" value="disp_line" <?php if(checkSqueeze("disp_line",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_line">LINE ID</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[17]" id="disp-inflow" value="disp-inflow" <?php if(checkSqueeze("disp-inflow",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp_inflow]" id="disp_inflow" value="disp_inflow" <?php if(checkSqueeze("disp_inflow",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_inflow">流入元</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[18]" id="disp-introduce" value="disp-introduce" <?php if(checkSqueeze("disp-introduce",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp_introduce]" id="disp_introduce" value="disp_introduce" <?php if(checkSqueeze("disp_introduce",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_introduce">紹介者</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[19]" id="disp-remarks" value="disp-remarks" <?php if(checkSqueeze("disp-remarks",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp-remarks]" id="disp-remarks" value="disp-remarks" <?php if(checkSqueeze("disp-remarks",$disp_squeeze)) echo "checked"?>>
                        <label for="disp-remarks">備考</label>
                    </div>
                    <div class="check-box">
                        <!-- <input  class="search-check" type="checkbox" name="disp-squeeze-content[20]" id="disp-comment" value="disp-comment" <?php if(checkSqueeze("disp-comment",$disp_squeeze)) echo "checked"?>> -->
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[disp_comment]" id="disp_comment" value="disp_comment" <?php if(checkSqueeze("disp_comment",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_comment">特記事項</label>
                    </div>
                    <?php 
                        }
                        foreach ($spiritTypeArray as $key => $value) {
                            foreach ($value as $key_num => $value_num) {
                                
                                $split_id = $value_num["ID"];
                    ?>
                    <div class="check-box">
                        <input  class="search-check" type="checkbox" name="disp-squeeze-content[<?php echo $value_num["ID"]; ?>]" id="disp-split-<?php echo $value_num["ID"];?>" value="<?php echo $value_num["ID"];?>" <?php if(checkSqueeze($split_id,$disp_squeeze)) echo "checked"?>>
                        <label for="disp-split-<?php echo $value_num["ID"];?>"><?php echo $value_num["title"];?></label>
                    </div>
                    <?php 
                            }
                        }
                    ?>
                <?php 
                    if($spirit_flg){
                        require_once (dirname(__FILE__)."/../../class/GroupSettingClass.php");
                        require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
                        $group_setting_data = new GroupSettingClass(); //グループクラス
                        $spirit_sheet_data = new spiritSheetClass(); //管理データ
                        $group_list = $group_setting_data->getGroupData();
                        $spiritStatusArray = $spirit_sheet_data->getSpiritAdminStatusID('cpt_spirit_status');

                ?>
                    
                    <div class="modal-group-tr">

                        <label class="modal-group-label" for="">グループ設定</label>
                        <select class="table-squeeze" name="disp-squeeze-content[group]" id="">
                            <option value="">すべて表示</option>
                            <?php 
                                foreach ($group_list as $key => $value) { 
                            ?>
                            <option value="<?php echo $value['ID']?>" <?php if(isset($disp_squeeze->{'group'}) && $value['ID'] == $disp_squeeze->{'group'}) echo "selected";?>><?php echo $value['title']?></option>
                            <?php } ?>
    
                        </select>
                    </div>

                    <div class="modal-group-tr">

                        <label class="modal-group-label" for="">ステータス</label>
                        <select class="table-squeeze" name="disp-squeeze-content[status]" id="">
                            <option value="0">すべて表示</option>
                            <option value="1" <?php if( isset( $disp_squeeze->{'status'}) && $disp_squeeze->{'status'} == "1") echo "selected"; ?>>未確認</option>
                            <?php 
                                foreach ($spiritStatusArray as $key => $value) {
                                
                            ?>
                            <option value="<?php echo $key; ?>" <?php if( isset($disp_squeeze->{'status'}) && $disp_squeeze->{'status'} == $key) echo "selected"; ?>><?php echo $value["title"];?></option>
                            <?php 
                                }
                            ?>
                        </select>
                    </div>

                    <div class="modal-group">
                        <label class="modal-group-label" for="">依頼日</label>
                        <input type="date" id="request_start" name="disp-squeeze-content[request_start]" value="<?php if( isset($disp_squeeze->{'request_start'})){  echo $disp_squeeze->{'request_start'}; }?>">~<input  class="search-check" type="date" id="request_end" name="disp-squeeze-content[request_end]" value="<?php if( isset($disp_squeeze->{'request_end'})){  echo $disp_squeeze->{'request_end'}; }?>">
                        <button type="button" class="admin-spritsheet-search-reset" onclick="reset_request('start')">依頼日リセット</button>
                    </div>
                    <div class="modal-group">
                        <label class="modal-group-label" for="">依頼確定日</label>
                        <input type="date" id="request_start_ok" name="disp-squeeze-content[request_start_ok]" value="<?php if( isset($disp_squeeze->{'request_start_ok'})){  echo $disp_squeeze->{'request_start_ok'}; }?>">~<input  class="search-check" type="date" id="request_end_ok" name="disp-squeeze-content[request_end_ok]" value="<?php if( isset($disp_squeeze->{'request_end_ok'})){  echo $disp_squeeze->{'request_end_ok'}; }?>">
                        <button type="button" class="admin-spritsheet-search-reset" onclick="reset_request('ok')">依頼確定日リセット</button>
                    </div>
                    <div class="modal-group">
                        <label class="modal-group-label" for="">実行日</label>
                        <input type="date" id="request_start_execute" name="disp-squeeze-content[request_start_execute]" value="<?php if( isset($disp_squeeze->{'request_start_execute'})){  echo $disp_squeeze->{'request_start_execute'}; }?>">~<input  class="search-check" type="date" id="request_end_execute" name="disp-squeeze-content[request_end_execute]" value="<?php if( isset($disp_squeeze->{'request_end_execute'})){  echo $disp_squeeze->{'request_end_execute'}; }?>">
                        <button type="button" class="admin-spritsheet-search-reset" onclick="reset_request('execute')">実行日リセット</button>
                    </div>
                <?php 

                    
                    }
                ?>
                </div>
                
                
                <button class="modal-save-btn">設定を保存して閉じる</button>
            </div>
        </form>
	</div>

	<script>
        
        // ボタン表示
        function setting_modal(){

            document.getElementById('setting-modal').style.display = 'block';

            document.getElementById('over-close').addEventListener('click', function() {
                document.getElementById('setting-modal').style.display = 'none';
            });
            document.getElementById('btn-close').addEventListener('click', function() {
                document.getElementById('setting-modal').style.display = 'none';
            });

        }
        function reset_request(type){
            if(type == "start"){
                document.getElementById('request_start').value = "";
                document.getElementById('request_end').value = "";
            }else if(type == 'ok'){
                document.getElementById('request_start_ok').value = "";
                document.getElementById('request_end_ok').value = "";

            }else if(type == 'execute'){
                document.getElementById('request_start_execute').value = "";
                document.getElementById('request_end_execute').value = "";

            }

        }

        // チェックボックス切り替え
        function all_check(do_check){

            document.querySelectorAll('input[name^="disp-squeeze-content["]').forEach(function(checkbox) {
                let no = parseInt(checkbox.name.match(/\d+/)[0]); // `search-squeeze-content[xx]` の xx 部分を抽出
                // if (no >= 0 && no <= 19) { // `0` ～ `19` の範囲のみ
                    checkbox.checked = do_check;
                // }
            });

        }
        function disp_all_check(do_check){
            const elements = document.querySelectorAll('.search-check');
            elements.forEach(checkbox => {
                checkbox.checked = do_check;
            });
        }

	</script>
    
<?php 
    } 
/****************************************************
 **  検索設定モーダル表示
 ******************************************************/
define("SEARCH_ID", 0);
define("SEARCH_LAST_NAME", 1);
define("SEARCH_FIRST_NAME", 2);
define("SEARCH_LAST_KANA", 3);
define("SEARCH_FIRST_KANA", 4);
define("SEARCH_CONNECTION", 5);
define("SEARCH_GROUP", 6);
define("SEARCH_SEX", 7);
define("SEARCH_TEL", 8);
define("SEARCH_POST_NO", 9);
define("SEARCH_ADDRESS", 10);
define("SEARCH_MAIL", 11);
define("SEARCH_BORN", 12);
define("SEARCH_AGE", 13);
define("SEARCH_REGIST", 14);
define("SEARCH_LINE", 15);
define("SEARCH_INFLOW", 16);
define("SEARCH_INTRODUCE", 17);
define("SEARCH_REMARKS", 18);
define("SEARCH_COMMENT", 19);
function SearchModalDisp($disp_squeeze)
{
	?>

	<?php //表示用    ?>
	<div id="search-modal" class="modal">
        
		<div class="modal__bg" id="search-over-close"></div>
        <form action="<?php echo getURLSetSlag("admin-member-list"); ?>" method="post">
            <input type="hidden" name="search-squeeze">
            <div class="modal__content pt20 pm20 pl20 pr20">
                <div class="" id="search-btn-close">X</div>

                <div class="modal-text-area" id="disp-text">検索設定</div>
                <div class="">キーワード検索に含めるものにチェック入れてください</div>
                <div class="">※最低1つはチェックを入れてください</div>


                <div class="js-btn-flex">

                    <button type="button" class="check-btn " onclick="search_all_check(true)">全チェック</button>
                    <button type="button" class="check-btn " onclick="search_all_check(false)">全チェックを外す</button>
                </div>

                <div class="check-flex">
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[0]" id="disp-id" value="disp-id" <?php if(checkSqueeze("disp-id",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" class="target_search" type="checkbox" name="search-squeeze-content[user_unique_id]" id="disp-id" value="disp-id" <?php if(checkSqueeze("disp-id",$disp_squeeze)) echo "checked"?>>
                        <label for="disp-id">ID</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[2]" id="disp-last-name" value="disp-last-name" <?php if(checkSqueeze("disp_last_name",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp_last_name]" id="disp_last_name" value="disp_last_name" <?php if(checkSqueeze("disp_last_name",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_last_name">苗字</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[3]" id="disp-first-name" value="disp-first-name" <?php if(checkSqueeze("disp-first-name",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp_first_name]" id="disp_first_name" value="disp_first_name" <?php if(checkSqueeze("disp_first_name",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_first_name">名前</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[4]" id="disp-last-kana" value="disp-last-kana" <?php if(checkSqueeze("disp-last-kana",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp_last_kana]" id="disp_last_kana" value="disp_last_kana" <?php if(checkSqueeze("disp_last_kana",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_last_kana">ミョウジ（カタカナ）</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[5]" id="disp-first-kana" value="disp-first-kana" <?php if(checkSqueeze("disp-first-kana",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp_first_kana]" id="disp_first_kana" value="disp_first_kana" <?php if(checkSqueeze("disp_first_kana",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_first_kana">ナマエ（カタカナ）</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[6]" id="disp-connection" value="disp-connection" <?php if(checkSqueeze("disp-connection",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp-connection]" id="disp-connection" value="disp-connection" <?php if(checkSqueeze("disp-connection",$disp_squeeze)) echo "checked"?>>
                        <label for="disp-connection">関連</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[7]" id="disp-group" value="disp-group" <?php if(checkSqueeze("disp-group",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp-group]" id="disp-group" value="disp-group" <?php if(checkSqueeze("disp-group",$disp_squeeze)) echo "checked"?>>
                        <label for="disp-group">グループ</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[8]" id="disp-sex" value="disp-sex" <?php if(checkSqueeze("disp-sex",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp-sex]" id="disp-sex" value="disp-sex" <?php if(checkSqueeze("disp-sex",$disp_squeeze)) echo "checked"?>>
                        <label for="disp-sex">性別</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[9]" id="disp-tel" value="disp-tel" <?php if(checkSqueeze("disp-tel",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp_tel]" id="disp_tel" value="disp_tel" <?php if(checkSqueeze("disp_tel",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_tel">連絡先</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[10]" id="disp-post-no" value="disp-post-no" <?php if(checkSqueeze("disp-post-no",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp_post_no]" id="disp_post_no" value="disp_post_no" <?php if(checkSqueeze("disp_post_no",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_post_no">郵便番号</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[11]" id="disp-address" value="disp-address" <?php if(checkSqueeze("disp-address",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp_address]" id="disp_address" value="disp_address" <?php if(checkSqueeze("disp_address",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_address">住所</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[12]" id="disp-mail" value="disp-mail" <?php if(checkSqueeze("disp-mail",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp_mail]" id="disp_mail" value="disp_mail" <?php if(checkSqueeze("disp_mail",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_mail">メールアドレス</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[13]" id="disp-born" value="disp-born" <?php if(checkSqueeze("disp-born",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp_born]" id="disp_born" value="disp_born" <?php if(checkSqueeze("disp_born",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_born">生年月日</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[14]" id="disp-age" value="disp-age" <?php if(checkSqueeze("disp-age",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp_age]" id="disp_age" value="disp_age" <?php if(checkSqueeze("disp_age",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_age">年齢</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[15]" id="disp-regist" value="disp-regist" <?php if(checkSqueeze("disp-regist",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp_regist]" id="disp_regist" value="disp_regist" <?php if(checkSqueeze("disp_regist",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_regist">登録日</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[16]" id="disp-line" value="disp-line" <?php if(checkSqueeze("disp-line",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp_line]" id="disp_line" value="disp_line" <?php if(checkSqueeze("disp_line",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_line">LINE ID</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[17]" id="disp-inflow" value="disp-inflow" <?php if(checkSqueeze("disp-inflow",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp_inflow]" id="disp_inflow" value="disp_inflow" <?php if(checkSqueeze("disp_inflow",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_inflow">流入元</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[18]" id="disp-introduce" value="disp-introduce" <?php if(checkSqueeze("disp-introduce",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp_introduce]" id="disp_introduce" value="disp_introduce" <?php if(checkSqueeze("disp_introduce",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_introduce">紹介者</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[19]" id="disp-remarks" value="disp-remarks" <?php if(checkSqueeze("disp-remarks",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp-remarks]" id="disp-remarks" value="disp-remarks" <?php if(checkSqueeze("disp-remarks",$disp_squeeze)) echo "checked"?>>
                        <label for="disp-remarks">備考</label>
                    </div>
                    <div class="check-box">
                        <!-- <input class="target_search" type="checkbox" name="search-squeeze-content[20]" id="disp-comment" value="disp-comment" <?php if(checkSqueeze("disp-comment",$disp_squeeze)) echo "checked"?>> -->
                        <input class="target_search" type="checkbox" name="search-squeeze-content[disp_comment]" id="disp_comment" value="disp_comment" <?php if(checkSqueeze("disp_comment",$disp_squeeze)) echo "checked"?>>
                        <label for="disp_comment">特記事項</label>
                    </div>
                </div>
                
                <button class="modal-save-btn">設定を保存して閉じる</button>
            </div>
        </form>
	</div>


	<script>
        
        // ボタン表示
        
        function search_modal(){
            document.getElementById('search-modal').style.display = 'block';

            document.getElementById('search-over-close').addEventListener('click', function() {
                document.getElementById('search-modal').style.display = 'none';
            });
            document.getElementById('search-btn-close').addEventListener('click', function() {
                document.getElementById('search-modal').style.display = 'none';
            });

        }

        // チェックボックス切り替え
        // function search_all_check(do_check){

        //     document.querySelectorAll('input[name^="search-squeeze-content["]').forEach(function(checkbox) {
        //         // console.log(checkbox.name);
        //         const index = parseInt(checkbox.name.match(/\d+/)[0]); // `search-squeeze-content[xx]` の xx 部分を抽出
        //         if (index >= 0 && index <= 20) { // `0` ～ `19` の範囲のみ
        //             checkbox.checked = do_check;
        //         }
        //     });

        // }

        function search_all_check(do_check){
            const elements = document.querySelectorAll('.target_search');
            elements.forEach(checkbox => {
                checkbox.checked = do_check;
            });
        }

	</script>
    
<?php 
    } 
    
    ?>

