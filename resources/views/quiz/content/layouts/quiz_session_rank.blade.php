@php
    use Illuminate\Support\Str;
    $softColors = ['#E57373', '#F06292', '#BA68C8', '#64B5F6', '#4DB6AC', '#81C784', '#FFD54F', '#FF8A65'];
    $quizSessionId = $session->id ?? 1;
@endphp

@extends('quiz.content.index')

@section('quiz-content')
    <h3 class="fw-bold">Session Result : {{ $session->title }}</h3>
    <div class="mb-3 text-muted">
        <strong>Waktu Classroom:</strong>
        <span id="classroom-time-range"></span>
    </div>
    <div class="flex flex-column mb-3">
        <label for="filterClassroom" class="form-label">Filter Classroom:</label>
        <select id="filterClassroom" class="form-select" style="max-width:300px;display:inline-block;">
            <option value="">Semua Classroom</option>
        </select>
    </div>
    <div id="rank-group-container"></div>

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

        function fetchSessionRank() {
            $.ajax({
                url: apiUrl,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    toastr.success("Data rangking berhasil diambil", "", { timeOut: 3000 });
                    allAttempts = data.sessionRankList || [];
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
                $('#classroom-time-range').text(
                    (classroomMap[selectedClassroom].start_time ?? '') +
                    ' - ' +
                    (classroomMap[selectedClassroom].end_time ?? '')
                );
            } else {
                $('#classroom-time-range').text('');
            }

            Object.keys(grouped).forEach(cid => {
                // Classroom title
                let classroomTitle = 'Tanpa Classroom';
                let classroomTime = '';
                if (cid !== 'Tanpa Classroom' && classroomMap[cid]) {
                    classroomTitle = classroomMap[cid].name;
                    classroomTime = (classroomMap[cid].start_time ?? '') + ' - ' + (classroomMap[cid].end_time ?? '');
                } else if (cid !== 'Tanpa Classroom') {
                    classroomTitle = `Classroom #${cid}`;
                }

                const groupDiv = document.createElement('div');
                groupDiv.classList.add('mb-4');

                groupDiv.innerHTML = `
                    <h4 class="fw-bold mb-1">${classroomTitle}</h4>
                    ${classroomTime ? `<div class="text-muted mb-2" style="font-size:0.95em;">${classroomTime}</div>` : ''}
                    <div class="quiz-container-rank"></div>
                `;

                container.appendChild(groupDiv);

                // Render rank for this group
                displaySessionRank(grouped[cid], groupDiv.querySelector('.quiz-container-rank'));
            });
        }

        function displaySessionRank(attempts, container) {
            if (!container) return;
            const prevRanks = {};
            container.querySelectorAll('.rank-item').forEach((item, i) => {
                const name = item.querySelector('h5')?.innerText;
                if (name) {
                    prevRanks[name] = i + 1;
                }
            });

            container.innerHTML = '';

            if (attempts && attempts.length > 0) {
                attempts.forEach((rank, index) => {
                    const rankElement = document.createElement('div');
                    rankElement.classList.add('rank-item', 'p-2', 'rounded', 'd-flex', 'align-items-center', 'animate-rank');

                    if (prevRanks[rank.name] && prevRanks[rank.name] > index + 1) {
                        rankElement.classList.add('rank-up');
                    }

                    if (index === 0) {
                        rankElement.style.backgroundColor = '#FFD700';
                    } else if (index === 1) {
                        rankElement.style.backgroundColor = '#C0C0C0';
                    } else if (index === 2) {
                        rankElement.style.backgroundColor = '#CD7F32';
                    } else {
                        rankElement.style.backgroundColor = '#888';
                    }

                    const crownIcon = index === 0
                        ? `<span class="d-inline-flex justify-content-center align-items-center bg-white text-warning rounded-circle"
                                style="width: 40px; height: 40px; aspect-ratio: 1/1;">
                                <i class="fas fa-crown text-xl"></i>
                            </span>`
                        : '';

                    rankElement.innerHTML = `
                        <div class="rank-card w-100 p-2 d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2 align-items-center">
                                <h3 class="m-0">#${index + 1}</h3>
                                <div>
                                    <h5 class="m-0">${rank.name}</h5>
                                    <p class="m-0">Score: ${rank.percentage_score}</p>
                                </div>
                            </div>
                            ${crownIcon}
                        </div>
                    `;

                    container.appendChild(rankElement);
                });
            } else {
                container.innerHTML = `<p class="text-center">No rankings available.</p>`;
            }
        }

        fetchSessionRank();
    </script>
@endsection
