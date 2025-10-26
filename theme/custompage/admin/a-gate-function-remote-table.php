<?php 


/****************************************************
 **   ユーザーテーブル作成　複数テーブル対応
 ******************************************************/
// function dispUserRemoteTabale($user_data,$disp_squeeze, $introduction_on, $spiritTypeArray,$id=0)
function dispUserRemoteTabale($user_data,$id=0)
{



    // $current_user_search_squeeze = json_decode(get_user_meta(get_current_user_id(),'search_squeeze',true));//表示設定取得
    // $current_user_disp_squeeze = $disp_squeeze;


    ?>
    <!-- <table id="userTable<?php echo $id;?>" class="user-disp-table table table-bordered">
    </table> -->

    
    <script>

        <?php /* 
        必要絞り込み
        □アイテム名
        □オーダー日
        □決済完了日

        
        */?>
        var userData = <?php echo json_encode($user_data); ?>;

        var table = $('#userTable<?php echo $id;?>').DataTable({
                data: userData,
                scrollX: true,

                // fixedColumns: {
                //     leftColumns: 4       // 名前まで表示
                // },
                columns: [
                    <?php if(isset($_POST["all_delete"])){ ?>
                        {data:"acf_temporary_status"} //削除
                    <?php } ?>

                    <?php if(isset($_POST["all_registration"])){ ?>
                        {data:"acf_temporary_registration"} //登録
                    <?php } ?>

                    {data:"acf_temporary_order_day"},    // オーダー番号

                    <?php if(!isset($_POST["all_delete"]) && !isset($_POST["all_registration"])){ ?>
                        {data:"edit_btn"}, //編集
                    <?php } ?>

                    {data:"acf_temporary_status"},    // ステータス
                    {data:"acf_temporary_payment"},    // 支払方法
                    {data:"acf_temporary_order_day"},    // オーダー日
                    {data:"acf_temporary_payment_end"},    // 決済完了日
                    {data:"acf_temporary_item_name"},    // アイテム名
                    {data:"acf_temporary_product_number"},    // 品番
                    {data:"acf_temporary_subtotal"},    // 小計
                    {data:"acf_temporary_name"},    // 名前
                    {data:"is_candidate"},    // 候補
                    {data:"acf_temporary_address"},    // 住所
                    {data:"acf_temporary_tel"},    // 電話番号
                    {data:"acf_temporary_mail"},    // メールアドレス

                    

                ],
                
                <?php // } ?>
                "order": [[0, "dec"]], // 第2列（インデックス2）を昇順（asc）にソート
                "pageLength":100,
                <?php /* 
                <?php if(isset($current_user_disp_squeeze->{1})) { ?>
                "pageLength": <?php echo $current_user_disp_squeeze->{1};?>,  // 初期表示件数
                <?php }else{ ?>
                "pageLength":100,
                <?php } ?>
                 */?>
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
                }
        });

    </script>

    <?php
}

/****************************************************
 **   ユーザーリモート浄霊作成用テーブル
 ******************************************************/
function dispUserRemoteMakeTabale($user_data,$id=0)
{
    $current_user_disp_squeeze = "";
    $current_user_search_squeeze = "";

?>
    <style>
        #userTable0{
            margin: 0;
        }
    </style>
    <table id="userTable0" class="user-disp-table table table-bordered">
        <thead>
            <tr>
                <th>ユーザーID</th>
                <th></th>
                <th>名前</th>
                <th>関連</th>
                <th>グループ</th>
                <th>性別</th>
                <th>連絡先</th>
                <th>住所</th>
                <th>生年月日</th>
                <th>メールアドレス</th>
                <th>紹介者</th>
            </tr>
        </thead>
    </table>
    <script>

        var userData = <?php echo json_encode($user_data); ?>;
        var table = $('#userTable<?php echo $id;?>').DataTable({
        data: userData,
        scrollX: true,

        fixedColumns: {
            leftColumns: 4       // 名前まで表示
        },

        columns: [
            <?php 

                JSDataTableNoSearch($current_user_disp_squeeze,$current_user_search_squeeze,"disp-id","user_unique_id");

                // 選択ボタン
            ?>
            {data:"remote_btn"},
            {
                //名前
                data: null,
                render: function(data, type, row) {
                    if (type === 'filter' || type === 'sort') {
                        // 検索やソート時にはプレーンテキストのみを返す
                        // return row.last_name + row.first_name;
                        return row.last_name + row.first_name ;
                    }
                    // 表示時にはHTMLを生成
                    return '<a href="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=' + row.ID + '" target="_blank">' + row.last_name + row.first_name + '</a>';
                }
            },
            
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
            
            <?php 
                } 
                
                JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_tel","tel");
            ?>
            {

                data: null,
                render: function(data, type, row) {
                    if (type === 'filter' || type === 'sort') {
                        // 検索やソート時にはプレーンテキストのみを返す
                        // return row.last_name + row.first_name;
                        return row.post_code + row.city + row.billing_address;
                    }
                    // 表示時にはHTMLを生成
                    return '<div>' + row.post_code + row.city + row.billing_address +'</div>';
                }
            },
            <?php 
            
            JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_born","born");
            JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_mail","user_email");

            JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,"disp_introduce","input_introduction_name");   // 紹介者

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

</script>
<?php 
}

/****************************************************
 **   ユーザーリモートソートボタン処理
 ******************************************************/
function dispUserRemoteThbtn($sort_data,$sort_content,$btn_name){
    $sort_arrow = "";
    $sort_mode = "up";
    $sort_url = getURLSetSlag("admin_remote_sprit_list")."?$sort_content=";

    if(isset($_GET[$sort_content])){
        if($_GET[$sort_content] == "up"){
            $sort_arrow = "↑";
            $sort_mode = "down";

            // 昇順で並び替え
            
            if($sort_content == "id_sort") $sort_content = "value";
            uasort($sort_data, function ($a, $b) use ($sort_content) {
                return $b[$sort_content] <=> $a[$sort_content];
            });
        }else{
            $sort_arrow = "↓";
            $sort_mode = "up";

            // 降順で並び替え
            
            if($sort_content == "id_sort") $sort_content = "value";
            uasort($sort_data, function ($a, $b) use ($sort_content) {
                return $a[$sort_content] <=> $b[$sort_content];
            });


        }
    }
    $sort_url .= $sort_mode;
    ?>

<a href="<?php echo $sort_url; ?>"><?php echo $btn_name. $sort_arrow;?></a>
<?php 

    return $sort_data;
}

/****************************************************
 **   粗見ソートボタン処理
 ******************************************************/
function dispAramiThbtn($sort_data,$sort_content,$btn_name){
    $sort_arrow = "";
    $sort_mode = "up";
    $sort_url = getURLSetSlag("admin-arami-sheet-list")."?arami_easy_list=on&$sort_content=";

    if(isset($_GET[$sort_content])){
        if($_GET[$sort_content] == "up"){
            $sort_arrow = "↑";
            $sort_mode = "down";

            // 昇順で並び替え
            uasort($sort_data, function ($a, $b) use ($sort_content) {
                return $b[$sort_content] <=> $a[$sort_content];
            });
        }else{
            $sort_arrow = "↓";
            $sort_mode = "up";

            // 降順で並び替え
            uasort($sort_data, function ($a, $b) use ($sort_content) {
                return $a[$sort_content] <=> $b[$sort_content];
            });


        }
    }
    $sort_url .= $sort_mode;
    ?>

<a href="<?php echo $sort_url; ?>"><?php echo $btn_name. $sort_arrow;?></a>
<?php 

    return $sort_data;
}



/****************************************************
 **   ユーザーリモートソート日付ボタン処理
 ******************************************************/
function dispUserRemoteThDatebtn($sort_data,$sort_content,$btn_name){
    $sort_arrow = "";
    $sort_mode = "up";
    $sort_url = getURLSetSlag("admin_remote_sprit_list")."?$sort_content=";
    if(isset($_GET[$sort_content])){
        if($_GET[$sort_content] == "up"){
            $sort_arrow = "↑";
            $sort_mode = "down";

            // 昇順で並び替え
            uasort($sort_data, function ($a, $b) use ($sort_content)  {
                return strcmp($b[$sort_content], $a[$sort_content]); 
            });
        }else{
            $sort_arrow = "↓";
            $sort_mode = "up";

            // 降順で並び替え
            uasort($sort_data, function ($a, $b)  use ($sort_content) {
                return strcmp($a[$sort_content], $b[$sort_content]); 
            });


        }
    }
    $sort_url .= $sort_mode;
    ?>

<a href="<?php echo $sort_url; ?>"><?php echo $btn_name. $sort_arrow;?></a>
<?php 

    return $sort_data;
}
?>


