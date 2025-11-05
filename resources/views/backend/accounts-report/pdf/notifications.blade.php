<button class="notification-btn">
    <i class="bx bxs-bell"></i>
    <span class="notification-badge">{{ auth()->user()->unreadNotifications()->latest()->take(5)->get()->count() }}</span>
</button>

<!-- Notification Dropdown Box -->
<div id="notification-box" class="notification-box">
    <!-- Loop through unread notifications -->
    @foreach (auth()->user()->unreadNotifications()->latest()->take(5)->get() as $item)
        <a href="{{ route('download-large-file',['notification_id' => $item->id, 'path' => $item->data['file_path']]) }}" class="notification-details">
            <div class="notification-item">
                <p class="notification-message">
                    <a href="{{ route('download-large-file',['notification_id' => $item->id, 'path' => $item->data['file_path']]) }}">
                        {{ $item->data['Message'] }}
                    </a>
                </p>
                <small class="notification-time">{{ $item->created_at->format('d M Y, h:i A') }}</small>
            </div>
        </a>
    @endforeach
</div>
