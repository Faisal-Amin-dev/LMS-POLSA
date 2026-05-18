@foreach($classrooms as $kelas)
<table>
    <thead>
        <tr>
            <th colspan="{{ $kelas->assignments->count() + 4 }}" style="font-weight: bold; font-size: 14pt;">
                REKAPITULASI NILAI AKADEMIK: {{ strtoupper($kelas->course->nama_mk ?? '-') }}
            </th>
        </tr>
        <tr>
            <th colspan="{{ $kelas->assignments->count() + 4 }}" style="font-size: 10pt; color: #555555;">
                Mata Kuliah: {{ $kelas->course->kode_mk ?? '-' }} | Grup Rombel: {{ $kelas->nama_kelas }} | Tahun Ajaran: {{ $kelas->tahun_akademik }}
            </th>
        </tr>
        <tr></tr> <tr style="background-color: #E2E8F0;">
            <th style="border: 1px solid #000000; font-weight: bold; text-align: center;">No</th>
            <th style="border: 1px solid #000000; font-weight: bold;">NIM</th>
            <th style="border: 1px solid #000000; font-weight: bold;">Nama Mahasiswa</th>
            @foreach($kelas->assignments as $task)
                <th style="border: 1px solid #000000; font-weight: bold; text-align: center;">{{ $task->title }}</th>
            @endforeach
            <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #FEF08A;">Rata-rata</th>
        </tr>
    </thead>
    <tbody>
        @php
            $colScores = [];
            foreach($kelas->assignments as $task) {
                $colScores[$task->id] = [];
            }
            $rowAverages = [];
        @endphp

        @foreach($kelas->mahasiswas as $index => $mhs)
            @php $studentScores = []; @endphp
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000; text-align: left;">'{{ $mhs->nim }}</td>
                <td style="border: 1px solid #000000;">{{ $mhs->nama }}</td>
                
                @foreach($kelas->assignments as $task)
                    @php
                        $submissionGroup = $submissions[$mhs->id][$task->id] ?? null;
                        $record = $submissionGroup ? $submissionGroup->first() : null;
                        $nilai = ($record && $record->nilai !== null) ? $record->nilai : null;
                        
                        if($nilai !== null) {
                            $studentScores[] = $nilai;
                            $colScores[$task->id][] = $nilai;
                        }
                    @endphp
                    <td style="border: 1px solid #000000; text-align: center;">
                        {{ $nilai !== null ? $nilai : '—' }}
                    </td>
                @endforeach

                @php
                    $avgRow = count($studentScores) > 0 ? round(array_sum($studentScores) / count($studentScores), 2) : 0;
                    if(count($studentScores) > 0) $rowAverages[] = $avgRow;
                @endphp
                <td style="border: 1px solid #000000; text-align: center; font-weight: bold; background-color: #FEF9C3;">
                    {{ $avgRow }}
                </td>
            </tr>
        @endforeach

        @if($kelas->mahasiswas->count() > 0 && $kelas->assignments->count() > 0)
            <tr>
                <td colspan="3" style="border: 1px solid #000000; text-align: right; font-weight: bold; background-color: #F9FAFB;">Nilai Tertinggi</td>
                @foreach($kelas->assignments as $task)
                    <td style="border: 1px solid #000000; text-align: center; font-weight: bold; color: #1E40AF; background-color: #F9FAFB;">
                        {{ count($colScores[$task->id]) > 0 ? max($colScores[$task->id]) : '—' }}
                    </td>
                @endforeach
                <td style="border: 1px solid #000000; text-align: center; font-weight: bold; color: #1E40AF; background-color: #FEF9C3;">
                    {{ count($rowAverages) > 0 ? max($rowAverages) : 0 }}
                </td>
            </tr>

            <tr>
                <td colspan="3" style="border: 1px solid #000000; text-align: right; font-weight: bold; background-color: #F9FAFB;">Nilai Terendah</td>
                @foreach($kelas->assignments as $task)
                    <td style="border: 1px solid #000000; text-align: center; font-weight: bold; color: #991B1B; background-color: #F9FAFB;">
                        {{ count($colScores[$task->id]) > 0 ? min($colScores[$task->id]) : '—' }}
                    </td>
                @endforeach
                <td style="border: 1px solid #000000; text-align: center; font-weight: bold; color: #991B1B; background-color: #FEF9C3;">
                    {{ count($rowAverages) > 0 ? min($rowAverages) : 0 }}
                </td>
            </tr>

            <tr>
                <td colspan="3" style="border: 1px solid #000000; text-align: right; font-weight: bold; background-color: #F1F5F9;">Nilai Rata-rata Kelas</td>
                @foreach($kelas->assignments as $task)
                    @php
                        $colAvg = count($colScores[$task->id]) > 0 ? round(array_sum($colScores[$task->id]) / count($colScores[$task->id]), 2) : 0;
                    @endphp
                    <td style="border: 1px solid #000000; text-align: center; font-weight: bold; color: #4338CA; background-color: #F1F5F9;">
                        {{ $colAvg ?: '—' }}
                    </td>
                @endforeach
                @php
                    $totalClassAvg = count($rowAverages) > 0 ? round(array_sum($rowAverages) / count($rowAverages), 2) : 0;
                @endphp
                <td style="border: 1px solid #000000; text-align: center; font-weight: bold; color: #9A3412; background-color: #FEF08A;">
                    {{ $totalClassAvg }}
                </td>
            </tr>
        @endif
    </tbody>
</table>
<table><tr></tr><tr></tr></table>
@endforeach