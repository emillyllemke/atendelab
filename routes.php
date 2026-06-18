<?php

// 1. Carrega todos os middlewares e controllers
require_once __DIR__ . '/app/Middleware/auth.php';
require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Controllers/UsuarioController.php';
require_once __DIR__ . '/app/Controllers/PessoasController.php';
require_once __DIR__ . '/app/Controllers/TiposAtendimentosController.php';
require_once __DIR__ . '/app/Controllers/AtendimentosController.php';

// Define controller e action por query string. 
$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

switch ($controller) {
    
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
            case 'excluir': $usuariosController->excluir(); break;
            default: 
                http_response_code(404);
                echo 'Acao de usuarios nao encontrada.'; 
                break;
        }
        break;

    // --- MÓDULO DE PESSOAS ---
    case 'pessoas':
        $pessoasController = new PessoasController();

        switch ($action) {
            case 'listar': $pessoasController->listar(); break;
            case 'buscar': $pessoasController->buscarPorId(); break;
            case 'criar': $pessoasController->criar(); break;
            case 'atualizar': $pessoasController->atualizar(); break;
            case 'excluir': $pessoasController->excluir(); break;
            default: 
                http_response_code(404);
                echo 'Acao de pessoas nao encontrada.'; 
                break;
        }
        break;

    // --- MÓDULO DE TIPOS DE ATENDIMENTO ---
    case 'tipos_atendimento':
        $tiposController = new TiposAtendimentosController();

        switch ($action) {
            case 'listar': $tiposController->listar(); break;
            case 'buscar': $tiposController->buscarPorId(); break;
            case 'criar': $tiposController->criar(); break;
            case 'atualizar': $tiposController->atualizar(); break;
            case 'excluir': $tiposController->excluir(); break;
            default: 
                http_response_code(404);
                echo 'Acao de tipos de atendimento nao encontrada.'; 
                break;
        }
        break;

    // --- MÓDULO DE ATENDIMENTOS ---
    case 'atendimentos':
        $atendimentosController = new AtendimentosController();

        switch ($action) {
            case 'listar': $atendimentosController->listar(); break;
            case 'visualizar': $atendimentosController->visualizar(); break;
            case 'criar': $atendimentosController->criar(); break;
            case 'atualizar_status': $atendimentosController->atualizarStatus(); break;
            case 'excluir': $atendimentosController->excluir(); break;
            default: 
                http_response_code(404);
                echo 'Acao de atendimentos nao encontrada.'; 
                break;
        }
        break;

    // --- RESPOSTA PARA ROTA HOME (Se quiser manter fora do auth) ---
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