<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dataservices\BigSearchDataservice;

class BigSearchController extends Controller
{
    public function index(Request $request)
    {
        $searchStr = $request->post('searchStr');
        if (!$searchStr||(trim($searchStr)=="")) 
          return redirect()->back();

        return view('searchresult',BigSearchDataservice::index($searchStr));
    }
}
