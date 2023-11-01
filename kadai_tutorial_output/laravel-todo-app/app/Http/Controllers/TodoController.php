<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Goal;   
/* ↑ AppフォルダのModelsフォルダのGoal.php（モデルのファイル）を使うよ!と宣言
     宣言することで、このファイル内でGoalと記述するだけでGoalクラスを呼び出せるようになる
*/
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  
/* ↑ Authファサードを利用することで、「現在ログイン中のユーザー」を取得
     Authファサードは、クラスをインスタンス化しなくても、Auth::user()を記述することで、
     現在ログイン中のユーザー（Userモデルのインスタンス）を取得できる。
*/

class TodoController extends Controller
{    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Goal $goal) {  
    
        $request->validate([
            'content' => 'required',
        ]);

        $todo = new Todo();
        $todo->content = $request->input('content');
        $todo->user_id = Auth::id();  //←現在ログイン中のユーザーが持つ目標をすべて取得
        $todo->goal_id = $goal->id;
        $todo->done = false;
        $todo->save();        

        $todo->tags()->sync($request->input('tag_ids'));
        /* ↑sync()メソッドを使い、「tag_ids」に入ったidがtodoインスタンスと紐づき、tag_todoテーブル（中間テーブル）に保存される
            この場合、todoモーダル上からチェックを入れたタグのidが「tag_ids」に入るのかな…
        */
        return redirect()->route('goals.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Goal $goal, Todo $todo) {
        $request->validate([
            'content' => 'required',
        ]);

        $todo->content = $request->input('content');
        $todo->user_id = Auth::id();
        $todo->goal_id = $goal->id;
        $todo->done = $request->boolean('done', $todo->done);
        $todo->save();

        // 「完了」と「未完了」の切り替え時でないとき（通常の編集時）にのみタグを変更する
        if (!$request->has('done')) {
            $todo->tags()->sync($request->input('tag_ids'));
        };   

        return redirect()->route('goals.index');  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Goal $goal, Todo $todo) {  
        $todo->delete();
 
        return redirect()->route('goals.index');        
    }
}
