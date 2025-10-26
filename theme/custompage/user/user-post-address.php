<?php 

   require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
   require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");


    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);

    $userClass = new SpiritUserClass(); //ユーザー管理



    $post_id = "";

    if(isset($_POST["post_id"])){
        $post_id = $_POST["post_id"];
    }

    //削除
    if(isset($_POST["delete_post"]))
    {
        wp_delete_post($_POST["delete_id"]);
    }

    //優先
    if(isset($_POST["main_post"]))
    {
        $userClass->setUserPostAddressMain($_POST["main_id"],$user_id);
    }

    //var_dump($_POST);
    //保存
    if(isset($_POST["save_post"]))
    {

        if($_POST["acf_post_id"] != ""){
            $userClass->editUserPostAddress($_POST["acf_post_id"],$_POST);//編集
        }else{
            $userClass->saveUserPostAddress($user_id,$_POST);//保存

            //新規の場合は$post_idを空にする
            $post_id = "";
        }
    }


    //一覧
    $post_address_list = $userClass->getUserPostAddressList($user_id);

    //編集用
    $post_address_data = $userClass->getUserPostAddressArray($post_id);

    //カートを確認する
    $cart_count = get_cart_item_count($user_id);


    //var_dump($_POST);
?>
<style>
        /* モーダルの背景（オーバーレイ） */
        #modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        /* モーダルボックス */
        #modal-box {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            z-index: 1000;
            max-width: 300px;
        }

        #modal-box h2 {
            margin-top: 0;
            font-size: 20px;
        }

        #modal-box p {
            margin-bottom: 20px;
            font-size: 16px;
        }

        #close-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        #close-btn:hover {
            background-color: #45a049;
        }

        /* 郵送先一覧のスタイル */
        .post-address-list {
            max-width: 800px;
            margin: 40px auto 0;
            padding: 0 20px;
        }

        .post-address-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .post-address-header h3 {
            color: #333;
            font-size: 20px;
            font-weight: 600;
            margin: 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #B085C7;
        }

        .post-address-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: box-shadow 0.2s ease;
        }

        .post-address-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .post-address-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .post-address-details {
            flex: 1;
        }

        .post-address-name {
            font-size: 16px;
            color: #333;
            margin-bottom: 8px;
        }

        .post-address-address {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
        }

        .post-address-actions {
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }

        .post-address-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .edit-btn {
            background: #007cba;
            color: white;
        }

        .edit-btn:hover {
            background: #005a8b;
        }

        .delete-btn {
            background: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background: #c82333;
        }

        .main-btn {
            background: #28a745;
            color: white;
        }

        .main-btn:hover {
            background: #218838;
        }

        .post-address-empty {
            text-align: center;
            padding: 40px 20px;
            color: #666;
            font-size: 16px;
        }

        /* レスポンシブ対応 */
        @media (max-width: 768px) {
            .post-address-info {
                flex-direction: column;
                gap: 15px;
            }

            .post-address-actions {
                justify-content: center;
            }

            .post-address-card {
                padding: 15px;
            }
        }
    </style>




<div class="user-top-area">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title">郵送先の登録・編集</div>
    </div>

    <?php include(dirname(__FILE__)."/user-acount-menu.php"); ?>

    <form action="<?php echo getURLSetSlag("users/user-post-address");echo $get_url["add"];?>" method="post" style="max-width: 800px;margin-left: auto;margin-right: auto;">


        <input type="hidden" name="save_post" value="">
        <input type="hidden" name="acf_post_id" value="<?php echo $post_id; ?>">


        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">郵送先／郵便番号</div>
                        <div class="user-account-edit-wrap-sp-title">郵送先／郵便番号</div>
                        <div class="user-account-edit-wrap-pc-required-area"><div class="user-account-edit-wrap-pc-required-label">必須</div></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content">
                            <input class="user-account-edit-wrap-pc-text-one" type="text" name="acf_post_billing_postcode" id="post_zipcode"   value="<?php echo $post_address_data["郵送先郵便番号"]; ?>" placeholder="半角数字のみ、「-」なし" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                        </div>
                        <div class="user-account-edit-wrap-pc-content">
                            <button type="button" onclick="getUserPostAddress()" class="user-account-edit-copy-btn" style="margin-left: 10px;">住所検索</button> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">郵送先／住所</div>
                        <div class="user-account-edit-wrap-sp-title">郵送先／住所</div>
                        <div class="user-account-edit-wrap-pc-required-area"><div class="user-account-edit-wrap-pc-required-label">必須</div></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content" style="width: 100%;">
                            <input class="user-account-edit-wrap-pc-text-one" type="text" name="acf_post_billing_city" style="width: 100%;" id="post_address" value="<?php echo $post_address_data["郵送先住所1"]; ?>" placeholder="都道府県 市区町村" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">郵送先／建物名等</div>
                        <div class="user-account-edit-wrap-sp-title">郵送先／建物名等</div>
                        <div class="user-account-edit-wrap-pc-required-area"></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content" style="width: 100%;">
                            <input class="user-account-edit-wrap-pc-text-one" type="text" name="acf_post_billing_address_1" style="width: 100%;" id="post_address2" value="<?php echo $post_address_data["郵送先住所2"]; ?>" placeholder="番地・マンション名等">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">郵送先／名　前</div>
                        <div class="user-account-edit-wrap-sp-title">郵送先／名　前</div>
                        <div class="user-account-edit-wrap-pc-required-area"><div class="user-account-edit-wrap-pc-required-label">必須</div></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content"><input class="user-account-edit-wrap-pc-text-one" type="text" name="acf_post_billing_first_name" value="<?php echo $post_address_data["郵送先名前"]; ?>" placeholder="送り先" required></div>
                    </div>
                </div>
            </div>
        </div>
        
        <input type="hidden" name="acf_post_unixtime" value="<?php echo time(); ?>">
        <div class="user-account-edit-form-btn-wrap">
            <button type="submit" id="form-save" class="user-account-edit-form-btn" ><?php if($post_id != ""){echo "更新";}else{echo "保存";} ?> ＞</button>
        </div>

    </form>


    <?php if(count($post_address_list) > 0){ ?>
        <div class="post-address-list">
            <div class="post-address-header">
                <h3>登録済み郵送先一覧</h3>
            </div>
            
            <?php foreach($post_address_list as $post_key => $post_address_data){ ?>
                <div class="post-address-card" <?php if($post_address_data["メイン"] == "1"){echo "style='border: 2px solid #007cba;'";} ?>>
                    <div class="post-address-info">
                        <div class="post-address-details">
                            <div class="post-address-name">
                                <strong><?php echo $post_address_data["郵送先名前"]; ?></strong>
                            </div>
                            <div class="post-address-address">
                                〒<?php echo $post_address_data["郵送先郵便番号"]; ?><br>
                                <?php echo $post_address_data["郵送先住所1"]; ?><?php echo $post_address_data["郵送先住所2"] ? '　' . $post_address_data["郵送先住所2"] : ''; ?>
                            </div>
                        </div>
                        <div class="post-address-actions">

                            <?php if($post_address_data["メイン"] == ""){ ?>
                                <form action="<?php echo getURLSetSlag("users/user-post-address"); echo $get_url["add"]; ?>" method="post" style="display: inline;">
                                    <input type="hidden" name="main_id" value="<?php echo $post_key; ?>">
                                    <button type="submit" name="main_post" class="post-address-btn main-btn">優先</button>
                                </form>
                            <?php } ?>
                            <form action="<?php echo getURLSetSlag("users/user-post-address"); echo $get_url["add"]; ?>" method="post" style="display: inline;">
                                <input type="hidden" name="post_id" value="<?php echo $post_key; ?>">
                                <button type="submit" name="edit_post" class="post-address-btn edit-btn">編集</button>
                            </form>
                            <form action="<?php echo getURLSetSlag("users/user-post-address"); echo $get_url["add"]; ?>" method="post" style="display: inline;">
                                <input type="hidden" name="delete_id" value="<?php echo $post_key; ?>">
                                <button type="submit" name="delete_post" class="post-address-btn delete-btn" onclick="return confirm('この郵送先を削除しますか？')">削除</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php }else{ ?>
       
    <?php } ?>

    <div class="user-account-edit-form-btn-wrap">
        <a href="<?php echo getURLSetSlag("users/user-acount"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">会員情報へ戻る  &gt;</a>
    </div>

    <?php if($cart_count > 0){ ?>
        <div class="user-account-edit-form-btn-wrap" style="margin-top: 20px;margin-bottom: 100px;">
            <a href="<?php echo getURLSetSlag("users/user-store-cart-procedure"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">ご購入手続きへ  &gt;</a>
        </div>
    <?php } ?>


    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
    </div>
 </div>



 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

   function getUserAddress() {
        var zipcode = document.getElementById('zipcode').value;
        if (!zipcode) {
            alert("郵便番号を入力してください");
            return;
        }
    
        $.ajax({
            url: "https://zipcloud.ibsnet.co.jp/api/search?zipcode=" + zipcode,
            dataType: "jsonp",
            success: function(response) {
                if (response.status === 200 && response.results) {
                    var result = response.results[0];
                    var address = result.address1 + result.address2 + result.address3;
                    // document.getElementById('address').value = "'" + address + "'";
                    document.getElementById('address').value = address;
                } else {
                    alert("住所が見つかりませんでした");
                }
            },
            error: function() {
                alert("住所の取得に失敗しました");
            }
        });
    }
    function getUserPostAddress() {
        var zipcode = document.getElementById('post_zipcode').value;
        if (!zipcode) {
            alert("郵便番号を入力してください");
            return;
        }
    
        $.ajax({
            url: "https://zipcloud.ibsnet.co.jp/api/search?zipcode=" + zipcode,
            dataType: "jsonp",
            success: function(response) {
                if (response.status === 200 && response.results) {
                    var result = response.results[0];
                    var address = result.address1 + result.address2 + result.address3;
                    // document.getElementById('address').value = "'" + address + "'";
                    document.getElementById('post_address').value = address;
                } else {
                    alert("住所が見つかりませんでした");
                }
            },
            error: function() {
                alert("住所の取得に失敗しました");
            }
        });
    }

    
</script>
 