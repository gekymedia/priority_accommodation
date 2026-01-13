<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class PayReturnController extends Controller
{
    public function handle(Request $request)
    {
        return response()->json(["status" => "success"]);
    }
}