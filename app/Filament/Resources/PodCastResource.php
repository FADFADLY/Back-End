<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PodCastResource\Pages;
use App\Filament\Resources\PodCastResource\RelationManagers;
use App\Models\PodCast;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PodCastResource extends Resource
{
    protected static ?string $model = PodCast::class;

    protected static ?string $navigationIcon = 'heroicon-o-microphone';
    protected static ?string $navigationLabel = 'البودكاست';
    protected static ?string $label = 'بودكاست';
    protected static ?string $pluralLabel = 'البودكاست';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
            'index' => Pages\ListPodCasts::route('/'),
            'create' => Pages\CreatePodCast::route('/create'),
            'edit' => Pages\EditPodCast::route('/{record}/edit'),
        ];
    }
}
