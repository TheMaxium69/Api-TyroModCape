<?php

header('Content-Type: application/json');

if (!empty($_GET['pseudo'])) {

    require "db.php";

    $pseudo = $_GET['pseudo'];

    $sql = "SELECT * FROM players WHERE playerName = '$pseudo'";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as &$res) {
        $res["isSelected"] = 0;
    }

    if ($stmt->rowCount() == 1) {

        foreach ($result as &$res) {
            $res["isSelected"] = 1;
        }

        echo json_encode($result);
        die();


    } else {

        /* IL EN A PLUSIEUR ALORS JE CONSULE SI IL EN A SELECTIONNER UNE */

        if (!empty($_GET['idCapeUseritium'])){

            $idChooseCape = $_GET['idCapeUseritium'];

        } else {

            $json = file_get_contents('http://127.0.0.1/ApiUsertium/?controller=TyroServ&task=getCapeByPseudo&pseudo=' . $pseudo);
//            $json = file_get_contents('https://useritium.fr/api-externe/?controller=TyroServ&task=getCapeByPseudo&pseudo=' . $pseudo);
            $resultUseritiumApi = json_decode($json);
            $idChooseCape = $resultUseritiumApi->result->cape;
        }

        $idValide = false;

        if ($idChooseCape != null){

            $i = 1;
            foreach ($result as $oneCape) {

                if ($idChooseCape == $oneCape['idCapes']){
                    $idValide = $i;
                }
                $i++;
            }

        }

        /* IL NA PAS SELECTIONNER DE CAPE OU IL A MYTHO SUR LE PANNEL */
        if (!$idValide || $idChooseCape == null){

            /* VERIFIER SI IL NE VEUT TOUT SIMPLEMENT PAS DE CAPE*/
            if ($idChooseCape != 99999999){

                /* JE LUI DONNE ALORS LA DERNIER CAPE ACHETER */
                $sql4 = "SELECT * FROM `players` WHERE `playerName` = '$pseudo' ORDER BY `players`.`dateAdded` DESC LIMIT 1";
                $stmt4 = $db->prepare($sql4);
                $stmt4->execute();
                $resultPlayerLatest = $stmt4->fetch();



                $idCapesLatest = $resultPlayerLatest['idCapes'];

                foreach ($result as &$res) {
                    if ($idCapesLatest == $res['idCapes']){
                        $res['isSelected'] = 1;
                    }
                }

            }



        } else {

            /* JE LUI DONNE LA CAPE SELECTIONNER SUR LE PANEL */

            $idCapes = $result[$idValide-1]['idCapes'];

            foreach ($result as &$res) {
                if ($idCapes == $res['idCapes']){
                    $res['isSelected'] = 1;
                }
            }

        }

        echo json_encode($result);
        die();


    }







} else {

    echo json_encode("Field not defined");

}