<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ChildArea;
use Illuminate\Http\Request;

class ChildAreaAPIController extends Controller
{
    private $childArea;

    public function __construct()
    {
        $this->childArea = new ChildArea();
    }

    public function getChildAreas($area){
        $childAreasList = $this->childArea->getChildAreas($area, null, null);
        return $childAreasList;
    }
}
