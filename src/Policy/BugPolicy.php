<?php

namespace App\Policy;

use App\Model\Entity\Bug;
use Authorization\IdentityInterface;

/**
 * Bug policy
 */
class BugPolicy
{
    /**
     * Check if $user can create Bug
     *
     * @param IdentityInterface $user The user.
     * @param Bug               $bug
     *
     * @return bool
     */
    public function canCreate(IdentityInterface $user, Bug $bug): bool
    {
        return true;
    }

    /**
     * Check if $user can update Bug
     *
     * @param IdentityInterface $user The user.
     * @param Bug               $bug
     *
     * @return bool
     */
    public function canUpdate(IdentityInterface $user, Bug $bug): bool
    {
        return $user->id == $bug->author_id || $user->id == $bug->assigned_id;
    }

    /**
     * Check if $user can delete Bug
     *
     * @param IdentityInterface $user The user.
     * @param Bug               $bug
     *
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Bug $bug): bool
    {
        return $user->id == $bug->author_id;
    }

    /**
     * Check if $user can view Bug
     *
     * @param IdentityInterface $user The user.
     * @param Bug               $bug
     *
     * @return bool
     */
    public function canView(IdentityInterface $user, Bug $bug): bool
    {
        return true;
    }
}
