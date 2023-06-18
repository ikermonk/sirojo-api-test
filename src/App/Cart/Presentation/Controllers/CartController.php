<?php
namespace Src\App\Cart\Presentation\Controllers;

use Illuminate\Http\Request;
use Src\Shared\Request\RequestId;
use Illuminate\Support\Facades\Log;
use Src\Shared\Request\RequestAddItem;
use Src\App\Cart\Application\Find\FindUserCart;
use Src\App\Cart\Application\AddItem\AddItemToCart;
use Src\App\Cart\Application\RemoveItem\RemoveItemFromCart;

class CartController {
    private FindUserCart $find_cart_service;
    private AddItemToCart $add_item_to_cart_service;
    private RemoveItemFromCart $remove_item_service;
    public function __construct(private readonly FindUserCart $findCartService, private readonly AddItemToCart $addItemToCartService, 
    private readonly RemoveItemFromCart $removeItemService) {
        $this->find_cart_service = $findCartService;
        $this->add_item_to_cart_service = $addItemToCartService;
        $this->remove_item_service = $removeItemService;
    }

    public function find(string $user_id) {
        try {
            if (isset($user_id) && $user_id !== "") {
                $requestId = new RequestId($user_id);
                $cart = $this->find_cart_service->find($requestId);
                return response()->json($cart);
            }
            return response()->json([
                'message' => 'Hay un error al tratar de obtener el carrito.'
            ], 409);
        } catch (\Exception $e) {
            Log::error("CartController - find - Exception: " . $e->getMessage());
            return response()->json([
                'message' => 'Ha ocurrido un error obteniendo el carrito del usuario.'
            ], 500);
        }
    }

    public function add_item(Request $request) {
        try {
            $data = $request->all();
            Log::info("CartController - add_item - POST => " . json_encode($data));
            $requestAddItem = new RequestAddItem($data["id_cart"], $data["user_id"], $data["product_id"], $data["quantity"]);
            if ($requestAddItem->validate()) {
                $this->add_item_to_cart_service->add($requestAddItem);
                return response()->json(["Item added to cart"], 200);
            }
            return response()->json([
                'message' => 'Hay un error al tratar de añadir un producto al carrito.'
            ], 409);
        } catch (\Exception $e) {
            Log::error("CartController - add_item - Exception: " . $e->getMessage());
            return response()->json([
                'message' => 'Ha ocurrido un error al tratar de añadir una linea nueva al carrito.'
            ], 500);
        }

    }

    public function remove_item(Request $request) {
        try {
            $data = $request->all();
            Log::info("CartController - remove_item - Delete => " . json_encode($data));
            $requestRemoveItem = new RequestId($data["id_line"]);
            Log::info("CartController - remove_item - ID => " . $data["id_line"]);
            if ($requestRemoveItem->validate()) {
                $this->remove_item_service->remove($requestRemoveItem);
                return response()->json(["Item removed to cart"], 200);
            }
            return response()->json([
                'message' => 'Hay un error al tratar de añadir un producto al carrito.'
            ], 409);
        } catch (\Exception $e) {
            Log::error("CartController - remove_item - Exception: " . $e->getMessage());
            return response()->json([
                'message' => 'Ha ocurrido un error al tratar de eliminar una linea del carrito.'
            ], 500);
        }

    }

}
?>