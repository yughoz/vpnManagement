<?php

namespace YuuApp\checkRoleHelp;
use Session;

class checkRoleHelp 
{
    public function checkAccess($module,$action = "")
    {
    	$dataSession = Session::get('moduleACC');
        
        if (!empty($action)) {
            if (empty($dataSession[$module][$action])) {
                return false;
            }
        } else {
            if (empty($dataSession[$module])) {
                return false;
            }
        }
        return true;
    }
}