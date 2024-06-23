<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Categories extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'category_id';

    public function getCats(){
        $list = DB::table($this->table)
        ->select('categories.*')
        ->get();

        return $list;
    }

    public function getSingleCat($id){
        return DB::table($this->table)
        ->select('categories.*')
        ->where('category_id', '=', $id)
        ->first();
    }

    public function filterCats($tuKhoa, $sapXep){
        $list = DB::table($this->table)
        ->select('categories.*');
        if(!empty($tuKhoa))
            $list = $list->where('category_name', 'LIKE', '%'. $tuKhoa .'%');
        if(!empty($sapXep))
            $list = $list->orderBy('category_name', $sapXep);
        else
            $list = $list->orderBy('category_name', 'ASC');
        $list = $list->get();

        return $list;
    }

    public function getImageCategory($id){
        return DB::table($this->table)
        ->select('categories.category_image')
        ->where('category_id', '=', $id)
        ->first();
    }

    public function checkCategories($categoryName){
        $list = DB::table($this->table)
        ->where('category_name' ,'=', $categoryName)
        ->get();

        return $list;
    }

    public function addCategory($categoryName, $fileName){
        return DB::table($this->table)
        ->insert([
            'category_name' => $categoryName,
            'category_image' => $fileName
        ]);
    }

    public function updateCategory($id, $categoryName, $fileName){
        return DB::table($this->table)
        ->where('category_id', '=', $id)
        ->update([
            'category_name' => $categoryName,
            'category_image' => $fileName
        ]);
    }

    public function deleteCategory($id){
        return DB::table($this->table)
        ->where('category_id', '=', $id)
        ->delete();
    }
}
