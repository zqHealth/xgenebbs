<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function show(Category $category){
        // 获取分类 ID 关联的话题，按照每 20 条分页, 懒加载 with()
        $topics = Topic::with('user', 'category')->where('category_id', $category->id)->paginate(30);
        // 传参变量话题和分类到模板中
        return view('topics.index', compact('topics', 'category'));
    }
}
