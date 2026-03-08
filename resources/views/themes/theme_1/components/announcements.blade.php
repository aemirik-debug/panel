<!-- Duyuru Component -->
<div id="announcement-container"></div>

<script>
    // Duyuru kontrol anahtarları
    const ANNOUNCEMENT_DONT_SHOW_KEY = 'announcement_dont_show_';
    const ANNOUNCEMENT_SESSION_KEY = 'announcement_session_';
    
    document.addEventListener('DOMContentLoaded', function() {
        fetchAnnouncements();
    });

    async function fetchAnnouncements() {
        try {
            const response = await fetch('/api/announcements');
            if (!response.ok) {
                throw new Error(`API hata kodu: ${response.status}`);
            }

            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                throw new Error('API JSON yerine HTML döndürdü (route/domain kontrol edilmeli).');
            }

            const result = await response.json();

            if (result.success && result.data && result.data.length > 0) {
                // İlk aktif duyuruyu göster
                const announcement = result.data[0];
                showAnnouncement(announcement);
            }
        } catch (error) {
            console.error('Duyuru yükleme hatası:', error);
        }
    }

    function showAnnouncement(announcement) {
        const dontShowKey = ANNOUNCEMENT_DONT_SHOW_KEY + announcement.id;
        const sessionKey = ANNOUNCEMENT_SESSION_KEY + announcement.id;
        
        // Kullanıcı "Tekrar Gösterme" dediyse kalıcı olarak gösterme
        if (localStorage.getItem(dontShowKey) === 'true') {
            return;
        }
        
        // Bu oturumda zaten gösterildiyse gösterme
        if (sessionStorage.getItem(sessionKey) === 'true') {
            return;
        }

        // Bu oturumda gösterildi olarak işaretle
        sessionStorage.setItem(sessionKey, 'true');

        renderAnnouncement(announcement);
    }

    function renderAnnouncement(announcement) {
        const container = document.getElementById('announcement-container');
        
        if (!container) return;

        const colorClass = getColorClass(announcement.color_scheme);
        const html = `
            <!-- Modal Overlay -->
            <div class="announcement-overlay" id="announcement-overlay-${announcement.id}" onclick="closeAnnouncementModal(${announcement.id})">
            </div>
            
            <!-- Modal İçeriği -->
            <div class="announcement-modal-wrapper" id="announcement-${announcement.id}" onclick="event.stopPropagation()">
                <div class="announcement-modal">
                    <button class="announcement-modal-close" onclick="closeAnnouncementModal(${announcement.id})">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    
                    ${announcement.image ? `
                        <div class="announcement-image">
                            <img src="${announcement.image}" alt="${announcement.title}" />
                        </div>
                    ` : ''}
                    
                    <div class="announcement-content">
                        <h3>${announcement.title}</h3>
                        <div class="announcement-text">
                            ${announcement.content}
                        </div>
                        
                        ${announcement.button_text && announcement.button_url ? `
                            <a href="${announcement.button_url}" class="announcement-btn btn-primary" target="_blank">
                                ${announcement.button_text}
                            </a>
                        ` : ''}
                    </div>
                    
                    <div class="announcement-actions">
                        <button class="announcement-action-btn announcement-btn-secondary" onclick="closeAnnouncementModal(${announcement.id})">
                            <i class="bi bi-x-circle"></i> Kapat
                        </button>
                        <button class="announcement-action-btn announcement-btn-primary" onclick="dontShowAgain(${announcement.id})">
                            <i class="bi bi-check-circle"></i> Tekrar Gösterme
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        container.innerHTML = html;
        
        // Görüntüleme kaydı gönder
        fetch(`/api/announcements/${announcement.id}/view`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        }).catch(error => console.error('Görüntüleme kaydı hatası:', error));

        // Animasyon
        setTimeout(() => {
            const modal = document.getElementById(`announcement-${announcement.id}`);
            const overlay = document.getElementById(`announcement-overlay-${announcement.id}`);
            if (modal) modal.classList.add('show');
            if (overlay) overlay.classList.add('show');
        }, 10);
    }

    function closeAnnouncementModal(id) {
        const modal = document.getElementById(`announcement-${id}`);
        const overlay = document.getElementById(`announcement-overlay-${id}`);
        
        if (modal) modal.classList.remove('show');
        if (overlay) overlay.classList.remove('show');
        
        setTimeout(() => {
            if (modal) modal.remove();
            if (overlay) overlay.remove();
        }, 300);
    }

    function dontShowAgain(id) {
        // localStorage'a kalıcı olarak kaydet
        const dontShowKey = ANNOUNCEMENT_DONT_SHOW_KEY + id;
        localStorage.setItem(dontShowKey, 'true');
        
        // Modal'ı kapat
        closeAnnouncementModal(id);
    }

    function getColorClass(scheme) {
        const colorMap = {
            'primary': 'primary',
            'success': 'success',
            'warning': 'warning',
            'danger': 'danger',
            'info': 'info'
        };
        return colorMap[scheme] || 'primary';
    }
</script>

<style>
    /* Container */
    #announcement-container {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
        pointer-events: none;
        overflow-y: auto;
    }

    /* Overlay - Arka plan karartma */
    .announcement-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: auto;
        z-index: 9998;
    }

    .announcement-overlay.show {
        opacity: 1;
    }

    /* Modal Wrapper */
    .announcement-modal-wrapper {
        position: relative;
        margin: 40px auto;
        transform: scale(0.98);
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: auto;
        z-index: 9999;
        max-width: 520px;
        width: 90%;
        overflow: visible;
    }

    .announcement-modal-wrapper.show {
        transform: scale(1);
        opacity: 1;
    }

    /* Modal İçeriği */
    .announcement-modal {
        background: white;
        border-radius: 12px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        overflow: visible;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .announcement-image {
        width: 100%;
        overflow: hidden;
        flex-shrink: 0;
        background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
    }

    .announcement-image img {
        width: 100%;
        height: auto;
        display: block;
    }

    .announcement-content {
        padding: 20px;
        flex: 1;
    }

    .announcement-content h3 {
        margin: 0 0 15px 0;
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }

    .announcement-text {
        margin: 15px 0;
        font-size: 16px;
        line-height: 1.6;
        color: #666;
    }

    .announcement-btn {
        display: inline-block;
        margin-top: 15px;
        padding: 12px 24px;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        font-size: 15px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .announcement-btn:hover {
        background: #5568d3;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }

    /* Modal Kapat Butonu */
    .announcement-modal-close {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 18px;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .announcement-modal-close:hover {
        background: rgba(0, 0, 0, 0.8);
        transform: rotate(90deg);
    }

    /* Modal Alt Aksiyonlar */
    .announcement-actions {
        display: flex;
        gap: 12px;
        padding: 20px 30px;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        flex-shrink: 0;
    }

    .announcement-action-btn {
        flex: 1;
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .announcement-btn-secondary {
        background: #e9ecef;
        color: #495057;
    }

    .announcement-btn-secondary:hover {
        background: #dee2e6;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .announcement-btn-primary {
        background: #667eea;
        color: white;
    }

    .announcement-btn-primary:hover {
        background: #5568d3;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .announcement-modal-wrapper {
            width: 95%;
            max-width: 500px;
        }

        .announcement-content {
            padding: 20px;
        }

        .announcement-content h3 {
            font-size: 20px;
        }

        .announcement-text {
            font-size: 14px;
        }

        .announcement-actions {
            flex-direction: column;
            padding: 15px 20px;
        }

        .announcement-action-btn {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .announcement-modal-wrapper {
            width: 98%;
            max-width: none;
        }

        .announcement-content {
            padding: 16px;
        }

        .announcement-content h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .announcement-text {
            font-size: 13px;
            line-height: 1.5;
        }

        .announcement-btn {
            padding: 10px 16px;
            font-size: 13px;
            margin-top: 12px;
        }

        .announcement-actions {
            gap: 8px;
            padding: 12px 16px;
        }

        .announcement-action-btn {
            padding: 10px 16px;
            font-size: 13px;
            gap: 6px;
        }

        .announcement-modal-close {
            width: 32px;
            height: 32px;
            font-size: 16px;
            top: 12px;
            right: 12px;
        }
    }
</style>

