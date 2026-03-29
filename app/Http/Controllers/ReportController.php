<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Report;
use App\Models\Thread;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:255|in:spam,harassment,inappropriate,other',
            'reason_detail' => 'required_if:reason,other|nullable|string|max:255',
            'post_id' => ['nullable', Rule::exists('posts', 'id')->whereNull('deleted_at')],
            'thread_id' => ['nullable', Rule::exists('threads', 'id')->whereNull('deleted_at')],
        ], [
            'reason_detail.required_if' => 'その他の場合は、詳細な理由を入力してください。'
        ]);

        $target_id = $request->post_id ?? $request->thread_id;
        $target_type = $request->post_id ? 'post' : 'thread';

        if (!$target_id) {
            return back()->with('error', '通報対象が不明です。');
        }


        if ($target_type === 'post') {
            $post = Post::find($request->post_id);
            if (!$post) {
                return back()->with('error', '投稿が見つかりません。');
            }
            $this->authorize('view', $post->thread->board);
        } else {
            $thread = Thread::find($request->thread_id);
            if (!$thread) {
                return back()->with('error', 'スレッドが見つかりません。');
            }
            $this->authorize('view', $thread->board);
        }


        $finalReason = $request->reason;
        if ($request->reason === 'other') {
            $finalReason = 'その他: ' . $request->reason_detail;
        }

        try {
            DB::transaction(function () use ($request, $finalReason, $target_type, $target_id) {

                $exists = Report::where('user_id', Auth::id())
                    ->where(function ($query) use ($request) {
                        if ($request->post_id) {
                            $query->where('post_id', $request->post_id);
                        } else {
                            $query->where('thread_id', $request->thread_id);
                        }
                    })
                    ->lockForUpdate()
                    ->exists();

                if ($exists) {
                    throw new \Exception('duplicate_report');
                }

                $report = new Report();
                $report->user_id = Auth::id();
                $report->post_id = $request->post_id;
                $report->thread_id = $request->thread_id;
                $report->reason = $finalReason;
                $report->save();


                $thres_hold = 10;
                if ($target_type === 'post') {
                    if (Report::where('post_id', $target_id)->count() >= $thres_hold) {
                        Post::find($target_id)?->delete();
                    }
                } else {
                    if (Report::where('thread_id', $target_id)->count() >= $thres_hold) {
                        Thread::find($target_id)?->delete();
                    }
                }
            });

            return back()->with('success', '報告を受け付けました。');
        } catch (\Exception $e) {
            if ($e->getMessage() === 'duplicate_report') {
                return back()->with('error', 'この投稿は既に通報済みです。');
            }
            Log::error($e);
            return back()->with('error', '処理に失敗しました。既に削除されているか、システムエラーの可能性があります。');
        }
    }
}
