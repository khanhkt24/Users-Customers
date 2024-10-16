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
</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
    <main>
        <div class="container">
            @if (session()->has('success'))
                <span class="btn btn-success">{{ session()->get('success') }}</span>
            @endif
            <h1 class="text-center">Thêm sản phẩm</h1>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="d-flex justify-content-end mb-3">
                <form action="{{ route('users.search') }}" method="GET" class="d-flex" style="width: 300px;">
                    <input type="text" name="query" placeholder="Tìm kiếm tài khoản..." class="form-control me-2"
                        aria-label="Tìm kiếm" value="{{ request()->query('query') }}">
                    <button type="submit" class="btn btn-primary">Tìm</button>
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
            </form>

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
                                <img src="{{ \Storage::url($item->image) }}" width="100px" alt="">
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
                                @if ($item->is_active)
                                    <span class="badge bg-primary">Active</span>
                                @else
                                    <span class="badge bg-danger">DeActive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('users.restore', $item->id) }}" class="btn btn-warning">Khôi
                                        phục</a>

                                    <a href="{{ route('users.edit', $item->id) }}" class="btn btn-warning">Sửa</a>

                                    <form action="{{ route('users.destroy', $item->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Xóa k?')" type="submit"
                                            class="btn btn-danger">Xóa
                                            mềm</button>
                                    </form>

                                    {{-- <form action="{{ route('users.forceDelete', $item->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Xóa vĩnh viễn?')" type="submit"
                                        class="btn btn-danger">Xóa vĩnh viễn</button>
                                </form> --}}
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            {{ $users->links() }}
            <a href="{{ route('users.index') }}">Danh sách</a>
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
