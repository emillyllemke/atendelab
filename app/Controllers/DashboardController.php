<?php

require_once __DIR__ . '/../Middleware/auth.php';

class DashboardController
{
    private PDO $pdo;

    public function __construct()
    {
        require_once __DIR__ . '/../../config/database.php';
        
        global $pdo;
        if (!$pdo) {
            http_response_code(500);
            exit('Conexão com o banco não disponível.');
        }
        $this->pdo = $pdo;
    }

    public function resumo(): void
    {
        exigirAutenticacao();

        try {
            $totalPessoas = $this->pdo->query('SELECT COUNT(*) FROM pessoas')->fetchColumn();
            $totalTipos = $this->pdo->query('SELECT COUNT(*) FROM tipos_atendimentos')->fetchColumn();
            $totalAtendimentos = $this->pdo->query('SELECT COUNT(*) FROM atendimentos')->fetchColumn();

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'indicadores' => [
                    'total_pessoas' => (int) $totalPessoas,
                    'total_tipos' => (int) $totalTipos,
                    'total_atendimentos' => (int) $totalAtendimentos
                ]
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao consultar o banco: ' . $e->getMessage()]);
            exit;
        }
    }
}