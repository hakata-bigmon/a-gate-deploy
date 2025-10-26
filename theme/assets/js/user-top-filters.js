document.addEventListener('DOMContentLoaded', function() {
    // カテゴリタブの切り替え
    const tabItems = document.querySelectorAll('.tab-item');
    tabItems.forEach(tab => {
        tab.addEventListener('click', function() {
            // アクティブクラスを削除
            tabItems.forEach(t => t.classList.remove('active'));
            // クリックされたタブをアクティブに
            this.classList.add('active');
            
            // カテゴリに応じたコンテンツの切り替え処理
            const category = this.dataset.category;
            console.log('Selected category:', category);
            filterByCategory(category);
        });
    });

    // ページ読み込み時に完了済みフィルタを自動選択
    setTimeout(() => {
        const completeFilterBtn = document.querySelector('.complete-navigation .filter-btn[data-filter="in-progress"]');
        if (completeFilterBtn) {
            // 他のボタンのアクティブクラスを削除
            const container = completeFilterBtn.closest('.user-navigation-container');
            container.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            // 完了済みボタンをアクティブに
            completeFilterBtn.classList.add('active');
            // フィルタリングを実行
            filterCompleteItems('in-progress');
        }
    }, 100);

    // 進行中セクションのフィルタボタンの切り替え
    const progressFilterBtns = document.querySelectorAll('.progress-navigation .filter-btn');
    progressFilterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // 同じセクション内のアクティブクラスを削除
            const container = this.closest('.user-navigation-container');
            container.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            // クリックされたボタンをアクティブに
            this.classList.add('active');
            
            // 進行中セクションのフィルタリング
            const filter = this.dataset.filter;
            filterProgressItems(filter);
        });
    });

    // 完了セクションのフィルタボタンの切り替え
    const completeFilterBtns = document.querySelectorAll('.complete-navigation .filter-btn');
    completeFilterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // 同じセクション内のアクティブクラスを削除
            const container = this.closest('.user-navigation-container');
            container.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            // クリックされたボタンをアクティブに
            this.classList.add('active');
            
            // 完了セクションのフィルタリング
            const filter = this.dataset.filter;
            filterCompleteItems(filter);
        });
    });

    // カテゴリによるフィルタリング機能
    function filterByCategory(category) {
        const progressItems = document.querySelectorAll('[data-section="progress"].user-jorei-list-item');
        
        progressItems.forEach(item => {
            const categoryElement = item.querySelector('.user-jorei-list-title-category');
            let shouldShow = false;
            
            if (categoryElement) {
                const categoryText = categoryElement.textContent.trim();
                
                switch(category) {
                    case 'all':
                        // 全て表示の場合
                        shouldShow = true;
                        break;
                    case 'applications':
                        // 各種お申し込みの場合（物販以外）
                        // 物販系のキーワードが含まれていない場合に表示
                        shouldShow = !categoryText.includes('物販') && 
                                   !categoryText.includes('グッズ') && 
                                   !categoryText.includes('商品');
                        break;
                    case 'sales':
                        // 物販の場合
                        // 物販系のキーワードが含まれている場合に表示
                        shouldShow = categoryText.includes('物販') || 
                                   categoryText.includes('グッズ') || 
                                   categoryText.includes('商品');
                        break;
                    default:
                        shouldShow = true;
                }
            } else {
                // カテゴリ要素が見つからない場合は全て表示の時のみ表示
                shouldShow = (category === 'all');
            }
            
            item.style.display = shouldShow ? 'block' : 'none';
            item.style.opacity = shouldShow ? '1' : '0';
        });
        
        showCategoryFilterFeedback(category);
    }

    // カテゴリフィルタのフィードバック表示
    function showCategoryFilterFeedback(category) {
       /* const feedback = document.createElement('div');
        feedback.className = 'filter-feedback';
        feedback.textContent = getCategoryFilterMessage(category);
        feedback.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #A078D0;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 1000;
            animation: slideIn 0.3s ease;
        `;
        
        document.body.appendChild(feedback);
        
        setTimeout(() => {
            feedback.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                document.body.removeChild(feedback);
            }, 300);
        }, 2000);*/
    }

    function getCategoryFilterMessage(category) {
        switch(category) {
            case 'all': return '全ての項目を表示';
            case 'applications': return '各種お申し込みを表示';
            case 'sales': return '物販を表示';
            default: return 'カテゴリフィルタを適用';
        }
    }

    // 修正版のフィルタリング機能（data属性を使用）
    function filterProgressItems(filter) {
        const progressItems = document.querySelectorAll('[data-section="progress"].user-jorei-list-item');
        
        progressItems.forEach(item => {
            const statusElement = item.querySelector('.user-jorei-list-status');
            if (statusElement) {
                const statusText = statusElement.textContent.trim();
                let shouldShow = false;
                
                switch(filter) {
                    case 'all':
                        shouldShow = true;
                        break;
                    case 'in-progress':
                        shouldShow = !statusText.includes('情報未入力') && 
                                   !statusText.includes('必要事項再入力') && 
                                   !statusText.includes('必要事項未入力') && 
                                   !statusText.includes('入金待ち');
                        break;
                    case 'confirmation':
                        shouldShow = statusText.includes('情報未入力') || 
                                   statusText.includes('必要事項未入力') || 
                                   statusText.includes('必要事項再入力');
                        break;
                    case 'unpaid':
                        shouldShow = statusText.includes('入金待ち');
                        break;
                    case 'complete':
                        shouldShow = statusText.includes('完了') || statusText.includes('発送済み') || statusText.includes('キャンセル');
                        break;
                    default:
                        shouldShow = true;
                }
                
                item.style.display = shouldShow ? 'block' : 'none';
                item.style.opacity = shouldShow ? '1' : '0';
            }
        });
        
        showFilterFeedback(filter, 'progress');
    }

    function filterCompleteItems(filter) {
        const completeItems = document.querySelectorAll('[data-section="complete"].user-jorei-list-item');
        let visibleCount = 0;
        const maxDisplayCount = 5;
        
        completeItems.forEach((item, index) => {
            const statusElement = item.querySelector('.user-jorei-list-status');
            if (statusElement) {
                const statusText = statusElement.textContent.trim();
                let shouldShow = false;
                
                switch(filter) {
                    case 'all':
                        shouldShow = true;
                        break;
                    case 'in-progress':
                        shouldShow = !statusText.includes('キャンセル');
                        break;
                    case 'confirmation':
                        shouldShow = statusText.includes('キャンセル');
                        break;
                    default:
                        shouldShow = true;
                }
                
                // フィルタ条件に合致し、かつ最大表示件数内の場合のみ表示
                if (shouldShow && visibleCount < maxDisplayCount) {
                    item.style.display = 'block';
                    item.style.opacity = '1';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                    item.style.opacity = '0';
                }
            }
        });
        
        // 表示件数制限のメッセージを更新
        updateCompleteSectionMessage(visibleCount, completeItems.length, filter);
        
        showFilterFeedback(filter, 'complete');
    }

    // 完了セクションのメッセージ更新
    function updateCompleteSectionMessage(visibleCount, totalCount, filter) {
        // 既存のメッセージを削除
        const existingMessage = document.querySelector('.complete-section-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // 新しいメッセージを作成
        if (visibleCount >= 5 && totalCount > 5) {
            const message = document.createElement('div');
            message.className = 'complete-section-message';
            message.style.cssText = 'text-align: center; padding: 20px; color: #666; font-size: 14px;';
            message.textContent = `※ 最新の5件のみ表示しています。全ての履歴は下記のリンクからご確認ください。`;
            
            // 完了セクションの最後に挿入
            const completeSection = document.querySelector('[data-section="complete"]');
            if (completeSection) {
                completeSection.parentNode.insertBefore(message, completeSection.nextSibling);
            }
        }
    }

    // フィルタ変更の視覚的フィードバック
    function showFilterFeedback(filter, section) {
      /*  const feedback = document.createElement('div');
        feedback.className = 'filter-feedback';
        feedback.textContent = getFilterMessage(filter, section);
        feedback.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #A078D0;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 1000;
            animation: slideIn 0.3s ease;
        `;
        
        document.body.appendChild(feedback);
        
        setTimeout(() => {
            feedback.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                document.body.removeChild(feedback);
            }, 300);
        }, 2000);*/
    }

    function getFilterMessage(filter, section) {
        if (section === 'progress') {
            switch(filter) {
                case 'all': return '全て表示';
                case 'in-progress': return '進行中の項目を表示';
                case 'confirmation': return '情報入力待ちの項目を表示';
                case 'unpaid': return '入金待ちの項目を表示';
                default: return 'フィルタを適用';
            }
        } else if (section === 'complete') {
            switch(filter) {
                case 'all': return '全て表示';
                case 'in-progress': return '完了済みの項目を表示';
                case 'confirmation': return 'キャンセルの項目を表示';
                default: return 'フィルタを適用';
            }
        }
        return 'フィルタを適用';
    }

    // CSS アニメーション
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
});