<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");

    //浄霊タイプ
    $spiritTypeClass = new SpiritTypeClass();

    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);

    $userClass = new SpiritUserClass(); //ユーザー管理
   

    $userData = $userClass->getUserAcountData($user_id);//ユーザー情報
    $spiritData = $userClass->getUserSpritApplicant($user_id);//浄霊情報

    $spiritType = $spiritTypeClass->getSpiritType(true);

    $complete_array = $userClass->getUserSpritApplicantComplete($spiritData);

    //var_dump($complete_array);

    //var_dump($spiritType);
?>

<div class="user-top-area">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title">浄霊・鑑定など各種お申し込み</div>
    </div>

    <div class="user-spirit-list-menu-list-wrap">

        <div class="user-spirit-list-menu-list-btns">
          <a href="<?php echo getURLSetSlag("users/user-spirit-list-menu") . $get_url["add"]; ?>" class="user-spirit-list-menu-list-btn active">
            <span class="user-spirit-list-menu-list-btn-icon material-icons">list</span>
          </a>
          <a href="<?php echo getURLSetSlag("users/user-schedule-data") . $get_url["add"]; if($get_url["add"] == ""){echo "?type=" .SpiritTypeClass::SPIRIT_TYPE_NAME_DAY;}else{echo "&type=" .SpiritTypeClass::SPIRIT_TYPE_NAME_DAY;}?>" class="user-spirit-list-menu-list-btn">
            <span class="user-spirit-list-menu-list-btn-icon material-icons">calendar_today</span>
          </a>
        </div>

    </div>
   
    <div class="user-spirit-list-menu-wrap-area">
        <div class="user-spirit-list-menu-wrap">

            <a href="<?php echo getURLSetSlag("users/user-spirit-list-menu") . $get_url["add"]; ?>">
                <div class="user-spirit-list-menu-item" <?php if(!isset($_GET["type"])){echo "style='background-color: #800A90;color: white;'";}?> >
                    <div class="user-spirit-list-menu-item-title">全て表示</div>
                </div>
            </a>

            <a href="<?php echo getURLSetSlag("users/user-spirit-list-menu") . $get_url["add"]; if($get_url["add"] == ""){echo "?type=" .SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT;}else{echo "&type=" .SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT;}?>">
                <div class="user-spirit-list-menu-item" <?php if(isset($_GET["type"]) && $_GET["type"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT){echo "style='background-color: #800A90;color: white;'";}?> >
                    <div class="user-spirit-list-menu-item-title">リモート依頼</div>
                </div>
            </a>

            <a href="<?php echo getURLSetSlag("users/user-spirit-list-menu") . $get_url["add"]; if($get_url["add"] == ""){echo "?type=" .SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL;}else{echo "&type=" .SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL;}?>">
                <div class="user-spirit-list-menu-item" <?php if(isset($_GET["type"]) && $_GET["type"] == SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL){echo "style='background-color: #800A90;color: white;'";}?> >
                    <div class="user-spirit-list-menu-item-title">鑑定</div>
                </div>
            </a>

            <a href="<?php echo getURLSetSlag("users/user-schedule-data") . $get_url["add"]; if($get_url["add"] == ""){echo "?type=" .SpiritTypeClass::SPIRIT_TYPE_NAME_DAY;}else{echo "&type=" .SpiritTypeClass::SPIRIT_TYPE_NAME_DAY;}?>">
                <div class="user-spirit-list-menu-item" <?php if(isset($_GET["type"]) && $_GET["type"] == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY){echo "style='background-color: #800A90;color: white;'";}?>  <?php if(isset($_GET["type"]) && $_GET["type"] == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY){echo "style='background-color: #800A90;color: white;'";}?> >
                    <div class="user-spirit-list-menu-item-title">日程確定依頼</div>
                </div>
            </a>

            <a href="<?php echo getURLSetSlag("users/user-schedule-data") . $get_url["add"]; if($get_url["add"] == ""){echo "?type=" .SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN;}else{echo "&type=" .SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN;}?>">
                <div class="user-spirit-list-menu-item" <?php if(isset($_GET["type"]) && $_GET["type"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){echo "style='background-color: #800A90;color: white;'";}?> >
                    <div class="user-spirit-list-menu-item-title">遠隔・相談</div>
                </div>
            </a>

        </div>
    </div>


    <?php if($userData["認証"] ==  "2"){?>
        <div class="user-spirit-list-menu-contens-wrap">


            <?php if(!isset($_GET["type"])){?>


                <?php foreach($spiritType as $key => $value){?>

                    <?php foreach($value as $type_key => $type_value){?>

                        <?php
                            //物販は表示しない
                            if($type_value["group"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){
                                continue;
                            }

                            //日程確定依頼と相談も表示しない
                            if($type_value["group"] == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY || $type_value["group"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){
                                continue;
                            }

                            //解放条件を調べる
                            if(!$spiritTypeClass->getSpiritTypeReleaseCondition($type_value["ID"],$spiritData))
                            {
                                continue;
                            }
                        ?>
                        <a href="<?php echo getURLSetSlag("users/user-sales-credit-page") . $get_url["add"]; if($get_url["add"] == ""){echo "?type=" .$type_value["ID"];}else{echo "&type=" .$type_value["ID"];}?>">
                            <div class="user-spirit-list-menu-contens-item">
                                <div class="user-spirit-list-menu-contens-item-category"><?php echo $spiritTypeClass->getSpiritTypeName($type_value["group"]); ?></div>
                                <div class="user-spirit-list-menu-contens-item-title"><?php echo $type_value["title"]; ?></div>
                        
                            </div>
                        </a>
                    <?php }?>

                <?php }?>

            <?php }else{ ?>

                <?php foreach($spiritType[$_GET["type"]] as $type_key => $type_value){?>

                    <?php
                        //物販は表示しない
                        if($type_value["group"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){
                            continue;
                        }

                        //解放条件を調べる
                        if(!$spiritTypeClass->getSpiritTypeReleaseCondition($type_value["ID"],$complete_array))
                        {
                            continue;
                        }
                    ?>
                    <a href="<?php echo getURLSetSlag("users/user-sales-credit-page") . $get_url["add"]; if($get_url["add"] == ""){echo "?type=" .$type_value["ID"];}else{echo "&type=" .$type_value["ID"];}?>">
                        <div class="user-spirit-list-menu-contens-item">
                            <div class="user-spirit-list-menu-contens-item-category"><?php echo $spiritTypeClass->getSpiritTypeName($type_value["group"]); ?></div>
                            <div class="user-spirit-list-menu-contens-item-title"><?php echo $type_value["title"]; ?></div>
                        </div>
                    </a>
                <?php }?>

            <?php }?>




        </div>
    <?php }else{?>
        <div class="user-spirit-list-menu-contens-wrap">
            <div class="user-spirit-list-menu-contens-item">
                <div class="user-spirit-list-menu-contens-item-title">
                    <a href="<?php echo getURLSetSlag("users/user-acount-edit");echo $get_url["add"]; ?>">会員情報認証</a>が完了するまではお申し込みできません。</div>
            </div>
        </div>
    <?php }?>
    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
    </div>
</div>
