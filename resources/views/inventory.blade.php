<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>在庫管理リスト</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Viteを使用してアセットを読み込み --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <h1>在庫管理リスト</h1>

    {{-- アイテム追加フォーム --}}
    <form id="addForm" action="{{ route('AddItem') }}" method="POST">
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

     {{-- 更新ボタン --}}
    <form action="{{ route('UpdateItem') }}" method="POST" id="updateForm" style="display:inline;">
        @csrf
        <button type="submit" id="updateBtn">更新</button>
    
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
            @foreach($InventoryItems as $InventoryItem)
                {{-- アイテム行 --}}
                <tr data-id="{{ $InventoryItem['id'] }}" class="{{ $InventoryItem['checked'] ? 'checked' : '' }}">
                    <!-- チェックボックス（編集不可） -->
                    <td><input type="checkbox" class="chk-qty" data-qty="{{ $InventoryItem['quantity'] }}"></td>
                    <!-- 時刻（編集不可） -->
                    <td>{{ $InventoryItem['time'] }}</td>
                    <!-- 数量（編集可能） -->
                    <td>
                        <input type="number" name="quantity[{{ $InventoryItem['id'] }}]" value="{{ $InventoryItem['quantity'] }}" min="0" max="9999">
                    </td>
                    <!-- コメント（編集可能） -->
                    <td>
                        <textarea name="content[{{ $InventoryItem['id'] }}]">{{ $InventoryItem['comment'] }}</textarea>
                    </td>
                    <!-- 削除ボタン -->
                    <td>
                        {{-- 削除フォーム --}}
                        <form action="{{ route('DeleteItem', $InventoryItem['id']) }}" method="POST" style="display:inline;" class="delete-form">
                            @csrf
                            <button type="submit" class="delete-btn">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach {{-- アイテムループ終了 --}}
        </table>
    </form>

    {{-- ボタンエリア(下部) --}}
    <div class="btn-area">
        {{-- 合計数量表示ボタン --}}
        <button id="showTotal">合計数量</button>
        {{-- クリア全件削除ボタン --}}
        <form action="{{ route('ClearAllItems') }}" method="POST" id="clearForm" style="display:inline;">
            @csrf
            <button type="submit">クリア</button>
        </form>
    </div>
</body>
</html>