@extends('admin.layout')

{{-- メインコンテンツ --}}
@section('contents')
      
 <h1>ユーザ一覧</h1>
 <table border="1">
    <tr>
        <th>ユーザーID</th>
        <th>ユーザー名</th>
        <th>購入した「買うもの」の数</th>

    </tr>
  
    @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}
            <td>{{ $user->name }}
            <td>{{ $user->task_num }}
    @endforeach
 </table>
  @endsection