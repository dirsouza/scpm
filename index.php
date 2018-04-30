<?php
setlocale(LC_ALL, "pt_BR", "pt_BR-utf-8", "portuguese");

require_once "vendor/autoload.php";

session_start();

$_SESSION['system'] = array(
    'name' => 'SCMM - Sistema de Controle de Mercadorias de Comércios',
    'version' => '1.0.0'
);

use Slim\Slim;
use SCMM\Models\Login;
use SCMM\Models\User;
use SCMM\Models\Commerce;
use SCMM\Models\Product;
use SCMM\Models\ProductCommerce;
use SCMM\Models\ProductFilter;

$app = new Slim();
$app->config(array(
    'debug' => true,
    'templates.path' => 'views',
    'mode' => 'development'
));

/**
 * Index
 * Url: http://scmm/index
 */
$app->get('/', function() use ($app) {
    Login::verifyLogin();
    
    $user = new Login();
    $user->getUser((int)$_SESSION[Login::SESSION]['Idusuario']);
    $data = $user->getValues();
    
    if ($data['Destipo'] === '1') {
        $mainPanel = array(
            'commerces' => (is_array(Commerce::listComercios()) && count(Commerce::listComercios()) > 0) ? count(Commerce::listComercios()) : 0,
            'products' => (is_array(Product::listProdutos()) && count(Product::listProdutos()) > 0) ? count(Product::listProdutos()) : 0,
            'admins' => (is_array(User::listAdministradores()) && count(User::listAdministradores()) > 0) ? count(User::listAdministradores()) : 0,
            'clients' => (is_array(User::listClientes()) && count(User::listClientes()) > 0) ? count(User::listClientes()) : 0
        );

        $userName = User::listAdministradorId((int)$data['Idusuario']);
        $userName = explode(" ", $userName[0]['desnome']);
        $_SESSION['userName'] = $userName[0];
        
        $app->render('default/header.php', array(
            'user' => $data,
            'page' => 'Painel Principal'
        ));
        $app->render('default/index.php', array(
            'mainPanel' => $mainPanel
        ));
        $app->render('default/footer.php');
    } else {
        $app->redirect('/scmm/client');
    }
});

/**
 * Login
 * Url: http://scmm/login
 */

$app->group('/login', function() use ($app) {
    $app->get('/', function() use ($app) {
        $app->render("/login/header.php");
        $app->render("/login/login.php");
        $app->render("/login/footer.php");
    });
    
    $app->post('/', function() use ($app) {
        $login = new Login();
        $login->login($_POST['desLogin'], $_POST['desSenha']);
        $app->redirect('/scmm/');
    });
});

/**
 * Logout
 * Url: http://scmm/logout
 */
$app->group('/logout', function() use ($app) {
    $app->get('/', function() use ($app) {
        Login::logout();
        $app->redirect('/scmm/');
    });
});

/**
 * Registro
 * Url: http://scmm/register
 */
$app->group('/register', function() use ($app) {
    $app->get('/', function() use ($app) {
        $app->render("/login/header.php");
        $app->render("/login/register.php");
        $app->render("/login/footer.php");
    });
    
    $app->post('/', function() use ($app) {
        $register = $_POST;
    
        if ($register['desSenha'] === $register['desReSenha']) {
            $user = new User();
            $user->setData($register);
            $user->addUsuario();

            $_SESSION['register'] = array(
                'msg' => "Usuário Cadastrado com Sucesso!"
            );
            $app->redirect('/scmm/login');
        } else {
            $_SESSION['register'] = array(
                'desLogin' => $register['desLogin'],
                'desNome' => $register['desNome'],
                'msg' => "As senhas não são identicas."
            );
            $app->redirect('/scmm/register');
        }
    });
});

/**
 * Cadastros
 * Url: http://scmm/registration
 */
$app->group('/registration', function() use ($app) {
    /**
     * Comércio
     * Url: http://scmm/registration/commerce
     */
    $app->group('/commerce', function() use ($app) {
        $app->get('/', function() use ($app) {
            Login::verifyLogin();
            
            $user = new Login();
            $user->getUser((int)$_SESSION[Login::SESSION]['Idusuario']);
            
            $commerces = Commerce::listComercios();
            
            $app->render('default/header.php', array(
                'user' => $user->getValues(),
                'page' => "Lista de Comércios"
            ));
            $app->render('commerce/index.php', array(
                'commerces' => $commerces
            ));
            $app->render('default/footer.php');
        });

        $app->get('/create', function() use ($app) {
            Login::verifyLogin();
            
            $user = new Login();
            $user->getUser((int)$_SESSION[Login::SESSION]['Idusuario']);

            if (isset($_SESSION['restoreData'])) {
                $data = array(
                    'desNome' => $_SESSION['restoreData']['desNome'],
                    'desCEP' => $_SESSION['restoreData']['desCEP'],
                    'desRua' => $_SESSION['restoreData']['desRua'],
                    'desBairro' => $_SESSION['restoreData']['desBairro']
                );
                unset($_SESSION['restoreData']);
            } else {
                $data = null;
            }
            
            $app->render('default/header.php', array(
                'user' => $user->getValues(),
                'page' => "Novo Comércio"
            ));
            $app->render('commerce/create.php', array(
                'data' => $data
            ));
            $app->render('default/footer.php');
        });

        $app->post('/create', function() use ($app) {
            Login::verifyLogin();
            
            $commerce = new Commerce();
            $commerce->setData($_POST);
            $commerce->addComercio();
            
            $app->redirect('/scmm/registration/commerce');
        });

        $app->get('/update/:id', function($id) use ($app) {
            Login::verifyLogin();
            
            $user = new Login();
            $user->getUser((int)$_SESSION[Login::SESSION]['Idusuario']);
            
            $commerce = Commerce::listComercioId((int)$id);
            
            $app->render('default/header.php', array(
                'user' => $user->getValues(),
                'page' => "Editar Comércio"
            ));
            $app->render('commerce/update.php', array(
                'commerce' => $commerce[0]
            ));
            $app->render('default/footer.php');
        });

        $app->post('/update/:id', function($id) use ($app) {
            Login::verifyLogin();
            
            $commerce = new Commerce();
            $commerce->setData($_POST);
            $commerce->updateComercio((int)$id);
            
            $app->redirect('/scmm/registration/commerce');
        });

        $app->get('/delete/:id', function($id) use ($app) {
            Login::verifyLogin();
            
            $commerce = new Commerce();
            $commerce->deleteComercio((int)$id);
            
            $app->redirect('/scmm/registration/commerce');
        });
        
        $app->get('/report', function() use ($app) {
            Login::verifyLogin();

            $commerces = Commerce::listComercios();
            
            $app->render('commerce/report.php', array(
                'commerces' => $commerces
            ));
        });
    });

    /**
     * Produto
     * Url: http://scmm/registration/product
     */
    $app->group('/product', function() use ($app) {
        $app->get('/', function() use ($app) {
            Login::verifyLogin();
            
            $user = new Login();
            $user->getUser((int)$_SESSION[Login::SESSION]['Idusuario']);
            
            $products = Product::listProdutos();
            
            $app->render('default/header.php', array(
                'user' => $user->getValues(),
                'page' => "Lista de Produtos"
            ));
            $app->render('product/index.php', array(
                'products' => $products
            ));
            $app->render('default/footer.php');
        });

        $app->get('/create', function() use ($app) {
            Login::verifyLogin();
            
            $user = new Login();
            $user->getUser((int)$_SESSION[Login::SESSION]['Idusuario']);

            if (isset($_SESSION['restoreData'])) {
                $data = array(
                    'desNome' => $_SESSION['restoreData']['desNome'],
                    'desMarca' => $_SESSION['restoreData']['desMarca'],
                    'desDescricao' => $_SESSION['restoreData']['desDescricao']
                );
                unset($_SESSION['restoreData']);
            } else {
                $data = null;
            }
            
            $app->render('default/header.php', array(
                'user' => $user->getValues(),
                'page' => "Novo Produto"
            ));
            $app->render('product/create.php', array(
                'data' => $data
            ));
            $app->render('default/footer.php');
        });

        $app->post('/create', function() use ($app) {
            Login::verifyLogin();
            
            $product = new Product();
            $product->setData($_POST);
            $product->addProduto();
            
            $app->redirect('/scmm/registration/product');
        });

        $app->get('/update/:id', function($id) use ($app) {
            Login::verifyLogin();
            
            $user = new Login();
            $user->getUser((int)$_SESSION[Login::SESSION]['Idusuario']);
            
            $product = Product::listProdutoId((int)$id);
            
            $app->render('default/header.php', array(
                'user' => $user->getValues(),
                'page' => "Editar Produto"
            ));
            $app->render('product/update.php', array(
                'product' => $product[0]
            ));
            $app->render('default/footer.php');
        });

        $app->post('/update/:id', function($id) use ($app) {
            Login::verifyLogin();
            
            $product = new Product();
            $product->setData($_POST);
            $product->updateProduto((int)$id);
            
            $app->redirect('/scmm/registration/product');
        });

        $app->get('/delete/:id', function($id) use ($app) {
            Login::verifyLogin();
            
            $product = new Product();
            $product->deleteProduto((int)$id);
            
            $app->redirect('/scmm/registration/product');
        });
        
        $app->get('/report', function() use ($app) {
            Login::verifyLogin();

            $products = Product::listProdutos();
            
            $app->render('product/report.php', array(
                'products' => $products
            ));
        });
    });
    
    /**
    * Produtos por Comércio
    * Url: http://scmm/registration/product_commerce
    */   
    $app->group('/product_commerce', function() use ($app) {
        $app->get('/', function() use ($app) {
            Login::verifyLogin();

            $user = new Login();
            $user->getUser((int)$_SESSION[Login::SESSION]['Idusuario']);

            $products_commerces = ProductCommerce::listProdutosComercios();

            $app->render('default/header.php', array(
                'user' => $user->getValues(),
                'page' => "Lista de Produtos por Comércio"
            ));
            $app->render('product_commerce/index.php', array(
                'products_commerces' => $products_commerces
            ));
            $app->render('default/footer.php');
        });

        $app->get('/create', function() use ($app) {

        });

        $app->post('/create', function() use ($app) {

        });

        $app->get('/update/:id', function($id) use ($app) {

        });

        $app->post('/update/:id', function($id) {

        });

        $app->get('/delete/:id', function($id) {

        });
    });
});

/**
 * Usuários
 * Url: http://scmm/users
 */
$app->group('/users', function() use ($app) {
    /**
    * Administrador
    * Url: http://scmm/users/admin
    */
    $app->group('/admin', function() use ($app) {
        $app->get('/', function() use ($app) {
        
        });

        $app->get('/create', function() use ($app) {

        });

        $app->post('/create', function() use ($app) {

        });

        $app->get('/update/:id', function($id) use ($app) {

        });

        $app->post('/update/:id', function($id) {

        });

        $app->get('/delete/:id', function($id) {

        });
    });
    
    /**
    * Cliente
    * Url: http://scmm/users/client
    */
    $app->group('/client', function() use ($app) {
        $app->get('/', function() use ($app) {
        
        });
    });
});

/**
 * Cliente
 * Url: http://scmm/client
 */
$app->group('/client', function() use ($app) {
    $app->get('/', function() use ($app) {
        Login::verifyLogin();
    
        $user = new Login();
        $user->getUser((int)$_SESSION[Login::SESSION]['Idusuario']);
        $data = $user->getValues();

        $userName = User::listClienteId((int)$data['Idusuario']);
        $userName = explode(" ", $userName[0]['desnome']);
        $_SESSION['userName'] = $userName[0];

        $app->render('default/header.php', array(
            'user' => $data,
            'page' => 'Faça sua pesquisa'
        ));
        $app->render('clientSearch/index.php');
        $app->render('default/footer.php');
    });
});

$app->run();