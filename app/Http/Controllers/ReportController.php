<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Report;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'reason' => 'required|string',
            'reason_detail' => 'required_if:reason,other|nullable|string|max:255',
            'post_id' => 'nullable|exists:posts,id',
            'thread_id' => 'nullable|exists:threads,id',
        ], [
            'reason_detail.required_if' => 'その他の場合は、詳細な理由を入力してください。'
        ]);

        $target_id = $request->post_id ?? $request->thread_id;
        $target_type = $request->post_id ? 'post' : 'thread';

        if (!$target_id) {
            return back()->with('error', '通報対象が不明です。');
        }

        $exists = Report::where('user_id', Auth::id())
            ->where(function ($query) use ($request) {
                if ($request->post_id) {
                    $query->where('post_id', $request->post_id);
                } else {
                    $query->where('thread_id', $request->thread_id);
                }
            })->exists();

        if ($exists) {
            return back()->with('error', 'この投稿は既に通報済みです。');
        }

        // 2. 保存する理由の決定
        // 「その他」なら入力された詳細を、それ以外なら選択肢のラベル（またはvalue）を保存
        $finalReason = $request->reason;
        if ($request->reason === 'other') {
            $finalReason = 'その他: ' . $request->reason_detail;
        }

        try {
            DB::transaction(function () use ($request, $finalReason, $target_type, $target_id,) {
                Report::create([
                    'user_id' => Auth::id(),
                    'post_id' => $request->post_id,
                    'thread_id' => $request->thread_id,
                    'reason' => $finalReason,
                ]);

                // 5件で削除のロジック
                $thres_hold = 5;
                if ($target_type === 'post') {
                    if (Report::where('post_id', $target_id)->count() >= $thres_hold) {
                        Post::find($target_id)->delete();
                    }
                } else {
                    if (Report::where('thread_id', $target_id)->count() >= $thres_hold) {
                        Thread::find($target_id)->delete();
                    }
                }
            });

            return back()->with('success', '報告を受け付けました。');
        } catch (\Exception $e) {
            Log::error($e);
            return back()->with('error', '処理に失敗しました。既に削除されているか、システムエラーの可能性があります。');
        }
    }
}
