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
                Forms\Components\Section::make(__('messages.basic_info'))
                    ->schema([
                        Forms\Components\TextInput::make(__('messages.name'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make(__('messages.description'))
                            ->required()
                            ->maxLength(1000)
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label(__('messages.restaurants.restaurant_image'))
                            ->image()
                            ->required()
                            ->directory('restaurants/images')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(5120) // 5MB
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('menu_path')
                            ->label(__('messages.restaurants.restaurant_menu'))
                            ->acceptedFileTypes(['application/pdf'])
                            ->required()
                            ->directory('restaurants/menus')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(10240) // 10MB
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make(__('messages.location'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make(__('messages.latitude'))
                                    ->numeric()
                                    ->required()
                                    ->step('any')
                                    ->rules(['between:-90,90'])
                                    ->helperText(__('messages.latitude_helper')),

                                Forms\Components\TextInput::make(__('messages.longitude'))
                                    ->numeric()
                                    ->required()
                                    ->step('any')
                                    ->rules(['between:-180,180'])
                                    ->helperText(__('messages.longitude_helper')),
                            ]),
                    ]),

                Forms\Components\Section::make(__('messages.key_words'))
                    ->schema([
                        Forms\Components\Select::make(__('messages.key_words'))
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpanFull()
                            ->helperText(__('messages.key_words_helper')),
                    ]),

                // Приховане поле для super_admin
                Forms\Components\Select::make('user_id')
                    ->label(__('messages.restaurants.owner'))
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
                    ->label(__('messages.image'))
                    ->circular()
                    ->size(60),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('messages.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label(__('messages.description'))
                    ->limit(50)
                    ->tooltip(function (Model $record): string {
                        return $record->description;
                    }),

                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('messages.restaurants.owner'))
                    ->visible(fn () => auth()->user()->hasRole('super_admin'))
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tags.name')
                    ->label(__('messages.key_words'))
                    ->separator(',')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('messages.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make(__('messages.key_words'))
                    ->label(__('messages.key_words'))
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
