<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;

class CouponController extends Controller
{
    public function getCoupons(Request $request)
	{
        try{
            $coupons = array(
                array(
                    "id" => "id1",
                    "name" => "name",
                    "expirationTime" => "2022/12/31T10:00:00Z",
                    "description" => "description",
                    "isValid" => true,
                    "usedTime" => "2022/10/01T10:00:00Z",
                    "code" => "123456"
                ),
                array(
                    "id" => "id2",
                    "name" => "name",
                    "expirationTime" => "2022/12/31T10:00:00Z",
                    "description" => "description",
                    "isValid" => true,
                    "code" => "123456"
                ),
                array(
                    "id" => "id3",
                    "name" => "name",
                    "expirationTime" => "2020/12/31T10:00:00Z",
                    "description" => "description",
                    "isValid" => false,
                    "code" => "123456"
                )
            );
			return response()->json($coupons);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
    }

    public function draw(Request $request)
	{
        try{
            $drawResponse = array(
               "name" => "name",
               "phone" => "phone",
               "orderId" => "123214"
            );
			return response()->json($drawResponse);
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
    }
}