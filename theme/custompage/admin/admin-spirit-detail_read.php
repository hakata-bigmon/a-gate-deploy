<?php

    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritQuestionDispClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritInputCustomizeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");
    require_once (dirname(__FILE__)."/../../class/mailTextClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritNewsClass.php");

    require_once ("a-gate-functions.php");

    // 流入データ
    $spirit_sheet_data = new spiritSheetClass(); //管理データ

    //ユーザークラス
    $userClass = new SpiritUserClass(); //ユーザー管理
    //表示データ
    $disp_questiont_class = new SpiritQuestionDispClass(); //表示データ
    //個人データ
    $spirit_customize_data = new SpiritInputCustomizeClass(); //文字データ

    $mailText = new MailTextClass();
    $newsClass = new SpiritNewsClass();

    

    $profile_last_up_date = get_user_meta($check_user_id,'last_up_date',true);
    $profile_last_up_date_id = get_user_meta($check_user_id,'last_up_date_id',true);
    $profile_last_up_date_name = "";

    if($profile_last_up_date_id != ""){
        $profile_last_up_date_user = get_userdata($profile_last_up_date_id);
        $profile_last_up_date_name = $profile_last_up_date_user->last_name . " " . $profile_last_up_date_user->first_name;
    }
    else{
        $profile_last_up_date_name = "";
    }



    $img_post_acf_id = $_GET["sheet_name"]; //画像取得用シートID



    //ステータス保存
    if(isset($_POST['change_purespirit_user_status'])){
        update_field("acf_purespirit_user_status", $_POST['acf_purespirit_user_status'], $_GET['sheet_name']);
        //変更者と変更日保存（タイムゾーンは東京）
        date_default_timezone_set('Asia/Tokyo');
        $today = date('Y-m-d H:i:s');
        update_field("acf_purespirit_user_status_change_user", get_current_user_id(), $_GET['sheet_name']);
        update_field("acf_purespirit_user_status_change_date", date('Y-m-d H:i:s'), $_GET['sheet_name']);
    }

    
    // 施術結果画像更新
    if(isset($_POST['upload_result'])){

        $updata_img = json_decode(get_field("acf_purespirit_result_img_id", $_GET['sheet_name']));

        // 初回登録
        if(is_null($updata_img)){
            $updata_img[] = $_POST['acf_e-mark_image_field'];
            update_field("acf_purespirit_result_img_id", json_encode($updata_img), $_GET['sheet_name']);

        // 2コメ以降
        }else{

            // 重複チェック
            if(!in_array( $_POST['acf_e-mark_image_field'],$updata_img,true)){
        
                $updata_img[] = $_POST['acf_e-mark_image_field'];
        
                update_field("acf_purespirit_result_img_id", json_encode($updata_img), $_GET['sheet_name']);
            }
        }
    }

    // 施術結果画像削除
    if(isset($_POST['result_change'])){
        $updata_img = json_decode(get_field("acf_purespirit_result_img_id", $_GET['sheet_name']));  //現在の保存画像

        if(is_array($updata_img)){
            // 削除する画像以外で配列作成
            $updata_img = array_filter($updata_img, function($value) {
                return $value != $_POST['result_change'];
            });
            
            $updata_img = array_values($updata_img);        //  連想配列を配列に戻す

            if(count($updata_img) == 0){
                update_field("acf_purespirit_result_img_id", "", $_GET['sheet_name']);
            }else{
                update_field("acf_purespirit_result_img_id", json_encode($updata_img), $_GET['sheet_name']);
            }
        }
    }

    // 施術結果画像更新
    if(isset($_POST['upload_submit_result'])){

        $updata_img = json_decode(get_field("acf_purespirit_submission_img_id", $_GET['sheet_name']));

        // 初回登録
        if(is_null($updata_img)){
            $updata_img[] = $_POST['acf_treatment_result_image_field'];
            update_field("acf_purespirit_submission_img_id", json_encode($updata_img), $_GET['sheet_name']);

        // 2コメ以降
        }else{

            // 重複チェック
            if(!in_array( $_POST['acf_treatment_result_image_field'],$updata_img,true)){
        
                $updata_img[] = $_POST['acf_treatment_result_image_field'];
        
                update_field("acf_purespirit_submission_img_id", json_encode($updata_img), $_GET['sheet_name']);
            }
        }
    }

    // 施術結果画像削除
    if(isset($_POST['upload_submit_change'])){
        $updata_img = json_decode(get_field("acf_purespirit_submission_img_id", $_GET['sheet_name']));  //現在の保存画像

        if(is_array($updata_img)){
            // 削除する画像以外で配列作成
            $updata_img = array_filter($updata_img, function($value) {
                return $value != $_POST['upload_submit_change'];
            });
            
            $updata_img = array_values($updata_img);        //  連想配列を配列に戻す

            if(count($updata_img) == 0){
                update_field("acf_purespirit_submission_img_id", "", $_GET['sheet_name']);
            }else{
                update_field("acf_purespirit_submission_img_id", json_encode($updata_img), $_GET['sheet_name']);
            }
        }
    }



    //リモート浄霊の更新
    if( isset($_POST["set_remote"]))
    {
        $remote_temporary_sheet_id = $_POST["remote_sheet_id"];//仮sheet_id
        $remote_sheet_id =  get_field('acf_purespirit_remote_sprit_num' , $_GET["sheet_name"]); //sheetID

        //申込者と枠数は入っている
        $slots = get_field('acf_remote_sprit_sheet_target_slots' ,$remote_sheet_id);
        
        //対象者が１つでも入っていたら更新しない
        $target_count = 0;

        for($i=1;$i<=$slots;$i++){

            if(get_field('acf_remote_sprit_sheet_id_' . $i ,$remote_sheet_id) != "")
            {
               $target_count++;
            }
        }

        if($target_count == 0 )
        {

             for($i=1;$i<=$slots;$i++){

                //IDがある場合はIDを入れる
                if(get_field('acf_remote_sprit_sheet_target_number_' . $i ,$remote_temporary_sheet_id) != ""){

                    $set_id = get_field('acf_remote_sprit_sheet_target_number_' . $i ,$remote_temporary_sheet_id);

                    //echo $set_id ."<br>";
                    //番号を入れる
                    $spiritSheet->saveRemoteSpritTargetData( "acf_remote_sprit_sheet_target_id" ,  $remote_sheet_id , $i , get_field('acf_remote_sprit_sheet_target_number_' . $i ,$remote_temporary_sheet_id));
                }
                else{
                    //誕生日を分解
                    $birthday = get_field('acf_remote_sprit_sheet_target_birthday_' . $i ,$remote_temporary_sheet_id);
                    $birthday_array = explode('-', $birthday);

                    //新規作成する
                    $make_user_test = array(
                        'input_last_name' => get_field('acf_remote_sprit_sheet_last_name_' . $i ,$remote_temporary_sheet_id),
                        'input_first_name' => get_field('acf_remote_sprit_sheet_first_name_' . $i ,$remote_temporary_sheet_id),
                        'input_last_name_kana' => get_field('acf_remote_sprit_sheet_last_name_kana_' . $i ,$remote_temporary_sheet_id),
                        'input_first_name_kana' => get_field('acf_remote_sprit_sheet_first_name_kana_' . $i ,$remote_temporary_sheet_id),
                        'input_user_born_year' => $birthday_array[0],
                        'input_user_born_month' => $birthday_array[1],
                        'input_user_born_day' => $birthday_array[2],

                    );


                    $resdt = setRemoteUserRegist($make_user_test);


                    //IDが保存
                    $spiritSheet->saveRemoteSpritTargetData( "acf_remote_sprit_sheet_target_id" ,  $remote_sheet_id , $i , $resdt);

                }

                //関係を入れる
                update_field("acf_remote_sprit_sheet_target_relationship_" . $i,get_field('acf_remote_sprit_sheet_target_relationship_' . $i ,$remote_temporary_sheet_id), $remote_sheet_id);

                //画像を保存

                $img_url =  $spirit_sheet_data->setMadeilibrary(  get_field('acf_remote_sprit_sheet_target_img_' . $i ,$remote_temporary_sheet_id) , get_field('acf_remote_sprit_sheet_target_img_name_' . $i ,$remote_temporary_sheet_id), get_field('acf_remote_sprit_sheet_target_img_path_' . $i ,$remote_temporary_sheet_id) ,  $i );

                update_field("acf_remote_sprit_sheet_target_img_" . $i,$img_url, $remote_sheet_id);

                

               
             }

             //仮シートを削除
             wp_delete_post($remote_temporary_sheet_id, true);


        }





    }

    
    $title = "";
    $img_upload = false;
    $img_url = getURLSetSlag("admin-spirit-detail").'?user_id='.$check_user_id.'&sheet_name='.$_GET['sheet_name'].'&img_upload=on';
    $img_title = "画像アップロード";
    if(isset($_GET['img_upload'])){
        $img_upload = true;
        $title = "　画像アップロード";
        $img_url = getURLSetSlag("admin-spirit-detail").'?user_id='.$check_user_id.'&sheet_name='.$_GET['sheet_name'];
        $img_title = "鑑定シート詳細";
    }

    DeleteImgModalDisp();   //画像削除モーダル
    //DispImgmodal();         //画像表示モーダル



     $userData = $userClass->getUserAcountData($check_user_id);//ユーザー情報
     $sheet_data = $userClass->getUserSpritApplicantSheet($check_user_id,$_GET["sheet_name"]);

     //メール送信とお知らせを作成
     if(isset($_POST["mail_send_spirit"])){
      
       
            $mail_id = $mailText->getMailText($_POST["mail_send_spirit"],$_POST["mail_send_type"],true);
            
            if($_POST["mail_send_type"] == SpiritUserClass::MAIL_TYPE_SALES_SEND_REPORT)
            {
                //物販
                $mail_text_array = $mailText->sendSalesSendMail( $check_user_id , $mail_id , $sheet_data[$_GET["sheet_name"]] ,$_POST["mail_send_type"] ,true);
            }
           else{
                $mail_text_array = $mailText->sendMemberStatusMail( $check_user_id , $mail_id , $sheet_data[$_GET["sheet_name"]] ,$_POST["mail_send_type"] ,true);
                
           }
        
           $news_id = $newsClass->createNewsDataMail($check_user_id,$_POST,$mail_text_array);

           if($news_id != "")
           {
                //メール送信
                if($_POST["mail_send_type"] == SpiritUserClass::MAIL_TYPE_SALES_SEND_REPORT)
                {
                    //物販
                    $mail_text_array = $mailText->sendSalesSendMail( $check_user_id , $mail_id , $sheet_data[$_GET["sheet_name"]] ,$_POST["mail_send_type"]);
                }
                else{
                    $mail_text_array = $mailText->sendMemberStatusMail( $check_user_id , $mail_id , $sheet_data[$_GET["sheet_name"]] ,$_POST["mail_send_type"]);
                    
                }
              //ニュースを作成したらポップアップでメール送信完了を表示
              echo "<script>alert('メール送信完了');</script>";
           }
     }

    // var_dump($sheet_data);
?>

    <div class="admin-spirit-title-box">
        <div class="admin-spirit-menu-title"><?php echo $check_user_data['input_last_name'] . " " . $check_user_data['input_first_name'] . " " .  get_field('acf_pure_spirit_title',get_field('acf_acf_purespirit_type',$_GET["sheet_name"]));?> シート<?php echo $title;?></div>
    </div>
    
    <div style="display: flex; justify-content: flex-start; gap: 10px;">


        <?php if(!judgeUserRole()){?>
            <form action="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $_GET['user_id'];?>" method="post" style="margin: 0;">
                <input type="hidden" name="change_user" value="change_user" id="">
                <button type="submit" id="form-submit" class="form-btn" style="width: 220px; height: 40px;margin: 0px;background-color: beige;">プロフィールを編集する</button>
            </form>
        <?php } ?>

        <?php if(!judgeUserRole()){?>
            <div style="text-align: right;">
                <button type="button" id="form-back" class="form-btn" style="display: inline-block;width: 220px; margin-top: 0px;height: 40px;background-color: antiquewhite;">
                    <a href="<?php echo getURLSetSlag("admin-spirit-detail").'?user_id='.$check_user_id.'&sheet_edit=on&sheet_name='.$_GET['sheet_name']; ?>">管理シート編集</a>
                </button>
            </div>
        <?php } ?>

        <form action="<?php echo getURLSetSlag("users/user-spirit-question-edit");?>?check_user=<?php echo $check_user_id; ?>" method="post" target="_blank" style="display: inline;">
            <button type="submit"  class="admin-spirit-question-edit-button" style="font-size: 16px;width: 220px;height: 40px;cursor: pointer;">質問シート編集</button>
            <input type="hidden"  name="sheet_id" value="<?php echo $_GET["sheet_name"]; ?>">
        </form>

        <button type="button" id="form-back" class="form-btn" style="width: 220px; height: 40px;margin: 0px;">
            <a href="<?php echo getURLSetSlag("admin-member-edit").'?user_id='.$check_user_id; ?>">シートTOPへ戻る</a>
        </button>

    </div>


    <div class="admin-spirit-subtitle-box accordion-header-report-buttons" style="width: 100%; cursor: pointer;" onclick="toggleReportButtonsAccordion()">
        <div class="admin-spirit-menu-title" style="display: flex; justify-content: space-between; align-items: center;">
            <span>報告ボタン</span>
            <span class="accordion-icon-report-buttons" style="font-size: 20px;">▶</span>
        </div>
    </div>

    <div id="reportButtonsAccordionContent" class="report-buttons-accordion-content" style="display: none;">
        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #e9ecef;">
            <div style="font-weight: bold; color: #495057; margin-bottom: 15px; font-size: 14px;">ボタンを押すと、会員にメールとお知らせが届きます</div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">

                <?php if($sheet_data[$_GET["sheet_name"]]["依頼タイプ"] != SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){?>
                    <form action="<?php echo getURLSetSlag("admin-spirit-detail").'?user_id='.$check_user_id.'&sheet_name='.$_GET['sheet_name']; ?>" method="post" style="margin: 0;">
                        <input type="hidden" name="mail_send_spirit" value="<?php echo $sheet_data[$_GET["sheet_name"]]["依頼タイプ"];?>">
                        <input type="hidden" name="mail_send_type" value="<?php echo SpiritUserClass::MAIL_TYPE_COMPLETE_REPORT;?>">
                        <input type="hidden" name="mail_send_unixtime" value="<?php echo time();?>">
                        <button type="button" onclick="confirmSubmit('complete', '施術完了報告')" class="form-btn" style="background-color: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; min-width: 150px;margin-top: 0px;margin-bottom: 5px;">施術完了報告</button>
                    </form>
                <?php }else{?>
                    <form action="<?php echo getURLSetSlag("admin-spirit-detail").'?user_id='.$check_user_id.'&sheet_name='.$_GET['sheet_name']; ?>" method="post" style="margin: 0;">
                        <input type="hidden" name="mail_send_spirit" value="<?php echo $sheet_data[$_GET["sheet_name"]]["依頼タイプ"];?>">
                        <input type="hidden" name="mail_send_type" value="<?php echo SpiritUserClass::MAIL_TYPE_SALES_SEND_REPORT;?>">
                        <input type="hidden" name="mail_send_unixtime" value="<?php echo time();?>">
                        <button type="button" onclick="confirmSubmit('sales_send', '物販発送完了報告')" class="form-btn" style="background-color: #e83e8c; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; min-width: 150px;margin-top: 0px;margin-bottom: 5px;">物販発送完了報告</button>
                    </form>

                <?php }?>

                <form action="<?php echo getURLSetSlag("admin-spirit-detail").'?user_id='.$check_user_id.'&sheet_name='.$_GET['sheet_name']; ?>" method="post" style="margin: 0;">
                    <input type="hidden" name="mail_send_spirit" value="<?php echo $sheet_data[$_GET["sheet_name"]]["依頼タイプ"];?>">
                    <input type="hidden" name="mail_send_type" value="<?php echo SpiritUserClass::MAIL_TYPE_SHEET_CONFIRM_REPORT;?>">
                    <input type="hidden" name="mail_send_unixtime" value="<?php echo time();?>">
                    <button type="button" onclick="confirmSubmit('sheet_confirm', 'シート確認完了報告')" class="form-btn" style="background-color: #20c997; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; min-width: 150px;margin-top: 0px;margin-bottom: 5px;">シート確認完了報告</button>
                </form>

                <form action="<?php echo getURLSetSlag("admin-spirit-detail").'?user_id='.$check_user_id.'&sheet_name='.$_GET['sheet_name']; ?>" method="post" style="margin: 0;">
                    <input type="hidden" name="mail_send_spirit" value="<?php echo $sheet_data[$_GET["sheet_name"]]["依頼タイプ"];?>">
                    <input type="hidden" name="mail_send_type" value="<?php echo SpiritUserClass::MAIL_TYPE_SHEET_RETURN_REPORT;?>">
                    <input type="hidden" name="mail_send_unixtime" value="<?php echo time();?>">
                    <button type="button" onclick="confirmSubmit('sheet_return', 'シート再提出報告')" class="form-btn" style="background-color: #fd7e14; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; min-width: 150px;margin-top: 0px;margin-bottom: 5px;">シート再提出報告</button>
                </form>

                <form action="<?php echo getURLSetSlag("admin-spirit-detail").'?user_id='.$check_user_id.'&sheet_name='.$_GET['sheet_name']; ?>" method="post" style="margin: 0;">
                    <input type="hidden" name="mail_send_spirit" value="<?php echo $sheet_data[$_GET["sheet_name"]]["依頼タイプ"];?>">
                    <input type="hidden" name="mail_send_type" value="<?php echo SpiritUserClass::MAIL_TYPE_PAYMENT_CONFIRM_REPORT;?>">
                    <input type="hidden" name="mail_send_unixtime" value="<?php echo time();?>">
                    <button type="button" onclick="confirmSubmit('payment_confirm', '入金確認完了報告')" class="form-btn" style="background-color: #17a2b8; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; min-width: 150px;margin-top: 0px;margin-bottom: 5px;">入金確認完了報告</button>
                </form>
                
                <?php if($sheet_data[$_GET["sheet_name"]]["依頼タイプ"] != SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){?>
                    <form action="<?php echo getURLSetSlag("admin-spirit-detail").'?user_id='.$check_user_id.'&sheet_name='.$_GET['sheet_name']; ?>" method="post" style="margin: 0;">
                        <input type="hidden" name="mail_send_spirit" value="<?php echo $sheet_data[$_GET["sheet_name"]]["依頼タイプ"];?>">
                        <input type="hidden" name="mail_send_type" value="<?php echo SpiritUserClass::MAIL_TYPE_SCHEDULE_DECISION_REPORT;?>">
                        <input type="hidden" name="mail_send_unixtime" value="<?php echo time();?>">
                        <button type="button" onclick="confirmSubmit('schedule_decision', '施術日決定報告')" class="form-btn" style="background-color: #6f42c1; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; min-width: 150px;margin-top: 0px;margin-bottom: 5px;">施術日決定報告</button>
                    </form>
                <?php }?>

                <form action="<?php echo getURLSetSlag("admin-personal-news-edit").'?user_id='.$check_user_id.'&sheet_name='.$_GET['sheet_name']; ?>" method="post" style="margin: 0;" target="_blank">
                    <button type="submit" class="form-btn" style="background-color: #17a2b8; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; min-width: 150px;margin-top: 0px;margin-bottom: 5px;">個別お知らせ作成</button>
                </form>
            </div>
        </div>
    </div><!-- 報告ボタンアコーディオンの終了 -->

    <div>
        <div class="admin-spirit-subtitle-box accordion-header-profile" style="width: 100%; cursor: pointer;" onclick="toggleProfileAccordion()">
            <div class="admin-spirit-menu-title" style="display: flex; justify-content: space-between; align-items: center;">
                <span>プロフィール</span>
                <span class="accordion-icon-profile" style="font-size: 20px;">▶</span>
            </div>
        </div>
        
        <div id="profileAccordionContent" class="profile-accordion-content" style="display: none;">
            <div style="text-align: right; margin-top: 10px;">
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <?php if(!judgeUserRole()){?>
                        <form action="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $_GET['user_id'];?>" method="post" style="margin: 0;">
                            <input type="hidden" name="change_user" value="change_user" id="">
                            <button type="submit" id="form-submit" class="form-btn" style="width: 220px; height: 40px;margin: 0px;background-color: beige;">プロフィールを編集する</button>
                        </form>
                    <?php } ?>
                </div>
            </div>

            <div class="user-table-flex" style="margin-top: 30px;">
                <div class="user-table-item">ID</div><?php echo $check_user_data['input_unique_id']; ?>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">名前</div>
                <?php echo  $check_user_data['input_last_name']; ?> <?php echo  $check_user_data['input_first_name'];?> ( <?php echo $check_user_data['input_last_name_kana'];?> <?php echo $check_user_data['input_first_name_kana'];?> )
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">生年月日・性別</div>
                    <?php echo  $check_user_data['input_user_born_year'];?>年<?php echo  $check_user_data['input_user_born_month'];?>月<?php echo  $check_user_data['input_user_born_day'];?>日
                    <?php echo getUserSex($check_user_data['input_user_sex']); ?>
            </div>

            <div class="user-table-flex" >
                <div class="user-table-item">関連</div>
                <div class="user-table-data">        </div>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">連絡先</div>
                <?php echo $check_user_data['input_tel_1'];?>-<?php echo $check_user_data['input_tel_2'];?>-<?php echo $check_user_data['input_tel_3'];?>
            </div>

            <div class="user-table-flex" >
                <div class="user-table-item">メールアドレス</div><?php echo $check_user_data['input_user_email'];?>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">住所</div>
                〒<?php echo $check_user_data['input_post_no'];?>　<?php echo $check_user_data['input_billing_city'];?>　<?php echo $check_user_data['input_address2'];?>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">登録日</div>   <?php echo $check_user_data['input_user_registed'];?>
            </div>
            <div class="user-table-flex">
                <div class="user-table-item">特記事項</div>   <?php echo $check_user_data['input_remarks'];?>
            </div>
        </div>

    </div>





    <?php if(!$img_upload){?>


        <?php /* シート詳細 */?>
        <div class="admin-spirit-subtitle-box accordion-header-management" style="width: 100%; cursor: pointer;" onclick="toggleManagementAccordion()">
            <div class="admin-spirit-menu-title" style="display: flex; justify-content: space-between; align-items: center;">
                <span>管理詳細</span>
                <span class="accordion-icon-management" style="font-size: 20px;">▶</span>
            </div>
        </div>

        <div id="managementAccordionContent" class="management-accordion-content" style="display: none;">
            <div style="text-align: right; margin-top: 10px;">
                最終更新:<?php echo $sheet_data[$_GET["sheet_name"]]["最終変更日年月日"]; ?> <?php echo $sheet_data[$_GET["sheet_name"]]["最終変更者名"]; ?>
            </div>

            <?php if(!judgeUserRole()){?>
                <div style="text-align: right;margin-top:30px;">
                    <button type="button" id="form-back" class="form-btn" style="display: inline-block;width: 220px; margin-top: 0px;height: 40px;background-color: antiquewhite;">
                        <a href="<?php echo getURLSetSlag("admin-spirit-detail").'?user_id='.$check_user_id.'&sheet_edit=on&sheet_name='.$_GET['sheet_name']; ?>">管理シート編集</a>
                    </button>
                </div>
           <?php } ?>

            <div class="user-table-flex">
                <div class="user-table-item">施術名</div><?php echo get_field('acf_pure_spirit_title',get_field('acf_acf_purespirit_type',$_GET["sheet_name"]));?>
            </div>

            
            <div class="user-table-flex">
                <div class="user-table-item">依頼日</div><?php echo  $sheet_data[$_GET["sheet_name"]]["依頼日年月日"];?>
            </div>

            <div class="user-table-flex" >
                <div class="user-table-item">申込者</div>
                <div class="user-table-data"><?php echo $sheet_data[$_GET["sheet_name"]]["フル名前"];?></div>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">依頼確定日</div>  <?php echo $sheet_data[$_GET["sheet_name"]]["依頼確定日年月日"];?>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">決済日</div>  <?php echo   $sheet_data[$_GET["sheet_name"]]["決済日年月日"];?>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">実行予定日</div> 
                <div>
                    <div style="margin-top: 18px;"><?php echo  $sheet_data[$_GET["sheet_name"]]["実行予定日年月日"];?>　</div>
                    <div style="font-size: 10px;font-weight: 600;">
                        実行日を入力していない状態で実行予定日を入力していると、会員状況ステータスが「浄霊依頼中」の際に「〇〇年〇月〇日～〇〇年〇月〇予定」と表示されます。<br>
                        会員状況ステータスが「浄霊依頼中」で予定日が入力されていない場合は、「施術日未確定」と表示されます。
                    </div>
                </div>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">実行日</div> 
                <div>
                    <div style="margin-top: 18px;"><?php echo  $sheet_data[$_GET["sheet_name"]]["実行日年月日"];?>　</div>
                    <div style="font-size: 10px;font-weight: 600;">
                        実行日を<font color="red">入力している状態</font>でも実行予定日の日で「〇〇年〇月〇日～〇〇年〇月〇予定」と会員には表示されます。<br>
                        スケジュールが決まっている場合は、そちらの日が優先されます。<br>
                        <font color="red">会員の入力可能締め切りは実行日までとなります。</font>
                    </div>
                </div>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">管理者　状況ステータス</div>   
                    <?php 
                            
                        $status = $sheet_data[$_GET["sheet_name"]]["管理者ステータス表示"];

                        echo $status;

                    ?>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">会員　状況ステータス</div>   
                <?php 
                    $status = $sheet_data[$_GET["sheet_name"]]["会員ステータス表示"];
                    echo $status;
                ?>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">キャンセル日</div>   <?php echo  $sheet_data[$_GET["sheet_name"]]["キャンセル日"];?>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">価格</div>   <?php echo  $sheet_data[$_GET["sheet_name"]]["価格"];?>　円
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">支払い方法</div>   <?php echo  $sheet_data[$_GET["sheet_name"]]["支払いタイプ表示"];?>
            </div>

            <?php if( $sheet_data[$_GET["sheet_name"]]["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){ //物販表示?>
                
                <div class="user-table-flex">
                    <div class="user-table-item">個数</div>   <?php echo $sheet_data[$_GET["sheet_name"]]["販売個数"];?>　個
                </div>
                <div class="user-table-flex">
                    <div class="user-table-item">追跡番号画像</div> 
                    
                    <?php  if(get_field('acf_previous_sales_post_img',$_GET["sheet_name"]) != ""){ ?>
                    
                        <button id="openSalesImgBtn">画像を表示する</button>

                        <!-- モーダル -->
                        <?php 
                            $sales_img =  get_field('acf_previous_sales_post_img',$_GET["sheet_name"]);    
                            
                        // echo $sales_img;
                        ?>

                        <div id="salesImgModal" class="emark_img_modal">
                            <div class="emark_img_modal_content">
                                <span class="emark_img_close">&times;</span>
                                <img src="<?php echo $sales_img;?>" alt="Popup Image">
                            </div>
                        </div>

                    <?php } ?>
                </div>
                <div class="user-table-flex">
                    <div class="user-table-item">送付先住所</div>
                    <?php if($sheet_data[$_GET["sheet_name"]]["郵送先住所1"] != ""){?>
                        <?php echo $sheet_data[$_GET["sheet_name"]]["郵送先郵便番号"];?><br>
                        <?php echo $sheet_data[$_GET["sheet_name"]]["郵送先住所1"];?>
                        <?php echo $sheet_data[$_GET["sheet_name"]]["郵送先住所2"];?><br>
                        <?php echo $sheet_data[$_GET["sheet_name"]]["郵送先名前"];?>
                    <?php }else{?>
                        <?php echo $userData["郵便番号ハイフン"];?><br>
                        <?php echo $userData["住所"];?><br>
                        <?php echo $userData["フル名前"];?>
                    <?php }?>
                </div>
            <?php } ?>



            <div class="user-table-flex">
                <div class="user-table-item">事務局メモ（依頼内容追記）</div>  <?php echo  nl2br(get_field('acf_purespirit_add_text',$_GET["sheet_name"]));?>
            </div>


            <div class="user-table-flex">
                <div class="user-table-item">再提出依頼表示</div>  
                <div>
                    <?php echo  nl2br(get_field('acf_purespirit_return_input_text',$_GET["sheet_name"]));?>
                    <div style="font-size: 11px;font-weight: 600;">会員ステータスが「必要事項再入力」の際に会員の質問ページに表示されます</div>
                </div>
            </div>

            <?php /* A-GATE確認 */?>
            <?php if(!judgeUserRole()){?>
        
        
            
                <div class="user-table-flex">
                    <div class="user-table-item">氏名等確認</div>   <?php   if(get_field('acf_agate_name_check',$_GET["sheet_name"]) != ""){ echo "確認済み";}?>
                </div>
                <div class="user-table-flex">
                    <div class="user-table-item">結果レポ格納</div>   <?php   if(get_field('acf_agate_result_on',$_GET["sheet_name"]) != ""){ echo "確認済み";}?>
                </div>

                <?php if( $sheet_data[$_GET["sheet_name"]]["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){ ?>
                    <div class="user-table-flex">
                        <div class="user-table-item">相談者への支払い額</div>   
                        <?php  
                        
                            $exe_pay_amount = get_field('acf_previous_execution_pay',$_GET["sheet_name"]);

                            if($exe_pay_amount == ""){
                                $exe_pay_amount = 0;
                            }
                        
                            echo $exe_pay_amount . "円";
                        
                        ?>
                    </div>
                <?php } ?>
                
                <div class="user-table-flex">
                    <div class="user-table-item">Eマーク送付画像</div> 
                    
                    <?php  if(get_field('acf_agate_result_img',$_GET["sheet_name"]) != ""){ ?>
                    
                        <button id="openAgateImgBtn">画像を表示する</button>

                        <!-- モーダル -->
                        <?php 
                            $agate_img =  get_field('acf_agate_result_img',$_GET["sheet_name"]);
                                    
                        ?>

                        <div id="agateImgModal" class="emark_img_modal">
                            <div class="emark_img_modal_content">
                                <span class="emark_img_close">&times;</span>
                                <img src="<?php echo $agate_img;?>" alt="Popup Image">
                            </div>
                        </div>

                    <?php } ?>
                </div>
        
            
            <?php } ?>
        


            <?php if($sheet_data[$_GET["sheet_name"]]["スケジュール有無"] != ""){  ?>

                <div class="admin-spirit-subtitle-box" style="margin-bottom:0;width: 100%;">
                    <div class="admin-spirit-menu-title">スケジュール</div>
                </div>


                <?php if($sheet_data[$_GET["sheet_name"]]["スケジュール"] != ""){  ?>

                    <div style="display: flex;justify-content: right;">
                        <div class="admin-spirit-question-edit-button-area">
                            <form action="<?php echo getURLSetSlag("admin-spirit-schedule-edit");?>" method="post" target="_blank">
                                <button type="submit"  class="admin-spirit-question-edit-button" style="width: 220px;height: 40px;">スケジュール詳細</button>
                                <input type="hidden"  name="edit_schedule" value="<?php echo $sheet_data[$_GET["sheet_name"]]["スケジュール"]; ?>">
                                <input type="hidden"  name="read" value="">
                            </form>
                        </div>

                        <div class="admin-spirit-question-edit-button-area">
                            <form action="<?php echo getURLSetSlag("admin-spirit-schedule-list");?>" method="post">
                                <button type="submit"  class="admin-spirit-question-edit-button" style="width: 220px;height: 40px;margin-left: 10px;background-color: gainsboro;">スケジュール変更</button>
                                <input type="hidden"  name="choice_user" value="<?php echo $check_user_id;?>">
                                <input type="hidden"  name="choice_sheet" value="<?php echo $_GET["sheet_name"];?>">
                                <input type="hidden"  name="choice_schedule" value="<?php echo $sheet_data[$_GET["sheet_name"]]["スケジュール"];?>">
                            </form>
                        </div>
                    </div>

                    <?php 
                        $spiritScheduleData = new SpiritScheduleClass(); //スケジュールデータ

                        $set_spirit_sheet = $spiritScheduleData->getSpritScheduledetail($sheet_data[$_GET["sheet_name"]]["スケジュール"]);

                    ?>

                    <div class="user-table-flex">
                        <div class="user-table-item"  style="width: 135px;">表示名</div>
                        <?php echo $set_spirit_sheet["表示名"];?>
                    </div>

                    <div class="user-table-flex">
                        <div class="user-table-item"  style="width: 135px;">依頼種類</div>
                        <?php echo $set_spirit_sheet["施術名管理"];?>
                    </div>

                    <div class="user-table-flex">
                        <div class="user-table-item"  style="width: 135px;">実施日</div>
                        <?php echo $set_spirit_sheet["実行年月日"];?>
                        <?php if($set_spirit_sheet["実行時間"] == ""){ ?>
                        
                        <?php }else{ ?>
                        　    <?php echo $set_spirit_sheet["実行時間"];?>時～
                        <?php } ?>
                    </div>


                    <div class="user-table-flex">
                        <div class="user-table-item"  style="width: 135px;">施術場所</div>

                        <?php if( $set_spirit_sheet["場所"] != ""){?>
                        
                    
                            <?php echo $set_spirit_sheet["場所ステータス"]["名前"] . " " . $set_spirit_sheet["場所ステータス"]["住所"]; ?>

                        <?php } ?>
                    </div>


                

                <?php }else{ ?>

                    <div style="margin-top: 20px;font-size: 24px;">設定がされていません。<br>シート編集よりご変更ください。</div>

                    <div class="admin-spirit-question-edit-button-area">
                        <form action="<?php echo getURLSetSlag("admin-spirit-schedule-list");?>" method="post">
                            <button type="submit"  class="admin-spirit-question-edit-button" style="background-color: gainsboro;">スケジュール変更</button>
                            <input type="hidden"  name="choice_user" value="<?php echo $check_user_id;?>">
                            <input type="hidden"  name="choice_sheet" value="<?php echo $_GET["sheet_name"];?>">
                        </form>
                    </div>

                <?php } ?>

                

            <?php } ?>

        </div><!-- 管理詳細アコーディオンの終了 -->




        <?php /* 質問事項 */?>
            <div class="admin-spirit-subtitle-box accordion-header-question" style="margin-bottom:0;width: 100%; cursor: pointer;" onclick="toggleQuestionAccordion()">
                <div class="admin-spirit-menu-title" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>質問要項</span>
                    <span class="accordion-icon-question" style="font-size: 20px;">▶</span>
                </div>
            </div>

        <div id="questionAccordionContent" class="question-accordion-content" style="display: none;">

            <div style="text-align: right; margin-top: 10px;display: flex;justify-content: space-between;">
                <?php if(!judgeUserRole()){?>
                    <form action="<?php echo getURLSetSlag("admin-spirit-detail");?>?user_id=<?php echo $check_user_id; ?>&sheet_name=<?php echo $_GET["sheet_name"]; ?>" method="post" id="statusChangeForm" style="margin: 0; display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                        <input type="hidden" name="change_purespirit_user_status" value="true" id="">
                        <?php 

                            $status = get_field('acf_purespirit_user_status',$_GET["sheet_name"]);
            
                            $spiritStatusArray = $spirit_sheet_data->getSpiritAdminStatus('cpt_spirit_usestatus');
                        ?>
                        <select name="acf_purespirit_user_status" id="statusSelect" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                            <?php foreach ($spiritStatusArray as $value) {?>
                                <?php if($status != $value["ID"]){continue;}?>
                                <option value="<?php echo $value["ID"];?>" <?php if($status == $value["ID"]){ echo "selected"; }?>><?php echo $value["title"]?></option>
                            <?php } ?>
                            <?php foreach ($spiritStatusArray as $value) {?>
                                <?php if($status == $value["ID"]){continue;}?>
                                <option value="<?php echo $value["ID"];?>" <?php if($status == $value["ID"]){ echo "selected"; }?>><?php echo $value["title"]?></option>
                            <?php } ?>
                        </select>

                        <button type="button" onclick="confirmStatusChange()" class="form-btn" style="width: 220px; height: 40px;margin: 0px;background-color: beige;">変更する</button>
                    </form>
                <?php } ?>

            
                <div class="admin-spirit-question-edit-button-area" style="display: inline-block;">
                    <form action="<?php echo getURLSetSlag("users/user-spirit-question-edit");?>?check_user=<?php echo $check_user_id; ?>" method="post" target="_blank" style="display: inline;">
                        <button type="submit"  class="admin-spirit-question-edit-button" style="font-size: 16px;width: 220px;height: 40px;cursor: pointer;">質問シート編集</button>
                        <input type="hidden"  name="sheet_id" value="<?php echo $_GET["sheet_name"]; ?>">
                    </form>
                </div>
            </div>

            <?php 

                $post_sheet = get_post($_GET['sheet_name']);

                $date = new DateTime($post_sheet->post_date);
                $write_date = $date->format('Y/m/d');
            ?>

            <div class="" style="margin-top: 20px;">入力日:<?php echo $write_date;?></div>

            <?php if(judgeUserRole()){?>
                <button type="button" id="" class="form-btn" style="background-color: antiquewhite;">
                    <a href="<?php echo getURLSetSlag("admin-spirit-detail").'?user_id='.$check_user_id.'&sheet_edit=on&sheet_name='.$_GET['sheet_name']; ?>">コメント編集</a>
                </button>
            <?php } ?>

        <?php
        
            $type = get_field('acf_acf_purespirit_type',$_GET["sheet_name"]);

        
            //if( $type != 77)//リモート浄霊以外
            {

                //現在の質問番号を取得
                $spiritQuestionArray = $spirit_sheet_data->getSpiritQuestion($type);

                //回答配列を取得
                $anser = $spirit_sheet_data->getSpiritSheetAnswer( $_GET["sheet_name"] );

                $count = 0;


                //対象者が違う場合を記載

                // var_dump($anser);
               
        
                if($spiritQuestionArray != "")
                {
                    $customize_data = $spirit_customize_data->getInputCustomizeData( $sheet_data[$_GET["sheet_name"]]["質問"] );


                    if($sheet_data[$_GET["sheet_name"]]["対象者有無"]){ //対象者選択有


                        $personal_input_array = $spirit_customize_data->getPersonalDataInput( $sheet_data[$_GET["sheet_name"]]["質問"] );

?>

                        <?php if($sheet_data[$_GET["sheet_name"]]["対象者"]["対象者情報"] != false){ ?>

                            <div class="admin-spirit-subtitle-box" style="width: 300px;margin-top: 20px;">
                                <div class="admin-spirit-menu-title">申込者</div>
                            </div>

                            <div style="font-size: 30px;margin-bottom: 30px;"> <?php echo  $check_user_data['input_last_name']; ?> <?php echo  $check_user_data['input_first_name'];?></div>
                        <?php } ?>


    
                        <div class="admin-spirit-subtitle-box" style="width: 300px;margin-top: 20px;">
                            <div class="admin-spirit-menu-title">対象者</div>
                        </div>

                        <?php if($sheet_data[$_GET["sheet_name"]]["対象者"]["対象者情報"] == false){ ?>

                            <div style="font-size: 30px;margin-bottom: 30px;">申込者と同じ</div>

                        <?php }else{ ?>

                            <?php foreach ($personal_input_array as $key => $value) { //対象者と申込者が違う?>

                                <?php 
                                    //if(!isset($customize_data["acf_spirit_note_personal_table"][ $value["ID"]] ) ){continue;} 
                                    if($customize_data["acf_spirit_note_personal_table"][ $value["ID"] ][0] == ""){continue;}//対象者用のデータON・OFF
                                    if($value["acf_input_target"] == "" ){continue;} //対象者用のデータON・OFF
                                ?>

                                <div class="orderform-question-box">
                                        <div class="orderform-question-input-area">


                                        <div class="orderform-question-title">
                                            <div class="orderform-question-title-right">〇<?php echo $value["acf_input_personal_data_title"]; ?></div>
                                        </div>

                                        <?php 
                                
                                            if($value["acf_input_target"] != "" ){
                                                $disp_questiont_class->dispPersonalDataInputTargetForm( $value["ID"] , $customize_data["acf_spirit_note_personal_table"] , $userData ,  $sheet_data[$_GET["sheet_name"]]["対象者"] , false);
                                            }
                                        ?>
                              
                                        </div>
                                    </div>

                                    <div class="orderform-question-line"></div>

                            <?php } ?>


                        <?php } ?>
                        
                    <?php } ?>


                   <?php

                    if(isset($spiritQuestionArray[$type])){
                        foreach ($spiritQuestionArray[$type] as $key => $value) {

                            if($value["text"] != true || $value["type"] == 10)//対象者追加は外す
                            {
                                continue;
                            }

                            $count++;
?>

                            <div class="">

                                <div class="spirt_question_title">Q<?php echo $count;?>.<?php echo $value["text"];?></div>
                                <div class="" style="min-height: 50px;">

                                    <?php 
                                        //表示の内容
                                        $disp_value = "";

                                        if( isset(  $anser[$value["ID"]] ) ){

                                            if($value["type"] == SpiritSheetClass::QUESTION_TYPE_IMG_DATA){//画像は画像URLを渡す
                                                $disp_value = get_field("acf_questionqnser_img_url" ,  $anser[$value["ID"]]);
                                            }
                                            else{
                                                $disp_value = get_field("acf_questionqnser_text" ,  $anser[$value["ID"]]);
                                            }
                                        }

                                        //質問の表示
                                        $disp_questiont_class->dispQuestionByType( $userClass , $value , $anser , $disp_value  , false);

                                    ?>
                            
                                    <div class="" style="border-bottom: 1px solid gray;margin-top: 10px;"> </div>

                                </div>
                            </div>
<?php
                        }
                    }

                } 
            }

            
    ?>

    <?php if(!judgeUserRole()){?>
        <form action="<?php echo getURLSetSlag("admin-spirit-detail");?>?user_id=<?php echo $check_user_id; ?>&sheet_name=<?php echo $_GET["sheet_name"]; ?>" method="post" id="statusChangeForm" style="margin: 0; display: flex; align-items: center; gap: 10px; margin-top: 50px;">
            <input type="hidden" name="change_purespirit_user_status" value="true" id="">
            <?php 

                $status = get_field('acf_purespirit_user_status',$_GET["sheet_name"]);

                $spiritStatusArray = $spirit_sheet_data->getSpiritAdminStatus('cpt_spirit_usestatus');
            ?>
            <select name="acf_purespirit_user_status" id="statusSelect" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                <?php foreach ($spiritStatusArray as $value) {?>
                    <?php if($status != $value["ID"]){continue;}?>
                    <option value="<?php echo $value["ID"];?>" <?php if($status == $value["ID"]){ echo "selected"; }?>><?php echo $value["title"]?></option>
                <?php } ?>
                <?php foreach ($spiritStatusArray as $value) {?>
                    <?php if($status == $value["ID"]){continue;}?>
                    <option value="<?php echo $value["ID"];?>" <?php if($status == $value["ID"]){ echo "selected"; }?>><?php echo $value["title"]?></option>
                <?php } ?>
            </select>

            <button type="button" onclick="confirmStatusChange()" class="form-btn" style="width: 220px; height: 40px;margin: 0px;background-color: beige;">変更する</button>
        </form>
    <?php } ?>



        </div><!-- 質問要項アコーディオンの終了 -->

    <?php
    }   // if(!$img_upload)
    ?>


    <?php /* 施術提出画像 */?>
    <div class="admin-spirit-subtitle-box accordion-header-additional" style="width: 100%; cursor: pointer;" onclick="toggleAdditionalAccordion()">
        <div class="admin-spirit-menu-title" style="display: flex; justify-content: space-between; align-items: center;">
            <span>追加画像</span>
            <span class="accordion-icon-additional" style="font-size: 20px;">▶</span>
        </div>
    </div>

    <div id="additionalAccordionContent" class="additional-accordion-content" style="display: none;">
        <form action="<?php echo getURLSetSlag("admin-spirit-detail").'?user_id='.$check_user_id.'&sheet_name='.$_GET['sheet_name']; ?>#acf_treatment_result_image_field" method="post" id="acf_treatment_result_image_field">
            
            <input type="hidden" name="upload_submit_result" value="upload_result" id="upload_submit_result">
            <input type="hidden" name="acf_treatment_result_image_field" id="acf_e-acf_treatment_result_image_field" value="">

            <?php if(!judgeUserRole()){?>
            <button type="button" id="treatment_result_image" class="treatment_result_image form-btn" style="background-color: lightcoral; margin-top: 10px;">追加画像をアップロード</button>
            <?php } ?>

        <div class="flex-img-area">

            <?php 
                $yet_img_no = 1;
                $acf_purespirit_submission_img_ids = json_decode(get_field('acf_purespirit_submission_img_id',$_GET["sheet_name"])); // 施術提出画像ID
                
                if(is_array($acf_purespirit_submission_img_ids)){
    
                    foreach ($acf_purespirit_submission_img_ids as $value) {
                        $media_post = get_post($value);  // メディア情報を取得
    
                        $purespirit_submission_image_date = $media_post->post_date;  //アップロード日
                        $purespirit_submission_image_url = wp_get_attachment_url($value);  //画像URL
            ?>
            <div class="img-box">

                <img class="spirit-detail-img" name="e-mark_image" id="uploaded_treatment_result_image_preview_<?php echo $yet_img_no; ?>" onclick="img_modal('<?php echo $purespirit_submission_image_url; ?>')" src="<?php echo $purespirit_submission_image_url; ?>" style="margin-left: 10px;max-width: 300px;<?php echo $purespirit_submission_image_url ? '' : 'display: none;'; ?>">
                <div class="img-upload-text">アップロード日<?php echo $purespirit_submission_image_date; ?></div>
                <button type="button" class="img-delete-mark" onclick="dispTreatmentBtn(<?php echo $value; ?>)">削除</button>

            </div>
                    <?php $yet_img_no++; ?>
                <?php } ?>
            <?php } ?>
        </div>
        </form>

        <?php if(!judgeUserRole()){?>
            <form action="<?php echo getURLSetSlag("admin-spirit-detail");?>?user_id=<?php echo $check_user_id; ?>&sheet_name=<?php echo $_GET["sheet_name"]; ?>" method="post" id="statusChangeForm" style="margin: 0; display: flex; align-items: center; gap: 10px; margin-top: 50px;">
                <input type="hidden" name="change_purespirit_user_status" value="true" id="">
                <?php 

                    $status = get_field('acf_purespirit_user_status',$_GET["sheet_name"]);

                    $spiritStatusArray = $spirit_sheet_data->getSpiritAdminStatus('cpt_spirit_usestatus');
                ?>
                <select name="acf_purespirit_user_status" id="statusSelect" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                    <?php foreach ($spiritStatusArray as $value) {?>
                        <?php if($status != $value["ID"]){continue;}?>
                        <option value="<?php echo $value["ID"];?>" <?php if($status == $value["ID"]){ echo "selected"; }?>><?php echo $value["title"]?></option>
                    <?php } ?>
                    <?php foreach ($spiritStatusArray as $value) {?>
                        <?php if($status == $value["ID"]){continue;}?>
                        <option value="<?php echo $value["ID"];?>" <?php if($status == $value["ID"]){ echo "selected"; }?>><?php echo $value["title"]?></option>
                    <?php } ?>
                </select>

                <button type="button" onclick="confirmStatusChange()" class="form-btn" style="width: 220px; height: 40px;margin: 0px;background-color: beige;">変更する</button>
            </form>
        <?php } ?>

    </div><!-- 追加画像アコーディオンの終了 -->


    <?php /* 結果レポート */ ?>
    <div class="admin-spirit-subtitle-box accordion-header-report" style="width: 100%; cursor: pointer;" onclick="toggleReportAccordion()">
        <div class="admin-spirit-menu-title" style="display: flex; justify-content: space-between; align-items: center;">
            <span>結果レポート画像</span>
            <span class="accordion-icon-report" style="font-size: 20px;">▶</span>
        </div>
    </div>

    <div id="reportAccordionContent" class="report-accordion-content" style="display: none;">
        <form action="<?php echo getURLSetSlag("admin-spirit-detail").'?user_id='.$check_user_id.'&sheet_name='.$_GET['sheet_name']; ?>#treatment_result_form" method="post" id="treatment_result_form">
            
            <input type="hidden" name="upload_result" value="" id="result_change">
            <input type="hidden" name="acf_e-mark_image_field" id="acf_e-mark_image_field" value="">
            
            <?php if(!judgeUserRole()){?>
            <button type="button" id="upload_image_button" class="e-mark form-btn" style="background-color: lightcoral; margin-top: 10px;">結果レポート画像をアップロード</button>
            <?php } ?>

        <div class="flex-img-area">
            <?php 
                $yet_img_no = 1;
                $treatment_result_ids = json_decode(get_field('acf_purespirit_result_img_id',$_GET["sheet_name"]));         //画像データ取得

                if(is_array($treatment_result_ids)){

                    foreach ($treatment_result_ids as $value) {
                        $media_post = get_post($value);  // メディア情報を取得
        
                        $treatment_result_image_date = $media_post->post_date;  //アップロード日
                        $treatment_result_image_url = wp_get_attachment_url($value);  //画像URL
            ?>
            <div class="img-box">
                <img class="spirit-detail-img" name="e-mark_image" id="uploaded_e-mark_image_preview_<?php echo $yet_img_no; ?>" onclick="img_modal('<?php echo $treatment_result_image_url; ?>')" src="<?php echo $treatment_result_image_url; ?>" style="margin-left: 10px;max-width: 300px;<?php echo $treatment_result_image_url ? '' : 'display: none;'; ?>">
                
                <div class="img-upload-text">アップロード日<?php echo $treatment_result_image_date; ?></div>
                <button type="button" class="img-delete-mark" onclick="dispBtn(<?php echo $value; ?>)">削除</button>
            </div>
            <?php
                        $yet_img_no++;
                    }
                }
            ?>
        </div>
        </form>

        <?php if(!judgeUserRole()){?>
            <form action="<?php echo getURLSetSlag("admin-spirit-detail");?>?user_id=<?php echo $check_user_id; ?>&sheet_name=<?php echo $_GET["sheet_name"]; ?>" method="post" id="statusChangeForm" style="margin: 0; display: flex; align-items: center; gap: 10px; margin-top: 50px;">
                <input type="hidden" name="change_purespirit_user_status" value="true" id="">
                <?php 

                    $status = get_field('acf_purespirit_user_status',$_GET["sheet_name"]);

                    $spiritStatusArray = $spirit_sheet_data->getSpiritAdminStatus('cpt_spirit_usestatus');
                ?>
                <select name="acf_purespirit_user_status" id="statusSelect" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                    <?php foreach ($spiritStatusArray as $value) {?>
                        <?php if($status != $value["ID"]){continue;}?>
                        <option value="<?php echo $value["ID"];?>" <?php if($status == $value["ID"]){ echo "selected"; }?>><?php echo $value["title"]?></option>
                    <?php } ?>
                    <?php foreach ($spiritStatusArray as $value) {?>
                        <?php if($status == $value["ID"]){continue;}?>
                        <option value="<?php echo $value["ID"];?>" <?php if($status == $value["ID"]){ echo "selected"; }?>><?php echo $value["title"]?></option>
                    <?php } ?>
                </select>

                <button type="button" onclick="confirmStatusChange()" class="form-btn" style="width: 220px; height: 40px;margin: 0px;background-color: beige;">変更する</button>
            </form>
        <?php } ?>

    </div><!-- 結果レポート画像アコーディオンの終了 -->

    <?php if($img_upload || isset($_GET['sheet_edit'])){?>
        
        <?php DispChangeLog($check_user_id); ?>

    <?php } ?>

     <script>
        // ボタンとモーダル要素の取得
        const modal = document.getElementById("eMarkModal");
        const btn = document.getElementById("openEmarkImgBtn");
        const span = document.getElementsByClassName("emark_img_close")[0];

        // ボタンがクリックされた時、モーダルを表示
        // btn.onclick = function() {
        //     modal.style.display = "block";
        // }

        // // 閉じるボタンがクリックされた時、モーダルを非表示にする
        // span.onclick = function() {
        //     modal.style.display = "none";
        // }

        // // モーダルの外側がクリックされた時、モーダルを非表示にする
        // window.onclick = function(event) {
        //     if (event.target == modal) {
        //         modal.style.display = "none";
        //     }
        // }
    </script>
     <script>
        // 全てのリンクを選択
        document.querySelectorAll('.postLink').forEach(link => {
            // 各リンクにクリックイベントを設定
            link.addEventListener('click', function(event) {
                event.preventDefault(); // デフォルトの動作をキャンセル

                // クリックされたリンクのデータ値を取得
                const value = this.getAttribute('data-value');

                // フォームを動的に作成
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?php echo getURLSetSlag("admin-arami-sheet-edit"); ?>';
                form.target = '_blank';

                // 隠しフィールドを追加
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'arami_check_sheet_id'; // サーバー側で使うキー
                hiddenInput.value = value; // リンクに対応する値
                form.appendChild(hiddenInput);

                // フォームを送信
                document.body.appendChild(form);
                form.submit();
            });
        });
    </script>
     <script>
        // 全てのリンクを選択
        document.querySelectorAll('.postaramiLink').forEach(link => {
            // 各リンクにクリックイベントを設定
            link.addEventListener('click', function(event) {
                event.preventDefault(); // デフォルトの動作をキャンセル

                // クリックされたリンクのデータ値を取得
                const value = this.getAttribute('data-value');

                // フォームを動的に作成
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?php echo getURLSetSlag("admin-arami-sheet-edit"); ?>';
                form.target = '_blank';

                // 隠しフィールドを追加
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'arami_sheet_id'; // サーバー側で使うキー
                hiddenInput.value = value; // リンクに対応する値
                form.appendChild(hiddenInput);

                // フォームを送信
                document.body.appendChild(form);
                form.submit();
            });
        });

    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // 追跡番号画像モーダル
        const openSalesImgBtn = document.getElementById('openSalesImgBtn');
        const salesImgModal = document.getElementById('salesImgModal');
        const salesImgClose = salesImgModal ? salesImgModal.querySelector('.emark_img_close') : null;

        if (openSalesImgBtn && salesImgModal) {
            openSalesImgBtn.addEventListener('click', function() {
                salesImgModal.style.display = 'block';
            });

            if (salesImgClose) {
                salesImgClose.addEventListener('click', function() {
                    salesImgModal.style.display = 'none';
                });
            }

            salesImgModal.addEventListener('click', function(e) {
                if (e.target === salesImgModal) {
                    salesImgModal.style.display = 'none';
                }
            });
        }

        // Eマーク送付画像モーダル
        const openAgateImgBtn = document.getElementById('openAgateImgBtn');
        const agateImgModal = document.getElementById('agateImgModal');
        const agateImgClose = agateImgModal ? agateImgModal.querySelector('.emark_img_close') : null;

        if (openAgateImgBtn && agateImgModal) {
            openAgateImgBtn.addEventListener('click', function() {
                agateImgModal.style.display = 'block';
            });

            if (agateImgClose) {
                agateImgClose.addEventListener('click', function() {
                    agateImgModal.style.display = 'none';
                });
            }

            agateImgModal.addEventListener('click', function(e) {
                if (e.target === agateImgModal) {
                    agateImgModal.style.display = 'none';
                }
            });
        }
    });
    </script>

    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/user-disp-utility.js?<?php echo date('Ymd H:i:s'); ?>"></script>

    <script>
    function confirmSubmit(action, actionName) {
        if (confirm('「' + actionName + '」を実行しますか？\n\n会員にメールとお知らせが送信されます。')) {
            // 対応するフォームを探して送信
            const forms = document.querySelectorAll('form');
            for (let form of forms) {
                const hiddenInput = form.querySelector('input[name="mail_send_spirit"]');
                const mailTypeInput = form.querySelector('input[name="mail_send_type"]');
                
                // actionに基づいて正しいフォームを特定
                if (hiddenInput && mailTypeInput) {
                    let shouldSubmit = false;
                    
                    switch(action) {
                        case 'complete':
                            if (mailTypeInput.value === '<?php echo SpiritUserClass::MAIL_TYPE_COMPLETE_REPORT; ?>') {
                                shouldSubmit = true;
                            }
                            break;
                        case 'sheet_confirm':
                            if (mailTypeInput.value === '<?php echo SpiritUserClass::MAIL_TYPE_SHEET_CONFIRM_REPORT; ?>') {
                                shouldSubmit = true;
                            }
                            break;
                        case 'sheet_return':
                            if (mailTypeInput.value === '<?php echo SpiritUserClass::MAIL_TYPE_SHEET_RETURN_REPORT; ?>') {
                                shouldSubmit = true;
                            }
                            break;
                        case 'payment_confirm':
                            if (mailTypeInput.value === '<?php echo SpiritUserClass::MAIL_TYPE_PAYMENT_CONFIRM_REPORT; ?>') {
                                shouldSubmit = true;
                            }
                            break;
                        case 'schedule_decision':
                            if (mailTypeInput.value === '<?php echo SpiritUserClass::MAIL_TYPE_SCHEDULE_DECISION_REPORT; ?>') {
                                shouldSubmit = true;
                            }
                            break;
                        case 'sales_send':
                            if (mailTypeInput.value === '<?php echo SpiritUserClass::MAIL_TYPE_SALES_SEND_REPORT; ?>') {
                                shouldSubmit = true;
                            }
                            break;
                    }
                    
                    if (shouldSubmit) {
                        form.submit();
                        break;
                    }
                }
            }
        }
    }

    // プロフィールアコーディオン切り替え
    function toggleProfileAccordion() {
        const content = document.getElementById('profileAccordionContent');
        const icon = document.querySelector('.accordion-icon-profile');
        
        if (content.style.display === 'none') {
            content.style.display = 'block';
            icon.textContent = '▼';
        } else {
            content.style.display = 'none';
            icon.textContent = '▶';
        }
    }

    // 管理詳細アコーディオン切り替え
    function toggleManagementAccordion() {
        const content = document.getElementById('managementAccordionContent');
        const icon = document.querySelector('.accordion-icon-management');
        
        if (content.style.display === 'none') {
            content.style.display = 'block';
            icon.textContent = '▼';
        } else {
            content.style.display = 'none';
            icon.textContent = '▶';
        }
    }

    // 質問要項アコーディオン切り替え
    function toggleQuestionAccordion() {
        const content = document.getElementById('questionAccordionContent');
        const icon = document.querySelector('.accordion-icon-question');
        
        if (content.style.display === 'none') {
            content.style.display = 'block';
            icon.textContent = '▼';
        } else {
            content.style.display = 'none';
            icon.textContent = '▶';
        }
    }

    // 追加画像アコーディオン切り替え
    function toggleAdditionalAccordion() {
        const content = document.getElementById('additionalAccordionContent');
        const icon = document.querySelector('.accordion-icon-additional');
        
        if (content.style.display === 'none') {
            content.style.display = 'block';
            icon.textContent = '▼';
        } else {
            content.style.display = 'none';
            icon.textContent = '▶';
        }
    }

    // 結果レポート画像アコーディオン切り替え
    function toggleReportAccordion() {
        const content = document.getElementById('reportAccordionContent');
        const icon = document.querySelector('.accordion-icon-report');
        
        if (content.style.display === 'none') {
            content.style.display = 'block';
            icon.textContent = '▼';
        } else {
            content.style.display = 'none';
            icon.textContent = '▶';
        }
    }

    // 報告ボタンアコーディオン切り替え
    function toggleReportButtonsAccordion() {
        const content = document.getElementById('reportButtonsAccordionContent');
        const icon = document.querySelector('.accordion-icon-report-buttons');
        
        if (content.style.display === 'none') {
            content.style.display = 'block';
            icon.textContent = '▼';
        } else {
            content.style.display = 'none';
            icon.textContent = '▶';
        }
    }

    // ステータス変更確認
    function confirmStatusChange() {
        const select = document.getElementById('statusSelect');
        const selectedText = select.options[select.selectedIndex].text;
        
        if (confirm('会員ステータスを「' + selectedText + '」に変更しますか？')) {
            document.getElementById('statusChangeForm').submit();
        }
    }

    // 一番上に戻るボタンのクリック処理
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
    </script>

    <!-- 一番上に戻るボタン -->
    <button class="scroll-to-top-btn" onclick="scrollToTop()" title="一番上に戻る">↑</button>

    <style>
    .accordion-header-profile {
        transition: background-color 0.2s ease;
    }
    
    .accordion-header-profile:hover {
        background-color: #f0f0f0;
    }
    
    .profile-accordion-content {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .accordion-icon-profile {
        transition: transform 0.3s ease;
        display: inline-block;
    }

    .accordion-header-management {
        transition: background-color 0.2s ease;
    }
    
    .accordion-header-management:hover {
        background-color: #f0f0f0;
    }
    
    .management-accordion-content {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .accordion-icon-management {
        transition: transform 0.3s ease;
        display: inline-block;
    }

    .accordion-header-question {
        transition: background-color 0.2s ease;
    }
    
    .accordion-header-question:hover {
        background-color: #f0f0f0;
    }
    
    .question-accordion-content {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .accordion-icon-question {
        transition: transform 0.3s ease;
        display: inline-block;
    }

    .accordion-header-additional {
        transition: background-color 0.2s ease;
    }
    
    .accordion-header-additional:hover {
        background-color: #f0f0f0;
    }
    
    .additional-accordion-content {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .accordion-icon-additional {
        transition: transform 0.3s ease;
        display: inline-block;
    }

    .accordion-header-report {
        transition: background-color 0.2s ease;
    }
    
    .accordion-header-report:hover {
        background-color: #f0f0f0;
    }
    
    .report-accordion-content {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .accordion-icon-report {
        transition: transform 0.3s ease;
        display: inline-block;
    }

    .accordion-header-report-buttons {
        transition: background-color 0.2s ease;
    }
    
    .accordion-header-report-buttons:hover {
        background-color: #f0f0f0;
    }
    
    .report-buttons-accordion-content {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .accordion-icon-report-buttons {
        transition: transform 0.3s ease;
        display: inline-block;
    }

    /* 一番上に戻るボタン */
    .scroll-to-top-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background-color: #17a2b8;
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-size: 24px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        display: block;
        transition: all 0.3s ease;
        opacity: 0.9;
    }

    .scroll-to-top-btn:hover {
        background-color: #138496;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        opacity: 1;
    }

    .scroll-to-top-btn:active {
        transform: translateY(-1px);
    }
    </style>