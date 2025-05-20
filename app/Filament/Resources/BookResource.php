<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'الكتب';
    protected static ?string $pluralLabel = 'الكتب';
    protected static ?string $modelLabel = 'كتاب';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        // العمود الأيمن: كل الإنبتات
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\FileUpload::make('image_url')
                                    ->label('صورة الغلاف')
                                    ->imagePreviewHeight('200')
                                    ->image()
                                    ->directory('books_covers')
                                    ->visibility('public')
                                    ->required(),

                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->label('عنوان الكتاب')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('author')
                                    ->required()
                                    ->label('اسم المؤلف')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('genre')
                                    ->required()
                                    ->label('نوع الكتاب')
                                    ->maxLength(255),

                            ]),

                        // العمود الشمال: الوصف فقط
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('publisher')
                                    ->required()
                                    ->label('الناشر')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('publication_date')
                                    ->required()
                                    ->label('تاريخ النشر')
                                    ->type('date'),

                                Forms\Components\Textarea::make('description')
                                    ->label('وصف الكتاب')
                                    ->rows(10) // عشان يكون باين وكبير
                                    ->autosize()
                            ]),
                    ])
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('صورة الغلاف')
                    ->size(70),
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان الكتاب')
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->label('اسم المؤلف')
                    ->searchable(),
                Tables\Columns\TextColumn::make('genre')
                    ->label('نوع الكتاب'),
                Tables\Columns\TextColumn::make('publisher')
                    ->label('الناشر'),
                Tables\Columns\TextColumn::make('publication_date')
                    ->label('تاريخ النشر')
                    ->date(),
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
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
