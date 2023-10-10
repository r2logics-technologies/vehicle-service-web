<?php

namespace App\Http\Controllers\Mobile\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PolicyController extends Controller
{
    public function getPolicy(){
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $policy = Policy::first();
        if ($policy) {
            return response([
                'status' => 'success',
                'message' => '',
                'policy' => $policy->description,
            ]);
        }

        return response([
            'status' => 'error',
            'message' => 'policy not created yet',
            'policy' => null
        ]);
    }
    public function getTerms(){
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $terms = Term::first();
        if ($terms) {
            return response([
                'status' => 'success',
                'message' => '',
                'terms' => $terms->description,
            ]);
        }

        return response([
            'status' => 'error',
            'message' => 'terms not created yet',
            'terms' => null
        ]);
    }
}
