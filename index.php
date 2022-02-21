<?php

if (isset($_POST["mail"]) && isset($_POST["consumption"]) && isset($_POST["countrystate"]) && isset($_POST["city_id"])) { //If required, make the simulation request record.



    if (isset($_POST["subscribe"])) {


        $subscribe = $_POST["subscribe"];
    } else {

        $subscribe = "not";
    }

    $mail = $_POST["mail"];
    $consumption = str_replace(',', '.', $_POST["consumption"]);
    $countrystate = $_POST["countrystate"];

    if (strlen($_POST["city_id"]) < 3) {

        $city_id = $_POST["city_id"];
    } else {

        $city_id = '18';
    }

    include 'connectiondata.php';

    date_default_timezone_set('UTC');
    $dateandtime = date('l jS \of F Y h:i:s A');

    $stmt = $mysqli->prepare("INSERT INTO entries (mail, subscribe, consumption, countrystate, city_id, dateandtime) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $mail, $subscribe, $consumption, $countrystate, $city_id, $dateandtime);

    if ($stmt->execute()) { //successful connection

        $stmt->close();
        $mysqli->close();

        include 'results.php'; //If the registration is successful, open the results page.

        exit;
    } else {

        echo 'An error occurred when trying to connect to the database.'; //If the connection is not established, show the error message.
    }
}

# Code below make the budget request.

if (isset($_POST["has-happened"])) {


    if (isset($_POST["phone"])) {

        $phone = $_POST["phone"];
    } else {

        $phone = 0;
    }

    if (isset($_POST["mail"]) && isset($_POST["city_id"])) {

        $mail = $_POST["mail"];

        $city_id = $_POST["city_id"];

        if ($local == 'y') {

            $mysqli = new mysqli($local_hostname, $local_user, $local_password, $local_database);
        } elseif ($local == 'n') {

            $mysqli = new mysqli($server_hostname, $server_user, $server_password, $server_database);
        }

        //Code below make data records.

        date_default_timezone_set('UTC');
        $datadia = date('l jS \of F Y h:i:s A');

        $stmt = $mysqli->prepare("INSERT INTO requests (mail, phone, city_id, dateandtime) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $mail, $phone, $city_id, $dateandtime);

        if ($stmt->execute()) {

            $stmt->close();
            $mysqli->close();

?>

            <!DOCTYPE html>
            <html lang="pt-BR">

            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="initial-scale=1.0">
                <title>Solicitação enviada! - BELUGA Engenharia</title>
            </head>

            <body>

            </body>

            </html>

            <br><br>

            <h2>Solicitação enviada com sucesso!</h2>
            <br>
            <h3>Agora você está mais perto de ter energia limpa e barata.</h3>
            <br><br>Enquanto isso, nos siga nas redes sociais: <br><a href="https://www.instagram.com/belugaengenharia/" target="_blank">@belugaengenharia</a> e <a href="https://www.instagram.com/voltac.solar/" target="_blank">@voltac.solar</a>.

            <a href="https://www.beluga.eng.br/simuladorsolar/">
                <br><br>Voltar.</a>


        <?php
            //Connection established.
            exit;
        } else {

            echo 'An error occurred when trying to connect to the database.';
        }
    }
} else {



    # Checks if the user has already requested for something.

    if (empty($_POST["consumption"]) == true) { //If not, open the homepage html.

        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">

        <head>
            <meta charset="UTF-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="initial-scale=1.0" />
            <title>Simulador de energia solar - BELUGA Engenharia</title>

            <link rel="stylesheet" href="style_results.css" />

            <script src="script.js"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

            <link rel='preconnect' href='https://fonts.gstatic.com'>
            <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700&family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

        </head>

        <body>
            <section class='header-index'>

                <a href='https://www.beluga.eng.br/simuladorsolar/' target="_blank"><img class='logo' src='./images/logo.png' /></a>

            </section>


            <h1>Simule seu sistema de geração de energia solar.</h1>
            <div class="top-text">
                <span class="hide-mobile">A energia solar está proporcionando independência energética para cada vez mais familias, comércios e industrias.<br></span>
                Faça uma simulação e descubra o quanto pode economizar com um sistema de geração de energia solar.<br><br>
            </div>
            <section class='form'>

                <form method='post'>

                    Valor mensal da conta de energia (R$):<br><input class='input-field' type='text' onkeyup='oneDot(this)' onkeypress='return isNumberKey(event)' name='consumption' placeholder='0,00' required /><br>
                    Estado:<br><select class="select-field" name="countrystate">
                        <option value="Mato Grosso do Sul">Mato Grosso do Sul</option>
                    </select><br>
                    Cidade:<br>

                    <?php

                    include "connectiondata.php";

                    if ($mysqli->connect_errno) {
                        echo "An error occurred when trying to connect to the database." . $mysqli->connect_errno . ") " . $mysqli->connect_error;
                    }

                    $sql = "SELECT * FROM cities";
                    $con      = $mysqli->query($sql) or die($mysqli->error);
                    ?>

                    <select class="select-field" name="city_id">';

                        <?php
                        while ($irrad = $con->fetch_array()) {

                            if ($irrad['city'] == 'Campo Grande') {

                                echo '<option value="' . $irrad['id'] . '" selected>' . $irrad['city'] . '</option>';
                            } else {

                                echo '<option value="' . $irrad['id'] . '">' . $irrad['city'] . '</option>';
                            }
                        }
                        ?>

                    </select><br>
                    Seu e-mail:<br>
                    <input class="input-field" type="email" name="mail" required />
                    <br>
                    <?php

                    ?>

                    <div class="input-subscribe">
                        <input type='checkbox' name='subscribe' value='yes'>
                        <label for='subscribe'><span class="input-subscribe-txt">Quero receber novidades no e-mail.</span>
                        </label>
                        </input>
                    </div>

                    <button class='btn'>Simular</button>

                </form>




            </section>

            <section class="partner-info">

                Em parceria com<br>
                <img style='width: 190px;' src='https://www.beluga.eng.br/voltac_logo.jpg' />

            </section>

        </body>

        </html>

<?php
    } else {
    }
}

?>