<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponser
{
	private function successResponse($data, $code) {
		return response()->json($data, $code);
	}

	protected function errorResponse($msg, $code) {
		return response()->json(['error' => $msg, 'code' => $code], $code);
	}

	protected function showAll(Collection $collection, $code = 200) {
		return $this->successResponse(['data' => $collection], $code);
	}

	protected function showOne(Model $instance, $code = 200) {
		return response()->json(['data' => $instance], $code);
	}

}