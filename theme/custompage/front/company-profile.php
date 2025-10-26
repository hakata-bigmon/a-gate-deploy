
<?php 

  

  ?>

<div class="company-profile-container">
    <div class="company-profile-item">
        <div class="company-profile-label">会社名</div>
        <div class="company-profile-value">株式会社A-GATE</div>
    </div>
    
    <div class="company-profile-item">
        <div class="company-profile-label">代表取締役社長</div>
        <div class="company-profile-value">牧野　健児</div>
    </div>
    
    <div class="company-profile-item">
        <div class="company-profile-label">事業所</div>
        <div class="company-profile-value">〒101-0044　東京都千代田区鍛冶町2-8-7　光起ビル２F</div>
    </div>
    
    <div class="company-profile-item">
        <div class="company-profile-label">E-MAIL</div>
        <div class="company-profile-value">info＠a-gate-ltd.com</div>
    </div>
    
    <div class="company-profile-item">
        <div class="company-profile-label">設立</div>
        <div class="company-profile-value">2019年9月26日</div>
    </div>
</div>

<style>
.company-profile-container {
    max-width: 800px;
    margin: 40px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 40px;
    border: 1px solid #e9ecef;
}

.company-profile-item {
    display: flex;
    align-items: flex-start;
    padding: 20px 0;
    border-bottom: 1px solid #f1f3f4;
}

.company-profile-item:last-child {
    border-bottom: none;
}

.company-profile-label {
    width: 200px;
    font-weight: 700;
    color: #234a6f;
    font-size: 16px;
    padding-right: 20px;
    flex-shrink: 0;
}

.company-profile-value {
    flex: 1;
    color: #495057;
    font-size: 16px;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .company-profile-container {
        margin: 20px;
        padding: 20px;
    }
    
    .company-profile-item {
        flex-direction: column;
        padding: 15px 0;
    }
    
    .company-profile-label {
        width: 100%;
        margin-bottom: 8px;
        padding-right: 0;
    }
    
    .company-profile-value {
        padding-left: 0;
    }
}
</style>