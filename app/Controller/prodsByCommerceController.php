<?php

namespace App\Controller;

use Core\Controller;
use App\Model\Login;
use App\Model\Commerce;
use App\Model\Product;
use App\Model\ProdsByCommerce;

class prodsByCommerceController extends Controller
{
    private function loginVerify()
    {
        Login::verifyLogin();

        $user = new Login();
        $user->getUser((int)$_SESSION[Login::SESSION]['Idusuario']);

        return $user->getValues();
    }

    public static function actionViewIndex()
    {
        $user = self::loginVerify();

        $prodsByCommerces = ProdsByCommerce::listProdutosComercios();

        parent::loadView('default', 'header', array(
            'user' => $user,
            'page' => "Lista de Produtos por Comércio"
        ));
        parent::loadView('prodsByCommerce', 'index', $prodsByCommerces);
        parent::loadView('default', 'footer');
    }

    public static function actionViewCreate()
    {
        $user = self::loginVerify();

        $commerces = Commerce::listComercios();

        parent::loadView('default', 'header', array(
            'user' => $user,
            'page' => "Novo Produto"
        ));
        parent::loadView('prodsByCommerce', 'create', array(
            'commerces' => $commerces
        ));
        parent::loadView('default', 'footer');
    }

    public static function actionCreate($data)
    {
        $user = self::loginVerify();

        $idComercio = $data['idComercio'];
        array_shift($data);
        $data = array_combine($data['idProduto'], $data['desPreco']);

        $prodsByCommerces = new ProdsByCommerce();
        $prodsByCommerces->setData(['idComercio' => $idComercio]);
        foreach ($data as $key => $value) {
            $prodsByCommerces->setData(['idProduto' => (int)$key]);
            $prodsByCommerces->setData(['desPreco' => str_replace(",", ".", str_replace(".", "", $value))]);
            $prodsByCommerces->addProdutoComercio();
        }

        header("location: /admin/prodsByCommerce");
        exit;
    }

    public static function actionViewUpdate($id)
    {
        $user = self::loginVerify();

        
    }

    public static function actionUpdate($id, $data)
    {
        $user = self::loginVerify();

        
    }

    public static function actionDelete($id)
    {
        $user = self::loginVerify();

        
    }

    public static function actionViewReport()
    {
        $user = self::loginVerify();

        
    }

    public static function getProduct($id)
    {
        $user = self::loginVerify();

        $setProduct = Product::listProdutoId($id);

        echo json_encode($setProduct[0]);
    }

    public static function getProductDiff($id) {
        $products = Product::listProdutos();
        $prodsByCommerces = ProdsByCommerce::listProdComeIdComercio($id);
        
        if (is_array($prodsByCommerces) && count($prodsByCommerces) > 0) {
            for($i = 0; $i < count($products); $i++){
                if (array_key_exists($i, $prodsByCommerces)) {
                    if ($products[$i]['idproduto'] === $prodsByCommerces[$i]['idproduto']) {
                        $diff[] = $products[$i];
                    }
                }
            }

            for($i = 0; $i < count($diff); $i++) {
                if ($diff[$i]['idproduto'] === $products[$i]['idproduto']) {
                    unset($products[$i]);
                }
            }
        }

        echo json_encode($products);
    }
}