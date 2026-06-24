<?php
class AtendimentosController
{
    private PDO $pdo;

    public function __construct()
    {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

    public function listar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $sql = 'SELECT a.id, a.data_atendimento, a.horario_atendimento, a.status, a.descricao, a.observacao_final,
                       p.nome AS pessoa_nome, 
                       t.nome AS tipo_atendimento_nome, 
                       u.nome AS usuario_nome
                FROM atendimentos a
                JOIN pessoas p ON a.pessoa_id = p.id
                JOIN tipos_atendimentos t ON a.tipo_atendimento_id = t.id
                JOIN usuarios u ON a.usuario_id = u.id
                ORDER BY a.data_atendimento DESC, a.horario_atendimento DESC';
                
        $stmt = $this->pdo->query($sql);
        $atendimentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($atendimentos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function visualizar(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID inválido.']);
            return;
        }

        $sql = 'SELECT a.*, 
                       p.nome AS pessoa_nome, p.documento, p.telefone,
                       t.nome AS tipo_atendimento_nome, 
                       u.nome AS usuario_nome
                FROM atendimentos a
                JOIN pessoas p ON a.pessoa_id = p.id
                JOIN tipos_atendimentos t ON a.tipo_atendimento_id = t.id
                JOIN usuarios u ON a.usuario_id = u.id
                WHERE a.id = :id';
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $atendimento = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$atendimento) {
            http_response_code(404);
            echo json_encode(['erro' => 'Atendimento não encontrado.']);
            return;
        }

        echo json_encode($atendimento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

   public function criar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $pessoa_id = filter_input(INPUT_POST, 'pessoa_id', FILTER_VALIDATE_INT);
        $tipo_atendimento_id = filter_input(INPUT_POST, 'tipo_atendimento_id', FILTER_VALIDATE_INT);
        $usuario_id = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);
        $data_atendimento = $_POST['data_atendimento'] ?? '';
        $horario_atendimento = $_POST['horario_atendimento'] ?? '';
        $descricao = trim($_POST['descricao'] ?? '');
        $observacao_final = trim($_POST['observacao_final'] ?? '');
        $status = $_POST['status'] ?? 'aberto';

        if (!$pessoa_id || !$tipo_atendimento_id || !$usuario_id || $data_atendimento === '' || $horario_atendimento === '') {
            http_response_code(400);
            echo json_encode(['erro' => 'Dados obrigatórios em falta.']);
            return;
        }

        try {
            $sql = 'INSERT INTO atendimentos (pessoa_id, tipo_atendimento_id, usuario_id, data_atendimento, horario_atendimento, descricao, observacao_final, status)
                    VALUES (:pessoa_id, :tipo_atendimento_id, :usuario_id, :data_atendimento, :horario_atendimento, :descricao, :observacao_final, :status)';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':pessoa_id', $pessoa_id, PDO::PARAM_INT);
            $stmt->bindValue(':tipo_atendimento_id', $tipo_atendimento_id, PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->bindValue(':data_atendimento', $data_atendimento);
            $stmt->bindValue(':horario_atendimento', $horario_atendimento);
            $stmt->bindValue(':descricao', $descricao);
            // Se observação for vazia, envia null
            $stmt->bindValue(':observacao_final', $observacao_final !== '' ? $observacao_final : null);
            $stmt->bindValue(':status', $status);
            $stmt->execute();

            http_response_code(201);
            echo json_encode([
                'mensagem' => 'Atendimento registrado.',
                'id' => $this->pdo->lastInsertId()
            ], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao registrar atendimento.']);
        }
    }

    public function atualizarStatus(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $status = $_POST['status'] ?? '';
        $statusValidos = ['aberto', 'em_andamento', 'concluido'];
        
        if (!$id || !in_array($status, $statusValidos)) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID ou status inválido. Os status permitidos são: aberto, em_andamento, concluido.']);
            return;
        }

        try {
            $sql = 'UPDATE atendimentos SET status = :status WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['mensagem' => 'Status do atendimento atualizado com sucesso.'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao atualizar o status.']);
        }
    }

    public function excluir(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID inválido.']);
            return;
        }

        try {
            $sql = 'DELETE FROM atendimentos WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['mensagem' => 'Atendimento excluído com sucesso.'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao excluir o atendimento.']);
        }
    }
}