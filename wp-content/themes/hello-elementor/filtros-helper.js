class Filtros {

    tiposDeAcoes() {
        return {
            regiao: [
                'Centro',
                'Zona Sul',
                'Zona Leste',
                'Zona Oeste',
                'Zona Norte'
            ],
            nacao: [
                'Angola',
                'Queto',
                'Umbanda'
            ]
        }
    }

    limparFiltros() {
        document.querySelector('#categoria').value = 'selecione';
        document.getElementById('tipo-acao').options.length = 1;
        document.getElementById('tipo-acao').hidden = true;
        document.getElementById('limpar-filtro').hidden = true;
    }

    eventoTrocaCategoria() {
        let categoriaSelecionada = document.querySelector('#categoria').value;

        if (categoriaSelecionada == 'selecione') {
            this.limparFiltros();
            return null;
        }

        document.getElementById('tipo-acao').hidden = false;
        document.getElementById('limpar-filtro').hidden = false;
        document.getElementById('tipo-acao').options.length = 1;
        this.constroiFiltroTipodeAcao(categoriaSelecionada);

        return categoriaSelecionada;
    }

    constroiFiltroTipodeAcao(categoria) {
        this.tiposDeAcoes()[categoria].forEach(acao => {
            let option = document.createElement('option');
            option.value = acao;
            option.textContent = acao;
            document.querySelector('#tipo-acao').appendChild(option);
        })
    }

}
