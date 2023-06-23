<?php
namespace App\Services;

use App\Models\CartItems;
use App\Exceptions\UpdateException;
use Illuminate\Support\Facades\Log;
use Src\Shared\Crud\AddServiceInterface;
use Src\Shared\Crud\ListServiceInterface;
use Src\Shared\Crud\DeleteServiceInterface;
use Src\Shared\Crud\UpdateServiceInterface;

class CartItemsService implements ListServiceInterface, AddServiceInterface, UpdateServiceInterface, DeleteServiceInterface {
    public function list(string $id = null): array {
        return CartItems::where('id_cart', '=', $id)
        ->get()
        ->toArray();
    }

    public function add(mixed $object): mixed {
        $object->save();
        $object->refresh();
        return $object;
    }

    public function update(string $id, mixed $object): mixed {
        $item = CartItems::where('uuid', "=", $id)->first();
        if (isset($item) && $item !== "") {
            $item->quantity = $object->quantity;
            $item->save();
            $item->refresh();
            return $item;
        }
        throw new UpdateException();
    }

    public function delete(string $id): void {
        CartItems::where('uuid', "=", $id)->delete();
    }

}
?>