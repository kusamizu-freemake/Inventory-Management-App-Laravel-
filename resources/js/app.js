// ページ読み込み完了後に実行
document.addEventListener('DOMContentLoaded', function() {
    console.log("✅ app.js 読み込み成功");

    // 現在時刻の更新
    function updateClock() {
        // 現在の日時を取得
        const now = new Date();
        // 日本時間形式で時刻を文字列化（例：13:45:30）
        const timeString = now.toLocaleTimeString('ja-JP');
        
        // 画面表示用の要素に時刻を設定
        const currentTimeElement = document.getElementById('currentTime');
        if (currentTimeElement) {
            currentTimeElement.textContent = timeString;
        }
        
        // 非表示のinput要素にも時刻を設定（フォーム送信用）
        const hiddenTimeElement = document.getElementById('hiddenTime');
        if (hiddenTimeElement) {
            hiddenTimeElement.value = timeString;
        }
    }
    
    // 1秒（1000ミリ秒）ごとにupdateClock関数を実行
    setInterval(updateClock, 1000);
    // ページ読み込み時に即座に1回実行
    updateClock();

    // 数量の増減ボタン
    const QtyInput = document.getElementById('quantity'); // 数量入力フィールド
    const IncrementBtn = document.getElementById('increment'); // ＋ボタン
    const DecrementBtn = document.getElementById('decrement');// －ボタン
    
    // ＋ボタンがクリックされたとき
    if (IncrementBtn && QtyInput) {
        IncrementBtn.addEventListener('click', function() {
            // 現在の値を取得（カンマを削除してから整数に変換）
            let val = parseInt(QtyInput.value.replace(/,/g, '')) || 0;
            // 上限9999まで増やせる
            if (val < 9999) {
                val++;
            }
            // カンマ区切りで表示（例：1234 → 1,234）
            QtyInput.value = val.toLocaleString();
        });
    }
    
    // −ボタンがクリックされたとき
    if (DecrementBtn && QtyInput) {
        DecrementBtn.addEventListener('click', function() {
            let val = parseInt(QtyInput.value.replace(/,/g, '')) || 0;
            // 0より小さくならないように
            if (val > 0) {
                val--;
            }
            QtyInput.value = val.toLocaleString();
        });
    }

    // チェックボックスの状態変化
    const Checkboxes = document.querySelectorAll('.chk-qty');
    Checkboxes.forEach(function(chk) {
        chk.addEventListener('change', function(e) {
            // チェックボックスが含まれる行（tr要素）を取得
            const Row = e.target.closest('tr');
            if (Row) {
                // チェックされていたら 'checked' クラスを追加、外されたら削除
                // 'checked'クラスが付くと背景色が緑になる（CSS参照）
                if (e.target.checked) {
                    Row.classList.add('checked');
                } else {
                    Row.classList.remove('checked');
                }
            }
        });
    });

    // 合計数量の計算
    const ShowTotalBtn = document.getElementById('showTotal');
    if (ShowTotalBtn) {
        ShowTotalBtn.addEventListener('click', function() {
            let total = 0;
            
            // チェックされているチェックボックスをすべて取得
            const CheckedBoxes = document.querySelectorAll('.chk-qty:checked');
            CheckedBoxes.forEach(function(chk) {
                // data-qty属性から数量を取得
                // getAttribute('data-qty')でHTML属性を取得
                const qty = parseInt(chk.getAttribute('data-qty')) || 0;
                // 合計に加算
                total += qty;
            });
            
            // 結果をアラートで表示（カンマ区切り）
            alert(`合計 ${total.toLocaleString()} です。`);
        });
    }

    // クリアボタンの確認ダイアログ
    const ClearForm = document.getElementById('clearForm');
    if (ClearForm) {
        ClearForm.addEventListener('submit', function(e) {
            // フォーム送信時に確認ダイアログを表示
            if (!confirm('本当に全件削除しますか？')) {
                // キャンセルされたらフォーム送信を中止
                e.preventDefault();
            }
        });
    }

    // 削除ボタンの確認ダイアログ
    const DeleteForms = document.querySelectorAll('.delete-form');
    DeleteForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            // 確認ダイアログを表示
            if (!confirm('この行を削除しますか？')) {
                // キャンセルされたら送信中止
                e.preventDefault();
            }
        });
    });

    // 更新ボタンの確認ダイアログ
    const UpdateForm = document.getElementById('updateForm');
    if (UpdateForm) {
        UpdateForm.addEventListener('submit', function(e) {
            // 確認ダイアログを表示
            if (!confirm('在庫リストを更新しますか？')) {
                e.preventDefault(); 
            }
        });
    }
});
