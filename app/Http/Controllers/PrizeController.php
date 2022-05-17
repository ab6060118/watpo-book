<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Services\PrizeService;
use App\Models\Activity;
use App\Models\Log;
use App\Models\Prize;
use Exception;
use Hash;
use Illuminate\Http\Request;

class PrizeController extends Controller {
    const headers = array('Content-Type' => 'application/json; <a href="http://superlevin.ifengyuan.tw/tag/charset/">charset</a>=utf-8');
    public function add(Request $request) {
        try {
            $prize = new Prize;
            $prize->name = $request->name;
            $prize->activity_id = $request->activity_id;
            $prize->description = $request->description;
            $prize->money = $request->money;
            $prize->status = (($request->status == 'on') ? '1' : '0');
            $prizeService = new PrizeService;
            if (!$prizeService->checkProbability($request->probability)) {
                return response()->json(['message' => '機率格式錯誤', 'error' => 1], 200, self::headers, JSON_UNESCAPED_UNICODE);
            }
            if ($prize->status && !$prizeService->checkActivityProbability($request->probability, 0, $request->activity_id)) {
                return response()->json(['message' => '機率總和過100%', 'error' => 1], 200, self::headers, JSON_UNESCAPED_UNICODE);
            }
            $prize->probability = $request->probability;
            $prize->save();
            Log::create(['description' => '增加獎項 id ' . $prize->id . ' - 活動 id ' . $request->activity_id]);
            return response()->json('新增成功', 200, self::headers, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            return response()->json('系統錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json('資料庫錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
        }
    }
    public function update(Request $request) {
        try {
            $prize = new Prize;
            $prize = $prize->where('id', $request->id)->first();
            $prize->name = $request->name;
            $prize->description = $request->description;
            $prize->money = $request->money;
            $prize->status = (($request->status == 'on') ? '1' : '0');
            $prizeService = new PrizeService;
            if (!$prizeService->checkProbability($request->probability)) {
                return response()->json(['message' => '機率格式錯誤', 'error' => 1], 200, self::headers, JSON_UNESCAPED_UNICODE);
            }
            if ($prize->status && !$prizeService->checkActivityProbability($request->probability, $prize->id, $prize->activity_id)) {
                return response()->json(['message' => '機率總和過100%', 'error' => 1], 200, self::headers, JSON_UNESCAPED_UNICODE);
            }
            $prize->probability = $request->probability;
            $prize->save();
            Log::create(['description' => '修改獎項 id ' . $prize->id . ' - 活動 id ' . $prize->activity_id]);
            return response()->json('修改成功', 200, self::headers, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            return response()->json('系統錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json('資料庫錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
        }
    }
    public function delete(Request $request) {
        try {
            $prize = new Prize;
            $prize = $prize->where('id', $request->id)->first();
            $prize->delete();
            Log::create(['description' => '刪除獎項 id ' . $prize->id . ' - 活動 id ' . $prize->activity_id]);
            return redirect('/admin/activity/edit/' . $prize->activity_id);
        } catch (Exception $e) {
            return redirect('/admin/activity/edit/' . $prize->activity_id)->withErrors(['fail' => '系統錯誤 請洽系統管理商']);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/admin/activity/edit/' . $prize->activity_id)->withErrors(['fail' => '資料庫錯誤 請洽系統管理商']);
        }
    }
}
