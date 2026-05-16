<div class="notif-dropdown-container" style="position:relative;">
    <button class="theme-toggle" id="notifBtn" title="Messages" style="position:relative; width:34px; height:34px; border-radius:var(--r); background:var(--ink06); border:1px solid var(--glass-border); display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:.95rem; transition:var(--transition); color:var(--ink60);">
        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='var(--ink60)' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0' /></svg> <span id="notif-badge" class="badge-red" style="position:absolute; top:-5px; right:-5px; width:18px; height:18px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:50%; font-size:10px; border:2px solid var(--paper); display:none; background:#ef4444; color:white; font-weight:900;">
            0
        </span>
    </button>
    
    <div class="notif-dropdown" id="notifDropdown" style="position:absolute; top:calc(100% + 10px); right:0; width:320px; background:var(--chat-panel-bg); backdrop-filter:blur(24px); border:1px solid var(--glass-border); border-radius:14px; box-shadow:var(--shadow-card); opacity:0; transform:translateY(10px); pointer-events:none; transition:0.3s var(--ease); z-index:1100; overflow:hidden;">
        <div style="padding:1rem 1.25rem; border-bottom:1px solid var(--glass-border); display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size:0.85rem; font-weight:800; color:var(--ink); letter-spacing:0.02em;">Messages</span>
            <span style="font-size:0.65rem; font-weight:700; text-transform:uppercase; color:var(--accent); letter-spacing:0.05em;">Nouveau</span>
        </div>
        
        <div id="notif-list" style="max-height:360px; overflow-y:auto; padding:0.5rem 0;">
            <div style="padding:2rem; text-align:center; color:var(--ink30); font-size:0.8rem;">
                <div style="font-size:1.5rem; margin-bottom:0.5rem;"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='var(--ink30)' style='width:1.5rem;height:1.5rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25v-4.162c0-2.24-1.5-4.29-3.57-4.972L5.22 8.962c-2.07.682-3.57 2.732-3.57 4.972z' /></svg></div>
                Aucun nouveau message
            </div>
        </div>
        
        <a href="{{ route('messages.index') }}" style="display:block; padding:0.9rem; text-align:center; font-size:0.75rem; font-weight:700; color:var(--ink60); text-decoration:none; background:var(--ink06); border-top:1px solid var(--glass-border); transition:0.2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--ink60)'">
            Voir tous les messages
        </a>
    </div>
</div>

<style>
    .notif-dropdown-container.active #notifDropdown {
        opacity: 1 !important;
        transform: translateY(0) !important;
        pointer-events: all !important;
    }
    .notif-item {
        display: block; padding: 0.85rem 1.25rem; text-decoration: none; transition: 0.2s; border-bottom: 1px solid var(--ink06);
    }
    .notif-item:last-child { border-bottom: none; }
    .notif-item:hover { background: var(--ink06); }
    .notif-item-name { font-size: 0.82rem; font-weight: 800; color: var(--ink); margin-bottom: 0.15rem; display: flex; justify-content: space-between; }
    .notif-item-time { font-size: 0.65rem; font-weight: 500; color: var(--ink30); }
    .notif-item-subject { font-size: 0.75rem; color: var(--ink60); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notifBtn = document.getElementById('notifBtn');
        const notifContainer = document.querySelector('.notif-dropdown-container');
        const notifBadge = document.getElementById('notif-badge');
        const notifList = document.getElementById('notif-list');

        notifBtn?.addEventListener('click', function(e) {
            e.stopPropagation();
            notifContainer.classList.toggle('active');
        });

        document.addEventListener('click', function() {
            notifContainer.classList.remove('active');
        });

        function updateNotifications() {
            fetch('{{ route('messages.unreadCount') }}')
                .then(r => r.json())
                .then(data => {
                    // Update badge
                    if (data.count > 0) {
                        notifBadge.textContent = data.count;
                        notifBadge.style.display = 'flex';
                    } else {
                        notifBadge.style.display = 'none';
                    }

                    // Update list
                    if (data.latest && data.latest.length > 0) {
                        notifList.innerHTML = '';
                        data.latest.forEach(msg => {
                            const item = document.createElement('a');
                            item.href = `{{ url('/messages') }}/${msg.id}`;
                            item.className = 'notif-item';
                            const unreadDot = !msg.is_read ? '<div style="width:8px; height:8px; background:var(--accent); border-radius:50%; margin-right:8px;"></div>' : '';
                            item.innerHTML = `
                                <div class="notif-item-name">
                                    <div style="display:flex; align-items:center;">
                                        ${unreadDot}
                                        ${msg.sender_name}
                                    </div>
                                    <span class="notif-item-time">${msg.time}</span>
                                </div>
                                <div class="notif-item-subject" style="${!msg.is_read ? 'font-weight:700; color:var(--ink);' : ''}">${msg.subject}</div>
                            `;
                            notifList.appendChild(item);
                        });
                    } else {
                        notifList.innerHTML = `
                            <div style="padding:2rem; text-align:center; color:var(--ink30); font-size:0.8rem;">
                                <div style="font-size:1.5rem; margin-bottom:0.5rem;"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='var(--ink30)' style='width:1.5rem;height:1.5rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25v-4.162c0-2.24-1.5-4.29-3.57-4.972L5.22 8.962c-2.07.682-3.57 2.732-3.57 4.972z' /></svg></div>
                                Aucun nouveau message
                            </div>
                        `;
                    }
                })
                .catch(err => console.error('Erreur polling messages:', err));
        }

        // Initial check
        updateNotifications();
        // Poll every 30s
        setInterval(updateNotifications, 30000);
    });
</script>
