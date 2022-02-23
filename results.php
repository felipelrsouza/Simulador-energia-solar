<?php

function round_up($number, $precision = 2) //To round numbers when needed.
{
    $fig = (int) str_pad('1', $precision, '0');
    return (ceil($number * $fig) / $fig);
}

function round_down($number, $precision = 2) //To round numbers when needed.
{
    $fig = (int) str_pad('1', $precision, '0');
    return (floor($number * $fig) / $fig);
}

include 'connectiondata.php'; //MySQL connection file.

$query = "SELECT * FROM cities WHERE id = '" . $city_id . "'"; //Gets the solar data from the user's city.
$con      = $mysqli->query($query) or die($mysqli->error);

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

$taxes = 0.3551; // Taxes percentage.

$building_type = 0; // 0-> Residential 1-> Rural 2-> Commercial, serv 3-> Industry

switch ($building_type) { //Set tariff according to the type of property.
    case 0:
        $tariff = 0.69365; // Residential - Reference year: 2022
        break;
    case 1:
        $tariff = 0.61040; // Rural - Reference year: 2022
        break;
    case 2:
        $tariff = 0.69365; // Commercial, serv - Reference year: 2022
        break;
    case 3:
        $tariff = 0.69365; // Industry - Reference year: 2022
        break;
}

//Apply social tariff if needed.
if ($building_type == 0 and $consumption <= 30) {
    $tariff = $tariff * (1 - 0.65);
} elseif ($building_type == 0 and $consumption <= 100) {
    $tariff = $tariff * (1 - 0.40);
} elseif ($building_type == 0 and $consumption <= 220) {
    $tariff = $tariff * (1 - 0.10);
}

//$monthly_bill = round(($consumption * $tariff) / (1 - $taxes), 2); //Allow user to insert monthly consumption instead of monthly bill, if needed.
$monthly_bill = $consumption;
$annual_bill = round(($monthly_bill * 12), 2);

// Solar plate data
$plate330 = [
    "name" => "Default plate of 330w",
    "potency" => 330, //in watts.
    "area" => 2, //in m².
    "efficiency" => 0.75,
    "lifespan" => 25, //in years.
];

$plate335 = [
    "name" => "Default plate of 335w",
    "potency" => 335, //in watts.
    "area" => 2, //in m².
    "efficiency" => 0.75,
    "lifespan" => 25, //in years.
];

$plate360 = [
    "name" => "Default plate of 360w",
    "potency" => 360, //in watts.
    "area" => 2, //in m².
    "efficiency" => 0.75,
    "lifespan" => 25, //in years.
];

$plate400 = [
    "name" => "Default plate of 400w",
    "potency" => 400, //in watts.
    "area" => 2, //in m².
    "efficiency" => 0.75,
    "lifespan" => 25, //in years.
];

$greater_irradiation = max($irradiation_city); //Take the greater irradiation month to build graphic (set what 100% will be).

$number_of_plates = round_up($monthly_bill / (((12 * $plate330["potency"] * 30.417 * $irradiation_city["md"] * $plate330["efficiency"]) / 1000) / 12), 0); // Calculate the number of plates in the system.

if ($number_of_plates == 1) { 
    $unit_txt = 'unidade';
} else {
    $unit_txt = 'unidades';
};

$average_solar_production = ($number_of_plates * $plate330["potency"] * 30.417 * $irradiation_city["md"] * $plate330["efficiency"]) / 1000; // Average production per month in R$.
$anual_solar_production = (12 * $average_solar_production); // Average anual production R$.
$anual_economy = $anual_solar_production * $tariff; // Average anual production R$.
$average_anual_economy = ($anual_solar_production * $tariff) / (1 - $taxes); // Average anual economy R$.

// Production for each month in R$.
$plate_production[1] = round(($number_of_plates * $plate330["potency"] * 31 * $irradiation_city[1] * $plate330["efficiency"]) / 1000, 2);
$plate_production[2] = round(($number_of_plates * $plate330["potency"] * 29 * $irradiation_city[2] * $plate330["efficiency"]) / 1000, 2);
$plate_production[3] = round(($number_of_plates * $plate330["potency"] * 31 * $irradiation_city[3] * $plate330["efficiency"]) / 1000, 2);
$plate_production[4] = round(($number_of_plates * $plate330["potency"] * 30 * $irradiation_city[4] * $plate330["efficiency"]) / 1000, 2);
$plate_production[5] = round(($number_of_plates * $plate330["potency"] * 31 * $irradiation_city[5] * $plate330["efficiency"]) / 1000, 2);
$plate_production[6] = round(($number_of_plates * $plate330["potency"] * 30 * $irradiation_city[6] * $plate330["efficiency"]) / 1000, 2);
$plate_production[7] = round(($number_of_plates * $plate330["potency"] * 31 * $irradiation_city[7] * $plate330["efficiency"]) / 1000, 2);
$plate_production[8] = round(($number_of_plates * $plate330["potency"] * 31 * $irradiation_city[8] * $plate330["efficiency"]) / 1000, 2);
$plate_production[9] = round(($number_of_plates * $plate330["potency"] * 30 * $irradiation_city[9] * $plate330["efficiency"]) / 1000, 2);
$plate_production[10] = round(($number_of_plates * $plate330["potency"] * 31 * $irradiation_city[10] * $plate330["efficiency"]) / 1000, 2);
$plate_production[11] = round(($number_of_plates * $plate330["potency"] * 30 * $irradiation_city[11] * $plate330["efficiency"]) / 1000, 2);
$plate_production[12] = round(($number_of_plates * $plate330["potency"] * 31 * $irradiation_city[12] * $plate330["efficiency"]) / 1000, 2);

$budget = -0.6412 * ($number_of_plates ^ 2) + 1747.7 * ($number_of_plates) + 8163.6; // Total cost.

// Show results page.

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da simulação - BELUGA Engenharia</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700&family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="script.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php
        for ($x = 1; $x <= 12; $x++) {
            echo "#month-" . $x . " {height: " . (round(100 * ($irradiation_city[$x] / $greater_irradiation), 3)) . "%;}\n";
        }
        ?>
    </style>
</head>
<body>
    <div class='top-bcg'>
        <section class='header results'>
            <a href='https://www.beluga.eng.br/simuladorsolar/' target="_blank"><img src='./images/logo.png' /></a>
        </section>
        <div class='block-1-bcg'>
            <section class='block-1'>
                <h1>Simulação <span class='hidden-on-mobile'>de sistema de geração solar </span>para <span class='title-city-name'><?php echo $city; ?></span></h1>
                <div class='block-1-container'>
                    <div>
                        <span class='block-1-item'>Placas:</span> <?php echo $number_of_plates . " " . $unit_txt . " de " . $plate330["potency"] . "w (" . (str_replace(['.'], ',', $number_of_plates * $plate330["potency"] / 1000)) . "kWp).<br>"; ?>
                        <span class='block-1-item'>Geração:</span> média de <?php echo number_format($average_solar_production, 2, ',', '.') . " kWh/mês<span class='hidden-on-mobile'> (" . number_format($average_solar_production, 2, ',', '.') . " kWh/ano)</span>.<br>"; ?>
                        <span class='block-1-item'>Área mín. nec.: </span> <?php echo ($plate330["area"] * $number_of_plates) . "m² de <span class='hidden-on-mobile'>telhado ou superfície."; ?>
                    </div>
                    <div class='block-1-item-left'><span class='block-1-item'>Economia estimada por ano:</span><br><span class='block-1-detail'>R$</span>
                        <span class='block-1-price'><?php echo number_format($average_anual_economy, 2, ',', '.'); ?></span><span class='block-1-detail'>.</span>
                    </div>
                    <div><span class='block-1-item'>Economia em 25 anos*:</span> <br><span class='block-1-detail'>R$</span>
                        <span class='block-1-price'><?php echo number_format($average_anual_economy * $plate330["lifespan"], 2, ',', '.'); ?></span><span class='block-1-detail'>.</span>
                    </div>
                </div>
                <span class='block-1-item'>Economia ao longo do ano em reais**:</span>
                <section class='block-1-graphic'>
                    <?php
                    for ($x = 1; $x <= 12; $x++) {
                        echo "<div id='month-" . $x . "'><div>" . (number_format(($plate_production[$x] * $tariff) / (1 - $taxes), 2, ',', '.')) . "</div></div>";
                    }
                    ?>
                </section>
                <div class='block-1-graphic-under'>
                    <div>Jan</div>
                    <div>Fev</div>
                    <div>Mar</div>
                    <div>Abr</div>
                    <div>Mai</div>
                    <div>Jun</div>
                    <div>Jul</div>
                    <div>Ago</div>
                    <div>Set</div>
                    <div>Out</div>
                    <div>Nov</div>
                    <div>Dez</div>
                </div>
                <div class='block-1-container'>
                    <div><span class='block-1-item'>Custo estimado do sistema:</span><br><span class='block-1-detail'>R$</span>
                        <span class='block-1-price'><?php echo number_format($budget * 0.947368, 2, ',', '.') . "</span><span class='block-1-detail'> a R$</span><span class='block-1-price'>" . number_format($budget * 1.052632, 2, ',', '.'); ?></span><span class='block-1-detail'>.</span>
                    </div>
                    <div class='block-1-note'>*Tempo em que as placas atingem 80% de eficiência. **Estimativa com base nos dados
                        públicos da CRESESB e nos custos da concessionária para 2021.
                    </div>
                </div>
            </section>
        </div>
    </div>
    </div>
    <section class='block-2'>
        <h1><span class='hidden-on-mobile'>Ficou interessado? </span>Peça um orçamento detalhado.</h1>
        <p><span class='hidden-on-mobile'>Aproveite o último ano com insenção de subsídios. </span>Preencha seus dados de contato para saber mais sobre valores,
            opções de financiamento, detalhes de instalação, e mais.</p>
        <form method='post'>
            <div class='form'>
                <div>Cidade de instalação*:<br><input name='city' value='<?php echo $city . " (MS)"; ?>' type='text' value='Campo Grande - MS' required></input></div>
                <div>Telefone e/ou WhatsApp:<br><input name='phone' onkeypress='return isNumberKey(event)' type='text'></input></div>
                <div>E-mail*:<br><input name='mail' value='<?php echo $mail; ?>' type='email' required></input></div>
                <input name='has-happened' value='1' type='hidden'></input>
                <div class='form-btn'><button>Solicitar</button></div>
            </div>
        </form>
        <h1>Por que ter energia solar?</h1><br>
        <section class='block-3'>
            <div><img src="https://www.beluga.eng.br/icon1.png" />
                <p>
                    Perfeita para a sua casa, comércio ou indústria.
                    <br>A energia solar pode ser instalada em diversos tipos de imóveis.
                </p>
            </div>
            <div><img src="https://www.beluga.eng.br/icon3.png" />
                <p>
                    A conta de energia aumenta ano após ano.
                    <br>Mais de 30% de aumento nos últimos 5 anos.
                </p>
            </div>
            <div><img src="https://www.beluga.eng.br/icon2.png" />
                <p>
                    Economia enquanto ajuda o meio ambiente.<br>
                    Energia limpa sem emissão de CO2.
                </p>
            </div>
            <div><img src="https://www.beluga.eng.br/icon4.png" />
                <p>
                    Estado com posição geográfica favorável à energia solar.
                    <br>Maior eficiência, com mais energia gerada por área.
                </p>
            </div>
        </section>
        <h1>Sobre a Voltac Energia Solar</h1>
        <p>A Voltac Energia Solar é uma empresa focada no projeto e instalação de energia solar, com experiência de execução tanto na cidade quanto no campo.</p>
        <div class='about-img'></div>
    </section>
    <div class="footer"><br>
        Beluga - @belugaengenharia
    </div>
</body>
</html>