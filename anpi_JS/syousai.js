document.addEventListener('DOMContentLoaded', () => {
    // 要素の取得
    const dialog = document.getElementById('dialoghennsyuu');
    // IDではなく「class="hennsyuuBtn"」を持つ全てのボタンを取得する
    const openBtns = document.querySelectorAll('.hennsyuuBtn'); 
    const closeBtn = document.getElementById('closeModeBtn');
    const deleteForm = document.querySelector('form[action="syousai.php"][method="get"]');

    // すべての編集ボタンに対してクリックイベントを設定する
    if (openBtns.length > 0) {
        openBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (dialog) {
                    dialog.showModal();
                }
            });
        });
    }

    // キャンセルボタンを押したときにダイアログを閉じる
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            dialog.close();
        });
    }

    // 削除ボタンを押したときに確認を出す
    if (deleteForm) {
        deleteForm.addEventListener('submit', (event) => {
            const confirmDelete = confirm("本当にこの社員情報を削除してもよろしいですか？");
            if (!confirmDelete) {
                // 「キャンセル」を押した場合は送信を中止する
                event.preventDefault();
            }
        });
    }
    
    // ダイアログの外側をクリックしたときにも閉じる
    if (dialog) {
        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) {
                dialog.close();
            }
        });
    }
});