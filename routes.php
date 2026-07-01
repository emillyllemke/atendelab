<?php

require_once __DIR__ . '/app/Middleware/auth.php';
require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Controllers/UsuariosController.php';
require_once __DIR__ . '/app/Controllers/PessoasController.php';
require_once __DIR__ . '/app/Controllers/TiposAtendimentosController.php';
require_once __DIR__ . '/app/Controllers/AtendimentosController.php';
require_once __DIR__ . '/app/Controllers/FrontendController.php';
require_once __DIR__ . '/app/Controllers/DashboardController.php';

function responderRotaNaoEncontrada(string $mensagem): void {
    http_response_code(404);
    echo json_encode(['erro' => $mensagem], JSON_UNESCAPED_UNICODE);
}

$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

switch ($controller) {
    // --- MÓDULO FRONTEND ---
    case 'frontend':
        require_once __DIR__ . '/app/Controllers/FrontendController.php';
        $frontendController = new FrontendController();

        switch ($action) {
            case 'pessoas': 
                $frontendController->pessoas(); 
                break;
            case 'tipos':
            case 'tiposAtendimentos': 
                $frontendController->tiposAtendimentos(); 
                break;
            case 'atendimentos': 
                $frontendController->atendimentos(); 
                break;
            default: 
                http_response_code(404);
                echo 'Acao frontend nao encontrada.'; 
                break;
        }
        break;    

    // --- MÓDULO DE AUTENTICAÇÃO ---
    case 'auth':
        $authController = new AuthController();
        switch ($action) {
            case 'login': 
                $authController->exibirLogin(); 
                break;
            case 'entrar': 
                $authController->entrar(); 
                break;
            case 'dashboard': 
                $authController->dashboard(); 
                break;
            case 'logout': 
                $authController->logout(); 
                break;
            default: 
                http_response_code(404);
                echo 'Acao de autenticacao nao encontrada.'; 
                break;
        }
        break;

    // --- MÓDULO DE USUÁRIOS ---
    case 'usuarios':
        exigirAutenticacao(); 
        $usuariosController = new UsuariosController();

        switch ($action) {
            case 'listar': $usuariosController->listar(); break;
            case 'buscarPorId': $usuariosController->buscarPorId(); break;
            case 'criar': $usuariosController->criar(); break;
            case 'atualizar': $usuariosController->atualizar(); break;
            case 'inativar': $usuariosController->inativar(); break;
            default: 
                http_response_code(404);
                echo 'Acao de usuarios nao encontrada.'; 
                break;
        }
        break;

    // --- MÓDULO DE PESSOAS ---
    case 'pessoas':
        exigirAutenticacao(); 
        $pessoasController = new PessoasController();

        switch ($action) {
            case 'listar': 
                $pessoasController->listar(); 
                break;
            case 'buscar': 
                $pessoasController->buscarPorId();
                break;
            case 'criar': 
                $pessoasController->criar(); 
                break;
            case 'atualizar': 
                $pessoasController->atualizar(); 
                break;
            case 'inativar': 
                $pessoasController->inativar(); 
                break;
            default: 
                http_response_code(404);
                echo 'Acao de pessoas nao encontrada.'; 
                break;
        }
        break;

    // --- MÓDULO DE TIPOS DE ATENDIMENTO ---
    case 'tipos_atendimento':
    case 'tipos': 
        require_once __DIR__ . '/app/Controllers/TiposAtendimentosController.php';
        $tiposController = new TiposAtendimentosController();

        switch ($action) {
            case 'listar':
                $tiposController->listar();
                break;
            case 'buscar':
            case 'buscarPorId':
                $tiposController->buscarPorId();
                break;
            case 'criar':
                $tiposController->criar();
                break;
            case 'atualizar':
                $tiposController->atualizar();
                break;
            case 'inativar':
                $tiposController->inativar();
                break;
            default:
                responderRotaNaoEncontrada('Ação de tipos de atendimento não encontrada.');
                break;
        }
        break;

    // --- MÓDULO DE ATENDIMENTOS ---
    case 'atendimentos':
        exigirAutenticacao();
        require_once __DIR__
            . '/app/Controllers/AtendimentosController.php';
        $atendimentosController = new AtendimentosController();
        switch ($action) {
            case 'listar':
                $atendimentosController->listar();
                break;
            case 'visualizar':
                $atendimentosController->visualizar();
                break;
            case 'criar':
                $atendimentosController->criar();
                break;
            case 'alterarStatus':
            case 'atualizarStatus':
                $atendimentosController->atualizarStatus();
                break;
            case 'opcoesFormulario':
                $atendimentosController->opcoesFormulario();
                break;
            default:
                responderRotaNaoEncontrada(
                    'Ação de atendimentos não encontrada.'
                );
        }
    break;
    
    // --- MÓDULO DASHBOARD ---
    case 'dashboard':
        $dashboardController = new DashboardController();
        
        switch ($action) {
            case 'resumo': 
                $dashboardController->resumo(); 
                break;
            default: 
                http_response_code(404);
                echo 'Acao do dashboard nao encontrada.'; 
                break;
        }
        break;

    // --- RESPOSTA PARA ROTA HOME ---
    case 'home':
        echo '<h1>AtendeLab</h1>';
        echo '<p>Projeto em execução. Controladores disponíveis: auth, usuarios, pessoas, tipos_atendimento, atendimentos.</p>';
        break;

    // --- ERRO 404 (Controller Inexistente) ---
    default:
        http_response_code(404);
        echo 'Controller nao encontrado.';
        break;
}