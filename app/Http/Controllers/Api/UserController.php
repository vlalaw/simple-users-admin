<?php

namespace App\Http\Controllers\Api;

use Request;
use App\Models\User;
use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request as HttpRequest;

class UserController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        $users = User::query();

        foreach (Request::get('orderBy') ?? [] as $column => $order) {
            $users->orderBy($column, $order);
        }

        return UserResource::collection($users->get());
    }
}
