<?php

namespace App\Filament\App\Pages;

use App\Models\SupportTicket;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class SupportPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Destek';
    protected static ?int $navigationSort = 200;
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Destek Talebi Gönder')
                    ->description('Yaşadığınız sorunları veya geri bildiriminizi detaylı bir şekilde anlatın.')
                    ->schema([
                        Select::make('category')
                            ->label('Sorun Kategorisi')
                            ->options([
                                'blog' => 'Blog / Haberler',
                                'products' => 'Ürünler',
                                'services' => 'Hizmetler',
                                'categories' => 'Kategoriler',
                                'gallery' => 'Galeri',
                                'slider' => 'Slider',
                                'menu' => 'Menüler',
                                'settings' => 'Ayarlar',
                                'other' => 'Diğer',
                            ])
                            ->required()
                            ->native(false),

                        RichEditor::make('message')
                            ->label('Sorun Açıklaması')
                            ->helperText('Yaşadığınız sorunu detaylı bir şekilde anlatın.')
                            ->required()
                            ->columnSpanFull(),

                        FileUpload::make('screenshot')
                            ->label('Ekran Görüntüsü (İsteğe Bağlı)')
                            ->helperText('Sorunun ekran görüntüsünü yükleyebilirsiniz.')
                            ->image()
                            ->maxSize(5120) // 5MB
                            ->columnSpanFull(),
                    ])
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        // Başlığı otomatik oluştur
        $title = SupportTicket::generateTitle($data['category'], $data['message']);

        // Destek talebini oluştur
        SupportTicket::create([
            'user_id' => Auth::id(),
            'category' => $data['category'],
            'title' => $title,
            'message' => $data['message'],
            'screenshot' => $data['screenshot'] ?? null,
            'status' => 'yeni',
        ]);

        $this->form->fill();

        Notification::make()
            ->title('Destek Talebiniz Gönderildi')
            ->body('Destek talebiniz başarıyla alınmıştır. Kısa sürede yanıtlanacaktır.')
            ->success()
            ->send();
    }
}
