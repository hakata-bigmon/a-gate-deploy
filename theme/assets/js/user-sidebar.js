document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('userSidebar');
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const mainContent = document.getElementById('userMainContent');
  
    function setSidebarState() {
      if (window.innerWidth >= 700) {
        // デスクトップサイズでは開いた状態にする
        sidebar.classList.add('open');
        sidebar.classList.remove('closed');
        if (mainContent) {
          mainContent.style.marginLeft = '320px';
        }
      } else {
        // モバイルサイズでは閉じた状態
        sidebar.classList.remove('open');
        sidebar.classList.add('closed');
        if (mainContent) {
          mainContent.style.marginLeft = '0';
        }
      }
    }
  
    // 初期状態を設定（ページ読み込み時）
    setSidebarState();
    
    // ウィンドウリサイズ時の対応
    window.addEventListener('resize', function() {
      sidebar.style.transition = 'none';
      if (mainContent) {
        mainContent.style.transition = 'none';
      }
      setSidebarState();
      requestAnimationFrame(() => {
        sidebar.style.transition = '';
        if (mainContent) {
          mainContent.style.transition = '';
        }
      });
    });
  
    // トグルボタンのクリックイベント（どの幅でも有効）
    if (toggleBtn) {
      toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('open');
        sidebar.classList.toggle('closed');
        if (window.innerWidth >= 700 && mainContent) {
          if (sidebar.classList.contains('closed')) {
            mainContent.style.marginLeft = '0';
          } else {
            mainContent.style.marginLeft = '320px';
          }
        }
      });
    }
  });