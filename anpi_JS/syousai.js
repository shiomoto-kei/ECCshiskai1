document.addEventListener('DOMContentLoaded', () => {
    // 要素の取得
    const dialog = document.getElementById('dialoghennsyuu');
    const openBtn = document.getElementById('hennsyuuBton');
    const closeBtn = document.getElementById('closeModeBtn');
    const deleteForm = document.querySelector('form[action="syousai.php"][method="get"]');

    // 編集ボタンを押したときにダイアログを開く
    if (openBtn) {
        openBtn.addEventListener('click', () => {
            dialog.showModal();
        });
    }

    //キャンセルボタンを押したときにダイアログを閉じる
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
    
    //ダイアログの外側をクリックしたときにも閉じる
    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) {
            dialog.close();
        }
    });
});