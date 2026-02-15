<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;

class UniversityController extends Controller
{
  /**
   * 大学一覧を表示（地域別・検索機能付き）
   */
  public function index(Request $request)
  {
    // コンポーネントの input name="keyword" に合わせて変更
    $keyword = $request->input('keyword');

    // 1. 地域（Region）を全件取得
    // 2. その地域に紐づく大学（Universities）を検索条件で絞り込んで一緒に取得（Eager Loading）
    $regions = Region::with(['universities' => function ($query) use ($keyword) {
      // 検索ワードがある場合、大学名またはドメインで絞り込み
      if ($keyword) {
        $query->where(function ($q) use ($keyword) {
          $q->where('name', 'like', "%{$keyword}%")
            ->orWhere('email_domain', 'like', "%{$keyword}%");
        });
      }
    }])
      ->orderBy('id', 'asc') // 北海道(1)から順に並べる
      ->get();

    // viewに渡す変数名も keyword に統一
    return view('universities.index', compact('regions', 'keyword'));
  }
}
