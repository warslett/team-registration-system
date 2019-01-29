<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\PasswordResetLimitReachedException;
use App\Exception\ResetPasswordTokenInvalidException;
use App\Repository\UserRepository;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Twig\Environment;

class ResetPasswordTokenService
{

    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    const PASSWORD_RESET_LIMIT = 4;
    const PASSWORD_RESET_LIMIT_LIFETIME = "+24 hours";
    /**
     * @var string
     */
    private $sender;

    public function __construct(
        UserRepository $repository,
        TokenGeneratorInterface $tokenGenerator,
        \Swift_Mailer $mailer,
        Environment $twig,
        string $sender
    ) {
        $this->repository = $repository;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->sender = $sender;
    }

    /**
     * @param string $resetPasswordToken
     * @throws ResetPasswordTokenInvalidException
     * @return User
     */
    public function resolveUserFromToken(string $resetPasswordToken): User
    {
        $user = $this->repository->findOneByResetPasswordToken(md5($resetPasswordToken));

        if (is_null($user)) {
            throw new ResetPasswordTokenInvalidException("No user with that token");
        }

        if (!is_null($user->getResetPasswordTokenExpiry()) && $user->getResetPasswordTokenExpiry() < new \DateTime()) {
            throw new ResetPasswordTokenInvalidException("That token has expired");
        }

        return $user;
    }

    public function inValidateResetPasswordToken(User $user)
    {
        $user->setResetPasswordToken(null);
        $user->setResetPasswordTokenExpiry(null);
    }

    /**
     * @param User $user
     * @param \DateTime|null $expiry
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws PasswordResetLimitReachedException
     * @throws \RuntimeException
     */
    public function triggerResetPassword(User $user, ?\DateTime $expiry = null)
    {
        if ($user->getRefreshPasswordCountDate() === null || $user->getRefreshPasswordCountDate() < new \DateTime('now')) {

            $user->setResetPasswordCount(0);
            $user->setRefreshPasswordCountDate(new \DateTime(self::PASSWORD_RESET_LIMIT_LIFETIME));

        } elseif ($user->getResetPasswordCount() >= self::PASSWORD_RESET_LIMIT) {

            throw new PasswordResetLimitReachedException(
                sprintf(
                    "The password for %s has been reset too many times. You must wait until %s to try again",
                    $user->getEmail(),
                    $user->getRefreshPasswordCountDate()->format('jS \of F H:i')
                )
            );
        }

        for ($x = 1; $x <= 10000; $x++) {
            $resetPasswordToken = $this->tokenGenerator->generateToken();
            if ($this->repository->findOneByResetPasswordToken($resetPasswordToken) === null) {

                $user->setResetPasswordToken(md5($resetPasswordToken));
                $user->setResetPasswordTokenExpiry($expiry);

                $message = (new \Swift_Message())
                    ->setSubject("Please provide a password for your Job Board account")
                    ->setTo($user->getEmail())
                    ->setFrom($this->sender)
                    ->setBody(
                        $this->twig->render(
                            'emails/reset-password.html.twig',
                            ['token' => $resetPasswordToken]
                        ),
                        'text/html'
                    )
                ;

                $this->mailer->send($message);
                $user->incrementResetPasswordCount();
                return;
            }
        }

        throw new \RuntimeException("Failed to generate a reset password token");
    }
}
