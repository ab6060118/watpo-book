<?php

namespace App\Http\Services;

use App\Http\Repositories\PrizeRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PrizeService {
    public function checkProbability(float $probability) {
        return (is_numeric($probability) && $probability >= 0 && $probability <= 100);
    }
    public function checkActivityProbability(float $prize_probability, int $prize_id, int $activity_id) {
        // dd($prize_id, $activity_id);

        $prizeRepository = new PrizeRepository;
        $activity_probability = $prizeRepository->getActivityProbability($prize_id, $activity_id);
        $total_probability = $prize_probability + $activity_probability;
        return (is_numeric($total_probability) && $total_probability >= 0 && $total_probability <= 100);
    }
    static public function creatCode() {
        return strtoupper(Str::random(6));
    }
    static public function draw(int $money) {
        // dd($money);
        $prizeRepository = new PrizeRepository;
        $prizes = $prizeRepository->getValidActivityPrize($money, Carbon::now());
        $random = mt_rand(0, 10000) / 100;
        $draw_prize = null;
        $probability = 0;
        foreach ($prizes as $prize) {
            // var_dump($prize->id);
            // var_dump($prize->probability);
            $probability += $prize->probability;
            if ($random < $probability) {
                $draw_prize = $prize;
                break;
            }
        }
        // dd($random, $draw_prize, $prizes->toArray());
        return $draw_prize;
    }

}