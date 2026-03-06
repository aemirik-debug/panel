@php
    $whatsappNumber = $settings->whatsapp_number ?? null;
@endphp

@if($whatsappNumber)
<style>
.whatsapp-float {
    position: fixed;
    width: 60px;
    height: 60px;
    bottom: 80px;
    right: 30px;
    background-color: #25d366;
    color: #FFF;
    border-radius: 50px;
    text-align: center;
    font-size: 30px;
    box-shadow: 2px 2px 15px rgba(37, 211, 102, 0.4);
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.whatsapp-float:hover {
    background-color: #128c7e;
    box-shadow: 2px 2px 20px rgba(37, 211, 102, 0.6);
    transform: scale(1.05);
    color: #FFF;
}

.whatsapp-float i {
    margin-top: 2px;
}

@media screen and (max-width: 768px) {
    .whatsapp-float {
        width: 50px;
        height: 50px;
        bottom: 70px;
        right: 20px;
        font-size: 24px;
    }
}
</style>

<a href="https://wa.me/{{ $whatsappNumber }}" class="whatsapp-float" target="_blank" rel="noopener noreferrer" title="WhatsApp ile İletişime Geç">
    <i class="bi bi-whatsapp"></i>
</a>
@endif
