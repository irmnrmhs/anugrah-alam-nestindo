<!-- Migrate -->
public function up(): void
    {
        Schema::create('product_identifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rms_id')->constrained('raw_materials')->onDelete('cascade');
            $table->foreignId('grades_id')->constrained('grades')->onDelete('cascade');
            $table->string('kode')->unique();
            $table->date('tanggal');
            $table->integer('biji');
            $table->decimal('berat', 7, 2);
            $table->timestamps();
        });
    }

<!-- Model -->
 <?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductIdentifier extends Model
{
    protected $fillable = [
        'rms_id',
        'grades_id',
        'kode',
        'tanggal',
        'biji',
        'berat'
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'rms_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grades_id');
    }
}

// Controller
<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\ProductIdentifier;
use App\Models\RawMaterial;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ProductIdentifierController extends Controller
{
    public string $obj = 'Pengidentifikasi Produk';
    public function index(): View
    {
        $identifiers = ProductIdentifier::with('rawMaterial', 'grade')->oldest()->get();
        $rms = RawMaterial::all();
        $grades = Grade::all();

        return view('raw-material.productIdentifier', compact('identifiers', 'rms', 'grades'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'grades_id' => 'required|exists:grades,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99'
        ]);

        // $supplier = Supplier::find($validated['suppliers_id']);
        
        $rm = RawMaterial::find($validated['rms_id']);
        $grade = Grade::find($validated['grades_id']);

        $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
        $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $rm->kode);

        // $validated['kode'] =  $cleanGrade . '-' . $cleanKode . $supplier->kode;
        $validated['kode'] =  $cleanGrade . '-' . $cleanKode;

        $identifier = ProductIdentifier::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $identifier,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $identifier = ProductIdentifier::with('rawMaterial', 'grade')->findOrFail($id);
        return response()->json($identifier);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'grades_id' => 'required|exists:grades,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99'
        ]);

        // $supplier = Supplier::find($validated['suppliers_id']);
        $rm = RawMaterial::find($validated['rms_id']);
        $grade = Grade::find($validated['grades_id']);

        $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
        $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $rm->kode);

        $validated['kode'] = $cleanGrade . '-' . $cleanKode;
        // $validated['kode'] = $cleanGrade . '-' . $cleanKode . $supplier->kode;

        $identifier = ProductIdentifier::findOrFail($id);
        $identifier->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $identifier,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $identifier = ProductIdentifier::findOrFail($id);
        $identifier->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }
}

// blade
 <!-- @extends('layouts.form')

@php
    $title = 'Kelola Pengidentifikasi Produk';
    $singular = 'Pengidentifikasi Produk';
@endphp

@section('table-headers')
    <th>No</th>
    {{-- <th>Supplier</th> --}}
    <th>Kode Bahan Baku</th>
    <th>Grade</th>
    <th>Kode Produk</th>
    <th>Tanggal Grading</th>
    <th>Jumlah Biji</th>
    <th>Jumlah Berat</th>
@stop

@section('table-body')
    @foreach($identifiers as $index => $identifier)
        <tr data-id="{{ $identifier->id }}">
            <td>{{ $index + 1 }}</td>
            {{-- <td>{{ $identifier->supplier->nama }}</td> --}}
            <td>{{ $identifier->rawMaterial->kode }}</td>
            <td>{{ $identifier->grade->grade }}</td>
            <td>{{ $identifier->kode }}</td>
            <td>{{ $identifier->tanggal }}</td>
            <td>{{ $identifier->biji }}</td>
            <td>{{ $identifier->berat }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Kode Bahan Baku</label>
        <select id="rms_id" class="form-control" required>
            <option value="">-- Pilih Bahan Baku --</option>
            @foreach($rms as $rm)
                <option value="{{ $rm->id }}">{{ $rm->kode }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Grade</label>
        <select id="grades_id" class="form-control" required>
            <option value="">-- Pilih Grade --</option>
            @foreach($grades as $grade)
                <option value="{{ $grade->id }}">{{ $grade->grade }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal Grading</label>
        <input type="date" id="tanggal" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Biji</label>
        <input type="number" id="biji" step="1" min="0" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Berat</label>
        <input type="number" id="berat" step="0.001" min="0" max="99999.99" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/identifiers/${id}` : '/identifiers';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        rms_id: $('#rms_id').val(),
        grades_id: $('#grades_id').val(),
        tanggal: $('#tanggal').val(),
        biji: $('#biji').val(),
        berat: $('#berat').val()
    };

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire('Sukses', res.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Gagal', res.message || 'Terjadi kesalahan!', 'error');
        }
    })
    .catch(() => Swal.fire('Error', 'Gagal mengirim data. Pastikan kode tidak duplikat.', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/identifiers/${id}`)
            .then(r => r.json())
            .then(identifier => {
                $('#item_id').val(identifier.id);
                $('#rms_id').val(identifier.rms_id);
                $('#grades_id').val(identifier.grades_id);
                $('#tanggal').val(identifier.tanggal);
                $('#biji').val(identifier.biji);
                $('#berat').val(identifier.berat);
                $('#modalTitle').text('Edit Pengidentifikasi Produk');
                new bootstrap.Modal('#crudModal').show();
            });
    });

    $(document).on('click', '.btnDelete', function() {
        const id = $(this).closest('tr').data('id');
        Swal.fire({
            title: 'Yakin hapus?',
            text: 'Data tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/identifiers/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(r => r.json())
                .then(res => {
                    if (res.status === 'success') {
                        Swal.fire('Terhapus!', res.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', res.message || 'Tidak bisa menghapus data', 'error');
                    }
                });
            }
        });
    });
@stop -->



// after
// controller
<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\ProductIdentifier;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ProductIdentifierController extends Controller
{
    public string $obj = 'Pengidentifikasi Produk';
    public function index(): View
    {
        $identifiers = ProductIdentifier::with('rawMaterial', 'grade')->latest()->get();
        $rms = RawMaterial::all();
        $grades = Grade::all();

        return view('raw-material.productIdentifier', compact('identifiers', 'rms', 'grades'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'grades_id' => 'required|exists:grades,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99'
        ]);

        $rm = RawMaterial::with('arrival')->find($validated['rms_id']);

        // $stokBiji = $rm->biji_sisa_identifier;
        // $stokBerat = $rm->berat_sisa_identifier;

        // if ($validated['biji'] > $stokBiji || $validated['berat'] > $stokBerat) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Melebihi stok sisa',
        //     ], 422);
        // }

        $grade = Grade::find($validated['grades_id']);
        $supplier = $rm->arrival->dcertificate->supplier->kode;

        $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
        $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $rm->kode);

        $validated['kode'] = $cleanGrade . '-' . $cleanKode . $supplier;

        if (ProductIdentifier::where('kode', $validated['kode'])->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode produk sudah ada'
            ], 422);
        }

        $identifier = ProductIdentifier::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $identifier,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $identifier = ProductIdentifier::findOrFail($id);
        return response()->json($identifier);
    }

    public function materialInfo($id)
    {
        $raw = RawMaterial::findOrFail($id);
        $lastOut = ProductIdentifier::where('rms_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $raw->biji_sisa_identifiers,
            'berat_sisa' => $raw->berat_sisa_identifiers,
            'last_date' => $lastOut?->tanggal,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'grades_id' => 'required|exists:grades,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99'
        ]);

        $rm = RawMaterial::findOrFail($validated['rms_id']);

        if ($validated['biji'] > $rm->biji_sisa_identifier || $validated['berat'] > $rm->berat_sisa_identifier) {
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

        $rm = RawMaterial::with('arrival')->find($validated['rms_id']);
        $grade = Grade::find($validated['grades_id']);
        $supplier = $rm->arrival->dcertificate->supplier->kode;

        $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
        $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $rm->kode);
        $kode =  $cleanGrade . '-' . $cleanKode . $supplier;
        $validated['kode'] = $kode;

        $identifier = ProductIdentifier::findOrFail($id);

        $identifier->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $identifier,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $identifier = ProductIdentifier::findOrFail($id);
        $identifier->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        ProductIdentifier::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\ProductIdentifier;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ProductIdentifierController extends Controller
{
    public string $obj = 'Pengidentifikasi Produk';
    public function index(): View
    {
        $identifiers = ProductIdentifier::with('rawMaterial', 'grade')->latest()->get();
        $rms = RawMaterial::all();
        $grades = Grade::all();

        return view('raw-material.productIdentifier', compact('identifiers', 'rms', 'grades'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'grades_id' => 'required|exists:grades,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99'
        ]);

        $rm = RawMaterial::with('arrival')->find($validated['rms_id']);

        // $stokBiji = $rm->biji_sisa_identifier;
        // $stokBerat = $rm->berat_sisa_identifier;

        // if ($validated['biji'] > $stokBiji || $validated['berat'] > $stokBerat) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Melebihi stok sisa',
        //     ], 422);
        // }

        $grade = Grade::find($validated['grades_id']);
        $supplier = $rm->arrival->dcertificate->supplier->kode;

        $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
        $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $rm->kode);

        $validated['kode'] = $cleanGrade . '-' . $cleanKode . $supplier;

        if (ProductIdentifier::where('kode', $validated['kode'])->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode produk sudah ada'
            ], 422);
        }

        $identifier = ProductIdentifier::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $identifier,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $identifier = ProductIdentifier::findOrFail($id);
        return response()->json($identifier);
    }

    public function materialInfo($id)
    {
        $raw = RawMaterial::findOrFail($id);
        $lastOut = ProductIdentifier::where('rms_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $raw->biji_sisa_identifiers,
            'berat_sisa' => $raw->berat_sisa_identifiers,
            'last_date' => $lastOut?->tanggal,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'grades_id' => 'required|exists:grades,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99'
        ]);

        $rm = RawMaterial::findOrFail($validated['rms_id']);

        if ($validated['biji'] > $rm->biji_sisa_identifier || $validated['berat'] > $rm->berat_sisa_identifier) {
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

        $rm = RawMaterial::with('arrival')->find($validated['rms_id']);
        $grade = Grade::find($validated['grades_id']);
        $supplier = $rm->arrival->dcertificate->supplier->kode;

        $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
        $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $rm->kode);
        $kode =  $cleanGrade . '-' . $cleanKode . $supplier;
        $validated['kode'] = $kode;

        $identifier = ProductIdentifier::findOrFail($id);

        $identifier->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $identifier,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $identifier = ProductIdentifier::findOrFail($id);
        $identifier->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        ProductIdentifier::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}

