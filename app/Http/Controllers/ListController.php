<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ListController extends Controller
{
    // ホームページ表示
    public function index(Request $request)
    {
        $items = $request->session()->get('items', []);
        return view('inventory', compact('items'));
    }

    // アイテム追加
    public function add(Request $request)
    {
        $items = $request->session()->get('items', []);
        $id = Str::random(10);

        // JSから送られた現在時刻をそのまま使う
        $items[] = [
            'id' => $id,
            'time' => $request->input('time', now()->format('H:i:s')),
            'quantity' => (int)str_replace(',', '', $request->input('quantity', 0)),
            'comment' => $request->input('comment', ''),
            'checked' => false,
        ];

        $request->session()->put('items', $items);
        return redirect()->route('home');
    }

    // アイテム全削除
    public function clear(Request $request)
    {
        $request->session()->forget('items');
        return redirect()->route('home');
    }

    // 合計数量取得
    public function total(Request $request)
    {
        $items = $request->session()->get('items', []);

        // チェックされた行の数量を合計
        $total = 0;
        foreach ($items as $item) {
            if (!empty($item['checked'])) {
                $total += (int)$item['quantity'];
            }
        }

        return response()->json(['total' => $total]);
    }

    //  チェック状態切替
    public function toggleCheck(Request $request, $id)
    {
        $items = $request->session()->get('items', []);
        
        foreach ($items as &$item) {
            if ($item['id'] === $id) {
                $item['checked'] = !$item['checked'];
                break;
            }
        }
        
        $request->session()->put('items', $items);
        return response()->json(['success' => true]);
    }


    // アイテム削除
    public function delete(Request $request, $id)
    {
        $items = $request->session()->get('items', []);
        $items = array_filter($items, function ($item) use ($id) {
            return $item['id'] !== $id;
        });
        $request->session()->put('items', array_values($items));
        return redirect()->route('home');
    }
}