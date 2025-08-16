<?php

declare(strict_types=1);
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ShoppingRegisterPostRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\CompletedShoppingList as TaskModel;

class CompletedShoppingListController extends Controller
{
    /**
     * タスク一覧ページ を表示する
     * 
     * @return \Illuminate\View\View
     */
    public function list()
    {$per_page=2;

        // 一覧の取得
        $list = TaskModel::where('user_id', Auth::id())
                      
                        ->orderBy('created_at')
     ->paginate($per_page);
     
     $sql = TaskModel::where('user_id', Auth::id())
                 
                        ->orderBy('created_at')
                        ->toSql();
        //echo "<pre>\n"; var_dump($sql, $list); exit;
        // var_dump($sql);
        
        return view('completed_shopping_list', ['list' => $list]);
    }

  
}