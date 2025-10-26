<?php 

    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritQuestionDispClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritInputCustomizeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSalesClass.php");
       
    $spirit_sheet_data = new spiritSheetClass(); //管理データ

     //ユーザークラス
    $userClass = new SpiritUserClass(); //ユーザー管理
    //表示データ
    $disp_questiont_class = new SpiritQuestionDispClass(); //表示データ
    //個人データ
    $spirit_customize_data = new SpiritInputCustomizeClass(); //文字データ

    //物販
    $spirit_sales_data = new SpiritSalesClass(); //物販管理

    $decode_before_data = null;


    //ユーザーの全データを取得
	$users = get_users( array('orderby'=>'ID','order'=>'ASC') ); 

    
    //var_dump($_POST);

    // バックアップデータ作成
    if(isset($_POST['do_back_up'])){

        $res = setChangeUserData($check_user_id,$_POST);
        if($res != 0) {

            if(isset($res->errors)){

                $result_msg = "前日データに戻す事が出来ませんでした";
                
            }else{

                $result_msg = "前日データ変更完了";
            }
        } else {
            $result_msg = "前日データに戻す事が出来ませんでした";
        }
    }
    if(isset($_POST['back_up_make'])){

        echo saveUserData(10);
    }
    

    //質問のフィールド
     $type = get_field('acf_acf_purespirit_type',$_GET["sheet_name"]);

    //現在の質問番号を取得
    $spiritQuestionArray = $spirit_sheet_data->getSpiritQuestion($type);


    //保存
    if(isset($_POST["save_data"]))
    {
        //var_dump($_POST);
        $fields = acf_get_fields(SpiritSheetClass::SPIRITSHEET_FIELD_NUM);
        $update_field = array();    // 更新情報

        
        // シート編集履歴
        $id = $_GET["sheet_name"];
        if(CheckPostData($id,"acf_purespirit_requested_date")) $update_field['依頼日'] = true;
        if(CheckPostData($id,"acf_purespirit_request_confirmation_date")) $update_field['依頼確定日'] = true;
        if(CheckPostData($id,"acf_purespirit_payment_date")) $update_field['決済日'] = true;
        if(CheckPostData($id,"acf_purespirit_execution_confirmation_date")) $update_field['実行予定日'] = true;
        if(CheckPostData($id,"acf_purespirit_execution_date")) $update_field['実行日'] = true;
        if(CheckPostData($id,"acf_purespirit_status")) $update_field['管理者状況ステータス'] = true;
        if(CheckPostData($id,"acf_purespirit_user_status")) $update_field['会員状況ステータス'] = true;
        if(CheckPostData($id,"acf_previous_payment_type")) $update_field['支払い方法'] = true;
        if(CheckPostData($id,"acf_applicant")) $update_field['申込者'] = true;
        if(CheckPostData($id,"acf_purespirit_add_text")) $update_field['依頼内容追記'] = true;
        if(CheckPostData($id,"acf_purespirit_return_input_text")) $update_field['再提出依頼表示'] = true;
        if(CheckPostData($id,"acf_agate_name_check")) $update_field['a-gate 氏名等確認'] = true;
        if(CheckPostData($id,"acf_agate_result_img")) $update_field['a-gate Eマーク送付画像'] = true;

        //送付先変更
        if(isset($_POST["post_address"]))
        {
            $post_address_data = $userClass->getUserPostAddressArray($_POST["post_address"]);
            update_field("acf_previous_post_billing_postcode", $post_address_data["郵送先郵便番号"], $_GET["sheet_name"]);
            update_field("acf_previous_billing_city", $post_address_data["郵送先住所1"], $_GET["sheet_name"]);
            update_field("acf_previous_billing_address_1", $post_address_data["郵送先住所2"], $_GET["sheet_name"]);
            update_field("acf_previous_billing_first_name", $post_address_data["郵送先名前"], $_GET["sheet_name"]);
            $update_field['送付先変更'] = true;
        }


    
        //物販のキャンセル
        $spirit_sales_data->cancelSalesPage($_GET["sheet_name"],$_POST);

        foreach( $fields as $field ){
            

            if($field['name'] != "acf_purespirit_array")
            {
                if(isset($_POST[ $field['name'] ] ))
                {
                    update_field($field['name'], $_POST[ $field['name'] ], $_GET["sheet_name"]);
                }
            }
            //echo $field['label'] . " : " . $field['value'];
        }



        //変更があった場合は最終保存を入れる
        if(count($update_field) > 0)
        {

            date_default_timezone_set('Asia/Tokyo');
            $today = date("Y-m-d H:i:s");

            update_field("acf_previous_change_last_day",$today, $_GET["sheet_name"]);
            update_field("acf_previous_change_last_id", get_current_user_id(), $_GET["sheet_name"]);
        }

        //会員ステータスもしくは、管理者ステータスがキャンセルだった場合、どちらもキャンセルに修正する
        if(isset($_POST['acf_purespirit_status']) && $_POST['acf_purespirit_status'] == SpiritUserClass::ADMIN_STATUS_CANCEL){
            update_field('acf_purespirit_user_status', SpiritUserClass::MEMBER_STATUS_CANCEL, $_GET["sheet_name"]);
        }
        if(isset($_POST['acf_purespirit_user_status']) && $_POST['acf_purespirit_user_status'] == SpiritUserClass::MEMBER_STATUS_CANCEL){
            update_field('acf_purespirit_status', SpiritUserClass::ADMIN_STATUS_CANCEL, $_GET["sheet_name"]);
        }
        
        //var_dump($_POST);
    }


    //リモート保存
    if(isset($_POST["remote_save_data"]))
    {

        //質問関連
        $anser_number = array();
        $anser = "";

        $saveSheetData =  $userClass->getUserSpritApplicantSheet($check_user_id,$_GET["sheet_name"]);

        //リモート浄霊だけ別シート
        $remote_sheet_data  = $saveSheetData[$_GET["sheet_name"]]["リモート浄霊情報"];


       // var_dump($remote_sheet_data);

        $remote_sheet_id = $saveSheetData[$_GET["sheet_name"]]["リモート浄霊"]; //sheetID
        

       

        for ($i=1;$i<=count($remote_sheet_data);$i++) {


            if( $remote_sheet_data[$i]["ID"] == "")
            {
                break;
            }
            $spirit_sheet_data->saveRemoteSpritTargetSheetData(  'acf_remote_sprit_sheet_target_execution_schedule_date' ,  $remote_sheet_data[$i]["ID"]  , $_POST[ 'acf_remote_sprit_sheet_target_execution_schedule_date' ][$i - 1] );//実行予定日
            $spirit_sheet_data->saveRemoteSpritTargetSheetData(  'acf_remote_sprit_sheet_target_execution_date' ,  $remote_sheet_data[$i]["ID"]  , $_POST[ 'acf_remote_sprit_sheet_target_execution_date' ][$i - 1] );//実行日
            $spirit_sheet_data->saveRemoteSpritTargetSheetData(  'acf_remote_sprit_sheet_target_input_date' ,  $remote_sheet_data[$i]["ID"]  , $_POST[ 'acf_remote_sprit_sheet_target_input_date' ][$i - 1] );//入力完了日
            $spirit_sheet_data->saveRemoteSpritTargetSheetData(  'acf_remote_sprit_sheet_target_status' ,  $remote_sheet_data[$i]["ID"]  , $_POST[ 'acf_remote_sprit_sheet_target_status' ][$i - 1] );//ステータス
            $spirit_sheet_data->saveRemoteSpritTargetSheetData(  'acf_remote_sprit_sheet_movie_choice' ,  $remote_sheet_data[$i]["ID"]  , $_POST[ 'acf_remote_sprit_sheet_movie_choice_' . $i ] );//選択画像


            
        }


        //質問番号を上書き
        $spirit_sheet_data->setSpiritSheetAnswer( $_GET["sheet_name"] , $anser_number);
       

    }

    

    //スケジュール保存
    if(isset($_POST["save_schedule"]))
    {
        update_field("acf_previous_schedule_number", $_POST[ "save_schedule" ], $_GET["sheet_name"]);


    }



    $userData = $userClass->getUserAcountData($check_user_id);//ユーザー情報
    $sheet_data = $userClass->getUserSpritApplicantSheet($check_user_id,$_GET["sheet_name"]);

    ///var_dump($_POST);

?>

    <form action="<?php echo getURLSetSlag("admin-spirit-detail"); ?>?user_id=<?php echo $_GET['user_id'];?>&sheet_edit=<?php echo $_GET["sheet_edit"];?>&sheet_name=<?php echo $_GET['sheet_name'];?>" method="post">
        <input type="hidden" name="back_up_make" value="back_up_make">
        <input type="hidden" name="do_change_user" value="do_change_user">
        <button>バックアップデータ作成</button>
    </form>
    <div class="">

        <div class="admin-spirit-title-box">
            <div class="admin-spirit-menu-title"><?php echo $check_user_data['input_last_name'] . " " . $check_user_data['input_first_name'] . " " .  get_field('acf_pure_spirit_title',get_field('acf_acf_purespirit_type',$_GET["sheet_name"]));?>　　シート編集</div>
        </div>
    
        
        <div style="display: flex; justify-content: flex-start; gap: 10px;">

            

            <button type="button" id="form-back" class="form-btn" style="margin: 0px;display: inline-block;margin-bottom: 15px;margin-top: 0;width: 220px;background-color: antiquewhite;height: 40px;">
                <a href="<?php echo getURLSetSlag("admin-spirit-detail"); ?>?user_id=<?php echo $_GET['user_id'];?>&sheet_name=<?php echo $_GET['sheet_name'];?>">施術詳細に戻る</a>
            </button>

            <form action="<?php echo getURLSetSlag("users/user-spirit-question-edit");?>?check_user=<?php echo $check_user_id; ?>" method="post" target="_blank" style="display: inline;">
                <button type="submit"  class="admin-spirit-question-edit-button" style="font-size: 16px;width: 220px;height: 40px;cursor: pointer;">質問シート編集</button>
                <input type="hidden"  name="sheet_id" value="<?php echo $_GET["sheet_name"]; ?>">
            </form>

            <button type="button" id="form-back" class="form-btn" style="width: 220px; height: 40px;margin: 0px;">
                <a href="<?php echo getURLSetSlag("admin-member-edit").'?user_id='.$check_user_id; ?>">シートTOPへ戻る</a>
            </button>

        </div>

            

           
          
        <?php if($decode_before_data != NULL){?>
            <div class="admin-btn-area btn-flex" style="">
                <button type="button" id="form-back" class="form-btn gray" onclick='click_modal("<?php echo $decode_before_data["back_up_date"]?> 時点のデータに戻ります。\n入力されていた情報は戻ってしまいますが\n宜しいでしょうか？", "post_back_user");' >
                    <?php echo $decode_before_data['back_up_date']?>の情報に戻す
                </button>
            </div>
        <?php } ?>
        

        
        <div class="admin-spirit-subtitle-box accordion-header-profile" style="width: 100%; cursor: pointer;" onclick="toggleProfileAccordion()">
            <div class="admin-spirit-menu-title" style="display: flex; justify-content: space-between; align-items: center;">
                <span>プロフィール</span>
                <span class="accordion-icon-profile" style="font-size: 20px;">▶</span>
            </div>
        </div>

        <div id="profileAccordionContent" class="profile-accordion-content" style="display: none;">
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
            <?php if(!judgeUserRole()){?>
                <form action="<?php echo getURLSetSlag("admin-member-edit"); ?>?user_id=<?php echo $_GET['user_id'];?>" method="post" style="margin: 0;">
                    <input type="hidden" name="change_user" value="change_user" id="">
                    <button type="submit" id="form-submit" class="form-btn" style="width: 220px; height: 40px;margin: 0px;background-color: beige;">プロフィールを編集する</button>
                </form>
            <?php } ?>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">ID</div>　<?php echo $check_user_data['input_unique_id']; ?>
        </div>
    
        <div class="user-table-flex">
            <div class="user-table-item">名前</div>
            <?php echo  $check_user_data['input_last_name']; ?> <?php echo  $check_user_data['input_first_name'];?> ( <?php echo $check_user_data['input_last_name_kana'];?> <?php echo $check_user_data['input_first_name_kana'];?> )
        </div>
    
    
        <div class="user-table-flex">
            <div class="user-table-item">生年月日・性別</div>

                <?php if( $check_user_data['input_user_born_year'] != ""){?>
                    <?php echo  $check_user_data['input_user_born_year'];?>年<?php echo  $check_user_data['input_user_born_month'];?>月<?php echo  $check_user_data['input_user_born_day'];?>日
                <?php } ?>
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
        </div><!-- プロフィールアコーディオンの終了 -->
    </div>


    
           

    <form action="<?php echo  getURLSetSlag( "admin-spirit-detail" );?>?user_id=<?php echo $_GET["user_id"];?>&sheet_edit=<?php echo $_GET["sheet_edit"];?>&sheet_name=<?php echo $_GET["sheet_name"];?>" name="save_data" method="post" id="save_data">

        <?php if(judgeUserRole()){?>
            <input type="hidden" name="teacher_comment">
        <?php } ?>


        <?php /* シート詳細 */?>
        <?php if(!judgeUserRole()){?>
        
            <div class="admin-spirit-subtitle-box accordion-header-sheet" style="width: 100%; cursor: pointer;" onclick="toggleSheetAccordion()">
                <div class="admin-spirit-menu-title" style="display: flex; justify-content: space-between; align-items: center;">
                    <span><?php echo get_field('acf_pure_spirit_title',get_field('acf_acf_purespirit_type',$_GET["sheet_name"]));?> シート詳細</span>
                    <span class="accordion-icon-sheet" style="font-size: 20px;">▼</span>
                </div>
            </div>

            <div id="sheetAccordionContent" class="sheet-accordion-content" style="display: block;">
                <div style="text-align: right; margin-top: 10px;">
            最終更新:<?php echo $sheet_data[$_GET["sheet_name"]]["最終変更日年月日"]; ?> <?php echo $sheet_data[$_GET["sheet_name"]]["最終変更者名"]; ?>
        </div>

        <div style="text-align: right;margin-top:30px;" >
            <button type="button" id="form-back" class="form-btn" style="display: inline-block;margin-bottom: 15px;margin-top: 0;width: 220px;background-color: antiquewhite;height: 40px;">
                <a href="<?php echo getURLSetSlag("admin-spirit-detail"); ?>?user_id=<?php echo $_GET['user_id'];?>&sheet_name=<?php echo $_GET['sheet_name'];?>">施術詳細に戻る</a>
            </button>
        </div>


        <button class="admin-spirit-edit-list-edit-button" type="submit" id="spirit-save-btn" style="margin-top: 55px;">シートを保存する</button>


        <div class="user-table-flex" style="margin-top:30px;">
            <div class="user-table-item">作成日</div>
            <?php 
                echo date('Y/m/d H:i:s', get_field('acf_purespirit_unixtime',$_GET["sheet_name"]));
            ?>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">依頼日</div>
            <input type="date" id="" name="acf_purespirit_requested_date" value="<?php echo get_field('acf_purespirit_requested_date',$_GET["sheet_name"]); ?>">
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">依頼確定日</div>
            <input type="date" id="" name="acf_purespirit_request_confirmation_date" value="<?php echo get_field('acf_purespirit_request_confirmation_date',$_GET["sheet_name"]); ?>">
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">決済日</div>
            <input type="date" id="" name="acf_purespirit_payment_date" value="<?php echo get_field('acf_purespirit_payment_date',$_GET["sheet_name"]); ?>">
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">実行予定日</div>
            <div>
                 <div style="margin-top: 18px;"><input type="date" id="" name="acf_purespirit_execution_confirmation_date" value="<?php echo get_field('acf_purespirit_execution_confirmation_date',$_GET["sheet_name"]); ?>"> </div>
                 <div style="font-size: 11px;font-weight: 600;">
                    実行日を入力していない状態で実行予定日を入力していると、<br>会員状況ステータスが「浄霊依頼中」の際に「〇〇年〇月〇日～〇〇年〇月〇予定」と表示されます。<br>
                    会員状況ステータスが「浄霊依頼中」で予定日が入力されていない場合は、「施術日未確定」と表示されます。
                </div>
             </div>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">実行日</div>
            <div>
                <div style="margin-top: 18px;"><input type="date" id="" name="acf_purespirit_execution_date" value="<?php echo get_field('acf_purespirit_execution_date',$_GET["sheet_name"]); ?>"></div>
                <div style="font-size: 11px;font-weight: 600;">
                    実行日を<font color="red">入力している状態</font>でも実行予定日の日で「〇〇年〇月〇日～〇〇年〇月〇予定」と会員には表示されます。<br>
                    スケジュールが決まっている場合は、そちらの日が優先されます。
                </div>
             </div>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">管理者　状況ステータス</div>
                
                <?php 

                     $status = get_field('acf_purespirit_status',$_GET["sheet_name"]);

                    if($status == SpiritUserClass::ADMIN_STATUS_CANCEL){
                        echo "<font color='red'>キャンセル</font>";
                    }
                    else{

                        $spiritStatusArray = $spirit_sheet_data->getSpiritAdminStatus('cpt_spirit_status');
                        
                     //echo $status;
                ?>
                    <select name="acf_purespirit_status" id="">
                        <option value="">未設定</option>

                        <?php
                            foreach ($spiritStatusArray as $value) {
                        ?>
                                <option value="<?php echo $value["ID"];?>" <?php if($status == $value["ID"]){ echo "selected"; }?>><?php echo $value["title"]?></option>
                        <?php } ?>

                    </select>
                <?php  } ?>

           
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">会員　状況ステータス</div>
        
                <?php 

                     $status = get_field('acf_purespirit_user_status',$_GET["sheet_name"]);


                    if($status == SpiritUserClass::MEMBER_STATUS_CANCEL){
                        echo "<font color='red'>キャンセル</font>";
                    }
                    else{       

                        $spiritStatusArray = $spirit_sheet_data->getSpiritAdminStatus('cpt_spirit_usestatus');

                     //echo $status;
                ?>
                    <select name="acf_purespirit_user_status" id="">

                        <option value="">未設定</option>

                        <?php
                            foreach ($spiritStatusArray as $value) {
                        ?>
                                <option value="<?php echo $value["ID"];?>" <?php if($status == $value["ID"]){ echo "selected"; }?>><?php echo $value["title"]?></option>
                        <?php } ?>
                    </select>
                <?php  } ?>

            </select>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">キャンセル日</div>
            <div>
                <div style="margin-top: 18px;"><input type="date" id="" name="acf_previous_cancel_day" value="<?php echo get_field('acf_previous_cancel_day',$_GET["sheet_name"]); ?>"></div>
             </div>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">支払い方法</div>
            <select name="acf_previous_payment_type" id="">

                <?php
                    foreach ($userClass->payment_type_field as $key => $value) {
                                                
                ?>
                        <option value="<?php echo $key;?>" <?php if($sheet_data[$_GET["sheet_name"]]["支払いタイプ"] == $key){ echo "selected"; }?>><?php echo $value;?></option>
                <?php } ?>
             </select>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">申込者</div>
            <select name="acf_applicant" id="">
                <?php 

                    $status = get_field('acf_applicant',$_GET["sheet_name"]);
                ?>

                 <option value="">未設定</option>

                <?php
                    foreach ($users as $row) {
                        
                        $is_delete = get_user_meta($row->ID,"is_delete",true);
                        if($is_delete) continue;
                        if($row->roles[0] != "subscriber") continue;
                ?>
                        <option value="<?php echo $row->ID;?>" <?php if($status == $row->ID){ echo "selected"; }?>><?php echo get_the_author_meta('last_name',$row->ID);?>　<?php echo get_the_author_meta('first_name',$row->ID);?></option>
                <?php } ?>
             </select>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">単価</div>
            <input type="number"  name="acf_purespirit_price"  style="text-align: right;" value="<?php echo $sheet_data[$_GET["sheet_name"]]["価格元"]; ?>">　円
        </div>

         <?php if( $sheet_data[$_GET["sheet_name"]]["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){ ?>
            <div class="user-table-flex">
                <div class="user-table-item">個数</div>   
                <?php /*
                <input type="number"  name="acf_previous_quantity"  style="text-align: right;" value="<?php echo $sheet_data[$_GET["sheet_name"]]["販売個数"]; ?>">　個
                */ ?>
                <?php echo $sheet_data[$_GET["sheet_name"]]["販売個数"]; ?>個　<font color="red">(個数の変更はできません)</font>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">追跡番号画像</div>
                <?php $img_post_acf_id =$_GET["sheet_name"]; ?>
                <?php $existing_image_url =get_field('acf_previous_sales_post_img',$_GET["sheet_name"]); ?>

                <button type="button" id="upload_image_button_sales" class="button">画像をアップロード</button>
                <input type="hidden" name="acf_previous_sales_post_img" id="acf_image_field_sales" value="<?php echo $existing_image_url; ?>">
                <img id="uploaded_image_preview_sales" src="<?php echo $existing_image_url; ?>" style="margin-left: 10px;max-width: 300px;<?php echo $existing_image_url ? '' : 'display: none;'; ?>">
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">送付先</div>
                <div>
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
                

                    <?php  $post_address_list = $userClass->getUserPostAddressList($check_user_id); ?>

                    <?php if(count($post_address_list) > 0){?>

                        <div>
                            <div class="post-address-selection" style="margin-top: 50px;">
                                <div style="font-size: 14px;font-weight: 600;margin-bottom: 10px;">変更する場合は、郵送先を選択してください。</div>
                                <?php foreach($post_address_list as $post_key => $post_address_data){?>
                                    <div class="post-address-option"  style="margin-top: 20px;">
                                        <label class="post-address-radio-label">
                                            <input type="radio" name="post_address" value="<?php echo $post_key; ?>" class="post-address-radio"><?php echo $post_address_data["郵送先名前"]; ?>
                                            <div class="post-address-radio-content">
                                                <div class="post-address-radio-info">
                                                    <div class="post-address-radio-name">
                                                        
                                                    </div>
                                                    <div class="post-address-radio-address">
                                                        〒<?php echo $post_address_data["郵送先郵便番号"]; ?><br>
                                                        <?php echo $post_address_data["郵送先住所1"]; ?><?php echo $post_address_data["郵送先住所2"] ? '　' . $post_address_data["郵送先住所2"] : ''; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
        <?php } ?>


        <div class="user-table-flex">
            <div class="user-table-item">事務局メモ（依頼内容追記）</div>
            <div>
                <textarea name="acf_purespirit_add_text" id="" rows="5" cols="10" style="width: 846px;"><?php echo  get_field('acf_purespirit_add_text',$_GET["sheet_name"]);?></textarea>
             </div>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">再提出依頼表示</div>
            <div>
                 <div><textarea name="acf_purespirit_return_input_text" id="" rows="5" cols="10" style="width: 846px;"><?php echo  get_field('acf_purespirit_return_input_text',$_GET["sheet_name"]);?></textarea></div>
                 <div style="font-size: 11px;font-weight: 600;color: red;font-size: 29px;">会員ステータスが「必要事項再入力」の際に会員の質問ページに表示されます</div>
            </div>
        </div>

        <div class="user-table-flex">
            <div class="user-table-item">氏名等確認</div> 
            <select name="acf_agate_name_check" id="">
                <option value=""  <?php if(get_field('acf_agate_name_check',$_GET["sheet_name"]) == ""){ echo "selected"; }?>>（未選択）</option>
                <option value="1"  <?php if(get_field('acf_agate_name_check',$_GET["sheet_name"]) != ""){ echo "selected"; }?>>済</option>
            </select>
        </div>

        <?php if( $sheet_data[$_GET["sheet_name"]]["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){ ?>
            <div class="user-table-flex">
                <div class="user-table-item">相談者への支払い額</div>   
                <?php  
                
                    $exe_pay_amount = get_field('acf_previous_execution_pay',$_GET["sheet_name"]);

                    if($exe_pay_amount == ""){
                        $exe_pay_amount = 0;
                    }
                
                ?>

                <input type="number" id="" name="acf_previous_execution_pay"  value="<?php echo $exe_pay_amount; ?>">円
            </div>
        <?php } ?>

        <div class="user-table-flex">
            <div class="user-table-item">Eマーク送付画像</div>
            <?php $img_post_acf_id =$_GET["sheet_name"]; ?>
            <?php $existing_image_url =get_field('acf_agate_result_img',$_GET["sheet_name"]); ?>

            <button type="button" id="upload_image_button_agate" class="button">画像をアップロード</button>
            <input type="hidden" name="acf_agate_result_img" id="acf_image_field_agate" value="<?php echo $existing_image_url; ?>">
            <img id="uploaded_image_preview_agate" src="<?php echo $existing_image_url; ?>" style="margin-left: 10px;max-width: 300px;<?php echo $existing_image_url ? '' : 'display: none;'; ?>">
        </div>

        
        <input type="hidden" name="save_data"  value="save_data" />
        <button class="admin-spirit-edit-list-edit-button" type="submit" id="spirit-save-btn" style="margin-top: 55px;">シートを保存する</button>

        </div><!-- シート詳細アコーディオンの終了 -->
       
        <?php } ?>

    </form>



   <?php if($sheet_data[$_GET["sheet_name"]]["スケジュール有無"] != ""){  ?>

        <div class="admin-spirit-subtitle-box accordion-header-schedule" style="margin-bottom:0;width: 100%; cursor: pointer;" onclick="toggleScheduleAccordion()">
            <div class="admin-spirit-menu-title" style="display: flex; justify-content: space-between; align-items: center;">
                <span>スケジュール</span>
                <span class="accordion-icon-schedule" style="font-size: 20px;">▶</span>
            </div>
        </div>

        <div id="scheduleAccordionContent" class="schedule-accordion-content" style="display: none;">

            <?php if($sheet_data[$_GET["sheet_name"]]["スケジュール"] != ""){  ?>

                <div style="text-align: right;">
                    <div class="admin-spirit-question-edit-button-area" style="display: inline-block;margin-bottom: 10px;">
                        <form action="<?php echo getURLSetSlag("admin-spirit-schedule-list");?>" method="post">
                            <button type="submit"  class="admin-spirit-question-edit-button" style="background-color: gainsboro;width: 220px;height: 40px;">スケジュール変更</button>
                            <input type="hidden"  name="choice_user" value="<?php echo $check_user_id;?>">
                            <input type="hidden"  name="choice_sheet" value="<?php echo $_GET["sheet_name"];?>">
                            <input type="hidden"  name="choice_page" value="">
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

            <?php } ?>

        </div><!-- スケジュールアコーディオンの終了 -->

    <?php } ?>


    <?php /* 質問事項 */ ?>
    
    <div class="admin-spirit-subtitle-box accordion-header-question" style="width: 100%; cursor: pointer;" onclick="toggleQuestionAccordion()">
        <div class="admin-spirit-menu-title" style="display: flex; justify-content: space-between; align-items: center;">
            <span>質問要項</span>
            <span class="accordion-icon-question" style="font-size: 20px;">▶</span>
        </div>
    </div>

    <div id="questionAccordionContent" class="question-accordion-content" style="display: none;">

        <div style="text-align: right; margin-top: 10px;">
        <div class="admin-spirit-question-edit-button-area" style="display: inline-block;">
            <form action="<?php echo getURLSetSlag("users/user-spirit-question-edit");?>?check_user=<?php echo $check_user_id; ?>" method="post" target="_blank" style="display: inline;">
                <button type="submit"  class="admin-spirit-question-edit-button" style="font-size: 16px;width: 220px;height: 40px;cursor: pointer;">質問シート編集</button>
                <input type="hidden"  name="sheet_id" value="<?php echo $_GET["sheet_name"]; ?>">
            </form>
        </div>
    </div>


    <form action="<?php echo  getURLSetSlag( "admin-spirit-detail" );?>?user_id=<?php echo $_GET["user_id"];?>&sheet_edit=<?php echo $_GET["sheet_edit"];?>&sheet_name=<?php echo $_GET["sheet_name"];?>" name="remote_save" method="post" id="remote_save">

        <input type="hidden" name="remote_save_data"  value="remote_save_data" />


    <?php
        
        $type = get_field('acf_acf_purespirit_type',$_GET["sheet_name"]);

        $sheet_data = $userClass->getUserSpritApplicantSheet($check_user_id,$_GET["sheet_name"]);

        //if( $type != 77)//リモート浄霊以外
        {

            //現在の質問番号を取得
            $spiritQuestionArray = $spirit_sheet_data->getSpiritQuestion($type);

            //回答配列を取得
            $anser = $spirit_sheet_data->getSpiritSheetAnswer( $_GET["sheet_name"] );

            $count = 0;
        
            if($spiritQuestionArray != "")
            {
                $customize_data = $spirit_customize_data->getInputCustomizeData( $sheet_data[$_GET["sheet_name"]]["質問"] );

                if($sheet_data[$_GET["sheet_name"]]["対象者有無"]){ //対象者選択有

                    $personal_input_array = $spirit_customize_data->getPersonalDataInput( $sheet_data[$_GET["sheet_name"]]["質問"] );

?>
                        <div class="admin-spirit-subtitle-box" style="width: 300px;margin-top: 20px;">
                        <div class="admin-spirit-menu-title">対象者</div>
                    </div>

                    <?php if($sheet_data[$_GET["sheet_name"]]["対象者"]["対象者情報"] == false){ ?>

                        <div style="font-size: 30px;margin-bottom: 30px;">申込者と同じ</div>

                    <?php }else{ ?>

                        <?php foreach ($personal_input_array as $key => $value) { //対象者と申込者が違う
                           
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
                    
                <?php if(isset($spiritQuestionArray[$type])){

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
<?php
                    }
                }
            } 
        }
?>

    </form>
</div><!-- 質問要項アコーディオンの終了 -->

</div>

</div>











    <?php DispChangeLog($check_user_id); ?>
  

<script>
       
     function validateNumberInput(input) {
            input.value = input.value.replace(/[^0-9]/g, "");
        }
        
        //２個の値を３つめにまとめる（名前用）
        function updateSum(id_1, id_2,id_3) {
           
            var text_1 = document.getElementById(id_1);
            var text_2 = document.getElementById(id_2);
           

            document.getElementById(id_3).value = text_1.value + " " + text_2.value;
        }

        //３個の値を４つめにまとめる（電話用）
        function updateTel(id_1, id_2,id_3,id_4) {
           
            var text_1 = document.getElementById(id_1);
            var text_2 = document.getElementById(id_2);
            var text_3 = document.getElementById(id_3);
           

            if(text_1 != "" || text_2 != "" || text_3 != ""){
                document.getElementById(id_4).value = text_1.value + "-" + text_2.value + "-" + text_3.value;
            }
            else{
                document.getElementById(id_4).value = "";
            }
        }
        
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

  <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/user-disp-utility.js?<?php echo date('Ymd H:i:s'); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var adminStatusSelect = document.querySelector('select[name="acf_purespirit_status"]');
    var userStatusSelect = document.querySelector('select[name="acf_purespirit_user_status"]');
    var saveBtn = document.getElementById('spirit-save-btn');
    var form = saveBtn.closest('form');

    // 初期値を保存
    var initialAdminStatus = adminStatusSelect ? adminStatusSelect.value : '';
    var initialUserStatus = userStatusSelect ? userStatusSelect.value : '';

    if (saveBtn && adminStatusSelect && userStatusSelect) {
        saveBtn.addEventListener('click', function(e) {
            var currentAdminStatus = adminStatusSelect.value;
            var currentUserStatus = userStatusSelect.value;
            var showConfirm = false;

            // 管理者ステータスが44に変更された場合
            if (initialAdminStatus !== '44' && currentAdminStatus === '44') {
                showConfirm = true;
            }
            // 会員ステータスが7827に変更された場合
            if (initialUserStatus !== '7827' && currentUserStatus === '7827') {
                showConfirm = true;
            }

            if (showConfirm) {
                if (!window.confirm('キャンセルすると戻せませんが宜しいですか？')) {
                    e.preventDefault();
                    return false;
                }
            }
            // それ以外はそのままsubmit
        });
    }

    // 郵送先ラジオボタンのトグル機能（修正版）
    var postAddressRadios = document.querySelectorAll('.post-address-radio');
    var lastSelectedValue = null;

    postAddressRadios.forEach(function(radio) {
        radio.addEventListener('mousedown', function(e) {
            // マウスダウン時に現在の状態を記録
            if (this.checked) {
                this.dataset.wasChecked = 'true';
            } else {
                this.dataset.wasChecked = 'false';
            }
        });

        radio.addEventListener('click', function(e) {
            // クリック時に以前の状態をチェック
            if (this.dataset.wasChecked === 'true') {
                // 既に選択されていた場合は選択を解除
                setTimeout(() => {
                    this.checked = false;
                    lastSelectedValue = null;
                }, 0);
            } else {
                // 新しく選択された場合
                lastSelectedValue = this.value;
            }
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 追跡番号画像アップロード
    const uploadButtonSales = document.getElementById('upload_image_button_sales');
    const imageFieldSales = document.getElementById('acf_image_field_sales');
    const imagePreviewSales = document.getElementById('uploaded_image_preview_sales');

    if (uploadButtonSales) {
        uploadButtonSales.addEventListener('click', function() {
            // WordPressメディアライブラリを開く
            const frame = wp.media({
                title: '画像を選択',
                button: {
                    text: '選択'
                },
                multiple: false
            });

            frame.on('select', function() {
                const attachment = frame.state().get('selection').first().toJSON();
                imagePreviewSales.src = attachment.url;
                imagePreviewSales.style.display = 'inline-block';
                imageFieldSales.value = attachment.url;
            });

            frame.open();
        });
    }

    // Eマーク送付画像アップロード
    const uploadButtonAgate = document.getElementById('upload_image_button_agate');
    const imageFieldAgate = document.getElementById('acf_image_field_agate');
    const imagePreviewAgate = document.getElementById('uploaded_image_preview_agate');

    if (uploadButtonAgate) {
        uploadButtonAgate.addEventListener('click', function() {
            // WordPressメディアライブラリを開く
            const frame = wp.media({
                title: '画像を選択',
                button: {
                    text: '選択'
                },
                multiple: false
            });

            frame.on('select', function() {
                const attachment = frame.state().get('selection').first().toJSON();
                imagePreviewAgate.src = attachment.url;
                imagePreviewAgate.style.display = 'inline-block';
                imageFieldAgate.value = attachment.url;
            });

            frame.open();
        });
    }
});
</script>

<script>
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

// シート詳細アコーディオン切り替え
function toggleSheetAccordion() {
    const content = document.getElementById('sheetAccordionContent');
    const icon = document.querySelector('.accordion-icon-sheet');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.textContent = '▼';
    } else {
        content.style.display = 'none';
        icon.textContent = '▶';
    }
}

// スケジュールアコーディオン切り替え
function toggleScheduleAccordion() {
    const content = document.getElementById('scheduleAccordionContent');
    const icon = document.querySelector('.accordion-icon-schedule');
    
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
</script>

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

.accordion-header-sheet {
    transition: background-color 0.2s ease;
}

.accordion-header-sheet:hover {
    background-color: #f0f0f0;
}

.sheet-accordion-content {
    transition: all 0.3s ease;
    overflow: hidden;
}

.accordion-icon-sheet {
    transition: transform 0.3s ease;
    display: inline-block;
}

.accordion-header-schedule {
    transition: background-color 0.2s ease;
}

.accordion-header-schedule:hover {
    background-color: #f0f0f0;
}

.schedule-accordion-content {
    transition: all 0.3s ease;
    overflow: hidden;
}

.accordion-icon-schedule {
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
</style>