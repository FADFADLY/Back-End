<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\HtmlString;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('الصورة')
                    ->schema([
                        Placeholder::make('avatar')
                            ->label('الصورة الشخصية')
                            ->content(fn ($record) =>
                            $record->avatar
                                ? new HtmlString('<img src="' . asset('storage/' . $record->avatar) . '" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover;">')
                                : 'لا توجد صورة'
                            )
                            ->columnSpanFull()
                            ->disabled(),
                    ])
                    ->columns(1),

                Section::make('البيانات الأساسية')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('الاسم')
                                ->disabled(),

                            TextInput::make('username')
                                ->label('اسم المستخدم')
                                ->disabled(),

                            TextInput::make('email')
                                ->label('البريد الإلكتروني')
                                ->disabled(),

                            TextInput::make('gender')
                                ->label('النوع')
                                ->disabled(),

                            TextInput::make('age')
                                ->label('العمر')
                                ->disabled(),
                        ]),
                    ]),

                Section::make('السيرة الذاتية')
                    ->schema([
                        Textarea::make('bio')
                            ->label('نبذة عن المستخدم')
                            ->rows(5)
                            ->disabled(),
                    ]),
            ]);
    }
}
