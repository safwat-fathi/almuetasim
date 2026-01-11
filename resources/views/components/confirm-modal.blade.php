@props([
    'id' => 'confirm-modal',
    'title' => 'تأكيد الإجراء',
    'message' => 'هل أنت متأكد من رغبتك في المتابعة؟',
    'confirmText' => 'تأكيد',
    'cancelText' => 'إلغاء',
    'confirmClass' => 'btn-error'
])

<dialog id="{{ $id }}" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 class="font-bold text-lg text-start">{{ $title }}</h3>
        <p class="py-4 text-start">{{ $message }}</p>
        <div class="modal-action">
            <form method="dialog">
                <!-- if there is a button in form, it will close the modal -->
                <button class="btn">{{ $cancelText }}</button>
            </form>
            <button type="button" class="btn {{ $confirmClass }}" id="{{ $id }}-confirm-btn">
                {{ $confirmText }}
            </button>
        </div>
    </div>
</dialog>
