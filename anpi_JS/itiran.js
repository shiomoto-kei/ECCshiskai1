document.addEventListener('DOMContentLoaded', () => {
    // ダイアログ要素の取得
    const dialogSa = document.getElementById('dialogallsadel'); // 初期化用
    const dialogTuika = document.getElementById('dialogtuika');   // 追加用

    //開くボタンの取得
    const openSaBtn = document.getElementById('openhennsyuuBtn'); // 編集（初期化）ボタン
    const openTuikaBtn = document.getElementById('opentuikaBtn');   // ＋追加ボタン

    //キャンセルボタンの取得（全ての「キャンセル」ボタン）
    //HTML側で id="closeModeBtn" を class="closeModeBtn" に変える
    const closeBtns = document.querySelectorAll('.closeModeBtn');

    // --- ダイアログを開く処理 ---

    // 安否初期化ダイアログ
    if (openSaBtn) {
        openSaBtn.addEventListener('click', () => {
            dialogSa.showModal();
        });
    }

    // 新規追加ダイアログ
    if (openTuikaBtn) {
        openTuikaBtn.addEventListener('click', () => {
            dialogTuika.showModal();
        });
    }

    // --- ダイアログを閉じる処理 ---

    // 全てのキャンセルボタンに対して閉じる処理を割り当てる
    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            dialogSa.close();
            dialogTuika.close();
        });
    });

    // 背景クリックで閉じる（お好みで追加）
    [dialogSa, dialogTuika].forEach(dialog => {
        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) {
                dialog.close();
            }
        });
    });
});