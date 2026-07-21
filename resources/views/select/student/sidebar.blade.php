{{-- Sidebar checklist subtopik --}}
<ul class="list pt-3">
    @php $prevComplete = true; @endphp
    @foreach($rows as $row)
        @php
            $active = ($row->id == $detailId) ? 'color:#000; font-weight:bold; text-decoration:underline;' : '';

            // Selesai = semua tugas di subtopik ini sudah benar
            $correctTugas = $row->total_tugas > 0
                ? DB::table('select_student_submissions')
                    ->where('user_id', Auth::id())
                    ->where('topic_detail_id', $row->id)
                    ->where('submission_type', 'tugas')
                    ->where('status', 'true')
                    ->where('enroll_id', $enrollId)
                    ->distinct('answer_number')->count('answer_number')
                : 0;

            $isComplete = ($row->total_tugas > 0 && $correctTugas >= $row->total_tugas);

            // Percobaan selesai?
            $correctPercobaan = $row->total_percobaan > 0
                ? DB::table('select_student_submissions')
                    ->where('user_id', Auth::id())
                    ->where('topic_detail_id', $row->id)
                    ->where('submission_type', 'percobaan')
                    ->where('status', 'true')
                    ->where('enroll_id', $enrollId)
                    ->distinct('answer_number')->count('answer_number')
                : $row->total_percobaan;

            $percobaanDone = ($row->total_percobaan == 0 || $correctPercobaan >= $row->total_percobaan);

            // Subbab ini bisa diakses hanya jika subbab sebelumnya sudah selesai
            $isLocked = !$prevComplete;

            // Update untuk iterasi berikutnya
            $prevComplete = $isComplete;
        @endphp
        <div class="row px-4 py-2">
            <div class="col" style="padding-bottom:1rem;">
                <img src="{{ asset('images/book.png') }}" style="height:24px; margin-right:10px;">

                @if($isLocked)
                    {{-- Terkunci: tampilkan teks biasa + ikon gembok --}}
                    <span class="text" style="color:#aaa; cursor:not-allowed;" title="Selesaikan subbab sebelumnya terlebih dahulu">
                        {{ $row->title }}
                    </span>
                    <span style="color:#aaa; font-size:13px; margin-left:6px;" title="Terkunci">&#128274;</span>
                @else
                    <a class="text" style="{{ $active }}"
                       href="{{ route('v2.showTopicDetail') }}?mysqlid={{ $mysqlid }}&start={{ $row->id }}">
                        {{ $row->title }}
                    </a>

                    {{-- Status badge --}}
                    @if($isComplete)
                        <span style="color:#28a745; font-size:16px; margin-left:6px;" title="Selesai">&#10003;</span>
                    @elseif($percobaanDone && !$isComplete)
                        <span class="badge ms-1" style="background:#0066ff; color:#fff; font-size:10px;">Tugas</span>
                    @elseif(!$percobaanDone)
                        <span class="badge ms-1" style="background:#f0ad4e; color:#fff; font-size:10px;">Percobaan</span>
                    @endif
                @endif
            </div>
        </div>
    @endforeach
</ul>