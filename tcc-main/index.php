<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />
    <meta content="" name="keywords" />

    <link rel="stylesheet" href="css/header_footer.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/diario.css" />
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <title>TCC</title>

    <link rel="shortcut icon" type="imagex/png" href="./img/porco_face.jpg">

    <!-- Incluindo a biblioteca Ionicons -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://d3js.org/d3.v7.min.js"></script>


</head>

<body>
    <script>
        async function getWeatherData(lat, lon) {
            try {
                const weatherResponse = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&daily=temperature_2m_max,temperature_2m_min,temperature_2m_mean,weathercode&current_weather=true&timezone=auto&lang=pt`);
                const weatherData = await weatherResponse.json();

                if (weatherResponse.status !== 200) {
                    throw new Error('Erro ao buscar dados do clima');
                }

                // Atualiza a temperatura atual
                const temperaturaDiv = document.getElementById('temperatura_principal');
                const tempAtual = weatherData.current_weather ? Math.round(weatherData.current_weather.temperature) : 'Indisponível';
                temperaturaDiv.innerHTML = `${tempAtual}°`;

                // Atualiza a umidade atual
                const umidadeDiv = document.getElementById('umidade_principal');
                const umidadeAtual = weatherData.current_weather ? weatherData.current_weather.relative_humidity : 'Indisponível'; // Certifique-se de que a API retorna `relative_humidity`
                umidadeDiv.innerHTML = umidadeAtual !== 'Indisponível' ? `Umidade: ${umidadeAtual}%` : 'Umidade: Indisponível';


                // Atualiza os dados para os 3 dias
                const days = [0, 1, 2];
                days.forEach((day, index) => {
                    const date = new Date();
                    date.setDate(date.getDate() + day);

                    // Atualiza data
                    const dateLabel = index === 0 ? "Hoje" : index === 1 ? "Amanhã" : date.toLocaleDateString('pt-BR', {
                        weekday: 'short'
                    });
                    document.querySelector(`#dia${index + 1} .data-dia`).textContent = dateLabel;

                    // Atualiza temperaturas mínimas e máximas
                    const tempMax = Math.round(weatherData.daily.temperature_2m_max[day]);
                    const tempMin = Math.round(weatherData.daily.temperature_2m_min[day]);
                    document.querySelector(`#dia${index + 1} .min-value`).textContent = `${tempMin}°`;
                    document.querySelector(`#dia${index + 1} .max-value`).textContent = `${tempMax}°`;

                    // Atualiza ícone de clima
                    const weatherCode = weatherData.daily.weathercode[day];
                    const iconDiv = document.querySelector(`#dia${index + 1} .icon_thermal`);
                    const icon = getWeatherIcon(weatherCode);
                    iconDiv.innerHTML = `<ion-icon name="${icon}"></ion-icon>`;
                });
            } catch (error) {
                console.error('Erro:', error.message);
            }
        }

        // Função para retornar o ícone com base no código do clima
        function getWeatherIcon(weatherCode) {
            const weatherIcons = {
                0: 'sunny-outline', // Céu limpo
                1: 'partly-sunny-outline', // Parcialmente nublado
                2: 'cloudy-outline', // Nublado
                3: 'cloudy-outline', // Muito nublado
                45: 'cloud-outline', // Névoa
                48: 'cloud-outline', // Névoa densa
                51: 'rainy-outline', // Chuvisco
                61: 'rainy-outline', // Chuva leve
                63: 'rainy-outline', // Chuva moderada
                71: 'snow-outline', // Neve leve
                95: 'thunderstorm-outline', // Tempestade
                99: 'thunderstorm-outline' // Tempestade severa
            };

            return weatherIcons[weatherCode] || 'help-outline'; // Ícone padrão para códigos desconhecidos
        }


        // Chamar a função para obter os dados do tempo
        getWeatherData(-21.248833, -50.314750); // Substitua pelas coordenadas reais

        function ajustarFonte() {
            // Pega a altura do elemento com id "conteudo_diario"
            const conteudoDiario = document.getElementById('conteudo_diario');
            const alturaConteudo = conteudoDiario.offsetHeight;

            // Calcula as porcentagens para cada elemento
            const fontSizeTime = alturaConteudo * 0.6; // 60% para #time
            const fontSizeDate = alturaConteudo * 0.1; // 10% para #date
            const fontSizeDay = alturaConteudo * 0.08; // 8% para #day
            const fontSizeTemperatura = alturaConteudo * 0.1; // 10% para #temperatura_principal
            const fontSizeUmidade = alturaConteudo * 0.1; // 10% para #umidade_principal

            // Ajusta o font-size (aumentando 20% para todos), a altura e o padding-top para cada elemento
            const time = document.getElementById('time');
            time.style.fontSize = (fontSizeTime * 1.2) + 'px'; // Aumenta 20% para #time
            time.style.height = fontSizeTime + 'px';
            time.style.marginTop = -(fontSizeTime * 0.25) + 'px'; // 25% a menos no padding-top para #time

            const date = document.getElementById('date');
            date.style.fontSize = (fontSizeDate * 1.2) + 'px'; // Aumenta 20% para #date
            date.style.height = fontSizeDate + 'px';
            date.style.marginTop = -(fontSizeDate * 0.25) + 'px'; // 25% a menos no padding-top para #date

            const day = document.getElementById('day');
            day.style.fontSize = (fontSizeDay * 1.2) + 'px'; // Aumenta 20% para #day
            day.style.height = fontSizeDay + 'px';
            day.style.marginTop = -(fontSizeDay * 0.25) + 'px'; // 25% a menos no padding-top para #day

            const temperatura = document.getElementById('temperatura_principal');
            temperatura.style.fontSize = (fontSizeTemperatura * 1.2) + 'px'; // Aumenta 20% para #temperatura_principal
            temperatura.style.height = fontSizeTemperatura + 'px';
            temperatura.style.marginTop = -(fontSizeTemperatura * 0.25) + 'px'; // 25% a menos no padding-top para #temperatura_principal

            const umidade = document.getElementById('umidade_principal');
            umidade.style.fontSize = (fontSizeUmidade * 1.2) + 'px'; // Aumenta 20% para #umidade_principal
            umidade.style.height = fontSizeUmidade + 'px';
            umidade.style.marginTop = -(fontSizeUmidade * 0.25) + 'px'; // 25% a menos no padding-top para #umidade_principal
        }

        // Chama a função sempre que necessário (por exemplo, ao carregar a página)
        window.addEventListener('load', ajustarFonte);

        // Você também pode chamar essa função caso haja algum redimensionamento de tela
        window.addEventListener('resize', ajustarFonte);

    </script>

    <!--   popup aquiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii -->
    <section class="popup">
        <!--Pix-->
        <div class="popup_container" id="popup_container-pix">
            <div class="container_pix">
                <div class="pix_texto">
                    <button class="fechar" id="close">&times;</button>
                    <h2>OLHA O PIX</h2>
                    <p>Nos apoie, aceitamos doação de qualquer valor</p>
                </div>
                <img src="img/pix.png" alt="" />
            </div>
        </div>
        <!--cadastro-->
        <div class="popup_container" id="popup_container-cadastro">
            <div class="container_cadastro">
                <div class="pix_texto">
                    <button class="fechar" id="close2">&times;</button>
                    <h2>Cadastre-se e Receba Atualizações</h2>
                </div>
                <fieldset class="fieldset_form">
                    <form action="" method="post" class="form_cadastro" onsubmit="enviarFormulario(event);">
                        <div class="form-group">
                            <ion-icon name="person-circle-outline"></ion-icon>
                            <input type="text" name="nome_usuario" id="nome_usuario" required>
                            <label for="nome">Nome Completo</label>
                        </div>
                        <div class="form-group">
                            <ion-icon name="mail-outline"></ion-icon>
                            <input type="email" name="email" id="email" required>
                            <label for="email">Email</label>
                        </div>
                        <div class="form-group">
                            <ion-icon name="call-outline"></ion-icon>
                            <input type="text" name="telefone" id="telefone" required>
                            <label for="telefone">Telefone</label>
                        </div>
                        <input type="submit" class="btn" value="Enviar"></input>

                    </form>
                    <div id="mensagem"></div>
                </fieldset>

            </div>
        </div>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    </section>
    <!--   site aquiiiiiiiiiiiiiiiiiiiiiiiiii -->
    <div class="container">
        <header>
            <div class="logo">
                <h1>ECO</h1>
            </div>
        </header>
        <main>
            <div class="container_main_principal">
                <!--section informação relogio-->
                <section id="info_diaria">
                    <div id="barra_lateral"></div>
                    <div id="conteudo_diario">
                        <div id="umidade_principal"></div> <!-- Esta div será atualizada com a umidade -->
                        <div id="time"></div>
                        <div id="conteudo_menor">
                            <div class="conteudo_menor_principal">
                                <div id="date"></div>
                                <div id="day"></div>
                            </div>
                            <div id="temperatura_principal"></div>
                            <!-- Esta div será atualizada com a temperatura atual -->
                        </div>
                    </div>
                </section>
                <!--section informação diario-->
                <section id="diario">
                    <div class="dia" id="dia1">
                        <div class="data-dia"></div>
                        <div class="icon_thermal"></div>
                        <div class="temperaturas">
                            <div class="temp-min">
                                <span class="min-value"></span>
                            </div>
                            <div class="temp-barra">
                                <div class="barra"></div>
                                <div class="temp-atual-ponto"></div>
                            </div>
                            <div class="temp-max">
                                <span class="max-value"></span>
                            </div>
                        </div>
                    </div>

                    <div class="barra_horizontal"></div>

                    <div class="dia" id="dia2">
                        <div class="data-dia"></div>
                        <div class="icon_thermal"></div>
                        <div class="temperaturas">
                            <div class="temp-min">
                                <span class="min-value"></span>
                            </div>
                            <div class="temp-barra">
                                <div class="barra"></div>
                            </div>
                            <div class="temp-max">
                                <span class="max-value"></span>
                            </div>
                        </div>
                    </div>

                    <div class="barra_horizontal"></div>

                    <div class="dia" id="dia3">
                        <div class="data-dia"></div>
                        <div class="icon_thermal"></div>
                        <div class="temperaturas">
                            <div class="temp-min">
                                <span class="min-value"></span>
                            </div>
                            <div class="temp-barra">
                                <div class="barra"></div>
                            </div>
                            <div class="temp-max">
                                <span class="max-value"></span>
                            </div>
                        </div>
                    </div>
                </section>
                <!--aqui victor aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa-->
                <!--           botao de descer             -->
                <section id="sec_btn_dados">
                    <button id="btn_dados">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </section>
                <section id="bloco">
                    <?php include('includes/conexao.php'); ?>
                    <div class="bloquinhos grafico_principal">
                        <div class="areatexto">
                            <h2>legenda</h2>
                            <h1>Principal</h1>
                        </div>
                        <div class="areainterativa">
                        </div>
                    </div>
                    <div class="bloquinhos">
                        <div class="areatexto">
                            <h2>UV precisa mudar</h2>
                            <h1><?php echo $valor_dado_1; ?>°</h1>
                        </div>
                        <div class="areainterativa">
                            <div id="gaugeContainer"></div>

                            <script src="https://d3js.org/d3.v7.min.js"></script>
                            <script>
                                // Valor do índice UV (aqui você pode colocar o valor do PHP ou de uma API externa)
                                const uvValue = 6;  // Substitua por um valor real, como por exemplo, da API OpenWeather

                                // Função para determinar o ícone baseado no valor de UV
                                function getUvIcon(uv) {
                                    if (uv <= 2) {
                                        return "https://openweathermap.org/img/wn/01d.png";  // Ícone para UV baixo
                                    } else if (uv <= 5) {
                                        return "https://openweathermap.org/img/wn/02d.png";  // Ícone para UV moderado
                                    } else if (uv <= 7) {
                                        return "https://openweathermap.org/img/wn/03d.png";  // Ícone para UV alto
                                    } else {
                                        return "https://openweathermap.org/img/wn/04d.png";  // Ícone para UV muito alto
                                    }
                                }

                                // Ajustando o tamanho do gráfico baseado no contêiner
                                const width = document.getElementById("gaugeContainer").offsetWidth;
                                const height = document.getElementById("gaugeContainer").offsetHeight;
                                const outerRadius = Math.min(width, height) / 2 - 10; // Raio externo
                                const innerRadius = outerRadius * 0.75; // Raio interno

                                // Criando o SVG
                                const svg = d3.create("svg")
                                    .attr("viewBox", `0 0 ${width} ${height}`)
                                    .attr("width", "100%")
                                    .attr("height", "100%");

                                // Centralizando o gráfico
                                const g = svg.append("g")
                                    .attr("transform", `translate(${width / 2}, ${height / 2})`);

                                // Definindo a função de arco
                                const arc = d3.arc()
                                    .innerRadius(innerRadius)
                                    .outerRadius(outerRadius)
                                    .startAngle(0);

                                // Fundo do gráfico
                                const background = g.append("path")
                                    .datum({ endAngle: 2 * Math.PI }) // Todo o círculo
                                    .style("fill", "#ddd")
                                    .attr("d", arc);

                                // Gráfico de UV (preenchendo de acordo com o valor de UV)
                                const foreground = g.append("path")
                                    .datum({ endAngle: (uvValue / 10) * 2 * Math.PI }) // A porção baseada no valor de UV (0-10)
                                    .style("fill", uvValue <= 3 ? "green" : uvValue <= 7 ? "yellow" : "red") // Cor baseado no valor de UV
                                    .attr("d", arc);

                                // Ícone no centro do gráfico, baseado no valor de UV
                                const iconUrl = getUvIcon(uvValue);

                                const icon = g.append("image")
                                    .attr("x", -24)  // Centraliza horizontalmente
                                    .attr("y", -24)  // Centraliza verticalmente
                                    .attr("width", 48)  // Tamanho do ícone
                                    .attr("height", 48)  // Tamanho do ícone
                                    .attr("xlink:href", iconUrl);

                                // Adicionando o gráfico no contêiner
                                document.getElementById("gaugeContainer").appendChild(svg.node());
                            </script>
                        </div>
                    </div>

                    <div class="bloquinhos">
                        <div class="areatexto">
                            <h2>Umidade</h2>
                            <h1><?php echo $valor_dado_2; ?>%</h1>
                        </div>
                        <div class="areainterativa">
                            <div id="gaugeContainerHumidity"></div>
                            <script src="https://d3js.org/d3.v7.min.js"></script>
<script>
    // Função para criar o gráfico de umidade
    function createHumidityGauge(humidityValue, containerId) {
        // Função para determinar o ícone baseado no valor de umidade
        function getHumidityIcon(humidity) {
            if (humidity <= 30) {
                return "https://openweathermap.org/img/wn/10d.png";  // Ícone para umidade baixa
            } else if (humidity <= 60) {
                return "https://openweathermap.org/img/wn/03d.png";  // Ícone para umidade moderada
            } else {
                return "https://openweathermap.org/img/wn/04d.png";  // Ícone para umidade alta
            }
        }

        // Ajustando o tamanho do gráfico baseado no contêiner
        const width = document.getElementById(containerId).offsetWidth;
        const height = document.getElementById(containerId).offsetHeight;
        const outerRadius = Math.min(width, height) / 2 - 10; // Raio externo
        const innerRadius = outerRadius * 0.75; // Raio interno

        // Criando o SVG
        const svg = d3.create("svg")
            .attr("viewBox", `0 0 ${width} ${height}`)
            .attr("width", "100%")
            .attr("height", "100%");

        // Centralizando o gráfico
        const g = svg.append("g")
            .attr("transform", `translate(${width / 2}, ${height / 2})`);

        // Definindo a função de arco
        const arc = d3.arc()
            .innerRadius(innerRadius)
            .outerRadius(outerRadius)
            .startAngle(0);

        // Fundo do gráfico
        const background = g.append("path")
            .datum({ endAngle: 2 * Math.PI }) // Todo o círculo
            .style("fill", "#ddd")
            .attr("d", arc);

        // Gráfico de Umidade (preenchendo de acordo com o valor de umidade)
        const foreground = g.append("path")
            .datum({ endAngle: (humidityValue / 100) * 2 * Math.PI }) // A porção baseada no valor de umidade (0-100)
            .style("fill", humidityValue <= 30 ? "blue" : humidityValue <= 60 ? "orange" : "green") // Cor baseado no valor de umidade
            .attr("d", arc);

        // Ícone no centro do gráfico, baseado no valor de umidade
        const iconUrl = getHumidityIcon(humidityValue);

        const icon = g.append("image")
            .attr("x", -24)  // Centraliza horizontalmente
            .attr("y", -24)  // Centraliza verticalmente
            .attr("width", 48)  // Tamanho do ícone
            .attr("height", 48)  // Tamanho do ícone
            .attr("xlink:href", iconUrl);

        // Adicionando o gráfico no contêiner
        document.getElementById(containerId).appendChild(svg.node());
    }

    // Usando o valor da umidade vindo do PHP
    const humidityValue = <?php echo $valor_dado_2; ?>;  // A variável PHP passa o valor da umidade
    createHumidityGauge(humidityValue, "gaugeContainerHumidity");
</script>
                        </div>
                    </div>

                    <div class="bloquinhos">
                        <div class="areatexto">
                            <h2>Pressao</h2>
                            <h1><?php echo $valor_dado_3 ?> ATM</h1>
                        </div>
                        <div class="areainterativa">
                        <div id="gaugeContainerPressure"></div>
                        <script src="https://d3js.org/d3.v7.min.js"></script>
<script>
    // Função para criar o gráfico de pressão
    function createPressureGauge(pressureValue, containerId) {
        // Ajustando o tamanho do gráfico baseado no contêiner
        const width = document.getElementById(containerId).offsetWidth;
        const height = document.getElementById(containerId).offsetHeight;
        const outerRadius = Math.min(width, height) / 2 - 10; // Raio externo
        const innerRadius = outerRadius * 0.75; // Raio interno

        // Criando o SVG
        const svg = d3.create("svg")
            .attr("viewBox", `0 0 ${width} ${height}`)
            .attr("width", "100%")
            .attr("height", "100%");

        // Centralizando o gráfico
        const g = svg.append("g")
            .attr("transform", `translate(${width / 2}, ${height / 2})`);

        // Definindo a função de arco
        const arc = d3.arc()
            .innerRadius(innerRadius)
            .outerRadius(outerRadius)
            .startAngle(0);

        // Fundo do gráfico
        const background = g.append("path")
            .datum({ endAngle: 2 * Math.PI }) // Todo o círculo
            .style("fill", "#ddd")
            .attr("d", arc);

        // Gráfico de Pressão (preenchendo de acordo com o valor da pressão)
        const foreground = g.append("path")
            .datum({ endAngle: (pressureValue / 1100) * 2 * Math.PI }) // A porção baseada no valor de pressão (normalmente entre 900mbar e 1100mbar)
            .style("fill", pressureValue < 1000 ? "red" : "green") // Cor baseado no valor de pressão (vermelho para pressão baixa, verde para alta)
            .attr("d", arc);

        // Texto no centro do gráfico
        g.append("text")
            .attr("x", 0)
            .attr("y", 0)
            .attr("text-anchor", "middle")
            .attr("dy", ".35em")
            .style("font-size", "24px")
            .style("font-weight", "bold")
            .text(`${pressureValue} mbar`);

        // Adicionando o gráfico no contêiner
        document.getElementById(containerId).appendChild(svg.node());
    }

    // Usando o valor da pressão vindo do PHP
    const pressureValue = <?php echo $valor_dado_3; ?>;  // A variável PHP passa o valor da pressão
    createPressureGauge(pressureValue, "gaugeContainerPressure");
</script>
                        </div>
                    </div>

                    <div class="bloquinhos">
                        <div class="areatexto">
                            <h2>Vento</h2>
                            <h1><?php echo $valor_dado_4; ?> KM/H</h1>
                        </div>
                        <div class="areainterativa">
                            <h3></h3>
                        </div>
                    </div>

                    <div class="bloquinhos">
                        <div class="areatexto">
                            <h2><?php require_once 'includes/funcoes.php';
                            echo $direcao;
                            ?></h2>
                            <h1><?php echo $valor_dado_5; ?> °</h1>
                        </div>
                        <div class="areainterativa">

                        </div>
                    </div>

                    <div class="bloquinhos">
                        <div class="areatexto">
                            <h2>Condição Climática</h2>
                            <h1><?php echo $valor_dado_6; ?></h1>
                        </div>
                        <div class="areainterativa">
                            <h3></h3>
                        </div>
                    </div>


        </main>

        <footer>
            <div class="footer_item">
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="js/index.js"></script>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>



</body>

</html>