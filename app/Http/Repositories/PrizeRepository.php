<?php
namespace App\Http\Repositories;

use App\Models\Activity;
use App\Models\Prize;
use Carbon\Carbon;

class PrizeRepository {
    public function getActivityProbability(int $prize_id, int $activity_id) {
        // dd($activity_id, $prize_id);
        return Prize::where('activity_id', $activity_id)
            ->where('id', '!=', $prize_id)
            ->where('status', 1)
            ->sum('probability');
    }
    public function getValidActivityPrize(int $money, Carbon $time) {
        // dd($money, $time);
        // $activity = Activity::where('status', 1)
        //     ->where('money', '<=', $money)
        //     ->where('start_time', '<=', $time)
        //     ->where('end_time', '>=', $time);
        // dd($activity->toSql(), $activity->getBindings());
        return Prize::where('activity_id', function ($query) use ($money, $time) {
            $query->select('id')
                ->from((new Activity)->getTable())
                ->where('status', 1)
                ->where('money', '<=', $money)
                ->where('start_time', '<=', $time)
                ->where('end_time', '>=', $time)
                ->first();
        })
            ->where('status', 1)
            ->where('probability', '>', 0)
            ->get();

    }
}