<?php


namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

abstract class Voter implements VoterInterface
{
	abstract protected function supports(string $attribute, $subject);
	abstract protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token);
}