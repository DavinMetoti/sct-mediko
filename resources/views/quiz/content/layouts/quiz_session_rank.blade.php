@php
    use Illuminate\Support\Str;
    $softColors = ['#E57373', '#F06292', '#BA68C8', '#64B5F6', '#4DB6AC', '#81C784', '#FFD54F', '#FF8A65'];
    $quizSessionId = $session->id ?? 1;
@endphp

@extends('quiz.content.index')

@section('quiz-content')
    <div class="row">
        <div class="col-md-6">
            <div class="card p-3 rounded-4 shadow sticky-top" style="top: 24px; z-index: 10; background: linear-gradient(135deg, #f8fafc 60%, #e3f2fd 100%); border: 1px solid #e3e3e3;">
                <h5 class="fw-bold mb-3" style="color: #24437E;">
                    <i class="fa-solid fa-trophy text-warning me-2"></i>
                    {{ $session->title }}
                </h5>
                <div class="mb-3 text-muted" style="font-size: 1.05rem;">
                    <strong class="text-primary">Waktu Classroom:</strong>
                    <span id="classroom-time-range" class="ms-1"></span>
                </div>
                <div class="flex flex-column mb-3">
                    <label for="filterClassroom" class="form-label fw-semibold" style="color: #24437E;">
                        <i class="fa-solid fa-filter me-1"></i>Filter Classroom:
                    </label>
                    <select id="filterClassroom" class="form-select border-primary" style="max-width:300px;display:inline-block; background: #f4f8fb;">
                        <option value="">Semua Classroom</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="rank-group-container"></div>
        </div>
    </div>

    @php
        $currentAttemptId = isset($attempt) ? $attempt->id : null;
    @endphp

    <script src="{{ secure_asset('assets/js/module.js') }}"></script>
    <script>
        const socket = io('https://server.oscemediko.id/', {
            query: {
                channel: 'channel-set-mediko',
                secureKey: 'D4BA583B5663BDCA754B8C5448977'
            }
        });
        const quizSessionId = @json($quizSessionId);
        const apiUrl = "{{ route('quiz-rank', ['id' => $quizSessionId]) }}";
        let allAttempts = [];
        let classroomMap = {};
        let lastRankDataJson = null; // Tambahkan untuk menyimpan snapshot data terakhir

        function fetchSessionRank(forceUpdate = false) {
            $.ajax({
                url: apiUrl,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    const newRankList = data.sessionRankList || [];
                    const newRankJson = JSON.stringify(newRankList);

                    // Jika bukan forceUpdate dan data tidak berubah, jangan update tampilan
                    if (!forceUpdate && lastRankDataJson === newRankJson) {
                        return;
                    }
                    lastRankDataJson = newRankJson;

                    toastr.success("Data rangking berhasil diambil", "", { timeOut: 3000 });
                    allAttempts = newRankList;
                    buildClassroomMap(allAttempts);
                    populateClassroomFilter();
                    displaySessionRankGrouped(allAttempts);
                },
                error: function(xhr, status, error) {
                    toastr.error("Gagal mengambil data rangking");
                    console.error('Error:', error);
                }
            });
        }

        function buildClassroomMap(attempts) {
            classroomMap = {};
            attempts.forEach(attempt => {
                if (attempt.classroom_id && attempt.classroom) {
                    classroomMap[attempt.classroom_id] = attempt.classroom;
                }
            });
        }

        function populateClassroomFilter() {
            const $filter = $('#filterClassroom');
            $filter.empty();
            $filter.append('<option value="">Semua Classroom</option>');
            // Kumpulkan unique classroom_id
            let ids = {};
            allAttempts.forEach(a => {
                if (a.classroom_id && a.classroom) {
                    ids[a.classroom_id] = a.classroom.name + ' (' + (a.classroom.start_time ?? '') + ' - ' + (a.classroom.end_time ?? '') + ')';
                }
            });
            Object.keys(ids).forEach(cid => {
                $filter.append(`<option value="${cid}">${ids[cid]}</option>`);
            });
        }

        $('#filterClassroom').on('change', function() {
            displaySessionRankGrouped(allAttempts);
        });

        socket.on('receive-message', (data) => {
            console.log('Received:', data);
            // Hanya update jika data berubah
            fetchSessionRank();
        });

        function displaySessionRankGrouped(attempts) {
            const container = document.getElementById('rank-group-container');
            container.innerHTML = '';

            if (!attempts || attempts.length === 0) {
                container.innerHTML = `<p class="text-center">No rankings available.</p>`;
                $('#classroom-time-range').text('');
                return;
            }

            // Filter by classroom if selected
            const selectedClassroom = $('#filterClassroom').val();
            let filteredAttempts = attempts;
            if (selectedClassroom) {
                filteredAttempts = attempts.filter(a => String(a.classroom_id) === String(selectedClassroom));
            }

            // Group by classroom_id
            const grouped = {};
            filteredAttempts.forEach(attempt => {
                const cid = attempt.classroom_id || 'Tanpa Classroom';
                if (!grouped[cid]) grouped[cid] = [];
                grouped[cid].push(attempt);
            });

            // Show classroom time range if filter is selected
            if (selectedClassroom && classroomMap[selectedClassroom]) {
                // Format tanggal ke "20 Mei 2025, 12:00"
                function formatDateRange(start, end) {
                    if (!start || !end) return '';
                    const months = [
                        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                    ];
                    const startDate = new Date(start.replace(' ', 'T'));
                    const endDate = new Date(end.replace(' ', 'T'));
                    const day1 = startDate.getDate();
                    const month1 = months[startDate.getMonth()];
                    const year1 = startDate.getFullYear();
                    const time1 = startDate.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    const day2 = endDate.getDate();
                    const month2 = months[endDate.getMonth()];
                    const year2 = endDate.getFullYear();
                    const time2 = endDate.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    // Jika tanggal sama bulan sama tahun sama, tampilkan satu tanggal rentang waktu
                    if (day1 === day2 && month1 === month2 && year1 === year2) {
                        return `${day1} ${month1} ${year1}, ${time1} - ${time2}`;
                    } else {
                        return `${day1} ${month1} ${year1}, ${time1} - ${day2} ${month2} ${year2}, ${time2}`;
                    }
                }
                $('#classroom-time-range').text(
                    formatDateRange(classroomMap[selectedClassroom].start_time, classroomMap[selectedClassroom].end_time)
                );
                $('#classroom-time-range').parent().show();
            } else {
                $('#classroom-time-range').text('');
                // Hide the parent div if filter kosong
                $('#classroom-time-range').parent().hide();
            }

            Object.keys(grouped).forEach(cid => {
                // Classroom title
                let classroomTitle = 'Tanpa Classroom';
                let classroomTime = '';
                if (cid !== 'Tanpa Classroom' && classroomMap[cid]) {
                    classroomTitle = classroomMap[cid].name;
                    // Format tanggal ke "20 Mei 2025, waktu"
                    function formatDateRange(start, end) {
                        if (!start) return '';
                        const months = [
                            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                        ];
                        const startDate = new Date(start.replace(' ', 'T'));
                        const day = startDate.getDate();
                        const month = months[startDate.getMonth()];
                        const year = startDate.getFullYear();
                        return `${day} ${month} ${year}, ${startDate.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}`;
                    }
                    classroomTime = formatDateRange(classroomMap[cid].start_time, classroomMap[cid].end_time);
                } else if (cid !== 'Tanpa Classroom') {
                    classroomTitle = `Classroom #${cid}`;
                }

                const groupDiv = document.createElement('div');
                groupDiv.classList.add('mb-4');

                groupDiv.innerHTML = `
                    <div class="quiz-container-rank card p-3 mb-2">
                        <div class="mb-2">
                            <h6 class="fw-bold text-center text-dark">
                                <i class="fa-solid fa-ranking-star me-2 text-warning"></i> Live Leaderboard
                            </h6>
                            <h4 class="fw-bold mb-1" style="font-size:1.15rem;">${classroomTitle}</h4>
                            ${classroomTime ? `<div class="text-muted" style="font-size:0.95em;">${classroomTime}</div>` : ''}
                        </div>
                        <div class="rank-list"></div>
                    </div>
                `;

                container.appendChild(groupDiv);

                // Render rank for this group ke .rank-list
                displaySessionRank(grouped[cid], groupDiv.querySelector('.rank-list'));
            });
        }

        function displaySessionRank(attempts, container) {
            if (!container) return;
            container.innerHTML = '';

            const currentAttemptId = @json($currentAttemptId);

            const rankStyles = [
                { bg: "#AB972F", shadow: "inset 8px 0 0  #FFD700" }, // Gold
                { bg: "#8B8B8B", shadow: "inset 8px 0 0  #C0C0C0" }, // Silver
                { bg: "#9A6632", shadow: "inset 8px 0 0  #CD7F32" }, // Bronze
            ];

            if (!attempts || attempts.length === 0) {
                container.innerHTML = `<p class="text-center">No rankings available.</p>`;
                return;
            }

            attempts.forEach((rank, index) => {
                let fontWeight = "normal";
                let fontColor = "#fff";
                let border = "none";
                let borderRadius = "8px";
                let bgColor = "#24437E"; // default blue
                let boxShadow = "";

                // Top 3
                if (index < 3) {
                    bgColor = rankStyles[index].bg;
                    boxShadow = rankStyles[index].shadow;
                    fontWeight = "bold";
                }

                // Highlight user's own attempt
                if (rank.id == currentAttemptId) {
                    border = "2px solid #fff";
                    boxShadow += "inset 8px 0 0 #00BFFF";
                }

                const style = `
                    background: ${bgColor};
                    color: ${fontColor};
                    border-radius: ${borderRadius};
                    margin-bottom: 10px;
                    box-shadow: ${boxShadow};
                    border: ${border};
                    transition: box-shadow 0.2s;
                    padding: 0;
                    font-size: 1rem;
                `;

                const html = `
                    <div class="w-100 d-flex align-items-center" style="min-height: 40px; padding: 0 24px;">
                        <span style="font-size: 1.1rem; font-weight: bold; margin-right: 16px;">${index + 1}.</span>
                        <h5 style="font-size: 0.9rem; font-weight: ${fontWeight}; flex: 1; margin: 0;">${rank.name}</h5>
                        <span style="font-size: 0.9rem; margin-left: 12px;">${rank.percentage_score}</span>
                    </div>
                `;

                const rankElement = document.createElement('div');
                rankElement.className = 'rank-item';
                rankElement.style = style;
                rankElement.innerHTML = html;

                container.appendChild(rankElement);
            });
        }

        fetchSessionRank();
    </script>
@endsection
