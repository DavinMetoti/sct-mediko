@php
    use Illuminate\Support\Str;
    $softColors = ['#E57373', '#F06292', '#BA68C8', '#64B5F6', '#4DB6AC', '#81C784', '#FFD54F', '#FF8A65'];
    $quizSessionId = $session->id ?? 1;
@endphp

@extends('quiz.content.index')

@section('quiz-content')
    <h3 class="fw-bold">Session Result : {{ $session->title }}</h3>
    <div class="quiz-container-rank"></div>

    <script src="{{ secure_asset('assets/js/module.js') }}"></script>
    <script>
        const quizSessionId = @json($quizSessionId);

        const apiUrl = "{{ route('quiz-rank', ['id' => $quizSessionId]) }}";

        function fetchSessionRank() {
            $.ajax({
                url: apiUrl,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    toastr.success("Data rangking berhasil diambil", "", { timeOut: 3000 });
                    displaySessionRank(data.sessionRankList);
                },
                error: function(xhr, status, error) {
                    toastr.error("Gagal mengambil data rangking");
                    console.error('Error:', error);
                }
            });
        }

        function displaySessionRank(attempts) {
            const container = document.querySelector('.quiz-container-rank');

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

        Pusher.logToConsole = true;

        var pusher = new Pusher('d54d62cdcd51d9a71282', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('quiz-channel');

        channel.bind('quiz-updated', function(data) {
            fetchSessionRank();
            toastr.success("Data rangking berhasil diperbarui", "", { timeOut: 3000 });
        });
    </script>
@endsection
