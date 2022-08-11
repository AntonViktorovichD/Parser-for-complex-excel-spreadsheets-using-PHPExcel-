@include('header')
@include('layouts.menu')
<div class="container">
    {{ $alert }}
</div>
<script>
    setTimeout(() => window.location.href = '<?= $route ?>', 2000);
</script>
@include('layouts.footer')
