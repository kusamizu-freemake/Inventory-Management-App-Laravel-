<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>在庫管理リスト</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: sans-serif; margin: 20px; }
        h1 { margin-bottom: 20px; }

        .quantity-area {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }
        button, input[type="number"] {
            height: 36px;
            font-size: 16px;
        }
        input[type="number"] {
            width: 100px;
            text-align: right;
            font-weight: bold;
        }
        .time {
            margin-left: 20px;
            font-weight: bold;
            font-size: 16px;
        }
        textarea {
            width: 250px;
            height: 60px;
            font-size: 14px;
            vertical-align: middle;
        }
        .add-area {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .add-area button {
            height: 60px;
            padding: 0 15px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        tr:nth-child(odd) { background-color: #f0f8ff; }
        tr:nth-child(even) { background-color: #ffffff; }
        tr.checked { background-color: #c8e6c9; }

        .btn-area {
            margin-top: 15px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .btn-area button {
            padding: 6px 15px;
        }
    </style>
</head>
<body>
    <h1>在庫管理リスト</h1>

    <form id="addForm" action="{{ route('add') }}" method="POST">
        @csrf
        <div class="quantity-area">
            <button type="button" id="decrement">−</button>
            <input type="number" id="quantity" name="quantity" value="0" min="0" max="9999">
            <button type="button" id="increment">＋</button>
            <span class="time" id="currentTime"></span>
            <input type="hidden" id="hiddenTime" name="time">
        </div>

        <div class="add-area">
            <textarea name="comment" placeholder="コメントを入力"></textarea>
            <button type="submit">追加</button>
        </div>
    </form>

    <table>
        <tr>
            <th>選択</th>
            <th>時刻</th>
            <th>数量</th>
            <th>コメント</th>
        </tr>
        @foreach($items as $item)
            <tr data-id="{{ $item['id'] }}" class="{{ $item['checked'] ? 'checked' : '' }}">
                <td><input type="checkbox" class="checkItem" {{ $item['checked'] ? 'checked' : '' }}></td>
                <td>{{ $item['time'] }}</td>
                <td>{{ number_format($item['quantity']) }}</td>
                <td>{{ $item['comment'] }}</td>
            </tr>
        @endforeach
    </table>

    <div class="btn-area">
        <form action="{{ route('clear') }}" method="POST">@csrf<button type="submit">クリア</button></form>
        <button id="showTotal">合計数量</button>
    </div>

    <script>
        // === 現在時刻の更新 ===
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('ja-JP');
            document.getElementById('currentTime').textContent = timeString;
            document.getElementById('hiddenTime').value = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // === 数量増減 ===
        const qtyInput = document.getElementById('quantity');
        document.getElementById('increment').addEventListener('click', () => {
            let val = parseInt(qtyInput.value.replace(/,/g, '')) || 0;
            if (val < 9999) val++;
            qtyInput.value = val.toLocaleString();
        });
        document.getElementById('decrement').addEventListener('click', () => {
            let val = parseInt(qtyInput.value.replace(/,/g, '')) || 0;
            if (val > 0) val--;
            qtyInput.value = val.toLocaleString();
        });

        // === チェック状態 ===
        document.querySelectorAll('.checkItem').forEach(chk => {
            chk.addEventListener('change', e => {
                const row = e.target.closest('tr');
                row.classList.toggle('checked', e.target.checked);
                saveCheckState();
            });
        });

        function saveCheckState() {
            const data = [];
            document.querySelectorAll('tr[data-id]').forEach(row => {
                data.push({
                    id: row.dataset.id,
                    checked: row.classList.contains('checked')
                });
            });
            // セッション更新はオプション（必要に応じて追加）
        }

        // === 合計数量 ===
        document.getElementById('showTotal').addEventListener('click', async () => {
            const res = await fetch('{{ route("total") }}', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").content}
            });
            const data = await res.json();
            alert(`合計 ${data.total.toLocaleString()} です。`);
        });
    </script>
</body>
</html>
