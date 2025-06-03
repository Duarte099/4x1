ver quando enviar os recibos, em que mes e em julho se apenas a quem fez horas

peguntar o que quer por mais na dashboard e balanco geral

perguntar se quer por tambem o professor no registro de presença

perguntar que campos colocar em cada tabela

ver como funciona se o aluno sair a meio do mês, se tem de pagar a que fez durante o mês

perguntar se precisa de duração no horario

grafico para novos alunos por mês e alunos descontinuados

colocar pop up a dizer os pagamentos em atraso na pagina do recibo com os recibos em atraso com um click a redirecionar para esse recibo em atraso

atualizar:
    perfil
    professorEdit

pergutnar como funciona se o aluno nao tiver no perfil horas grupo e registarem

function getParametroUrl(nome) {
                    const params = new URLSearchParams(window.location.search);
                    return params.get(nome);
                }

                const idAluno = getParametroUrl('idAluno');

                fetch(`json.obterPagamentosAlunos.php?idAluno=${idAluno}`)
                    .then(response => response.json())
                    .then(data => {
                        const etiquetas = [];
                        const valores = [];
                        const cores = [];
                        const links = [];
                        const borderColors = [];

                        const defaultBorderColor = 'rgba(0,0,0,0)';
                        const activeBorderColor = 'black';
                        let activeIndex = null;
                        let ultimoAno = null;

                        data.forEach((row, index) => {
                            if (row.ano !== ultimoAno) {
                                // Inserir coluna separadora invisível
                                etiquetas.push(row.ano); // Mostra o ano
                                valores.push(0); // Sem altura
                                cores.push('rgba(0,0,0,0)'); // Invisível
                                links.push(null); // Sem link
                                borderColors.push(defaultBorderColor);
                                ultimoAno = row.ano;
                            }

                            etiquetas.push(`${row.mes}`);
                            valores.push(parseFloat(row.valor));

                            switch (row.status) {
                                case '1': cores.push('green'); break;
                                case '2': cores.push('yellow'); break;
                                case '3': cores.push('red'); break;
                                default: cores.push('gray');
                            }

                            links.push(`alunoEdit.php?idAluno=${row.idAluno}&ano=${row.ano}&mes=${row.mesNum}&tab=recibo`);
                            borderColors.push(defaultBorderColor);
                        });

                        const ctx = document.getElementById("barChart").getContext("2d");

                        const myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: etiquetas,
                                datasets: [{
                                    label: "Pagamentos por mês",
                                    data: valores,
                                    backgroundColor: cores,
                                    borderColor: borderColors,
                                    borderWidth: 3,
                                    borderRadius: 5
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                onHover: (event, elements) => {
                                    event.native.target.style.cursor = elements.length ? 'pointer' : 'default';
                                },
                                onClick: (e, elements) => {
                                    if (elements.length > 0) {
                                        const index = elements[0].index;
                                        if (!links[index]) return; // Ignorar colunas invisíveis

                                        myChart.data.datasets[0].borderColor = borderColors.map((_, i) =>
                                            i === index ? activeBorderColor : defaultBorderColor
                                        );
                                        myChart.update();
                                        window.location.href = links[index];
                                    }
                                },
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: context => context.raw > 0 ? `Valor: €${context.raw}` : ''
                                        }
                                    },
                                    datalabels: {
                                        anchor: "center",
                                        align: "center", // <- isto centraliza horizontalmente
                                        rotation: 90,     // <- sem rotação = texto na horizontal
                                        color: "white",
                                        font: {
                                            size: 12,
                                            weight: 'bold'
                                        }
                                    },
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Valor'
                                        }
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Erro ao buscar os dados:', error));

                    <div class="col-md-6">
                                    <div class="card">
                                        <div class="chart-container">
                                            <canvas id="barChart"></canvas>
                                        </div>
                                    </div>
                                </div>


                                $mes = $_GET['mes'] ?? date('m');
    $ano = $_GET['ano'] ?? date('Y');