<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ListController extends Controller
{
    public function index(Request $request)
    {
        $items = $request->session()->get('items', []);
        return view('welcome', compact('items'));
    }

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

    public function clear(Request $request)
    {
        $request->session()->forget('items');
        return redirect()->route('home');
    }

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
}
