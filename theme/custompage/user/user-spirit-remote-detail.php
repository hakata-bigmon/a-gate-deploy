<?php 


$userData = $userClass->getUserAcountData($spirit_data["依頼者ID"]);

   // var_dump($userData);

   // echo "<br>";
   // echo "<br>";
   // var_dump($spirit_data);
   ?>






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
            <?php if($spirit_data["価格"] > 0){?>
                <div class="sales-detail-value price">￥<?php echo $spirit_data["価格"];?></div>
            <?php }else{?>
                <div class="sales-detail-value price">無料</div>
            <?php }?>
        </div>
        
        <div class="sales-detail-row">
            <div class="sales-detail-label">支払い方法</div>
            <div class="sales-detail-value"><?php echo $spirit_data["支払いタイプ表示"];?></div>
        </div>
        
        <div class="sales-detail-row">
            <div class="sales-detail-label">実行予定日</div>
            <div class="sales-detail-value">
            <?php 
                  if($spirit_data["実行予定日"] == ""){

                      //この時に日程確定になっている場合はいま検討中なので、ステータスを検討中に変えておく
                      if($spirit_data["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_CONFIRMED){
                          $spirit_data["会員ステータス"] = SpiritUserClass::MEMBER_STATUS_CONFIRMATION_INFOMATION;
                          $spirit_data["会員ステータス表示"] = "実行日申請中";
                      }
                      
                      echo "日程調整中（決定次第、日程が表示されます）";
                  }else{
                          $execution_date = $userClass->dispMemberStatus($spirit_data["実行予定日"]);

                          //$execution_dateの一週間前の日付を取得
                          $execution_date_one_week_ago = date('Y年n月d日', strtotime($execution_date . ' -1 week'));

                          //$execution_dateの一週間後の日付を取得
                          $execution_date_one_week_after = date('Y年n月d日', strtotime($execution_date . ' +1 week'));
                          echo $execution_date_one_week_ago . " ～ " . $execution_date_one_week_after . " 予定";
                  }
              ?>
        
            </div>
        </div>
        
        
        
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

        
    </div>
</div>

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



<?php 
      
      
    $treatment_result_ids = json_decode(get_field('acf_purespirit_result_img_id',$sheet_id));

  if($spirit_data["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_COMPLETE && is_array($treatment_result_ids)){

?>
  <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
      <form action="<?php echo getURLSetSlag("users/user-spirit-result"); echo $get_url["add"]; ?>" method="post">
          <input type="hidden" name="sheet_id" value="<?php echo $_POST["sheet_id"]; ?>">
          <button type="submit" class="user-account-edit-return-btn"  style="background-color: #C18AD1;color: white;">結果へ  &gt;</button>
      </form>
  </div>    
<?php }?>


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