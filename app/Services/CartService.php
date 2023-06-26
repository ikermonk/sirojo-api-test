<?php
namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Log;
use Src\Shared\Crud\AddServiceInterface;
use Src\Shared\Crud\GetServiceInterface;
use Src\Shared\Crud\UpdateServiceInterface;

class CartService implements GetServiceInterface, AddServiceInterface, UpdateServiceInterface {

    public function get(string $user_id, string $by = null): mixed {
        if (isset($by) && $by !== "" && $by === "user") {
            return Cart::where('user_id', '=', $user_id)->first();
        }
        return Cart::where('uuid', "=", $id)->first();
    }

    public function add(mixed $object): Cart {
        $object->save();
        $object->refresh();
        return $object;
    }

    public function update(string $id, mixed $object): mixed {
        $item = Cart::where('uuid', "=", $id)->first();
        if (isset($item) && $item !== "") {
            $item->save();
            $item->refresh();
            return $item;
        }
        throw new UpdateException();
    }

}
?>