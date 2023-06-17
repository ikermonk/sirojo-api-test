<?php
namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Log;
use Src\Shared\Crud\AddServiceInterface;
use Src\Shared\Crud\GetServiceInterface;

class CartService implements GetServiceInterface, AddServiceInterface {

    public function get(string $user_id): mixed {
        Log::info("CartService - get - UserID => ", [$user_id]);
        return Cart::where('user_id', '=', $user_id)->first();
    }

    public function add(mixed $object): Cart {
        $object->save();
        $object->refresh();
        return $object;
    }
}
?>