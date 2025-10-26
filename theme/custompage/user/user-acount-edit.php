<?php 

   require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
   require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");


    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);

    $userClass = new SpiritUserClass(); //ユーザー管理




    //保存
    if(isset($_POST["save_acount"]))
    {

        $userClass->saveUserAcountData($user_id,$_POST);
        
        // 申請の場合の処理
        if(isset($_POST["auth"]) && $_POST["auth"] == "true")
        {
            // 認証申請の処理
            update_user_meta($user_id, 'user_date_complete', '1');
            //error_log("認証申請が送信されました。ユーザーID: " . $user_id);
        }
    }


    $userData = $userClass->getUserAcountData($user_id);
    $user_meta = get_user_by('id',$user_id);


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

        /* 申請モーダルのスタイル */
        #apply-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        #apply-modal-box {
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
            max-width: 500px;
            width: 90%;
        }

        #apply-modal-box h2 {
            margin-top: 0;
            font-size: 20px;
        }

        #apply-modal-box p {
            margin-bottom: 20px;
            font-size: 16px;
        }
    </style>
<!-- モーダル部分 -->
    <div id="modal-overlay">
        <div id="modal-box">
            <?php  if(isset($_POST["auth"]) && $_POST["auth"] == "true"){?>
                <h2>申請</h2>
            <?php }else{ ?>
                <h2>保存完了</h2>
            <?php } ?>

            <?php  if(isset($_POST["auth"]) && $_POST["auth"] == "true"){?>
                <p>会員情報の申請が完了しました。</p>
                <p>審査完了までお待ちください。</p>
            <?php }else{ ?>
                <p>アカウント情報を保存しました。</p>
            <?php } ?>

            <button id="close-btn" onclick="closeModal()">閉じる</button>
        </div>
    </div>

<!-- 申請モーダル部分 -->
    <div id="apply-modal-overlay">
        <div id="apply-modal-box">
            <h2>認証申請</h2>
            <p>この内容で認証申請を行いますか？</p>
            <p style="font-size: 14px; color: #666; margin: 10px 0;">
                申請後は内容の変更ができません。<br>
                内容に誤りがないか、必ずご確認ください。
            </p>
            
            <div style="display: flex; gap: 10px; justify-content: center;margin-top: 20px;">
                <button id="apply-confirm-btn" onclick="confirmApply()" style="padding: 10px 20px; background-color: #0a9071; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">申請する</button>
                <button id="apply-cancel-btn" onclick="closeApplyModal()" style="padding: 10px 20px; background-color: #ccc; color: black; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">キャンセル</button>
            </div>
        </div>
    </div>



<div class="user-top-area">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title">会員情報の編集</div>
    </div>

    <?php include(dirname(__FILE__)."/user-acount-menu.php"); ?>

    <form action="<?php echo getURLSetSlag("users/user-acount-edit");echo $get_url["add"];?>" method="post" style="max-width: 800px;margin-left: auto;margin-right: auto;" id="main-form">


        <?php if($userData["認証"] == ""){?>
            <div style="padding-top: 20px; padding-bottom: 20px;">
                <p>本フォームでご入力いただく内容は施術に際して重要な情報です。<span style="color: red;">誤りのないよう、必ずご入力ください。</span></p>
                <p>認証手続きが完了するまで、施術および物販商品のご購入はできません。</p>
                <p>入力内容に問題がなければ、申請ボタンを押して、運営に認証申請を行ってください。</p>
                <p>申請後に内容を変更する場合は「お問い合わせ」よりご連絡いただく必要があり、その際、施術等に遅れが生じる可能性があります。あらかじめ誤りがないか、必ずご確認ください。</p>
            </div>
        <?php }else if($userData["認証"] == "1"){ ?>
            <div style="padding-top: 20px; padding-bottom: 20px;">
                <p>現在、会員情報を申請しています。</p>
                <p>申請が完了したら、施術および物販商品のご購入が可能になりますので、もう少々お待ちください。</p>
            </div>
        <?php }else if($userData["認証"] == "2"){ ?>
            <div style="padding-top: 20px; padding-bottom: 20px;">
                <p>会員情報の認証が完了しました。</p>
                <p>情報のご変更が必要な場合はお問い合わせより、ご連絡ください。</p>
            </div>
        <?php }else if($userData["認証"] == "3"){ ?>
            <div style="padding-top: 20px; padding-bottom: 20px;">
                <p>会員情報の入力内容に誤りがあります。</p>
                <p>入力内容に問題がなければ、申請ボタンを押して、運営に認証申請を行ってください。</p>

                <?php if($user_meta->membersip_back_reason != ""){?>
                    <p>【修正内容】<br>
                        <?php echo nl2br($user_meta->membersip_back_reason); ?>
                    </p>
                <?php } ?>

            </div>
        <?php } ?>


        <input type="hidden" name="save_acount" value="">
        <input type="hidden" name="auth" id="auth_param" value="">

        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">名　前</div>
                        <div class="user-account-edit-wrap-sp-title">名　前</div>
                        <div class="user-account-edit-wrap-pc-required-area"><div class="user-account-edit-wrap-pc-required-label">必須</div></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content"><input class="user-account-edit-wrap-pc-text-one" type="text" name="input_last_name" value="<?php echo $userData["苗字"]; ?>" placeholder="姓" required <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "readonly"; ?>></div>
                        <div class="user-account-edit-wrap-pc-content"><input class="user-account-edit-wrap-pc-text-one" type="text" name="input_first_name" value="<?php echo $userData["名前"]; ?>" placeholder="名" required <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "readonly"; ?>></div>
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
                        <div class="user-account-edit-wrap-pc-content"><input class="user-account-edit-wrap-pc-text-one" type="text" name="input_last_name_kana" value="<?php echo $userData["ミョウジ"]; ?>" placeholder="セイ" required <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "readonly"; ?>></div>
                        <div class="user-account-edit-wrap-pc-content"><input class="user-account-edit-wrap-pc-text-one" type="text" name="input_first_name_kana" value="<?php echo $userData["ナマエ"]; ?>" placeholder="メイ" required <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "readonly"; ?>></div>
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
                    <div class="user-account-edit-wrap-pc-content">
                        <select name="input_user_sex" id="input_user_sex" class="user-account-edit-wrap-pc-select" <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "disabled"; ?>>
                            <option value="U" <?php if ($userData["性別値"] == "U") echo "selected"; ?>>性　別</option>
                            <option value="M" <?php if ($userData["性別値"] == "M") echo "selected"; ?>>男　性</option>
                            <option value="W" <?php if ($userData["性別値"] == "W") echo "selected"; ?>>女　性</option>
                        </select>
                        <?php if($userData["認証"] != "") { ?>
                            <input type="hidden" name="input_user_sex" value="<?php echo $userData["性別値"]; ?>">
                        <?php } ?>
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
                            <input type="number" min="1" name="input_user_born_year" class="user-account-edit-wrap-pc-text-one" value="<?php echo $userData["誕生日年"]; ?>" style="width: 50px;margin-right: 0;" required <?php if($userData["認証"] != "") echo "readonly"; ?>>年
                        </div>
                        <div class="user-account-edit-wrap-pc-content"  style="margin-right: 8px;">
                            <select name="input_user_born_month" id="input_user_born_month" class="user-account-edit-wrap-pc-select" required <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "disabled"; ?>>
                                <option value=""></option>
                                <?php for ($i = 1; $i <= 12; $i++) { ?>
                                    <option value="<?php echo $i; ?>" <?php if ($userData["誕生日月"] == $i) echo "selected"; ?>><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                            <?php if($userData["認証"] != "") { ?>
                                <input type="hidden" name="input_user_born_month" value="<?php echo $userData["誕生日月"]; ?>">
                            <?php } ?>月
                        </div>
                        <div class="user-account-edit-wrap-pc-content">
                        <select name="input_user_born_day" id="input_user_born_day" class="user-account-edit-wrap-pc-select" required <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "disabled"; ?>>
                                <option value=""></option>
                                <?php for ($i = 1; $i <= 31; $i++) { ?>
                                    <option value="<?php echo $i; ?>" <?php if ($userData["誕生日日"] == $i) echo "selected"; ?>><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                            <?php if($userData["認証"] != "") { ?>
                                <input type="hidden" name="input_user_born_day" value="<?php echo $userData["誕生日日"]; ?>">
                            <?php } ?>日
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
                            <input type="number" min="1" name="input_user_report_year" class="user-account-edit-wrap-pc-text-one" value="<?php echo $userData["届け出日年"]; ?>" style="width: 50px;margin-right: 0;" <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "readonly"; ?>>年
                        </div>
                        <div class="user-account-edit-wrap-pc-content"  style="margin-right: 8px;">
                            <select name="input_user_report_month" id="input_user_report_month" class="user-account-edit-wrap-pc-select" <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "disabled"; ?>>
                                <option value=""></option>
                                <?php for ($i = 1; $i <= 12; $i++) { ?>
                                    <option value="<?php echo $i; ?>" <?php if ($userData["届け出日月"] == $i) echo "selected"; ?>><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                            <?php if($userData["認証"] != "") { ?>
                                <input type="hidden" name="input_user_report_month" value="<?php echo $userData["届け出日月"]; ?>">
                            <?php } ?>月
                        </div>
                        <div class="user-account-edit-wrap-pc-content">
                        <select name="input_user_report_day" id="input_user_report_day" class="user-account-edit-wrap-pc-select" <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "disabled"; ?>>
                                <option value=""></option>
                                <?php for ($i = 1; $i <= 31; $i++) { ?>
                                    <option value="<?php echo $i; ?>" <?php if ($userData["届け出日日"] == $i) echo "selected"; ?>><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                            <?php if($userData["認証"] != "") { ?>
                                <input type="hidden" name="input_user_report_day" value="<?php echo $userData["届け出日日"]; ?>">
                            <?php } ?>日
                        </div>
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
                    
                    <div style="color:#CCCCCC;">変更する際は <a href="<?php echo getURLSetSlag("users/user-contact"); echo $get_url["add"]; ?>">お問い合わせ</a>までご連絡ください</div>
                </div>
            </div>
           
        </div>



        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">連絡先</div>
                        <div class="user-account-edit-wrap-sp-title">連絡先</div>
                        <div class="user-account-edit-wrap-pc-required-area"><div class="user-account-edit-wrap-pc-required-label">必須</div></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content">
                            <input class="user-account-edit-wrap-pc-text-one" type="text" name="input_tel_1" style="width: 60px;" value="<?php echo $userData["電話番号1"]; ?>" placeholder="例: 090" oninput="this.value = this.value.replace(/[^0-9]/g, '')"  required <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "readonly"; ?>>-
                        </div>
                        <div class="user-account-edit-wrap-pc-content">
                            <input class="user-account-edit-wrap-pc-text-one" type="text" name="input_tel_2" style="width: 60px;" value="<?php echo $userData["電話番号2"]; ?>" placeholder="例: 1234" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "readonly"; ?>>-
                        </div>
                        <div class="user-account-edit-wrap-pc-content">
                            <input class="user-account-edit-wrap-pc-text-one" type="text" name="input_tel_3" style="width: 60px;" value="<?php echo $userData["電話番号3"]; ?>" placeholder="例: 5678" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required <?php if($userData["認証"] != "") echo "readonly"; ?>>
                        </div>
                        <div class="user-account-edit-wrap-pc-content" style="font-size: 12px;">(半角数字のみ)</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">LINE ID</div>
                        <div class="user-account-edit-wrap-sp-title">LINE ID</div>
                        <div class="user-account-edit-wrap-pc-required-area"></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content" >
                            @<input class="user-account-edit-wrap-pc-text-one"  type="text" name="input_lind_id" id="input_lind_id" value="<?php echo $userData["LINEID"]; ?>"  placeholder="半角英数字と . - _ のみ" oninput="this.value = this.value.replace(/[^a-zA-Z0-9._-]/g, '')" <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "readonly"; ?>>
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
                        <div class="user-account-edit-wrap-pc-required-area"><div class="user-account-edit-wrap-pc-required-label">必須</div></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content">
                            <input class="user-account-edit-wrap-pc-text-one" type="text" name="input_post_no" id="zipcode"   value="<?php echo $userData["郵便番号"]; ?>" placeholder="半角数字のみ、「-」なし" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "readonly"; ?>>
                        </div>
                        <?php if($userData["認証"] == ""){ ?>
                            <div class="user-account-edit-wrap-pc-content">
                                <button type="button" onclick="getUserAddress()" class="user-account-edit-copy-btn" style="margin-left: 10px;">住所検索</button> 
                            </div>
                        <?php } ?>
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
                        <div class="user-account-edit-wrap-pc-required-area"><div class="user-account-edit-wrap-pc-required-label">必須</div></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content"style="width: 100%;">
                            <input class="user-account-edit-wrap-pc-text-one" type="text" name="input_address1" style="width: 100%;" id="address" value="<?php echo $userData["住所1"]; ?>" placeholder="都道府県 市区町村" required <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "readonly"; ?>>
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
                            <input class="user-account-edit-wrap-pc-text-one" type="text"  style="width: 100%;" name="input_address2" id="address2" value="<?php echo $userData["住所2"]; ?>" placeholder="番地・マンション名等" <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "readonly"; ?>>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if($userData["認証"] == ""){ ?>
            <div class="user-account-edit-wrap">
                <div class="user-account-edit-wrap-pc">
                    <div class="user-account-edit-wrap-pc-flex">
                        <div class="user-account-edit-wrap-pc-title-flex">
                            <div class="user-account-edit-wrap-pc-title">実家情報</div>
                            <div class="user-account-edit-wrap-sp-title">実家情報</div>
                            <div class="user-account-edit-wrap-pc-required-area"></div>
                        </div>
                        <div class="user-account-edit-wrap-sp-flex">
                            <div class="user-account-edit-wrap-pc-content">
                                居住住所と同じ場合はボタンを押す
                            </div>
                            <div class="user-account-edit-wrap-pc-content">
                                <button type="button" onclick="setUserParentsAddress()" class="user-account-edit-copy-btn" style="margin-left: 10px;">コピーする</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">実家／郵便番号</div>
                        <div class="user-account-edit-wrap-sp-title">実家／郵便番号</div>
                        <div class="user-account-edit-wrap-pc-required-area"><div class="user-account-edit-wrap-pc-required-label">必須</div></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content">
                            <input class="user-account-edit-wrap-pc-text-one" type="text" name="input_parents_post_no" id="parents_zipcode"   value="<?php echo $userData["実家郵便番号"]; ?>" placeholder="半角数字のみ、「-」なし" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "readonly"; ?>>
                        </div>
                        <?php if($userData["認証"] == ""){ ?>
                            <div class="user-account-edit-wrap-pc-content">
                                <button type="button" onclick="getUserParentsAddress()" class="user-account-edit-copy-btn" style="margin-left: 10px;">住所検索</button> 
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">実家／住所</div>
                        <div class="user-account-edit-wrap-sp-title">実家／住所</div>
                        <div class="user-account-edit-wrap-pc-required-area"><div class="user-account-edit-wrap-pc-required-label">必須</div></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content" style="width: 100%;">
                            <input class="user-account-edit-wrap-pc-text-one" type="text" name="input_parents_address1" style="width: 100%;" id="parents_address" value="<?php echo $userData["実家住所1"]; ?>" placeholder="都道府県 市区町村" required <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "readonly"; ?>>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">実家／建物名等</div>
                        <div class="user-account-edit-wrap-sp-title">実家／建物名等</div>
                        <div class="user-account-edit-wrap-pc-required-area"></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content" style="width: 100%;">
                            <input class="user-account-edit-wrap-pc-text-one" type="text" name="input_parents_address2" style="width: 100%;" id="parents_address2" value="<?php echo $userData["実家住所2"]; ?>" placeholder="番地・マンション名等" <?php if($userData["認証"] != "" && $userData["認証"] != "3") echo "readonly"; ?>>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php /*
        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">郵送先／郵便番号</div>
                        <div class="user-account-edit-wrap-sp-title">郵送先／郵便番号</div>
                        <div class="user-account-edit-wrap-pc-required-area"></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content">
                            <input class="user-account-edit-wrap-pc-text-one" type="text" name="input_post_no2" id="post_zipcode"   value="<?php echo $userData["郵送先郵便番号"]; ?>" placeholder="半角数字のみ、「-」なし" oninput="this.value = this.value.replace(/[^0-9]/g, '')" require <?php if($userData["認証"] != "") echo "readonly"; ?>>
                        </div>
                        <?php if($userData["認証"] == ""){ ?>
                            <div class="user-account-edit-wrap-pc-content">
                                <button type="button" onclick="getUserPostAddress()" class="user-account-edit-copy-btn" style="margin-left: 10px;">住所検索</button> 
                            </div>
                        <?php } ?>
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
                        <div class="user-account-edit-wrap-pc-required-area"></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content" style="width: 100%;">
                            <input class="user-account-edit-wrap-pc-text-one" type="text" name="input_post_address1" style="width: 100%;" id="post_address" value="<?php echo $userData["郵送先住所1"]; ?>" placeholder="都道府県 市区町村" require <?php if($userData["認証"] != "") echo "readonly"; ?>>
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
                            <input class="user-account-edit-wrap-pc-text-one" type="text" name="input_post_address2" style="width: 100%;" id="post_address2" value="<?php echo $userData["郵送先住所2"]; ?>" placeholder="番地・マンション名等" require <?php if($userData["認証"] != "") echo "readonly"; ?>>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        */ ?>
        <?php if($userData["認証"] == "" || $userData["認証"] == "3"){ ?>
            <div class="user-account-edit-form-btn-wrap">
                <button type="submit" id="form-save" class="user-account-edit-form-btn" onclick="return validateForm()">保　存 ＞</button>
            </div>
        <?php } ?>

    </form>

    <?php if($userData["認証"] == "" || $userData["認証"] == "3"){ ?>
        <form action="<?php echo getURLSetSlag("users/user-acount-edit");echo $get_url["add"];?>" method="post" style="max-width: 800px;margin-left: auto;margin-right: auto;" id="apply-form">
            <input type="hidden" name="save_acount" value="1">
            <input type="hidden" name="auth" value="true">

            <div class="user-account-edit-form-btn-wrap">
                <button type="button" id="form-apply" class="user-account-edit-form-btn" onclick="showApplyModal()" style="background-color: #0a9071;">申請する ＞</button>
            </div>

        </form>
    <?php } ?>

    <div class="user-account-edit-form-btn-wrap">
        <a href="<?php echo getURLSetSlag("users/user-acount"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">会員情報へ戻る  &gt;</a>
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

    function getUserParentsAddress() {
        var zipcode = document.getElementById('parents_zipcode').value;
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
                    document.getElementById('parents_address').value = address;
                } else {
                    alert("住所が見つかりませんでした");
                }
            },
            error: function() {
                alert("住所の取得に失敗しました");
            }
        });
    }

    function setUserParentsAddress() {
        document.getElementById('parents_zipcode').value = document.getElementById('zipcode').value;
        document.getElementById('parents_address').value = document.getElementById('address').value;
        document.getElementById('parents_address2').value = document.getElementById('address2').value;
    
       
    }

</script>
 <script>
        // ページが読み込まれたときにモーダルを表示するか確認
        window.addEventListener('load', function() {
            if (localStorage.getItem('showModal') === 'true') {
                showModal();
                localStorage.removeItem('showModal');
            }
        });

        // フォームバリデーション関数
        function validateForm() {
            // 必須項目の配列
            const requiredFields = [
                { name: 'input_last_name', label: '姓' },
                { name: 'input_first_name', label: '名' },
                { name: 'input_last_name_kana', label: 'セイ' },
                { name: 'input_first_name_kana', label: 'メイ' },
                { name: 'input_user_born_year', label: '誕生年' },
                { name: 'input_user_born_month', label: '誕生月' },
                { name: 'input_user_born_day', label: '誕生日' },
                { name: 'input_tel_1', label: '電話番号1' },
                { name: 'input_tel_2', label: '電話番号2' },
                { name: 'input_tel_3', label: '電話番号3' },
                { name: 'input_post_no', label: '郵便番号' },
                { name: 'input_address1', label: '住所' },
                { name: 'input_parents_post_no', label: '実家郵便番号' },
                { name: 'input_parents_address1', label: '実家住所' },
            ];

            let missingFields = [];

            // 各必須項目をチェック
            requiredFields.forEach(field => {
                const element = document.querySelector(`[name="${field.name}"]`);
                if (element && (!element.value || element.value.trim() === '')) {
                    missingFields.push(field.label);
                }
            });

            // 未入力項目がある場合
            if (missingFields.length > 0) {
                alert('以下の項目を入力してください：\n' + missingFields.join('\n'));
                return false;
            }

            // 電話番号の形式チェック
            const tel1 = document.querySelector('[name="input_tel_1"]').value;
            const tel2 = document.querySelector('[name="input_tel_2"]').value;
            const tel3 = document.querySelector('[name="input_tel_3"]').value;
            
            if (tel1.length < 2 || tel2.length < 3 || tel3.length < 4) {
                alert('電話番号を正しく入力してください');
                return false;
            }

            // 郵便番号の形式チェック
            const postNo = document.querySelector('[name="input_post_no"]').value;
            if (postNo.length !== 7) {
                alert('郵便番号は7桁で入力してください');
                return false;
            }

            // 実家郵便番号の形式チェック
            const parentsPostNo = document.querySelector('[name="input_parents_post_no"]').value;
            if (parentsPostNo.length !== 7) {
                alert('実家郵便番号は7桁で入力してください');
                return false;
            }

            // すべてのバリデーションを通過した場合
            localStorage.setItem('showModal', 'true');
            return true;
        }

        // 更新ボタンが押されたときに呼び出す関数
        function updateAccount() {
            localStorage.setItem('showModal', 'true');
            location.reload(); // ページをリロード
        }

        // モーダルを表示する関数
        function showModal() {
            document.getElementById('modal-overlay').style.display = 'block';
        }

        // モーダルを閉じる関数
        function closeModal() {
            document.getElementById('modal-overlay').style.display = 'none';
        }

        // 申請モーダルを表示する関数
        function showApplyModal() {
            if (!validateForm()) {
                return;
            }
            document.getElementById('apply-modal-overlay').style.display = 'block';
        }

        // 申請モーダルを閉じる関数
        function closeApplyModal() {
            document.getElementById('apply-modal-overlay').style.display = 'none';
        }

        // 申請を確定する関数
        function confirmApply() {
            // メインフォームの入力値を申請フォームにコピー
            copyFormDataToApplyForm();
            
            // モーダルを閉じる
            closeApplyModal();
            
            // 申請フォームを送信
            document.getElementById('apply-form').submit();
        }

        // メインフォームの入力値を申請フォームにコピーする関数
        function copyFormDataToApplyForm() {
            const mainForm = document.getElementById('main-form'); // メインフォーム
            const applyForm = document.getElementById('apply-form'); // 申請フォーム
            
            if (!mainForm || !applyForm) {
                console.error('フォームが見つかりません');
                return;
            }
            
            // メインフォームのすべての入力要素を取得
            const mainInputs = mainForm.querySelectorAll('input, select, textarea');
            console.log('メインフォームの入力要素数:', mainInputs.length);
            
            // 申請フォームに既存のinputをクリア（save_acount, auth以外）
            const existingInputs = applyForm.querySelectorAll('input[name]:not([name="save_acount"]):not([name="auth"])');
            existingInputs.forEach(input => input.remove());
            console.log('既存のinputを削除しました:', existingInputs.length);
            
            // メインフォームの入力値を申請フォームにコピー
            let copiedCount = 0;
            mainInputs.forEach(function(input) {
                if (input.name && input.name !== 'save_acount' && input.name !== 'auth') {
                    const newInput = document.createElement('input');
                    newInput.type = 'hidden';
                    newInput.name = input.name;
                    
                    // 入力タイプに応じて値を設定
                    if (input.type === 'checkbox') {
                        newInput.value = input.checked ? '1' : '0';
                    } else if (input.type === 'radio') {
                        if (input.checked) {
                            newInput.value = input.value;
                        } else {
                            return; // チェックされていないradioはスキップ
                        }
                    } else {
                        newInput.value = input.value;
                    }
                    
                    applyForm.appendChild(newInput);
                    copiedCount++;
                    console.log('コピー:', input.name, '=', newInput.value);
                }
            });
            
            console.log('メインフォームの入力値を申請フォームにコピーしました。コピー数:', copiedCount);
        }
    </script>