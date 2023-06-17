<?php
namespace Src\Shared\Crud;

interface ListServiceInterface {
    public function list(string $id = null): array;
}
?>