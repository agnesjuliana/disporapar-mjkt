@props([
    'id',
    'action',
    'label' => 'Alasan Penolakan',
])

<div id="{{ $id }}" class="modal-overlay">
    <div class="modal-box p-6">
        <h3 class="font-semibold text-lg mb-1">Tolak</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
            Masukkan alasan penolakan. Alasan ini akan dikirimkan ke pihak terkait.
        </p>
        <form method="POST" action="{{ $action }}">
            @csrf
            <div class="mb-4">
                <label class="form-label">{{ $label }} <span class="text-red-500">*</span></label>
                <textarea name="reason" required rows="3" class="form-textarea" placeholder="Jelaskan alasan penolakan..."></textarea>
            </div>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="closeModal('{{ $id }}')" class="btn btn-secondary btn-sm">Batal</button>
                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
            </div>
        </form>
    </div>
</div>
