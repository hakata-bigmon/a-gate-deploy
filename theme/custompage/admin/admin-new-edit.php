

<?php 

    require_once ("a-gate-functions.php");
	require_once (dirname(__FILE__)."/../../class/spiritNewsClass.php");


	$newsClass = new SpiritNewsClass(); //ニュースデータ

	$news_id = $_POST["news_edit"];


	$up_data_message = "";

	//保存
	if(isset($_POST["save"]))
	{
		//タイトル変更
		// 投稿情報を配列で用意
		$post_data = array(
			'ID' => $news_id,
			'post_title' => $_POST["news_title"]
		);

		// 投稿を更新
		$update_result = wp_update_post($post_data, true);

		//対象者更新
		update_field("acf_news_all_post", $_POST["acf_news_all_post"], $news_id);


		//対象者
		if($_POST["acf_news_all_post"] == "specific_users")//対象者指定
		{
			$target_array = explode(',', $_POST["news_member"]);//分割

			$json_data = json_encode($target_array, JSON_UNESCAPED_UNICODE);//JSON化

			update_field("acf_news_member",$json_data, $news_id);
		}
		else{
			update_field("acf_news_member", "", $news_id);//それ以外は削除
		}

		//告知
		if(isset($_POST["news_post"]))
		{
			//告知が存在する
			update_field("acf_news_post", "1", $news_id);
		}
		else{
			//存在しない
			//もし現在保存しているのが告知のであれば、全員の既読を削除する
			if(get_field('acf_news_post', $news_id) != ""){
				//全ユーザー既読を削除
				$newsClass->deleteUnreadPostAllUser($news_id);
			}

			update_field("acf_news_post", "", $news_id);
		}

		//表示
		if(isset($_POST["news_disp"]))
		{
			//表示が存在する
			update_field("acf_news_disp", "1", $news_id);
		}
		else{
			//表示しない
			update_field("acf_news_disp", "", $news_id);
		}

		//表示日
		update_field("acf_news_disp_date",$_POST["news_disp_date"], $news_id);


		date_default_timezone_set('Asia/Tokyo');

		$today = date("Y-m-d H:i:s");

		$up_data_message = "更新しました " . $today;

	}


	

	$user_data = $newsClass->getNewsPostUSer(true);//ユーザーのデータ取得
    //var_dump($_POST);
?>




<div class="admin-user-table-area">


    <div class="admin-title">
        <?php echo "お知らせ編集"; ?>
	</div>

	<div class="">
       
		<div class="admin-preview-button-flex" style="justify-content: right;margin-bottom: 15px;">
            <div class="admin-preview-button-flex-box">
				<form action="<?php echo home_url(); ?>/?post_type=cpt_news&p=<?php echo $news_id;?>&preview=true" method="post" target="_blank">
				    <button type="submit"  class="admin-preview-button" style="background-color: aliceblue;">確認</button>
				</form>
            </div>

            <div class="admin-preview-button-flex-box">
                <button class="admin-preview-button" type="button"  style="background-color: lightgray;cursor: pointer;"  onclick="window.location.href='<?php echo getURLSetSlag("admin-news-list"); ?>'">一覧に戻る</button>
            </div>
        </div>

    </div>

	<form action="<?php echo getURLSetSlag("admin-new-edit"); ?>" method="post" onSubmit="return save_check()">


		<input type="hidden" name="save" value="">
		<input type="hidden" name="news_edit" value="<?php echo $news_id;?>">

		<div class="user-table-flex">
			<div class="user-table-item"  style="min-width: 135px;">タイトル</div>
			<input type="text" name="news_title" value="<?php echo get_the_title($news_id);?>" style="width: 750px;">
		</div>
		<div class="user-table-flex">
			<div class="user-table-item"  style="min-width: 135px;">作成日</div>
			<?php echo get_the_date('Y年n月j日',$news_id);?>
		</div>
		<div class="user-table-flex">
			<div class="user-table-item"  style="min-width: 135px;">対象者選択</div>

			<?php
				$news_all_post = get_field('acf_news_all_post', $news_id);
		
			?>

			<input type="radio" name="acf_news_all_post" id="news_all_post" value="no_users" <?php if($news_all_post == "" || $news_all_post == "no_users"){?>checked<?php } ?> style="">対象者なし
			<input type="radio" name="acf_news_all_post" id="news_all_post" value="all_users" <?php if($news_all_post =="all_users"){?>checked<?php } ?> style="">全員対象
			<input type="radio" name="acf_news_all_post" id="news_all_post" value="specific_users" <?php if($news_all_post == "specific_users"){?>checked<?php } ?> style="">対象者指定
		</div>
		<div class="user-table-flex">
			<div class="user-table-item"  style="min-width: 135px;">
				対象者(個別)
			</div>
			<div>

				<?php 
		
					$news_member = get_field('acf_news_member', $news_id);

					//hidden用
					$news_member_input_cord = "";
					$news_member_name_cord = "";

					//空じゃない場合、デコード
					if($news_member != "")
					{
						$news_member = json_decode($news_member, true);  //jsonデータ戻し

						//var_dump($user_data);

						foreach ($news_member as $key => $value) {
						
							if(!get_userdata( $value ) )
							{
								continue;//存在しない
							}

							$news_member_input_cord .= $value . ",";
							$news_member_name_cord .= $user_data[$value] . ",";
						}
						
						$news_member_input_cord = substr($news_member_input_cord, 0, -1);
						$news_member_name_cord = substr($news_member_name_cord, 0, -1);
					}

					//echo $news_member;
		
				?>

				<button id="open-popup" type="button">対象者リストを開く</button>

				<div>
					<div id="result"><?php echo $news_member_name_cord;?></div>
				</div>

				<input type="hidden" name="news_member" id="news_member" value="<?php echo $news_member_input_cord;?>" style="">
			</div>
			
		
		</div>
		<div class="user-table-flex">
			<div class="user-table-item"  style="min-width: 135px;">告知</div>
			<div>
				<div>
					<input type="checkbox" name="news_post" id="news_post"  <?php if(get_field('acf_news_post', $news_id) != ""){?> checked<?php } ?> style="">告知する
				</div>
				<div style="font-size: 10px;color: red;">
					*告知すると会員が確認するが必要になります。再告知する場合は一度、チェックを外して保存した後、再チェックをしてください
				</div>
			</div>
		</div>
		<div class="user-table-flex">
			<div class="user-table-item"  style="min-width: 135px;">表示</div>
			<div>
				<div>
					<input type="checkbox" name="news_disp" id="news_disp"  <?php if(get_field('acf_news_disp', $news_id) != ""){?> checked<?php } ?> style="">表示する
				</div>
				<div style="font-size: 10px;color: red;">
					*表示すると会員に表示されます
				</div>
			</div>
		</div>
		<div class="user-table-flex">
			<div class="user-table-item"  style="min-width: 135px;">表示日</div>
			<div>
				<div>
					<input type="date" name="news_disp_date" value="<?php echo get_field('acf_news_disp_date', $news_id); ?>">
				</div>
				<div style="font-size: 10px;color: red;">
					*表示日を設定すると会員には表示日が表示されます。設定がない場合は作成日が表示されます。
				</div>
			</div>
		</div>

		<div style="max-width: 500px;margin-left: auto;margin-right: auto;margin-top: 30px;" >
			<button  type="submit" class="admin-remote-make-arami-button" style="width: 100%;font-size: 18px;" >保存する</button>
		</div>

		<div style="text-align: center;margin-top: 10px;color: red;">
			<?php echo $up_data_message;?>
		</div>

	</form>


	<?php 
	
		//告知しており、対象者指定の時のみ未読リスト表示(全員だと多すぎる)
		if(get_field('acf_news_post', $news_id) != "" && get_field('acf_news_all_post', $news_id) == "specific_users")
		{
			$news_member = get_field('acf_news_member', $news_id);

			$news_member = json_decode($news_member, true);  //jsonデータ戻し

			$no_individual_array = array();

			foreach ($news_member as $key => $value) {

				if(!get_userdata( $value ) )
				{
					continue;//存在しない
				}

				//未読
				if(!$newsClass->checkUnreadPost($value , $news_id))
				{
					array_push($no_individual_array,$value);
				}
			}

			if(count($no_individual_array) > 0)
			{
	?>
			<div class="admin-news-no-individual-area">

				<div class="admin-news-no-individual-title">【未読者一覧】</div>

				<div class="admin-news-no-individual">

					<?php foreach ($no_individual_array as $key => $value) {?>

						<?php echo $user_data[$value];?>,

					<?php } ?>

				</div>
			</div>
	<?php
			}
		}
	
	?>



	<div class="admin-news-text-contens">

		<div class="admin-news-text-edit-button-area">
			<form action="<?php echo home_url(); ?>/wp-admin/post.php?post=<?php echo $news_id;?>&action=edit" method="post" style="" target="_blank">
				<button type="submit"  class="admin-temporary-registration-new-post-submit" style="width: 100%;border-radius: 6px;height: 45px;">お知らせ内容を編集</button>
			</form>
		</div>

		<div class="admin-news-text-edit-button-message">
			内容を編集した場合、この画面を更新しないと変更されません。<br>
			上記の設定を保存する場合は「保存する」ボタンを押してもらい、保存しない場合は画面を更新してください。

		</div>

		<div class="admin-news-text-box">

			<?php $post_contens = get_post_field('post_content', $news_id); ?>
			<div class="admin-news-text-area">
				<?php if($post_contens !=  ""){ ?>


					<?php 
                
							$post_contens = wpautop($post_contens);

								// 許可するHTML要素と属性を指定
							$allowed_tags = wp_kses_allowed_html('post');
							$allowed_tags['img'] = array(
								'src' => true,
								'alt' => true,
								'title' => true,
								'width' => true,
								'height' => true,
							);
					?>


				<?php } ?>

									
				<?php echo  wp_kses($post_contens, $allowed_tags);?>

			</div>

		</div>


	</div>





</div>


<script>

	const userNames = <?php echo json_encode($user_data); ?>;
	let lastSentData = []; // 直前に送信されたデータを格納する変数
	const dispPostUser = document.getElementById('news_member');

	document.getElementById('open-popup').addEventListener('click', function (event) {

		event.preventDefault(); // 親フォームの送信を防ぐ

		// データをクエリパラメータに変換してポップアップに渡す
       const initialValues = dispPostUser.value.length > 0 
            ? '?selected=' + encodeURIComponent(dispPostUser.value) 
            : '';

        // 別ウィンドウをポップアップとして開く
        const popupWidth = 1400;
        const popupHeight = 800;
        const left = (screen.width - popupWidth) / 5;
        const top = (screen.height - popupHeight) / 2;

        window.open(
            '<?php echo getURLSetSlag("admin-news-postmember"); ?>'  + initialValues, // ここをPHPファイルにしてもOK
            'popupWindow',
            `width=${popupWidth},height=${popupHeight},top=${top},left=${left},resizable=yes,scrollbars=yes`
        );
    });

	// ポップアップからデータを受け取る関数
	function displayPopupData(data) {
		lastSentData = data; // 受け取ったデータを保持

		let name_chage_data = "";

		// データが空でなければループで値を結合
		if (data.length > 0) {
			data.forEach(function(value, index) {
				// カンマを先頭につけないように調整
				name_chage_data += (index > 0 ? ", " : "") + userNames[value];
			});
		} else {
			name_chage_data = "選択された項目がありません";
		}

		// 結果を表示
		dispPostUser.value =  data.join(',');
		document.getElementById('result').innerHTML = `${name_chage_data}`;
	}

    // グローバルに関数を登録する（ポップアップで使用するため）
    window.displayPopupData = displayPopupData;
	



	//送信チェック
	function save_check(){
		const dispPostTypeselectedValue = document.querySelector('input[name="acf_news_all_post"]:checked').value;

		//対象者無し
		if(dispPostTypeselectedValue == "no_users")//対象者無し
		{

			
			const news_post_checkbox = document.getElementById('news_post');
			const news_post_isChecked = news_post_checkbox.checked;

			if(news_post_isChecked)
			{
				alert("対象者がない場合は告知できません");

				return false; // 送信を中止
			}

			const news_disp_checkbox = document.getElementById('news_disp');
			const news_disp_isChecked = news_disp_checkbox.checked;

			if(news_disp_isChecked)
			{
				alert("対象者がない場合は表示できません");

				return false; // 送信を中止
			}
    
		}
		else if(dispPostTypeselectedValue == "specific_users")//対象者指定
		{
			if(dispPostUser.value == "")//IDがない
			{
				alert("対象者が指定されていません");

				return false; // 送信を中止
			}
		}

		

		return true; // 送信を中止

	}

</script>
      
