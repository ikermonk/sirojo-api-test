<?php
namespace Src\App\Cart\Presentation\Controllers;

use Illuminate\Http\Request;
use Src\Shared\Request\RequestId;
use Illuminate\Support\Facades\Log;
use Src\Shared\Request\RequestAddItem;
use Src\Shared\Request\RequestRemoveItem;
use Src\Shared\Request\RequestUpdateCart;
use Src\App\Cart\Application\Find\FindUserCart;
use Src\App\Cart\Application\Update\UpdateCart;
use Src\App\Cart\Application\ClearCart\ClearCart;
use Src\App\Cart\Application\AddItem\AddItemToCart;
use Src\App\Cart\Application\RemoveItem\RemoveItemFromCart;

class CartController {
    private FindUserCart $find_cart_service;
    private AddItemToCart $add_item_to_cart_service;
    private RemoveItemFromCart $remove_item_service;
    private UpdateCart $update_cart_service;
    private ClearCart $clear_cart_service;
    public function __construct(private readonly FindUserCart $findCartService, private readonly AddItemToCart $addItemToCartService, 
    private readonly RemoveItemFromCart $removeItemService, private readonly UpdateCart $updateCartService, private readonly ClearCart $clearCartService) {
        $this->find_cart_service = $findCartService;
        $this->add_item_to_cart_service = $addItemToCartService;
        $this->remove_item_service = $removeItemService;
        $this->update_cart_service = $updateCartService;
        $this->clear_cart_service = $clearCartService;
    }

    public function find(Request $request) {
        try {
            $data = $request->all();
            Log::info("CartController - find - Data => " . json_encode($data));
            $requestId = new RequestId($data["id"], $data["by"]);
            if ($requestId->validate()) {
                Log::info("CartController - find - Request => " . $requestId->getId() . " // " . $requestId->getBy());
                $cart = $this->find_cart_service->find($requestId);
                Log::info("CartController - find - Cart => " . json_encode($cart));
                if (isset($cart)) return response()->json($cart);
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
        $data = $request->all();
        Log::info("CartController - add_item - POST => " . json_encode($data));
        $requestAddItem = new RequestAddItem($data["id_cart"], $data["user_id"], $data["product_id"], $data["quantity"]);
        if ($requestAddItem->validate()) {
            Log::info("CartController - find - Request => " . json_encode($requestAddItem));
            $cart = $this->add_item_to_cart_service->add($requestAddItem);
            Log::info("CartController - find - Cart => " . json_encode($cart));
            return response()->json($cart);
        }
        return response()->json([
            'message' => 'Hay un error al tratar de añadir un producto al carrito.'
        ], 409);

    }

    public function remove_item(Request $request) {
        try {
            $data = $request->all();
            Log::info("CartController - remove_item - Request => " . json_encode($data));
            $requestRemoveItem = new RequestRemoveItem($data["id_line"], $data["id_cart"]);
            Log::info("CartController - remove_item - Data => " . json_encode($requestRemoveItem));
            if ($requestRemoveItem->validate()) {
                $cart = $this->remove_item_service->remove($requestRemoveItem);
                return response()->json($cart);
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

    public function update(Request $request, string $cart_id) {
        try {
            $data = $request->all();
            Log::info("CartController - update - PUT => " . $cart_id . " // " . json_encode($data));
            $requestUpdateCart = new RequestUpdateCart($cart_id, $data["user_id"], $data["items"]);
            if ($requestUpdateCart->validate()) {
                $cart = $this->update_cart_service->update($requestUpdateCart);
                return response()->json($cart);
            }
            return response()->json([
                'message' => 'Hay un error al tratar de actualizar el carrito.'
            ], 409);
        } catch (\UpdateException $e) {
            Log::error("CartController - update - UpdateException: " . $e->getMessage());
            return response()->json([
                'message' => 'No se ha localizado un Producto al actualizar el Carrito.'
            ], 500);
        } catch (\Exception $e) {
            Log::error("CartController - update - Exception: " . $e->getMessage());
            return response()->json([
                'message' => 'Ha ocurrido un error al tratar de actualizar el carrito.'
            ], 500);
        }
    }

    public function clear(Request $request) {
        try {
            $data = $request->all();
            Log::info("CartController - clear - DELETE => " . json_encode($data));
            $requestId = new RequestId($data["id_cart"], "");
            Log::info("CartController - clear - DELETE - Request => " . json_encode($requestId));
            if ($requestId->validate()) {
                $cart = $this->clear_cart_service->clear($requestId);
                return response()->json($cart);
            }
            return response()->json([
                'message' => 'Hay un error al tratar de vaciar el carrito.'
            ], 409);
        } catch (\Exception $e) {
            Log::error("CartController - clear - Exception: " . $e->getMessage());
            return response()->json([
                'message' => 'Ha ocurrido un error al tratar de vaciar el carrito.'
            ], 500);
        }
    }    

}
?>