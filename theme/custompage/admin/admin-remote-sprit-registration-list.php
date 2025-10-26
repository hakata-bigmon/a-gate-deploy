<?php

	require_once ("a-gate-functions.php");

	require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
	require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");

	$spiritType = new SpiritTypeClass(); //管理データ
	$spiritTypeArray = $spiritType->getSpiritType();

	$spiritSheet = new SpiritSheetClass(); //管理データ
	
   //var_dump($_POST);




   //検索登録
   if(isset($_POST["search"]))
   {
       $search_array = array();

       foreach ($_POST as $key => $value) {
           $search_array[ $key ] = $value;
       }

       update_user_meta(get_current_user_id(),'search_remote_register_list',json_encode($search_array, JSON_UNESCAPED_UNICODE));
   }
    //検索リセット
   if(isset($_POST["search_reset"]))
   {
       update_user_meta(get_current_user_id(),'search_remote_register_list',"");
   }

   //検索取得
   $current_user_search_data = get_user_meta(get_current_user_id(),'search_remote_register_list',true);//表示設定取得
   
   if($current_user_search_data != "-" && $current_user_search_data != null){

        $current_user_search_data = json_decode($current_user_search_data, true);//表示設定取得
   }

   if($current_user_search_data == "")
   {
       $current_user_search_data = array();
   }
   


   //シート削除
   if(isset($_POST["sheet_delete"]))
   {
        wp_delete_post($_POST["sheet_id"], true);
   }


   //リモート浄霊一覧を取得
   $remote_array = $spiritSheet->getRemoteSpritTentative(  );

  
  // var_dump($remote_array);
?>


<div class="admin-user-table-area">

	<div class="admin-title">
		<?php echo "リモート浄霊仮登録一覧（WEB）"; ?>
	</div>

     <div style="font-size: 18px;font-weight: 600;color: red;margin-bottom: 20px;">
        WEBから登録された情報を編集できます。<br>
        対象者の設定と、まだ登録されていないリモート浄霊のシートを選択してください。
    </div>

	<?php if(count($remote_array) > 0){ ?>

       

        <div class="admin-temporary-registration-search-area">

            <div class="admin-temporary-registration-search-flex">

                <form action="<?php echo getURLSetSlag("admin-remote-sprit-registration-list"); ?>" method="post">

                    <div class="admin-temporary-registration-search-flex">

                        <div class="admin-temporary-registration-input-box">

                            <div class="admin-temporary-registration-input-label">依頼日</div>

                            <div class="admin-temporary-registration-input-flex">
                                <div class="admin-temporary-registration-input-form">
                                    <input type="date" id="" name="remote_list_search_requested_start" value="<?php  if(isset($current_user_search_data["remote_list_search_requested_start"])){ echo $current_user_search_data["remote_list_search_requested_start"];}?>">
                                </div>
                
                                <div style="margin-left: 10px;margin-right: 10px;">～</div>

                                <div class="admin-temporary-registration-input-form">
                                    <input type="date" id="" name="remote_list_search_requested_end" value="<?php if(isset($current_user_search_data["remote_list_search_requested_end"])){echo $current_user_search_data["remote_list_search_requested_end"];}?>">
                                </div>
                            </div>

                        </div>

                       


                        <button class="admin-remote-search-button">絞り込む</button>

                         <input type="hidden" name="search" value="">
                    </div>

                </form>

                <form action="<?php echo getURLSetSlag("admin_remote_sprit_list"); ?>" method="post">

                    <button class="admin-remote-search-reset-button">リセット</button>

                    <input type="hidden" name="search_reset" value="">
                </form>

            </div>
        </div>

       



    <div class="admin-temporary-registration-check-table">

		<table id="userTable" class="user-disp-table table table-bordered">
            <thead>
                <tr>
                    <th>シートID</th>
                    <th style="width: 150px;">編集</th>
                    <th>申込者</th>
                    <th>対象者数</th>
                    <th>依頼日</th>
                    <th>登録者</th>
                    <th>新規</th>
                </tr>
            </thead>
            <tbody>
                <?php 

                    
                                         
                    foreach ($remote_array as $key => $value) {



                        $input_day = get_field('acf_remote_sprit_sheet_target_input_date' ,$value);

                        if($input_day != "")
                        {
                            $input_day =  date('Y-m-d',strtotime($input_day));
                        }

                        //依頼日絞り込み
                        if(isset($current_user_search_data["remote_list_search_requested_start"]))
                        {

                            if($current_user_search_data["remote_list_search_requested_start"] != "")
                            {
                                if($input_day == "")
                                {
                                    continue;
                                }

                                $time1 = strtotime($current_user_search_data["remote_list_search_requested_start"]);
                                $time2 = strtotime($input_day);

                                  

                                if($time1 > $time2)
                                {
                                        continue;
                                }
                            }

                        }


                        if(isset($current_user_search_data["remote_list_search_requested_end"]))
                        {

                            if($current_user_search_data["remote_list_search_requested_end"] != "")
                            {
                                if($input_day == "")
                                {
                                    continue;
                                }

                                $time1 = strtotime($current_user_search_data["remote_list_search_requested_end"]);
                                $time2 = strtotime($input_day);

                                  

                                if($time1 < $time2)
                                {
                                        continue;
                                }
                            }

                        }


                        //枠素取得
                        $slots = get_field('acf_remote_sprit_sheet_target_slots',$value);

                        $applicant_id = get_field('acf_remote_sprit_sheet_applicant_id' ,$value);

                        //登録数

                        $input_num = 0;

                        for($i=1;$i<=10;$i++)
                        {
                            if(get_field('acf_remote_sprit_sheet_target_number_' .$i ,$value) != "")
                            {
                                $input_num++;
                            }
                        }

                ?>
                        <tr>
                            <td  style=" text-align: center;">
                               <?php echo $value; ?>
                            </td>

                            <td  style=" text-align: center;padding: 0;display: flex">
                               <form action="<?php echo getURLSetSlag("admin-remote-sprit-registration-detail"); ?>" method="post" >
                                    <input type="hidden" name="sheet_id" value="<?php echo $value;?>">
                                    <button type="submit" class="edit-mark" style="width: 60px;font-size: 18px;margin-left: 5px;margin-right: 5px;">編集</button>
                                </form>
                            

                               <form action="<?php echo getURLSetSlag("admin-remote-sprit-registration-list"); ?>" method="post" onSubmit="return delete_check()" >
                                    <input type="hidden" name="sheet_id" value="<?php echo $value;?>">
                                    <input type="hidden" name="sheet_delete" value="">
                                    <button type="submit" class="edit-mark" style="background-color:blue; width: 60px;font-size: 18px;margin-left: 5px;margin-right: 5px;">削除</button>
                                </form>
                            </td>
                                
                            <td  style=" text-align: center;">
                                <a href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $applicant_id;?>"  target="_blank">
                                    <?php echo get_user_meta($applicant_id,'last_name',true); ?>　<?php echo get_user_meta($applicant_id,'first_name',true); ?>
                                </a>
                            </td>
                            <td  style=" text-align: center;">
                                <?php echo $slots; ?>
                            </td>
                            <td  style=" text-align: center;">
                                <?php echo  get_field('acf_remote_sprit_sheet_target_input_date' ,$value); ?>
                            </td>
                            <td  style=" text-align: center;">
                                <?php echo $slots - $input_num; ?>人
                            </td>
                            <td  style=" text-align: center;">
                                  <?php echo $input_num; ?>人
                            </td>

                          
                             
                        </tr>
                <?php
                        
                    }
                ?>


            </tbody>

        </table>

	<?php } ?>


</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 

    
    <script>

    // 浄霊シートデータをここに全て格納
    var userData = <?php echo json_encode($up_date_array); ?>;

    /*$(document).ready( function () {

        $('#userTable').DataTable({
            data: userData,
            scrollX: true,
            columns: [
                { data: 'acf_pure_spirit_title' },
                { data: 'user_edit_url' },          //詳細ボタン
                { data: 'purespirit_name' },          //依頼者
                { data: 'applicant_name' },          //申込者
                { data: 'introduction_name' },          //紹介者
                { data: 'acf_purespirit_requested_date' },          //依頼日
                { data: 'acf_purespirit_request_confirmation_date' },          //依頼鑑定日
                { data: 'acf_purespirit_payment_date' },          //決済日
                { data: 'acf_purespirit_execution_date' },          //実行日
                { data: 'acf_purespirit_add_text' },          //実行日依頼内容追記
                { data: 'status' },          //実行日依頼内容追記ステータス
                { data: 'a-gate' },          //a-gate
            ],
            
            "order": [[0, "dec"]], // 第2列（インデックス2）を昇順（asc）にソート
            "paging": false, // ページネーションを非表示
            "pageLength": -1,  // 初期表示件数
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
            }

        });
    } );
    */

</script>

<script type="text/javascript"> 
<!-- 



function delete_check(){

	if(window.confirm('このシートを削除してもよろしいですか？')){ // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

	}
	else{ // 「キャンセル」時の処理

		window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止

	}

}


// -->
</script>