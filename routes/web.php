<?php

use App\Livewire\Karyawan\KaryawanDashboard;
use App\Livewire\Karyawan\Production\StartProduction;
use App\Livewire\Karyawan\Production\ProductionStatus;
use App\Livewire\Karyawan\Production\ProductionChecksheet;
use App\Livewire\Login;
use App\Livewire\Manajerial\ManajerialDashboard;
use App\Livewire\Manajerial\Production\ProblemApproval;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Karyawan\Production\CheckProductionStatus;
use App\Livewire\Karyawan\QualityCheck\QualityCheckForm;
use App\Livewire\Manajerial\Manajemen\ProductManagement;
use App\Livewire\Production\ProductionReport;
use App\Livewire\Manajerial\Sop\SopApproval;
use App\Http\Controllers\SopPdfController;
use App\Http\Controllers\KaryawanDetailPdfController;
use App\Livewire\Manajerial\OeeDashboard;
use App\Livewire\Manajerial\OeeDetail;
use App\Http\Controllers\OeePdfController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\ProductionReportPdfController;
use app\Livewire\Manajerial\Quality\FishboneAnalysis;
use App\Livewire\Manajerial\Quality\ParetoAnalysis;
use App\Livewire\Manajerial\Quality\QualityAnalysisDashboard;
use App\Livewire\Manajerial\Quality\QualityAnalysisTable;
use App\Http\Controllers\ParetoAnalysisPdfController;
use Illuminate\Http\Request;
use App\Http\Controllers\FishboneAnalysisPdfController;
use App\Livewire\Manajerial\Quality\NGReport;
use App\Http\Controllers\NGReportPdfController;
use App\Livewire\Manajerial\Quality\PDCAManagement;
use App\Livewire\Manajerial\Quality\PDCADetail;
use App\Livewire\Manajerial\Quality\PDCAEdit;





Route::get('/', function () {
    return "App is running! Time: " . now()->format('Y-m-d H:i:s');
});

Route::get('/test-db', function () {
    try {
        \DB::connection()->getPdo();
        return "Database connection: OK";
    } catch (\Exception $e) {
        return "Database connection: ERROR - " . $e->getMessage();
    }
});

Route::get('/test-env', function () {
    return [
        'APP_ENV' => config('app.env'),
        'APP_DEBUG' => config('app.debug'),
        'DB_CONNECTION' => config('database.default'),
        'DB_HOST' => config('database.connections.mysql.host'),
    ];
});

Route::get('/documentation', function () {
    return view('documentation.index');
})->name('documentation');



Route::get('/login', Login::class)->name('login')->middleware('guest');

Route::get('/production/{productionId}/report', ProductionReport::class)->name('production.report');

// Manajerial Routes
// Di dalam group route manajerial
Route::prefix('manajerial')->middleware(['auth', 'role:manajerial'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Manajerial\Dashboard::class)->name('manajerial.dashboard');    
    Route::get('/production/problems', ProblemApproval::class)->name('manajerial.production.problems');
    Route::get('/machines', \App\Livewire\Manajerial\Manajemen\MachineManagement::class)->name('manajerial.machines');
    Route::get('/shifts', \App\Livewire\Manajerial\Manajemen\ShiftManagement::class)->name('manajerial.shifts');
    Route::get('/maintenance-tasks', \App\Livewire\Manajerial\Manajemen\MaintenanceTaskManagement::class)->name('manajerial.maintenance-tasks');
    Route::get('/sop', \App\Livewire\Manajerial\Sop\SopIndex::class)->name('manajerial.sop');
    Route::get('/sop/{id}', \App\Livewire\Manajerial\Sop\SopDetail::class)->name('manajerial.sop.detail');
    Route::get('/products', \App\Livewire\Manajerial\Manajemen\ProductManagement::class)->name('manajerial.products');
    Route::get('/manajerial/sop/approval', SopApproval::class)->name('manajerial.sop.approval');
    Route::get('/manajerial/sop/{id}/pdf', [SopPdfController::class, 'generate'])
    ->name('manajerial.sop.pdf')
    ->middleware(['auth', 'role:manajerial']);
    // Fix: Pindahkan route PDF ke dalam group manajerial dan perbaiki path-nya
    Route::get('/karyawan-report', App\Livewire\Manajerial\KaryawanReport::class)
        ->name('manajerial.karyawan-report');
    
    // Add these PDF routes
    Route::get('/karyawan-report/pdf/{dateFrom}/{dateTo}', [KaryawanDetailPdfController::class, 'generateReport'])
        ->name('manajerial.karyawan.report.pdf');
    Route::get('/karyawan/{userId}/pdf/{dateFrom}/{dateTo}', [KaryawanDetailPdfController::class, 'generate'])
        ->name('manajerial.karyawan.detail.pdf');
    Route::get('/users', \App\Livewire\Manajerial\Manajemen\UserManagement::class)->name('manajerial.users');
    Route::get('/karyawan/detail-pdf/{userId}/{dateFrom}/{dateTo}', [KaryawanDetailPdfController::class, 'generate'])
    ->name('karyawan.detail.pdf');
    
     // Add these new OEE routes
     Route::get('/oee/dashboard', OeeDashboard::class)->name('manajerial.oee.dashboard');
     Route::get('/oee/{machineId}/detail', OeeDetail::class)->name('manajerial.oee.detail');
    
    Route::get('/oee/dashboard/pdf', [OeePdfController::class, 'generateDashboardPdf'])
        ->name('manajerial.oee.dashboard.pdf');
        
    Route::get('/oee/{machineId}/detail/pdf', [OeePdfController::class, 'generateDetailPdf'])
        ->name('manajerial.oee.detail.pdf');
        
        Route::get('/manajerial/production/report', \App\Livewire\Manajerial\Production\ProductionReport::class)
            ->name('manajerial.production.report');
            Route::get('/manajerial/production/report/pdf', [ProductionReportPdfController::class, 'generate'])
                ->name('manajerial.production.report.pdf')
                ->middleware(['auth', 'role:manajerial']);
    
    // Tambahkan route untuk QC/QA Analysis
    Route::get('/quality-analysis', \App\Livewire\Manajerial\Quality\QualityAnalysisDashboard::class)
        ->name('manajerial.quality.analysis');
    // Update route PDF Pareto Analysis
    Route::get('/quality-analysis/pareto', ParetoAnalysis::class)
        ->name('manajerial.quality.pareto');
    Route::get('/quality-analysis/pareto/export-pdf', [ParetoAnalysisPdfController::class, 'generate'])
        ->name('pareto.pdf')
        ->middleware(['auth', 'role:manajerial']);
    // Dalam group route manajerial quality
    Route::get('/quality-analysis/fishbone', \App\Livewire\Manajerial\Quality\FishboneAnalysis::class)
    ->name('manajerial.quality.fishbone');
    Route::get('/pdca', PDCAManagement::class)->name('pdca.index');
    Route::get('/pdca/detail/{id}', PDCADetail::class)->name('pdca.detail');
    Route::get('/pdca/edit/{id}', PDCAEdit::class)->name('pdca.edit');
    
    // Tambahkan route untuk PDCA Management
    Route::get('/quality-analysis/pdca', \App\Livewire\Manajerial\Quality\PDCAManagement::class)
    ->name('manajerial.quality.pdca');
    Route::get('/manajerial/quality/ng-report/pdf', [NGReportPdfController::class, 'generate'])
        ->name('ng-report.pdf')
        ->middleware(['auth', 'role:manajerial']);
    // Update route PDF Fishbone Analysis
    Route::get('/quality-analysis/fishbone/{id}/export-pdf', [FishboneAnalysisPdfController::class, 'generate'])
        ->name('fishbone.pdf')
        ->middleware(['auth', 'role:manajerial']);
        Route::get('/quality/ng-report', NGReport::class)->name('manajerial.quality.ng-report');
});


// Karyawan Routes
Route::prefix('karyawan')->middleware(['auth', 'role:karyawan'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Karyawan\Dashboard::class)->name('karyawan.dashboard');
    Route::get('/production/status', ProductionStatus::class)->name('production.status');
    Route::get('/production/start', StartProduction::class)->name('production.start');
    Route::get('/production/checksheet/{machineId}/{shiftId}', \App\Livewire\Components\ChecksheetTable::class)
        ->name('production.checksheet')
        ->where(['machineId' => '[0-9]+', 'shiftId' => '[0-9]+']);
    Route::get('/karyawan/production/finish/{productionId}', \App\Livewire\Karyawan\Production\FinishProduction::class)
        ->name('production.finish')
        ->middleware(['auth', 'role:karyawan']);
        Route::get('/production/{productionId}/quality-check', QualityCheckForm::class)->name('production.quality-check');
        Route::middleware(['auth'])->group(function () {
            Route::get('/karyawan/history', App\Livewire\Karyawan\ProductionHistory::class)->name('karyawan.history');
        });
});



Route::post('/logout', function() {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->name('logout');




// Route untuk testing preview email OEE Alert
Route::get('/email/preview/oee-alert', function () {
    // Buat data dummy untuk testing
    $machine = new \App\Models\Machine();
    $machine->id = 1;
    $machine->name = 'Mesin Testing';
    
    $production = new \App\Models\Production();
    $production->batch_number = 'TEST-001';
    $production->start_time = now();
    
    // Buat dummy product
    $product = new \App\Models\Product();
    $product->name = 'Produk Testing';
    $production->product = $product;
    
    // Buat dummy shift
    $shift = new \App\Models\Shift();
    $shift->name = 'Shift Pagi';
    $production->shift = $shift;
    
    // Data OEE
    $oeeScore = 75.5;
    $targetOee = 85.0;
    $difference = $targetOee - $oeeScore;
    $url = url('/manajerial/oee/1/detail');
    
    // Render view email
    return view('emails.oee-alert', [
        'machine' => $machine,
        'oeeScore' => $oeeScore,
        'targetOee' => $targetOee,
        'difference' => $difference,
        'url' => $url,
        'production' => $production
    ]);
})->name('email.preview.oee-alert');

// Route untuk testing kirim WhatsApp OEE Alert
Route::get('/whatsapp/send-test/oee-alert', function () {
    // Buat data dummy untuk testing
    $machine = new \App\Models\Machine();
    $machine->id = 1;
    $machine->name = 'Mesin Testing';
    
    $production = new \App\Models\Production();
    $production->batch_number = 'TEST-001';
    $production->start_time = now();
    
    // Buat dummy product
    $product = new \App\Models\Product();
    $product->name = 'Produk Testing';
    $production->product = $product;
    
    // Buat dummy shift
    $shift = new \App\Models\Shift();
    $shift->name = 'Shift Pagi';
    $production->shift = $shift;
    
    // Data OEE
    $oeeScore = 75.5;
    $targetOee = 85.0;
    
    // Kirim notifikasi ke WhatsApp yang ditentukan
    $testPhone = request('phone', '6285187826862'); // Ganti dengan nomor WhatsApp untuk testing
    
    $whatsappService = app(\App\Services\WhatsAppService::class);
    $result = $whatsappService->sendOeeAlert($testPhone, $machine, $oeeScore, $targetOee, $production);
    
    if ($result) {
        return "WhatsApp OEE Alert telah dikirim ke $testPhone";
    } else {
        return "Gagal mengirim WhatsApp OEE Alert ke $testPhone. Periksa log untuk detail.";
    }
})->name('whatsapp.send-test.oee-alert');

// Tambahkan route untuk testing kirim pesan WhatsApp sederhana
Route::get('/whatsapp/send-simple-test', function () {
    $phone = request('phone', '6285187826862');
    
    try {
        $response = Http::withHeaders([
            'Authorization' => config('services.fonnte.api_key')
        ])->withoutVerifying()
          ->post('https://api.fonnte.com/send', [
            'target' => $phone,
            'message' => 'Ini adalah pesan test dari EzzyIndustri. Waktu: ' . now()->format('H:i:s'),
            'delay' => 1,
            'countryCode' => '62'
        ]);
        
        return "Pesan test sederhana dikirim ke $phone<br><br>Respons:<br><pre>" . 
               json_encode($response->json(), JSON_PRETTY_PRINT) . "</pre>";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
})->name('whatsapp.send-simple-test');

Route::post('/upload-image', [ImageUploadController::class, 'store'])
    ->name('upload.image')
    ->middleware(['auth', 'web']); // Tambah middleware auth dan web

Route::post('/store-chart-image', function(Request $request) {
    session(['pareto_chart_image' => $request->chartImage]);
    return response()->json(['success' => true]);
})->middleware('auth');
