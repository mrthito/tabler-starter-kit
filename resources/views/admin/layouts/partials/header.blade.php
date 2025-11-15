<meta name="msapplication-TileColor" content="#066fd1" />
<meta name="theme-color" content="#066fd1" />
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="mobile-web-app-capable" content="yes" />
<meta name="HandheldFriendly" content="True" />
<meta name="MobileOptimized" content="320" />
<link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon" />
<link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon" />
<meta name="description" content="" />
<meta name="canonical" content="{{ url()->current() }}" />
<meta name="twitter:image:src" content="{{ asset('assets/images/og.png') }}" />
<meta name="twitter:site" content="@{{ config('social.twitter') }}" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="{{ $title }}" />
<meta name="twitter:description" content="" />
<meta property="og:image" content="{{ asset('assets/images/og.png') }}" />
<meta property="og:image:width" content="1280" />
<meta property="og:image:height" content="640" />
<meta property="og:site_name" content="{{ config('app.name') }}" />
<meta property="og:type" content="object" />
<meta property="og:title" content="{{ $title }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:description" content="" />
@vite(['resources/scss/admin.scss', 'resources/js/admin.js', 'resources/js/app.js', 'resources/js/admin-theme.js', 'resources/scss/flags.scss', 'resources/scss/icons.scss', 'resources/scss/marketing.scss', 'resources/scss/payments.scss', 'resources/scss/admin-props.scss', 'resources/scss/socials.scss', 'resources/scss/themes.scss', 'resources/scss/vendors.scss'])
<style>
    @import url("https://rsms.me/inter/inter.css");
</style>

@stack('styles')
