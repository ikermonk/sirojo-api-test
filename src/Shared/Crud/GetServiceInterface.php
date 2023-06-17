<?php 
namespace Src\Shared\Crud;

interface GetServiceInterface {
    public function get(string $id): mixed;
}
?>