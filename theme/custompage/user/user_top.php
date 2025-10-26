<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritNewsClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");

    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);

    $newsClass = new SpiritNewsClass(); //ニュースデータ
    $userClass = new SpiritUserClass(); //ユーザー管理
    $spiritScheduleData = new SpiritScheduleClass(); //スケジュールデータ

   

    $userData = $userClass->getUserAcountData($user_id);//ユーザー情報

    $newData = $newsClass->getNewsPostDate($user_id);//ニュース情報


    $spiritData = $userClass->getUserSpritApplicant($user_id);//浄霊情報

    //var_dump($spiritData["all"]);
?>


<?php if($userData["認証"] ==  ""  || $userData["認証"] ==  "3"){?>

    <!-- モーダル -->
    <div id="accountInfoModal" class="modal-overlay" style="display: flex;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>アカウント情報の入力が必要です</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>

            <?php if($userData["認証"] ==  "3"){?>
                <div class="modal-body">
                    <p>会員情報の入力内容に誤りがあります。<br>会員情報入力にもどり、修正内容をご確認してください。</p>
                    <p>この情報は施術において大事な情報となる為、<font style="color: red;">必ずお間違いがないようにご入力</font>する必要があります。</p>
                    <p>申請が完了するまでは、施術および、物販のご購入はできません。</p>
                </div>
            <?php }else{?>
                <div class="modal-body">
                    <p>未入力のアカウント情報があります。<br>会員情報から、貴方の情報をご入力し、運営に申請してください。</p>
                    <p>この情報は施術において大事な情報となる為、<font style="color: red;">必ずお間違いがないようにご入力</font>する必要があります。</p>
                    <p>申請が完了するまでは、施術および、物販のご購入はできません。</p>
                </div>
            <?php }?>
            <div class="modal-footer">
                <a href="<?php echo getURLSetSlag("users/user-acount-edit");echo $get_url["add"]; ?>" class="modal-btn-primary">アカウント情報を入力する</a>
                <button class="modal-btn-secondary" onclick="closeModal()">後で入力する</button>
            </div>
        </div>
    </div>

    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            overflow: hidden;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            margin: 0;
            position: relative;
            flex-shrink: 0;
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid #e9ecef;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            color: #333;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-body p {
            margin: 0;
            font-size: 16px;
            line-height: 1.5;
            color: #555;
        }

        .modal-footer {
            padding: 20px 24px;
            border-top: 1px solid #e9ecef;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .modal-btn-primary {
            background: #A078D0;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .modal-btn-primary:hover {
            background: #8B5BB3;
            transform: translateY(-1px);
        }

        .modal-btn-secondary {
            background: #f8f9fa;
            color: #666;
            border: 1px solid #dee2e6;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-btn-secondary:hover {
            background: #e9ecef;
        }

        @media (max-width: 768px) {
            .modal-overlay {
                padding: 20px;
                align-items: center;
            }

            .modal-content {
                width: 100%;
                max-width: none;
                margin: 0;
                max-height: 80vh;
            }

            .modal-header,
            .modal-body,
            .modal-footer {
                padding: 16px;
            }

            .modal-footer {
                flex-direction: column;
            }

            .modal-btn-primary,
            .modal-btn-secondary {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .modal-overlay {
                padding: 10px;
                align-items: center;
            }

            .modal-content {
                max-height: 85vh;
            }

            .modal-header,
            .modal-body,
            .modal-footer {
                padding: 12px;
            }

            .modal-header h3 {
                font-size: 16px;
            }

            .modal-body p {
                font-size: 14px;
            }
        }
    </style>

    <script>
        function closeModal() {
            document.getElementById('accountInfoModal').style.display = 'none';
        }

        // モーダル外をクリックしたら閉じる
        document.getElementById('accountInfoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // ESCキーで閉じる
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>

<?php }?>

<div class="user-top-area">


    <?php  

        if(count($spiritData["all"]) > 0)
        {
    ?>
            <div class="user-jorei-section-title-wrap">
              <div class="user-jorei-section-title">ご依頼・ご購入の進捗ステータス</div>
            </div>


            <div class="user-navigation-container progress-navigation" data-section="progress">
                <!-- カテゴリタブ -->
                <div class="category-tabs">
                    <div class="tab-item active" data-category="all">
                        <span>全て表示</span>
                    </div>
                    <div class="tab-item" data-category="applications">
                        <span>各種お申し込み</span>
                    </div>
                    <div class="tab-item" data-category="sales">
                        <span>物販</span>
                    </div>
                </div>

                <!-- フィルタボタン -->
                <div class="filter-buttons">
                    <div class="filter-btn active" data-filter="all">
                        <span>全て表示</span>
                    </div>
                    <div class="filter-btn" data-filter="in-progress">
                        <span class="warning-icon">▲</span>
                        <span>進行中 ></span>
                    </div>
                    <div class="filter-btn" data-filter="confirmation">
                        <span class="warning-icon">▲</span>
                        <span>情報入力待ち ></span>
                    </div>
                    <div class="filter-btn" data-filter="unpaid">
                        <span class="warning-icon">▲</span>
                        <span>入金待ち ></span>
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

                        <script src="<?php echo get_template_directory_uri(); ?>/assets/js/user-top-filters.js?v=<?php echo time(); ?>"></script>
    <?php 

            $incomplete_count = 0;

            if(count($spiritData["incomplete"]) > 0)
            {
                 foreach ($spiritData["incomplete"] as $key => $value) {
                
                    //作成中は表示しない
                    if($value["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_CREATE){
                        continue;
                    }
                    //var_dump($value);
                    if($value["依頼者ID"] != $user_id)
                    {
                        continue;
                    }

                    $incomplete_count++;
                    
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



                            <?php 
                            
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


                                        <?php if($value["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_LAST_CONFIRMATION){ ?>

                                        <div>
                                            <form action="<?php echo getURLSetSlag("users/user-spirit-question-check"); echo $get_url["add"];?>" method="post">
                                                <button type="submit"  class="user-jorei-list-button-area-input">最終確認 ＞</button>
                                                <input type="hidden"  name="sheet_id"  value="<?php echo $value["ID"]; ?>">
                                            </form>
                                        </div>

                                        <?php }else{ ?>


                                            <div>
                                                <form action="<?php echo getURLSetSlag("users/user-spirit-question-edit"); echo $get_url["add"];?>" method="post">
                                                    <button type="submit"   class="user-jorei-list-button-area-input">入力シート編集 ＞</button>
                                                    <input type="hidden"  name="sheet_id" value="<?php echo $value["ID"]; ?>">
                                                </form>
                                            </div>

                                        <?php } ?>

                                    <?php } ?>

                                    <?php if($userClass->checkConfirmationQuestionSheet( $value["会員ステータス"] , $value["実行日"] )){ //シート確認可能?>



                                        <?php if($value["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_LAST_CONFIRMATION){ ?>

                                            <div>
                                                <form action="<?php echo getURLSetSlag("users/user-spirit-question-check"); echo $get_url["add"];?>" method="post">
                                                    <button type="submit"  class="user-jorei-list-button-area-input">最終確認 ＞</button>
                                                    <input type="hidden"  name="sheet_id"  value="<?php echo $value["ID"]; ?>">
                                                </form>
                                            </div>

                                        <?php }else{ ?>

                                            <div>
                                                <form action="<?php echo getURLSetSlag("users/user-spirit-question-edit"); echo $get_url["add"];?>" method="post">
                                                    <button type="submit"  class="user-jorei-list-button-area-input">入力シート確認 ＞</button>
                                                    <input type="hidden"  name="sheet_id"  value="<?php echo $value["ID"]; ?>">
                                                </form>
                                            </div>
                                        <?php } ?>

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

                            </div>

                         </div>

                        
                    </div>

    <?php
                 }

     ?>

                 <?php if($incomplete_count == 0){?>
                    <div class="no-history-message">
                       <div class="no-history-icon">📋</div>
                       <div class="no-history-text">履歴はありません</div>
                       <div class="no-history-subtext">現在、進行中の履歴はございません</div>
                   </div>
                    
                 <?php }?>

     <?php 
            }
            else {
     ?>
                   <div style="text-align: center;font-size: 24px;color: black;margin-top: 50px;margin-bottom: 50px;">履歴はありません。</div>
     <?php 
            }
    ?>


            <div class="user-jorei-section-title-wrap" style="margin-top: 150px;">
              <div class="user-jorei-section-title">浄霊履歴／完了・キャンセル</div>
            </div>

            <div class="user-navigation-container complete-navigation" data-section="complete">

                <!-- フィルタボタン -->
                <div class="filter-buttons">
                    <div class="filter-btn active" data-filter="all">
                        <span>全て表示</span>
                    </div>
                    <div class="filter-btn" data-filter="in-progress">
                        <span>完了済み ></span>
                    </div>
                    <div class="filter-btn" data-filter="confirmation">
                        <span>キャンセル ></span>
                    </div>
                    
                </div>
            </div>


     <?php  
            if(count($spiritData["complete"]) > 0)
            {

                $disp_count = 0;

                $max_display_count = 5; // 最大表示件数
                $displayed_count = 0; // 実際に表示した件数

                foreach ($spiritData["complete"] as $key => $value) {

                    if($value["依頼者ID"] != $user_id)
                    {
                        continue;
                    }

                    // 最大表示件数に達したらループを終了
                    if($displayed_count >= $max_display_count) {
                        break;
                    }

                    //var_dump($value);

                    $displayed_count++;

    ?>

                    <div class="user-jorei-list-item"  data-section="complete">
                        <div class="user-jorei-list-box">

                            <div class="user-jorei-list-title">
                                <div class="user-jorei-list-title-category"><?php echo $value["依頼タイプ名"];?></div>
                                <div class="user-jorei-list-title-name"><?php echo $value["依頼名前"];?></div>
                            </div>

                            <div class="user-jorei-list-title-date-sp">ご依頼日:<?php echo $value["依頼日年月日"];?></div>

                            
                            <?php 
                            
                                //キャンセル
                                if($value["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_CANCEL){ 
                            ?>
                                <div class="user-jorei-list-status" style="background-color: #c1b8b8;"><?php echo $value["会員ステータス表示"]; //dispMemberStatus( $status  ) ?></div>
                                <div class="user-jorei-list-button-area" style="background-color: #fffefe;">
                                    <?php echo $value["キャンセル日年月日"];?>
                                </div>

                            <?php  
                                }else{ //完了
                            
                                
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

                                <div class="user-jorei-list-data-box-item">
                                    <div class="user-jorei-list-data-box-item-title">実行日</div>
                                    <div class="user-jorei-list-data-box-item-value">
                                    <?php  
                                        echo $value["実行日年月日"];
                                    ?>
                                    </div>
                                </div>

                                <div class="user-jorei-list-status" style="background-color: #e917b2;"><?php echo $value["会員ステータス表示"]; //dispMemberStatus( $status  ) ?></div>
                                <div class="user-jorei-list-button-area" style="<?php echo $title_box_style; ?>">

                                    <div>
                                        <form action="<?php echo getURLSetSlag("users/user-spirit-detail"); echo $get_url["add"];?>" method="post">
                                            <button type="submit"  class="user-jorei-list-button-area-detail">詳細 ＞</button>
                                            <input type="hidden"  name="sheet_id"  value="<?php echo $value["ID"]; ?>">
                                        </form>
                                    </div>
                                    <div>
                                        <form action="<?php echo getURLSetSlag("users/user-spirit-question-edit"); echo $get_url["add"];?>" method="post">
                                            <button type="submit"  class="user-jorei-list-button-area-input">入力シート確認 ＞</button>
                                            <input type="hidden"  name="sheet_id"  value="<?php echo $value["ID"]; ?>">
                                        </form>
                                    </div>



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
                            <?php
                                }
                            ?>

                            

                        </div>

                        
                    </div>

    <?php

                 }

                 // 表示件数が最大件数に達した場合のメッセージ
                 if($displayed_count >= $max_display_count && count($spiritData["complete"]) > $max_display_count) {
                     echo '<div style="text-align: center; padding: 20px; color: #666; font-size: 14px;">※ 最新の5件のみ表示しています。全ての履歴は下記のリンクからご確認ください。</div>';
                 }

     ?>

                <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
                    <a href="<?php echo getURLSetSlag("users/user-in-progress-spirit-list"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">浄霊履歴 ／ 完了一覧 &gt;</a>
                </div>

     <?php 
            }
            else {
     ?>
                   <div class="no-history-message">
                       <div class="no-history-icon">📋</div>
                       <div class="no-history-text">履歴はありません</div>
                       <div class="no-history-subtext">現在、完了・キャンセルの履歴はございません</div>
                   </div>
     <?php 
            }
    ?>



    <?php 
        }else{
     ?>
         <div class="no-history-message">
             <div class="no-history-icon">📋</div>
             <div class="no-history-text">履歴はありません</div>
             <div class="no-history-subtext">現在、ご依頼・ご購入の履歴はございません</div>
         </div>
     <?php 
        }
     ?>

</div>

<div class="user-news-area">

    <div class="user-news-area-content">

    <div class="user-news-title-center">NEWS</div>


    <?php if(count($newData["all"]) > 0){?>

        <?php  

            //お知らせ
            $news_disp_count = 0;

            foreach ($newData["all"] as $key => $value) {

                 foreach ($value as $news_key => $news_value) {


                 if(SpiritNewsClass::TOP_NEWS_NUM <= $news_disp_count)
                 {
                     break;
                 }

                 $news_disp_count++;

                 //表示日を取得
                 $disp_date = $newsClass->getPostDate($news_value);

                 //8日以内ならnewを表示
                 $news_post_data = $newsClass->getNewsPostData($news_value);


                 
         ?>

            <div class="user-news-list-item">
                <div class="user-news-list-row">
                    <div class="user-news-list-row2">
                        <?php if($news_post_data["is_individual"]){ ?>
                            <span class="user-news-badge-individual">個人宛</span>
                        <?php } ?>

                        <span class="user-news-date"><?php echo $news_post_data["disp_date_year_month_day"]; ?></span>
                    </div>
                    <div class="user-news-list-row3">
                        <?php if($news_post_data["is_new"]){ ?>
                            <span class="user-news-badge-new">NEW</span>
                        <?php } ?>
                        <span class="user-news-title-text"><a href="<?php echo get_the_permalink($news_value); ?>"><?php echo get_the_title($news_value); ?></a></span>
                    </div>
                </div>
            </div>

         <?php 
                 } 
            }
         ?>

        <a class="user-news-more-link" href="<?php echo home_url(); ?>/users/user-notice<?php echo $get_url["add"]; ?>">もっとみる　&gt;</a>





    <?php }else{ //ニュースデータ無し ?>

         <div class="no-history-message">
             <div class="no-history-icon">📰</div>
             <div class="no-history-text">お知らせはありません</div>
             <div class="no-history-subtext">現在、お知らせはございません</div>
         </div>

    <?php } ?>

    </div>
</div>
