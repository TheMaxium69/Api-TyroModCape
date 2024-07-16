<?php

header('Content-Type: application/json');

if (!empty($_GET['pseudo'])) {

    require "db.php";

    $pseudo = $_GET['pseudo'];

    /* REGARDER TOUTE LES CAPE ACHETER PAR LE JOUEUR */
    $sql = "SELECT * FROM players WHERE playerName = '$pseudo'";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* A T'IL ACHETER DES CAPE */
    if ($stmt->rowCount() > 0) {

        /* A T'IL UNE SEUL CAPE */
        if ($stmt->rowCount() == 1) {

            /* IL EN A 1 SEUL ALORS JE RENVOIE LA SEUL QU'IL A */
            $idCapes = $result[0]['idCapes'];

            $sql2 = "SELECT * FROM capes WHERE id = '$idCapes'";
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();
            $resultCapeOne = $stmt2->fetch();

            if ($resultCapeOne['isAnimated'] == 1){
                $isAnimated = true;
            } else {
                $isAnimated = false;
            }

            $data = array(
                "animatedCape" => $isAnimated,
                "capeGlint" => false,
                "upsideDown" => false,
                "textures" => array(
                    "cape" => imgToBase64($resultCapeOne['url']),
                    "ears" => null
                )
            );


        } else {

            /* IL EN A PLUSIEUR ALORS JE CONSULE SI IL EN A SELECTIONNER UNE */

            $json = file_get_contents('http://127.0.0.1/ApiUsertium/?controller=TyroServ&task=getCapeByPseudo&pseudo=' . $pseudo);
//            $json = file_get_contents('https://useritium.fr/api-externe/?controller=TyroServ&task=getCapeByPseudo&pseudo=' . $pseudo);
            $resultUseritiumApi = json_decode($json);
            $idChooseCape = $resultUseritiumApi->result->cape;

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

                /* JE LUI DONNE ALORS LA DERNIER CAPE ACHETER */
                $sql4 = "SELECT * FROM `players` WHERE `playerName` = '$pseudo' ORDER BY `players`.`dateAdded` DESC LIMIT 1";
                $stmt4 = $db->prepare($sql4);
                $stmt4->execute();
                $resultPlayerLatest = $stmt4->fetch();

                $idCapesLatest = $resultPlayerLatest['idCapes'];

                $sql5 = "SELECT * FROM capes WHERE id = '$idCapesLatest'";
                $stmt5 = $db->prepare($sql5);
                $stmt5->execute();
                $resultCapeLatestOne = $stmt5->fetch();

                if ($resultCapeLatestOne['isAnimated'] == 1){
                    $isAnimated = true;
                } else {
                    $isAnimated = false;
                }

                $data = array(
                    "animatedCape" => $isAnimated,
                    "capeGlint" => false,
                    "upsideDown" => false,
                    "textures" => array(
                        "cape" => imgToBase64($resultCapeLatestOne['url']),
                        "ears" => null
                    )
                );

            } else {

                /* JE LUI DONNE LA CAPE SELECTIONNER SUR LE PANEL */

                $idCapes = $result[$idValide-1]['idCapes'];

                $sql3 = "SELECT * FROM capes WHERE id = '$idCapes'";
                $stmt3 = $db->prepare($sql3);
                $stmt3->execute();
                $resultCapeChooseOne = $stmt3->fetch();

                if ($resultCapeChooseOne['isAnimated'] == 1){
                    $isAnimated = true;
                } else {
                    $isAnimated = false;
                }

                $data = array(
                    "animatedCape" => $isAnimated,
                    "capeGlint" => false,
                    "upsideDown" => false,
                    "textures" => array(
                        "cape" => imgToBase64($resultCapeChooseOne['url']),
                        "ears" => null
                    )
                );

            }



        }

    /* IL N'A AUCUNE CAPE */
    } else {

        $data = array(
            "animatedCape" => false,
            "capeGlint" => false,
            "upsideDown" => false,
            "textures" => array(
                "cape" => null,
                "ears" => null
            )
        );

    }

    echo json_encode($data);



} else {

    echo json_encode("Field not defined");

}

function imgToBase64($url)
{

    $file_path = dirname(__FILE__);
    return base64_encode(file_get_contents($file_path . "/capes/" . $url));

}