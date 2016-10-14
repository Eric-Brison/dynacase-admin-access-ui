<?php
function token_method(Action & $action)
{
    $usage = new ActionUsage($action);
    //$search = $usage->addOptionalParameter("search", "search filters");
    $method = $usage->addRequiredParameter("method", "method to use", array(
        "delete"
    ));
    $token = $usage->addRequiredParameter("token", "token");
    
    $usage->setStrictMode(false);
    $usage->verify();
    $err = "";
    
    switch ($method) {
        case "delete":
            $userToken = new UserToken($action->dbaccess, $token);
            if ($userToken->isAffected()) {
                $err = $userToken->delete();
            } else {
                $err = sprintf(___("Token %s not exists", "access") , $token);
            }
            break;
    }
    
    header('Content-Type: application/json');
    
    if ($err) {
        header("HTTP/1.0 400 Error");
        $response = ["success" => false, "error" => $err];
    } else {
        $response = ["success" => true, "token" => $token, "message" => sprintf(___("<p>Token <b>%s</b></br> has been deleted.</p>", "access") , $token) ];
    }
    $action->lay->noparse = true;
    $action->lay->template = json_encode($response);
}
