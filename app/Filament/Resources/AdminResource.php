<?php

namespace App\Filament\Resources;

use App\Enums\RolesEnum;
use App\Enums\PermissionsEnum;
use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdminResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $modelLabel = 'Admin';
    protected static ?string $pluralModelLabel = 'Admins';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->autocomplete(false),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(User::class, 'email', fn($record) => $record)
                    ->maxLength(255)
                    ->autocomplete(false),

                Forms\Components\Hidden::make('show_password')
                    ->default(false),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn($state) => filled($state))
                    ->required()
                    ->autocomplete('new-password'),

                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->required()
                    ->autocomplete('new-password')
                    ->same('password'),

            ])
            ->statePath('data')
            ->operation('create');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Email Status')
                    ->badge()
                    ->state(function ($record) {
                        return $record->email_verified_at
                            ? 'Verified'
                            : 'Not Verified';
                    })
                    ->color(function ($record) {
                        return $record->email_verified_at ? 'success' : 'danger';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('F j, Y h:i A')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime('F j, Y h:i A')->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where(function ($query) {
                $query->role(RolesEnum::ADMIN->value)
                    ->orWhere('id', request()->route('record'));
            });
    }

    public static function canViewAny(): bool
    {
        return Auth::check() &&
        Gate::allows(PermissionsEnum::Manage_Admins->value);
    }
}
