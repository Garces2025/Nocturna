<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Administración';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Usuarios';

    public static function getNavigationLabel(): string
    {
        return 'Usuarios';
    }

    public static function getNavigationTooltip(): string
    {
        return 'Gestionar usuarios del sistema';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información Personal')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Nombre'),
                        TextInput::make('last_name')
                            ->required()
                            ->label('Apellido'),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Correo Electrónico'),
                        TextInput::make('password')
                            ->password()
                            ->required(fn ($record) => ! $record)
                            ->minLength(8)
                            ->label('Contraseña'),
                        DatePicker::make('birthday')
                            ->required()
                            ->label('Fecha de Nacimiento'),
                        Select::make('gender')
                            ->options([
                                'M' => 'Masculino',
                                'F' => 'Femenino',
                                'O' => 'Otro'
                            ])
                            ->required()
                            ->label('Género'),
                        Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->label('Roles'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('last_name')
                    ->label('Apellido')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge(),
                    ToggleColumn::make('status')
                    ->label('Estado')
                    ->afterStateUpdated(function ($record, $state) {
                        $status = $state ? 'activado' : 'desactivado';
                        $message = $state
                            ? "El usuario ahora puede acceder al panel"
                            : "El usuario ya no podrá acceder al panel";

                        Notification::make()
                            ->title("Usuario {$status}")
                            ->body("El usuario {$record->name} ha sido {$status}. {$message}")
                            ->color('primary')
                            ->icon($state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                            ->duration(4000)
                            ->send();
                    })
                    ->onColor('primary')
                    ->offColor('danger')
                    ->tooltip('Activar/Desactivar acceso al panel'),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Roles'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('Seccion_usuarios');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('Crear_usuario');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('Editar_usuario');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('Eliminar_usuario');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('Seccion_usuarios1');
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->can('Listar_usuarios');
    }

}
