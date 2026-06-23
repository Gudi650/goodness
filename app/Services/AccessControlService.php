<?php

namespace App\Services;

class AccessControlService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    //function to check if user is CEO or Admin return true if user is CEO or Admin, otherwise return false
    public function isCeoOrAdmin($user) : bool
    {
        return $user->is_ceo || $user->is_admin;
    }

    

}
