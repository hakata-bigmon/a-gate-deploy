<?php 

require_once(get_template_directory() . '/class/spiritScheduleClass.php');


//var_dump($_POST);

if(isset($_POST["cancel_schedule"]))
{

  $spirit_sheet_data->cancelSpiritSheet( $sheet_id , $user_id );
  
  //再取得  
  $spiritData = $userClass->getUserSpritApplicantSheet($user_id,$sheet_id);//浄霊情報
  $spiritSheetArray = $spirit_sheet_data->getSpiritQuestion($spiritData[$sheet_id]["質問"]);//質問データ
  $spirit_data = $spiritData[$sheet_id];
}


$userData = $userClass->getUserAcountData($spirit_data["依頼者ID"]);

$spiritScheduleData = new SpiritScheduleClass(); //スケジュールデータ

$set_spirit_sheet = $spiritScheduleData->getSpritScheduledetail($spirit_data["スケジュール"]);
   
/*
var_dump($spirit_data);

    echo "<br>";
    echo "<br>";
    var_dump($set_spirit_sheet);
  */  
?>

<style>

  .schedule-profile-name{
    font-size: 16px;
    font-weight: 600;
    color: #888888;
    margin-bottom: 5px;
    margin-top: 10px;
  }
  
</style>




<div class="user-jorei-section-title-wrap">
    <div class="user-jorei-section-title"><?php echo $spirit_data["依頼名前"];?>　詳細</div>
</div>

<div class="sales-detail-container">
    <div class="sales-detail-card">
        <div class="sales-detail-row">
            <div class="sales-detail-label">依頼日</div>
            <div class="sales-detail-value"><?php echo $spirit_data["依頼日年月日"];?></div>
        </div>
        
        <div class="sales-detail-row">
            <div class="sales-detail-label">依頼名</div>
            <div class="sales-detail-value"><?php echo $spirit_data["依頼名前"];?></div>
        </div>
        
        <div class="sales-detail-row">
            <div class="sales-detail-label">対象者</div>
            <?php 
              $target = "";
              if($spirit_data["対象者"]["対象者情報"] == false){
                $target = "申込者ご本人";
              }else{
                $target =  $spirit_data["対象者"]["フル名前"];
              }
            ?>
            <div class="sales-detail-value"><?php echo $target;?></div>
        </div>
        
        <div class="sales-detail-row">
            <div class="sales-detail-label">価格</div>
            <div class="sales-detail-value price"><?php if($spirit_data["価格"] > 0){?>￥<?php echo $spirit_data["価格"];}else{?>無料<?php } ?></div>
        </div>
        
        <?php if($spirit_data["価格"] > 0){?>
          <div class="sales-detail-row">
              <div class="sales-detail-label">支払い方法</div>
              <div class="sales-detail-value"><?php echo $spirit_data["支払いタイプ表示"];?></div>
          </div>
        <?php } ?>
        
        <div class="sales-detail-row">
            <div class="sales-detail-label">実行日</div>
            <div class="sales-detail-value">
            <?php echo $spirit_data["実行日年月日"];?>
            <?php if($set_spirit_sheet["実行時間表示"] != ""){?>
               　 <?php echo $set_spirit_sheet["実行時間表示"];?>～
            <?php } ?>
           
            </div>
        </div>
        
        <?php if($set_spirit_sheet["施術時間"] != "" && $set_spirit_sheet["施術時間"]  > 0){?>
            <div class="sales-detail-row">
                <div class="sales-detail-label">施術時間</div>
                <div class="sales-detail-value"><?php echo $set_spirit_sheet["施術時間"];?>分</div>
            </div>
        <?php } ?>
        <?php if($spirit_data["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_WATING_PAYMENT){?>
            <div class="sales-detail-row">
                <div class="sales-detail-label">支払い状況</div>
                <div class="sales-detail-value">ご入金待ち</div>
            </div>
        <?php } ?>
        <?php if($set_spirit_sheet["キャンセル時間"] != "" && $set_spirit_sheet["キャンセル時間"] > date("Y-n-j H:i") && $spirit_data["会員ステータス"] != SpiritUserClass::MEMBER_STATUS_WATING_PAYMENT){?>
            <div class="sales-detail-row">
                <div class="sales-detail-label">キャンセル可能</div>
                <div class="sales-detail-value"><?php echo $set_spirit_sheet["キャンセル時間年月日"];?></div>
            </div>
        <?php } ?>

        <?php if($set_spirit_sheet["担当者"] != ""){?>
          <div class="sales-detail-row">
            <div class="sales-detail-label">担当者</div>
            <div class="sales-detail-value">
              <?php $user_data = get_userdata($set_spirit_sheet["担当者"]);?>

              <div class="schedule-profile-flex">
                <div class="schedule-profile-img">
                  <?php 
                      $image_id = get_field('acf_teacher_profile_img', 'user_' . $set_spirit_sheet["担当者"]);
                      if ($image_id) {
                          $image_url = wp_get_attachment_image_url($image_id, 'medium');
                          if (!$image_url) {
                              $image_url = $image_id["url"]; // URLが直接保存されている場合
                          }
                      } else {
                          $image_url = get_template_directory_uri().'/assets/images/noimage.jpg';
                      }
                      
                      
                  ?>
                    <img src="<?php echo $image_url;?>" alt="" style="width: 100%;max-width: 80px;">
                </div>

                <div class="schedule-profile-text">
                    <div class="schedule-profile-name"><?php echo $user_data->display_name;?></div>
                    <div class="schedule-profile-explanation"><?php echo nl2br(get_field('acf_teacher_explanation', 'user_' . $set_spirit_sheet["担当者"]));?></div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>

        <?php if($set_spirit_sheet["場所"] != ""){?>
          <div class="sales-detail-row">
            <div class="sales-detail-label">実地場所</div>
            <div class="sales-detail-value">
              <?php
                echo $set_spirit_sheet["場所ステータス"]["名前"] . "<br>" . $set_spirit_sheet["場所ステータス"]["住所"];
              ?>
            </div>
          </div>

        <?php } ?>

        
        
        <?php if($spirit_data["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_CANCEL){?>
          <div class="sales-detail-row cancel">
              <div class="sales-detail-label">キャンセル</div>
              <div class="sales-detail-value">
                  <?php echo $spirit_data["キャンセル日年月日"];?>
              </div>
          </div>
          <?php }else{ ?>

            <div class="sales-detail-row">
                <div class="sales-detail-label">ステータス</div>
                <div class="sales-detail-value">
                    <?php echo $spirit_data["会員ステータス表示"];?>
                </div>
            </div>


          <?php } ?>

        <?php //依頼が確定していない && 無料じゃない ?>
        <?php if(($spirit_data["依頼確定日"] == "" || $spirit_data["価格"] == 0) && $spirit_data["会員ステータス"] != SpiritUserClass::MEMBER_STATUS_CANCEL){?>

          <?php if($set_spirit_sheet["キャンセル時間"] != "" && $set_spirit_sheet["キャンセル時間"] > date("Y-n-j H:i")){?>
            <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
              <form action="<?php echo getURLSetSlag("users/user-spirit-detail"); echo $get_url["add"]; ?>" method="post" onsubmit="return confirmCancel()">
                <input type="hidden" name="sheet_id" value="<?php echo $_POST["sheet_id"];?>">
                <input type="hidden" name="cancel_schedule" value="1">
                <button type="submit" class="user-account-edit-return-btn" style="background-color: gainsboro;color: black;">キャンセルする</button>
              </form>
              
              <script>
              function confirmCancel() {
                  return confirm('この予約をキャンセルしてもよろしいですか？\n\n※この操作は取り消せません。');
              }
              </script>
            </div>
          <?php } ?>
        
        <?php } ?>


        
    </div>
</div>

<?php 
      
      
    $treatment_result_ids = json_decode(get_field('acf_purespirit_result_img_id',$sheet_id));
  
    if($spirit_data["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_COMPLETE && is_array($treatment_result_ids)){
  
  ?>
    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <form action="<?php echo getURLSetSlag("users/user-spirit-result"); echo $get_url["add"]; ?>" method="post">
            <input type="hidden" name="sheet_id" value="<?php echo $_POST["sheet_id"]; ?>">
            <button type="submit" class="user-account-edit-return-btn" style="background-color: #C18AD1;color: white;">結果へ  &gt;</button>
        </form>
    </div>    
  <?php }?>

<!-- 画像モーダル -->
<div id="imageModal" class="image-modal">
    <div class="image-modal-content">
        <div class="modal-image-container">
            <img id="modalImage" src="" alt="拡大画像">
            <span class="image-modal-close">&times;</span>
        </div>
    </div>
</div>

<style>
.sales-detail-container {
  max-width: 800px;
  margin: 32px auto 0 auto;
  padding: 0 20px;
}
.sales-detail-card {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(193,138,209,0.08);
  padding: 32px 24px;
  border: 1px solid #f0e6f5;
}
.sales-detail-row {
  display: flex;
  align-items: flex-start;
  padding: 16px 0;
  border-bottom: 1px solid #f0e6f5;
  font-family: 'Noto Sans JP', sans-serif;
}
.sales-detail-row:last-child {
  border-bottom: none;
}
.sales-detail-label {
  width: 140px;
  font-weight: 600;
  color: #555;
  font-size: 15px;
  flex-shrink: 0;
}
.sales-detail-value {
  flex: 1;
  color: #333;
  font-size: 15px;
  line-height: 1.5;
}
.sales-detail-value.price {
  font-weight: 700;
  color: #C18AD1;
  font-size: 18px;
}
.sales-detail-value.address {
  line-height: 1.6;
}
.sales-detail-row.cancel {
  background: #fff5f5;
  margin: 0 -24px;
  padding: 16px 24px;
  border-top: 1px solid #f0e6f5;
  border-bottom: 1px solid #f0e6f5;
}
.sales-detail-row.cancel .sales-detail-label {
  color: #e57373;
}
.sales-detail-row.cancel .sales-detail-value {
  color: #e57373;
}
.sales-detail-img {
  transition: opacity 0.2s;
}
.sales-detail-img:hover {
  opacity: 0.8;
}
.image-modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.8);
}
.image-modal-content {
  position: relative;
  margin: auto;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
}
.modal-image-container {
  position: relative;
  display: inline-block;
}
.image-modal-content img {
  max-width: 90%;
  max-height: 90%;
  object-fit: contain;
}
.image-modal-close {
  position: absolute;
  top: 10px;
  right: 10px;
  color: #fff;
  font-size: 24px;
  font-weight: bold;
  cursor: pointer;
  background: rgba(0,0,0,0.7);
  border-radius: 50%;
  width: 35px;
  height: 35px;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1002;
}
.image-modal-close:hover,
.image-modal-close:focus {
  background: rgba(0,0,0,0.9);
}
@media (max-width: 768px) {
  .sales-detail-container {
    padding: 0 16px;
  }
  .sales-detail-card {
    padding: 24px 20px;
  }
  .sales-detail-row {
    flex-direction: column;
    gap: 8px;
  }
  .sales-detail-label {
    width: 100%;
    font-size: 14px;
  }
  .sales-detail-value {
    font-size: 14px;
  }
  .sales-detail-value.price {
    font-size: 16px;
  }
}
.image-container {
  position: relative;
  display: inline-block;
}
</style>
    

<script>
document.addEventListener('DOMContentLoaded', function() {
    const salesDetailImg = document.querySelector('.sales-detail-img');
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalClose = document.querySelector('.image-modal-close');

    if (salesDetailImg && imageModal && modalImage && modalClose) {
        // 画像クリックでモーダル表示
        salesDetailImg.addEventListener('click', function() {
            modalImage.src = this.src;
            imageModal.style.display = 'block';
        });

        // ×ボタンでモーダル非表示
        modalClose.addEventListener('click', function() {
            imageModal.style.display = 'none';
        });

        // モーダル外クリックで非表示
        imageModal.addEventListener('click', function(e) {
            if (e.target === imageModal) {
                imageModal.style.display = 'none';
            }
        });

        // ESCキーでモーダル非表示
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && imageModal.style.display === 'block') {
                imageModal.style.display = 'none';
            }
        });
    }
});
</script>    