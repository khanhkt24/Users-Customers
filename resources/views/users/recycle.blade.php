<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #3929294b;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            border-radius: 50%;
            transition: .4s;
        }

        /* Khi checkbox được kiểm tra */
        input:checked+.slider {
            background-color: #0c0000;
            /* Màu xanh khi được kiểm tra */
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }
    </style>
</head>

<body>
    <header>
    </header>
    <main>
        <div class="container">
            @if (session()->has('success'))
                <span class="btn btn-success">{{ session()->get('success') }}</span>
            @endif
            <h1 class="text-center">Danh sách tài khoản người dùng đã xóa</h1>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- <div class="d-flex justify-content-end mb-3">
                <form action="{{ route('users.search') }}" method="GET" class="d-flex" style="width: 300px;">
                    <input type="text" name="query" placeholder="Tìm kiếm tài khoản..." class="form-control me-2"
                        aria-label="Tìm kiếm" value="{{ request()->query('query') }}">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </form>
            </div>

            <form action="{{ route('users.filter') }}" method="GET" class="d-flex justify-content-end mb-3"
                style="width: 300px;">
                <select name="filter_by" class="form-select me-2">
                    <option value="">Tất cả</option>
                    <option value="active" {{ request()->filter_by === 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ request()->filter_by === 'deactive' ? 'selected' : '' }}>Không hoạt động
                    </option>
                    <option value="male" {{ request()->filter_by === 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ request()->filter_by === 'femail' ? 'selected' : '' }}>Nữ
                    </option>
                </select>
                <button type="submit" class="btn btn-primary">Lọc</button>
            </form> --}}

            <table class="table table-default">

                <thead>
                    <tr>
                        <th>Tên</th>
                        <th>Hình ảnh</th>
                        <th>Email</th>
                        <th>Giới tính</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>
                                <img src="{{ \Storage::url('backup/' . $item->image) }}" width="100px" alt="">
                            </td>
                            <td>{{ $item->email }}</td>
                            <td>
                                @if ($item->gender)
                                    <span class="text-dark">male</span>
                                @else
                                    <span class="text-dark">female</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('users.updateStatus', $item->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="is_active" value="0">
                                    <label class="switch">
                                        <input type="checkbox" name="is_active" value="1"
                                            {{ $item->is_active ? 'checked' : '' }} onchange="this.form.submit()">
                                        <span class="slider"></span>
                                    </label>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('users.restore', $item->id) }}" class="btn btn-warning">Khôi
                                        phục</a>

                                    {{-- <a href="{{ route('users.edit', $item->id) }}" class="btn btn-primary">Chi tiết</a>

                                    <form action="{{ route('users.destroy', $item->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Xóa k?')" type="submit"
                                            class="btn btn-danger">Xóa
                                            </button>
                                    </form> --}}

                                </div>
                            </td>
                        </tr>
                    @endforeach
                    <div class="mt-3">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </tbody>
            </table>

        </div>

    </main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
</body>

</html>
