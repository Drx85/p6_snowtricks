<?php


namespace App\Security\Voter;


use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
	const USER_EDIT = 'user_edit';
	
	protected function supports(string $attribute, $subject): bool
	{
		if (!in_array($attribute, [self::USER_EDIT])) {
			return false;
		}
		if (!$subject instanceof User) {
			return false;
		}
		return true;
	}
	
	protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
	{
		$userSession = $token->getUser();
		if (!$userSession instanceof UserInterface) {
			return false;
		}
		$user = $subject;
		return $this->canEdit($user, $userSession);
	}
	
	private function canEdit(User $user, UserInterface $userSession)
	{
		return $user->getUserIdentifier() === $userSession->getUserIdentifier();
	}
}