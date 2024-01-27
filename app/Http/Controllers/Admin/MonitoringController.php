<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item\Item;
use App\Models\Currency\Currency;
use App\Models\Raffle\Raffle;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Services\MonitoringService;

class MonitoringController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Monitoring Controller
    |--------------------------------------------------------------------------
    |
    | For checking site statistics or to monitor ownership of specific things.
    |
    */

    /**
     * Shows the ownership statistic page.
     *
     */
    public function getOwnership(Request $request, MonitoringService $service)
    {
        $requestParams = $request->only('object_type', 'object_id', 'sort');

        if (isset($requestParams["object_type"]) && isset($requestParams["object_id"])) {
            $type = $requestParams["object_type"];
            $id = $requestParams["object_id"];
            $sort = $requestParams['sort'] ?? 'all';
            $object = $service->getObjectFromRequest($type, $id);
            $currentlyOwnedByUserId = $service->getCurrentlyOwned($type, $id);
            arsort($currentlyOwnedByUserId);
            $alltimeOwnedByUserId = $service->getAlltimeOwned($type, $id);
            arsort($alltimeOwnedByUserId);
            $usersByUserId = User::whereIn('id', array_merge(array_keys($currentlyOwnedByUserId) , array_diff(array_keys($alltimeOwnedByUserId), array_keys($currentlyOwnedByUserId) )) )->get()->groupBy('id');
        }
        return view('admin.monitoring.ownership', [
            'items' => Item::orderBy('name')->pluck('name', 'id'),
            'currencies' => Currency::where('is_user_owned', 1)->orderBy('name')->pluck('name', 'id'),
            'raffles' => Raffle::where('rolled_at', null)->where('is_active', 1)->orderBy('name')->pluck('name', 'id'),
            'object' => $object ?? null,
            'currentlyOwnedByUserId' => $currentlyOwnedByUserId ?? null,
            'alltimeOwnedByUserId' => $alltimeOwnedByUserId ?? null,
            'usersByUserId' => $usersByUserId ?? null,
            'requestParams' => $requestParams,
            'sort' => $sort ?? 'all'
        ]);
    }
}
