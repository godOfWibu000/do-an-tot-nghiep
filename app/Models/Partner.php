<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Partner extends Model
{
    use HasFactory;

    protected $table = 'partners';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'address',
        'phone_number',
        'logo',
        'company_name',
        'tax_number',
        'company_address',
        'company_phone_number',
        'company_email',
        'company_website',
        'partner_status'
    ];

    public function getAllPartners($tuKhoa, $sapXep, $trangThai){
        $list = DB::table($this->table)
        ->join('users', 'partners.id', '=', 'users.id')
        ->select('users.email', 'partners.id', 'partners.name', 'partners.address', 'partners.phone_number', 'partners.partner_status');

        if(!empty($tuKhoa))
            $list = $list->where(function($query) use ($tuKhoa) {
                $query->where('partners.name', 'LIKE', '%' . $tuKhoa . '%')
                ->orWhere('partners.address', 'LIKE', '%' . $tuKhoa . '%')
                ->orWhere('partners.phone_number', 'LIKE', '%' . $tuKhoa . '%');
            });
        if(!empty($trangThai))
            $list = $list->where('partners.partner_status', '=', $trangThai);
        if(!empty($sapXep))
            $list = $list->orderBy('partners.name', $sapXep);
        else
            $list = $list->orderBy('partners.name', 'ASC');

        $list = $list->paginate(10);

        return $list;
    }

    public function getSinglePartner($id){
        return DB::table('partners')
        ->join('users', 'partners.id', '=', 'users.id')
        ->select('partners.*', 'users.email')
        ->where('partners.id', $id)
        ->first();
    }
}
