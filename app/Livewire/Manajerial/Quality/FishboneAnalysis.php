<?php

namespace App\Livewire\Manajerial\Quality;

use Livewire\Component;
use App\Models\FishboneAnalysis as FishboneModel;
use App\Models\FishboneCause;
use App\Models\NGReport;
use App\Models\QualityCheck;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;


class FishboneAnalysis extends Component
{
    use WithFileUploads;
    public $analyses;
    public $ngReports;
    public $qualityChecks;
    public $cloudinary_url;
    public $cloudinary_id;
    
    // Add this property
    public $statusNotes = '';
    public $causes = [];
    public $currentAnalysisId = null;
    public $selectedDiagram = null;
    public function viewDiagram($id)
    
    
    {
        try {
            $this->selectedAnalysis = \App\Models\FishboneAnalysis::find($id);
            
            if ($this->selectedAnalysis && $this->selectedAnalysis->cloudinary_url) {
                $this->dispatch('showDiagramModal');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load diagram');
        }
    }
    public function mount()
    {
        $this->ngReports = NGReport::all();
        $this->qualityChecks = QualityCheck::all();
        $this->analyses = FishboneModel::all();
        
        // Initialize causes array
        $this->causes = [
            'Man' => [],
            'Machine' => [],
            'Method' => [],
            'Material' => [],
            'Measurement' => [],
            'Environment' => []
        ];
    }

    public function addCause($category)
    {
        $this->causes[$category][] = [
            'cause' => '',
            'description' => '',
            'pic' => '',
            'target_date' => ''
        ];
    }

    public function removeCause($category, $index)
    {
        unset($this->causes[$category][$index]);
        $this->causes[$category] = array_values($this->causes[$category]);
    }
    
    // Form properties
    public $selectedNGReport;
    public $selectedQualityCheck;
    public $title;
    public $problemStatement;
    public $analysisDate;
    
    protected $listeners = [
        'uploadDiagram' => 'uploadDiagram',
        'deleteAnalysis' => 'deleteAnalysis'  // Tambahkan ini
    ];

    public function uploadDiagram($url, $public_id)
    {
        Log::info('Starting uploadDiagram process', [
            'url' => $url,
            'public_id' => $public_id,
            'editMode' => $this->editMode,
            'editId' => $this->editId
        ]);
    
        try {
            if ($this->editMode && $this->editId) {
                // Handle edit case
                $analysis = FishboneModel::find($this->editId);
                if (!$analysis) {
                    throw new \Exception("Analysis not found: {$this->editId}");
                }
                
                $updated = $analysis->update([
                    'cloudinary_url' => $url,
                    'cloudinary_id' => $public_id
                ]);
    
                $message = 'Diagram updated successfully';
            } else {
                // Handle new analysis case
                $analysis = FishboneModel::latest()->first();
                
                if (!$analysis) {
                    throw new \Exception("New analysis not found");
                }
    
                Log::info('Updating new analysis with diagram', [
                    'analysis_id' => $analysis->id,
                    'url' => $url
                ]);
    
                $updated = $analysis->update([
                    'cloudinary_url' => $url,
                    'cloudinary_id' => $public_id
                ]);
    
                if (!$updated) {
                    throw new \Exception('Failed to update new analysis with diagram');
                }
    
                $message = 'Diagram saved successfully';
            }
    
            $this->dispatch('uploadComplete', [
                'status' => 'success',
                'message' => $message
            ]);
    
        } catch (\Exception $e) {
            Log::error('Failed to handle diagram upload', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->dispatch('uploadComplete', [
                'status' => 'error',
                'message' => 'Failed to process diagram: ' . $e->getMessage()
            ]);
        }
    }

    // Tambahkan property di bagian atas class
    public $isUploading = false;
    
    // Modifikasi method save
    public function save()
    {
        Log::info('Starting save analysis process', [
            'editMode' => $this->editMode,
            'editId' => $this->editId
        ]);
    
        try {
            // Store edit state in session
            session(['temp_edit_mode' => $this->editMode]);
            session(['temp_edit_id' => $this->editId]);
    
            $this->validate([
                'title' => 'required',
                'problemStatement' => 'required',
                'analysisDate' => 'required|date',
            ]);
    
            // Generate diagram baru sekali saja
            $this->generateDiagram();
            Log::info('Generated new diagram before save');
            
            // Tunggu sebentar agar mermaid selesai render
            sleep(1);
            
            // Dispatch saveDiagram sekali saja
            $this->dispatch('saveDiagram');
            Log::info('Dispatching saveDiagram for new diagram');
    
            // Tunggu proses upload
            sleep(2);
    
            if ($this->editMode) {
                Log::info('Updating existing analysis', ['id' => $this->editId]);
                $analysis = FishboneModel::find($this->editId);
                
                // Update setelah upload diagram baru selesai
                $analysis->update([
                    'ng_report_id' => $this->selectedNGReport,
                    'quality_check_id' => $this->selectedQualityCheck,
                    'title' => $this->title,
                    'problem_statement' => $this->problemStatement,
                    'analysis_date' => $this->analysisDate
                ]);
    
                Log::info('Deleting existing causes');
                $analysis->causes()->delete();
            }
            
            // Validasi form utama
            Log::info('Validating main form data');
            $this->validate([
                'title' => 'required',
                'problemStatement' => 'required',
                'analysisDate' => 'required|date',
            ]);
    
            // Validasi root causes
            Log::info('Validating causes data', [
                'categories' => array_keys($this->causes),
                'total_causes' => collect($this->causes)->flatten(1)->count()
            ]);
    
            $hasEmptyCategory = true;
            foreach ($this->causes as $category => $categoryCauses) {
                if (empty($categoryCauses)) {
                    Log::warning("Empty category found", ['category' => $category]);
                    session()->flash('error', "Category {$category} must have at least one cause");
                    return;
                }
                
                foreach ($categoryCauses as $index => $cause) {
                    if (empty($cause['cause'])) {
                        Log::warning("Empty cause found", [
                            'category' => $category,
                            'index' => $index
                        ]);
                        session()->flash('error', "All causes in {$category} must be filled");
                        return;
                    }
                }
                $hasEmptyCategory = false;
            }
    
            Log::info('All validations passed, dispatching saveDiagram event');
            $this->dispatch('saveDiagram');
    
            if ($this->editMode) {
                Log::info('Updating existing analysis', ['id' => $this->editId]);
                $analysis = FishboneModel::find($this->editId);
                $analysis->update([
                    'ng_report_id' => $this->selectedNGReport,
                    'quality_check_id' => $this->selectedQualityCheck,
                    'title' => $this->title,
                    'problem_statement' => $this->problemStatement,
                    'analysis_date' => $this->analysisDate,
                    'cloudinary_url' => $this->cloudinary_url,
                    'cloudinary_id' => $this->cloudinary_id
                ]);
    
                Log::info('Deleting existing causes');
                $analysis->causes()->delete();
            } else {
                Log::info('Creating new analysis');
                $analysis = FishboneModel::create([
                    'ng_report_id' => $this->selectedNGReport,
                    'quality_check_id' => $this->selectedQualityCheck,
                    'title' => $this->title,
                    'problem_statement' => $this->problemStatement,
                    'analysis_date' => $this->analysisDate,
                    'created_by' => Auth::user()->name,
                    'status' => 'draft',
                    'cloudinary_url' => $this->cloudinary_url,
                    'cloudinary_id' => $this->cloudinary_id
                ]);
            }
    
            // Create/recreate causes
            foreach ($this->causes as $category => $categoryCauses) {
                foreach ($categoryCauses as $cause) {
                    if (!empty($cause['cause'])) {
                        Log::info('Creating cause', [
                            'category' => $category,
                            'cause' => $cause['cause']
                        ]);
                        FishboneCause::create([
                            'fishbone_analysis_id' => $analysis->id,
                            'category' => $category,
                            'cause' => $cause['cause'],
                            'description' => $cause['description'] ?? '',
                            'pic' => $cause['pic'] ?? '',
                            'target_date' => !empty($cause['target_date']) ? $cause['target_date'] : null
                        ]);
                    }
                }
            }
    
            // Reset form and refresh data
            $this->resetForm();
            $this->analyses = FishboneModel::all();
            
            $message = $this->editMode ? 'updated' : 'created';
            session()->flash('message', "Fishbone Analysis {$message} successfully.");
    
        } catch (\Exception $e) {
            Log::error('Save failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Failed to save analysis: ' . $e->getMessage());
        } finally {
            $this->isUploading = false;
        }
    }

    public function render()
    {
        return view('livewire.manajerial.quality.fishbone-analysis');
    }

    public function confirmCause($category, $index)
    {
        if (!empty($this->causes[$category][$index]['cause'])) {
            $this->dispatch('causesUpdated', [
                'problem' => $this->problemStatement ?? 'Problem Statement',
                'causes' => $this->causes,
                'isConfirmed' => true
            ]);
        }
    }
    
    public $showDiagram = false;
    public $diagramCode = '';
    
    public function generateDiagram()
    {
        // Validasi form utama
        if (empty($this->selectedNGReport)) {
            session()->flash('error', 'Please select NG Report Reference');
            return;
        }

        if (empty($this->selectedQualityCheck)) {
            session()->flash('error', 'Please select Quality Check Reference');
            return;
        }

        if (empty($this->title)) {
            session()->flash('error', 'Analysis title is required');
            return;
        }

        if (empty($this->problemStatement)) {
            session()->flash('error', 'Problem statement is required');
            return;
        }

        if (empty($this->analysisDate)) {
            session()->flash('error', 'Analysis date is required');
            return;
        }

        // Validasi root causes
        $emptyCategoryFound = false;
        foreach ($this->causes as $category => $categoryCauses) {
            if (empty($categoryCauses)) {
                session()->flash('error', "Category {$category} must have at least one cause");
                return;
            }
            
            foreach ($categoryCauses as $cause) {
                if (empty($cause['cause'])) {
                    session()->flash('error', "All causes in {$category} must be filled");
                    return;
                }
                if (empty($cause['description'])) {
                    session()->flash('error', "Description in {$category} must be filled");
                    return;
                }
                if (empty($cause['pic'])) {
                    session()->flash('error', "PIC in {$category} must be filled");
                    return;
                }
                if (empty($cause['target_date'])) {
                    session()->flash('error', "Target date in {$category} must be filled");
                    return;
                }
            }
        }

        // Jika semua validasi passed, generate diagram
        $this->showDiagram = true;
        
        // Perbaiki format diagram
        $diagram = "graph LR\n";  // Ubah flowchart ke graph
        
        // Root node (problem)
        $problemText = str_replace('"', '', substr($this->problemStatement, 0, 30)) ?: 'Problem Statement';
        $diagram .= "    root[\"" . $problemText . "\"]\n";
        
        $categories = ['Man', 'Machine', 'Method', 'Material', 'Measurement', 'Environment'];
        
        foreach ($categories as $category) {
            $diagram .= "    " . $category . "[" . $category . "]\n";
            $diagram .= "    root --- " . $category . "\n";
            
            if (isset($this->causes[$category])) {
                foreach ($this->causes[$category] as $idx => $cause) {
                    if (!empty($cause['cause'])) {
                        $safeText = str_replace(['"', "'", "\n"], ' ', substr($cause['cause'], 0, 20));
                        $causeId = $category . "_" . $idx;
                        $diagram .= "    " . $causeId . "[\"" . $safeText . "\"]\n";
                        $diagram .= "    " . $category . " --- " . $causeId . "\n";
                    }
                }
            }
        }
        
        // Styling
        $diagram .= "\n    style root fill:#ff6666,stroke:#333,stroke-width:2px,color:#fff\n";
        foreach ($categories as $category) {
            $diagram .= "    style " . $category . " fill:#99ccff,stroke:#666\n";
        }
        
        $this->diagramCode = $diagram;
        $this->dispatch('diagramGenerated');
    }
    
    public function updateStatus($analysisId, $newStatus)
    {
        try {
            Log::info('Attempting to update status', [
                'analysis_id' => $analysisId,
                'new_status' => $newStatus
            ]);

            $analysis = FishboneModel::find($analysisId);
            
            if ($newStatus === 'in_progress') {
                // Perbaiki query untuk cek incomplete causes
                $incompleteCauses = $analysis->causes()
                    ->where(function($query) {
                        $query->whereNull('pic')
                              ->orWhere('pic', '')
                              ->orWhereNull('target_date');
                    })
                    ->count();

                if ($incompleteCauses > 0) {
                    Log::warning('Incomplete data found', ['incomplete_count' => $incompleteCauses]);
                    session()->flash('error', 'Please complete all PIC and target dates before submitting');
                    return;
                }
            }

            $analysis->update([
                'status' => $newStatus,
                'submitted_by' => $newStatus === 'in_progress' ? Auth::user()->name : $analysis->submitted_by,
                'submitted_at' => $newStatus === 'in_progress' ? now() : $analysis->submitted_at
            ]);

            Log::info('Status updated successfully', [
                'analysis_id' => $analysisId,
                'new_status' => $newStatus
            ]);

            session()->flash('message', 'Status updated successfully to ' . ucfirst($newStatus));
            $this->analyses = FishboneModel::all();

        } catch (\Exception $e) {
            Log::error('Error updating status', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Failed to update status: ' . $e->getMessage());
        }
    }
    public $selectedAnalysis = null;

    public function viewDetail($id)
    {
        try {
            Log::info('Loading analysis detail', ['id' => $id]);
            
            $this->selectedAnalysis = \App\Models\FishboneAnalysis::query()
                ->with('causes')
                ->find($id);

            if ($this->selectedAnalysis) {
                Log::info('Analysis data', [
                    'cloudinary_url' => $this->selectedAnalysis->cloudinary_url,
                    'problem_statement' => $this->selectedAnalysis->problem_statement
                ]);
                $this->dispatch('showDetailModal');
            }
        } catch (\Exception $e) {
            Log::error('Error in viewDetail: ' . $e->getMessage());
            session()->flash('error', 'Failed to load analysis detail');
        }
    }
    private function generateDiagramCode()
    {
        if (!$this->selectedAnalysis || !$this->selectedAnalysis->causes) {
            return;
        }

        $problemStatement = $this->selectedAnalysis->problem_statement;
        $causes = $this->selectedAnalysis->causes;

        // Gunakan format flowchart untuk Mermaid
        $diagramCode = "flowchart LR\n";
        $diagramCode .= "    Problem{{\"" . addslashes($problemStatement) . "\"}}\n";

        foreach ($causes->groupBy('category') as $category => $categoryCauses) {
            $categoryId = str_replace(' ', '_', $category);
            // Kurangi ukuran box dengan mengurangi padding
            $diagramCode .= "    " . $categoryId . "([\"" . $category . "\"])\n";
            $diagramCode .= "    Problem --> " . $categoryId . "\n";

            foreach ($categoryCauses as $index => $cause) {
                $causeId = $categoryId . "_" . $index;
                $safeText = str_replace(['"', "'", "\n"], ' ', $cause->cause);
                // Kurangi ukuran text box
                $diagramCode .= "    " . $causeId . "(\"" . substr($safeText, 0, 30) . "\")\n";
                $diagramCode .= "    " . $categoryId . " --> " . $causeId . "\n";
            }
        }

        // Tambahkan config untuk mengatur ukuran diagram
        $diagramCode .= "\n%%{init: {'flowchart': {'nodeSpacing': 30, 'rankSpacing': 30, 'padding': 5}}}%%\n";
        
        // Tambahkan styling
        $diagramCode .= "\n    style Problem fill:#ff6666,stroke:#333,stroke-width:2px\n";
        foreach ($causes->groupBy('category') as $category => $categoryCauses) {
            $categoryId = str_replace(' ', '_', $category);
            $diagramCode .= "    style " . $categoryId . " fill:#99ccff,stroke:#666\n";
        }

        $this->diagramCode = $diagramCode;
    }
    public function exportPdf($analysisId)
        {
            try {
                Log::info('Starting PDF export', ['analysis_id' => $analysisId]);
                
                $analysis = FishboneModel::with(['causes' => function($query) {
                    $query->orderBy('category');
                }])->find($analysisId);
                
                if (!$analysis) {
                    Log::error('Analysis not found for export', ['id' => $analysisId]);
                    session()->flash('error', 'Analysis not found');
                    return;
                }

                // Get image from Cloudinary
                $base64Image = null;
                if ($analysis->cloudinary_url) {
                    try {
                        $imageContent = file_get_contents($analysis->cloudinary_url);
                        $base64Image = base64_encode($imageContent);
                    } catch (\Exception $e) {
                        Log::warning('Failed to fetch diagram image', ['error' => $e->getMessage()]);
                    }
                }

                $pdf = \PDF::loadView('pdf.fishbone-analysis', [
                    'analysis' => $analysis,
                    'causes' => $analysis->causes->groupBy('category'),
                    'base64Image' => $base64Image
                ]);
    
                $pdf->setPaper('A4', 'landscape');
                
                Log::info('PDF generated successfully', ['analysis_id' => $analysisId]);
                
                // Ubah cara return PDF
                return response()->streamDownload(
                    function() use ($pdf) { 
                        print $pdf->output(); 
                    }, 
                    'fishbone-analysis-' . $analysisId . '.pdf',
                    [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'attachment; filename="fishbone-analysis-' . $analysisId . '.pdf"'
                    ]
                );
    
            } catch (\Exception $e) {
                Log::error('PDF export failed', [
                    'error' => $e->getMessage(),
                    'stack' => $e->getTraceAsString(),
                    'analysis_id' => $analysisId
                ]);
                session()->flash('error', 'Failed to generate PDF: ' . $e->getMessage());
            }
        }

    // Tambahkan property baru
    public $editMode = false;
    public $editId = null;
    
    // Modifikasi method editAnalysis
    public function editAnalysis($id)
    {
        try {
            Log::info('Starting edit analysis', ['id' => $id]);
            $analysis = FishboneModel::with('causes')->find($id);
            
            if ($analysis) {
                $this->editMode = true;
                $this->editId = $id;
                $this->selectedNGReport = $analysis->ng_report_id;
                $this->selectedQualityCheck = $analysis->quality_check_id;
                $this->title = $analysis->title;
                $this->problemStatement = $analysis->problem_statement;
                $this->analysisDate = $analysis->analysis_date;
                $this->cloudinary_url = $analysis->cloudinary_url;
                $this->cloudinary_id = $analysis->cloudinary_id;
                
                // Reset dan populate causes
                $this->causes = [
                    'Man' => [],
                    'Machine' => [],
                    'Method' => [],
                    'Material' => [],
                    'Measurement' => [],
                    'Environment' => []
                ];
                
                foreach ($analysis->causes as $cause) {
                    $this->causes[$cause->category][] = [
                        'cause' => $cause->cause,
                        'description' => $cause->description,
                        'pic' => $cause->pic,
                        'target_date' => $cause->target_date
                    ];
                }
                
                // Generate diagram baru setelah data dimuat
                $this->generateDiagram();
                Log::info('Analysis loaded and new diagram generated for editing', [
                    'id' => $id,
                    'diagram_generated' => true
                ]);
                
                session()->flash('message', 'Analysis loaded for editing');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load analysis: ' . $e->getMessage());
        }
    }


    // Add new method to reset form
    private function resetForm()
    {
        $this->editMode = false;
        $this->editId = null;
        $this->title = '';
        $this->problemStatement = '';
        $this->selectedNGReport = '';
        $this->selectedQualityCheck = '';
        $this->analysisDate = '';
        $this->showDiagram = false;
        $this->cloudinary_url = null;
        $this->cloudinary_id = null;
        $this->causes = [
            'Man' => [],
            'Machine' => [],
            'Method' => [],
            'Material' => [],
            'Measurement' => [],
            'Environment' => []
        ];
    }


    
    // Update method delete
    public function deleteAnalysis($analysisId)
    {
        try {
            Log::info('Starting delete process', ['id' => $analysisId]);
            
            $analysis = FishboneModel::findOrFail($analysisId);
            
            // Delete associated causes first
            $analysis->causes()->delete();
            
            // Delete the analysis
            $analysis->delete();
            
            Log::info('Analysis deleted successfully', ['id' => $analysisId]);
            
            $this->dispatch('deleteComplete', [
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to delete analysis', [
                'id' => $analysisId,
                'error' => $e->getMessage()
            ]);
            
            $this->dispatch('deleteComplete', [
                'status' => 'error',
                'message' => 'Gagal menghapus data'
            ]);
        }
    }
}
