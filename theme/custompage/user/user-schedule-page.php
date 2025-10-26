<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleCalendarClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSalesClass.php");
    
    //浄霊タイプ
    $spiritTypeClass = new SpiritTypeClass();

    $spiritSchedule = new SpiritScheduleClass();
    $spiritSales = new SpiritSalesClass();

    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);

    $userClass = new SpiritUserClass(); //ユーザー管理
   
    $type = "";

    if(isset($_POST["edit_schedule"]))
    {
        $type = $_POST["edit_schedule"];
    }
    //指定データ
    $spiritTypeArray = $spiritTypeClass->getSpiritTypeKeyTypeNum();


    $schedule_data = $spiritSchedule->getSpritScheduledetail( $type );


    //商品自体のデータ
    $type_data = $spiritTypeArray[ $schedule_data["施術名"] ];

    $page_id = "";
    
    //販売ページデータ
    $saledata = $spiritSales->getSalesPage($type_data , $type_data["sales_page"]);

    
     //担当者取得
     $spiritSchedule = new SpiritScheduleClass();
    
     $schedule_manager = $spiritSchedule->getScheduleManager( $schedule_data["施術名"] , $spiritTypeArray);

   /* var_dump($schedule_manager);
    echo "<br>";
    echo "<br>";
    var_dump($schedule_data);
    echo "<br>";
    echo "<br>";
    var_dump($type_data);
    echo "<br>";
    echo "<br>";
    var_dump($saledata);
    */
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@500&display=swap');
    
    * {
        font-family: 'Noto Sans JP', sans-serif;
       
    }
    
    .user-jorei-center-wrap{
        max-width: 1000px;
        margin-left: auto;
        margin-right: auto;
        padding-left: 30px;
        padding-right: 30px;
    }

    .user-jorei-top-img-wrap{
        max-width: 1000px;
        margin-left: auto;
        margin-right: auto;
    }
    .user-jorei-top-text-wrap{
        margin-top: 30px;
        font-size: 15px;
        
    }
    
    .user-jorei-message-wrap {
        margin-top: 20px;
    }
    
    .user-jorei-message-flex {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .schedule-label {
        background: #A078D0;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        min-width: 80px;
        text-align: center;
        flex-shrink: 0;
        width: 150px;
    }
    
    .schedule-value {
        color: #666666;
        font-size: 16px;
        font-weight: 500;
        padding-top: 3px;
    }

    .schedule-profile-flex{
        display: flex;
    }

    .schedule-profile-text{
        margin-left: 10px;
    }

    .schedule-profile-explanation{
        margin-top: 10px;
        font-size: 12px;
        color: #888888;
    }

    .schedule-profile-name{
        font-size: 13px;
        font-weight: 600;
        color: #888888;
    }

    .user-jorei-payment-wrap{
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .user-jorei-payment-price-flex{
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
        padding-top: 10px;
        border-bottom: 1px solid #888888;
        padding-bottom: 12px;
    }

    .user-jorei-payment-price-title{
        font-size: 16px;
        font-weight: 600;
        color: #888888;
        text-align: center;

    }

    .user-jorei-payment-title{
        padding-bottom: 10px;
        border-bottom: 1px solid #888888;
        text-align: center;
    }
    
    .user-jorei-payment-title-text{
        font-size: 20px;
        font-weight: 600;
        color: #888888;
    }

    .user-jorei-payment-price{
    }

/* 購入ボタンのスタイル */
.purchase-btn {
    font-size: 14px;
    height: 24px;
    margin-left: auto;
    margin-right: auto;
    width: 160px;
    color: #C18AD1;
    margin-top: 30px;
    border: 2px solid #B085C7;
    border-radius: 24px;
    background: #fff;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Noto Sans JP', sans-serif;
    padding: 0;
    font-weight: normal;
    transition: all 0.2s ease;
}

.purchase-btn:hover {
    background: #f8f4ff;
    color: #C18AD1;
    text-decoration: none;
}

    .purchase-btn:active {
        transform: translateY(1px);
    }
    @media (max-width: 1000px) {
        .user-jorei-message-flex {
            display: block;
           
        }
        
        .schedule-label {
            margin-bottom: 20px;
            width: 100%;
            box-sizing: border-box;
        }
    }
</style>

<div class="user-top-area" style="margin-top: 40px;">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title"><?php echo $schedule_data["表示名"];?></div>
    </div>

    <div class="user-jorei-top-img-wrap">
        <div class="user-jorei-top-img">
            <img src="<?php echo $saledata["サムネイル"];?>" alt="" style="width: 100%;">
        </div>
    </div>

   <div class="user-jorei-center-wrap">

        

        <div class="user-jorei-top-text-wrap">
            <?php $post_contens = get_post_field('post_content', $type_data["sales_page"]); ?>
            <?php if($post_contens !=  ""){ ?>
                
                <?php 
                    $allowed_tags = wp_kses_allowed_html('post');
                    $allowed_tags['img'] = array(
                        'src' => true,
                        'alt' => true,
                        'title' => true,
                        'width' => true,
                        'height' => true,
                    );
                ?>
                <?php echo wp_kses($post_contens, $allowed_tags);?>
                
            <?php } ?>


        </div>

        <?php if($saledata["URL"] != ""){?>

            <div class="user-jorei-message-wrap">
                <div class="user-jorei-message-flex">
                    <div class="schedule-label">説明サイト</div>
                    <div class="schedule-value"><a href="<?php echo $saledata["URL"]; ?>" target="_blank"><?php echo $saledata["表示名"]; ?>　説明ページへ</a>(必ずお読みください)</div>
                </div>
            </div>
            <hr style="margin-top: 30px;color:#CCCCCC;">
        <?php }else{ ?>

            <hr style="margin-top: 50px;color:#CCCCCC;">
        <?php } ?>

        

        <div class="user-jorei-message-wrap">
            <div class="user-jorei-message-flex">
                <div class="schedule-label">日時</div>
                <div class="schedule-value" style="font-weight: 600;font-size: 18px;"><?php echo $schedule_data["実行年月日"]; ?> (<?php echo date('D', strtotime($schedule_data["実行年月日"])); ?>) <?php echo $schedule_data["実行時間表示"]; ?>～</div>
            </div>
        </div>

        <hr style="margin-top: 30px;color:#CCCCCC;">

        <div class="user-jorei-message-wrap">
            <div class="user-jorei-message-flex">
                <div class="schedule-label">価格</div>
                <div class="schedule-value"><?php if($type_data["price"] > 0){?>￥<?php echo $type_data["price"]; ?>（税込）<?php }else{?>無料<?php }?></div>
            </div>
        </div>

       

        <?php $post_schedule_reservation_target = get_field("acf_sales_schedule_reservation_target", $type_data["sales_page"]);?>
        <?php if($post_schedule_reservation_target != ""){?>

            <hr style="margin-top: 30px;color:#CCCCCC;">

            <div class="user-jorei-message-wrap">
                <div class="user-jorei-message-flex">
                    <div class="schedule-label">予約対象</div>

                    <div class="schedule-value">
                    <?php 
                        $allowed_tags = wp_kses_allowed_html('post');
                        $allowed_tags['img'] = array(
                            'src' => true,
                            'alt' => true,
                            'title' => true,
                            'width' => true,
                            'height' => true,
                        );
                    ?>
                    <?php echo wp_kses($post_schedule_reservation_target, $allowed_tags);?>
                    </div>
                </div>
            </div>
        <?php } ?>


        <?php if($type_data["time"] > 0){?>

            <hr style="margin-top: 30px;color:#CCCCCC;">

            <div class="user-jorei-message-wrap">
                <div class="user-jorei-message-flex">
                    <div class="schedule-label">所要時間</div>
                    <div class="schedule-value"><?php echo $type_data["time"]; ?>分</div>
                </div>
            </div>
        <?php } ?>


        <?php if($schedule_data["人数"] > 1){?>

            <hr style="margin-top: 30px;color:#CCCCCC;">

            <div class="user-jorei-message-wrap">
                <div class="user-jorei-message-flex">
                    <div class="schedule-label">予約可能人数</div>
                    <div class="schedule-value"><?php echo $schedule_data["人数"]; ?>人(残り<?php echo $schedule_data["人数"] - $schedule_data["予約人数"]; ?>席)</div>
                </div>
            </div>
        <?php } ?>


        <?php if($schedule_data["締切"] != ""){?>

            <hr style="margin-top: 30px;color:#CCCCCC;">

            <div class="user-jorei-message-wrap">
                <div class="user-jorei-message-flex">
                    <div class="schedule-label">申込締め切り</div>
                    <div class="schedule-value"><?php echo $schedule_data["締切年月日"]; ?></div>
                </div>
            </div>
        <?php } ?>

        <?php if($schedule_data["担当者"] != ""){?>
            <hr style="margin-top: 30px;color:#CCCCCC;">

            <div class="user-jorei-message-wrap">
                <div class="user-jorei-message-flex">
                    <div class="schedule-label">担当者</div>
                    <div class="schedule-value">
                        <?php $user_data = get_userdata($schedule_data["担当者"]);?>

                        <div class="schedule-profile-flex">
                            <div class="schedule-profile-img">
                            <?php 
                                $image_id = get_field('acf_teacher_profile_img', 'user_' . $schedule_data["担当者"]);
                                if ($image_id) {
                                    $image_url = wp_get_attachment_image_url($image_id, 'medium');
                                    if (!$image_url) {
                                        $image_url = $image_id["url"]; // URLが直接保存されている場合
                                    }
                                } else {
                                    $image_url = get_template_directory_uri().'/assets/images/noimage.jpg';
                                }
                                
                                
                            ?>
                                <img src="<?php echo $image_url;?>" alt="" style="width: 100%;max-width: 80px;">
                            </div>

                            <div class="schedule-profile-text">
                                <div class="schedule-profile-name"><?php echo $user_data->display_name;?></div>
                                <div class="schedule-profile-explanation"><?php echo nl2br(get_field('acf_teacher_explanation', 'user_' . $schedule_data["担当者"]));?></div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        <?php } ?>


        <?php if($schedule_data["場所"] != ""){?>
            <hr style="margin-top: 30px;color:#CCCCCC;">

            <div class="user-jorei-message-wrap">
                <div class="user-jorei-message-flex">
                    <div class="schedule-label">場所</div>
                    <div class="schedule-value">
                        <?php echo $schedule_data["場所ステータス"]["名前"];?><br>
                        <?php echo $schedule_data["場所ステータス"]["住所"];?>
                        <?php if($schedule_data["場所ステータス"]["住所"] != ""){?>
                            <br>
                            <?php //GoogleMapのURLを作成
                                $google_map_url = "https://www.google.com/maps/search/?api=1&query=" . urlencode($schedule_data["場所ステータス"]["住所"]);
                            ?>
                            <br>
                           （ <a href="<?php echo $google_map_url;?>" target="_blank" >GoogleMapで地図を表示</a>）
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>


        <?php if($type_data["price"] > 0){?>
            <hr style="margin-top: 30px;color:#CCCCCC;">

            <div class="user-jorei-message-wrap">
                <div class="user-jorei-message-flex">
                    <?php if(count($schedule_data["支払い方法"]) > 0){ ?>   
                        <div class="schedule-label">お支払い方法</div>
                        <?php foreach($schedule_data["支払い方法"] as $key => $value){ ?>
                            <div class="schedule-value"><?php echo $userClass->getPaymentTypeLabel($value); ?>,</div>
                        <?php } ?>
                    <?php }else{ ?>
                        <div class="schedule-value">クレジットカード</div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <?php $post_schedule_precautions = get_field("acf_sales_schedule_precautions", $type_data["sales_page"]);?>
        <?php if($post_schedule_precautions != ""){?>

            <hr style="margin-top: 30px;color:#CCCCCC;">

            <div class="user-jorei-message-wrap">
                <div class="user-jorei-message-flex">
                    <div class="schedule-label">注意事項</div>

                    <div class="schedule-value">
                    <?php 
                        $allowed_tags = wp_kses_allowed_html('post');
                        $allowed_tags['img'] = array(
                            'src' => true,
                            'alt' => true,
                            'title' => true,
                            'width' => true,
                            'height' => true,
                        );
                    ?>
                    <?php echo wp_kses($post_schedule_precautions, $allowed_tags);?>
                    </div>
                </div>
            </div>
        <?php } ?>


        <?php $post_schedule_precautions = get_field("acf_sales_schedule_remarks", $type_data["sales_page"]);?>
        <?php if($post_schedule_precautions != ""){?>

            <hr style="margin-top: 30px;color:#CCCCCC;">

            <div class="user-jorei-message-wrap">
                <div class="user-jorei-message-flex">
                    <div class="schedule-label">備考</div>

                    <div class="schedule-value">
                    <?php 
                        $allowed_tags = wp_kses_allowed_html('post');
                        $allowed_tags['img'] = array(
                            'src' => true,
                            'alt' => true,
                            'title' => true,
                            'width' => true,
                            'height' => true,
                        );
                    ?>
                    <?php echo wp_kses($post_schedule_precautions, $allowed_tags);?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php $post_add_title_1 = get_field("acf_sales_schedule_add_title_1", $type_data["sales_page"]);?>
        <?php $post_add_txt_1 = get_field("acf_sales_schedule_add_txt_1", $type_data["sales_page"]);?>
        <?php if($post_add_title_1 != "" && $post_add_txt_1 != ""){?>

            <hr style="margin-top: 30px;color:#CCCCCC;">

            <div class="user-jorei-message-wrap">
                <div class="user-jorei-message-flex">
                    <div class="schedule-label"><?php echo $post_add_title_1;?></div>

                    <div class="schedule-value">
                    <?php 
                        $allowed_tags = wp_kses_allowed_html('post');
                        $allowed_tags['img'] = array(
                            'src' => true,
                            'alt' => true,
                            'title' => true,
                            'width' => true,
                            'height' => true,
                        );
                    ?>
                    <?php echo wp_kses($post_add_txt_1, $allowed_tags);?>
                    </div>
                </div>
            </div>
        <?php } ?>


        <div class="user-jorei-payment-wrap" style="margin-top: 50px;">

        <form id="purchase-form" action="" method="post">

            <input type="hidden" name="schedule-id" value="<?php echo $type;?>">
            <input type="hidden" name="people-count" id="people-count" value="1">
            <input type="hidden" name="save-unixtime" id="save-unixtime" value="<?php echo time();?>">


                <?php if($type_data["price"] > 0){?>
                    <div class="user-jorei-payment-title">
                        <div class="user-jorei-payment-title-text">お会計合計</div>
                    </div>

                    <div class="user-jorei-payment-price" style="margin-top: 40px;">
                        <div class="user-jorei-payment-price-flex">
                            <div class="user-jorei-payment-price-title">ご購入単価</div>
                            <div class="user-jorei-payment-price-text">
                                ￥<?php echo $type_data["price"]; ?>（税込）
                            </div>
                        </div>
                       
                        <div class="user-jorei-payment-price-flex">
                            <div class="user-jorei-payment-price-title">予約人数</div>
                            <div class="user-jorei-payment-price-text" style="margin-right: 20px;">
                            <?php 
                                    if($schedule_data["人数"] != 1 && $schedule_data["人数"] - $schedule_data["予約人数"] > 0){

                                        $people_num = $schedule_data["人数"] - $schedule_data["予約人数"];
                                ?>
                                <select name="user-schedule-people" id="user-schedule-people" onchange="updateTotal()">
                                    <?php for($i = 1; $i <= $people_num; $i++){?>
                                        <option value="<?php echo $i;?>"><?php echo $i;?>人</option>
                                    <?php }?>
                                </select>
                            <?php }else{?>
                                1人
                            <?php }?>
                            </div>
                        </div>
                        <?php if($type_data["price"] > 0){?>
                            <div class="user-jorei-payment-price-flex">
                                <div class="user-jorei-payment-price-title">お支払い方法</div>
                                <div class="user-jorei-payment-price-text" style="margin-right: 20px;">
                            
                                    <select name="user-schedule-payment" id="user-schedule-payment" required>
                                        <?php if(count($schedule_data["支払い方法"]) > 1){?>
                                            <option value="">お支払い方法を選択してください</option>
                                        <?php }?>
                                        <?php foreach($schedule_data["支払い方法"] as $key => $value){?>
                                            <option value="<?php echo $value;?>"><?php echo $userClass->getPaymentTypeLabel($value);?></option>
                                        <?php }?>
                                    </select>
                            
                                </div>
                            </div>


                            <div class="user-jorei-payment-price-flex">
                                <div class="user-jorei-payment-price-title">お支払い期限</div>
                                <div class="user-jorei-payment-price-text" style="margin-right: 20px;">
                            
                                    <?php
                                        //本日の日付(タイムゾーンは東京)
                                        date_default_timezone_set('Asia/Tokyo');
                                        $today = date("Y-m-d");

                                        //本日の日付から7日分の日付配列
                                        $date_array = array();
                                        for($i = 0; $i < 7; $i++){
                                            $date_array[] = date("Y-m-d", strtotime($today . "+" . $i . " day"));
                                        }

                                        
                                    ?>
                                    <select name="previous_payment_limit_day" id="previous_payment_limit_day" required>
                                        <?php foreach($date_array as $key => $value){?>
                                            <option value="<?php echo $value;?>"><?php echo $value;?></option>
                                        <?php }?>
                                    </select>
                            
                                </div>
                            </div>

                        <?php }?>
                    
                    
                        <div class="user-jorei-payment-price-flex">
                            <div class="user-jorei-payment-price-title">お支払い合計</div>
                            <div class="user-jorei-payment-price-text">
                                ￥<span id="total-price"><?php echo $type_data["price"] * 1; ?></span>（税込）
                            </div>
                        </div>
                    </div>
                <?php }?>


                <?php if($schedule_data["締切"] > date("Y-m-d")){?>
                    <?php if($schedule_data["人数"] - $schedule_data["予約人数"] > 0){?>
                        <div class="user-account-edit-form-btn-wrap" style="margin-top: 30px; display: flex; justify-content: center;">
                            <?php if($type_data["price"] > 0){?>
                                <button type="button" class="purchase-btn" style="height: 60px;width: 260px;font-size: 28px;margin-top: 50px;" onclick="confirmPurchase()">申し込む</button>
                            <?php }else{?>
                                <button type="button" class="purchase-btn" style="height: 60px;width: 260px;font-size: 28px;margin-top: 50px;" onclick="confirmPurchaseFreeFree()">申し込む</button>
                            <?php }?>
                        </div>
                    <?php }else{?>
                        <div class="user-account-edit-form-btn-wrap" style="margin-top: 30px; display: flex; justify-content: center;">
                            <button type="button" class="purchase-btn" style="height: 60px;width: 260px;font-size: 28px;margin-top: 50px;">申込できません</button>
                        </div>
                    <?php }?>
                <?php }else{?>
                    <div class="user-account-edit-form-btn-wrap" style="margin-top: 30px; display: flex; justify-content: center;">
                        <button type="button" class="purchase-btn" style="height: 60px;width: 260px;font-size: 16px;margin-top: 50px;">この日程は締め切りました</button>
                    </div>
                <?php }?>
            </form>

        </div>


   </div>


   <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user-schedule-data"); echo $get_url["add"]; if( $get_url["add"] == ""){ echo "?type=" . $schedule_data["施術グループ"];}else{ echo "&type=" . $schedule_data["施術グループ"];}?>" class="user-account-edit-return-btn">日程選択へ戻る  &gt;</a>
    </div>

    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
    </div>
</div>

<script>
// 合計金額を更新する関数
function updateTotal() {
    const peopleSelect = document.getElementById('user-schedule-people');
    const totalPriceElement = document.getElementById('total-price');
    const peopleCountInput = document.getElementById('people-count');
    
    if (peopleSelect && totalPriceElement) {
        const selectedPeople = parseInt(peopleSelect.value);
        const basePrice = <?php echo $type_data["price"]; ?>;
        const totalPrice = basePrice * selectedPeople;
        
        totalPriceElement.textContent = totalPrice.toLocaleString();
        
        // 隠しフィールドも更新
        if (peopleCountInput) {
            peopleCountInput.value = selectedPeople;
        }
    }
}

function confirmPurchase() {
    // 支払い方法の選択をチェック
    const paymentSelect = document.getElementById('user-schedule-payment');
    if (!paymentSelect || paymentSelect.value === '') {
        alert('お支払い方法を選択してください。');
        return;
    }
   
    // 支払い方法に応じてフォームのactionを設定
    const form = document.getElementById('purchase-form');
    const paymentValue = paymentSelect.value;
    
    if (paymentValue === '2' || paymentValue === '4') {
        form.action = '<?php echo getURLSetSlag("users/user-schedule-procedure"); echo $get_url["add"]; ?>';
    } else {
        form.action = '<?php echo getURLSetSlag("users/user-schedule-credit-procedure"); echo $get_url["add"]; ?>';
    }
    
    // 支払い方法に応じてメッセージを変更
    let message = `こちらで申し込みますか？\n\n`;
    
    
    if (confirm(message)) {
        // フォームを送信
        form.submit();
    }
}


function confirmPurchaseFreeFree() {
   
   const message = `こちらで申し込みますか？\n\n`;
   
   if (confirm(message)) {
       // 無料の場合は通常の手続きページへ
       const form = document.getElementById('purchase-form');
       form.action = '<?php echo getURLSetSlag("users/user-schedule-procedure"); echo $get_url["add"]; ?>';
       form.submit();
   }
}
</script>