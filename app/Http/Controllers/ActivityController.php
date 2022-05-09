<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Activity;
use App\Models\Log;
use App\Models\Prize;
use Exception;
use Hash;
use Illuminate\Http\Request;

class ActivityController extends Controller {

    const headers = array('Content-Type' => 'application/json; <a href="http://superlevin.ifengyuan.tw/tag/charset/">charset</a>=utf-8');
    public function index(Request $request) {
        $activities = new Activity;
        if ($request->name) {
            $activities = $activities->where('name', 'Like', '%' . $request->name . '%');
        }
        if ($request->start_date && $request->start_time) {
            $activities = $activities->where('start_time', '>=', $request->start_date . ' ' . $request->start_time);
        }
        if ($request->start_to_date && $request->start_to_time) {
            $activities = $activities->where('start_time', '<=', $request->start_to_date . ' ' . $request->start_to_time);
        }
        if ($request->end_date && $request->end_time) {
            $activities = $activities->where('end_time', '>=', $request->end_date . ' ' . $request->end_time);
        }
        if ($request->end_to_date && $request->end_to_time) {
            $activities = $activities->where('end_time', '<=', $request->end_to_date . ' ' . $request->end_to_time);
        }
        $activities = $activities->paginate(10);
        $view_data['activities'] = $activities;
        $view_data['request'] = $request;
        // dd($activities);
        return view('admin.activity.index', $view_data);

    }

    public function add(Request $request) {
        try {
            $activities = new Activity;
            $activities->name = $request->name;
            $activities->description = $request->description;
            $activities->money = $request->money;
            $activities->status = (($request->status == 'on') ? '1' : '0');
            $start_time = $request->start_date . ' ' . $request->start_time;
            $end_time = $request->end_date . ' ' . $request->end_time;
            $activities->start_time = (strtotime($start_time) > strtotime($end_time)) ? $end_time : $start_time;
            $activities->end_time = (strtotime($start_time) > strtotime($end_time)) ? $start_time : $end_time;
            $activities->save();
            Log::create(['description' => '增加活動 id ' . $activities->id]);
            return response()->json('新增成功', 200, self::headers, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            return response()->json('系統錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json('資料庫錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
        }
    }
    public function delete(Request $request) {
        try {

            $activities = new Activity;
            $activities = $activities->where('id', $request->id)->first();
            $activities->delete();
            Log::create(['description' => '刪除活動 id ' . $activities->id]);
            return redirect('/admin/activity');
        } catch (Exception $e) {
            return redirect('/admin/activity')->withErrors(['fail' => '系統錯誤 請洽系統管理商']);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/admin/activity')->withErrors(['fail' => '資料庫錯誤 請洽系統管理商']);
        }
    }
    public function edit(Request $request) {
        $activity = new Activity;
        $activity = $activity
            ->with('prizes')
            ->where('id', $request->id)
            ->first();
        $prizes = $activity->prizes;
        $view_data['activity'] = $activity;
        $view_data['prizes'] = $prizes;
        $view_data['request'] = $request;
        // dd($activities);
        return view('admin.activity.edit', $view_data);

    }
    public function update(Request $request) {
        try {
            $activities = new Activity;
            $activities = $activities->where('id', $request->id)->first();
            $activities->name = $request->name;
            $activities->description = $request->description;
            $activities->money = $request->money;
            $activities->status = (($request->status == 'on') ? '1' : '0');
            $start_time = $request->start_date . ' ' . $request->start_time;
            $end_time = $request->end_date . ' ' . $request->end_time;
            $activities->start_time = (strtotime($start_time) > strtotime($end_time)) ? $end_time : $start_time;
            $activities->end_time = (strtotime($start_time) > strtotime($end_time)) ? $start_time : $end_time;
            $activities->save();
            Log::create(['description' => '修改活動 id ' . $activities->id]);
            return response()->json('修改成功', 200, self::headers, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            return response()->json('系統錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json('資料庫錯誤 請洽系統管理商', 400, self::headers, JSON_UNESCAPED_UNICODE);
        }
    }

}
