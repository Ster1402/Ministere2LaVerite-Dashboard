<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowResourceRequest;
use App\Models\Resource;
use App\Models\User;
use Carbon\Carbon;

class BorrowedResourceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(BorrowResourceRequest $request, Resource $resource)
    {
        $user = User::findOrFail($request->input('user'));
        $user->resources()->attach($resource, [
            'quantity' => $request->input('quantity'),
            'borrowed_at' => Carbon::now(),
        ]);

        return redirect()->route('resources.index');
    }
}
