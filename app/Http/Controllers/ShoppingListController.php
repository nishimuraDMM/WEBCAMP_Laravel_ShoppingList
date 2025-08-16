<?php

declare(strict_types=1);
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ShoppingRegisterPostRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\shopping_list as TaskModel;

use Illuminate\Support\Facades\DB;
use App\Models\CompletedShoppingList as CompletedTaskModel;


class ShoppingListController extends Controller
{
    /**
     * タスク一覧ページ を表示する
     * 
     * @return \Illuminate\View\View
     */
    public function list()
    {
        
        // 1Page辺りの表示アイテム数を設定
        $per_page = 2;
        
        // 一覧の取得
        $list = TaskModel::where('user_id', Auth::id())
                    
                   
                         ->orderBy('created_at')
                         ->paginate($per_page);
                        // ->get();
        /*
        $sql = TaskModel::where('user_id', Auth::id())
                        ->orderBy('priority', 'DESC')
                        ->orderBy('period')
                        ->orderBy('created_at')
                        ->toSql();
        //echo "<pre>\n"; var_dump($sql, $list); exit;
        var_dump($sql);
        */
        
        return view('shopping_list', ['list' => $list]);
    }

    /**
     * タスクの新規登録
     */
    public function register(ShoppingRegisterPostRequest $request)
    {
        // validate済みのデータの取得
        $datum = $request->validated();
        //
        //$user = Auth::user();
        //$id = Auth::id();
        //var_dump($datum, $user, $id); exit;

        // user_id の追加
        $datum['user_id'] = Auth::id();

        // テーブルへのINSERT
        try {
            $r = TaskModel::create($datum);
            // var_dump($r); exit;
        } catch(\Throwable $e) {
            // XXX 本当はログに書く等の処理をする。今回は一端「出力する」だけ
            echo $e->getMessage();
            exit;
        }

        // タスク登録成功
        $request->session()->flash('front.task_register_success', true);

        // リダイレクト
        return redirect('/shopping_list/list');

    }
     /**
     * 削除処理
     */
    public function delete(Request $request, $task_id)
    {
        // task_idのレコードを取得する
        $task = $this->getTaskModel($task_id);

        // タスクを削除する
        if ($task !== null) {
            $task->delete();
            $request->session()->flash('front.task_delete_success', true);
        }

        // 一覧に遷移する
        return redirect('/shopping_list/list');
    }
    public function complete(Request $request, $task_id)
    {
        /* タスクを完了テーブルに移動させる */
        try {
            // トランザクション開始
            DB::beginTransaction();

            // task_idのレコードを取得する
            $task = $this->getTaskModel($task_id);
            if ($task === null) {
                // task_idが不正なのでトランザクション終了
                throw new \Exception('');
            }

            // tasks側を削除する
            $task->delete();
            //var_dump($task->toArray()); exit;

            // completed_tasks側にinsertする
            $dask_datum = $task->toArray();
            unset($dask_datum['created_at']);
            unset($dask_datum['updated_at']);
            $r = CompletedTaskModel::create($dask_datum);
            if ($r === null) {
                // insertで失敗したのでトランザクション終了
                throw new \Exception('');
            }
            // echo '処理成功'; exit;

            // トランザクション終了
            DB::commit();
            // 完了メッセージ出力
            $request->session()->flash('front.task_completed_success', true);
        } catch(\Throwable $e) {
            // var_dump($e->getMessage()); exit;
            // トランザクション異常終了
            DB::rollBack();
            // 完了失敗メッセージ出力
            var_dump($e->getMessage()); exit;
            $request->session()->flash('front.task_completed_failure', true);
        }

        // 一覧に遷移する
        return redirect('/shopping_list/list');
    }
    protected function getTaskModel($task_id)
    {
        // task_idのレコードを取得する
        $task = TaskModel::find($task_id);
        if ($task === null) {
            return null;
        }
        // 本人以外のタスクならNGとする
        if ($task->user_id !== Auth::id()) {
            return null;
        }
        
        return $task;
    }
 }