<?php
namespace App\Services;

use App\Models\Cart;
use App\Exceptions\UpdateException;
use Illuminate\Support\Facades\Log;
use Src\Shared\Crud\AddServiceInterface;
use Src\Shared\Crud\GetServiceInterface;
use Src\Shared\Crud\UpdateServiceInterface;

class CartService implements GetServiceInterface, AddServiceInterface, UpdateServiceInterface {

    public function get(string $id, string $by = null): mixed {
        if (isset($by) && $by !== "" && $by === "user") {
            return Cart::where('user_id', '=', $id)->first();
        }
        return Cart::where('uuid', "=", $id)->first();
    }

    public function add(mixed $object): Cart {
        $object->save();
        $object->refresh();
        return $object;
    }

    public function update(string $id, mixed $object): void {
        Log::info("CartService - update - Params => " . $id . " // " . json_encode($object));
        $cart = Cart::where('uuid', "=", $id)->first();
        Log::info("CartService - update - Cart => " . json_encode($cart));
        if (isset($cart)) {
            $cart->updated_at = $object->updated_at;
            $cart->save();
            $cart->refresh();
            Log::info("CartService - update - Cart Updates => " . json_encode($cart));
        } else {
            throw new UpdateException();
        }
    }

}
?>