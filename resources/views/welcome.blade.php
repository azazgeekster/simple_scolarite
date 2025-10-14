<a href="{{route('admin.loginform')}}">Admin Space</a> <br>
@auth('student')
    <div>
        Student Authenticated
    </div>
@else
    <div class="">
        Student Not Authenticated
    </div>
@endauth <br>
<a href="{{route('student.loginform')}}">Student Space</a>
@auth('admin')
    <div>
        Admin Authenticated
    </div>
@else
<div>
        Admin Not Authenticated
    </div>
@endauth