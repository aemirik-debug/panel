<?php

namespace App\Filament\App\Resources\Quizzes;

use App\Filament\App\Resources\Quizzes\Pages;
use App\Filament\Traits\HasPackageModule;
use App\Models\Quiz;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;

class QuizResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'quizzes';
    
    protected static ?string $model = Quiz::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    public static function getNavigationLabel(): string
    {
        return 'Sınav Oluşturma';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Modüller';
    }

    public static function getPluralLabel(): string
    {
        return 'Sınavlar';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sınav Genel Bilgileri')
                    ->schema([
                        TextInput::make('page')
                            ->label('Bağlı Olduğu Sayfa')
                            ->placeholder('Örn: İnsan Kaynakları, Blog, vs.'),

                        TextInput::make('title')
                            ->label('Sınav Adı')
                            ->required()
                            ->maxLength(255),

                        Toggle::make('is_active')
                            ->label('Yayında mı? (Aktif)')
                            ->default(true),
                    ])->columns(3),

                // REPEATER: Sınırsız Soru Ekleme Alanı
                Section::make('Sınav Soruları')
                    ->schema([
                        Repeater::make('questions')
                            ->label('')
                            ->addActionLabel('+ Yeni Soru Ekle')
                            ->schema([
                                TextInput::make('question_text')
                                    ->label('Soru')
                                    ->required()
                                    ->columnSpanFull(),

                                TextInput::make('option_a')->label('A Şıkkı')->required(),
                                TextInput::make('option_b')->label('B Şıkkı')->required(),
                                TextInput::make('option_c')->label('C Şıkkı')->required(),
                                TextInput::make('option_d')->label('D Şıkkı')->required(),

                                Select::make('correct_answer')
                                    ->label('Doğru Cevap')
                                    ->options([
                                        'A' => 'A Şıkkı',
                                        'B' => 'B Şıkkı',
                                        'C' => 'C Şıkkı',
                                        'D' => 'D Şıkkı',
                                    ])
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['question_text'] ?? 'Yeni Soru'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('page')
                    ->label('Sayfa')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('title')
                    ->label('Sınav Adı')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Ekleme Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('page')
                    ->label('İçeriğin Bağlı Olduğu Sayfa')
                    ->options(fn () => Quiz::pluck('page', 'page')->toArray()),
            ])
            ->actions([
                EditAction::make()->label('Düzenle')->button(),
                DeleteAction::make()->label('Sil')->button(),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->label('Seçilileri Sil'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }
}