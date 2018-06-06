<?php

namespace App\Controller;

use Slim\Slim;
use Core\Controller;
use App\Controller\clientController;
use App\Model\Login;
use App\Model\Administrator;
use App\Model\Client;
use App\Model\Commerce;
use App\Model\Product;

class homeController extends Controller
{
    private function loginVerify()
    {
        Login::verifyLogin();

        $user = new Login();
        $user->getUser((int)$_SESSION[Login::SESSION]['Idusuario']);

        return $user->getValues();
    }

    public static function actionIndex()
    {
        $user = self::loginVerify();
        parent::verifyAdmin($user);

        $mainPanel = array(
            'commerces' => (is_array(Commerce::listComercios()) && count(Commerce::listComercios()) > 0) ? count(Commerce::listComercios()) : 0,
            'products' => (is_array(Product::listProdutos()) && count(Product::listProdutos()) > 0) ? count(Product::listProdutos()) : 0,
            'admins' => (is_array(Administrator::listAdministradores()) && count(Administrator::listAdministradores()) > 0) ? count(Administrator::listAdministradores()) : 0,
            'clients' => (is_array(Client::listClientes()) && count(Client::listClientes()) > 0) ? count(Client::listClientes()) : 0
        );

        $userName = Administrator::listAdministradorId((int)$user['Idusuario']);
        $userName = explode(" ", $userName[0]['desnome']);
        $_SESSION['userName'] = $userName[0];

        $mes_pt = array(
            1 => "Jan",
            2 => "Fev",
            3 => "Mar",
            4 => "Abr",
            5 => "Mai",
            6 => "Jun",
            7 => "Jul",
            8 => "Ago",
            9 => "Set",
            10 => "Out",
            11 => "Nov",
            12 => "Dez"
        );
        print_r($mes_pt); exit;

        $numero = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
        echo $numero; exit;
        
        $app = new Slim();
        $app->render('/template/header.php', array(
            'user' => $user,
            'page' => "Painel Principal"
        ));
        $app->render('/template/index.php', array(
            'mainPanel' => $mainPanel
        ));
        $app->render('/template/footer.php');
    }
}