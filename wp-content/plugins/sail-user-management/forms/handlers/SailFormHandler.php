<?php

namespace Sail\Form\Handlers;

use Sail\Api\SailApi;
use Sail\Utils\WebUtils;

abstract class SailFormHandler extends SailApi
{
    private bool $handleLoggedIn;
    private bool $handleLoggedOut;
    private string $handleAction;

    public function __construct(string $handleAction, bool $handleLoggedIn, bool $handleLoggedOut)
    {
        if (!$handleLoggedIn && !$handleLoggedOut) {
            throw new \InvalidArgumentException("Form handler must support either logged in user, not logged in user, or both");
        }
        $this->handleLoggedIn = $handleLoggedIn;
        $this->handleLoggedOut = $handleLoggedOut;
        $this->handleAction = $handleAction;
        $this->registerApi();
    }

    protected function getRoutePrefix(): string
    {
        return 'forms/v1';
    }

    protected function getApiRoute(): string
    {
        return $this->handleAction;
    }

    protected function getMethod(): string
    {
        return 'POST';
    }

    public function permissionCallback(): bool
    {
        if (!$this->handleLoggedIn && is_user_logged_in()) {
            WebUtils::redirect('/error-message?title=Error&message=Please sign out of your account before completing this action.');
            return false;
        } elseif (!$this->handleLoggedOut && !is_user_logged_in()) {
            WebUtils::redirect('/error-message?title=Error&message=Please sign into your account before completing this action.');
            return false;
        }
        return true;
    }
}
