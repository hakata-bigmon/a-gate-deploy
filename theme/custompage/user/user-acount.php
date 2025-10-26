<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");


    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);

    $userClass = new SpiritUserClass(); //ユーザー管理


    $userData = $userClass->getUserAcountData($user_id);
    
?>

<div class="user-top-area">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title">会員情報</div>
    </div>

    <?php include(dirname(__FILE__)."/user-acount-menu.php"); ?>
   

    <div class="user-account-info-area">


        <div class="user-account-info-wrap">
            <div class="user-account-info-wrap-pc">
                <div class="user-account-info-wrap-pc-flex">
                    <div class="user-account-info-wrap-pc-title">ID</div>
                    <div class="user-account-info-wrap-pc-content"><?php echo $userData["ユニークID"];?></div>
                </div>
            </div>
            <div class="user-account-info-wrap-sp">
                <div class="user-account-info-wrap-sp-flex">
                    <div class="user-account-info-wrap-sp-title">ID</div>
                    <div class="user-account-info-wrap-sp-content"><?php echo $userData["ユニークID"];?></div>
                </div>
            </div>
        </div>

        <div class="user-account-info-wrap">
            <div class="user-account-info-wrap-pc">
                <div class="user-account-info-wrap-pc-flex">
                    <div class="user-account-info-wrap-pc-title">名　前</div>
                    <div class="user-account-info-wrap-pc-content"><?php echo $userData["フル名前"];?></div>
                </div>
            </div>
            <div class="user-account-info-wrap-sp">
                <div class="user-account-info-wrap-sp-flex">
                    <div class="user-account-info-wrap-sp-title">名　前</div>
                    <div class="user-account-info-wrap-sp-content"><?php echo $userData["フル名前"];?></div>
                </div>
            </div>
        </div>

        <div class="user-account-info-wrap">
            <div class="user-account-info-wrap-pc">
                <div class="user-account-info-wrap-pc-flex">
                    <div class="user-account-info-wrap-pc-title">ナマエ</div>
                    <div class="user-account-info-wrap-pc-content"><?php echo $userData["フルナマエ"];?></div>
                </div>
            </div>
            <div class="user-account-info-wrap-sp">
                <div class="user-account-info-wrap-sp-flex">
                    <div class="user-account-info-wrap-sp-title">ナマエ</div>
                    <div class="user-account-info-wrap-sp-content"><?php echo $userData["フルナマエ"];?></div>
                </div>
            </div>
        </div>

        <div class="user-account-info-wrap">
            <div class="user-account-info-wrap-pc">
                <div class="user-account-info-wrap-pc-flex">
                    <div class="user-account-info-wrap-pc-title">性　別</div>
                    <div class="user-account-info-wrap-pc-content"><?php echo $userData["性別"];?></div>
                </div>
            </div>
            <div class="user-account-info-wrap-sp">
                <div class="user-account-info-wrap-sp-flex">
                    <div class="user-account-info-wrap-sp-title">性　別</div>
                    <div class="user-account-info-wrap-sp-content"><?php echo $userData["性別"];?></div>
                </div>
            </div>
        </div>


        <div class="user-account-info-wrap">
            <div class="user-account-info-wrap-pc">
                <div class="user-account-info-wrap-pc-flex">
                    <div class="user-account-info-wrap-pc-title">生年月日</div>
                    <div class="user-account-info-wrap-pc-content"><?php echo $userData["誕生日年月日"];?></div>
                </div>
            </div>
            <div class="user-account-info-wrap-sp">
                <div class="user-account-info-wrap-sp-flex">
                    <div class="user-account-info-wrap-sp-title">生年月日</div>
                    <div class="user-account-info-wrap-sp-content"><?php echo $userData["誕生日年月日"];?></div>
                </div>
            </div>
        </div>

        <div class="user-account-info-wrap">
            <div class="user-account-info-wrap-pc">
                <div class="user-account-info-wrap-pc-flex">
                    <div class="user-account-info-wrap-pc-title">届け出日</div>
                    <div class="user-account-info-wrap-pc-content"><?php echo $userData["届け出日年月日"];?></div>
                </div>
            </div>
            <div class="user-account-info-wrap-sp">
                <div class="user-account-info-wrap-sp-flex">
                    <div class="user-account-info-wrap-sp-title">届け出日</div>
                    <div class="user-account-info-wrap-sp-content"><?php echo $userData["届け出日年月日"];?></div>
                </div>
            </div>
        </div>


        <div class="user-account-info-wrap">
            <div class="user-account-info-wrap-pc">
                <div class="user-account-info-wrap-pc-flex">
                    <div class="user-account-info-wrap-pc-title">メールアドレス</div>
                    <div class="user-account-info-wrap-pc-content"><?php echo $userData["メール"];?></div>
                </div>
            </div>
            <div class="user-account-info-wrap-sp">
                <div class="user-account-info-wrap-sp-flex">
                    <div class="user-account-info-wrap-sp-title">メールアドレス</div>
                    <div class="user-account-info-wrap-sp-content"><?php echo $userData["メール"];?></div>
                </div>
            </div>
        </div>


        <div class="user-account-info-wrap">
            <div class="user-account-info-wrap-pc">
                <div class="user-account-info-wrap-pc-flex">
                    <div class="user-account-info-wrap-pc-title">連絡先</div>
                    <div class="user-account-info-wrap-pc-content"><?php echo $userData["電話番号"];?></div>
                </div>
            </div>
            <div class="user-account-info-wrap-sp">
                <div class="user-account-info-wrap-sp-flex">
                    <div class="user-account-info-wrap-sp-title">連絡先</div>
                    <div class="user-account-info-wrap-sp-content"><?php echo $userData["電話番号"];?></div>
                </div>
            </div>
        </div>

        <div class="user-account-info-wrap">
            <div class="user-account-info-wrap-pc">
                <div class="user-account-info-wrap-pc-flex">
                    <div class="user-account-info-wrap-pc-title">LINE ID</div>
                    <div class="user-account-info-wrap-pc-content"><?php echo $userData["LINEID"];?></div>
                </div>
            </div>
            <div class="user-account-info-wrap-sp">
                <div class="user-account-info-wrap-sp-flex">
                    <div class="user-account-info-wrap-sp-title">LINE ID</div>
                    <div class="user-account-info-wrap-sp-content"><?php echo $userData["LINEID"];?></div>
                </div>
            </div>
        </div>

        <div class="user-account-info-wrap">
            <div class="user-account-info-wrap-pc">
                <div class="user-account-info-wrap-pc-flex">
                    <div class="user-account-info-wrap-pc-title">郵便番号</div>
                    <div class="user-account-info-wrap-pc-content"><?php echo $userData["郵便番号ハイフン"];?></div>
                </div>
            </div>
            <div class="user-account-info-wrap-sp">
                <div class="user-account-info-wrap-sp-flex">
                    <div class="user-account-info-wrap-sp-title">郵便番号</div>
                    <div class="user-account-info-wrap-sp-content"><?php echo $userData["郵便番号ハイフン"];?></div>
                </div>
            </div>
        </div>

        <div class="user-account-info-wrap">
            <div class="user-account-info-wrap-pc">
                <div class="user-account-info-wrap-pc-flex">
                    <div class="user-account-info-wrap-pc-title">住　所</div>
                    <div class="user-account-info-wrap-pc-content"><?php echo $userData["住所"];?></div>
                </div>
            </div>
            <div class="user-account-info-wrap-sp">
                <div class="user-account-info-wrap-sp-flex">
                    <div class="user-account-info-wrap-sp-title">住　所</div>
                    <div class="user-account-info-wrap-sp-content"><?php echo $userData["住所"];?></div>
                </div>
            </div>
        </div>

        <div class="user-account-info-wrap">
            <div class="user-account-info-wrap-pc">
                <div class="user-account-info-wrap-pc-flex">
                    <div class="user-account-info-wrap-pc-title">実家/郵便番号</div>
                    <div class="user-account-info-wrap-pc-content"><?php echo $userData["実家郵便番号ハイフン"];?></div>
                </div>
            </div>
            <div class="user-account-info-wrap-sp">
                <div class="user-account-info-wrap-sp-flex">
                    <div class="user-account-info-wrap-sp-title">実家/郵便番号</div>
                    <div class="user-account-info-wrap-sp-content"><?php echo $userData["実家郵便番号ハイフン"];?></div>
                </div>
            </div>
        </div>

        <div class="user-account-info-wrap">
            <div class="user-account-info-wrap-pc">
                <div class="user-account-info-wrap-pc-flex">
                    <div class="user-account-info-wrap-pc-title">実家/住所</div>
                    <div class="user-account-info-wrap-pc-content"><?php echo $userData["実家住所"];?></div>
                </div>
            </div>
            <div class="user-account-info-wrap-sp">
                <div class="user-account-info-wrap-sp-flex">
                    <div class="user-account-info-wrap-sp-title">実家/住所</div>
                    <div class="user-account-info-wrap-sp-content"><?php echo $userData["実家住所"];?></div>
                </div>
            </div>
        </div>

        <div class="user-account-info-wrap">
            <div class="user-account-info-wrap-pc">
                <div class="user-account-info-wrap-pc-flex">
                    <div class="user-account-info-wrap-pc-title">認証</div>
                    <div class="user-account-info-wrap-pc-content">
                        <?php if($userData["認証"] == ""){?>
                            <span style="color: red;">未承認</span>
                        <?php }else if($userData["認証"] == "1"){?>
                            <span style="color: blue;">申請中</span>
                        <?php }else if($userData["認証"] == "2"){?>
                            <span style="color: green;">認証済</span>
                        <?php }else if($userData["認証"] == "3"){?>
                            <span style="color: red;">入力修正依頼</span>
                        <?php }?>
                    </div>
                </div>
            </div>
            <div class="user-account-info-wrap-sp">
                <div class="user-account-info-wrap-sp-flex">
                    <div class="user-account-info-wrap-sp-title">認証</div>
                    <div class="user-account-info-wrap-sp-content">
                        <?php if($userData["認証"] == ""){?>
                            <span style="color: red;">未承認</span>
                        <?php }else if($userData["認証"] == "1"){?>
                            <span style="color: blue;">申請中</span>
                        <?php }else if($userData["認証"] == "2"){?>
                                <span style="color: green;">認証済</span>
                        <?php }else if($userData["認証"] == "3"){?>
                            <span style="color: red;">入力修正依頼</span>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>

        <div class="user-account-info-bottom-area">
            <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-menu-btn">TOPに戻る &gt;</a>
        </div>

     </div>

</div>
