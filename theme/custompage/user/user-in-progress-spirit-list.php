<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");

    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);

    $userClass = new SpiritUserClass(); //ユーザー管理
    $spiritScheduleData = new SpiritScheduleClass(); //スケジュールデータ

   

    $userData = $userClass->getUserAcountData($user_id);//ユーザー情報



    $spiritData = array();
    $spiritData["all_data"] = array();
    $spiritDataAll = $userClass->getUserSpritApplicant($user_id);//浄霊情報


    foreach($spiritDataAll["all_data"] as $key => $value)
    {

          //作成中は表示しない
        if($value["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_CREATE){
            continue;
        }

        if($value["依頼者ID"] != $user_id)
        {
            continue;
        }

        if(isset($_GET["category"]))
        {
            if($_GET["category"] == "applications")
            {
                if($value["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES ){
                    continue;
                }
            }
            else if($_GET["category"] == "sales"){
                if($value["依頼タイプ"] != SpiritTypeClass::SPIRIT_TYPE_NAME_SALES ){
                    continue;
                }
            }
        }


        if(isset($_GET["filter"]))
        {
            if($_GET["filter"] == "all")
            {
                $spiritData["all_data"][$key] = $value;
            }
            else if($_GET["filter"] == "in-progress")
            {
                if($value["会員ステータス"] != SpiritUserClass::MEMBER_STATUS_CANCEL  &&
                    $value["会員ステータス"] != SpiritUserClass::MEMBER_STATUS_COMPLETE  &&
                    $value["会員ステータス"] != SpiritUserClass::MEMBER_STATUS_SHIPMENT_COMPLETE ){
                    $spiritData["all_data"][$key] = $value;
                }
            }
            else if($_GET["filter"] == "confirmation")
            {
                if($value["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_NOT_INFOMATION ){
                    $spiritData["all_data"][$key] = $value;
                }
            }
            else if($_GET["filter"] == "unpaid")
            {
                if($value["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_WATING_PAYMENT ){
                    $spiritData["all_data"][$key] = $value;
                }
            }
            else if($_GET["filter"] == "complete")
            {
                if($value["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_CANCEL  ||
                    $value["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_COMPLETE ||
                    $value["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_SHIPMENT_COMPLETE ){
                    $spiritData["all_data"][$key] = $value;
                }
                
            }
        }
        else
        {
            $spiritData["all_data"][$key] = $value;
        }
    }


    //  var_dump($spiritData["all_data"]);

    $max_count = 15;
    
    // フィルター用の変数
    $current_filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    $current_category = isset($_GET['category']) ? $_GET['category'] : 'all';
    
    // ページネーション用の変数
    $current_page = isset($_GET['pagenation']) ? (int)$_GET['pagenation'] : 1;
    $items_per_page = $max_count;
    $offset = ($current_page - 1) * $items_per_page;
?>


<?php if($userData["必須"] > 0){?>

    <div>
        未入力のアカウント情報があります。<a href="'<?php echo getURLSetSlag("users/user-acount-edit");echo $get_url["add"]; ?>">こちら</a>から、ご入力してください。
    </div>

<?php }?>

<div class="user-top-area">


    <?php  

       // if(count($spiritData["all_data"]) > 0)
        {
    ?>
            <div class="user-jorei-section-title-wrap">
              <div class="user-jorei-section-title">浄霊履歴</div>
            </div>


            <div class="user-navigation-container progress-navigation" data-section="progress">
                <!-- カテゴリタブ -->
                <div class="category-tabs">
                    <div class="tab-item <?php echo ($current_category == 'all') ? 'active' : ''; ?>" data-category="all">
                        <span>全て表示</span>
                    </div>
                    <div class="tab-item <?php echo ($current_category == 'applications') ? 'active' : ''; ?>" data-category="applications">
                        <span>各種お申し込み</span>
                    </div>
                    <div class="tab-item <?php echo ($current_category == 'sales') ? 'active' : ''; ?>" data-category="sales">
                        <span>物販</span>
                    </div>
                </div>

                <!-- フィルタボタン -->
                <div class="filter-buttons">
                    <div class="filter-btn <?php echo ($current_filter == 'all') ? 'active' : ''; ?>" data-filter="all">
                        <span>全て表示</span>
                    </div>
                    <div class="filter-btn <?php echo ($current_filter == 'in-progress') ? 'active' : ''; ?>" data-filter="in-progress">
                        <span>進行中 ></span>
                    </div>
                    <div class="filter-btn <?php echo ($current_filter == 'confirmation') ? 'active' : ''; ?>" data-filter="confirmation">
                        <span class="warning-icon">▲</span>
                        <span>情報入力待ち ></span>
                    </div>
                    <div class="filter-btn <?php echo ($current_filter == 'unpaid') ? 'active' : ''; ?>" data-filter="unpaid">
                        <span class="warning-icon">▲</span>
                        <span>入金待ち ></span>
                    </div>
                    <div class="filter-btn <?php echo ($current_filter == 'complete') ? 'active' : ''; ?>" data-filter="complete">
                        <span>完了 ></span>
                    </div>
                </div>
            </div>

            <style>
                .user-navigation-container {
                    max-width: 1000px;
                    margin: 0 auto 80px auto;
                    padding: 0 20px;
                }

                /* カテゴリタブ */
                .category-tabs {
                    display: flex;
                    background: #f8f9fa;
                    border-radius: 8px;
                    padding: 4px;
                    margin-bottom: 20px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }

                .tab-item {
                    flex: 1;
                    text-align: center;
                    padding: 2px 8px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    font-weight: 500;
                    font-size: 16px;
                    border: 2px solid transparent;
                }

                .tab-item.active {
                    background: #A078D0;
                    color: white;
                    box-shadow: 0 2px 4px rgba(160, 120, 208, 0.3);
                }

                .tab-item:not(.active) {
                    background: white;
                    color: #A078D0;
                    border-color: #A078D0;
                }

                .tab-item:hover:not(.active) {
                    background: #f0f0f0;
                }

                /* フィルタボタン */
                .filter-buttons {
                    display: flex;
                    gap: 12px;
                    flex-wrap: wrap;
                    justify-content: center;
                }

                .filter-btn {
                    display: flex;
                    align-items: center;
                    padding: 6px 0px;
                    border-radius: 20px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    font-weight: 500;
                    font-size: 12px;
                    border: 1px solid transparent;
                    white-space: nowrap;
                    width: 140px;
                    justify-content: center;
                }

                .filter-btn.active {
                    background: #A078D0;
                    color: white;
                    box-shadow: 0 2px 4px rgba(160, 120, 208, 0.3);
                }

                .filter-btn:not(.active) {
                    background: white;
                    color: #A078D0;
                    border-color: #A078D0;
                }

                .filter-btn:hover:not(.active) {
                    background: #f8f4ff;
                    transform: translateY(-1px);
                }

                .warning-icon {
                    color: #FFD700;
                    font-size: 12px;
                    font-weight: bold;
                }

                /* レスポンシブ対応 */
                @media (max-width: 768px) {
                    .user-navigation-container {
                        padding: 0 15px;
                    }

                    .category-tabs {
                        margin-bottom: 15px;
                    }

                    .tab-item {
                        padding: 10px 15px;
                        font-size: 14px;
                    }

                    .filter-buttons {
                        gap: 8px;
                    }

                    .filter-btn {
                        padding: 8px 12px;
                        font-size: 12px;
                    }
                }

                @media (max-width: 480px) {
                    .filter-buttons {
                        flex-direction: column;
                        align-items: center;
                    }

                    .filter-btn {
                        width: 100%;
                        max-width: 200px;
                        justify-content: center;
                    }
                }
                
                /* ページネーション */
                .pagination-container {
                    display: flex;
                    justify-content: center;
                    margin: 40px 0;
                }
                
                .pagination {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
                
                .pagination-btn {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 40px;
                    height: 40px;
                    border: 1px solid #A078D0;
                    background: white;
                    color: #A078D0;
                    text-decoration: none;
                    border-radius: 8px;
                    font-weight: 500;
                    transition: all 0.3s ease;
                }
                
                .pagination-btn:hover {
                    background: #A078D0;
                    color: white;
                    transform: translateY(-1px);
                }
                
                .pagination-btn.active {
                    background: #A078D0;
                    color: white;
                    border-color: #A078D0;
                }
                
                .pagination-ellipsis {
                    color: #A078D0;
                    font-weight: bold;
                    padding: 0 8px;
                }
                
                /* フィルター機能のスタイル */
                .filter-btn {
                    cursor: pointer;
                }
                
                .tab-item {
                    cursor: pointer;
                }
            </style>

                        <script>
                        // フィルターボタンを押すとGETパラメーターを追加
                        document.addEventListener('DOMContentLoaded', function() {
                            // フィルターボタンのクリックイベント
                            document.querySelectorAll('.filter-btn').forEach(function(btn) {
                                btn.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    
                                    // 現在のURLを取得
                                    let currentUrl = window.location.href;
                                    let url = new URL(currentUrl);
                                    
                                    // 既存のページネーションパラメーターを削除
                                    url.searchParams.delete('pagenation');
                                    
                                    // フィルター値を取得
                                    let filterValue = this.getAttribute('data-filter');
                                    
                                    // フィルターパラメーターを設定
                                    url.searchParams.set('filter', filterValue);
                                    
                                    // ページをリロード
                                    window.location.href = url.toString();
                                });
                            });
                            
                            // カテゴリタブのクリックイベント
                            document.querySelectorAll('.tab-item').forEach(function(tab) {
                                tab.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    
                                    // 現在のURLを取得
                                    let currentUrl = window.location.href;
                                    let url = new URL(currentUrl);
                                    
                                    // 既存のページネーションパラメーターを削除
                                    url.searchParams.delete('pagenation');
                                    
                                    // カテゴリー値を取得
                                    let categoryValue = this.getAttribute('data-category');
                                    
                                    // カテゴリーパラメーターを設定
                                    url.searchParams.set('category', categoryValue);
                                    
                                    // ページをリロード
                                    window.location.href = url.toString();
                                });
                            });
                        });
                        </script>
    <?php  

            $incomplete_count = 0;

            if(count($spiritData["all_data"]) > 0)
            {
                
                // ページネーション用にデータを分割
                $total_items = count($spiritData["all_data"]);
                $total_pages = ceil($total_items / $items_per_page);
                $paged_data = array_slice($spiritData["all_data"], $offset, $items_per_page);

                 foreach ($paged_data as $key => $value) {
                

                    //作成中は表示しない
                    if($value["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_CREATE){
                        continue;
                    }

                    if($value["依頼者ID"] != $user_id)
                    {
                        continue;
                    }

                    $incomplete_count++;
                   // var_dump($value);
                   // echo "<br>";
                    //echo "<br>";

     ?>

                    <div class="user-jorei-list-item"  data-section="progress">
                        <div class="user-jorei-list-box">

                            <div class="user-jorei-list-title">
                                <div class="user-jorei-list-title-category"><?php echo $value["依頼タイプ名"];?></div>
                                <div class="user-jorei-list-title-name"><?php echo $value["依頼名前"];?></div>
                            </div>

                            <div class="user-jorei-list-title-date-sp">ご依頼日:<?php echo $value["依頼日年月日"];?></div>


                            <?php  if($value["会員ステータス"] != SpiritUserClass::MEMBER_STATUS_CANCEL){ ?>
                                <div class="user-jorei-list-data-box">


                                    <?php 
                                    
                                        //入金待ちの時は入金締め切り日を表示
                                        if($value["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_WATING_PAYMENT
                                        && $value["依頼タイプ"] != SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT 
                                        && $value["依頼タイプ"] != SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL
                                        && $value["依頼タイプ"] != SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){

                                            //申込日の１週間後
                                            $application_date = $value["依頼日"];

                                            $application_date_one_week_after = date('Y年n月d日', strtotime($application_date . SpiritUserClass::SPIRIT_PAYMENT_DEADLINE));
                                    ?>

                                        <div class="user-jorei-list-data-box-item">
                                            <div class="user-jorei-list-data-box-item-title">入金締め切り</div>
                                            <div class="user-jorei-list-data-box-item-value">
                                            <?php 
                                                echo $application_date_one_week_after;
                                            ?>
                                            </div>
                                        </div>
                                    <?php
                                        }
                                    ?>


                                    <?php 
                                    
                                        //対象者は日程確定依頼とリモート依頼のみ
                                        if($value["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT || $value["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY)
                                        {
                                    ?>

                                        <div class="user-jorei-list-data-box-item">
                                            <div class="user-jorei-list-data-box-item-title">対象者</div>
                                            <div class="user-jorei-list-data-box-item-value">
                                            <?php 
                                                    if($value["対象者"]["対象者情報"] == false){
                                                        echo "申込者ご本人";
                                                    }else{
                                                        echo $value["対象者"]["フル名前"];
                                                    }
                                            ?>
                                            </div>
                                        </div>

                                        
                                        <?php if($value["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY){ //日程確定?>

                                            <?php $set_spirit_sheet = $spiritScheduleData->getSpritScheduledetail($value["スケジュール"]);?>

                                            <div class="user-jorei-list-data-box-item">
                                                <div class="user-jorei-list-data-box-item-title">実地日</div>
                                                <div class="user-jorei-list-data-box-item-value">

                                                    <?php
                                                        echo $set_spirit_sheet["実行年月日"];

                                                        if($set_spirit_sheet["実行時間"] != ""){
                                                            echo " " . $set_spirit_sheet["実行時間"] ."時～";
                                                        }
                                                    ?>
                                                </div>
                                            </div>

                                            <?php if($set_spirit_sheet["場所"] != ""){?>

                                                <div class="user-jorei-list-data-box-item">
                                                    <div class="user-jorei-list-data-box-item-title">実地場所</div>
                                                    <div class="user-jorei-list-data-box-item-value">

                                                        <?php
                                                        echo $set_spirit_sheet["場所ステータス"]["名前"] . "<br>" . $set_spirit_sheet["場所ステータス"]["住所"];
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        

                                        <?php } ?>

                                    <?php }else if($value["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){ //物販?>

                                        <div class="user-jorei-list-data-box-item">
                                            <div class="user-jorei-list-data-box-item-title">個　数</div>
                                            <div class="user-jorei-list-data-box-item-value">
                                            <?php 
                                                    echo $value["販売個数"] ."個";
                                            ?>
                                            </div>
                                        </div>
                                        <div class="user-jorei-list-data-box-item">
                                            <div class="user-jorei-list-data-box-item-title">価　格</div>
                                            <div class="user-jorei-list-data-box-item-value">
                                            <?php 
                                                    echo $value["価格"] ."円（税込）";
                                            ?>
                                            </div>
                                        </div>


                                    <?php }?>

                                    <?php if($value["会員ステータス"] != SpiritUserClass::MEMBER_STATUS_WATING_PAYMENT && $value["依頼タイプ"] != SpiritTypeClass::SPIRIT_TYPE_NAME_DAY){ //入金済み ?>
                                    
                                        <div class="user-jorei-list-data-box-item">
                                            <div class="user-jorei-list-data-box-item-title">実行日</div>
                                            <div class="user-jorei-list-data-box-item-value">
                                            <?php 
                                                if($value["実行予定日"] == ""){

                                                    //この時に日程確定になっている場合はいま検討中なので、ステータスを検討中に変えておく
                                                    if($value["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_CONFIRMED){
                                                        $value["会員ステータス"] = SpiritUserClass::MEMBER_STATUS_CONFIRMATION_INFOMATION;
                                                        $value["会員ステータス表示"] = "実行日申請中";
                                                    }
                                                
                                                    echo "日程調整中（決定次第、日程が表示されます）";
                                                }else{
                                                        $execution_date = $value["実行予定日"];

                                                        //日程確定・相談はそのまま表示
                                                        if($value["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY || $value["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){
                                                            $execution_date = $value["実行日年月日"];
                                                            echo $execution_date;
                                                        }
                                                        else{

                                                            //$execution_dateの一週間前の日付を取得
                                                            $execution_date_one_week_ago = date('Y年n月d日', strtotime($execution_date . ' -1 week'));

                                                            //$execution_dateの一週間後の日付を取得
                                                            $execution_date_one_week_after = date('Y年n月d日', strtotime($execution_date . ' +1 week'));
                                                            echo $execution_date_one_week_ago . " ～ " . $execution_date_one_week_after . " 予定";
                                                        }
                                                }
                                            ?>
                                            </div>
                                        </div>

                                    <?php } ?>


                                </div>
                            <?php } ?>

                            <?php  if($value["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_CANCEL){ ?>

                                <div class="user-jorei-list-status" style="background-color: #c1b8b8;"><?php echo $value["会員ステータス表示"]; //dispMemberStatus( $status  ) ?></div>
                                <div class="user-jorei-list-button-area" style="background-color: #fffefe;">
                                    <?php echo $value["キャンセル日年月日"];?>
                                </div>


                            <?php }else{ 
                            
                                    $title_style = "";
                                    $title_box_style = "";

                                    if($value["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES)
                                    {
                                        $title_style = "background-color: #00A5A9;";
                                        $title_box_style = "background-color: #E7F4F0;";
                                    }

                            ?>



                                <div class="user-jorei-list-status" style="<?php echo $title_style; ?>"><?php echo $value["会員ステータス表示"]; //dispMemberStatus( $status  ) ?></div>


                                <div class="user-jorei-list-button-area" style="<?php echo $title_box_style; ?>">

                                    <div>
                                        <form action="<?php echo getURLSetSlag("users/user-spirit-detail"); echo $get_url["add"];?>" method="post">
                                            <button type="submit"  class="user-jorei-list-button-area-detail">詳細 ＞</button>
                                            <input type="hidden"  name="sheet_id"  value="<?php echo $value["ID"]; ?>">
                                        </form>
                                    </div>


                                    <?php if($value["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_WATING_PAYMENT){ //入金待ち?>

                                        <div>
                                            <form action="<?php echo getURLSetSlag("users/user-payment-method"); echo $get_url["add"];?>" method="post">
                                                <button type="submit"  class="user-jorei-list-button-area-input">支払い方法 ＞</button>
                                                <input type="hidden"  name="sheet_id"  value="<?php echo $value["ID"]; ?>">
                                            </form>
                                        </div>

                                    <?php }else{ ?>



                                        <?php 
                                    
                                            $input_check = $userClass->checkInputQuestionSheet( $value["会員ステータス"] , $value["実行日"] );
                                        
                                        ?>

                                        <?php if($input_check["入力"]){ //シート入力可能?>

                                            <div>
                                                <form action="<?php echo getURLSetSlag("users/user-spirit-question-edit"); echo $get_url["add"];?>" method="post">
                                                    <button type="submit"   class="user-jorei-list-button-area-input">入力シート編集 ＞</button>
                                                    <input type="hidden"  name="sheet_id" value="<?php echo $value["ID"]; ?>">
                                                </form>
                                            </div>

                                        <?php } ?>

                                        <?php if($userClass->checkConfirmationQuestionSheet( $value["会員ステータス"] , $value["実行日"] )){ //シート確認可能?>

                                            <div>
                                                <form action="<?php echo getURLSetSlag("users/user-spirit-question-edit"); echo $get_url["add"];?>" method="post">
                                                    <button type="submit"  class="user-jorei-list-button-area-input">入力シート確認 ＞</button>
                                                    <input type="hidden"  name="sheet_id"  value="<?php echo $value["ID"]; ?>">
                                                </form>
                                            </div>

                                        <?php } ?>

                                        <?php if($userClass->checkResultSheet( $value["質問場所"])){ //場所?>

                                            <div>
                                                <form action="<?php echo getURLSetSlag("users/user-spirit-question-edit"); echo $get_url["add"];?>" method="post">
                                                    <button type="submit"  class="user-jorei-list-button-area-input">時間・場所確認 ＞</button>
                                                    <input type="hidden"  name="sheet_id" value="<?php echo $value["ID"]; ?>">
                                                </form>
                                            </div>

                                        <?php } ?>

                                    <?php } ?>

                                    <?php 
                                        
                                        $treatment_result_ids = json_decode(get_field('acf_purespirit_result_img_id',$value["ID"]));
                                    
                                        if(is_array($treatment_result_ids)){

                                    ?>
                                        <div>
                                            <form action="<?php echo getURLSetSlag("users/user-spirit-result"); echo $get_url["add"];?>" method="post">
                                                <button type="submit"  class="user-jorei-list-button-area-input">結果 ＞</button>
                                                <input type="hidden"  name="sheet_id"  value="<?php echo $value["ID"]; ?>">
                                            </form>
                                        </div>

                                    <?php }?>

                                    <?php  $sales_img =  get_field('acf_previous_sales_post_img',$value["ID"]);    ?>

                                    <?php if($sales_img != ""){ ?>


                                        <div>
                                            <form action="<?php echo getURLSetSlag("users/user-spirit-result"); echo $get_url["add"];?>" method="post">
                                                <button type="submit"  class="user-jorei-list-button-area-input">追跡番号画像 ＞</button>
                                                <input type="hidden"  name="sheet_id"  value="<?php echo $value["ID"]; ?>">
                                            </form>
                                        </div>

                                    <?php }?>


                                </div>

                            <?php } ?>

                         </div>

                        
                    </div>

    <?php
                 }

     ?>

            <?php if($incomplete_count == 0){?>

                <div class="no-history-message">
                    <div class="no-history-icon">📋</div>
                    <div class="no-history-text">履歴はありません</div>
                </div>

            <?php }?>



     <?php 
            }else{
    ?>

            <div class="no-history-message">
                <div class="no-history-icon">📋</div>
                <div class="no-history-text">履歴はありません</div>
            </div>
    <?php }
            
            // ページネーション表示
            if(isset($total_pages) && $total_pages > 1) {
                echo '<div class="pagination-container">';
                echo '<div class="pagination">';
                
                // 現在のフィルターパラメーターを構築
                $filter_params = array();
                if($current_filter != 'all') {
                    $filter_params[] = 'filter=' . urlencode($current_filter);
                }
                if($current_category != 'all') {
                    $filter_params[] = 'category=' . urlencode($current_category);
                }
                if(!empty($get_url["add"])) {
                    $filter_params[] = ltrim($get_url["add"], '&?');
                }
                $additional_params = !empty($filter_params) ? '&' . implode('&', $filter_params) : '';
                
                // 前のページ
                if($current_page > 1) {
                    echo '<a href="' . getURLSetSlag("users/user-in-progress-spirit-list") . '?pagenation=' . ($current_page - 1) . $additional_params . '" class="pagination-btn no-filter">&lt;</a>';
                }
                
                // ページ番号
                $start_page = max(1, $current_page - 2);
                $end_page = min($total_pages, $current_page + 2);
                
                if($start_page > 1) {
                    echo '<a href="' . getURLSetSlag("users/user-in-progress-spirit-list") . '?pagenation=1' . $additional_params . '" class="pagination-btn no-filter">1</a>';
                    if($start_page > 2) {
                        echo '<span class="pagination-ellipsis">...</span>';
                    }
                }
                
                for($i = $start_page; $i <= $end_page; $i++) {
                    $active_class = ($i == $current_page) ? ' active' : '';
                    echo '<a href="' . getURLSetSlag("users/user-in-progress-spirit-list") . '?pagenation=' . $i . $additional_params . '" class="pagination-btn no-filter' . $active_class . '">' . $i . '</a>';
                }
                
                if($end_page < $total_pages) {
                    if($end_page < $total_pages - 1) {
                        echo '<span class="pagination-ellipsis">...</span>';
                    }
                    echo '<a href="' . getURLSetSlag("users/user-in-progress-spirit-list") . '?pagenation=' . $total_pages . $additional_params . '" class="pagination-btn no-filter">' . $total_pages . '</a>';
                }
                
                // 次のページ
                if($current_page < $total_pages) {
                    echo '<a href="' . getURLSetSlag("users/user-in-progress-spirit-list") . '?pagenation=' . ($current_page + 1) . $additional_params . '" class="pagination-btn no-filter">&gt;</a>';
                }
                
                echo '</div>';
                echo '</div>';
            }
            else {
     ?>
     <?php 
            }
    ?>


<?php 
         }
    ?>


    

</div>

<style>
/* 履歴なしメッセージのスタイル */
.no-history-message {
    text-align: center;
    padding: 60px 20px;
    margin: 40px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 16px;
    border: 2px solid #dee2e6;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.no-history-icon {
    font-size: 48px;
    margin-bottom: 20px;
    opacity: 0.7;
}

.no-history-text {
    font-size: 24px;
    font-weight: 600;
    color: #495057;
    margin-bottom: 12px;
}

.no-history-subtext {
    font-size: 16px;
    color: #6c757d;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .no-history-message {
        padding: 40px 15px;
        margin: 30px 0;
    }
    
    .no-history-icon {
        font-size: 36px;
        margin-bottom: 15px;
    }
    
    .no-history-text {
        font-size: 20px;
        margin-bottom: 10px;
    }
    
    .no-history-subtext {
        font-size: 14px;
    }
}
</style>

