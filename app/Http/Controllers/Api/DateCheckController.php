<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckDateRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DateCheckController extends Controller
{
    /**
     * Date calculate API to calculate the number of sundays between two dates.
     */
    public function store(CheckDateRequest $request)
    {
        /**
         * The incoming request is valid...
         * Retrieve the validated input data
         */
        $validated = $request->validated();

        try {
            /**
             * Your start and end dates
             */
            $start_date = Carbon::parse($validated['start_date']);
            $end_date = Carbon::parse($validated['end_date']);

            /**
             * Initialize a counter for Sundays
             */
            $sunday_count = 0;

            /**
             * Loop through each day between the start and end dates
             */
            while ($start_date <= $end_date) {
                /**
                 * Check if the current day is a Sunday (Carbon uses ISO-8601 where Sunday is day 7)
                 */
                if ($start_date->dayOfWeek == Carbon::SUNDAY && $start_date->day <= 27) {
                    $sunday_count++;
                }

                /**
                 * Move to the next day
                 */
                $start_date->addDay();
            }

            return response()->json(['sunday_count' => $sunday_count], Response::HTTP_OK);
        } catch (Exception $e) {
            /**
             * Can store log for debugging
             */
            Log::info('Exception occured'. $e->getMessage());

            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
