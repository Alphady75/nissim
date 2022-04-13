<?php

namespace App\Security\Voter;

use App\Entity\Projet;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ProjetsVoter extends Voter
{
    const PROJET_EDIT = "projet_edit";
    const PROJET_DELETE = "projet_delete";
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $projet): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::PROJET_EDIT, self::PROJET_DELETE])
            && $projet instanceof Projet;
    }

    protected function voteOnAttribute(string $attribute, $propertie, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        //On autorise à un administrateur de modifier un projet
        //if ($this->security->isGranted("ROLE_ADMIN")) return true;

        //Verifie si un projet a un auteur
        if (null === $propertie->getUser()) return false;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::PROJET_EDIT:
                // logic to determine if the user can EDIT
                return $this->canEdit($propertie, $user);
                break;
            case self::PROJET_DELETE:
                // logic to determine if the user can VIEW
                return $this->canDelete($propertie, $user);
                break;
        }

        return false;
    }

    private function canEdit(Projet $propertie, User $user){
        return $user === $propertie->getUser();
    }

    private function canDelete(Projet $propertie, User $user){
        if($this->security->isGranted("ROLE_ADMIN")) return true;
        return $user === $propertie->getUser();
    }
}
