document.addEventListener('DOMContentLoaded', () => {
    // --- 1. ログインダイアログ用の設定 ---
    const homeBtn = document.querySelector("#homeBtn");
    const loginCloseBtn = document.querySelector("#closeBtn");
    const loginDialog = document.querySelector("#loginDialog");

    if (homeBtn && loginDialog) {
        homeBtn.addEventListener("click", () => {
            loginDialog.showModal();
        });
    }

    if (loginCloseBtn && loginDialog) {
        loginCloseBtn.addEventListener("click", () => {
            loginDialog.close();
        });
    }

    // ログインダイアログの外側クリックで閉じる
    if (loginDialog) {
        loginDialog.addEventListener('click', (e) => {
            if (e.target === loginDialog) {
                loginDialog.close();
            }
        });
    }

    // --- 2. ステータス確認ダイアログ用の設定 ---
    const statusDialog = document.getElementById('statusDialog');
    const statusOpenBtn = document.getElementById('openStatusBtn');
    const statusCloseBtn = document.getElementById('closeStatusBtn');

    if (statusOpenBtn && statusDialog) {
        statusOpenBtn.addEventListener('click', () => {
            statusDialog.showModal();
        });
    }

    if (statusCloseBtn && statusDialog) {
        statusCloseBtn.addEventListener('click', () => {
            statusDialog.close();
        });
    }

    // ステータスダイアログの外側クリックで閉じる
    if (statusDialog) {
        statusDialog.addEventListener('click', (e) => {
            if (e.target === statusDialog) {
                statusDialog.close();
            }
        });
    }
});