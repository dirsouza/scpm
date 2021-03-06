<?php

namespace App\Model;

use Core\Model;
use Lib\Dao;

/**
 * Classe para cadastrar e controlar os preços dos Produtos amarrados a um comércio
 */
class ProdsByCommerce extends Model
{

    /**
     * Adiciona o vinculo Comercio, Produto e Preço
     */
    public function addProdutoComercio()
    {
        try {
            $sql = new Dao();
            $sql->allQuery("INSERT INTO tbprodutocomercio (idcomercio,idproduto,despreco) 
                            VALUES (:IDCOMERCIO,:IDPRODUTO,:DESPRECO)", array(
                ':IDCOMERCIO' => $this->getIdComercio(),
                ':IDPRODUTO' => $this->getIdProduto(),
                ':DESPRECO' => $this->getDesPreco()
            ));
        } catch (\PDOException $e) {
            Model::returnError("Não foi possível Cadastrar o vinculo de Produto e Comércio.<br>" . $e->getMessage(), $_SERVER['REQUEST_URI']);
        }
    }

    /**
     * Atualiza o vinculo Comercio, Produto e Preço
     * @param type $idProdutoComercio
     */
    public function updateProdutoComercio(int $idProdutoComercio)
    {
        try {
            $sql = new Dao();
            $sql->allQuery("UPDATE tbprodutocomercio SET despreco = :DESPRECO
                            WHERE idProdutoComercio = :IDPRODUTOCOMERCIO", array(
                ':IDPRODUTOCOMERCIO' => $idProdutoComercio,
                ':DESPRECO' => str_replace(",", ".", str_replace(".", "", $this->getDesPreco()))
            ));
        } catch (\PDOException $e) {
            Model::returnError("Não foi possível Atualizar o vinculo de Produto e Comércio.<br>" . $e->getMessage(), '/admin/prodsByCommerce');
        }
    }

    /**
     * Exclui um vinculo entre Comercio e Produto
     * @param type $idProdutoComercio
     */
    public function deleteProdutoComercio(int $idProdutoComercio)
    {
        try {
            $sql = new Dao();
            $sql->allQuery("DELETE FROM tbprodutocomercio
                            WHERE idProdutoComercio = :IDPRODUTOCOMERCIO", array(
                ':IDPRODUTOCOMERCIO' => $idProdutoComercio
            ));
        } catch (\PDOException $e) {
            Model::returnError("Não foi possível Deletar o vinculo de Produto e Comércio.<br>" . $e->getMessage(), $_SERVER['REQUEST_URI']);
        }
    }

    public function deleteProdutoComercioAll(int $idProdutoComercio)
    {
        try {
            $sql = new Dao();
            $sql->allQuery('CALL sp_delete_produtoComercio (:IDPRODUTOCOMERCIO)', array(
                ':IDPRODUTOCOMERCIO' => $idProdutoComercio
            ));
        } catch(\PDOException $e) {
            Model::returnError("Não foi possível Deletar os vinculos de Produtos e Comércio.<br>" . $e->getMessage(), $_SERVER['REQUEST_URI']);
        }
    }

    /**
     * Lista todos os Produtos e Comercios vinculados e retorna um Array
     * @return type Array
     */
    public static function listProdutosComercios()
    {
        try {
            $sql = new Dao();
            $results = $sql->allSelect("SELECT * FROM vw_produtocomercio");

            if (is_array($results) && count($results) > 0) {
                return $results;
            }
        } catch (\PDOException $e) {
            Model::returnError("Não foi possível Listar os Produto e Comércio.<br>" . $e->getMessage(), $_SERVER['REQUEST_URI']);
        }
    }

    /**
     * Lista um Produto e Comercio vinculado e retorna um Array para atualização
     * @return type Array
     */
    public static function listProdutosComerciosId(int $idProdutoComercio)
    {
        try {
            $sql = new Dao();
            $results = $sql->allSelect("SELECT * FROM vw_produtocomercio
                                        WHERE idProdutoComercio = :IDPRODUTOCOMERCIO", array(
                ':IDPRODUTOCOMERCIO' => $idProdutoComercio
            ));

            if (is_array($results) && count($results) > 0) {
                return $results;
            }
        } catch (\PDOException $e) {
            Model::returnError("Não foi possível Listar o Produto e Comércio.<br>" . $e->getMessage(), $_SERVER['REQUEST_URI']);
        }
    }

    /**
     * Lista todos os produtos vinculados ao idComercio e retorna um Array
     * @return type Array
     */
    public static function listProdComeIdComercio(int $idComercio)
    {
        try {
            $sql = new Dao();
            $results = $sql->allSelect("SELECT * FROM vw_produtocomercio
                                        WHERE idcomercio = :IDCOMERCIO", array(
                ':IDCOMERCIO' => $idComercio
            ));

            if (is_array($results) && count($results) > 0) {
                return $results;
            }
        } catch (\PDOException $e) {
            Model::returnError("Não foi possível Listar o Produto e Comércio.<br>" . $e->getMessage(), $_SERVER['REQUEST_URI']);
        }
    }

    public static function listProdComeIdProduto(int $idProduto)
    {
        try {
            $sql = new Dao();
            $results = $sql->allSelect("SELECT * FROM vw_produtocomercio
                                        WHERE idproduto = :IDPRODUTO", array(
                ':IDPRODUTO' => $idProduto
            ));

            if (is_array($results) && count($results) > 0) {
                return $results;
            }
        } catch (\PDOException $e) {
            Model::returnError("Não foi possível Listar o Produto e Comércio.<br>" . $e->getMessage(), $_SERVER['REQUEST_URI']);
        }
    }
}