@extends('layouts.student')

@section('title', 'Visioconférence')

@section('content')
<style>
    .sm-container {
        font-family: var(--font-main);
        padding: 2rem 3rem 5rem;
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .sm-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .sm-sec-title {
        font-family: var(--font-serif);
        font-size: 1.8rem;
        font-weight: 300;
        margin: 0;
    }

    .sm-sec-title em {
        font-style: italic;
        color: var(--accent);
    }

    .sm-workspace {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 2rem;
        height: 520px;
    }

    @media (max-width: 1000px) {
        .sm-workspace {
            grid-template-columns: 1fr;
            height: auto;
        }
        .sm-video-frame {
            height: 350px !important;
        }
        .sm-video-chat {
            height: 350px !important;
        }
    }

    .sm-video-frame {
        background: #090D16;
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sm-video-badge {
        position: absolute;
        top: 1.25rem;
        left: 1.25rem;
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: .35rem .75rem;
        border-radius: var(--rx);
        border: 1px solid rgba(16, 185, 129, 0.2);
        z-index: 10;
        display: flex;
        align-items: center;
        gap: .4rem;
    }

    .sm-video-badge-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #10b981;
        animation: pulseLive 2s infinite;
    }

    @keyframes pulseLive {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(1.15); }
    }

    .sm-video-streams {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
    }

    .remote-video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        background: #000;
    }

    .local-video-container {
        position: absolute;
        bottom: 1.25rem;
        right: 1.25rem;
        width: 150px;
        height: 110px;
        border-radius: var(--r);
        overflow: hidden;
        border: 2px solid var(--paper);
        box-shadow: var(--shadow-card);
        background: #222;
        z-index: 20;
    }

    .local-video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1); /* Mirror effect */
    }

    .sm-video-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        text-align: center;
        color: #fff;
        z-index: 5;
    }

    .sm-video-avatar-pulse {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent), var(--accent3));
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--font-serif);
        font-size: 2.2rem;
        font-weight: 600;
        animation: pulseAvatar 2s infinite;
    }

    @keyframes pulseAvatar {
        0% { box-shadow: 0 0 0 0 rgba(234, 88, 12, 0.4); }
        70% { box-shadow: 0 0 0 16px rgba(234, 88, 12, 0); }
        100% { box-shadow: 0 0 0 0 rgba(234, 88, 12, 0); }
    }

    .sm-video-controls {
        position: absolute;
        bottom: 1.25rem;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: .75rem;
        z-index: 30;
    }

    .sm-video-btn {
        width: 42px;
        height: 42px;
        border-radius: var(--r);
        background: rgba(10, 37, 64, 0.65);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.15);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
    }

    .sm-video-btn:hover {
        background: var(--accent);
        transform: translateY(-2px);
    }

    .sm-video-btn.active-off {
        background: #ef4444;
        border-color: rgba(239, 68, 68, 0.4);
    }

    /* Video Chat */
    .sm-video-chat {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        height: 100%;
        box-shadow: var(--shadow-card);
    }

    .sm-chat-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--glass-border);
        font-weight: 700;
        font-size: .88rem;
        color: var(--ink);
        background: var(--ink06);
    }

    .sm-chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: .88rem;
    }

    .sm-chat-bubble {
        max-width: 80%;
        padding: .75rem 1rem;
        border-radius: var(--rl);
        font-size: .82rem;
        line-height: 1.5;
        word-wrap: break-word;
    }

    .sm-chat-bubble.student {
        background: var(--accent);
        color: #fff;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
    }

    .sm-chat-bubble.counselor {
        background: var(--ink06);
        color: var(--ink);
        align-self: flex-start;
        border-bottom-left-radius: 4px;
        border: 1px solid var(--glass-border);
    }

    .sm-chat-input-wrap {
        display: flex;
        gap: .5rem;
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--glass-border);
        background: var(--paper);
    }

    .sm-chat-input {
        flex: 1;
        background: var(--ink06);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: .6rem 1rem;
        color: var(--ink);
        font-family: var(--font-main);
        font-size: .82rem;
        outline: none;
    }

    .sm-chat-input:focus {
        border-color: var(--accent);
        background: var(--paper);
    }

    .sm-btn-send {
        background: var(--accent);
        border: none;
        border-radius: var(--r);
        color: #fff;
        padding: .5rem 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
    }

    .sm-btn-send:hover {
        opacity: .88;
    }

    /* Incoming call overlay */
    #incomingCallOverlay {
        display: none;
        position: absolute;
        inset: 0;
        background: rgba(10, 25, 47, 0.85);
        backdrop-filter: blur(12px);
        z-index: 999;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 1.5rem;
        color: #fff;
        border-radius: var(--rl);
    }
    
    .sm-call-avatar-pulse {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent2), var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        animation: pulseCall 1.5s infinite;
        border: 3px solid rgba(255,255,255,0.2);
    }
    
    @keyframes pulseCall {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 87, 184, 0.5); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 20px rgba(0, 87, 184, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 87, 184, 0); }
    }
</style>

<div class="sm-container">
    <div class="sm-header">
        <div>
            <p class="stag">Espace d'accompagnement</p>
            <h2 class="sm-sec-title">Entretien Vidéo <em>en direct</em></h2>
        </div>
        <div>
            <span style="font-size:.74rem; font-weight:700; color:var(--ink60);">CONSEILLER : {{ $counselor->name }}</span>
        </div>
    </div>

    <div class="sm-workspace">
        {{-- Video Frame --}}
        <div class="sm-video-frame">
            {{-- Incoming Call Overlay --}}
            <div id="incomingCallOverlay" style="display:none;">
                <div class="sm-call-avatar-pulse">
                    📞
                </div>
                <div style="text-align:center;">
                    <h3 style="font-family:var(--font-serif); font-size:1.5rem; font-weight:600; margin:0 0 0.5rem 0;">Appel en cours...</h3>
                    <p style="font-size:0.85rem; color:rgba(255,255,255,0.7); margin:0;">Le conseiller <strong>{{ $counselor->name }}</strong> vous invite à rejoindre la visioconférence.</p>
                </div>
                <div style="display:flex; gap:1rem; margin-top:1rem;">
                    <button id="btnAcceptCall" style="background:#10b981; color:#fff; border:none; padding:0.75rem 2rem; border-radius:var(--r); font-weight:700; cursor:pointer; font-size:0.88rem; box-shadow:0 4px 12px rgba(16,185,129,0.3); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        Accepter
                    </button>
                    <button id="btnRefuseCall" style="background:#ef4444; color:#fff; border:none; padding:0.75rem 2rem; border-radius:var(--r); font-weight:700; cursor:pointer; font-size:0.88rem; box-shadow:0 4px 12px rgba(239,68,68,0.3); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        Refuser
                    </button>
                </div>
            </div>
            <div class="sm-video-badge" id="videoBadge">
                <span class="sm-video-badge-dot"></span>
                <span id="callStatusText">EN ATTENTE DU CONSEILLER</span>
            </div>

            {{-- Real WebRTC video streams --}}
            <div class="sm-video-streams" id="videoStreams" style="display: none;">
                <video class="remote-video" id="remoteVideo" autoplay playsinline></video>
                <div class="local-video-container">
                    <video class="local-video" id="localVideo" autoplay playsinline muted></video>
                </div>
            </div>

            {{-- Video Placeholder before connect --}}
            <div class="sm-video-placeholder" id="videoPlaceholder">
                <div class="sm-video-avatar-pulse">
                    {{ strtoupper(substr($counselor->name, 0, 1)) }}
                </div>
                <div style="font-weight:700; font-size:.9rem;">Visioconférence avec {{ $counselor->name }}</div>
                <div style="font-size:.72rem; opacity:.7;" id="statusSubText">Caméra inactive · En attente de connexion</div>
            </div>

            {{-- Controls --}}
            <div class="sm-video-controls">
                <button class="sm-video-btn active-off" id="btnToggleCam" title="Activer/Désactiver Caméra">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 8-6 4 6 4V8Z"/><rect width="14" height="12" x="2" y="6" rx="2" ry="2"/><line id="camCrossLine" x1="2" y1="2" x2="22" y2="22" stroke="#ef4444" stroke-width="2.5"/></svg>
                </button>
                <button class="sm-video-btn active-off" id="btnToggleMic" title="Couper/Activer Micro">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" x2="12" y1="19" y2="22"/><line id="micCrossLine" x1="2" y1="2" x2="22" y2="22" stroke="#ef4444" stroke-width="2.5"/></svg>
                </button>
                <button class="sm-video-btn" id="btnToggleShare" title="Partager l'écran">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>
                </button>
                <a class="sm-video-btn active-off" href="{{ route('student.dashboard') }}" id="btnEndCall" title="Quitter la visioconférence">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </a>
            </div>
        </div>

        {{-- Chat Frame --}}
        <div class="sm-video-chat">
            <div class="sm-chat-header">Discussion en direct</div>
            <div class="sm-chat-messages" id="chatContainer">
                <div class="sm-chat-bubble counselor">
                    Bonjour ! Je suis connecté pour notre réunion d'accompagnement.
                </div>
            </div>
            <div class="sm-chat-input-wrap">
                <input type="text" class="sm-chat-input" id="chatInput" placeholder="Écrire un message en direct...">
                <button class="sm-btn-send" id="btnSendChat">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Include Socket.io client script hosted by the signaling server --}}
<script>
    (function() {
        var script = document.createElement('script');
        script.src = window.location.protocol + '//' + window.location.hostname + ':3000/socket.io/socket.io.js';
        document.head.appendChild(script);
    })();
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const studentId = {{ $student->id }};
    const roomId = 'meeting_' + studentId;
    
    // UI Elements
    const chatInput = document.getElementById('chatInput');
    const chatContainer = document.getElementById('chatContainer');
    const btnSendChat = document.getElementById('btnSendChat');
    const btnToggleCam = document.getElementById('btnToggleCam');
    const btnToggleMic = document.getElementById('btnToggleMic');
    const btnToggleShare = document.getElementById('btnToggleShare');
    const localVideo = document.getElementById('localVideo');
    const remoteVideo = document.getElementById('remoteVideo');
    const videoStreams = document.getElementById('videoStreams');
    const videoPlaceholder = document.getElementById('videoPlaceholder');
    const callStatusText = document.getElementById('callStatusText');
    const statusSubText = document.getElementById('statusSubText');
    const videoBadge = document.getElementById('videoBadge');
    
    let localStream = null;
    let screenStream = null;
    let peerConnection = null;
    let socket = null;
    
    let camActive = false;
    let micActive = false;
    let isSharing = false;

    // STUN config
    const rtcConfig = {
        iceServers: [{ urls: 'stun:stun.l.google.com:19302' }]
    };

    const overlay = document.getElementById('incomingCallOverlay');
    const btnAcceptCall = document.getElementById('btnAcceptCall');
    const btnRefuseCall = document.getElementById('btnRefuseCall');

    btnAcceptCall?.addEventListener('click', () => {
        console.log('[Meeting] Student accepted call');
        if (overlay) overlay.style.display = 'none';
        startMedia();
        if (socket) {
            socket.emit('accept-meeting', { roomId: roomId });
        }
    });

    btnRefuseCall?.addEventListener('click', () => {
        console.log('[Meeting] Student refused call');
        if (overlay) overlay.style.display = 'none';
        if (socket) {
            socket.emit('refuse-meeting', { roomId: roomId });
        }
        callStatusText.textContent = 'APPEL REFUSÉ';
    });

    // Initialize Socket.io Connection for WebRTC Signaling
    try {
        socket = io(window.location.protocol + '//' + window.location.hostname + ':3000');
        
        socket.on('connect', () => {
            console.log('[Socket] Connected to signaling server');
            socket.emit('join-room', roomId);
        });

        socket.on('invite-meeting', (data) => {
            console.log('[Meeting] Received call invitation from counselor');
            if (overlay) {
                overlay.style.display = 'flex';
            }
        });

        socket.on('peer-joined', (peerId) => {
            console.log('[WebRTC] Counselor joined the call:', peerId);
            callStatusText.textContent = 'CONSEILLER CONNECTÉ';
            videoBadge.style.background = 'rgba(16, 185, 129, 0.12)';
            videoBadge.style.color = '#10b981';
        });

        socket.on('offer', async (data) => {
            console.log('[WebRTC] Offer received');
            if (!peerConnection) createPeerConnection();
            
            await peerConnection.setRemoteDescription(new RTCSessionDescription(data.sdp));
            const answer = await peerConnection.createAnswer();
            await peerConnection.setLocalDescription(answer);
            
            socket.emit('answer', { sdp: answer, roomId: roomId });
            callStatusText.textContent = 'EN DIRECT · CONNECTÉ';
        });

        socket.on('answer', async (data) => {
            console.log('[WebRTC] Answer received');
            await peerConnection.setRemoteDescription(new RTCSessionDescription(data.sdp));
            callStatusText.textContent = 'EN DIRECT · CONNECTÉ';
        });

        socket.on('candidate', async (data) => {
            console.log('[WebRTC] ICE Candidate received');
            if (peerConnection) {
                await peerConnection.addIceCandidate(new RTCIceCandidate(data.candidate));
            }
        });

        socket.on('peer-disconnected', (peerId) => {
            console.log('[WebRTC] Counselor left');
            callStatusText.textContent = 'CONSEILLER DÉCONNECTÉ';
            videoBadge.style.background = 'rgba(239, 68, 68, 0.12)';
            videoBadge.style.color = '#ef4444';
            
            if (remoteVideo) remoteVideo.srcObject = null;
            if (peerConnection) {
                peerConnection.close();
                peerConnection = null;
            }
        });
    } catch (e) {
        console.error('[Socket] Failed to connect to signaling server:', e);
        callStatusText.textContent = 'ERREUR SERVEUR DE SIGNALISATION';
    }

    // --- WebRTC Logic ---
    async function startMedia() {
        try {
            localStream = await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: true
            });
            localVideo.srcObject = localStream;
            videoPlaceholder.style.display = 'none';
            videoStreams.style.display = 'block';
            
            camActive = true;
            micActive = true;
            
            btnToggleCam.classList.remove('active-off');
            document.getElementById('camCrossLine').style.display = 'none';
            
            btnToggleMic.classList.remove('active-off');
            document.getElementById('micCrossLine').style.display = 'none';

            console.log('[WebRTC] Local media stream started successfully');

            // If counselor is already in the room, start the call
            if (callStatusText.textContent === 'CONSEILLER CONNECTÉ') {
                initiateCall();
            }
        } catch (err) {
            console.error('[WebRTC] Failed to access camera/mic:', err);
            alert("Impossible d'accéder à la caméra et au micro. Veuillez accorder les permissions.");
        }
    }

    function createPeerConnection() {
        peerConnection = new RTCPeerConnection(rtcConfig);
        
        // Add tracks to PeerConnection
        if (localStream) {
            localStream.getTracks().forEach(track => {
                peerConnection.addTrack(track, localStream);
            });
        }

        // On ICE Candidate
        peerConnection.onicecandidate = (event) => {
            if (event.candidate) {
                socket.emit('candidate', {
                    candidate: event.candidate,
                    roomId: roomId
                });
            }
        };

        // On remote track
        peerConnection.ontrack = (event) => {
            console.log('[WebRTC] Remote track received');
            remoteVideo.srcObject = event.streams[0];
        };
    }

    async function initiateCall() {
        createPeerConnection();
        const offer = await peerConnection.createOffer();
        await peerConnection.setLocalDescription(offer);
        socket.emit('offer', { sdp: offer, roomId: roomId });
    }

    // Toggle Camera
    btnToggleCam.addEventListener('click', () => {
        if (!localStream) {
            startMedia();
            return;
        }
        camActive = !camActive;
        localStream.getVideoTracks()[0].enabled = camActive;
        
        if (camActive) {
            btnToggleCam.classList.remove('active-off');
            document.getElementById('camCrossLine').style.display = 'none';
        } else {
            btnToggleCam.classList.add('active-off');
            document.getElementById('camCrossLine').style.display = 'inline';
        }
    });

    // Toggle Microphone
    btnToggleMic.addEventListener('click', () => {
        if (!localStream) return;
        micActive = !micActive;
        localStream.getAudioTracks()[0].enabled = micActive;
        
        if (micActive) {
            btnToggleMic.classList.remove('active-off');
            document.getElementById('micCrossLine').style.display = 'none';
        } else {
            btnToggleMic.classList.add('active-off');
            document.getElementById('micCrossLine').style.display = 'inline';
        }
    });

    // Share Screen
    btnToggleShare.addEventListener('click', async () => {
        if (!localStream) return;
        
        if (!isSharing) {
            try {
                screenStream = await navigator.mediaDevices.getDisplayMedia({ video: true });
                const videoTrack = screenStream.getVideoTracks()[0];
                
                // Replace video track in peer connection sender
                if (peerConnection) {
                    const senders = peerConnection.getSenders();
                    const videoSender = senders.find(sender => sender.track && sender.track.kind === 'video');
                    if (videoSender) {
                        videoSender.replaceTrack(videoTrack);
                    }
                }
                
                // Display locally
                localVideo.srcObject = screenStream;
                isSharing = true;
                btnToggleShare.classList.add('active-off');
                
                // Handle share stopped from browser overlay
                videoTrack.onended = () => {
                    stopScreenSharing();
                };
            } catch (err) {
                console.error('[WebRTC] Screen sharing error:', err);
            }
        } else {
            stopScreenSharing();
        }
    });

    function stopScreenSharing() {
        if (!isSharing) return;
        
        const videoTrack = localStream.getVideoTracks()[0];
        if (peerConnection) {
            const senders = peerConnection.getSenders();
            const videoSender = senders.find(sender => sender.track && sender.track.kind === 'video');
            if (videoSender) {
                videoSender.replaceTrack(videoTrack);
            }
        }
        
        localVideo.srcObject = localStream;
        if (screenStream) {
            screenStream.getTracks().forEach(track => track.stop());
        }
        isSharing = false;
        btnToggleShare.classList.remove('active-off');
    }

    // --- WebSockets Live Chat (Laravel Echo) ---
    if (window.Echo) {
        console.log('[Echo] Connecting to channel chat');
        window.Echo.channel('chat')
            .listen('MessageSent', (e) => {
                console.log('[Echo] Message received:', e.message);
                
                // Display message only if sent by the counselor (receiver of student)
                if (Number(e.message.sender_id) !== Number(studentId)) {
                    appendChatBubble(e.message.body, 'counselor');
                }
            });
    } else {
        console.error('[Echo] Echo not found. Real-time chat disabled.');
    }

    // Append chat bubble to UI
    function appendChatBubble(text, senderType) {
        const bubble = document.createElement('div');
        bubble.className = `sm-chat-bubble ${senderType}`;
        bubble.textContent = text;
        chatContainer.appendChild(bubble);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Send Chat Message via AJAX and broadcast
    async function sendChatMessage() {
        const text = chatInput.value.trim();
        if (!text) return;
        
        // Append bubble locally
        appendChatBubble(text, 'student');
        chatInput.value = '';

        try {
            const response = await fetch('/student/meeting/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message_body: text })
            });
            const data = await response.json();
            console.log('[Chat] Message sent response:', data);
        } catch (err) {
            console.error('[Chat] Failed to send message:', err);
        }
    }

    btnSendChat.addEventListener('click', sendChatMessage);
    chatInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') sendChatMessage();
    });


});
</script>
@endsection
