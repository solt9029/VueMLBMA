<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;
use Auth;
use DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //isbnのクエリを取得、13または10文字だったら登録する→validation？
    public function register(Request $request)
    {
        $this->validate($request, [
            'isbn' => 'required|size:13'
        ]);

        $isbn = $request->input('isbn');
        $name = Book::getName($isbn);//名前を取得する

        //名前が取得できなかったら
        if(!$name){
            $name = '【＊＊＊書籍名自動取得不可＊＊＊】';
        }

        $info = Book::getInfo($isbn);

        $author = '';
        if (array_key_exists('authors', $info)) {
            foreach ($info['authors'] as $a) {
                $author .= $a;
                $author .= '/';
            }
        }

        $published = '';
        if (array_key_exists('publishedDate', $info)) {
            $published = $info['publishedDate'];
        }

        $description = '';
        if (array_key_exists('description', $info)) {
            $description = $info['description'];
        }

        $page = null;
        if (array_key_exists('pageCount', $info)) {
            $page = $info['pageCount'];
        }

        $thumbnail = '';
        if (array_key_exists('imageLinks', $info)) {
            if (array_key_exists('thumbnail', $info['imageLinks'])) {
                $thumbnail = $info['imageLinks']['thumbnail'];
            }
        }
        
        //そのインデックスが存在してから代入した方がよさそうだけど、とりあえずこれで
        Book::create([
            "user_id"=>Auth::user()->id,
            "isbn"=>$request->input("isbn"),
            "name"=>$name,
            'author' => $author,
            'published' => $published,
            'description' => $description,
            'page' => intval($page),
            'thumbnail' => $thumbnail
        ]);

        return;
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $id=intval($request->input("id"));
        $book=Book::find($id);

        //削除しようとしている本のユーザIDとログインしているユーザのIDが一致していたら削除する
        if (Auth::user()->id === $book->user_id) {
            $book->delete();
            return;
        }

        return false;
    }

    //ページネーションしながら出してほしい
    public function show(Request $request)
    {
        $page_num = 10;//ページネーションの数

        $query = DB::table('books')
            ->orderBy('created_at', 'desc')
            ->join('users', 'books.user_id', '=', 'users.id');

        if ($request->input('user_id')) {
            $query = $query->where('books.user_id', '=', $request->input('user_id'));
        }

        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
            $query = $query->where('books.name', 'like', "%{$keyword}%");
        }

        return $query->select('books.*', 'users.handle')->paginate($page_num);
    }

    public function state(Request $request)
    {
        $this->validate($request, [
            'isbn' => 'required|size:13'
        ]);

        return Book::getState($request->input("isbn"), $request->input("campus"));
    }

    public function test()
    {
        // $book = DB::table('books')->join('users', 'books.user_id', '=', 'users.id')->where()->select('books.*', 'users.handle')->paginate();
        // return json_encode($book);
        // return Auth::user()->id;
    }
}
