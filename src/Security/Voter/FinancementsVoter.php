<?php

namespace App\Security\Voter;

use App\Entity\Financement;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class FinancementsVoter extends Voter
{
    public const FINANCER = 'app_auteur_du_financement';
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::FINANCER])
            && $subject instanceof \App\Entity\Financements;
    }

        protected function voteOnAttribute(string $attribute, $financement, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        //On autorise à un administrateur de modifier un projet
        //if ($this->security->isGranted("ROLE_ADMIN")) return true;

        //Verifie si un projet a un auteur
        if (null === $financement->getUser()) return false;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::FINANCER:
                // logic to determine if the user can EDIT
                return $this->canView($financement, $user);
                break;
        }

        return false;
    }

    private function canView(Financement $financement, User $user){
        return $user === $financement->getUser();
    }
}
