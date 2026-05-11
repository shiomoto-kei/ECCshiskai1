document.addEventListener('DOMContentLoaded', () => {
    // ダイアログ要素の取得
    const dialogSa = document.getElementById('dialogallsadel');
    const dialogTuika = document.getElementById('dialogtuika');

    // 開くボタンの取得
    const openSaBtn = document.getElementById('openhennsyuuBtn');
    const openTuikaBtn = document.getElementById('opentuikaBtn');

    // キャンセルボタン（全ての .closeModeBtn を取得）
    const closeBtns = document.querySelectorAll('.closeModeBtn');

    // --- 開く処理 ---
    if (openSaBtn && dialogSa) {
        openSaBtn.addEventListener('click', () => dialogSa.showModal());
    }

    if (openTuikaBtn && dialogTuika) {
        openTuikaBtn.addEventListener('click', () => dialogTuika.showModal());
    }

    // --- 閉じる処理 ---
    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (dialogSa.open) dialogSa.close();
            if (dialogTuika.open) dialogTuika.close();
        });
    });

    // 背景クリックで閉じる設定
    [dialogSa, dialogTuika].forEach(dialog => {
        if (dialog) {
            dialog.addEventListener('click', (event) => {
                if (event.target === dialog) {
                    dialog.close();
                }
            });
        }
    });
});