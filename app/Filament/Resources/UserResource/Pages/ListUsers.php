<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use App\Models\Invitation;
use App\Mail\TeamInvitationMail;
use Illuminate\Support\Facades\Mail;
use App\Filament\Resources\UserResource;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('inviteUser')
                ->form([
                    TextInput::make('email')
                        ->email()
                        ->required()
                ])
                ->action(function ($data) {
                    $invitation = Invitation::create(['email' => $data['email']]);

                    // @todo Add email sending here
                    Mail::to($invitation->email)->send(new TeamInvitationMail($invitation));

                    Notification::make('invitedSuccess')
                        ->body('User invited successfully!')
                        ->success()->send();
                }),
        ];
    }
}
