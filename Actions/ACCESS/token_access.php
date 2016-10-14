<?php
function token_access(Action & $action)
{
    $action->parent->addJsRef("lib/jquery/1.7.2/jquery.js");
    $action->parent->addJsRef("lib/jquery-ui-1.12.0/jquery-ui.js");
    $action->parent->addJsRef("lib/jquery-dataTables/1.10/js/jquery.dataTables.js");
    $action->parent->addJsRef("ACCESS/Layout/token_access.js");
    
    $action->parent->addCssRef("WHAT/Layout/size-normal.css");
    $action->parent->addCssRef("lib/jquery-ui-1.12.0/jquery-ui.css");
    $action->parent->addCssRef("lib/jquery-dataTables/1.10/css/jquery.dataTables.css");
    $action->parent->addCssRef("lib/jquery-dataTables/1.10/css/dataTables.jqueryui.css");
    
    $action->parent->addCssRef("ACCESS/Layout/token_access.css");
}
