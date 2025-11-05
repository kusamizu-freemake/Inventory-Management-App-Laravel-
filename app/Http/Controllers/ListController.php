<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ListController extends Controller
{
    // ホームページ表示
    public function ShowInventory(Request $request)
    {
        $InventoryItems = $request->session()->get('InventoryItems', []);
        $Updated = $request->session()->get('Updated', false);
        return view('inventory', compact('InventoryItems', 'Updated'));
    }

    // アイテム追加
    public function AddItem(Request $request)
    {
        $InventoryItems = $request->session()->get('InventoryItems', []);
        $NewItemId = Str::random(10);

        // JSから送られた現在時刻をそのまま使う
        $InventoryItems[] = [
            'id' => $NewItemId,
            'time' => $request->input('time', now()->format('H:i:s')),
            'quantity' => (int)str_replace(',', '', $request->input('quantity', 0)),
            'comment' => $request->input('comment', ''),
            'checked' => false,
        ];

        $request->session()->put('InventoryItems', $InventoryItems);
        return redirect()->route('ShowInventory');
    }

    // アイテム全削除
    public function ClearAllItems(Request $request)
    {
        $request->session()->forget('InventoryItems');
        return redirect()->route('ShowInventory');
    }

    // 合計数量取得
    public function CalculateTotalQuantity(Request $request)
    {
        $inventoryItems = $request->session()->get('InventoryItems', []);

        // チェックされた行の数量を合計
        $TotalQuantity = 0;
        foreach ($inventoryItems as $inventoryItems) {
            if (!empty($inventoryItems['checked'])) {
                $TotalQuantity += (int)$inventoryItems['quantity'];
            }
        }

        return response()->json(['TotalQuantity' => $TotalQuantity]);
    }

    //  チェック状態切替
    public function ToggleCheck(Request $request, $id)
    {
        $InventoryItems = $request->session()->get('InventoryItems', []);
        
        foreach ($InventoryItems as &$InventoryItems) {
            if ($InventoryItems['id'] === $id) {
                $InventoryItems['checked'] = !$InventoryItems['checked'];
                break;
            }
        }
        
        $request->session()->put('InventoryItems', $InventoryItems);
        return response()->json(['success' => true]);
    }


    // アイテム削除
    public function DeleteItem(Request $request, $ItemId)
    {
        $InventoryItems = $request->session()->get('InventoryItems', []);
        $InventoryItems = array_filter($InventoryItems, function ($InventoryItems) use ($ItemId) {
            return $InventoryItems['id'] !== $ItemId;
        });
        $request->session()->put('InventoryItems', array_values($InventoryItems));
        return redirect()->route('ShowInventory');
    }

    // 在庫リスト更新
    public function UpdateItem(Request $request)
    {
        $InventoryItems = $request->session()->get('InventoryItems', []);

        $UpdateQuantities = $request->input('quantity', []); //
        $UpdateContents = $request->input('content', []); //
        // 指定されたアイテムを更新
        foreach ($InventoryItems as &$InventoryItem) {
            // 更新処理
            if (isset($UpdateQuantities[$InventoryItem['id']])) {
                $InventoryItem['quantity'] = (int)str_replace(',', '', $UpdateQuantities[$InventoryItem['id']]);
            }
            if (isset($UpdateContents[$InventoryItem['id']])) {
                $InventoryItem['comment'] = $UpdateContents[$InventoryItem['id']];
            }
        }
        // 更新結果をセッションに反映
        $request->session()->put('InventoryItems', $InventoryItems);
        return redirect()->route('ShowInventory')->with('Updated', true);
    }
}