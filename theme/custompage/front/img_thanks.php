
<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritInputCustomizeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritMailPostClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");

    $spirit_customize_data = new SpiritInputCustomizeClass(); //管理データ
    $spirit_sheet_data = new spiritSheetClass(); //質問データ
    $mail_post_data = new SpiritMailPostClass(); //管理データ


  

  ?>


  <div class="input-form-area">


    <div class="input-form-contens">


        <div class="input-form-header-img">
           
            <div class="input-img-upload-box">
                 <div class="input-img-upload-text">画像アップロード </div>
            </div>


        </div>


        <div class="input-form-main">

             <div class="input-img-upload-message" style="margin-top: 30px;">
                画像アップロードありがとうございました。<br>
                画像確認後、不備等があった場合は、ご連絡いたしますので、少々お待ちください。<br>

             </div>

        </div>

    </div>

</div>