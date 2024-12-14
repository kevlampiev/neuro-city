<?php

namespace App\Http\Controllers\Reference;

use App\Models\BankAccount;
use App\Models\Agreement;
use App\Models\Project;
use App\Models\CFSItem;
use App\Models\Company;
use App\Models\PlItem;
use App\Http\Controllers\Controller;

class ReferenceController extends Controller
{
    public function accounts()
    {
        return response()->json(BankAccount::with(['owner', 'bank'])->orderBy('account_number')->get());
    }

    public function agreements()
    {
        return response()->json(Agreement::with(['buyer', 'seller'])->orderBy('agr_number')->get());
    }

    public function projects()
    {
        return response()->json(Project::orderBy('name')->get());
    }

    public function cfsItems()
    {
        return response()->json(CFSItem::orderBy('name')->get());
    }

    public function beneficiaries()
    {
        return response()->json(Company::orderBy('name')->get());
    }

    public function plItems()
    {
        return response()->json(PlItem::orderBy('name')->get());
    }
}

