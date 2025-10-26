<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo get_template_directory_uri()."/custompage/admin/a-gate-script.js"?>"></script>

<?php 
    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");

    // 流入データ
    $spirit_sheet_data = new spiritSheetClass(); //管理データ
    $spiritType = new SpiritTypeClass(); //管理データ
    $spiritTypeArray = $spiritType->getSpiritType();
    $spiritStatusArray = $spirit_sheet_data->getSpiritAdminStatusID('cpt_spirit_status');
    $spiritMemberStatusArray = $spirit_sheet_data->getSpiritAdminStatusID('cpt_spirit_usestatus');

   //自分の情報
   $user = wp_get_current_user();

    //全員のユーザー情報作成
    $users = get_users();


    $user_split_data = array();

    foreach($users as $user) {
        $uid = $user->ID; 


        if(get_user_meta($uid,'spirit_data',true) != "")
        {
            
            $spiritTypeJsonArray = json_decode(get_user_meta($uid,'spirit_data',true));
        
        
            foreach ($spiritTypeJsonArray as $key => $value) {


                $user_split_data[$value] = get_field('acf_purespirit_requested_date',$value);;

            }
        }
    }

    arsort($user_split_data);



//   表示検索
// // 表示絞り込み保存
if(isset($_POST["disp-squeeze"]))
{
    $json_data = json_encode($_POST['disp-squeeze-content']);
    $res = update_user_meta(get_current_user_id(),'sprit_sheet_search',$json_data);   // ユーザーに関連名付与

}
$current_user_disp_squeeze = json_decode(get_user_meta(get_current_user_id(),'sprit_sheet_search',true));//表示設定取得
SettingModalDisp($spiritTypeArray,$current_user_disp_squeeze,true);

  //検索リセット
  if(isset($_POST["search_reset"]))
  {
      update_user_meta($user->ID,"sprit_sheet_search","");
  }


  //検索の保存情報を取得する

  $search_json_data = get_user_meta( $user->ID,'sprit_sheet_search',true);

  $search_save_data = array();

  if($search_json_data != "")
  {
      $search_save_data = json_decode($search_json_data, true);

    }
    
?>





<div class="admin-profile-edit-area" style="max-width: 1700px;">

 <div class="admin-profile-menu-button-box">
            <button type="button" class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag('admin-spirit-schedule-list'); ?>'" style="width: 100%;font-size: 30px;height: 80px;">浄霊スケジュール</button>
        </div>
    <div class="admin-profile-edit-title-box">
        <div class="admin-exorcism-menu-title" style="text-align: center;">浄霊シート一覧</div>
    </div>



    <?php  if($user_split_data != NULL){?>

        <div class="admin-spirit-delete-alert-text">シートの削除は対象者の詳細にて操作してください</div>

        
        <button type="button" class="form-btn" onclick="setting_modal()" style="margin:unset">表示設定</button>


        <div class="admin-spritsheet-search-area">

            <form action="<?php echo getURLSetSlag("admin-spirit-sheets-list"); ?>" method="post">


                <div class="admin-spritsheet-search-flex" >
                    <div class="admin-spritsheet-search-type" >
                </div>

            </form>
        </div>


        <table id="userTable" class="user-disp-table table table-bordered">
            <thead>
                <tr>
                    
                    <th>決済日</th><!-- 8 -->
                    <th>依頼日</th><!-- 6 -->
                    <th>依頼確定日</th><!-- 7 -->
                    <th style="width:200px">依頼内容</th><!-- 1 -->
                    <th style="min-width: 120px;">ステータス</th><!-- 11 -->
                    <th style="min-width: 120px;">会員ステータス</th>
                    <th>申込者名</th><!-- 3 -->
                    <th>ヨミカタ</th>
                    <th>グループ</th>
                    <th>実行日</th><!-- 9 -->
                </tr>
            </thead>
            <tbody>
                <?php  
            
                    $sheet_no = 0;
                    
                    $group_setting_data = new GroupSettingClass(); //グループクラス
                    $group_list = $group_setting_data->getGroupData();
                    foreach ($user_split_data as $key => $value) {  
                
                        $sprit_type = get_field('acf_acf_purespirit_type',$value);
                        $is_delete = get_field("is_delete", $value);

                        // 依頼内容が絞り込み対象に入ってい無ければ除外
                        $disp_squeeze_id = get_field('acf_acf_purespirit_type',$key);
                        if(isset($current_user_disp_squeeze) && $disp_squeeze_id != ""){
                            if(!property_exists($current_user_disp_squeeze, $disp_squeeze_id)) continue;
                        }

                        if(get_field('acf_pure_spirit_title',get_field('acf_acf_purespirit_type',$key)) == "")continue;

                        if($is_delete) continue;    //削除されたシートは表示しない

                        //依頼日
                        $requested_date = get_field('acf_purespirit_requested_date',$key);

                        // 依頼日絞り込み
                        $d1 = new DateTime($requested_date);
                        if(isset($current_user_disp_squeeze->{'request_start'}) && $current_user_disp_squeeze->{'request_start'} != ""){
                            $d2 = new DateTime($current_user_disp_squeeze->{'request_start'});

                            // if($d1 <= $d2)
                            if($d1 < $d2)
                            {
                                continue;
                            }
                        }
                        if(isset($current_user_disp_squeeze->{'request_end'}) && $current_user_disp_squeeze->{'request_end'} != ""){
                            $d2 = new DateTime($current_user_disp_squeeze->{'request_end'});

                            // if($d1 >= $d2)
                            if($d1 > $d2)
                            {
                                continue;
                            }
                        }

                        // 決行日
                        $execute_date = get_field('acf_purespirit_execution_date',$key);
                        $d1 = new DateTime($execute_date);
                        if(isset($current_user_disp_squeeze->{'request_start_execute'}) && $current_user_disp_squeeze->{'request_start_execute'} != ""){
                            $d2 = new DateTime($current_user_disp_squeeze->{'request_start_execute'});

                            // if($d1 <= $d2  || $execute_date == "" || $execute_date == null)
                            if($d1 < $d2  || $execute_date == "" || $execute_date == null)
                            {
                                continue;
                            }
                        }
                        if(isset($current_user_disp_squeeze->{'request_end_execute'}) && $current_user_disp_squeeze->{'request_end_execute'} != ""){
                            $d2 = new DateTime($current_user_disp_squeeze->{'request_end_execute'});

                            // if($d1 >= $d2 || $execute_date == "" || $execute_date == null)
                            if($d1 > $d2 || $execute_date == "" || $execute_date == null)
                            {
                                continue;
                            }
                        }

                        
                        //依頼確定日
                        $confirmation_date = get_field('acf_purespirit_request_confirmation_date',$key);

                        // 依頼確定日絞り込み
                        $d1 = new DateTime($confirmation_date);
                        if(isset($current_user_disp_squeeze->{'request_start_ok'}) && $current_user_disp_squeeze->{'request_start_ok'} != ""){
                            $d2 = new DateTime($current_user_disp_squeeze->{'request_start_ok'});

                            // if($d1 <= $d2 || $confirmation_date == "" || $confirmation_date == null)
                            if($d1 < $d2 || $confirmation_date == "" || $confirmation_date == null)
                            {
                                continue;
                            }
                        }
                        if(isset($current_user_disp_squeeze->{'request_end_ok'}) && $current_user_disp_squeeze->{'request_end_ok'} != ""){
                            // 依頼日開始日絞り込み
                            $d2 = new DateTime($current_user_disp_squeeze->{'request_end_ok'});

                            // if($d1 >= $d2 || $confirmation_date == "" || $confirmation_date == null)
                            if($d1 > $d2 || $confirmation_date == "" || $confirmation_date == null)
                            {
                                continue;
                            }
                        }


                        // ステータス　除外
                        $status = get_field('acf_purespirit_status',$key);
                        if(isset($current_user_disp_squeeze->{'status'}) && $current_user_disp_squeeze->{'status'} != "0"){
                            // 未確認絞り込みの除外処理
                            if(  !($status == "" && $current_user_disp_squeeze->{'status'} == 1) && $status != $current_user_disp_squeeze->{'status'}) continue;
                        }

                        //会員ステータス
                        $membet_status =  get_field('acf_purespirit_user_status',$key);


                        // ここからデータ作成
                        //グループの絞り込み表示除外
                        $user_introduction_id = get_user_meta(get_field('acf_purespirit_id',$key),'input_introduction_id',true);    //紹介者
                        

                        $purespirit_id = get_field('acf_purespirit_id',$key);

                        // $user_group_data = json_decode(get_user_meta($user_introduction_id,'group_data',true));// グループ
                        $user_group_data = json_decode(get_user_meta($purespirit_id,'group_data',true));// グループ


                        // $user_group_data = json_decode(get_user_meta($user_introduction_id,'group_data',true));// グループ

                        $group_list_add_flg = false;
                        if($user_group_data != null){
                            $group_list_data = array();
                            foreach ($user_group_data as $group_data_key) {
                                foreach ($group_list as $key2) {
                                    if ($key2['ID'] == $group_data_key) {
                                        $group_list_data[] = $key2['title'];
                                        $group_list_add_flg = true;
                                        break;
                                    }
                                }
                            }
                        }
                        // グループ絞り込みがある場合
                        if(isset($current_user_disp_squeeze->{'group'}) && $current_user_disp_squeeze->{'group'} != ""){
                            $group_check = false;
                            if($user_group_data != ""){
    
                                foreach ($user_group_data as $g_key => $g_value) {
                                    if($g_value == $current_user_disp_squeeze->{'group'}) $group_check = true;
                                }
    
                            }
                            if(!$group_check) continue;
                        }
                        $user_split_sheet_data[$sheet_no]['group_data'] = $user_group_data;
                        if($group_list_add_flg) $user_split_sheet_data[$sheet_no]['purespirit_group'] = $group_list_data;
                        else $user_split_sheet_data[$sheet_no]['purespirit_group'] = "";

                        //依頼内容
                        $search_sprit_type = get_field('acf_acf_purespirit_type',$key);

                        if( isset($search_save_data['search_sprit_type']) && $search_save_data['search_sprit_type'] != "")
                        {

                            if($search_save_data['search_sprit_type'] != $search_sprit_type)
                            {
                                continue;
                            }
                        }

                        //ステータス
                        if($status == "")
                        {
                            $user_split_sheet_data[$sheet_no]['status'] = "未確認";
                        }
                        else
                        {
                            $user_split_sheet_data[$sheet_no]['status'] = $spiritStatusArray[$status]["title"];
                        }

                        if( isset($search_save_data['search_sprit_status']) && $search_save_data['search_sprit_status'] != 0)
                        {
                            if($search_save_data['search_sprit_status'] != $status)
                            {
                                continue;
                            }
                        }

                         //会員ステータス
                        if($membet_status == "")
                        {
                            $user_split_sheet_data[$sheet_no]['membet_status'] = "未確認";
                        }
                        else
                        {
                            $user_split_sheet_data[$sheet_no]['membet_status'] =  $spiritMemberStatusArray[$membet_status]["title"];;
                        }

                    

                        //開始日
                        if( isset($search_save_data['search_sprit_request_start']) && $search_save_data['search_sprit_request_start'] != "")
                        {

                            if($requested_date == "")
                            {
                                continue;
                            }

                            $d1 = new DateTime($requested_date);
                            $d2 = new DateTime($search_save_data['search_sprit_request_start']);

                            if($d1 < $d2)
                            {
                                continue;
                            }
                        }

                         //終了日
                        if( isset($search_save_data['search_sprit_request_end']) && $search_save_data['search_sprit_request_end'] != "")
                        {

                            if($requested_date == "")
                            {
                                continue;
                            }

                            $d1 = new DateTime($requested_date);
                            $d2 = new DateTime($search_save_data['search_sprit_request_end']);

                            if($d1 > $d2)
                            {
                                continue;
                            }
                        }

                        //開始日
                        if( isset($search_save_data['search_sprit_requestconfirmed_start']) && $search_save_data['search_sprit_requestconfirmed_start'] != "")
                        {

                            if($confirmation_date == "")
                            {
                                continue;
                            }

                            $d1 = new DateTime($confirmation_date);
                            $d2 = new DateTime($search_save_data['search_sprit_requestconfirmed_start']);

                            if($d1 < $d2)
                            {
                                continue;
                            }
                        }

                         //終了日
                        if( isset($search_save_data['search_sprit_requestconfirmed_end']) && $search_save_data['search_sprit_requestconfirmed_end'] != "")
                        {

                            if($confirmation_date == "")
                            {
                                continue;
                            }

                            $d1 = new DateTime($confirmation_date);
                            $d2 = new DateTime($search_save_data['search_sprit_requestconfirmed_end']);

                            if($d1 > $d2)
                            {
                                continue;
                            }
                        }

                        //決済日
                        $payment_date = get_field('acf_purespirit_payment_date',$key);



                        //開始日
                        if( isset($search_save_data['search_sprit_execution_start']) && $search_save_data['search_sprit_execution_start'] != "")
                        {

                            if($payment_date == "")
                            {
                                continue;
                            }

                            $d1 = new DateTime($payment_date);
                            $d2 = new DateTime($search_save_data['search_sprit_execution_start']);

                            if($d1 < $d2)
                            {
                                continue;
                            }
                        }

                         //終了日
                        if( isset($search_save_data['search_sprit_execution_end']) && $search_save_data['search_sprit_execution_end'] != "")
                        {

                            if($payment_date == "")
                            {
                                continue;
                            }

                            $d1 = new DateTime($payment_date);
                            $d2 = new DateTime($search_save_data['search_sprit_execution_end']);

                            if($d1 > $d2)
                            {
                                continue;
                            }
                        }

                        $user_split_sheet_data[$sheet_no]['acf_pure_spirit_title'] = get_field('acf_pure_spirit_title',$search_sprit_type);    //依頼内容
                        $user_sheet_edit_url = getURLSetSlag("admin-spirit-detail") . '?user_id=' . get_field('acf_purespirit_id', $key) . '&sheet_name=' . $key;    //詳細URL
                        $user_split_sheet_data[$sheet_no]['user_edit_url'] = '<button class="edit-mark" style="width: 30px;font-size: 15px;"><a href="'.$user_sheet_edit_url.'" target="_blank">編</a></button>';    //詳細ボタン
                        
                        // 依頼者
                        $user_last_name_kana = get_user_meta(get_field('acf_purespirit_id',$key),'last_name',true);
                        $user_first_name_kana = get_user_meta(get_field('acf_purespirit_id',$key),'first_name',true);
                        $purespirit_url = getURLSetSlag("admin-member-edit") . '?user_id=' . get_field('acf_purespirit_id', $key);
                        $user_split_sheet_data[$sheet_no]['purespirit_name'] = '<a href="'.$purespirit_url.'"  target="_blank">'.$user_last_name_kana.$user_first_name_kana.'</a>';
                        $user_split_sheet_data[$sheet_no]['purespirit_name_kana'] = get_user_meta(get_field('acf_purespirit_id',$key),'last_name_kana',true).get_user_meta(get_field('acf_purespirit_id',$key),'first_name_kana',true);

                        // 申込者
                        if(get_field('acf_applicant',$key) != "")
                        {
                            $user_last_name_kana = get_user_meta(get_field('acf_applicant',$key),'last_name',true);
                            $user_first_name_kana = get_user_meta(get_field('acf_applicant',$key),'first_name',true);

                            $applicant_url = getURLSetSlag("admin-member-edit") . '?user_id=' . get_field('acf_applicant', $key)[0];
                            $user_split_sheet_data[$sheet_no]['applicant_name'] = '<a href="'.$applicant_url.'"  target="_blank">'.$user_last_name_kana.$user_first_name_kana.'</a>';
                            
                        }else{
                            $user_split_sheet_data[$sheet_no]['applicant_name'] = "";
                        }

                        // 紹介者                        
                        
                        if($user_introduction_id != "")
                        {

                            $user_last_name_kana = get_user_meta($user_introduction_id,'last_name',true);
                            $user_first_name_kana = get_user_meta($user_introduction_id,'first_name',true);
                            $introduction_url = getURLSetSlag("admin-member-edit") . '?user_id=' . $user_introduction_id;
                            $user_split_sheet_data[$sheet_no]['introduction_name'] = '<a href="'.$introduction_url.'"  target="_blank">'.$user_last_name_kana.$user_first_name_kana.'</a>';
                            
                            
                        }else{
                            $user_introduction_name = get_user_meta(get_field('acf_purespirit_id',$key),'input_introduction_name',true);
                            
                            $user_split_sheet_data[$sheet_no]['introduction_name'] = $user_introduction_name;
                            // $user_split_sheet_data[$sheet_no]['introduction_name_kana'] = "";
                        }
                        $user_split_sheet_data[$sheet_no]['acf_purespirit_requested_date'] = get_field('acf_purespirit_requested_date',$key);    //依頼日
                        $user_split_sheet_data[$sheet_no]['acf_purespirit_request_confirmation_date'] = get_field('acf_purespirit_request_confirmation_date',$key);    //依頼確定日
                        $user_split_sheet_data[$sheet_no]['acf_purespirit_payment_date'] = get_field('acf_purespirit_payment_date',$key);    //結構日
                        $user_split_sheet_data[$sheet_no]['acf_purespirit_execution_date'] = get_field('acf_purespirit_execution_date',$key);    //実行日
                        $user_split_sheet_data[$sheet_no]['acf_purespirit_add_text'] = get_field('acf_purespirit_add_text',$key);    //依頼内容追記
                        $user_split_sheet_data[$sheet_no]['ID'] =$key;

                        // a-gate
                        if(get_field('acf_agate_hands_on',$key) != "")
                        {
                            $user_split_sheet_data[$sheet_no]['a-gate'] = "〇";
                        }else{
                            $user_split_sheet_data[$sheet_no]['a-gate'] = "";

                        }

                        $sheet_no++;
                ?>
                    </tr>
                

                <?php } ?>


            </tbody>

        </table>

    <?php }?>
    <?php // var_dump($user_split_sheet_data); ?>

</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 

    
    <script>

    // 浄霊シートデータをここに全て格納
    var userData = <?php echo json_encode($user_split_sheet_data); ?>;

    $(document).ready( function () {

        $('#userTable').DataTable({
            data: userData,
            // scrollX: true,
            columns: [
                { data: 'acf_purespirit_payment_date' },          //8決済日
                { data: 'acf_purespirit_requested_date' },          //6依頼日
                { data: 'acf_purespirit_request_confirmation_date' },          //7依頼鑑定日
                { data: 'acf_pure_spirit_title' },  //1
                { data: 'status' },          //11実行日依頼内容追記ステータス
                { data: 'membet_status' },          //11会員ステータス
                { data: 'purespirit_name' },          //3依頼者
                { data: 'purespirit_name_kana' },   //ヨミカタ
                { data: 'purespirit_group' },   //グループ
                { data: 'acf_purespirit_execution_date' },          //9実行日
            ],
            
            "order": [[0, "dec"]], // 第2列（インデックス2）を昇順（asc）にソート
            "paging": false, // ページネーションを非表示
            "pageLength": -1,  // 初期表示件数
            // "order": [[0, "asc"]],  // 0列目を昇順("asc")にソート
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
            }

        });
    } );

</script>

