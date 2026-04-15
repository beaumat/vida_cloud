<div>
    <form wire:submit.prevent="decodeQRCode">
        <input type="file" wire:model="photo">
        @error('photo') <span class="error">{{ $message }}</span> @enderror

        <button type="submit">Decode QR Code</button>
    </form>

    @if ($qrCodeData)
        <div>
            <h3>QR Code Data:</h3>
            <p>{{ $qrCodeData }}</p>
        </div>
    @endif
</div>
