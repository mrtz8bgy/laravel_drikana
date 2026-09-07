@extends('layouts.login')

@section('content')
<script>
    function autoFill(){
        document.getElementById('email').value = 'admin@example.com';
        document.getElementById('password').value = '123456';
    }
</script>
@endsection
