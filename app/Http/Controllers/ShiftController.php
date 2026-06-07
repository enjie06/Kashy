<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShiftController extends Controller
{
    // ============================================================
    // KONFIGURASI SHIFT
    // ============================================================
    const SHIFTS = [
        'pagi'  => ['start' => '09:00', 'end' => '17:00', 'mulai_jam' => 9,  'selesai_jam' => 17],
        'malam' => ['start' => '15:00', 'end' => '23:00', 'mulai_jam' => 15, 'selesai_jam' => 23],
    ];

    const SHIFTS_KASIR = [
    'pagi'  => ['start' => '09:00', 'end' => '17:00', 'mulai_jam' => 9,  'selesai_jam' => 17],
    'malam' => ['start' => '15:00', 'end' => '23:00', 'mulai_jam' => 15, 'selesai_jam' => 23],
];

    // ============================================================
    // KARYAWAN — handleAbsensi
    // ============================================================
    public function handleAbsensi(Request $request)
    {
        $user  = Auth::user();
        $today = date('Y-m-d');
        $now   = now();

        if ($user->role === 'kasir') {
            return $this->handleAbsensiKasir($request);
        }

        $shiftType = $request->input('shift_type');
        if (!$shiftType || !isset(self::SHIFTS[$shiftType])) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih shift terlebih dahulu sebelum absen.'
            ], 422);
        }

        $shiftCfg = self::SHIFTS[$shiftType];

        $nowHour = (int) $now->format('H');
        if ($nowHour < $shiftCfg['mulai_jam'] || $nowHour >= $shiftCfg['selesai_jam']) {
            return response()->json([
                'success' => false,
                'message' => "Absensi hanya bisa dilakukan pada jam {$shiftCfg['start']} – {$shiftCfg['end']}."
            ], 422);
        }

        $attendance = Attendance::where('user_id', $user->id)
                                ->whereDate('created_at', $today)
                                ->first();

        if (!$attendance || !$attendance->check_in) {
            $terlambatInfo = $this->hitungTerlambat($now, $shiftCfg['start']);

            if (!$attendance) {
                $attendance = Attendance::create([
                    'user_id'      => $user->id,
                    'check_in'     => $now,
                    'check_out'    => null,
                    'status'       => 'hadir',
                    'shift_status' => 'aktif',
                ]);
            } else {
                $attendance->update([
                    'check_in'     => $now,
                    'shift_status' => 'aktif',
                ]);
            }

            $shiftNama = ucfirst($shiftType);
            $message   = $terlambatInfo['terlambat']
                ? "⚠️ Shift {$shiftNama} dimulai! Anda terlambat {$terlambatInfo['menit']} menit."
                : "✅ Shift {$shiftNama} dimulai! Tepat waktu.";

            return response()->json([
                'success'         => true,
                'action'          => 'masuk',
                'message'         => $message,
                'check_in'        => $now->format('H:i'),
                'shift_type'      => $shiftType,
                'shift_type_nama' => $shiftNama,
                'shift_start'     => $shiftCfg['start'],
                'shift_end'       => $shiftCfg['end'],
                'terlambat'       => $terlambatInfo['terlambat'],
                'terlambat_menit' => $terlambatInfo['menit'],
            ]);
        }

        if ($attendance->check_in && !$attendance->check_out) {
            $attendance->update([
                'check_out'    => $now,
                'shift_status' => 'selesai',
            ]);

            return response()->json([
                'success'   => true,
                'action'    => 'pulang',
                'message'   => '✅ Shift selesai! Terima kasih.',
                'check_out' => $now->format('H:i'),
                'check_in'  => $attendance->check_in->format('H:i'),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Shift hari ini sudah selesai.'
        ], 400);
    }

    // ============================================================
    // KASIR — handleAbsensi
    // ============================================================
    private function handleAbsensiKasir(Request $request)
    {
        $user  = Auth::user();
        $today = date('Y-m-d');
        $now   = now();

        $shiftType = $request->input('shift_type');
        if (!$shiftType || !isset(self::SHIFTS_KASIR[$shiftType])) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih shift terlebih dahulu sebelum absen.'
            ], 422);
        }

        $shiftCfg  = self::SHIFTS_KASIR[$shiftType];
        $attendance = Attendance::where('user_id', $user->id)
                                ->whereDate('created_at', $today)
                                ->first();

        // ── ABSEN MASUK ──
        if (!$attendance || !$attendance->check_in) {
            $terlambatInfo = $this->hitungTerlambat($now, $shiftCfg['start']);

            if (!$attendance) {
                $attendance = Attendance::create([
                    'user_id'      => $user->id,
                    'check_in'     => $now,
                    'check_out'    => null,
                    'status'       => 'hadir',
                    'shift_status' => 'aktif',
                ]);
            } else {
                $attendance->update([
                    'check_in'     => $now,
                    'shift_status' => 'aktif',
                ]);
            }

            $shiftNama = ucfirst($shiftType);
            $message   = $terlambatInfo['terlambat']
                ? "⚠️ Shift {$shiftNama} dimulai! Anda terlambat {$terlambatInfo['menit']} menit."
                : "✅ Shift {$shiftNama} dimulai! Tepat waktu.";

            return response()->json([
                'success'          => true,
                'action'           => 'masuk',
                'message'          => $message,
                'check_in'         => $now->format('H:i'),
                'shift_type'       => $shiftType,
                'shift_type_nama'  => $shiftNama,
                'terlambat'        => $terlambatInfo['terlambat'],
                'terlambat_menit'  => $terlambatInfo['menit'],
                'redirect'         => route('kasir.shiftkasir'),
            ]);
        }

        // ── ABSEN PULANG ──
        if ($attendance->check_in && !$attendance->check_out) {
            $attendance->update([
                'check_out'    => $now,
                'shift_status' => 'selesai',
            ]);

            return response()->json([
                'success'   => true,
                'action'    => 'pulang',
                'message'   => '✅ Shift selesai! Terima kasih.',
                'check_out' => $now->format('H:i'),
                'check_in'  => $attendance->check_in->format('H:i'),
                'redirect'  => route('dashboard-kasir'),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Shift hari ini sudah selesai.'
        ], 400);
    }

    // ============================================================
    // KASIR — bukaShift
    // Validasi: kasir wajib sudah absen masuk hari ini
    // ============================================================
    public function bukaShift(Request $request)
    {
        $user  = Auth::user();
        $today = date('Y-m-d');

        // ── Cek absensi masuk ──
        $attendance = Attendance::where('user_id', $user->id)
                                ->whereDate('created_at', $today)
                                ->whereNotNull('check_in')
                                ->first();

        if (!$attendance) {
            return response()->json([
                'success'  => false,
                'message'  => 'Anda belum melakukan absensi masuk hari ini. Silakan absen terlebih dahulu.',
                'need_absen' => true,
                'redirect' => route('kasir.absensikasir'),
            ], 403);
        }

        $saldoAwal = $request->input('saldo_awal', 0);

        // Cek apakah shift kas sudah aktif
        $existingShift = Shift::where('user_id', $user->id)
                              ->whereDate('created_at', $today)
                              ->whereNull('waktu_tutup')
                              ->first();

        if ($existingShift) {
            return response()->json([
                'success' => false,
                'message' => 'Shift kas sudah aktif hari ini.',
            ], 400);
        }

        $shift = Shift::create([
            'user_id'    => $user->id,
            'saldo_awal' => $saldoAwal,
            'waktu_buka' => now()->format('H:i'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Shift berhasil dibuka!',
            'shift'    => $shift,
            'redirect' => route('dashboard-kasir'),
        ]);
    }

    // ============================================================
    // KASIR — tutupShift
    // ============================================================
    public function tutupShift(Request $request)
    {
        $user  = Auth::user();
        $today = date('Y-m-d');

        $shift = Shift::where('user_id', $user->id)
                      ->whereDate('created_at', $today)
                      ->whereNull('waktu_tutup')
                      ->first();

        if (!$shift) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada shift aktif yang bisa ditutup.',
            ], 404);
        }

        $uangTunaiAktual = $request->input('uang_tunai_aktual', 0);

        $shift->update([
            'waktu_tutup'       => now()->format('H:i'),
            'uang_tunai_aktual' => $uangTunaiAktual,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Shift berhasil ditutup!',
            'redirect' => route('kasir.absensikasir'),
        ]);
    }

    // ============================================================
    // KASIR — minSaldo
    // ============================================================
    public function getMinSaldo()
    {
        return response()->json(['min_saldo' => 50000]);
    }

    // ============================================================
    // KASIR — shiftStatus (untuk dashboard kasir)
    // ============================================================
    public function shiftStatusKasir()
    {
        $user  = Auth::user();
        $today = date('Y-m-d');

        $shift = Shift::where('user_id', $user->id)
                      ->whereDate('created_at', $today)
                      ->whereNull('waktu_tutup')
                      ->first();

        if (!$shift) {
            return response()->json(['shift_active' => false, 'shift' => null]);
        }

        return response()->json([
            'shift_active' => true,
            'shift'        => $shift,
        ]);
    }

    // ============================================================
    // KARYAWAN — cekStatus
    // ============================================================
    public function cekStatusAbsensi()
    {
        return $this->cekStatus();
    }

    public function cekStatus()
    {
        $user  = Auth::user();
        $today = date('Y-m-d');
        $now   = now();

        if ($user->role === 'kasir') {
            return $this->cekStatusKasir();
        }

        $attendance = Attendance::where('user_id', $user->id)
                                ->whereDate('created_at', $today)
                                ->first();

        // Auto-checkout karyawan
        if (
            $attendance &&
            $attendance->shift_status === 'aktif' &&
            $attendance->check_in &&
            !$attendance->check_out
        ) {
            $shiftInfo    = $this->getShiftFromTime($attendance->check_in);
            $shiftEndHour = self::SHIFTS[$shiftInfo['type']]['selesai_jam'] ?? 23;

            if ((int) $now->format('H') >= $shiftEndHour) {
                $autoCheckout = $now->copy()->setTime($shiftEndHour, 0, 0);
                $attendance->update([
                    'check_out'    => $autoCheckout,
                    'shift_status' => 'selesai',
                ]);
            }
        }

        $attendance = Attendance::where('user_id', $user->id)
                                ->whereDate('created_at', $today)
                                ->first();

        if (!$attendance || !$attendance->check_in) {
            return response()->json([
                'shift_status'    => 'tidak_aktif',
                'check_in'        => null,
                'check_out'       => null,
                'shift_type'      => null,
                'shift_type_nama' => null,
                'shift_start'     => null,
                'shift_end'       => null,
                'terlambat'       => false,
                'nama'            => $user->name,
                'posisi'          => ucfirst($user->role),
            ]);
        }

        $shiftInfo = $this->getShiftFromTime($attendance->check_in);

        return response()->json([
            'shift_status'    => $attendance->shift_status,
            'check_in'        => $attendance->check_in->format('H:i'),
            'check_out'       => $attendance->check_out ? $attendance->check_out->format('H:i') : null,
            'shift_type'      => $shiftInfo['type'],
            'shift_type_nama' => $shiftInfo['type_nama'],
            'shift_start'     => $shiftInfo['start'],
            'shift_end'       => $shiftInfo['end'],
            'terlambat'       => $shiftInfo['terlambat'],
            'terlambat_menit' => $shiftInfo['terlambat_menit'],
            'nama'            => $user->name,
            'posisi'          => ucfirst($user->role),
        ]);
    }

    // ============================================================
    // KASIR — cekStatus absensi
    // ============================================================
    public function cekStatusKasir()
    {
        $user  = Auth::user();
        $today = date('Y-m-d');
        $now   = now();

        $attendance = Attendance::where('user_id', $user->id)
                                ->whereDate('created_at', $today)
                                ->first();

        // Auto-checkout kasir
        if (
            $attendance &&
            $attendance->shift_status === 'aktif' &&
            $attendance->check_in &&
            !$attendance->check_out
        ) {
            $shiftInfo    = $this->getShiftFromTimeKasir($attendance->check_in);
            $shiftEndHour = self::SHIFTS_KASIR[$shiftInfo['type']]['selesai_jam'] ?? 23;

            if ((int) $now->format('H') >= $shiftEndHour) {
                $autoCheckout = $now->copy()->setTime($shiftEndHour, 0, 0);
                $attendance->update([
                    'check_out'    => $autoCheckout,
                    'shift_status' => 'selesai',
                ]);
                $attendance = $attendance->fresh();
            }
        }

        $checkIn  = $attendance?->check_in  ? $attendance->check_in->format('H:i')  : null;
        $checkOut = $attendance?->check_out ? $attendance->check_out->format('H:i') : null;

        $terlambat      = false;
        $terlambatMenit = 0;

        if ($attendance && $attendance->check_in) {
            $shiftInfo      = $this->getShiftFromTimeKasir($attendance->check_in);
            $terlambat      = $shiftInfo['terlambat'];
            $terlambatMenit = $shiftInfo['terlambat_menit'];
        }

        return response()->json([
            'shift_status'    => $attendance?->shift_status ?? 'tidak_aktif',
            'check_in'        => $checkIn,
            'check_out'       => $checkOut,
            'terlambat'       => $terlambat,
            'terlambat_menit' => $terlambatMenit,
        ]);
    }

    // ============================================================
    // FULL HISTORY
    // ============================================================
    public function getFullHistory(Request $request)
    {
        $user     = Auth::user();
        $isKasir  = ($user->role === 'kasir');

        $joinDate = Carbon::parse($user->created_at)->startOfDay();
        $today    = Carbon::today();

        if ($request->filled('date')) {
            $startDate = Carbon::parse($request->date)->startOfDay();
            $endDate   = Carbon::parse($request->date)->endOfDay();
        } elseif ($request->filled('year') && $request->filled('month')) {
            $startDate = Carbon::create($request->year, $request->month, 1)->startOfDay();
            $endDate   = $startDate->copy()->endOfMonth();
            if ($endDate->gt($today)) $endDate = $today->copy();
            if ($startDate->lt($joinDate)) $startDate = $joinDate->copy();
        } else {
            $startDate = $today->copy()->subDays(29);
            if ($startDate->lt($joinDate)) $startDate = $joinDate->copy();
            $endDate = $today->copy();
        }

        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('created_at', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay()
            ])
            ->get()
            ->keyBy(fn($a) => Carbon::parse($a->created_at)->format('Y-m-d'));

        $result  = [];
        $current = $startDate->copy()->startOfDay();

        while ($current->lte($endDate->copy()->startOfDay())) {
            $dateKey    = $current->format('Y-m-d');
            $attendance = $attendances->get($dateKey);

            if ($attendance) {
                if (!$attendance->check_in) {
                    $result[] = [
                        'id'          => $attendance->id,
                        'date'        => $current->translatedFormat('l, d F Y'),
                        'date_raw'    => $dateKey,
                        'check_in'    => '-',
                        'check_out'   => '-',
                        'status'      => 'Tidak Hadir',
                        'shift_type'  => null,
                        'shift_start' => '-',
                        'shift_end'   => '-',
                    ];
                } else {
                    $shiftInfo = $isKasir
                        ? $this->getShiftFromTimeKasir($attendance->check_in)
                        : $this->getShiftFromTime($attendance->check_in);

                    $result[] = [
                        'id'          => $attendance->id,
                        'date'        => $current->translatedFormat('l, d F Y'),
                        'date_raw'    => $dateKey,
                        'check_in'    => $attendance->check_in->format('H:i'),
                        'check_out'   => $attendance->check_out ? $attendance->check_out->format('H:i') : '-',
                        'status'      => $shiftInfo['terlambat'] ? 'Terlambat' : 'Hadir',
                        'shift_type'  => $shiftInfo['type'],
                        'shift_start' => $shiftInfo['start'],
                        'shift_end'   => $shiftInfo['end'],
                    ];
                }
            } else {
                if ($current->lt($today)) {
                    $result[] = [
                        'id'          => null,
                        'date'        => $current->translatedFormat('l, d F Y'),
                        'date_raw'    => $dateKey,
                        'check_in'    => '-',
                        'check_out'   => '-',
                        'status'      => 'Tidak Hadir',
                        'shift_type'  => null,
                        'shift_start' => '-',
                        'shift_end'   => '-',
                    ];
                }
            }

            $current->addDay();
        }

        $result = array_reverse($result);

        if ($request->filled('status') && $request->status !== 'semua') {
            $statusMap = [
                'hadir'       => 'Hadir',
                'terlambat'   => 'Terlambat',
                'tidak_hadir' => 'Tidak Hadir',
            ];
            $result = array_values(array_filter($result, function ($item) use ($request, $statusMap) {
                return $item['status'] === ($statusMap[$request->status] ?? '');
            }));
        }

        // Stats
        $allAttendances = Attendance::where('user_id', $user->id)
            ->where('created_at', '>=', $joinDate)
            ->get()
            ->keyBy(fn($a) => Carbon::parse($a->created_at)->format('Y-m-d'));

        $stats    = ['hadir' => 0, 'terlambat' => 0, 'tidak_hadir' => 0];
        $loopDate = $joinDate->copy();

        while ($loopDate->lt($today)) {
            $key = $loopDate->format('Y-m-d');
            $att = $allAttendances->get($key);

            if ($att && $att->check_in) {
                $si = $isKasir
                    ? $this->getShiftFromTimeKasir($att->check_in)
                    : $this->getShiftFromTime($att->check_in);
                $si['terlambat'] ? $stats['terlambat']++ : $stats['hadir']++;
            } else {
                $stats['tidak_hadir']++;
            }

            $loopDate->addDay();
        }

        return response()->json([
            'histories' => $result,
            'stats'     => $stats,
        ]);
    }

    // ============================================================
    // RECENT HISTORY
    // ============================================================
    public function getRecentHistory()
    {
        $user    = Auth::user();
        $isKasir = ($user->role === 'kasir');

        $histories = Attendance::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $result = [];
        foreach ($histories as $history) {
            if (!$history->check_in) {
                $result[] = [
                    'date'      => $history->created_at->translatedFormat('d M Y'),
                    'check_in'  => '-',
                    'check_out' => '-',
                    'status'    => 'Tidak Hadir',
                ];
                continue;
            }

            $shiftInfo = $isKasir
                ? $this->getShiftFromTimeKasir($history->check_in)
                : $this->getShiftFromTime($history->check_in);

            $result[] = [
                'date'      => $history->created_at->translatedFormat('d M Y'),
                'check_in'  => $history->check_in->format('H:i'),
                'check_out' => $history->check_out ? $history->check_out->format('H:i') : '--',
                'status'    => $shiftInfo['terlambat'] ? 'Terlambat' : 'Hadir',
            ];
        }

        return response()->json($result);
    }

    // ============================================================
    // HELPERS
    // ============================================================
    private function getShiftFromTime($checkInTime)
    {
        $hour = (int) $checkInTime->format('H');

        if ($hour >= 6 && $hour < 14) {
            $shiftType     = 'pagi';
            $shiftTypeNama = 'Pagi';
        } else {
            $shiftType     = 'malam';
            $shiftTypeNama = 'Malam';
        }

        $cfg            = self::SHIFTS[$shiftType];
        $checkInHourMin = $checkInTime->format('H:i:s');
        $shiftStart     = $cfg['start'] . ':00';
        $terlambatMenit = (strtotime($checkInHourMin) - strtotime($shiftStart)) / 60;
        $terlambat      = $terlambatMenit > 15;

        return [
            'type'            => $shiftType,
            'type_nama'       => $shiftTypeNama,
            'start'           => $cfg['start'],
            'end'             => $cfg['end'],
            'terlambat'       => $terlambat,
            'terlambat_menit' => $terlambat ? round($terlambatMenit) : 0,
        ];
    }

    private function getShiftFromTimeKasir($checkInTime)
    {
        $hour = (int) $checkInTime->format('H');

        if ($hour >= 9 && $hour < 17) {
            $shiftType     = 'pagi';
            $shiftTypeNama = 'Pagi';
        } else {
            $shiftType     = 'malam';
            $shiftTypeNama = 'Malam';
        }

        $cfg            = self::SHIFTS_KASIR[$shiftType];
        $checkInHourMin = $checkInTime->format('H:i:s');
        $shiftStart     = $cfg['start'] . ':00';
        $terlambatMenit = (strtotime($checkInHourMin) - strtotime($shiftStart)) / 60;
        $terlambat      = $terlambatMenit > 15;

        return [
            'type'            => $shiftType,
            'type_nama'       => $shiftTypeNama,
            'start'           => $cfg['start'],
            'end'             => $cfg['end'],
            'terlambat'       => $terlambat,
            'terlambat_menit' => $terlambat ? round($terlambatMenit) : 0,
        ];
    }

    private function hitungTerlambat($checkInTime, $shiftStart)
    {
        $startTimestamp = strtotime($checkInTime->format('Y-m-d') . ' ' . $shiftStart . ':00');
        $checkInStamp   = $checkInTime->timestamp;
        $selisihMenit   = ($checkInStamp - $startTimestamp) / 60;
        $terlambat      = $selisihMenit > 15;

        return [
            'terlambat' => $terlambat,
            'menit'     => $terlambat ? round($selisihMenit) : 0,
        ];
    }
}