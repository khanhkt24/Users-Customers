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

        @if (session()->has('success'))
            <span class="alert alert-success">{{ session()->get('success') }}</span>
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
        <form action="{{ route('users.update',$user) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="" class="form-label">Name</label>
                <input type="text" class="form-control" name="name" id="" value="{{ $user->name}}"
                    placeholder="" />
                    @error('name')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="" class="form-label">email</label>
                <input type="text" class="form-control" name="email" id="" value="{{ $user->email }}"
                    placeholder="" />
                    @error('email')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="" class="form-label">is_active</label>
                <input type="checkbox" class="form-checkbox" @checked($user->is_active)
                 name="is_active" value="1" id="btncheck1">
                 @error('is_active')
                 <span class="text-danger">{{$message}}</span>
             @enderror
            </div>
            <div class="mb-3">
                <label for="" class="form-label">image</label>
                <input type="file" class="form-control" name="image" placeholder="" />
                 <img src="{{ \Storage::url($user->image) }}" width="100px" alt="">
            </div>


            <div class="mb-3">
                <label for="" class="form-label">address</label>
                <input type="text" class="form-control" name="address" id="" value="{{ $user->address }}"
                    placeholder="" />
                    @error('phoneNumber')
                    <span class="text-danger">{{$message}}</span>gender
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Gender</label>
                <div>
                    <input type="radio" id="male" name="gender" value="1" @checked($user->gender === 1) />
                    <label for="male">male</label>
                </div>
                <div>
                    <input type="radio" id="female" name="gender" value="0" @checked($user->gender === 0) />
                    <label for="female">female</label>
                </div>
                @error('gender')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="" class="form-label">phoneNumber</label>
                <input type="number" class="form-control" name="phoneNumber" id="" value="{{ $user->phoneNumber }}"
                    placeholder="" min="0" />
                @error('phoneNumber')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-warning">Sửa</button>
            <a href="{{ route('users.index') }}" class="btn btn-primary">Danh sách</a>
        </form>
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
