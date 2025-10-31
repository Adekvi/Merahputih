<link rel="icon" href="{{ asset('aset/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

<!-- Fonts and icons -->
<script src="{{ asset('aset/js/plugin/webfont/webfont.min.js') }}"></script>
<script>
    WebFont.load({
        google: {
            families: ["Public Sans:300,400,500,600,700"]
        },
        custom: {
            families: [
                "Font Awesome 5 Solid",
                "Font Awesome 5 Regular",
                "Font Awesome 5 Brands",
                "simple-line-icons",
            ],
            urls: ["{{ asset('aset/css/fonts.min.css') }}"],
        },
        active: function() {
            sessionStorage.fonts = true;
        },
    });
</script>

<!-- CSS Files -->
<link rel="stylesheet" href="{{ asset('aset/css/bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('aset/css/plugins.min.css') }}" />
<link rel="stylesheet" href="{{ asset('aset/css/kaiadmin.min.css') }}" />

<!-- CSS Just for demo purpose, don't include it in your project -->
<link rel="stylesheet" href="{{ asset('aset/css/demo.css') }}" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
