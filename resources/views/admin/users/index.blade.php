<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Users Management</title>
    <link rel="stylesheet" href="{{ asset('css/admin/users/index.css') }}">
</head>

<body>
    <div class="main-container">
        <x-side-bar />

        <main class="main-content">
            <header>
                <h1>Users Management</h1>
            </header>

            @if (session('success'))
                <div class="alert-container alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <section class="recent-section">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Joined Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>#{{ $user->id }}</td>
                                <td class="text-center"><strong>{{ $user->name }}</strong></td>
                                <td class="text-center">{{ $user->email }}</td>
                                <td class="text-center">{{ $user->phone }}</td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <span class="status {{ $user->is_banned ? 'banned' : 'active' }}">
                                        {{ $user->is_banned ? 'Banned' : 'Active' }}
                                    </span>
                                </td>
                                <td class="action-btns">
                                    <form action="{{ route('admin.users.updateIsBanned', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to {{ $user->is_banned ? 'unban' : 'ban' }} this user?')">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="is_banned" value="{{ $user->is_banned ? 0 : 1 }}">
                                        <button type="submit"
                                            class="view-btn {{ $user->is_banned ? 'btn-add' : 'btn-delete' }}">
                                            {{ $user->is_banned ? 'Unban User' : 'Ban User' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="no-data">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="pagination-wrapper">
                    <div class="pagination-container">
                        {{ $users->links() }}
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>
