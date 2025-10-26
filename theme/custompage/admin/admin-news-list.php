

<?php 

    require_once ("a-gate-functions.php");
	require_once (dirname(__FILE__)."/../../class/spiritNewsClass.php");
	require_once (dirname(__FILE__)."/../../class/mailTextClass.php");


    $mailText = new MailTextClass();
    $newsClass = new SpiritNewsClass();



    //個別からの保存
    if( isset($_POST["news_type"]) && $_POST["news_type"] == "personal")
    {
        
        //差し戻しデータを取得
        $mail_text_array = $mailText->sendSetCommonMail( $_POST["user_id"] , $_POST["news_title"] , $_POST["news_body"],true,true);

        $unix_time_array["mail_send_unixtime"] = $_POST["update_unixtime"];
        //ニュースデータの作成
        $news_id = $newsClass->createNewsDataMail($_POST["user_id"],$unix_time_array,$mail_text_array);

        //お知らせが作成されていなかったので差し戻しに変更（二重送信防止）
        if($news_id != "")
        {
            //メールを送信する
            $mailText->sendSetCommonMail( $_POST["user_id"] , $_POST["news_title"] , $_POST["news_body"],false);
        }


    }



    //削除
    if( isset($_POST["news_delete"]))
    {
         wp_delete_post($_POST["news_delete"], true);
    }



    $newsArray = $newsClass->getNewsData();


    $user_data = $newsClass->getNewsPostUSer(false);//ユーザーのデータ取得

?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">


<div class="admin-user-table-area">


    <div class="admin-title">
		<?php echo "お知らせ一覧"; ?>
	</div>

    <div class="">
       
        <div style="margin-bottom: 20px;text-align: right;">
            <button class="admin-preview-button" type="button"  style="background-color: lightgray;cursor: pointer;"  onclick="window.location.href='<?php echo getURLSetSlag("admin_news_menu"); ?>'">お知らせメニュー</button>
        </div>
    </div>


	<?php if(count($newsArray) >= 1){?>

        <div class="admin-temporary-registration-check-table" style="">

                
            <table id="sort-table" class="display">

                <thead>
                    <tr>
                        
                        <th style="width: 160px;"></th>
                        <th>投稿日付</th>
                        <th>タイトル</th>
                        <th>対象</th>
                        <th>対象者</th>
                        <th>告知</th>
                        <th>表示</th>
                        <th>表示日</th>
                    </tr>
                </thead>
                <tbody>
                       
                    <?php  foreach ($newsArray as $key => $value) {?>
                        <tr>
                           
                            <td>
                            
                                <div style="display: flex;width: 160px;">

                                    <form action="<?php echo home_url(); ?>/?post_type=cpt_news&p=<?php echo $key;?>&preview=true" method="post" target="_blank">
					                    <input type="hidden" name="arami_sheet_id" value="<?php echo $key;?>">
					                    <button type="submit"  class="admin-temporary-registration-new-post-submit" style="width: 50px;height: 30px;background-color: skyblue;">確</button>
				                    </form>

                                    <form action="<?php echo getURLSetSlag("admin-new-edit"); ?>" method="post" style="margin-left: 3px;">
					                    <input type="hidden" name="news_edit" value="<?php echo $key;?>">
					                    <button type="submit"  class="admin-temporary-registration-new-post-submit" style="width: 50px;height: 30px;">編</button>
				                    </form>

                                    <form action="<?php echo getURLSetSlag("admin-news-list"); ?>" method="post" style="margin-left: 3px;" onSubmit="return delete_check()">
					                    <input type="hidden" name="news_delete" value="<?php echo $key;?>">
					                    <button type="submit"  class="admin-temporary-registration-new-post-submit" style="width: 50px;height: 30px;background-color: red;color: white;">削</button>
				                    </form>
                                 </div>
                            
                            </td>
                            <td><?php echo $value["news_date"];?></td>
                            <td><?php echo $value["news_title"];?></td>
                            <td>
                            
                                <?php 
                                

                                    $post_user = $value["post_user"];

                                    $post_user_value ="対象者なし";

                                    if($post_user == "all_users")
                                    {
                                        $post_user_value ="全員";
                                    }
                                    else if($post_user == "specific_users")
                                    {
                                        $post_user_value ="指定";
                                    }
                                
                                ?>
                            
                            <?php echo $post_user_value;?>
                            
                            
                            </td>
                            <td>
                            
                                <?php 
                                    $news_member = get_field('acf_news_member', $key);

                                    $news_member_name_cord = "";

                                    $disp_member_count = 0;//表示会員名
                                    $member_count = 0;//(他何名などを表示する)

                                    //空じゃない場合、デコード
					                if($news_member != "")
					                {
						                $news_member = json_decode($news_member, true);  //jsonデータ戻し

						                //var_dump($news_member);

						                foreach ($news_member as $member_key => $member_value) {
						
                                            if($disp_member_count < 2)
                                            {
							                    $news_member_name_cord .= $user_data[$member_value] . ",";
                                            }
                                            else
                                            {
                                                $member_count++;
                                            }
                                            $disp_member_count++;

						                }
						
						               
						                $news_member_name_cord = substr($news_member_name_cord, 0, -1);
					                }
                                
                                ?>
                            
                            <?php echo $news_member_name_cord;?>
                            <?php if($member_count > 0){ echo "..他" .$member_count . "名"; };?>
                            
                            </td>
                            <td>
                                <?php
                                    if($value["news_post"] == "1")
                                    {
                                        echo "<font color='red'>告知済</font>";
                                    }
                                    else if($value["news_post"] == "")
                                    {
                                        echo "未告知";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    if($value["news_disp"] == "1")
                                    {
                                        echo "<font color='red'>表示済</font>";
                                    }
                                    else if($value["news_disp"] == "")
                                    {
                                        echo "非表示";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php


                                    $date = $value["news_disp_date"];

                                    if($date != "")
                                    {
                                        $date_time = new DateTime($date);

                                        $date = $date_time->format('Y年n月j日');
                                    }

                                    //get_the_date('Y年n月j日',$news_id)


                                    echo $date;
                                ?>
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