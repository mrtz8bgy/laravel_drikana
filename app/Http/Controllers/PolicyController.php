<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Policy;

class PolicyController extends Controller
{

    public function index($type)
    {
        // Some installations do not yet have all policy rows in the database.
        // Pass an empty model to the form instead of null so the admin page can
        // create the missing policy when it is first saved.
        $policy = Policy::firstOrNew(['name' => $type], ['content' => '']);
        return view('admin.policies.index', compact('policy'));
    }

    //updates the policy pages
    public function store(Request $request){
        $policy = Policy::firstOrNew(['name' => $request->name]);
        $policy->name = $request->name;
        $policy->content = $request->content;
        $policy->save();

        flash($request->name.' با موفقیت به روز رسانی شد');
        return back();
    }
}


