<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");


    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);

    $userClass = new SpiritUserClass(); //ユーザー管理


    $userData = $userClass->getUserAcountData($user_id);

    $target_id = "";

    $update_text = "";


    if(isset($_POST["target_id"]))
    {
        $target_id = $_POST["target_id"];
    }


    //保存
    if(isset($_POST["edit_target"]))
    {
        if($target_id == "")
        {
            //新規登録
            $target_id = $userClass->newTargetAcountData($user_id);
        }
        
        //保存
        $userClass->saveTargetAcountData($user_id,$target_id,$_POST);

        $update_text = "<div style='color:red;text-align:center;font-weight:bold;margin-bottom: 50px;'>対象者情報を更新しました</div>";
    }



    $targetData = $userClass->getUserTargetData($user_id,$target_id);
    
?>


<div class="user-top-area">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title">対象者情報の編集</div>
    </div>

    <?php include(dirname(__FILE__)."/user-acount-menu.php"); ?>


    <?php echo $update_text; ?>

    <form action="<?php echo getURLSetSlag("users/user-target");echo $get_url["add"];?>" method="post" >

        <input type="hidden" name="edit_target" value="">
        <input type="hidden" name="target_id" value="<?php echo $target_id;?>">

        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">名　前</div>
                        <div class="user-account-edit-wrap-sp-title">名　前</div>
                        <div class="user-account-edit-wrap-pc-required-area"><div class="user-account-edit-wrap-pc-required-label">必須</div></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content"><input class="user-account-edit-wrap-pc-text-one" type="text" name="input_last_name" value="<?php echo $targetData["苗字"]; ?>" placeholder="姓" required></div>
                        <div class="user-account-edit-wrap-pc-content"><input class="user-account-edit-wrap-pc-text-one" type="text" name="input_first_name" value="<?php echo $targetData["名前"]; ?>" placeholder="名" required></div>
                    </div>
                </div>
            </div>
        </div>


        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">ナマエ</div>
                        <div class="user-account-edit-wrap-sp-title">ナマエ</div>
                        <div class="user-account-edit-wrap-pc-required-area"><div class="user-account-edit-wrap-pc-required-label">必須</div></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content"><input class="user-account-edit-wrap-pc-text-one" type="text" name="input_last_name_kana" value="<?php echo $targetData["ミョウジ"]; ?>" placeholder="セイ" required></div>
                        <div class="user-account-edit-wrap-pc-content"><input class="user-account-edit-wrap-pc-text-one" type="text" name="input_first_name_kana" value="<?php echo $targetData["ナマエ"]; ?>" placeholder="メイ" required></div>
                    </div>
                </div>
            </div>
            
        </div>


        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">メールアドレス</div>
                        <div class="user-account-edit-wrap-sp-title">メールアドレス</div>
                        <div class="user-account-edit-wrap-pc-required-area"></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex" style="display: block;">
                        <div class="user-account-edit-wrap-pc-content">
                            <input class="user-account-edit-wrap-pc-text-one" style="width: 300px;" type="mail" name="input_mail" id="input_mail" value="<?php echo $targetData["メール"]; ?>">
                        </div>
                        <div class="user-account-edit-wrap-pc-content" style="margin-top: 10px;">
                            <button type="button" id="button" class="user-account-edit-copy-btn" onclick="copyValue('<?php echo $userData["メール"]; ?>','input_mail')" class="target_button">申込者情報をコピー</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">連絡先</div>
                        <div class="user-account-edit-wrap-sp-title">連絡先</div>
                        <div class="user-account-edit-wrap-pc-required-area"></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex" style="display: block;">
                        <div class="user-account-edit-wrap-sp-flex">
                            <div class="user-account-edit-wrap-pc-content">
                                <input class="user-account-edit-wrap-pc-text-one" type="text" name="input_tel_1"  id="input_tel_1" style="width: 60px;" value="<?php echo $targetData["電話番号1"]; ?>" placeholder="例: 090" oninput="this.value = this.value.replace(/[^0-9]/g, '')"  >-
                            </div>
                            <div class="user-account-edit-wrap-pc-content">
                                <input class="user-account-edit-wrap-pc-text-one" type="text" name="input_tel_2" id="input_tel_2" style="width: 60px;" value="<?php echo $targetData["電話番号2"]; ?>" placeholder="例: 1234" oninput="this.value = this.value.replace(/[^0-9]/g, '')" >-
                            </div>
                            <div class="user-account-edit-wrap-pc-content">
                                <input class="user-account-edit-wrap-pc-text-one" type="text" name="input_tel_3" id="input_tel_3" style="width: 60px;" value="<?php echo $targetData["電話番号3"]; ?>" placeholder="例: 5678" oninput="this.value = this.value.replace(/[^0-9]/g, '')" >
                            </div>
                        </div>
                        <div class="user-account-edit-wrap-pc-content" style="margin-top: 10px;">
                            <button type="button" id="button" class="user-account-edit-copy-btn" onclick="copyTelValue('<?php echo $userData["電話番号1"]; ?>','<?php echo $userData["電話番号2"]; ?>','<?php echo $userData["電話番号3"]; ?>','input_tel_1','input_tel_2','input_tel_3')" class="target_button">申込者情報をコピー</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="user-account-edit-wrap">
    <div class="user-account-edit-wrap-pc">
        <div class="user-account-edit-wrap-pc-flex">
            <div class="user-account-edit-wrap-pc-title-flex">
                <div class="user-account-edit-wrap-pc-title">性　別</div>
                <div class="user-account-edit-wrap-sp-title">性　別</div>
                <div class="user-account-edit-wrap-pc-required-area"></div>
            </div>
            <!-- ここにuser-account-edit-sex-rowクラスを追加 -->
            <div class="user-account-edit-wrap-sp-flex user-account-edit-sex-row">
                <div class="user-account-edit-wrap-pc-content">
                    <select name="input_user_sex" id="input_user_sex" class="user-account-edit-wrap-pc-select">
                        <option value="U" <?php if ($targetData["性別値"] == "U") echo "selected"; ?>>性　別</option>
                        <option value="M" <?php if ($targetData["性別値"] == "M") echo "selected"; ?>>男　性</option>
                        <option value="W" <?php if ($targetData["性別値"] == "W") echo "selected"; ?>>女　性</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>


        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">生年月日</div>
                        <div class="user-account-edit-wrap-sp-title">生年月日</div>
                        <div class="user-account-edit-wrap-pc-required-area"><div class="user-account-edit-wrap-pc-required-label">必須</div></div>
                    </div>
                   
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content" style="margin-right: 8px;">
                            <input type="number" min="1" name="input_user_born_year" class="user-account-edit-wrap-pc-text-one" value="<?php echo $targetData["誕生日年"]; ?>" style="width: 50px;margin-right: 0;" required>年
                        </div>
                        <div class="user-account-edit-wrap-pc-content"  style="margin-right: 8px;">
                            <select name="input_user_born_month" id="input_user_born_month" class="user-account-edit-wrap-pc-select" required>
                                <option value=""></option>
                                <?php for ($i = 1; $i <= 12; $i++) { ?>
                                    <option value="<?php echo $i; ?>" <?php if ($targetData["誕生日月"] == $i) echo "selected"; ?>><?php echo $i; ?></option>
                                <?php } ?>
                            </select>月
                        </div>
                        <div class="user-account-edit-wrap-pc-content">
                        <select name="input_user_born_day" id="input_user_born_day" class="user-account-edit-wrap-pc-select" required>
                                <option value=""></option>
                                <?php for ($i = 1; $i <= 31; $i++) { ?>
                                    <option value="<?php echo $i; ?>" <?php if ($targetData["誕生日日"] == $i) echo "selected"; ?>><?php echo $i; ?></option>
                                <?php } ?>
                            </select>日
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">

                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">届出日</div>
                        <div class="user-account-edit-wrap-sp-title">届出日</div>
                        <div class="user-account-edit-wrap-pc-required-area"></div>
                    </div>

                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content" style="margin-right: 8px;">
                            <input type="number" min="1" name="input_user_report_year" class="user-account-edit-wrap-pc-text-one" value="<?php echo $targetData["届け出日年"]; ?>" style="width: 50px;margin-right: 0;">年
                        </div>
                        <div class="user-account-edit-wrap-pc-content"  style="margin-right: 8px;">
                            <select name="input_user_report_month" id="input_user_report_month" class="user-account-edit-wrap-pc-select">
                                <option value=""></option>
                                <?php for ($i = 1; $i <= 12; $i++) { ?>
                                    <option value="<?php echo $i; ?>" <?php if ($targetData["届け出日月"] == $i) echo "selected"; ?>><?php echo $i; ?></option>
                                <?php } ?>
                            </select>月
                        </div>
                        <div class="user-account-edit-wrap-pc-content">
                        <select name="input_user_report_day" id="input_user_report_day" class="user-account-edit-wrap-pc-select">
                                <option value=""></option>
                                <?php for ($i = 1; $i <= 31; $i++) { ?>
                                    <option value="<?php echo $i; ?>" <?php if ($targetData["届け出日日"] == $i) echo "selected"; ?>><?php echo $i; ?></option>
                                <?php } ?>
                            </select>日
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">郵便番号</div>
                        <div class="user-account-edit-wrap-sp-title">郵便番号</div>
                        <div class="user-account-edit-wrap-pc-required-area"></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex" style="display: block;">
                        <div class="user-account-edit-wrap-pc-content">
                            <input class="user-account-edit-wrap-pc-text-one" type="text" name="input_post_no" id="zipcode"   value="<?php echo $targetData["郵便番号"]; ?>" placeholder="半角数字のみ、「-」なし" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        <div class="user-account-edit-wrap-sp-flex">
                            <div class="user-account-edit-wrap-pc-content">
                                <button type="button" onclick="getUserAddress()" class="user-account-edit-copy-btn" >住所検索</button> 
                            </div>
                            <div class="user-account-edit-wrap-pc-content" style="margin-left: 10px;">
                                <button type="button" class="user-account-edit-copy-btn" id="button" onclick="copyTelValue('<?php echo $userData["郵便番号"]; ?>','<?php echo $userData["住所1"]; ?>','<?php echo $userData["住所2"]; ?>','input_post_no','input_address1','input_address2')" class="target_button">申込者情報をコピー</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">住　所</div>
                        <div class="user-account-edit-wrap-sp-title">住　所</div>
                        <div class="user-account-edit-wrap-pc-required-area"></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content"style="width: 100%;">
                            <input class="user-account-edit-wrap-pc-text-one" type="text" name="input_address1" style="width: 100%;" id="address" value="<?php echo $targetData["住所1"]; ?>" placeholder="都道府県 市区町村">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">建物名等</div>
                        <div class="user-account-edit-wrap-sp-title">建物名等</div>
                        <div class="user-account-edit-wrap-pc-required-area"></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content" style="width: 100%;">
                            <input class="user-account-edit-wrap-pc-text-one" type="text"  style="width: 100%;" name="input_address2" id="address2" value="<?php echo $targetData["住所2"]; ?>" placeholder="番地・マンション名等">
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">申込者との関係</div>
                        <div class="user-account-edit-wrap-sp-title">申込者との関係</div>
                        <div class="user-account-edit-wrap-pc-required-area"><div class="user-account-edit-wrap-pc-required-label">必須</div></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content">
                            <input class="user-account-edit-wrap-pc-text-one" type="text" name="input_user_target_relationship" id=""  value="<?php echo $targetData["関係"]; ?>" placeholder="長男、兄、父、祖母など" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="user-account-edit-form-btn-wrap">
            <button type="submit" id="form-save" class="user-account-edit-form-btn" onclick="updateAccount()">保　存 ＞</button>
        </div>

    </form>


    <div class="user-account-edit-form-btn-wrap">
        <a href="<?php echo getURLSetSlag("users/user-target-list"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">対象者情報一覧  &gt;</a>
    </div>


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

    //値コピー
    function copyValue(sourceValue1, destinationName1) {
            
        // コピー先に値を設定
        document.querySelector(`input[name="${destinationName1}"]`).value = sourceValue1;
    }

    //電話番号コピー
    function copyTelValue(sourceValue1,sourceValue2,sourceValue3, destinationTel1,destinationTel2, destinationTel3) {
            
        // コピー先に値を設定
        document.querySelector(`input[name="${destinationTel1}"]`).value = sourceValue1;
        document.querySelector(`input[name="${destinationTel2}"]`).value = sourceValue2;
        document.querySelector(`input[name="${destinationTel3}"]`).value = sourceValue3;
    }

    


</script>