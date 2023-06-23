<?php
namespace Src\App\Cart\Domain\Dto;

class Cart {
    public string $id;
    public string $uuid;
    public string $user_id;
    public array $items;
    public function __construct(string $id, string $uuid, string $user_id, array $items) {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->user_id = $user_id;
        $this->items = $items;
    }
}
?>