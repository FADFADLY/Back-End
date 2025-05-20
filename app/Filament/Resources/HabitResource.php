<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HabitResource\Pages;
use App\Filament\Resources\HabitResource\RelationManagers;
use App\Models\Habit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HabitResource extends Resource
{
    protected static ?string $model = Habit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'العادات';
    protected static ?string $pluralLabel = 'العادات';
    protected static ?string $modelLabel = 'عادة';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('اسم العادة')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('icon')
                    ->required()
                    ->label('أيقونة العادة')
                    ->image()
                    ->directory('habit_icons')
                    ->visibility('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('icon')
                    ->label('أيقونة العادة')
                    ->size(70),
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم العادة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListHabits::route('/'),
            'create' => Pages\CreateHabit::route('/create'),
            'edit' => Pages\EditHabit::route('/{record}/edit'),
        ];
    }
}
