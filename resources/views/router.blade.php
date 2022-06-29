@include('header')
@include('layouts.menu')
{{ $alert }}
<script>
    setTimeout(() => window.location.href = '<?= $route ?>', 2000);
</script>
@include('layouts.footer')
