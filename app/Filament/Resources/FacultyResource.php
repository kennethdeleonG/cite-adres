<?php

namespace App\Filament\Resources;

use App\Domain\Faculty\Models\Faculty;
use App\Filament\Resources\FacultyResource\Pages;
use App\Filament\Resources\FacultyResource\RelationManagers;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FacultyResource extends Resource
{
    protected static ?string $model = Faculty::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\FileUpload::make('image')
                        ->label('Profile Picture')
                        ->image(),
                    Forms\Components\Group::make([
                        Forms\Components\TextInput::make('first_name')
                            ->label('First Name')
                            ->maxLength(100)
                            ->required(),
                        Forms\Components\TextInput::make('last_name')
                            ->label('Last Name')
                            ->maxLength(100)
                            ->required(),
                    ])->columns(2),
                    Forms\Components\TextInput::make('address')
                        ->label('Address')
                        ->maxLength(100)
                        ->required(),
                    Forms\Components\Group::make([
                        Forms\Components\TextInput::make('mobile')
                            ->label('Phone Number')
                            ->maxLength(11),
                        Forms\Components\Select::make('gender')
                            ->required()
                            ->label('Gender')
                            ->options([
                                'Male' => 'Male',
                                'Female' => 'Female',
                            ]),
                    ])->columns(2),
                    Forms\Components\TextInput::make('designation')
                        ->label('Designation')
                        ->maxLength(100),
                ]),

                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('email')
                        ->required()
                        ->email()
                        ->label('Email')
                        ->maxLength(100),
                    Forms\Components\Group::make([
                        Forms\Components\TextInput::make('password')
                            ->required()
                            ->password()
                            ->label('Password')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('confirm_password')
                            ->required()
                            ->password()
                            ->label('Confirm Password')
                            ->maxLength(100),
                    ])->columns(2),


                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListFaculties::route('/'),
            'create' => Pages\CreateFaculty::route('/create'),
            'edit' => Pages\EditFaculty::route('/{record}/edit'),
        ];
    }
}
