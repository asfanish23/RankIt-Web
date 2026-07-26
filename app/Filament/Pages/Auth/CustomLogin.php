<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class CustomLogin extends BaseLogin
{
    protected static string $view = 'filament.pages.auth.login';
    
    protected static string $layout = 'filament-panels::components.layout.base';

    protected function getEmailFormComponent(): \Filament\Forms\Components\Component
    {
        return parent::getEmailFormComponent()
            ->label('Email Address')
            ->placeholder('Enter your email address')
            ->prefixIcon('heroicon-m-envelope');
    }

    protected function getPasswordFormComponent(): \Filament\Forms\Components\Component
    {
        return parent::getPasswordFormComponent()
            ->label('Password')
            ->placeholder('Enter your password')
            ->prefixIcon('heroicon-m-lock-closed');
    }
}
