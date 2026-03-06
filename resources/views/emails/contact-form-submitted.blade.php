<h2>Yeni İletişim Formu Mesajı</h2>

<p><strong>Kaynak:</strong> {{ $siteLabel }}</p>
<p><strong>Form:</strong> {{ $contact->form_name ?? 'İletişim Formu' }}</p>
<p><strong>Ad Soyad:</strong> {{ $contact->name }}</p>
<p><strong>E-posta:</strong> {{ $contact->email }}</p>
<p><strong>Telefon:</strong> {{ $contact->phone ?: '-' }}</p>
<p><strong>Konu:</strong> {{ $contact->subject ?: '-' }}</p>
<p><strong>Mesaj:</strong></p>
<p>{{ $contact->message }}</p>

<p style="margin-top: 20px; color: #666;">Bu e-posta ajans-cms iletişim formu modulu tarafindan otomatik gonderildi.</p>
