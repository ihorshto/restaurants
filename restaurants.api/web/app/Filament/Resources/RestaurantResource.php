<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RestaurantResource\Pages;
use App\Models\Restaurant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RestaurantResource extends Resource
{
    protected static ?string $model = Restaurant::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationLabel = 'Restaurants';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->maxLength(1000)
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Restaurant Image')
                            ->image()
                            ->required()
                            ->directory('restaurants/images')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(5120) // 5MB
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('menu_path')
                            ->label('Menu PDF')
                            ->acceptedFileTypes(['application/pdf'])
                            ->required()
                            ->directory('restaurants/menus')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(10240) // 10MB
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Location')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->numeric()
                                    ->required()
                                    ->step('any')
                                    ->rules(['between:-90,90'])
                                    ->helperText('Enter latitude between -90 and 90'),

                                Forms\Components\TextInput::make('longitude')
                                    ->numeric()
                                    ->required()
                                    ->step('any')
                                    ->rules(['between:-180,180'])
                                    ->helperText('Enter longitude between -180 and 180'),
                            ]),
                    ]),

                Forms\Components\Section::make('Tags')
                    ->schema([
                        Forms\Components\Select::make('tags')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpanFull()
                            ->helperText('Select tags for filtering'),
                    ]),

                // Приховане поле для super_admin
                Forms\Components\Select::make('user_id')
                    ->label('Restaurant Owner')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn () => auth()->user()->hasRole('super_admin'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->circular()
                    ->size(60),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(function (Model $record): string {
                        return $record->description;
                    }),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Owner')
                    ->visible(fn () => auth()->user()->hasRole('super_admin'))
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tags.name')
                    ->label('Tags')
                    ->separator(',')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return parent::getEloquentQuery();
        }

        // restaurant_admin бачить тільки свої ресторани
        return parent::getEloquentQuery()->where('user_id', $user->id);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasAnyRole(['super_admin', 'restaurant_admin']);
    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();

        return $user->hasRole('super_admin') ||
            ($user->hasRole('restaurant_admin') && $record->user_id === $user->id);
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();

        return $user->hasRole('super_admin') ||
            ($user->hasRole('restaurant_admin') && $record->user_id === $user->id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRestaurants::route('/'),
            'create' => Pages\CreateRestaurant::route('/create'),
            'view' => Pages\ViewRestaurant::route('/{record}'),
            'edit' => Pages\EditRestaurant::route('/{record}/edit'),
        ];
    }
}
