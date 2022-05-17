<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Services\PrizeService;
use App\Models\Log;
use App\Models\Order;
use App\Models\Prize;
use App\Models\Voucher;
use Exception;
use Illuminate\Http\Request;

class VoucherController extends Controller {
    public function index(Request $request) {
        $vouchers = new Voucher;
        $vouchers = $vouchers
            ->with('prize');
        if ($request->name) {
            $vouchers = $vouchers->where('name', 'Like', '%' . $request->name . '%');
        }
        if ($request->phone) {
            $vouchers = $vouchers->where('phone', 'Like', '%' . $request->phone . '%');
        }
        if ($request->code) {
            $vouchers = $vouchers->where('code', $request->code);
        }
        $vouchers = $vouchers->get();
        $data = [];
        foreach ($vouchers as $voucher) {
            $data[] = [
                'id' => $voucher->id,
                'order_id' => $voucher->order_id,
                'order_client_name' => $voucher->order->name ?? '',
                'name' => $voucher->name,
                'description' => $voucher->prize->description,
                'code' => $voucher->code,
                'money' => $voucher->money,
                'expirationTime' => (is_null($voucher->expire_at) ? $voucher->expire_at : strtotime($voucher->expire_at)),
                'isValid' => $voucher->is_valid,
                'code' => $voucher->code,
                'usedTime' => (is_null($voucher->used_at) ? $voucher->used_at : strtotime($voucher->used_at)),
            ];
        }
        // dd($vouchers);
        return response()->json($data);
    }
    public function creat(Request $request) {
        try {
            $voucher = new Voucher;
            $voucher->name = $request->name;
            $voucher->phone = $request->phone;
            $order = Order::where('id', $request->orderId)
                ->with('service')
                ->first();
            // dd($request->orderId, $order);
            $voucher->order_id = $request->orderId;
            // 抽折價券
            $prize = PrizeService::draw($order->service->price);
            if (is_null($prize)) {
                return response()->json(['name' => $voucher->name, 'description' => null, 'expirationTime' => null]);
            }
            $voucher->prize_id = $prize->id;
            $voucher->expire_at = date('Y-m-d H:i:s', strtotime("+30 day"));
            $voucher->is_valid = 1;
            $voucher->money = $prize->money;
            $voucher->code = PrizeService::creatCode();
            // dd($voucher);
            $voucher->save();
            // Log::create(['description' => '增加活動 id ' . $activities->id]);
            return response()->json([
                'name' => $voucher->name,
                'description' => $prize->description,
                'code' => $voucher->code,
                'money' => $voucher->money,
                'expirationTime' => (is_null($voucher->expire_at) ? $voucher->expire_at : strtotime($voucher->expire_at))]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json('資料庫錯誤, 請洽系統商!', 400);
        }
        // $voucher = new Voucher;
        // $activity = $activity
        //     ->with('prizes')
        //     ->where('id', $request->id)
        //     ->first();
        // $prizes = $activity->prizes;
        // $view_data['activity'] = $activity;
        // $view_data['prizes'] = $prizes;
        // $view_data['request'] = $request;
        // // dd($activities);
        // $voucher = new Voucher;
        // $voucher = $voucher
        //     ->with('prize')
        //     ->first();
        // dd($voucher, $voucher->activity);
        // return view('admin.activity.edit', $view_data);

    }
}
