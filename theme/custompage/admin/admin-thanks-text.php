
<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritInputCustomizeClass.php");
    


    $spirit_sheet_data = new SpiritInputCustomizeClass(); //管理データ



    $question_num = 0;

    if(isset($_GET["type_id"]))
    {
        $question_num = $_GET["type_id"];
    }


    //保存
    if(isset($_POST["save_id"])){

        //チェックボックスだけ追加しないといけない

        $spirit_sheet_data->saveThanksCustomizeData( $_POST["save_id"] ,$_POST);

    }

    $customize_data = $spirit_sheet_data->getThanksCustomizeData( $question_num );

    //ない場合は新規作成
    if($customize_data == "")
    {
         $spirit_sheet_data->newThanksCustomizeData( $question_num  );
         $customize_data = $spirit_sheet_data->getThanksCustomizeData( $question_num );
    }

    //サンクスメールのIDを確認
    $mail_id =  $spirit_sheet_data->getIdThanksMail( $question_num  );


    
?>





<div class="admin-exorcism-area">


    <div class="admin-exorcism-button-area">

        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title" style="text-align: center;"><?php echo get_field('acf_pure_spirit_title',$question_num); ?>　サンクスページ設定</div></div>

        <div class="admin-preview-button-flex">
            <div class="admin-preview-button-area">
                <button class="admin-preview-button" type="button" onclick="submitForm()">プレビュー</button>
            </div>

            <div class="admin-preview-button-flex-box">
                <button class="admin-preview-button" type="button"  style="background-color: aliceblue;"  onclick="window.open('https://a-gate-kanri.com/wp-admin/post.php?post=<?php echo $mail_id; ?>&action=edit', '_blank')">サンクスメール設定</button>
            </div>
        </div>

        <div class="admin-inputcustomize-area">


             <form action="<?php echo  getURLSetSlag( "admin-thanks-text" );?>?type_id=<?php echo $question_num;?>" name="customize_input" method="post" id="customize_input">


                 <div class="admin-inputcustomize-note-area">

                    上から順番に表示されます。<br>
                    入力欄は「お問い合わせメールアドレス」と「テキスト差し込み３」の間になります。<br>
                 </div>

                  <?php if(isset($_POST["save_id"])){ ?>


                     <div class="admin-inputcustomize-save-area">保存しました</div>

                 <?php } ?>

                 <div class="admin-inputcustomize-input-tag-area">
                    <div class="admin-inputcustomize-input-tag-title">【入力タグ】</div>
                    <div class="admin-inputcustomize-input-tag">
                        <table class="admin-inputcustomize-input-tag-table">
                            <tr>
                                <td>・文字を赤文字にする場合は&lt;R&gt;～&lt;/R&gt;で囲ってください</td>　
                                <td>（例）&lt;R&gt;<font color="red">赤文字</font>&lt;/R&gt;<br></td>
                            </tr>
                            <tr>
                                <td>・文字を黒文字で太くする場合は&lt;B&gt;～&lt;/B&gt;で囲ってください</td>　
                                <td>（例）&lt;B&gt;<b>太文字</b>&lt;/B&gt;<br></td>
                            </tr>
                            <tr>
                                <td>・文字を黒文字でさらに太くする場合は&lt;B2&gt;～&lt;/B2&gt;で囲ってください</td>　
                                <td>（例）&lt;B2&gt;<b style="font-size:20px;">太文字</b>&lt;/B2&gt;<br></td>
                            </tr>
                            <tr>
                                <td>・文字を赤文字で太くする場合は&lt;RB&gt;～&lt;/RB&gt;で囲ってください</td>
                                <td>（例）&lt;RB&gt;<b><font color="red">太文字</font></b>&lt;/RB&gt;</td>　
                            </tr>
                            <tr>
                                <td>・文字列を黄色で塗る場合は&lt;LY&gt;～&lt;/LY&gt;で囲ってください</td>
                                <td>（例）&lt;LY&gt;<span style="background-color:yellow">文字</span>&lt;/LY&gt;</td>
                            </tr>
                        </table>
                    </div>

                 </div>


                


                 <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">～　開始直後の文章　～</div>


                    <div class="admin-inputcustomize-text-area">

                         <textarea id="input_add_text" name="acf_thanks_spirit_start_text" rows="10" cols="33" style="width: 100%;"><?php if( $customize_data != ""){ echo $customize_data["acf_thanks_spirit_start_text"]; }?></textarea>

                    </div>


                 </div>


                 <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">～　ID関連　～</div>


                    <div class="admin-inputcustomize-text-area">

                         ここにIDなどの説明が入ります

                    </div>


                 </div>



                 <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">～　<?php echo get_field('acf_pure_spirit_title',$question_num); ?>に必要な写真</div>

                    <div class="admin-thank-customize-detail-area">
                         <div class="admin-thank-customize-detail-text">写真などを使用する際はこちらのボタンから細かい設定を作成してください。</div>
                         <div class="admin-thank-customize-detail-button-area">
                             <button type=“button” class="admin-thank-customize-detail-button" onclick="window.open('https://a-gate-kanri.com/wp-admin/post.php?post=<?php echo $customize_data["acf_thanks_photo_text_id"]; ?>&action=edit', '_blank')">詳細ページへ</button>
                         </div>
                    </div>

                    <div class="admin-inputcustomize-text-area">

                         <textarea id="input_add_text" name="acf_thanks_photo_text" rows="30" cols="33" style="width: 100%;"><?php if( $customize_data != ""){ echo $customize_data["acf_thanks_photo_text"]; }?></textarea>

                    </div>

                    <div class="admin-thanks-phot-note-area">
                        ＊詳細、もしくは簡易入力の方を使用する場合は片方は未入力状態にしてください
                    </div>

                 </div>

                 <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">～　テキスト差し込み１　～</div>


                    <div class="admin-inputcustomize-text-area">

                         <input type="radio" name="acf_thanks_text_left_1" value=""  <?php if( $customize_data != "" && $customize_data["acf_thanks_text_left_1"] == "" ){ echo "checked"; } ?> />中央揃い
                         <input type="radio" name="acf_thanks_text_left_1" value="1" <?php if( $customize_data != "" && $customize_data["acf_thanks_text_left_1"] != "" ){ echo "checked"; } ?>/>左寄寄せ

                    </div>

                    <div class="admin-inputcustomize-text-area">

                         <textarea id="input_add_text" name="acf_thanks_text_1" rows="30" cols="33" style="width: 100%;"><?php if( $customize_data != ""){ echo $customize_data["acf_thanks_text_1"]; }?></textarea>

                    </div>


                 </div>

                 <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">
                        ～　お問い合わせメールアドレス　1～ 
                        <div class="admin-inputcustomize-sub-title">（入力があった場合差し込み１の後に入力されます）</div>
                    </div>




                    <div class="admin-inputcustomize-text-area">

                         <input type="text" name="acf_thanks_contact_information_1" id="contact_information" value="<?php if( $customize_data != ""){ echo $customize_data["acf_thanks_contact_information_1"]; }?>"/>

                    </div>


                 </div>


                 <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">～　注意事項　～</div>


                    <div class="admin-inputcustomize-text-area">

                         <textarea id="input_add_text" name="acf_thanks_notes" rows="15" cols="33" style="width: 100%;"><?php if( $customize_data != ""){ echo $customize_data["acf_thanks_notes"]; }?></textarea>

                    </div>


                 </div>


                 <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">～　テキスト差し込み２　～</div>

                    <div class="admin-inputcustomize-text-area">

                         <input type="radio" name="acf_thanks_text_left_2" value=""  <?php if( $customize_data != "" && $customize_data["acf_thanks_text_left_2"] == "" ){ echo "checked"; } ?> />中央揃い
                         <input type="radio" name="acf_thanks_text_left_2" value="1" <?php if( $customize_data != "" && $customize_data["acf_thanks_text_left_2"] != "" ){ echo "checked"; } ?>/>左寄寄せ

                    </div>


                    <div class="admin-inputcustomize-text-area">

                         <textarea id="input_add_text" name="acf_thanks_text_2" rows="15" cols="33" style="width: 100%;"><?php if( $customize_data != ""){ echo $customize_data["acf_thanks_text_2"]; }?></textarea>

                    </div>


                 </div>

                  <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">
                        ～　お問い合わせメールアドレス　２～ 
                        <div class="admin-inputcustomize-sub-title">（入力があった場合差し込み２の後に入力されます）</div>
                    </div>


                    <div class="admin-inputcustomize-text-area">

                         <input type="text" name="acf_thanks_contact_information_2" id="contact_information" value="<?php if( $customize_data != ""){ echo $customize_data["acf_thanks_contact_information_2"]; }?>"/>

                    </div>


                 </div>




                 <input type="hidden" name="type_id" id="type_id" value="<?php echo $question_num;?>" />
                 <input type="hidden" name="save_id" id="save_id" value="<?php if( $customize_data != ""){ echo $customize_data["ID"]; }?>" />

                 <div class="admin-inputcustomize-submit-area">
                    <input class="admin-spirit-submit-button" type="submit" value="保　存" onclick=""/>
                </div>

             </form>



             



             <div class="admin-spirit-return-button-area">
                <button type=“button” class="admin-spirit-return-button" onclick="location.href='<?php echo getURLSetSlag("admin-thanks-custom"); ?>'">戻る</button>
             </div>

         </div>

    </div>


 </div>


 <script>
        function submitForm() {
          

              // 元のフォームのデータを取得
            const form = document.getElementById('customize_input');
            const formData = new FormData(form);

            // 新しいフォームを作成
            const newForm = document.createElement('form');
            newForm.method = 'POST';
            newForm.action = '<?php echo  getURLSetSlag( "admin-thanks-preview" );?>'; // 送信先URLを指定
            newForm.target = '_blank'; // 新しいタブで開く

            // FormDataを新しいフォームに追加
            formData.forEach((value, key) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                newForm.appendChild(input);
            });

            //console.log(newForm.outerHTML);

            // 新しいフォームをドキュメントに追加して送信
            document.body.appendChild(newForm);
            newForm.submit();

            // 送信後、フォームをドキュメントから削除
            document.body.removeChild(newForm);

        }
 </script>