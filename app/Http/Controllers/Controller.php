<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, DispatchesJobs;

    protected function atomic(Closure $callback)
    {
        return DB::transaction($callback);
    }

    public function success($result, $message = '')
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    public function error($error, $errorMessages = [], $code = 500)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    public function failsValidation($validation)
    {
        return $this->error($validation->errors()->first(), $validation->errors(), 422);
    }

    protected function queryBuilder($data, $request, $filter = false, $with = [])
    {
        $select = explode(',', $request->select);
        if(count($with) > 0)
            $data = $data->with($with);

        if($filter)
            $data = $data->filter($request);

        if($request->select)
            $data = $data->select($select);

        if($request->orderBy) {
            $orderBy = explode('|', $request->orderBy);
            $data = $data->orderBy($orderBy[0] ?? 'created_at', $orderBy[1] ?? 'desc');
        }

        if($request->page && $request->page_size !== 'All')
            $data = $data->paginate($request->page_size, ['*'], 'page', $request->page);
        else
            $data = $data->get();

        return $data;
    }
}
