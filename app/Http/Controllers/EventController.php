<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\OwnedItems;

class EventController extends Controller
{
    const WHITELISTED_IDS = [1]; // Item IDs for event
    const MAX_MONEY_PER_REQUEST = 250;
    const TICKET = "YourTicketHere";
    const ENABLED = false;

    private function is_awardable($item_id) : bool
    {
        return (in_array((int)$item_id, self::WHITELISTED_IDS));
    }

    public function award(Request $request)
    {
        return self::WHITELISTED_IDS;
    }

    public function processaward(Request $request)
    {
        if (!self::ENABLED) {
            abort(403);
        }

        $itemId = $request->itemId;
        $userId = $request->userId;
        $ticket = $request->ticket;

        if (!$itemId || !$userId || !$ticket) {
            return 'BAD_VARIABLES';
        }

        if ($ticket !== self::TICKET) {
            return 'INVALID_TICKET';
        }

        if (!$this->is_awardable($itemId)) {
            return 'NOT_AWARDABLE';
        }

        $item = Item::findOrFail($itemId);
        $user = User::findOrFail($userId);

        $ownedItem = OwnedItems::where(['user_id' => $user->id, 'item_id' => $item->id])->first();
        if ($ownedItem) {
            return 'OWNED';
        }

        $award = OwnedItems::create([
            'user_id' => $user->id,
            'item_id' => $item->id
        ]);

        return 'OK';
    }

    public function getBalance(Request $request)
    {
        if (!self::ENABLED) {
            abort(403);
        }

        $userId = $request->userId;
        $ticket = $request->ticket;

        if (!$userId || !$ticket) {
            return 'BAD_VARIABLES';
        }

        if ($ticket !== self::TICKET) {
            return 'INVALID_TICKET';
        }

        $user = User::findOrFail($userId);

        return $user->money;
    }

    public function awardMoney(Request $request)
    {
        if (!self::ENABLED) {
            abort(403);
        }

        $userId = $request->userId;
        $ticket = $request->ticket;
        $money = $request->money;

        if (!$userId || !$ticket || !$money) {
            return 'BAD_VARIABLES';
        }

        if ($ticket !== self::TICKET) {
            return 'INVALID_TICKET';
        }

        if ($money > self::MAX_MONEY_PER_REQUEST) {
            return 'TOO_MUCH_MONEY';
        }

        $user = User::findOrFail($userId);
        $user->money = $user->money + $money;
        $user->save();

        return $user->money;
    }
}
