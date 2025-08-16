<?php

declare(strict_types=1);
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CompletedTask as CompletedTaskModel;
use Illuminate\Support\Facades\Hash;
use App\Models\User as UserModel;
class UserController extends Controller{
    public function index(){
        return view('user.register');
    }
    public function register(UserRegisterPost $request)
    {
        // validate済みのデータの取得
        $datum = $request->validated();
        //
        //$user = Auth::user();
        //$id = Auth::id();
        //var_dump($datum, $user, $id); exit;

        // user_id の追加
        $datum['password'] = Hash::make($datum['password']);
        // テーブルへのINSERT
        try {
            $r = UserModel::create($datum);
        } catch(\Throwable $e) {
            // XXX 本当はログに書く等の処理をする。今回は一端「出力する」だけ
            echo $e->getMessage();
            exit;
        }

        // タスク登録成功
        $request->session()->flash('front.task_register_success', true);

        // リダイレクト
        return redirect('/');

    }

}