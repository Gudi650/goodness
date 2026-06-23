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

    //acces everything in the sytem
    public function isAlwaysAllowed($user) : bool
    {
        return $user->role?->name === 'Admin' || $user->role?->name === 'CEO';
    }

    //function to check if user is CEO or Admin or Accountant return true if user is CEO or Admin, otherwise return false
    public function isCeoOrAdminOrAccountant($user) : bool
    {
        return $user->role?->name === 'CEO' || $user->role?->name === 'Admin' || $user->role?->name === 'Accountant';
    }

    //function to check if the user is a regular employee
    public function isEmployee($user) : bool
    {
        return $user->role?->name === 'Employee';
    }

    //function to check if the user is a manager
    public function isManager($user) : bool
    {
        return $user->role?->name === 'Manager';
    }

    //function to check if the user is a Accountant
    public function isAccountant($user) : bool
    {
        return $user->role?->name === 'Accountant';
    }

    //function to check if HR Manager
    public function isHrManager($user) : bool
    {
        return $user->role?->name === 'HR Manager';
    }

    //restriction of access to the HRM page only the CEO,Admin,HR Manager and Accountant can access the HRM page, other users will be redirected to the dashboard page with an error message
    public function restrictHrmAccess($user) : bool
    {
        if (! $this->isAlwaysAllowed($user) && ! $this->isHrManager($user)) {
           return false;
        }

        return true;
    }

    //restrict the acces of Users page to only CEO,Admin, HR Manager and Manager, other users will be redirected to the dashboard page with an error message
    public function restrictUserAccess($user) : bool
    {
        if (! $this->isAlwaysAllowed($user) && ! $this->isHrManager($user) && ! $this->isManager($user)) {
            return false;   
        }

        return true;
    }

}
