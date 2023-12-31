<?php

namespace App\Filament\Resources;

use App\Domain\Asset\Models\Asset;
use App\Filament\Resources\AssetResource\Pages;
use App\Filament\Resources\AssetResource\RelationManagers;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $breadcrumb = 'Document';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'folder.name',];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('name')
                        ->unique(ignoreRecord: true)
                        ->required(),
                    Forms\Components\FileUpload::make('file')
                        ->enableDownload()
                        ->required()
                        ->disk(config('filesystems.default'))
                        ->directory(function ($livewire) {
                            if (!is_null($livewire->ownerRecord)) {
                                return $livewire->ownerRecord->path;
                            }

                            return 'assets';
                        })
                        ->visibility('public')
                        ->afterStateUpdated(function (Closure $set, $state) {
                            if ($state) {

                                $units = ['B', 'KB', 'MB', 'GB', 'TB'];

                                $bytes = $state->getSize();

                                $bytes = max($bytes, 0);
                                $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                                $pow = min($pow, count($units) - 1);

                                $bytes /= pow(1024, $pow);

                                $size = round($bytes, 2) . ' ' . $units[$pow];

                                $set('size', $state->getSize());
                                $set('file_type', $state->getClientOriginalExtension());

                                $set('technical_information', [
                                    'File Size' => $size,
                                    'File Type' => Str::upper($state->getClientOriginalExtension()),
                                ]);
                            }
                            return $state;
                        }),
                    Forms\Components\KeyValue::make('technical_information')
                        ->keyLabel('Property name')
                        ->valueLabel('Property value')
                        ->disableAddingRows()
                        ->disableDeletingRows()
                        ->disableEditingKeys()
                        ->disableEditingValues()
                        ->formatStateUsing(fn ($record) => $record?->technical_information)
                        ->hidden(fn (Closure $get) => $get('file') ? false : true),
                    Forms\Components\Hidden::make('size'),
                    Forms\Components\Hidden::make('file_type'),
                    Forms\Components\Toggle::make('is_private')->label('Private')->default(false),
                ]),

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/file/create/{ownerRecord?}'),
            'edit' => Pages\EditAsset::route('/file/{record}/edit/{ownerRecord?}'),
        ];
    }
}
