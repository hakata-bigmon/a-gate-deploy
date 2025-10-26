<?php 

    // 全浄霊内容
    function make_all_split_table($id,$user_split_data,$spirit_sheet_data,$check_user_id){
?>
    <table id="<?php echo $id ;?>" class="user-disp-table table table-bordered">
        <thead>
            <tr>
                <th style="width:200px">依頼内容</th>
                <th>詳細</th>
                <th>単価</th>
                <?php if($id != 'all_split_table'){ ?>
                <th>対象者</th>
                <?php /* 
                本人には申込者表示しない
                    theadとforeach無いのtdとdatatableの{ data: 'target' }削除
                    <?php }else{ ?>
                    <th>申込者</th>
                 */?>
                <?php } ?>
                <th style="width: 85px;">依頼日</th>
                <th style="width: 85px;">依頼確定日</th>
                <th style="width: 85px;">決済日</th>
                <th style="width: 85px;">実行日</th>
                <th>依頼内容追記</th>
                <th>ステータス</th>
                <th style="font-size: 10px;">A-GETE<br>確認</th>
            </tr>
        </thead>
        <tbody></tbody>

<?php 
        
        $total_money = 0;   //合計金額
        $all_split_table = array();
        $all_split_table_no = 0;

        foreach ($user_split_data as $key => $value) { 

            if($id != 'all_split_table'){

                $get_target = $key;
            }else{
                $get_target = $value;
            }
            $sprit_type = get_field('acf_acf_purespirit_type',$get_target);
            $is_delete = get_field("is_delete", $get_target);

            if($is_delete) continue;    //削除されたシートは表示しない
            if(isset($_GET['select_sprit_type']) && $sprit_type != $_GET['select_sprit_type']  && "" != $_GET['select_sprit_type']) continue;

            $action_url = getURLSetSlag("admin-member-edit").'?user_id='.$_GET['user_id'];
            $a_url = getURLSetSlag("admin-spirit-detail").'?user_id='.$_GET['user_id'].'&sheet_name='.$get_target;
            $all_split_table[$all_split_table_no]['title'] = get_field('acf_pure_spirit_title',get_field('acf_acf_purespirit_type',$get_target));

            //リモート浄霊の場合
            if(get_field('acf_purespirit_remote_sprit_num',$get_target) != "")
            {
                $all_split_table[$all_split_table_no]['title'] .=  "(" . get_field('acf_remote_sprit_sheet_target_slots',get_field('acf_purespirit_remote_sprit_num',$get_target)) . "人枠)";
            }

            if($id != 'all_split_table'){

                $all_split_table[$all_split_table_no]['btn'] = <<<EOT
                                                                <td>
                                                                    <div class="" style="display: flex;justify-content: center;">
                                                                        <button class="edit-mark" style="width: 30px;margin: 0;font-size: 15px;"><a href="$a_url">編</a></button>
                                                                    </div>
                                                                </td>
                                                                EOT;
            }else{
                $all_split_table[$all_split_table_no]['btn'] = <<<EOT
                                                                <td>
                                                                    <div class="" style="display: flex;">
                                                                        <button class="edit-mark" style="width: 30px;margin: 0;font-size: 15px;"><a href="$a_url">編</a></button>
    
                                                                        <form id="hidden_sheet_$get_target" action="$action_url" method="post" onclick="dispBtn($get_target)">
                                                                            <input type="hidden" name="sheet_name" value="$get_target">
                                                                            <input type="hidden" name="sheet_hidden" value="sheet_hidden">
                                                                            <button type="button" class="edit-mark gray"  style="width: 30px;margin: 0;margin-left: 5px;font-size: 15px;">削</button>
                                                                        </form>
    
                                                                        <form id="delete_sheet_$get_target" action="$action_url" method="post" onclick="dispBtn($get_target)">
                                                                            <input type="hidden" name="sheet_name" value="$get_target">
                                                                            <input type="hidden" name="sheet_delete" value="is_delete">
                                                                        </form>
                                                                    </div>
                                                                </td>
                                                                EOT;
            }
            
            $all_split_table[$all_split_table_no]['request_day'] = "";      //依頼日
            $all_split_table[$all_split_table_no]['request_ok_day'] = "";      //依頼確定日
            $all_split_table[$all_split_table_no]['request_payday'] = "";      //決済日
            $all_split_table[$all_split_table_no]['request_execute_day'] = "";      //実行日
            $all_split_table[$all_split_table_no]['request_content'] = "";      //依頼内容追記
            $all_split_table[$all_split_table_no]['request_status'] = "";      //ステータス
            $all_split_table[$all_split_table_no]['a-gate'] = "";
            $all_split_table[$all_split_table_no]['money'] = "";        //金額
            $all_split_table[$all_split_table_no]['target'] = "";       //申込者

            #region 浄霊項目
            //単価計算
            $single_money = get_field('acf_purespirit_price',$get_target);
            if($single_money == "") $single_money = 0;   //0設定

            $total_money += $single_money;
            $all_split_table[$all_split_table_no]['money'] = "&yen;" .number_format($single_money);
            
            $status = get_field('acf_applicant',$get_target);

            // 申込者
            if($id == 'all_split_table'){
                if($status != "")
                {
                    if($status == $check_user_id)
                    {
                        $all_split_table[$all_split_table_no]['target'] = "本人";
                    }else{
                        $all_split_table[$all_split_table_no]['target'] = get_user_meta($check_user_id,'last_name',true) . " " . get_user_meta($check_user_id,'first_name',true);
                    }
                }
            }else{

                $t_url = getURLSetSlag("admin-member-edit");
                $t_acf = get_field('acf_purespirit_id',$get_target);
                $t_last_name = get_user_meta(get_field('acf_purespirit_id',$get_target),'last_name',true);
                $t_first_name = get_user_meta(get_field('acf_purespirit_id',$get_target),'first_name',true);
                $all_split_table[$all_split_table_no]['target'] = "<a href='$t_url?user_id=$t_acf' target='_blank'> $t_last_name  $t_first_name</a>";
            }

            // 依頼日
            $date = get_field('acf_purespirit_requested_date',$get_target);
            if($date != ""){
                $all_split_table[$all_split_table_no]['request_day'] = date('Y年m月d日',strtotime($date));
            }

            // 依頼確定日
            $date = get_field('acf_purespirit_requested_date',$get_target);
            if($date != ""){
                $all_split_table[$all_split_table_no]['request_ok_day'] = date('Y年m月d日',strtotime($date));
            }

            // 決済日
            $date = get_field('acf_purespirit_request_confirmation_date',$get_target);
            if($date != ""){
                $all_split_table[$all_split_table_no]['request_payday'] =  date('Y年m月d日',strtotime($date));
            }

            // 実行日
            $date = get_field('acf_purespirit_payment_date',$get_target);
            if($date != ""){
                $all_split_table[$all_split_table_no]['request_execute_day'] =  date('Y年m月d日',strtotime($date));
            }

            // 依頼内容追記
            $add_text = get_field('acf_purespirit_add_text',$get_target);
            if($date != ""){
                $all_split_table[$all_split_table_no]['request_content'] =  $add_text;
            }

            // ステータス
            $status = get_field('acf_purespirit_status',$get_target);
            if($status == "")
            {
                $all_split_table[$all_split_table_no]['request_status'] =  "未確認";
            }
            else
            {
                $spiritStatusArray = $spirit_sheet_data->getSpiritAdminStatusID('cpt_spirit_status');
                $all_split_table[$all_split_table_no]['request_status'] =  $spiritStatusArray[$status]["title"];
            }

            // a-gate
            if(get_field('acf_agate_hands_on',$get_target) != "")
            {
                $all_split_table[$all_split_table_no]['a-gate'] =  "〇";
            }

            $all_split_table_no++;

            #endregion
        }
?>
        <tr>
            <td colspan="2" style="text-align: center; vertical-align: middle;">合計金額</td>
            <td style="font-weight: 700;" id="total_money_<?php echo $id; ?>">    <?php echo number_format($total_money);?></td>
            <?php if($id != 'all_split_table'){?>
            <td></td>
            <?php } ?>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
<?php 
        return $all_split_table;
    }

    
    // リモート浄霊内容
    function make_remote_split_table($id,$user_split_data,$spirit_sheet_data,$check_user_id){
?>

<?php 
    } 

    
/****************************************************
 **   ユーザーテーブル注釈表示
 ******************************************************/
function DispAnnotation()
{
    ?>
    <div class="table-annotation">
        ユーザーのテーブル情報をスクロールさせる際は、1度テーブルをクリックしてから矢印キーで動かす事が可能です。<br>
        また、shiftキーを押しながらマウスホイールでも動かす事が可能です。
    </div>
    <?php
}


/****************************************************
 **   ユーザーテーブル作成
 ******************************************************/
function dispUserTabale($disp_squeeze, $introduction_on, $spiritTypeArray,$id="")
{


    ?>
    <table id="userTable<?php echo $id;?>" class="user-disp-table table table-bordered">
        <thead>
            <tr>
                <?php
                if ($introduction_on) {
                    echo '<th style="z-index:1"></th>'; //<!-- 紹介者選択蘭 -->
                }

                if (checkSqueeze("disp-id", $disp_squeeze)) {
                    echo '<th style="z-index:1">ユーザーID</th>';
                }
                if (!$introduction_on) {
                    echo '<th style="z-index:1"></th>'; //編集削除蘭
                }
                if (checkSqueeze("disp-last-name", $disp_squeeze)) {
                    echo '<th style="z-index:1">苗字</th>';
                }
                if (checkSqueeze("disp_first_name", $disp_squeeze)) {
                    echo '<th style="z-index:1">名前</th>';
                }
                if (checkSqueeze("disp_last_kana", $disp_squeeze)) {
                    echo '<th>ミョウジ</th>';
                }
                if (checkSqueeze("disp_first_kana", $disp_squeeze)) {
                    echo '<th>ナマエ</th>';
                }
                if (checkSqueeze("disp-connection", $disp_squeeze)) {
                    echo '<th>関連</th>';
                }
                if (checkSqueeze("disp-group", $disp_squeeze)) {
                    echo '<th>グループ</th>';
                }
                if (checkSqueeze("disp-sex", $disp_squeeze)) {
                    echo '<th>性別</th>';
                }
                if (checkSqueeze("disp_tel", $disp_squeeze)) {
                    echo '<th>連絡先</th>';
                }
                if (checkSqueeze("disp_post_no", $disp_squeeze)) {
                    echo '<th>郵便番号</th>';
                }
                if (checkSqueeze("disp_address", $disp_squeeze)) {
                    echo '<th>住所</th>';
                }
                if (checkSqueeze("disp_mail", $disp_squeeze)) {
                    echo '<th>メールアドレス</th>';
                }
                if (checkSqueeze("disp_line", $disp_squeeze)) {
                    echo '<th>LINE ID</th>';
                }
                if (checkSqueeze("disp_born", $disp_squeeze)) {
                    echo '<th>生年月日</th>';
                }
                if (checkSqueeze("disp_age", $disp_squeeze)) {
                    echo '<th>年齢</th>';
                }
                if (checkSqueeze("disp_regist", $disp_squeeze)) {
                    echo '<th>登録日</th>';
                }
                if (!$introduction_on) {
                    if (checkSqueeze("disp_inflow", $disp_squeeze)) {
                        echo '<th>流入元</th>';
                    }
                    if (checkSqueeze("disp_introduce", $disp_squeeze)) {
                        echo '<th>紹介者</th>';
                    }
                    if(checkSqueeze("disp_comment",$disp_squeeze)){
                        echo '<th class="sq-th">特記事項</th>';
                    }
                    // if(checkSqueeze("disp-remarks",$disp_squeeze)){
                    //     echo '<th>備考</th>';
                    // }
                    // 浄霊シート情報
                    foreach ($spiritTypeArray as $key => $value) {
                        foreach ($value as $key_num => $value_num) {
                            // $split_id = "disp-split-".$value_num["ID"];
                            $split_id = $value_num["ID"];
                            if (checkSqueeze($split_id, $disp_squeeze)) {
                                echo "<th class='split-sheet'>" . $value_num["title"] . "</th>";
                            }
                        }
                    }
                }
                ?>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <?php
}


/****************************************************
 **   ユーザーテーブル作成　複数テーブル対応
 ******************************************************/
function dispUserTabaleS($user_data,$disp_squeeze, $introduction_on, $spiritTypeArray,$id="")
{

    $current_user_search_squeeze = json_decode(get_user_meta(get_current_user_id(),'search_squeeze',true));//表示設定取得
    $current_user_disp_squeeze = $disp_squeeze;


    ?>
    <table id="userTable<?php echo $id;?>" class="user-disp-table table table-bordered">
        <thead>
            <tr>
                <?php
                if(isset($_GET['group_user_add'])){
                    echo "<th></th>";
                }
                if ($introduction_on) {
                    echo '<th style="z-index:1"></th>'; //<!-- 紹介者選択蘭 -->
                }

                if (checkSqueeze("disp-id", $disp_squeeze)) {
                    echo '<th style="z-index:1">ユーザーID</th>';
                }
                if (!$introduction_on) {
                    echo '<th style="z-index:1"></th>'; //編集削除蘭
                }
                if (checkSqueeze("disp_last_name", $disp_squeeze)) {
                    echo '<th style="z-index:1">苗字</th>';
                }
                if (checkSqueeze("disp_first_name", $disp_squeeze)) {
                    echo '<th style="z-index:1">名前</th>';
                }
                if (checkSqueeze("disp_last_kana", $disp_squeeze)) {
                    echo '<th>ミョウジ</th>';
                }
                if (checkSqueeze("disp_first_kana", $disp_squeeze)) {
                    echo '<th>ナマエ</th>';
                }
                if (checkSqueeze("disp-connection", $disp_squeeze)) {
                    echo '<th>関連</th>';
                }
                if (checkSqueeze("disp-group", $disp_squeeze)) {
                    echo '<th>グループ</th>';
                }
                if (checkSqueeze("disp-sex", $disp_squeeze)) {
                    echo '<th>性別</th>';
                }
                if (checkSqueeze("disp_tel", $disp_squeeze)) {
                    echo '<th>連絡先</th>';
                }
                if (checkSqueeze("disp_post_no", $disp_squeeze)) {
                    echo '<th>郵便番号</th>';
                }
                if (checkSqueeze("disp_address", $disp_squeeze)) {
                    echo '<th>住所</th>';
                }
                if (checkSqueeze("disp_mail", $disp_squeeze)) {
                    echo '<th>メールアドレス</th>';
                }
                if (checkSqueeze("disp_line", $disp_squeeze)) {
                    echo '<th>LINE ID</th>';
                }
                if (checkSqueeze("disp_born", $disp_squeeze)) {
                    echo '<th>生年月日</th>';
                }
                if (checkSqueeze("disp_age", $disp_squeeze)) {
                    echo '<th>年齢</th>';
                }
                if (checkSqueeze("disp_regist", $disp_squeeze)) {
                    echo '<th>登録日</th>';
                }
                if (!$introduction_on) {
                    if (checkSqueeze("disp_inflow", $disp_squeeze)) {
                        echo '<th>流入元</th>';
                    }
                    if (checkSqueeze("disp_introduce", $disp_squeeze)) {
                        echo '<th>紹介者</th>';
                    }
                    if(checkSqueeze("disp_comment",$disp_squeeze)){
                        echo '<th class="sq-th">特記事項</th>';
                    }
                    // if(checkSqueeze("disp-remarks",$disp_squeeze)){
                    //     echo '<th>備考</th>';
                    // }
                    // 浄霊シート情報
                    if($spiritTypeArray != ""){
                        foreach ($spiritTypeArray as $key => $value) {
                            foreach ($value as $key_num => $value_num) {
                                // $split_id = "disp-split-".$value_num["ID"];
                                $split_id = $value_num["ID"];
                                if (checkSqueeze($split_id, $disp_squeeze)) {
                                    echo "<th class='split-sheet'>" . $value_num["title"] . "</th>";
                                }
                            }
                        }
                    }
                }
                ?>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    
    <script>

        var userData = <?php echo json_encode($user_data); ?>;
    </script>
    <script>
        var table = $('#userTable<?php echo $id;?>').DataTable({
                data: userData,
                scrollX: true,

                fixedColumns: {
                    leftColumns: 4       // 名前まで表示
                },
                columns: [
                    <?php 
                        // まとめてユーザー追加する用
                        if(isset($_GET['group_user_add'])){
                    ?>
                    { 
                        data: null, 
                        render: function(row) { 
                        return  '<input type="checkbox" name="add_connect_user[' + row.ID + ']" id="">'; 
                        } 

                    },
                    <?php 
                        } 

                        // 紹介者選択時
                        if($introduction_on){
                    ?>
                    { 
                        data: null, 
                        render: function(data, type, row) { 
                            return '<div class="edit-mark" onclick="sendPostData({input_introduction_id: ' + row.ID + '})">選択</div>';
                        } 
                    },
                    <?php 
                        } 

                        JSDataTableNoSearch($current_user_disp_squeeze,$current_user_search_squeeze,"disp-id","user_unique_id");

                        // 紹介者選択時以外
                        if(!$introduction_on){
                    ?>
                    { 
                        data: null, 
                        render: function(row) { 
                        
                            <?php if(isset($_GET['group_id'])){?>
                                return '<form class="edit-mark" method="post" id="post_delete_user_' + row.ID + '" action="<?php echo getURLSetSlag("admin-member-list"); ?>?delete_user=on&user_id=' + row.ID + '" style="display:none"></form><a class="edit-mark" href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=' + row.ID + '">詳細/編集</a>'; 
                            <?php }else{ ?>
                                return '<form class="edit-mark" method="post" id="post_delete_user_' + row.ID + '" action="<?php echo getURLSetSlag("admin-member-list"); ?>?delete_user=on&user_id=' + row.ID + '" style="display:none"></form><a class="edit-mark" href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=' + row.ID + '">詳細/編集</a><div class="edit-mark gray" onclick="click_modal(\'ユーザーを削除しますか？\', \'post_delete_user_' + row.ID + '\')">削除</div>'; 
                            <?php } ?>
                        } 

                    },
                    <?php 
                        } 

                        // 苗字
                        if(checkSqueeze("disp_last_name",$current_user_disp_squeeze)){
                    ?>
                    { data: 'last_name'
                        <?php 
                            // 検索対象外の処理
                            if($current_user_disp_squeeze != null && !property_exists($current_user_search_squeeze, 'disp_last_name')){
                                echo ",  searchable: false";
                            }
                        ?>
                    },
                    <?php 
                        }
                        
                        // 名前
                        JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_first_name","first_name");
                        JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_last_kana","last_name_kana");
                        JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_first_kana","first_name_kana");
                    ?>
                    
                    <?php /* タグで作成したものは検索対象にならないのでそれを調整 241223 */?>
                    <?php if(checkSqueeze("disp-connection",$current_user_disp_squeeze)){?>
                    {
                        <?php 
                            if($current_user_disp_squeeze != null && !property_exists($current_user_search_squeeze, "disp-connection")){
                                echo "data: 'user_connection_disp',  searchable: false,";
                            }else{
                                echo "data: 'user_connection_disp',";
                            }
                        ?>
                        render: function(data, type, row) {
                            if (type === 'filter' || type === 'sort') {
                                // 検索やソートにはプレーンテキストを使用
                                const div = document.createElement('div');
                                div.innerHTML = data;
                                return div.textContent || div.innerText || '';
                            }
                            return data; // 表示にはHTMLをそのまま使う
                        }
                    },
                    <?php } ?>

                    <?php if(checkSqueeze("disp-group",$current_user_disp_squeeze)){?>
                    {
                        <?php 
                            if($current_user_disp_squeeze != null && !property_exists($current_user_search_squeeze, "disp-group")){
                                echo "data: 'group_data_list',  searchable: false,";
                            }else{
                                echo "data: 'group_data_list',";
                            }
                        ?>
                        render: function(data, type, row) {
                            if (type === 'filter' || type === 'sort') {
                                // 検索やソートにはプレーンテキストを使用
                                const div = document.createElement('div');
                                div.innerHTML = data;
                                return div.textContent || div.innerText || '';
                            }
                            return data; // 表示にはHTMLをそのまま使う
                        }
                    },
                    <?php } ?>

                    <?php if(checkSqueeze("disp-sex",$current_user_disp_squeeze)){?>
                    { 
                        data: null,
                        render: function(data, type, row) {
                            if (type === 'filter' || type === 'sort') {
                                // 検索やソート時にはプレーンテキストのみを返す
                                return row.sex;
                            }
                            // 表示時にはHTMLを生成
                            return '<div class="sex-' + row.sex + '" >' + row.sex + '</div>';
                        }
                    },
                    <?php } ?>
                    <?php 
                    
                    JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_tel","tel");
                    JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_post_no","post_code");
                    JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_address","city");
                    JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_mail","user_email");
                    JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_line","line");
                    JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_born","born");
                    JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_age","age");
                    JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_regist","regist_day");

                    if(!$introduction_on){ 
                        JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_inflow","input_inflow");   // 流入元
                        JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_introduce","input_introduction_name");   // 紹介者
                        JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_comment","input_remarks");   // 特記事項
                        // 浄霊シート情報
                        if($spiritTypeArray != ""){

                            foreach ($spiritTypeArray as $key => $value) {
                                foreach ($value as $key_num => $value_num) {
                                    // $split_id = "disp-split-".$value_num["ID"];
                                    $split_id = $value_num["ID"];
                                    if(checkSqueeze($split_id,$current_user_disp_squeeze)){
                                        echo "{ data: '".$value_num["title"]."' },";
                                    }
                                }
                            }
                        }
                    }
                    ?>

                ],
                
                <?php // } ?>
                "order": [[0, "dec"]], // 第2列（インデックス2）を昇順（asc）にソート

                <?php if(isset($current_user_disp_squeeze->{1})) { ?>
                "pageLength": <?php echo $current_user_disp_squeeze->{1};?>,  // 初期表示件数
                <?php }else{ ?>
                "pageLength":100,
                <?php } ?>
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
                }
        });
        console.log(table.settings().init().columnDefs);

    </script>

    <?php
}

/****************************************************
 **   ユーザーテーブル表示
 ******************************************************/
function makeUserTable($users, $connection_id = 0)
{

    require_once(dirname(__FILE__) . "/../../class/ConnectionGroupClass.php");
    require_once(dirname(__FILE__) . "/../../class/GroupSettingClass.php");

    $hiki_connection_id = $connection_id;
    
    $group_setting_data = new GroupSettingClass(); //グループクラス
    $group_list = $group_setting_data->getGroupData();


    $user_count_no = 0;
    $user_data = array();

    foreach ($users as $user) {
        $connection_id = $hiki_connection_id ;
        $born_year = get_user_meta($user->ID, 'born_year', true) . '/' . get_user_meta($user->ID, 'born_month', true) . '/' . get_user_meta($user->ID, 'born_day', true);

        $report_born_year = get_user_meta($user->ID, 'report_born_year', true) . '/' . get_user_meta($user->ID, 'report_born_month', true) . '/' . get_user_meta($user->ID, 'report_born_day', true);
        $is_delete = get_user_meta($user->ID, 'is_delete', true);
        $user_info = get_userdata($user->ID);

        if ($is_delete || $user_info->roles[0] == 'administrator' || $user_info->roles[0] == 'editor') continue;
        

        $user_regitsted_mail = "";
        $user_regitsted_mail = getParentAddress($user->user_email);// 家族なら親のメールアドレスを表示させる

        $connection_group_data = new ConnectionGroupClass(); //管理データ

        // グループデータ
        $user_group_data = json_decode(get_user_meta($user->ID, 'group_data', true));// グループ

        

        //関連グループ一覧表示時 かつ　追加でないとき　→　複数関連に対応できるように変更
        // TODO:ユーザーが関与しているグループをすべて表示
        if (isset($_GET['group_id'])) {

            if( $connection_id != 0){

                if (is_string($connection_id) && preg_match('/^\s*(\{.*\}|\[.*\])\s*$/s', $connection_id)) {
                    $connection_id = json_decode($connection_id, true)[0];
                }
                
                
                $user_connection_group_data = $connection_group_data->GetUserConnectGroupData($user->ID, $connection_id); //ユーザーの関連データ取得
            }else{

                $user_connection_group_data = $connection_group_data->GetUserConnectGroupData($user->ID, $_GET['group_id']); //ユーザーの関連データ取得
            }

            // var_dump($user_connection_group_data);  //削除okd

            // 関連登録済みでない and 非表示対象なら表示しない
            if (!$user_connection_group_data['registed_flg'] && isset($_GET['squeeze_group'])){
                continue;
            }

            
            // 指定関連グループ表示時に登録されていないユーザーは表示しない
            if (!$user_connection_group_data['registed_flg'] && !isset($_GET['group_user_add'])){
                continue;
            }

            // 指定関連グループ追加時に登録されているユーザーは表示しない
            if ($user_connection_group_data['registed_flg'] && isset($_GET['group_user_add'])){
                continue;
            }
            
        }
        

        // 顧客一覧での関連絞り込み機能
        if (isset($_GET['user-squeeze-connection']) && $_GET['user-squeeze-connection'] != "") {
            $user_connection_group_data = $connection_group_data->GetUserConnectGroupData($user->ID, $_GET['user-squeeze-connection']); //該当グループに属しているかチェック

            if (!$user_connection_group_data['registed_flg'])
                continue;
        }

        // 顧客一覧でのグループ絞り込み機能
        if (isset($_GET['user-squeeze-group']) && $_GET['user-squeeze-group'] != "") {
            if ($user_group_data == null)
                continue;
            else {
                $group_check = false;   //trueなら表示
                foreach ($user_group_data as $value) {
                    if ($value == $_GET['user-squeeze-group'])
                        $group_check = true;
                }
                if (!$group_check)
                    continue;
            }
        }

        $date = new DateTime($user->user_registered, new DateTimeZone('UTC'));
        $regist_date = $date->setTimezone(new DateTimeZone('Asia/Tokyo'));

        $user_age = calculateAge($born_year);   //年齢
        if($born_year == "//"){
            $born_year = "-";
            $user_age = "-";
        }
        $line_id = get_user_meta($user->ID, 'line_id', true);
        if($line_id == "") $line_id = "-";

        $user_data[] = array(
            'ID' => $user->ID,
            'user_unique_id' => get_user_meta($user->ID, 'user_unique_id', true),
            '' => '',
            'user_login' => $user->user_login,
            'first_name' => get_user_meta($user->ID, 'first_name', true),
            'last_name' => get_user_meta($user->ID, 'last_name', true),
            'first_name_kana' => get_user_meta($user->ID, 'first_name_kana', true),
            'last_name_kana' => get_user_meta($user->ID, 'last_name_kana', true),
            'sex' => get_user_meta($user->ID, 'sex', true),
            'tel' => get_user_meta($user->ID, 'billing_phone', true) . '-' . get_user_meta($user->ID, 'billing_phone2', true) . '-' . get_user_meta($user->ID, 'billing_phone2', true),
            'post_code' => get_user_meta($user->ID, 'billing_postcode', true),
            'city' => get_user_meta($user->ID, 'billing_city', true),
            'billing_address' => get_user_meta($user->ID, 'billing_address_1', true),
            'line' => $line_id,
            'born' => $born_year,
            'report_born' => $report_born_year,
            'age' => $user_age,
            'regist_day' => $regist_date->format('Y-m-d'),
            // 'input_inflow' => get_the_title(get_user_meta($user->ID, 'input_inflow', true)),
            'inflow_remarks' => get_user_meta($user->ID, 'inflow_remarks', true),
            'input_remarks' => get_user_meta($user->ID, 'user_remarks', true),
        );

        // リモート浄霊主導作成用　250107
        if(is_page('admin_make_remote_sprit')){
            if(isset($_GET["sheet_name"])){ //浄霊シートから来た
                if( !isset($sheet_set_array[$user->ID])){
                    $link_url = getURLSetSlag('admin-spirit-detail')."?user_id=".get_field('acf_purespirit_id', $_GET['sheet_name'])."&sheet_edit=on&sheet_name=".$_GET['sheet_name'];
                    $user_data[$user_count_no]['remote_btn'] = "
                    <form action=\"$link_url\" method=\"post\" onSubmit=\"return register_check()\">
                        <input type=\"hidden\" name=\"target_id\" value=\"$user->ID\">
                        <input type=\"hidden\" name=\"remote_add\" value=\"\">
                        <button type=\"submit\" class=\"edit-mark\" style=\"width: 36px;font-size: 12px;\">選択</button>
                    </form>
                    ";
                }
            }else if(!isset($_POST["applicant_id"])){
                
                $link_url = getURLSetSlag('admin_make_remote_sprit');
                $user_data[$user_count_no]['remote_btn'] = "
                <form action=\"$link_url\" method=\"post\">
                    <input type=\"hidden\" name=\"applicant_id\" value=\"$user->ID\">
                    <input type=\"hidden\" name=\"target_id[]\" value=\"\">
                    <button type=\"submit\" class=\"edit-mark\" style=\"width: 36px;font-size: 12px;\">選択</button>
                </form>
                ";
            }else{ 
                echo "通常";
                if(!isset( $target_array[  $user->ID ] )  && count($target_array) < 10){
                    $link_url = getURLSetSlag('admin_make_remote_sprit');

                    if($_POST['applicant_id'] == $user->ID){
                        $user_data[$user_count_no]['remote_btn'] = "
                            <form action=\"$link_url\" method=\"post\" onSubmit=\"return target_check()\" >";
                    }else{
                        $user_data[$user_count_no]['remote_btn'] = "<form action=\"$link_url\" method=\"post\">";
                    }
                    
                    $p_applicant_id = $_POST['applicant_id'];
                    if($_POST['applicant_id'] == $user->ID){
                        $user_data[$user_count_no]['remote_btn'] .= "
                        <form action=\"$link_url\" method=\"post\" onSubmit=\"return target_check()\" >";
                        
                    }else{
                        $user_data[$user_count_no]['remote_btn'] .= "
                        <form action=\"$link_url\" method=\"post\">";
                        
                    }

                    $user_data[$user_count_no]['remote_btn'] .= "
                        <input type=\"hidden\" name=\"applicant_id\" value=\"$p_applicant_id\">
                        <input type=\"hidden\" name=\"target_id[]\" value=\"$user->ID\">";

                    foreach ($target_array as $target_key => $target_value) {
                        if ($target_value != "") {
                            
                            $user_data[$user_count_no]['remote_btn'] .= "
                                    <input type=\"hidden\" name=\"target_id[]\" value=\"$target_value\">
                                    ";
                        }
                    }
                    $user_data[$user_count_no]['remote_btn'] .= "

                        <button type=\"submit\" class=\"edit-mark\" style=\"width: 36px;font-size: 12px; background-color: darkcyan;\">追加</button>
                    </form>
                    ";
                } 
            }
        }

        // 流入情報
        if(get_user_meta($user->ID, 'input_inflow', true) != ""){

            $user_data[$user_count_no]['input_inflow'] = get_the_title(get_user_meta($user->ID, 'input_inflow', true));
        }else{

            $user_data[$user_count_no]['input_inflow'] = "";
        }

        

        // 紹介者情報
        if (get_user_meta($user->ID, 'input_introduction_id', true) != 'x') {
            $introduction_id = get_user_meta($user->ID, 'input_introduction_id', true);
            $introduction_name = get_user_meta($introduction_id, 'last_name', true) . get_user_meta($introduction_id, 'first_name', true);
            $user_data[$user_count_no]['input_introduction_name'] = '<a href="' . getURLSetSlag("admin-member-edit") . '?user_id=' . $introduction_id . '">' . $introduction_name . '</a>';
        } else {
            $user_data[$user_count_no]['input_introduction_name'] = "";
        }

        // グループデータ追加
        $user_data[$user_count_no]['group_data'] = "";
        $user_data[$user_count_no]['group_data_list'] = "";

        // if ($user_group_data != null) {
        if (is_array($user_group_data)) {
            $group_list_data = array();

            
            foreach ($user_group_data as $key) {
                foreach ($group_list as $key2) {
                    if ($key2['ID'] == $key) {
                        $group_list_data[] = $key2['title'];
                        break;
                    }
                }
            }
            $user_data[$user_count_no]['group_data'] = $user_group_data;
            $user_data[$user_count_no]['group_data_list'] = $group_list_data;
        }

        // 浄霊シート情報取得
        if (is_page('admin-member-list') || is_page('admin-connection-member-list')) {

            require_once(dirname(__FILE__) . "/../../class/spiritTypeClass.php");
            $spiritType = new SpiritTypeClass(); //管理データ
            $spiritTypeArray = $spiritType->getSpiritType();    //浄霊タイプ

            $user_split_data = json_decode(get_user_meta($user->ID, 'spirit_data', true));
            if ($user_split_data != NULL) {

                foreach ($user_split_data as $key => $value) {
                    $sprit_type = get_field('acf_acf_purespirit_type', $value);
                    $is_delete = get_field("is_delete", $value);

                    if ($is_delete)
                        continue;    //削除されたシートは表示しない

                    // 依頼日格納
                    $user_data[$user_count_no][get_field('acf_pure_spirit_title', get_field('acf_acf_purespirit_type', $value))] = get_field('acf_purespirit_requested_date', $value);
                }

                // 空データの作成
                // 現在のタイトルが配列にあるかチェック
                foreach ($spiritTypeArray as $key => $value) {
                    foreach ($value as $key_num => $value_num) {
                        if (!array_key_exists($value_num["title"], $user_data[$user_count_no])) {
                            $user_data[$user_count_no][$value_num["title"]] = "";
                        }
                    }
                }
            } else {
                // 浄霊データなければすべて空
                foreach ($spiritTypeArray as $key => $value) {
                    foreach ($value as $key_num => $value_num) {
                        $user_data[$user_count_no][$value_num["title"]] = "";
                    }
                }
            }

        }

        $check_user_email = get_user_meta($user->ID, 'no_mail', true);


        if(checkUnix($check_user_email) == true) {
            $user_data[$user_count_no]['user_email'] = "-";
        } else {

            $user_data[$user_count_no]['user_email'] = $user_regitsted_mail;
        }

        // if ($connect_disp != "" && $connect_disp[0] != null) {
        // 関連グループデータ
        $connection_id = get_user_meta($user->ID, 'connect_group', true);
        

        // 関連グループに所属している時は表示しない
        // if($connection_id != ""){
        //     // 既存データがあればデコード
        //     $decode_connect_group = json_decode($connection_id);
        //     if(isset($_GET['group_user_add'])){
        //         // 全所属チェック
        //         $belong_flg = false;
        //         foreach ($decode_connect_group as $value) {
        //             if($value == $_GET['group_id']){
        //                 $belong_flg = true;
        //                 break;
        //             }
        //         }
        //         if($belong_flg) continue;
        //     }
        // }
        // $connect_disp = get_user_meta($user->ID, 'connect_group_disp', true);
        $connect_disp = "";
        if($connection_id != ""){
            $connect_group_id = json_decode($connection_id);


            foreach ($connect_group_id as $t_id) {

                // グループ削除したものは表示しない　253525
                $is_delete = get_field('acf_connection_is_delete',$t_id); 
                if($is_delete) continue;
                
                $connect_disp = get_field('acf_connection_top_name',$t_id); 

            }
        }
        
        if ($connect_disp != "" ) {

            $ids = json_decode($connection_id);     //関連ID

            // リンク化
            $add_link = array();
            for ($i=0; $i < count($ids); $i++) { 
                $slug_url = getURLSetSlag("admin-connection-member-list");
                $g_top_id = get_field("acf_connection_top",$ids[$i]);

                $group_name = get_field('acf_connection_top_name',$ids[$i]);
                $group_delete = get_field('acf_connection_is_delete',$ids[$i]);

                $group_releation_list = json_decode(get_field('acf_connection_list',$ids[$i]));
                $user_relationship = "";
                
                if(isset($group_releation_list->{$user->ID})){

                    $user_relationship = $connection_group_data->getRelationship($group_releation_list->{$user->ID}->{"relationship_data"} );
                }
                

                // var_dump($user->ID);  //削除okd
                // var_dump($group_name);  //削除okd
                // var_dump($group_releation_list->{$user->ID}->{"relationship_data"} );  //削除okd
                // echo "<br>";

                if(!isset($_GET['group_id'])){
                    if($g_top_id  == $user->ID && $group_delete != true ){
                        $add_link[$i] = "<a href='$slug_url?group_id=$ids[$i]&group_top=$g_top_id'>$group_name(代表設定)<br></a>";
                        
                    }else{
    
                        $add_link[$i] = "<a href='$slug_url?group_id=$ids[$i]&group_top=$g_top_id'>$group_name</a><br>";
                        $user_data[$user_count_no]['group_top_flg'] = false;
                    }
                }else{
                    // 関連グループで紹介表示中

                    if( $_GET['group_id'] != $ids[$i]) continue;

                    if($user_relationship != ""){

                        $add_link = $group_name."　".$user_relationship."<br>";
                    }
                    
    
                    if($g_top_id  == $user->ID && $group_delete != true ){
                        $user_data[$user_count_no]['group_top_flg'] = true;
                    }else{
    
                        // $add_link = $group_name."　".$user_relationship."<br>";
                        $user_data[$user_count_no]['group_top_flg'] = false;
                    }
                }

            }

            
            $user_data[$user_count_no]['user_connection_disp'] = $add_link;


        } else {
            $user_data[$user_count_no]['user_connection_disp'] = "";
        }


        $user_count_no++;
    }

    return $user_data;
}

/****************************************************
 **   リモート用ユーザーテーブル表示
 ******************************************************/
function makeRemoteUserTable($users,$target_array)
{

    require_once(dirname(__FILE__) . "/../../class/ConnectionGroupClass.php");
    require_once(dirname(__FILE__) . "/../../class/GroupSettingClass.php");
    
    $group_setting_data = new GroupSettingClass(); //グループクラス
    $group_list = $group_setting_data->getGroupData();


    $user_count_no = 0;
    $user_data = array();

    foreach ($users as $user) {
        $born_year = get_user_meta($user->ID, 'born_year', true) . '/' . get_user_meta($user->ID, 'born_month', true) . '/' . get_user_meta($user->ID, 'born_day', true);

        $report_born_year = get_user_meta($user->ID, 'report_born_year', true) . '/' . get_user_meta($user->ID, 'report_born_month', true) . '/' . get_user_meta($user->ID, 'report_born_day', true);
        $is_delete = get_user_meta($user->ID, 'is_delete', true);
        $user_info = get_userdata($user->ID);

        if ($is_delete || $user_info->roles[0] == 'administrator' || $user_info->roles[0] == 'editor')
            continue;

        $user_regitsted_mail = "";

        $user_regitsted_mail = getParentAddress($user->user_email);// 家族なら親のメールアドレスを表示させる

        $connection_group_data = new ConnectionGroupClass(); //管理データ

        // グループデータ
        $user_group_data = json_decode(get_user_meta($user->ID, 'group_data', true));// グループ

        $date = new DateTime($user->user_registered, new DateTimeZone('UTC'));
        $regist_date = $date->setTimezone(new DateTimeZone('Asia/Tokyo'));


        $line_id = get_user_meta($user->ID, 'line_id', true);
        if($line_id == "") $line_id = "-";

        $user_data[] = array(
            'ID' => $user->ID,
            'user_unique_id' => get_user_meta($user->ID, 'user_unique_id', true),
            'first_name' => get_user_meta($user->ID, 'first_name', true),
            'last_name' => get_user_meta($user->ID, 'last_name', true),
            'sex' => get_user_meta($user->ID, 'sex', true),
            'tel' => get_user_meta($user->ID, 'billing_phone', true) . '-' . get_user_meta($user->ID, 'billing_phone2', true) . '-' . get_user_meta($user->ID, 'billing_phone2', true),
            'post_code' => get_user_meta($user->ID, 'billing_postcode', true),
            'city' => get_user_meta($user->ID, 'billing_city', true),
            'billing_address' => get_user_meta($user->ID, 'billing_address_1', true),
            'line' => $line_id,
            'born' => $born_year,
            'regist_day' => $regist_date->format('Y-m-d'),
            'inflow_remarks' => get_user_meta($user->ID, 'inflow_remarks', true),
        );

        // リモート浄霊主導作成用　250107
        if(is_page('admin_make_remote_sprit')){
            if(isset($_GET["sheet_name"])){ //浄霊シートから来た
                if( !isset($sheet_set_array[$user->ID])){
                    $link_url = getURLSetSlag('admin-spirit-detail')."?user_id=".get_field('acf_purespirit_id', $_GET['sheet_name'])."&sheet_edit=on&sheet_name=".$_GET['sheet_name'];
                    $user_data[$user_count_no]['remote_btn'] = "
                    <form action=\"$link_url\" method=\"post\" onSubmit=\"return register_check()\">
                        <input type=\"hidden\" name=\"target_id\" value=\"$user->ID\">
                        <input type=\"hidden\" name=\"remote_add\" value=\"\">
                        <button type=\"submit\" class=\"edit-mark\" style=\"width: 36px;font-size: 12px;\">選択</button>
                    </form>
                    ";
                }
            }else if(!isset($_POST["applicant_id"])){
                
                $link_url = getURLSetSlag('admin_make_remote_sprit');
                $user_data[$user_count_no]['remote_btn'] = "
                <form action=\"$link_url\" method=\"post\">
                    <input type=\"hidden\" name=\"applicant_id\" value=\"$user->ID\">
                    <input type=\"hidden\" name=\"target_id[]\" value=\"\">
                    <button type=\"submit\" class=\"edit-mark\" style=\"width: 36px;font-size: 12px;\">選択</button>
                </form>
                ";
            }else{ 
                if(!isset( $target_array[  $user->ID ] )  && count($target_array) < 10){
                    $link_url = getURLSetSlag('admin_make_remote_sprit');

                    if($_POST['applicant_id'] == $user->ID){
                        $user_data[$user_count_no]['remote_btn'] = "
                            <form action=\"$link_url\" method=\"post\" onSubmit=\"return target_check()\" >";
                    }else{
                        $user_data[$user_count_no]['remote_btn'] = "<form action=\"$link_url\" method=\"post\">";
                    }

                    if($_POST['applicant_id'] == $user->ID){
                        $user_data[$user_count_no]['remote_btn'] .= "
                        <form action=\"$link_url\" method=\"post\" onSubmit=\"return target_check()\">
                        ";
                    }else{
                        $user_data[$user_count_no]['remote_btn'] .= "
                        <form action=\"$link_url\" method=\"post\">
                        ";

                    }
                    $t_post = $_POST['applicant_id'];
                    $user_data[$user_count_no]['remote_btn'] .= "
                        <input type=\"hidden\" name=\"applicant_id\" value=\"$t_post\">
                        <input type=\"hidden\" name=\"target_id[]\" value=\"$user->ID\">";

                    foreach ($target_array as $target_key => $target_value) {
                        if ($target_value != "") {
                            
                            $user_data[$user_count_no]['remote_btn'] .= "
                                    <input type=\"hidden\" name=\"target_id[]\" value=\"$target_value\">
                                    ";
                        }
                    }
                    $user_data[$user_count_no]['remote_btn'] .= "

                        <button type=\"submit\" class=\"edit-mark\" style=\"width: 36px;font-size: 12px; background-color: darkcyan;\">追加</button>
                    </form>
                    ";
                } else{
                    // 既にリモート浄霊選択済みの場合に空のデータを用意
                    $user_data[$user_count_no]['remote_btn'] = "";
                }
            }
        }

        // 流入情報
        if(get_user_meta($user->ID, 'input_inflow', true) != ""){

            $user_data[$user_count_no]['input_inflow'] = get_the_title(get_user_meta($user->ID, 'input_inflow', true));
        }else{

            $user_data[$user_count_no]['input_inflow'] = "";
        }

        // 紹介者情報
        if (get_user_meta($user->ID, 'input_introduction_id', true) != 'x') {
            $introduction_id = get_user_meta($user->ID, 'input_introduction_id', true);
            $introduction_name = get_user_meta($introduction_id, 'last_name', true) . get_user_meta($introduction_id, 'first_name', true);
            $user_data[$user_count_no]['input_introduction_name'] = '<a href="' . getURLSetSlag("admin-member-edit") . '?user_id=' . $introduction_id . '">' . $introduction_name . '</a>';
        } else {
            $user_data[$user_count_no]['input_introduction_name'] = "";
        }

        // グループデータ追加
        $user_data[$user_count_no]['group_data'] = "";
        $user_data[$user_count_no]['group_data_list'] = "";

        if (is_array($user_group_data)) {
            $group_list_data = array();

            
            foreach ($user_group_data as $key) {
                foreach ($group_list as $key2) {
                    if ($key2['ID'] == $key) {
                        $group_list_data[] = $key2['title'];
                        break;
                    }
                }
            }
            $user_data[$user_count_no]['group_data'] = $user_group_data;
            $user_data[$user_count_no]['group_data_list'] = $group_list_data;
        }

        $check_user_email = get_user_meta($user->ID, 'no_mail', true);
        if(checkUnix($check_user_email)) {
            $user_data[$user_count_no]['user_email'] = "-";
        } else {

            $user_data[$user_count_no]['user_email'] = $user_regitsted_mail;
        }

        // 関連グループデータ
        $connection_id = get_user_meta($user->ID, 'connect_group', true);

        $connect_disp = get_user_meta($user->ID, 'connect_group_disp', true);
        
        if ($connect_disp != "" ) {

            $g_id = json_decode($connect_disp);     //参加関連名
            $ids = json_decode($connection_id);     //関連ID
            
            // リンク化
            $add_link = array();
            for ($i=0; $i < count($ids); $i++) { 
                $slug_url = getURLSetSlag("admin-connection-member-list");
                $g_top_id = get_field("acf_connection_top",$ids[$i]);
                $add_link[$i] = "<a href='$slug_url?group_id=$ids[$i]&group_top=$g_top_id'>$g_id[$i]</a>";
                
            }

            
            $user_data[$user_count_no]['user_connection_disp'] = $add_link;


        } else {
            $user_data[$user_count_no]['user_connection_disp'] = "";
        }


        $user_count_no++;
    }

    return $user_data;
}

?>


