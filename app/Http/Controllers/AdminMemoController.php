<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Memo;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Exception;

class AdminMemoController extends Controller
{
    /**
     * コンストラクタでmiddleware('auth:admin');
     * を設定しているので、ログイン前では
     * メモ・ユーザーに関するデータにはアクセスできません。
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $memos = Memo::paginate(50);
        return view('admin.admin_memos', compact('memos'));
    }

}
