<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function storeCategory(Request $request)
    {
        $user = $request->user();
        if ($user->role->name != 'admin') {
            return response()->json(['message' => 'Вы не имеете прав администратора для создания категории'], 403);
        }
        $category = Category::create(['name' => $request->name]);
        return response()->json([
            'message' => 'Категория создана',
            'category' => $category
        ]);
        $user->update([
            'setup_complete' => true
        ]);
    }
    public function deleteCategory(Request $request, $id){
        $user = $request->user();
        if ($user->role->name != 'admin') {
            return response()->json(['message' => 'Вы не имеете прав администратора для создания категории'], 403);
        }
        $category = Category::find($id);
        if($category)
        {
            $category->delete();
            return response()->json(['message' => 'Категория удалена успешно'], 200);
        }
        else{
            return response()->json(['message' => 'Такой категории не существует'], 400);
        }
    }
    public function getCategories(Request $request)
    {
        $categories = Category::all();
        return response()->json($categories);
    }
}
