<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientRequestResource\Pages;
use App\Models\ClientRequest;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class ClientRequestResource extends Resource
{
    protected static ?string $model = ClientRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Gestión de Usuarios';

    protected static ?string $modelLabel = 'Solicitud de Cliente';

    protected static ?string $pluralModelLabel = 'Solicitudes de Cliente';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }


    public static function getPermissionIdentifier(): string
    {
        return 'client_requests';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('view_any_client_requests');
    }


    public static function canCreate(): bool
    {
        return auth()->user()->can('create_client_requests');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update_client_requests');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete_client_requests');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view_any_client_requests');
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->can('view_client_requests');
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->label('Usuario'),
                Forms\Components\Textarea::make('message')
                    ->required()
                    ->label('Mensaje')
                    ->maxLength(65535),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobada',
                        'rejected' => 'Rechazada',
                    ])
                    ->required()
                    ->label('Estado'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('message')
                    ->label('Mensaje')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobada',
                        'rejected' => 'Rechazada',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de solicitud')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobada',
                        'rejected' => 'Rechazada',
                    ])
                    ->label('Estado'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make('approve')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Model $record) => $record->status === 'pending')
                    ->action(function (ClientRequest $record) {
                        $record->update(['status' => 'approved']);
                        $user = $record->user;
                        $user->update(['role' => 'client']);
                        $user->syncRoles(['client']);

                        Notification::make()
                            ->title('Solicitud aprobada')
                            ->success()
                            ->body('El usuario ha sido promovido a cliente correctamente.')
                            ->send();

                        return redirect(request()->header('Referer'));
                    }),
                Action::make('reject')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Model $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (ClientRequest $record) {
                        $record->update(['status' => 'rejected']);
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientRequests::route('/'),
            'create' => Pages\CreateClientRequest::route('/create'),
            'edit' => Pages\EditClientRequest::route('/{record}/edit'),
        ];
    }
}
