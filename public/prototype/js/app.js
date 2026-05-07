// Initialize state
const state = {
    currentRole: 'student',
    currentView: 'login',
    studentData: {
        name: 'Budi Santoso',
        nim: '220101010',
        semester: 5,
        skkmPoints: 15,
        skkmMax: 20,
        skkmHistory: [
            { id: 1, date: '10 Okt 2026', category: 'Organisasi', name: 'Panitia Seminar Nasional', points: 5, status: 'disetujui' },
            { id: 2, date: '15 Sep 2026', category: 'Minat', name: 'Juara 3 Lomba Esai', points: 10, status: 'disetujui' },
            { id: 3, date: '01 Nov 2026', category: 'Penalaran', name: 'Workshop Web Dev', points: 2, status: 'pending' }
        ],
        guidanceHistory: [
            { id: 1, date: '2026-10-12', topic: 'Evaluasi KRS dan Rencana Magang', notes: 'Perlu perbaikan nilai matkul algoritma', status: 'divalidasi', lecturer: 'Dr. Ahmad, S.Kom., M.Kom.' }
        ]
    },
    lecturerData: {
        approvals: [
            { id: 3, student: 'Budi Santoso', nim: '220101010', activity: 'Workshop Web Dev', points: 2, status: 'pending' },
            { id: 4, student: 'Siti Aminah', nim: '220101012', activity: 'Relawan Bencana', points: 5, status: 'pending' },
        ]
    }
};

// Initialize Lucide icons
lucide.createIcons();

// DOM Elements
const views = {
    login: document.getElementById('view-login'),
    studentDashboard: document.getElementById('view-student-dashboard'),
    lecturerDashboard: document.getElementById('view-lecturer-dashboard')
};

// --- View Transition & Logic ---
function switchView(viewName) {
    Object.values(views).forEach(v => v.classList.remove('active'));
    
    if (viewName === 'studentDashboard') {
        renderStudentDashboard();
        views.studentDashboard.classList.add('active');
    } else if (viewName === 'lecturerDashboard') {
        renderLecturerDashboard();
        views.lecturerDashboard.classList.add('active');
    } else {
        views.login.classList.add('active');
    }
}

// --- Login Logic ---
const roleBtns = document.querySelectorAll('.role-btn');
const identifierLabel = document.getElementById('label-identifier');
const identifierInput = document.getElementById('identifier');

roleBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
        // Toggle active class
        roleBtns.forEach(b => b.classList.remove('active'));
        e.target.classList.add('active');
        
        state.currentRole = e.target.dataset.role;
        
        if(state.currentRole === 'student') {
            identifierLabel.innerText = 'NIM / Email';
            identifierInput.placeholder = 'Masukkan NIM atau Email';
        } else {
            identifierLabel.innerText = 'NIDN / Email';
            identifierInput.placeholder = 'Masukkan NIDN atau Email';
        }
    });
});

const togglePasswordBtn = document.getElementById('toggle-password-btn');
const passwordInput = document.getElementById('password');
const eyeIcon = document.getElementById('eye-icon');

togglePasswordBtn.addEventListener('click', () => {
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.setAttribute('data-lucide', 'eye-off');
    } else {
        passwordInput.type = 'password';
        eyeIcon.setAttribute('data-lucide', 'eye');
    }
    lucide.createIcons();
});

document.getElementById('login-form').addEventListener('submit', (e) => {
    e.preventDefault();
    const id = identifierInput.value;
    const pwd = passwordInput.value;
    
    if(id && pwd) {
        showToast('Login berhasil', 'success');
        if(state.currentRole === 'student') {
            switchView('studentDashboard');
        } else {
            switchView('lecturerDashboard');
        }
    }
});

function logout() {
    showToast('Berhasil keluar', 'info');
    switchView('login');
    document.getElementById('login-form').reset();
}

// --- Dashboard Student ---
function renderStudentDashboard() {
    // Render SKKM Points
    const progressDegree = (state.studentData.skkmPoints / state.studentData.skkmMax) * 360;
    const circularProgress = document.querySelector('.circular-progress');
    if(circularProgress) circularProgress.style.setProperty('--progress', `${progressDegree}deg`);
    
    document.querySelector('.progress-value').innerHTML = `${state.studentData.skkmPoints}<span class="progress-total">/${state.studentData.skkmMax}</span>`;
    
    // Render table
    const tbody = document.getElementById('student-activity-table');
    if(!tbody) return;
    
    let html = '';
    state.studentData.skkmHistory.forEach(item => {
        let badgeClass = item.status === 'disetujui' ? 'badge-success' : item.status === 'pending' ? 'badge-warning' : 'badge-error';
        html += `
            <tr>
                <td>${item.date}</td>
                <td><span class="badge badge-info">${item.category}</span></td>
                <td>${item.name}</td>
                <td>+${item.points}</td>
                <td><span class="badge ${badgeClass}">${item.status.charAt(0).toUpperCase() + item.status.slice(1)}</span></td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
    
    // Render Guidance table
    const tbodyGuidance = document.getElementById('student-guidance-table');
    if(tbodyGuidance) {
        let guidanceHtml = '';
        state.studentData.guidanceHistory.forEach(item => {
            let badgeClass = item.status === 'divalidasi' ? 'badge-success' : 'badge-warning';
            guidanceHtml += `
                <tr>
                    <td>${item.date}</td>
                    <td style="font-weight: 500;">${item.topic}</td>
                    <td class="text-slate">${item.notes}</td>
                    <td><span class="badge ${badgeClass}">${item.status.charAt(0).toUpperCase() + item.status.slice(1)}</span></td>
                    <td class="action-cell">
                        <button class="btn btn-sm btn-outline" onclick="app.editGuidance(${item.id})">
                            <i data-lucide="edit-2"></i>
                        </button>
                        <button class="btn btn-sm btn-outline" style="color: var(--error); border-color: var(--error-light);" onclick="app.deleteGuidance(${item.id})">
                            <i data-lucide="trash-2"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        tbodyGuidance.innerHTML = guidanceHtml;
    }
    lucide.createIcons();
}

// --- Dashboard Lecturer ---
function renderLecturerDashboard() {
    const tbody = document.getElementById('lecturer-approval-table');
    if(!tbody) return;
    
    let html = '';
    state.lecturerData.approvals.forEach(item => {
        html += `
            <tr id="approval-row-${item.id}">
                <td>
                    <div style="font-weight: 500;">${item.student}</div>
                </td>
                <td class="text-slate">${item.nim}</td>
                <td>${item.activity}</td>
                <td><span class="badge badge-info">+${item.points} Pts</span></td>
                <td><button class="btn btn-sm btn-outline"><i data-lucide="file-text"></i> PDF</button></td>
                <td class="action-cell">
                    <button class="btn btn-sm btn-primary" style="background: var(--success); border-color: var(--success);" onclick="app.approveSkkm(${item.id})">
                        <i data-lucide="check"></i> Setujui
                    </button>
                    <button class="btn btn-sm btn-outline" style="color: var(--error); border-color: var(--error-light);" onclick="app.rejectSkkm(${item.id})">
                        <i data-lucide="x"></i> Tolak
                    </button>
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
    lucide.createIcons();
}

// --- Modals Logic ---
const overlay = document.getElementById('modal-overlay');
const modals = document.querySelectorAll('.modal');
const closeBtns = document.querySelectorAll('.close-modal, .cancel-modal');

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if(modal) {
        overlay.classList.add('show');
        modal.classList.add('show');
    }
}

function closeAllModals() {
    overlay.classList.remove('show');
    modals.forEach(m => m.classList.remove('show'));
}

closeBtns.forEach(btn => btn.addEventListener('click', closeAllModals));
overlay.addEventListener('click', closeAllModals);

// --- SKKM Logic ---
document.getElementById('form-skkm').addEventListener('submit', (e) => {
    e.preventDefault();
    const points = parseInt(document.getElementById('skkm-points').value);
    
    if (state.studentData.skkmPoints + points > state.studentData.skkmMax) {
        showToast(`Maaf, batas poin per semester adalah ${state.studentData.skkmMax}. Poin saat ini ${state.studentData.skkmPoints}.`, 'error');
        return;
    }

    const newSkkm = {
        id: Date.now(),
        date: new Date().toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'}),
        category: document.getElementById('skkm-category').options[document.getElementById('skkm-category').selectedIndex].text,
        name: document.getElementById('skkm-name').value,
        points: points,
        status: 'pending'
    };

    state.studentData.skkmHistory.unshift(newSkkm);
    // Don't add to current points until approved, but for demo we just show it pending
    renderStudentDashboard();
    closeAllModals();
    document.getElementById('form-skkm').reset();
    showToast('Sertifikat SKKM berhasil diajukan untuk verifikasi.', 'success');
});

// --- Guidance Logic (CRUD) ---
document.getElementById('form-guidance').addEventListener('submit', (e) => {
    e.preventDefault();
    const id = document.getElementById('guidance-id').value;
    const date = document.getElementById('guidance-date').value;
    const topic = document.getElementById('guidance-topic').value;
    const notes = document.getElementById('guidance-notes').value;

    if (id) {
        // Update
        const index = state.studentData.guidanceHistory.findIndex(g => g.id == id);
        if(index !== -1) {
            state.studentData.guidanceHistory[index] = { ...state.studentData.guidanceHistory[index], date, topic, notes };
            showToast('Logbook bimbingan diperbarui.', 'success');
        }
    } else {
        // Create
        state.studentData.guidanceHistory.unshift({
            id: Date.now(),
            date, topic, notes,
            status: 'pending',
            lecturer: 'Dr. Ahmad, S.Kom., M.Kom.'
        });
        showToast('Logbook bimbingan berhasil ditambahkan.', 'success');
    }

    renderStudentDashboard();
    closeAllModals();
    document.getElementById('form-guidance').reset();
});

// Edit Guidance
function editGuidance(id) {
    const item = state.studentData.guidanceHistory.find(g => g.id == id);
    if(item) {
        document.getElementById('guidance-id').value = item.id;
        document.getElementById('guidance-date').value = item.date;
        document.getElementById('guidance-topic').value = item.topic;
        document.getElementById('guidance-notes').value = item.notes;
        document.getElementById('guidance-modal-title').innerText = 'Edit Logbook Bimbingan';
        openModal('modal-guidance');
    }
}

// Delete Guidance
function deleteGuidance(id) {
    if(confirm('Apakah Anda yakin ingin menghapus logbook ini?')) {
        state.studentData.guidanceHistory = state.studentData.guidanceHistory.filter(g => g.id != id);
        renderStudentDashboard();
        showToast('Logbook berhasil dihapus.', 'info');
    }
}

// Lecturer Actions
function approveSkkm(id) {
    const row = document.getElementById(`approval-row-${id}`);
    if(row) row.remove();
    showToast('SKKM Mahasiswa disetujui', 'success');
    
    // update data
    state.lecturerData.approvals = state.lecturerData.approvals.filter(a => a.id !== id);
}

function rejectSkkm(id) {
    const row = document.getElementById(`approval-row-${id}`);
    if(row) row.remove();
    showToast('SKKM Mahasiswa ditolak', 'info');
    
    state.lecturerData.approvals = state.lecturerData.approvals.filter(a => a.id !== id);
}

// --- Toast Notifications ---
function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    let icon = 'info';
    if(type === 'success') icon = 'check-circle';
    if(type === 'error') icon = 'alert-circle';
    if(type === 'warning') icon = 'alert-triangle';
    
    toast.innerHTML = `
        <i data-lucide="${icon}"></i>
        <div>${message}</div>
    `;
    
    container.appendChild(toast);
    lucide.createIcons();
    
    setTimeout(() => {
        toast.style.animation = 'fadeOut 0.3s forwards';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

// Expose app functions to window for onclick handlers
window.app = {
    logout,
    showSkkmModal: () => openModal('modal-skkm'),
    showGuidanceModal: () => {
        document.getElementById('form-guidance').reset();
        document.getElementById('guidance-id').value = '';
        document.getElementById('guidance-modal-title').innerText = 'Isi Logbook Bimbingan';
        openModal('modal-guidance');
    },
    editGuidance,
    deleteGuidance,
    approveSkkm,
    rejectSkkm
};
