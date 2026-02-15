<?php

namespace App\Http\Controllers;

use App\Models\UniversityRequest;
use Illuminate\Http\Request;

class UniversityRequestController extends Controller
{
    // リクエストフォームの表示
    public function create()
    {
        return view('university_requests.create');
    }

    // リクエストの保存処理
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'], // ドメイン確認のためemail形式
            'verification_url' => ['required', 'url', 'max:255'],
        ]);

        UniversityRequest::create($validated);

        return redirect()->route('university-request.create')
            ->with('status', '大学の追加リクエストを送信しました。管理者が確認後、反映されます。');
    }
}
