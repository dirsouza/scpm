<?php

namespace App\Model;

use Core\Model;
use Lib\Dao;
use App\Model\User;

class Client extends Model
{
    /**
     * Adicionar usuário Cliente
     */
    public function addCliente()
    {
        if ($this->verifyData()) {
            $user = new User();
            $user->setData($this->getValues());
            $result = $user->addUsuario();

            if (is_numeric($result) && $result > 0) {
                try {
                    $sql = new Dao();
                    $sql->allQuery("INSERT INTO tbcliente (idusuario,desnome,desemail)
                                    VALUES (:IDUSUARIO,:DESNOME,:DESEMAIL)", array(
                        ':IDUSUARIO' => $result,
                        ':DESNOME' => $this->getDesNome(),
                        ':DESEMAIL' => $this->getDesEmail()
                    ));

                    $user->setUser($result);
                } catch (\PDOException $e) {
                    User::deleteUsuario($result);
                    Model::returnError("Não foi possível Cadastrar o Cliente.<br>" . $e->getMessage(), $_SERVER['REQUEST_URI']);
                }
            } else {
                $this->recoveryData();
                $this->errorUser($result);
            }
        }
    }

    /**
     * Retorna os dados dos Clientes
     * @return type array
     */
    public static function listClientes()
    {
        try {
            $sql = new Dao();
            $results = $sql->allSelect("SELECT * FROM tbcliente
                                        INNER JOIN tbusuario
                                        USING (idusuario)");

            if (is_array($results) && count($results) > 0) {
                return $results;
            }
        } catch (\PDOException $e) {
            Model::returnError("Não foi possível recuperar os dados dos Clientes.<br>" . $e->getMessage(), $_SERVER['REQUEST_URI']);
        }
    }

    /**
     * Retorna os dados de um Cliente
     * @param type int
     * @return type array
     */
    public static function listClienteId(int $idUser)
    {
        try {
            $sql = new Dao();
            $result = $sql->allSelect("SELECT * FROM tbcliente
                                       WHERE idusuario = :IDUSUARIO", array(
                ':IDUSUARIO' => $idUser
            ));
            if (is_array($result) && count($result) > 0) {
                return $result;
            }
        } catch (\PDOException $e) {
            Model::returnError("Não foi possível recuperar os dados do Cliente.<br>" . $e->getMessage(), $_SERVER['REQUEST_URI']);
        }
    }

    /**
     * Invoca o método de tratamento de erros da Model
     * @param type array
     */
    private function errorUser(array $error)
    {
        foreach ($error as $key => $value) {
            ($key == 0) ? $msg = $value : $msg .= "<br>" . $value;
        }

        Model::returnError($msg, $_SERVER['REQUEST_URI']);
    }

    /**
     * Verifica se os dados são vazios
     * Caso SIM - Retorna False
     * Caso NAO - Retorna true
     * @return type boolean
     */
    private function verifyData()
    {
        foreach ($this->getValues() as $key => $value) {
            if (empty($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Cria uma SESSÃO com os dados digitados pelo usuário
     */
    private function recoveryData()
    {
        $_SESSION['register'] = array(
            'desNome' => $this->getDesNome(),
            'desEmail' => $this->getDesEmail(),
            'desLogin' => $this->getDesLogin()
        );
    }
}