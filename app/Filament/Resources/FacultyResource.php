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
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;
use Illuminate\Support\Str;

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
                        ->formatStateUsing(function ($record) {
                            return $record?->getMedia('image')
                                ->mapWithKeys(fn (Media $file) => [$file->uuid => $file->uuid])
                                ->toArray() ?? [];
                        })
                        ->image()
                        ->beforeStateDehydrated(null)
                        ->dehydrateStateUsing(fn (?array $state) => array_values($state ?? [])[0] ?? null)
                        ->getUploadedFileUrlUsing(static function (Forms\Components\FileUpload $component, string $file): ?string {
                            $mediaClass = config('media-library.media_model', Media::class);

                            /** @var ?Media $media */
                            $media = $mediaClass::findByUuid($file);

                            if ($component->getVisibility() === 'private') {
                                try {
                                    return $media?->getTemporaryUrl(now()->addMinutes(5));
                                } catch (Throwable $exception) {
                                    // This driver does not support creating temporary URLs.
                                }
                            }

                            return $media?->getUrl();
                        }),
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
                            ->minLength(11)
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
                            ->maxLength(20)
                            ->minLength(8)
                            ->confirmed(),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->required()
                            ->password()
                            ->label('Confirm Password')
                            ->maxLength(20)
                            ->minLength(8)
                            ->dehydrated(false),
                    ])->columns(2),


                ])->hiddenOn('edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('Name'))
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('first_name', $direction);
                    })
                    ->formatStateUsing(function ($record) {
                        return Str::limit($record->first_name . ' ' . $record->last_name, 50);
                    })
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('designation')
                    ->label(trans('Designation'))
                    ->formatStateUsing(function ($record) {
                        return $record->designation ?? 'N/A';
                    })
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaculties::route('/'),
            'create' => Pages\CreateFaculty::route('/create'),
            'edit' => Pages\EditFaculty::route('/{record}/edit'),
        ];
    }
}
