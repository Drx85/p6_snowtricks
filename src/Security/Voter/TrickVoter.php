<?php


namespace App\Security\Voter;


use App\Entity\Trick;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class TrickVoter extends Voter
{
	const TRICK_EDIT = 'trick_edit';
	
	private Security $security;
	
	public function __construct(Security $security)
	{
		$this->security = $security;
	}
	
	protected function supports(string $attribute, $subject): bool
	{
		if (!in_array($attribute, [self::TRICK_EDIT])) return false;
		if (!$subject instanceof Trick) return false;
		return true;
	}
	
	protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
	{
		$user = $token->getUser();
		if (!$user instanceof UserInterface) return false;
		
		if ($this->security->isGranted('ROLE_ADMIN')) return true;
		
		if (!$user->isVerified()) return false;

		$trick = $subject;
		return $this->canEdit($trick, $user);
	}
	
	private function canEdit(Trick $trick, UserInterface $user): bool
	{
		return $user === $trick->getUser();
	}
}