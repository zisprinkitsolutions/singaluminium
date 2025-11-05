<style>
    body::before {
        content: "";
        position: fixed;
        top: 50%;
        left: 50%;
        width: 70%;
        height: 100%;
        background: url("{{ asset('img/watermark.png') }}") no-repeat center center;
        background-size: contain; /* adjust size */
        opacity: 0.1; /* transparency */
        transform: translate(-50%, -50%) rotate(0deg); /* rotate 45° */
        z-index: 9999;
        pointer-events: none; /* don’t block clicks */
    }
</style>
{{-- <style>
    body::before {
        content: "";
        position: fixed;
        top: 50%;
        left: 50%;
        width: 600px; /* adjust */
        height: 600px; /* adjust */
        background: url("{{ asset('images/watermark.png') }}") no-repeat center;
        background-size: contain;
        opacity: 0.1; /* transparent */
        transform: translate(-50%, -50%) rotate(45deg); /* rotate 45° */
        z-index: 9999;
        pointer-events: none;
    }
</style> --}}

