@include('layouts.header')
@include('layouts.menu')
<div class="container">
    @php
    foreach($users as $user) {
       var_dump($user[0]);
    }
    @endphp
</div>

{{ $elev_users->links() }}
@include('layouts.footer')
