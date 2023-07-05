<?php
namespace Src\Shared\Crud;

interface UpdateServiceInterface {
    public function update(string $id, mixed $object): void;
}
?>