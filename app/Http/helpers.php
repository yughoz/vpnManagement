<?php

// use Session;

// class checkRoleHelp 
// {
   function checkAccess($module,$action = "")
    {
        $dataSession = Session::get('moduleACC');
        // $dataSessionUser = ;

        if (in_array( Session::get('dataAPL.id'),config('adminlte.users_dev'))) {
            return true;
       }
        
        if (!empty($action)) {
            if (empty($dataSession[$module]->$action)) {
                return false;
            }
        } else {
            if (empty($dataSession[$module])) {
                return false;
            }
        }
        return true;
    }

// }