<?php

function round_up($number, $precision = 2)
{
    $fig = (int) str_pad('1', $precision, '0');
    return (ceil($number * $fig) / $fig);
}

function round_down($number, $precision = 2)
{
    $fig = (int) str_pad('1', $precision, '0');
    return (floor($number * $fig) / $fig);
}


include 'connectiondata.php';

$consulta = "SELECT * FROM cities WHERE id = '" . $city_id . "'";

$con      = $mysqli->query($consulta) or die($mysqli->error);

while ($irrad = $con->fetch_array()) {

    $city = $irrad['city'];

    $irradiation_city = [
        1 => $irrad['1'],
        2 => $irrad['2'],
        3 => $irrad['3'],
        4 => $irrad['4'],
        5 => $irrad['5'],
        6 => $irrad['6'],
        7 => $irrad['7'],
        8 => $irrad['8'],
        9 => $irrad['9'],
        10 => $irrad['10'],
        11 => $irrad['11'],
        12 => $irrad['12'],
        "md" => $irrad['md'],
    ];
}


$building_type = 0; // 0-> Residencial 1-> Rural 2-> Comercial, serv e outros 3-> Industrial
$taxes = 0.3551;


//Define a tarifa de acordo com o tipo de imóvel
switch ($building_type) {
    case 0:
        $tariff = 0.69365; // Residencial - 2022
        break;
    case 1:
        $tariff = 0.61040; // Rural - 2022
        break;
    case 2:
        $tariff = 0.69365; //Comercial e serv. - 2022
        break;
    case 3:
        $tariff = 0.69365; // Industrial - 2022
        break;
}

// Aplica tarifa social quando aplicável
if ($building_type == 0 and $consumption <= 30) {
    $tariff = $tariff * (1 - 0.65);
} elseif ($building_type == 0 and $consumption <= 100) {
    $tariff = $tariff * (1 - 0.40);
} elseif ($building_type == 0 and $consumption <= 220) {
    $tariff = $tariff * (1 - 0.10);
}

$monthly_bill = round(($consumption * $tariff) / (1 - $taxes), 2); //Calcula o valor da conta de energia paga atualmente
$annual_bill = round(($monthly_bill * 12), 2);

// Dados das placas solares
$plate330 = [
    "name" => "Default plate of 330w",
    "potency" => 330,
    "area" => 2,
    "efficiency" => 0.75,
    "lifespan" => 25,
];

$plate335 = [
    "name" => "Default plate of 335w",
    "potency" => 335,
    "area" => 2,
    "efficiency" => 0.75,
    "lifespan" => 25,
];

$plate360 = [
    "name" => "Default plate of 360w",
    "potency" => 360,
    "area" => 2,
    "efficiency" => 0.75,
    "lifespan" => 25,
];

$plate400 = [
    "name" => "Default plate of 400w",
    "potency" => 400,
    "area" => 2,
    "efficiency" => 0.75,
    "lifespan" => 25,
];



$greater_irradiation = max($irradiation_city);

$irradiation_city_month = 5;
//$diasmes=30.417;

$number_of_plates = round_up($monthly_bill / (((12 * $plate330["potency"] * 30.417 * $irradiation_city["md"] * $plate330["efficiency"]) / 1000) / 12), 0);

if ($number_of_plates == 1) {

    $unit_txt = 'unidade';
} else {

    $unit_txt = 'unidades';
}


$average_solar_production = ($number_of_plates * $plate330["potency"] * 30.417 * $irradiation_city["md"] * $plate330["efficiency"]) / 1000;
$anual_solar_production = (12 * $average_solar_production); //valor produzido por placa por ano
$anual_economy = $anual_solar_production * $tariff;
$average_anual_economy = ($anual_solar_production * $tariff) / (1 - $taxes);

$plate_production_1 = round(($number_of_plates * $plate330["potency"] * 31 * $irradiation_city[1] * $plate330["efficiency"]) / 1000, 2);
$plate_production_2 = round(($number_of_plates * $plate330["potency"] * 29 * $irradiation_city[2] * $plate330["efficiency"]) / 1000, 2);
$plate_production_3 = round(($number_of_plates * $plate330["potency"] * 31 * $irradiation_city[3] * $plate330["efficiency"]) / 1000, 2);
$plate_production_4 = round(($number_of_plates * $plate330["potency"] * 30 * $irradiation_city[4] * $plate330["efficiency"]) / 1000, 2);
$plate_production_5 = round(($number_of_plates * $plate330["potency"] * 31 * $irradiation_city[5] * $plate330["efficiency"]) / 1000, 2);
$plate_production_6 = round(($number_of_plates * $plate330["potency"] * 30 * $irradiation_city[6] * $plate330["efficiency"]) / 1000, 2);
$plate_production_7 = round(($number_of_plates * $plate330["potency"] * 31 * $irradiation_city[7] * $plate330["efficiency"]) / 1000, 2);
$plate_production_8 = round(($number_of_plates * $plate330["potency"] * 31 * $irradiation_city[8] * $plate330["efficiency"]) / 1000, 2);
$plate_production_9 = round(($number_of_plates * $plate330["potency"] * 30 * $irradiation_city[9] * $plate330["efficiency"]) / 1000, 2);
$plate_production_10 = round(($number_of_plates * $plate330["potency"] * 31 * $irradiation_city[10] * $plate330["efficiency"]) / 1000, 2);
$plate_production_11 = round(($number_of_plates * $plate330["potency"] * 30 * $irradiation_city[11] * $plate330["efficiency"]) / 1000, 2);
$plate_production_12 = round(($number_of_plates * $plate330["potency"] * 31 * $irradiation_city[12] * $plate330["efficiency"]) / 1000, 2);

$budget = -0.6412 * ($number_of_plates ^ 2) + 1747.7 * ($number_of_plates) + 8163.6;

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da simulação - BELUGA Engenharia</title>

    <link rel="stylesheet" href="style_results.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans&family=Patua+One&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700&family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        #month-1 {
            height: <?php echo round(100 * ($irradiation_city[1] / $greater_irradiation), 3) . "%"; ?>;
        }

        #month-2 {
            height: <?php echo round(100 * ($irradiation_city[2] / $greater_irradiation), 3) . "%"; ?>;
        }

        #month-3 {
            height: <?php echo round(100 * ($irradiation_city[3] / $greater_irradiation), 3) . "%"; ?>;
        }

        #month-4 {
            height: <?php echo round(100 * ($irradiation_city[4] / $greater_irradiation), 3) . "%"; ?>;
        }

        #month-5 {
            height: <?php echo round(100 * ($irradiation_city[5] / $greater_irradiation), 3) . "%"; ?>;
        }

        #month-6 {
            height: <?php echo round(100 * ($irradiation_city[6] / $greater_irradiation), 3) . "%"; ?>;
        }


        #month-7 {
            height: <?php echo round(100 * ($irradiation_city[7] / $greater_irradiation), 3) . "%"; ?>;
        }


        #month-8 {
            height: <?php echo round(100 * ($irradiation_city[8] / $greater_irradiation), 3) . "%"; ?>;
        }


        #month-9 {
            height: <?php echo round(100 * ($irradiation_city[9] / $greater_irradiation), 3) . "%"; ?>;
        }


        #month-10 {
            height: <?php echo round(100 * ($irradiation_city[10] / $greater_irradiation), 3) . "%"; ?>;
        }

        #month-11 {
            height: <?php echo round(100 * ($irradiation_city[11] / $greater_irradiation), 3) . "%"; ?>;
        }

        #month-12 {
            height: <?php echo round(100 * ($irradiation_city[12] / $greater_irradiation), 3) . "%"; ?>;
        }
    </style>
</head>

<body>

<header>

<a href='https://www.beluga.eng.br/simuladorsolar/' target="_blank"><img class='logo' src='./images/logo.png' /></a>

</header>

    <section class='show-results'>
        <div class='show-results-body'>

            <div class="show-results-title">
                <h1>Simulação de sistema de geração solar para <span class='city-name'><?php echo $city; ?></span></h1>
            </div>
            <div class="show-results-system-info">
                <span class='show-results-item'>Placas:</span> <?php echo $number_of_plates . " " . $unit_txt . " de " . $plate330["potency"] . "w (" . (str_replace(['.'], ',', $number_of_plates * $plate330["potency"] / 1000)) . "kWp).<br>"; ?>
                <span class='show-results-item'>Geração:</span> média de <?php echo number_format($average_solar_production, 2, ',', '.') . " kWh/mês (" . number_format($average_solar_production, 2, ',', '.') . " kWh/ano).<br>"; ?>
                <span class='show-results-item'>Área mín. nec.: </span> <?php echo ($plate330["area"] * $number_of_plates) . "m² de telhado ou superfície.</p>"; ?>
            </div>
            <div class='show-results-economy-data'>
                <div class='show-results-subtitle'><span class='show-results-item'>Economia estimada por ano: <br><span style='font-size: 15px; font-weight: bold;'>R$</span>
                    <h2><?php echo number_format($average_anual_economy, 2, ',', '.'); ?></h2><span style='font-size: 15px; font-weight: bold;'>.</span>
                </div>
                <div class='show-results-subtitle'>Economia em 25 anos*: <br><span style='font-size: 15px; font-weight: bold;'>R$</span>
                    <h2><?php echo number_format($average_anual_economy * $plate330["lifespan"], 2, ',', '.'); ?></h2><span style='font-size: 15px; font-weight: bold;'>.</span><br>
                </div>
            </div>
            <p style='margin-bottom: 7px;'>Economia ao longo do ano em reais**:</p>

            <div class='meses'>
                <div class='submes' id='month-1'>
                    <div class='v'><?php echo number_format(($plate_production_1 * $tariff) / (1 - $taxes), 2, ',', '.'); ?></div>
                </div>
                <div class='submes' id='month-2'>
                    <div class='v'> <?php echo number_format(($plate_production_2 * $tariff) / (1 - $taxes), 2, ',', '.'); ?></div>
                </div>
                <div class='submes' id='month-3'>
                    <div class='v'><?php echo number_format(($plate_production_3 * $tariff) / (1 - $taxes), 2, ',', '.'); ?></div>
                </div>
                <div class='submes' id='month-4'>
                    <div class='v'><?php echo number_format(($plate_production_4 * $tariff) / (1 - $taxes), 2, ',', '.'); ?></div>
                </div>
                <div class='submes' id='month-5'>
                    <div class='v'><?php echo number_format(($plate_production_5 * $tariff) / (1 - $taxes), 2, ',', '.'); ?></div>
                </div>
                <div class='submes' id='month-6'>
                    <div class='v'><?php echo number_format(($plate_production_6 * $tariff) / (1 - $taxes), 2, ',', '.'); ?></div>
                </div>
                <div class='submes' id='month-7'>
                    <div class='v'><?php echo number_format(($plate_production_7 * $tariff) / (1 - $taxes), 2, ',', '.'); ?></div>
                </div>
                <div class='submes' id='month-8'>
                    <div class='v'><?php echo number_format(($plate_production_8 * $tariff) / (1 - $taxes), 2, ',', '.'); ?></div>
                </div>
                <div class='submes' id='month-9'>
                    <div class='v'><?php echo number_format(($plate_production_9 * $tariff) / (1 - $taxes), 2, ',', '.'); ?></div>
                </div>
                <div class='submes' id='month-10'>
                    <div class='v'><?php echo number_format(($plate_production_10 * $tariff) / (1 - $taxes), 2, ',', '.'); ?></div>
                </div>
                <div class='submes' id='month-11'>
                    <div class='v' id='v11'><?php echo number_format(($plate_production_11 * $tariff) / (1 - $taxes), 2, ',', '.'); ?></div>
                </div>
                <div class='submesul' id='month-12'>
                    <div class='v'><?php echo number_format(($plate_production_12 * $tariff) / (1 - $taxes), 2, ',', '.'); ?></div>
                </div>
            </div>
            <div class='submeses'><span class='titsubmeses'>Jan</span><span class='titsubmeses'>Fev</span><span class='titsubmeses'>Mar</span><span class='titsubmeses'>Abr</span><span class='titsubmeses'>Mai</span><span class='titsubmeses'>Jun</span><span class='titsubmeses'>Jul</span><span class='titsubmeses'>Ago</span><span class='titsubmeses'>Set</span><span class='titsubmeses'>Out</span><span class='titsubmeses'>Nov</span><span class='titsubmesesul'>Dez</span></div>
            <div class='caixatextend'>
                <div id='textendfim' class='textend'>Custo estimado do sistema: <br><span style='font-size: 15px; font-weight: bold;'>De R$</span>
                    <h2><?php echo number_format($budget * 0.947368, 2, ',', '.') . "</span><span style='font-size: 15px; font-weight: bold;'> a R$</span><h2>" . number_format($budget * 1.052632, 2, ',', '.'); ?></span><span style='font-size: 15px; font-weight: bold;'>.</span>
                </div>
                <div class='txtcond'>*Tempo em que as placas atingem 80% de eficiência. **Estimativa com base nos dados
                    públicos da CRESESB e nos custos da concessionária para 2021.</div>
            </div>

        </div>
    </section>

    <div id='blp2'>
        <div id='sblp2'>

            <div class='tit' style='margin-top: 7px;'>Ficou interessado? Peça um orçamento detalhado</div>
            <b>Aproveite o último ano com insenção de subsídios.</b> Preencha seus dados de contato para saber mais sobre valores,
            opções de financiamento, detalhes de instalação, e mais.
            <div id='formpai'>
                <form method='post'>
                    <div id='formquebra'>
                        <div class='formtxt'>Telefone e/ou WhatsApp:<br><input class='base' name='phone' onkeypress='return isNumberKey(event)' type='text'></input></div>
                        <div class='formtxt'>E-mail*:<br><input class='base' name='mail' value='<?php echo $mail; ?>' type='email' required></input></div>
                        <div class='formtxt'>Cidade de instalação*:<br><input class='base' name='city' value='<?php echo $city . " (MS)"; ?>' type='text' value='Campo Grande - MS' required></input></div>
                    </div>

                    <input class='base' name='ocorreu' value='1' type='hidden'></input>
                    <br><br>
                    <center>
                        <button>Solicitar</button>
                    </center>
                </form>
            </div>

            <div class='tit'>Por que ter energia solar?</div><br>
            <div id='pergfreq'>
                <div class='subper'>
                    <center><img src="https://www.beluga.eng.br/icon1.png" style="width: 70px;" /></center>
                    <br>

                    Perfeita para a sua casa, comércio ou indústria.<br>A energia solar pode ser instalada em diversos tipos de imóveis.<br><br>
                    <br>

                    <center><img src="https://www.beluga.eng.br/icon3.png" style="width: 70px;" /></center>
                    <br>
                    A conta de energia aumenta ano após ano.
                    <br>Mais de 30% de aumento nos últimos 5 anos.

                    <br><br>
                </div>
                <div class='subper'>
                    <center><img src="https://www.beluga.eng.br/icon2.png" style="width: 70px;" /></center>
                    <br>
                    Economia enquanto ajuda o meio ambiente.<br>
                    Energia limpa sem emissão de CO2.
                    <br><br>
                    <br>

                    <center><img src="https://www.beluga.eng.br/icon4.png" style="width: 70px;" /></center>
                    <br>
                    Estado com posição geográfica favorável à energia solar.
                    <br>Maior eficiência, com mais energia gerada por área.
                    <br><br>
                </div>
            </div>

            <div class='tit'>Sobre a Voltac Energia Solar</div>

            A Voltac Energia Solar é uma empresa focada no projeto e instalação de energia solar, com experiência de execução tanto na cidade quanto no campo.
            <br><br>
            <center>
                <img src="https://www.beluga.eng.br/img1.jpeg" style="width:90%;" />
            </center>
            <br>



        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var top = document.querySelector(".h-section.h-hero.d-flex.align-items-lg-center.align-items-md-center.align-items-center.style-415.style-local-15-h22.position-relative");
            document.querySelector(".background-wrapper").innerHTML = null;
            top.style.background = "rgba(7, 12, 25, 0.55)";
            top.style.borderBottom = "1px solid rgba(255, 255, 255, 0.17)";

            document.title = 'Simulação de sistema solar - Beluga Engenharia';

        });

        function resize() {
            var top = document.querySelector(".h-section.h-hero.d-flex.align-items-lg-center.align-items-md-center.align-items-center.style-415.style-local-15-h22.position-relative");
            if ($(window).width() < 751) {
                top.style.height = '90px';

            } else {
                top.style.height = '120px';
            }
        }

        $(window).on("resize", resize);
        resize(); // call once initially


        function oculta(el) {
            var display = document.getElementById(el).style.display;
            if (display == "none")
                document.getElementById(el).style.display = 'block';
            else
                document.getElementById(el).style.display = 'none';
        }

        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }
    </script>

    <div class='rodape'>
        <b>Beluga - @belugaengenharia</b>
    </div>