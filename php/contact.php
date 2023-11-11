<?php
    $array = array("firstname" => "", "name" => "", "email" => "", "phone" => "", "message" => "", "firstnameError" => "", "nameError" => "", "mailError" => "", "emailError" => "", "phoneError" => "", "messageError" => "", "isSuccess" => false); //on remplace les variables ci-dessous par un array. À la fin, on va repasser à l'Ajax, un seul objet
    
    // $firstname = $name = $email = $phone = $message = "";
    // $firstnameError = $nameError = $emailError = $phoneError = $messageError = "";
    // $isSuccess = false;
    $emailTo = "luisase@gmail.com";

    if($_SERVER["REQUEST_METHOD"] == "POST"){ //la propriété REQUEST_METHOD se remplie quand le formulaire sera soumis; il charge post lorsque le bouton "submit" (method=post) est pesé

        //ci-dessous on remplace les variables $ par l'array d'en haut. P.ex., $firstname deviendra $array["firstname"]
        $array["firstname"] = verifyInput($_POST["firstname"]); //parce qu'on a donné l'id firstname pour le prénom dans le input
        $array["name"] = verifyInput($_POST["name"]); //même chose
        $array["email"] = verifyInput($_POST["email"]); //même chose
        $array["phone"] = verifyInput($_POST["phone"]); //même chose
        $array["message"] = verifyInput($_POST["message"]); //même chose
        $array["isSuccess"] = true; //initialiser ceci pour permettre d'imprimer la message de soumission du formulaire (voir la fin du code)
        $emailText = ""; //le message commence vide et il va s'agrandir avec la contatenation (points) ci-dessous

        if(empty($array["firstname"])){ //pour une véfirication si le champs est "required", mais au niveu du serveur cette fois-ci
            $array["firstnameError"] = "We want to know your first name!";
            $array["isSuccess"] = false;
        } else $emailText .= "Firstname: {$array["firstname"]}\n"; //le point nous permet de concatenner la variable au texte fournit. Le \n permet de sauter une ligne. Le {} permet d'inclure une array

        if(empty($array["name"])){ //même chose
            $array["nameError"] = "We want to know your last name!";
            $array["isSuccess"] = false;            
        } else $emailText .= "Name: {$array["name"]}\n";

        if(empty($array["message"])){ //même chose
            $array["messageError"] = "We want to know your message!";
            $array["isSuccess"] = false;
        } else $emailText .= "Message: {$array["message"]}\n";

        if(!isEmail($array["email"])){
            $array["emailError"] = "it is not an email address!";
            $array["isSuccess"] = false;
        } else $emailText .= "Email: {$array["email"]}\n";

        if(!isPhone($array["phone"])){
            $array["phoneError"] = "Only numbers and spaces, please.";
            $array["isSuccess"] = false;
        } else $emailText .= "Phone: {$array["phone"]}\n";

        if($array["isSuccess"]){
            $headers = "From: {$array["firstname"]} {$array["name"]} <{$array["email"]}>\r\n Reply-To: {$array["email"]}";
            mail($emailTo, "Un message de mon site", $emailText, $headers); //envoie d'email (serveur local xampp maintenant). On ne peut pas envoyer d'un serveur local facilement les emails. Lorsque c'est mis sur un seveur en ligne (Funio), ça doit fonctionner
            // $firstname = $name = $email = $phone = $message = ""; //remettre à zéro tous les champs une fois soumis le message
        }

        echo json_encode($array); //pour renvoyer notre array. Encode moi en objet json mon array (qui contient tout le résultat de notre travail dans le php)
    }
    
    function isPhone($var){
        return preg_match("/^[0-9 ]*$/", $var); //permet de mettre un masque avec une langue qui s'appelle "Expressions régulières". Les caractère possibles dans cette expression sont des chiffres de 0-9 et des espaces. L'étoile ("*") nous permet que le champs soit vide et me permet de répeter les chiffres autant que je veux. Pour forcer au moins un chiffre je remplace le "*" par "+"
    }

    function isEmail($var){
        return filter_var($var, FILTER_VALIDATE_EMAIL); //filtre de validation de email déjà fait. True si valide. Il a fait une fonction à part pour que ce soit plus jolie, plus net, dans le code. Il aurait pu mettre le filtre direct dans le code ci-dessus
    }
    
    function verifyInput($var){//Pour nettoyer la variable et enlever des possibles codes dangereux
        $var = trim($var); //enlène de retour à la ligne, des espaces supplémentaires, tabs, etc.
        $var = stripcslashes($var); //enlève le anti-slashs.
        $var = htmlspecialchars($var); //The htmlspecialchars() function converts some predefined characters to HTML entities.
        return $var;        
    }
?>