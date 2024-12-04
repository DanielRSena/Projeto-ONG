function formatarCPF(cpf, campo) {
    // Remove caracteres não numéricos
    cpf = cpf.replace(/\D/g, '');

    // Limita o tamanho máximo do CPF para 11 dígitos
    if (cpf.length > 11) {
        cpf = cpf.slice(0, 11);
    }

    // Aplica a máscara
    cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
    cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
    cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

    // Define o valor formatado no campo correto
    if (campo === 'cpf-paciente') {
        document.getElementById('cpf-paciente').value = cpf;
    } else if (campo === 'cpfResponsavel-paciente') {
        document.getElementById('cpfResponsavel-paciente').value = cpf;
    }

    return cpf;
}

function formatarCRP(crp, campo) {
    // Remove caracteres não numéricos
    crp = crp.replace(/\D/g, '');
    
    // Limita o tamanho máximo do CRP para 8 dígitos (considerando o número de registro)
    if (crp.length > 8) {
        crp = crp.slice(0, 8);
    }
    
    // Aplica a máscara
    crp = crp.replace(/^(\d{2})(\d{6})$/, '$1/$2-IS');
    
    // Define o valor formatado no campo correto
    if (campo === 'crp-psicopedagogo') {
        document.getElementById('crp-psicopedagogo').value = crp;
    }
    
    return crp;
    }

function formatarData($data) {
        $data = date_create($data);
        return date_format($data, 'd/m/Y');
}