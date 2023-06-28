<?php
namespace Src\Shared\Request;

class RequestRemoveItem {
    public string $id;
    public string $id_cart;
    public function __construct(string $id, string $id_cart) {
        $this->id = $id;
        $this->id_cart = $id_cart;
    }

    public function validate(): bool {
        return isset($this->id) && $this->id !== "" 
            && isset($this->id_cart) && $this->id_cart !== "";
    }    

}
?>