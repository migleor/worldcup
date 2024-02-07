<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(){
        $groups = Group::with('team')->get();
        $agrupateGroups = $groups->groupBy('grupo');
        return response()->json(["groups"=>$agrupateGroups]);
    }
}
