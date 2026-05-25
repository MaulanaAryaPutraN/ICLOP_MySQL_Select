{{-- Mulai sidebar --}}
<ul class="list pt-3">
    @php $no = 0; @endphp
    @foreach($rows as $row)
        @php
            $no++;
            $active = ($row->id == $detailId) ? 'color:#000; font-weight:bold; text-decoration: underline;' : '';
            $correctCount = DB::table('mysql_student_submissions')
                ->where('user_id', Auth::user()->id)
                ->where('topic_detail_id', $row->id)
                ->where('status', 'true')
                ->where('enroll_id', $enrollId) // tambahkan filter enroll_id
                ->count();
            $isComplete = ($correctCount >= $row->total_question);
        @endphp
        <div class="row px-4 py-2">
            <div class="col" style="padding-bottom: 1rem;">
                <img src="{{ asset('images/book.png') }}" style="height: 24px; margin-right: 10px;">
                <a class="text" style="{{ $active }};" href="{{ route('showTopicDetail') }}?mysqlid={{ $mysqlid }}&start={{ $row->id }}" id="requirement">{{ $row->title }}</a>
                @if($isComplete)
                    <span style="color: #28a745; font-size: 18px; margin-left: 6px; font-weight:bold;" title="Completed">&#10003;</span>
                @endif
            </div>
        </div>
    @endforeach
</ul>
{{-- Akhir sidebar --}}