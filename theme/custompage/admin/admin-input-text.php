
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

        $spirit_sheet_data->saveInputCustomizeData( $_POST["save_id"] ,$_POST);

    }

    $customize_data = $spirit_sheet_data->getInputCustomizeData( $question_num );

    //ない場合は新規作成
    if($customize_data == "")
    {
         $spirit_sheet_data->newInputCustomizeData( $question_num  );
         $customize_data = $spirit_sheet_data->getInputCustomizeData( $question_num );
    }

?>





<div class="admin-exorcism-area">


    <div class="admin-exorcism-button-area">

        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title" style="text-align: center;"><?php echo get_field('acf_pure_spirit_title',$question_num); ?>　入力フォーム設定</div></div>

        <div class="admin-preview-button-flex" style="display: block;margin-top: 30px;">
            <?php /*
            <div class="admin-preview-button-flex-box">
                <button class="admin-preview-button" type="button" onclick="submitForm()">プレビュー</button>
            </div>
            */ ?>

            <div class="admin-preview-button-flex-box">
                <button class="admin-preview-button" type="button" id="openLinkButton" style="background-color: aliceblue;width: 100%;height: 80px;">個人情報設定</button>
            </div>

            <div class="admin-preview-button-flex-box" style="margin-top: 30px;">
                <button class="admin-preview-button" type="button" id="openQuestionButton" style="width: 100%;height: 80px;">質問内容設定</button>
            </div>
         </div>

        <div class="admin-inputcustomize-area">

         <?php /*
             <form action="<?php echo  getURLSetSlag( "admin-input-text" );?>?type_id=<?php echo $question_num;?>" name="customize_input" method="post" id="customize_input">


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

                         <textarea id="input_add_text" name="acf_spirit_start_text" rows="10" cols="33" style="width: 100%;"><?php if( $customize_data != ""){ echo $customize_data["acf_spirit_start_text"]; }?></textarea>

                    </div>


                 </div>


                 <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">～　<?php echo get_field('acf_pure_spirit_title',$question_num); ?>の流れ　～</div>


                    <div class="admin-inputcustomize-text-area">

                         <textarea id="input_add_text" name="acf_spirit_flow_text" rows="10" cols="33" style="width: 100%;"><?php if( $customize_data != ""){ echo $customize_data["acf_spirit_flow_text"]; }?></textarea>

                    </div>


                 </div>


                 <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">～　テキスト差し込み１　～</div>


                    <div class="admin-inputcustomize-text-area">

                         <textarea id="input_add_text" name="acf_spirit_text_1" rows="10" cols="33" style="width: 100%;"><?php if( $customize_data != ""){ echo $customize_data["acf_spirit_text_1"]; }?></textarea>

                    </div>


                 </div>

                  <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">
                        ～　お問い合わせメールアドレス　1～ 
                        <div class="admin-inputcustomize-sub-title">（入力があった場合差し込み１の後に入力されます）</div>
                    </div>


                    <div class="admin-inputcustomize-text-area">

                         <input type="text" name="acf_spirit_contact_information" id="contact_information" value="<?php if( $customize_data != ""){ echo $customize_data["acf_spirit_contact_information"]; }?>"/>

                    </div>


                 </div>


                 <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">～　フォームにご入力頂く前に　～</div>


                    <div class="admin-inputcustomize-text-area">

                         <textarea id="input_add_text" name="acf_spirit_before_input" rows="10" cols="33" style="width: 100%;"><?php if( $customize_data != ""){ echo $customize_data["acf_spirit_before_input"]; }?></textarea>

                    </div>

                 </div>


                 <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">～　テキスト差し込み２　～</div>


                    <div class="admin-inputcustomize-text-area">

                         <textarea id="input_add_text" name="acf_spirit_text_2" rows="10" cols="33" style="width: 100%;"><?php if( $customize_data != ""){ echo $customize_data["acf_spirit_text_2"]; }?></textarea>

                    </div>


                 </div>

                 
                 <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">
                        ～　お問い合わせメールアドレス　2～ 
                        <div class="admin-inputcustomize-sub-title">（入力があった場合差し込み２の後に入力されます）</div>
                    </div>


                    <div class="admin-inputcustomize-text-area">

                         <input type="text" name="acf_spirit_contact_information_2" id="acf_spirit_contact_information_2" value="<?php if( $customize_data != ""){ echo $customize_data["acf_spirit_contact_information_2"]; }?>"/>

                    </div>


                 </div>



                 <div class="admin-inputcustomize-message-area">

                    ここに個人の入力が部分が入ります

                 </div>


                 <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">～　テキスト差し込み３　～</div>


                    <div class="admin-inputcustomize-text-area">

                         <textarea id="input_add_text" name="acf_spirit_text_3" rows="10" cols="33" style="width: 100%;"><?php if( $customize_data != ""){ echo $customize_data["acf_spirit_text_3"]; }?></textarea>

                    </div>


                 </div>


                 <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">
                        ～　注意１　～
                        <div class="admin-inputcustomize-sub-title">（注意１だけの場合は番号は表示されません）</div>
                    </div>

                    <div class="admin-inputcustomize-note-add-area">
                        注意のタイトル追加　<input type="text" name="acf_spirit_note_title_1" id="acf_spirit_note_title_1" value="<?php if( $customize_data != ""){ echo $customize_data["acf_spirit_note_title_1"]; }?>"/>
                    </div>


                    <div class="admin-inputcustomize-text-area">

                         <textarea id="input_add_text" name="acf_spirit_note_1" rows="10" cols="33" style="width: 100%;"><?php if( $customize_data != ""){ echo $customize_data["acf_spirit_note_1"]; }?></textarea>

                    </div>


                 </div>

                 <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">～　注意２　～</div>

                    <div class="admin-inputcustomize-note-add-area">
                        注意のタイトル追加　<input type="text" name="acf_spirit_note_title_2" id="acf_spirit_note_title_2" value="<?php if( $customize_data != ""){ echo $customize_data["acf_spirit_note_title_2"]; }?>"/>
                    </div>
                    <div class="admin-inputcustomize-text-area">

                         <textarea id="input_add_text" name="acf_spirit_note_2" rows="10" cols="33" style="width: 100%;"><?php if( $customize_data != ""){ echo $customize_data["acf_spirit_note_2"]; }?></textarea>

                    </div>


                 </div>


                 <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">～　注意３　～</div>

                    <div class="admin-inputcustomize-note-add-area">
                        注意のタイトル追加　<input type="text" name="acf_spirit_note_title_3" id="acf_spirit_note_title_3" value="<?php if( $customize_data != ""){ echo $customize_data["acf_spirit_note_title_3"]; }?>"/>
                    </div>
                    <div class="admin-inputcustomize-text-area">

                         <textarea id="input_add_text" name="acf_spirit_note_3" rows="10" cols="33" style="width: 100%;"><?php if( $customize_data != ""){ echo $customize_data["acf_spirit_note_3"]; }?></textarea>

                    </div>


                 </div>

                 <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">～　注意４　～</div>

                    <div class="admin-inputcustomize-note-add-area">
                        注意のタイトル追加　<input type="text" name="acf_spirit_note_title_4" id="acf_spirit_note_title_4" value="<?php if( $customize_data != ""){ echo $customize_data["acf_spirit_note_title_4"]; }?>"/>
                    </div>
                    <div class="admin-inputcustomize-text-area">

                         <textarea id="input_add_text" name="acf_spirit_note_4" rows="10" cols="33" style="width: 100%;"><?php if( $customize_data != ""){ echo $customize_data["acf_spirit_note_4"]; }?></textarea>

                    </div>


                 </div>

                  <div class="admin-inputcustomize-box">


                    <div class="admin-inputcustomize-title">～　注意５　～</div>

                    <div class="admin-inputcustomize-note-add-area">
                        注意のタイトル追加　<input type="text" name="acf_spirit_note_title_5" id="acf_spirit_note_title_5" value="<?php if( $customize_data != ""){ echo $customize_data["acf_spirit_note_title_5"]; }?>"/>
                    </div>
                    <div class="admin-inputcustomize-text-area">

                         <textarea id="input_add_text" name="acf_spirit_note_5" rows="10" cols="33" style="width: 100%;"><?php if( $customize_data != ""){ echo $customize_data["acf_spirit_note_5"]; }?></textarea>

                    </div>


                 </div>


                 <input type="hidden" name="type_id" id="type_id" value="<?php echo $question_num;?>" />
                 <input type="hidden" name="save_id" id="save_id" value="<?php if( $customize_data != ""){ echo $customize_data["ID"]; }?>" />

                 <div class="admin-inputcustomize-submit-area">
                    <input class="admin-spirit-submit-button" type="submit" value="保　存" onclick=""/>
                </div>

             </form>



             



             <div class="admin-spirit-return-button-area">
                <button type=“button” class="admin-spirit-return-button" onclick="location.href='<?php echo getURLSetSlag("admin-input-custom"); ?>'">戻る</button>
             </div>
          */ ?>
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
            newForm.action = '<?php echo  getURLSetSlag( "admin-preview" );?>'; // 送信先URLを指定
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

        document.getElementById('openLinkButton').addEventListener('click', function() {
            var url = '<?php echo  getURLSetSlag( "admin-input-personaldata" );?>?type_id=<?php echo $question_num;?>&post_id=<?php echo $customize_data["ID"]; ?>'; // 開きたいリンクのURL
            window.open(url, '_blank'); // '_blank' で新しいタブで開く
        });

         document.getElementById('openQuestionButton').addEventListener('click', function() {
            var url = '<?php echo  getURLSetSlag( "admin-exorcism-questions" );?>?type_id=<?php echo $question_num;?>'; // 開きたいリンクのURL
            window.open(url, '_blank'); // '_blank' で新しいタブで開く
        });

 </script>