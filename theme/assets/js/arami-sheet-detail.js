/**
 * arami-sheet-detail.js - 安全にDataTablesを初期化するスクリプト
 * 
 * このスクリプトは、jQueryの競合を防ぎ、DOM要素が確実に存在する状態で
 * DataTablesを初期化するためのコードです。
 */

// DOMが完全に読み込まれた後に実行するため、無名関数で囲む
(function() {
    // DOMContentLoadedイベントを使用して、DOMの準備ができたら実行
    document.addEventListener('DOMContentLoaded', function() {
        // jQueryが確実に読み込まれるまで少し待つ
        setTimeout(function() {
            // jQueryが読み込まれているか確認
            if (typeof jQuery !== 'undefined') {
                // 無名関数内でjQueryを使用し、$変数の競合を防ぐ
                jQuery(function($) {
                    console.log('jQuery version:', $.fn.jquery);
                    
                    // テーブルが存在するか確認
                    if ($('#arami-sheet-table').length > 0) {
                        console.log('Table found, preparing to initialize DataTable');
                        
                        try {
                            // DataTablesが読み込まれているか確認
                            if (typeof $.fn.DataTable === 'function') {
                                // テーブルがすでにDataTableとして初期化されているか確認
                                if ($.fn.DataTable.isDataTable('#arami-sheet-table')) {
                                    console.log('Destroying existing DataTable instance');
                                    $('#arami-sheet-table').DataTable().destroy();
                                }
                                
                                // DataTablesの初期化
                                $('#arami-sheet-table').DataTable({
                                    language: {
                                        url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
                                    },
                                    order: [[0, 'desc']],
                                    pageLength: 50,
                                    lengthMenu: [[10, 30, 50, 100, 500, -1], [10, 30, 50, 100, 500, "全件"]],
                                    searching: false, // 検索ボックスを非表示
                                    lengthChange: false, // 表示件数の選択を非表示
                                    paging: false, // ページネーションを非表示
                                    // 潜在的なエラーを防ぐための追加設定
                                    orderClasses: false,
                                    deferRender: true
                                });
                                
                                console.log('DataTable initialized successfully');
                            } else {
                                console.error('DataTable plugin not available');
                            }
                        } catch (e) {
                            console.error('Error initializing DataTable:', e);
                        }
                    } else {
                        console.log('Table #arami-sheet-table not found in DOM');
                    }
                });
            } else {
                console.error('jQuery not loaded');
            }
        }, 500); // 0.5秒待つ
    });
})();