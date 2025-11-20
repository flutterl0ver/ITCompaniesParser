<?php

namespace App\Http\Controllers;

use App\Services\CompaniesUpdateSerivce;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(Request $request, CompaniesUpdateSerivce $updateService)
    {
        $updateService->updateAll();
        return redirect()->back();
    }
}
