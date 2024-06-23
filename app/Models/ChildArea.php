<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChildArea extends Model
{
    use HasFactory;
    protected $table = 'child_areas';
    protected $primaryKey = 'child_area_id';
    protected $fillable = ['child_area_name', 'parent_area']; 

    public function getChildAreas($area, $tuKhoa, $sapXep){
        $list = DB::table('child_areas')
        ->select('child_areas.*')
        ->where('child_areas.parent_area', '=', $area);

        if(!empty($tuKhoa))
            $list = $list->where('child_area_name', 'LIKE', '%' . $tuKhoa . '%');
        if(!empty($sapXep))
            $list = $list->orderBy('child_area_name', $sapXep);
        else
            $list = $list->orderBy('child_area_name', 'ASC');

        $list = $list->get();

        return $list;
    }

    public function checkChildArea($childAreaName){
        return DB::table($this->table)
        ->where('child_area_name' ,'=', $childAreaName)
        ->get();
    }
}
