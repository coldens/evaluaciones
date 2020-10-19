<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyStoreRequest;
use App\Models\Reply;
use Illuminate\Database\QueryException;

class ReplyController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  ReplyStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReplyStoreRequest $request)
    {
        try {
            return Reply::create($request->validated());
        } catch (QueryException $e) {
            if ($e->getCode() == '23000') {
                return Reply::find($request->id);
            }

            throw $e;
        }
    }
}
