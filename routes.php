<?php
// 1. Carrega todos os controllers
require_once __DIR__ . '/app/Controllers/UsuarioController.php';
require_once __DIR__ . '/app/Controllers/PessoasController.php';
require_once __DIR__ . '/app/Controllers/TiposAtendimentosController.php';
require_once __DIR__ . '/app/Controllers/AtendimentosController.php';

// Define controller e action por query string.
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// 2. Roteamento para Usuários
if ($controller == 'usuarios') {
    $usuariosController = new UsuariosController();

    switch ($action) {
        case 'listar': $usuariosController->listar(); break;
        case 'buscar': $usuariosController->buscarPorId(); break;
        case 'criar': $usuariosController->criar(); break;
        case 'atualizar': $usuariosController->atualizar(); break;
        case 'excluir': $usuariosController->excluir(); break;
        default: echo 'Ação de usuários não encontrada.'; break;
    }

// 3. Roteamento para Pessoas
} elseif ($controller == 'pessoas') {
    $pessoasController = new PessoasController();

    switch ($action) {
        case 'listar': $pessoasController->listar(); break;
        case 'buscar': $pessoasController->buscarPorId(); break;
        case 'criar': $pessoasController->criar(); break;
        case 'atualizar': $pessoasController->atualizar(); break;
        case 'excluir': $pessoasController->excluir(); break;
        default: echo 'Ação de pessoas não encontrada.'; break;
    }

// 4. Roteamento para Tipos de Atendimento
} elseif ($controller == 'tipos_atendimento') {
    $tiposController = new TiposAtendimentosController();

    switch ($action) {
        case 'listar': $tiposController->listar(); break;
        case 'buscar': $tiposController->buscarPorId(); break;
        case 'criar': $tiposController->criar(); break;
        case 'atualizar': $tiposController->atualizar(); break;
        case 'excluir': $tiposController->excluir(); break;
        default: echo 'Ação de tipos de atendimento não encontrada.'; break;
    }

// 5. Roteamento para Atendimentos
} elseif ($controller == 'atendimentos') {
    $atendimentosController = new AtendimentosController();

    switch ($action) {
        case 'listar': $atendimentosController->listar(); break;
        case 'visualizar': $atendimentosController->visualizar(); break;
        case 'criar': $atendimentosController->criar(); break;
        case 'atualizar_status': $atendimentosController->atualizarStatus(); break;
        case 'excluir': $atendimentosController->excluir(); break;
        default: echo 'Ação de atendimentos não encontrada.'; break;
    }

// 6. Resposta padrão (Home)
} else {
    echo '<h1>AtendeLab</h1>';
    echo '<p>Projeto em execução. Controladores disponíveis: usuarios, pessoas, tipos_atendimento, atendimentos.</p>';
}