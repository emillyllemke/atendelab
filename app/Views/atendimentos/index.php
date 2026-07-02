<?php
$tituloPagina = 'Atendimentos';
require __DIR__ . '/../layouts/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h3 mb-1">Atendimentos</h1>
        <p class="text-secondary mb-0">Registro e acompanhamento dos atendimentos acadêmicos.</p>
    </div>
    <button class="btn btn-success" type="button" onclick="novoAtendimento()">Novo atendimento</button>
</div>

<div id="alerta"></div>

<div class="card border-0 shadow-sm mb-4 d-none" id="cardFormulario">
    <div class="card-body">
        <h2 class="h5">Novo atendimento</h2>
        <form id="formAtendimento">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pessoa *</label>
                    <select class="form-select" name="pessoa_id" id="pessoaSelect" required>
                        </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipo *</label>
                    <select class="form-select" name="tipo_atendimento_id" id="tipoSelect" required>
                        </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Descrição / Relato Inicial *</label>
                    <textarea class="form-control" name="descricao" rows="3" required></textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-success" type="submit">Salvar</button>
                <button class="btn btn-outline-secondary" type="button" onclick="fecharFormulario()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Data/Hora</th>
                    <th>Pessoa</th>
                    <th>Tipo de Atendimento</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody id="tabelaAtendimentos">
                <tr>
                    <td colspan="5" class="text-center py-4">Carregando...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalStatus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Atualizar Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formStatus">
                <input type="hidden" name="id" id="statusId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select class="form-select" name="status" required>
                            <option value="aberto">Aberto</option>
                            <option value="em_andamento">Em Andamento</option>
                            <option value="concluido">Concluído</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observação Final</label>
                        <textarea class="form-control" name="observacao_final" rows="3"placeholder="Obrigatória ao concluir"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Salvar Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const formAtendimento = document.getElementById('formAtendimento');
const cardFormulario = document.getElementById('cardFormulario');
let _modalStatusInstance = null;

function statusModal() {
    if (!_modalStatusInstance) {
        _modalStatusInstance = new bootstrap.Modal(document.getElementById('modalStatus'));
    }
    return _modalStatusInstance;
}

function abrirFormulario() { 
    cardFormulario.classList.remove('d-none'); 
    window.scrollTo({ top: 0, behavior: 'smooth' }); 
}

function fecharFormulario() { 
    cardFormulario.classList.add('d-none'); 
    formAtendimento.reset(); 
}

function novoAtendimento() { 
    fecharFormulario(); 
    abrirFormulario(); 
}

async function carregarCombos() {
    try {
        const resposta = await AtendeLabApi.get('atendimentos', 'opcoesFormulario');
        const dados = resposta.dados || resposta;

        const pessoaSelect = document.getElementById('pessoaSelect');
        const tipoSelect = document.getElementById('tipoSelect');

        pessoaSelect.innerHTML = '<option value="">Selecione uma pessoa...</option>' + 
            dados.pessoas.map(p => `<option value="${p.id}">${AtendeLabApi.escape(p.nome)}</option>`).join('');

        tipoSelect.innerHTML = '<option value="">Selecione o tipo...</option>' + 
            dados.tipos.map(t => `<option value="${t.id}">${AtendeLabApi.escape(t.nome)}</option>`).join('');
    } catch (error) {
        AtendeLabApi.showAlert('alerta', 'Erro ao carregar as opções do formulário.', 'danger');
    }
}

async function carregarAtendimentos() {
    try {
        const dados = AtendeLabApi.toList(await AtendeLabApi.get('atendimentos', 'listar'));
        const tbody = document.getElementById('tabelaAtendimentos');
        
        if (!dados.length) { 
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4">Nenhum atendimento registrado.</td></tr>'; 
            return; 
        }

        tbody.innerHTML = dados.map(a => {
            let badgeClass = 'text-bg-secondary';
            if (a.status === 'aberto') badgeClass = 'text-bg-warning';
            else if (a.status === 'em_andamento') badgeClass = 'text-bg-primary';
            else if (a.status === 'concluido') badgeClass = 'text-bg-success';

            const partesData = a.data_atendimento.split('-');
            const dataFormatada = `${partesData[2]}/${partesData[1]}/${partesData[0]}`;
            const horarioFormatado = a.horario_atendimento.substring(0, 5);

            return `<tr>
                <td>${AtendeLabApi.escape(dataFormatada + ' às ' + horarioFormatado)}</td>
                <td>${AtendeLabApi.escape(a.pessoa_nome || '')}</td>
                <td>${AtendeLabApi.escape(a.tipo_atendimento_nome || '')}</td>
                <td><span class="badge ${badgeClass}">${AtendeLabApi.escape(a.status)}</span></td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-primary" onclick="abrirStatus(${Number(a.id)}, '${AtendeLabApi.escapeAttr(a.status)}')">Alterar Status</button>
                </td>
            </tr>`;
        }).join('');
    } catch (error) { 
        AtendeLabApi.showAlert('alerta', error.message, 'danger'); 
    }
}

formAtendimento.addEventListener('submit', async event => {
    event.preventDefault();
    try {
        await AtendeLabApi.post(
            'atendimentos',
            'criar',
            new FormData(formAtendimento)
        );
        AtendeLabApi.showAlert('alerta', 'Atendimento registrado com sucesso.');
        fecharFormulario();
        await carregarAtendimentos();
    } catch (error) {
        AtendeLabApi.showAlert('alerta', error.message, 'danger');
    }
});

function abrirStatus(id, status) {
    document.getElementById('statusId').value = id;
    document.querySelector('#formStatus [name="status"]').value = status || 'aberto';
    document.querySelector('#formStatus [name="observacao_final"]').value = "";
    statusModal().show();
}

document.getElementById('formStatus').addEventListener('submit', async event => {
    event.preventDefault();
    try { 
        await AtendeLabApi.post(
            'atendimentos',
            'alterarStatus',
            new FormData(event.target)
        );
        statusModal().hide();
        AtendeLabApi.showAlert('alerta', 'Status atualizado com sucesso.');
        await carregarAtendimentos();
    } catch (error) {
        AtendeLabApi.showAlert('alerta', error.message, 'danger');
    }
});

document.addEventListener('DOMContentLoaded', async () => {
    try {
        await carregarCombos();
        await carregarAtendimentos();
    } catch (error) {
        AtendeLabApi.showAlert('alerta', error.message, 'danger');
    }
});
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>