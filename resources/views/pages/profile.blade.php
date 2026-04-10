<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="{{ asset('css/pages/profile.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <x-header />

    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=128' }}"
                        alt="User Avatar">
                </div>
                <h2>{{ $user->name }}</h2>
                <span class="badge">Member since {{ $user->created_at->format('M Y') }}</span>
            </div>

            <div class="profile-body">
                <div class="info-group">
                    <label><i class="fa-regular fa-envelope"></i> Email</label>
                    <p>{{ $user->email }}</p>
                </div>

                <div class="info-group">
                    <label><i class="fa-regular fa-user"></i> Full Name</label>
                    <p>{{ $user->name }}</p>
                </div>

                <div class="info-group">
                    <label><i class="fa-solid fa-phone"></i> Phone Number</label>
                    <p>{{ $user->phone ?? 'Chưa cập nhật số điện thoại' }}</p>
                </div>

                <div class="info-group">
                    <label><i class="fa-solid fa-location-dot"></i> Address</label>
                    <p>{{ $user->address ?? 'Chưa có địa chỉ giao hàng' }}</p>
                </div>

                <div class="profile-actions">
                    <a href="{{ route('profile.edit') }}" class="btn-edit">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>

    <x-footer />
</body>

</html>
