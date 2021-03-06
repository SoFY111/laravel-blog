<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

//Models
use App\Models\Category;
use App\Models\Article;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('back.categories.index', compact('categories'));
    }

    public function create(Request $request)
    {
        $isExist = Category::where('slug', Str::slug($request->category))->first();

        if($isExist) {
            toastr()->error($request->category. ' adında bir kategori zaten mevcut.');
            return redirect()->back();
        }

        $category = new Category;
        $category->name = $request->category;
        $category->slug = Str::slug($request->category);
        $category->save();
        toastr()->success('Kategori başarılı bir şekilde oluşturuldı.');
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $isSlug = Category::where('slug', Str::slug($request->slug))->whereNotIn('id', [ $request->id])->first();
        $isName = Category::where('name', Str::slug($request->category))->whereNotIn('id', [ $request->id])->first();

        if($isSlug or $isName ) {
            toastr()->error($request->category. ' adında bir kategori zaten mevcut.');
            return redirect()->back();
        }

        $category = Category::findOrFail($request->id);
        $category->name = $request->category;
        $category->slug = Str::slug($request->slug);
        $category->save();
        toastr()->success('Kategori başarılı bir şekilde güncellendi.');
        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $category = Category::findOrFail($request->id);
        if($category->id == 1){
            toastr()->error('Bu kategori silinemez.');
            return redirect()->back();
        }

        $defaultCategory=Category::find(1);
        $categoryArticleCount = $category->articleCount();
        $message = "Kategori başarılı bir şekilde silindi.";
        
        if($categoryArticleCount > 0){
            Article::where('categoryId', $category->id)->update(['categoryId' => 1]);
            $message = "Kategori başarılı bir şekilde silindi. Bu kategoriye ait ".$categoryArticleCount." adet makale ". $defaultCategory->name ." kategorisine taşındı.";
        }

        $category->delete();
        toastr()->success($message);
        return redirect()->back();
    }

    public function statusSwitch(Request $request)
    {
        $category = Category::findOrFail($request->id);
        if($request->statu == 'false' ) $status = 0;
        else $status = 1;
        $category->status = $status;
        $category->save();
    }

    public function getData(Request $request)
    {
        $category = Category::findOrFail($request->id);
        return response()->json($category);
    }
}
