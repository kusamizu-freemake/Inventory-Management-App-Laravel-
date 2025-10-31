<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>在庫管理リスト</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- 外部CSSファイルの読み込み --}}
    <link rel="stylesheet" href="{{ asset('app.css') }}">
    {{-- 外部JavaScriptファイルの読み込み --}}
    <script src="{{ asset('app.js') }}" defer></script>
</head>
<body>
    <h1>在庫管理リスト</h1>

    {{-- アイテム追加フォーム --}}
    <form id="addForm" action="{{ route('add') }}" method="POST">
        @csrf
        
        {{-- 数量入力エリア --}}
        <div class="quantity-area">
            <button type="button" id="decrement">−</button>
            <input type="number" id="quantity" name="quantity" value="0" min="0" max="9999">
            <button type="button" id="increment">＋</button>
            <span class="time" id="currentTime"></span>
            <input type="hidden" id="hiddenTime" name="time">
        </div>

        {{-- コメント入力エリア --}}
        <div class="add-area">
            <textarea name="comment" placeholder="コメントを入力"></textarea>
            <button type="submit">追加</button>
        </div>
    </form>

    {{-- アイテム一覧表示 --}}
    <table>
        <tr>
            <th>選択</th>
            <th>時刻</th>
            <th>数量</th>
            <th>コメント</th>
            <th>削除</th>
        </tr>
        
        {{-- アイテムをループ表示 --}}
        @foreach($items as $item)
            {{-- アイテム行 --}}
            <tr data-id="{{ $item['id'] }}" class="{{ $item['checked'] ? 'checked' : '' }}">
                <td><input type="checkbox" class="chk-qty" data-qty="{{ $item['quantity'] }}"></td>
                <td>{{ $item['time'] }}</td>
                <td class="qty-cell">{{ number_format($item['quantity']) }}</td>
                <td>{{ $item['comment'] }}</td>
                <td>
                    {{-- 削除フォーム --}}
                    <form action="{{ route('delete', $item['id']) }}" method="POST" style="display:inline;" class="delete-form">
                        @csrf
                        <button type="submit" class="delete-btn">削除</button>
                    </form>
                </td>
            </tr>
        @endforeach {{-- アイテムループ終了 --}}
    </table>

    {{-- ボタンエリア(下部) --}}
    <div class="btn-area">
        {{-- 合計数量表示ボタン --}}
        <button id="showTotal">合計数量</button>
        {{-- クリア全件削除ボタン --}}
        <form action="{{ route('clear') }}" method="POST" id="clearForm" style="display:inline;">
            @csrf
            <button type="submit">クリア</button>
        </form>
    </div>
</body>
</html>