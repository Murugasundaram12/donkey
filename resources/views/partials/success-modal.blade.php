<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-4">
                    <i class="icon icon-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h5 class="modal-title text-success mb-3" id="successModalLabel">
                    Success!
                </h5>
                <p class="mb-4">
                    {{ session('success_message', 'Your document has been submitted successfully. Our team will verify it and get back to you shortly. For follow-up, you can send a WhatsApp message to 9069067008.') }}
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-success px-4" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
