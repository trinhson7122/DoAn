<?php

namespace App\Traits;

use App\Enums\ThongKeType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait ModelScopeTrait
{
    public function scopeActive($query)
    {
        $model = new (self::class);
        return $query->where($model->getTable() . '.is_active', 1);
    }

    public function scopeFilter($query, string $type, bool $isPast = false, $prefix = '')
    {
        $cb = Carbon::now();
        $threeMonth = getThreeMonthByMonth($cb->month);
        $between = getStartEndThreeMonthByThreeMonth($threeMonth);
        $createdAtField = $prefix ? $prefix . '.created_at' : 'created_at';
        switch ($type) {
            case ThongKeType::DAY->value:
                $isPast && $cb->subDay(1);
                $query = $query->whereDay($createdAtField, $cb->day);
                break;
            case ThongKeType::WEEK->value:
                $isPast && $cb->subWeek(1);
                $query = $query->whereRaw("WEEK($createdAtField, 3) = " . $cb->week);
                break;
            case ThongKeType::MONTH->value:
                $isPast && $cb->subMonth(1);
                $query = $query->whereMonth($createdAtField, $cb->month);
                break;
            case ThongKeType::THREE_MONTHS->value:
                if ($isPast) {
                    $isLastYear = false;
                    if ($threeMonth == 1) {
                        $isLastYear = true;
                        $threeMonth = 4;
                    } else {
                        $threeMonth--;
                    }
                    $isLastYear && $cb->subYear(1);
                    $between = getStartEndThreeMonthByThreeMonth($threeMonth);
                }

                $query = $query->whereBetween(DB::raw("MONTH($createdAtField)"), $between);
                $query = $query->whereYear($createdAtField, $cb->year);
                break;
            case ThongKeType::YEAR->value:
                $isPast && $cb->subYear(1);
                $query = $query->whereYear($createdAtField, $cb->year);
                break;
        }

        return $query;
    }
}
