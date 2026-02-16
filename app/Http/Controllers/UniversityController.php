<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;

class UniversityController extends Controller
{

  public function index(Request $request)
  {
    $keyword = $request->input('keyword');

    $regions = Region::with(['universities' => function ($query) use ($keyword) {
      if ($keyword) {
        $escapedKeyword = str_replace(['%', '_'], ['\%', '\_'], $keyword);
        $query->where(function ($q) use ($escapedKeyword) {
          $q->where('name', 'like', "%{$escapedKeyword}%")
            ->orWhere('email_domain', 'like', "%{$escapedKeyword}%");
        });
      }
    }])
      ->orderBy('id', 'asc')
      ->get();


    return view('universities.index', compact('regions', 'keyword'));
  }
}
