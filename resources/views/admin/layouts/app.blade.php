<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ $title }}</title>
    @include('admin.layouts.partials.header')
</head>

<body>
    <div class="page">
        <header class="navbar navbar-expand-md d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                    aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="{{ route('admin.dashboard') }}" aria-label="Tabler">
                        <x-application-logo class="navbar-brand-image" />
                    </a>
                </div>
                <div class="navbar-nav flex-row order-md-last">
                    <div class="nav-item d-none d-md-flex me-3">
                        <div class="btn-list">
                            <a href="https://github.com/tabler/tabler" class="btn btn-5" target="_blank"
                                rel="noreferrer">
                                <i class="ti ti-brand-github icon icon-2"></i>
                                Source code
                            </a>
                            <a href="https://github.com/sponsors/codecalm" class="btn btn-6" target="_blank"
                                rel="noreferrer">
                                <i class="ti ti-heart icon icon-2 text-pink"></i>
                                Sponsor
                            </a>
                        </div>
                    </div>
                    <div class="d-none d-md-flex">
                        <div class="nav-item">
                            <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode"
                                data-bs-toggle="tooltip" data-bs-placement="bottom">
                                <i class="ti ti-moon icon icon-1"></i>
                            </a>
                            <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode"
                                data-bs-toggle="tooltip" data-bs-placement="bottom">
                                <i class="ti ti-sun icon icon-1"></i>
                            </a>
                        </div>
                        <div class="nav-item dropdown d-none d-md-flex">
                            <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                                aria-label="Show notifications" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="ti ti-bell icon icon-1"></i>
                                <span class="badge bg-red"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                                <div class="card">
                                    <div class="card-header d-flex">
                                        <h3 class="card-title">Notifications</h3>
                                        <div class="btn-close ms-auto" data-bs-dismiss="dropdown"></div>
                                    </div>
                                    <div class="list-group list-group-flush list-group-hoverable">
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="status-dot status-dot-animated bg-red d-block"></span>
                                                </div>
                                                <div class="col text-truncate">
                                                    <a href="#" class="text-body d-block">Example 1</a>
                                                    <div class="d-block text-secondary text-truncate mt-n1">Change
                                                        deprecated html tags to text decoration classes (#29604)</div>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="list-group-item-actions">
                                                        <i class="ti ti-star icon icon-2 text-muted"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto"><span class="status-dot d-block"></span></div>
                                                <div class="col text-truncate">
                                                    <a href="#" class="text-body d-block">Example 2</a>
                                                    <div class="d-block text-secondary text-truncate mt-n1">
                                                        justify-content:between ⇒ justify-content:space-between (#29734)
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="list-group-item-actions show">
                                                        <i class="ti ti-star icon icon-2 text-yellow"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto"><span class="status-dot d-block"></span></div>
                                                <div class="col text-truncate">
                                                    <a href="#" class="text-body d-block">Example 3</a>
                                                    <div class="d-block text-secondary text-truncate mt-n1">Update
                                                        change-version.js (#29736)</div>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="list-group-item-actions">
                                                        <i class="ti ti-star icon icon-2 text-muted"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto"><span
                                                        class="status-dot status-dot-animated bg-green d-block"></span>
                                                </div>
                                                <div class="col text-truncate">
                                                    <a href="#" class="text-body d-block">Example 4</a>
                                                    <div class="d-block text-secondary text-truncate mt-n1">Regenerate
                                                        package-lock.json (#29730)</div>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="list-group-item-actions">
                                                        <i class="ti ti-star icon icon-2 text-muted"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <a href="#" class="btn btn-2 w-100"> Archive all </a>
                                            </div>
                                            <div class="col">
                                                <a href="#" class="btn btn-2 w-100"> Mark all as read </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nav-item dropdown d-none d-md-flex me-3">
                            <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                                aria-label="Show app menu" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="ti ti-apps icon icon-1"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">My Apps</div>
                                        <div class="card-actions btn-actions">
                                            <a href="#" class="btn-action">
                                                <i class="ti ti-settings icon icon-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body scroll-y p-2" style="max-height: 50vh">
                                        <div class="row g-0">
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/amazon.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Amazon</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/android.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Android</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/app-store.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Apple App Store</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/apple-podcast.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Apple Podcast</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/apple.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Apple</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/behance.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Behance</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/discord.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Discord</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/dribbble.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Dribbble</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/dropbox.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Dropbox</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/ever-green.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Ever Green</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/facebook.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Facebook</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/figma.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Figma</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/github.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">GitHub</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/gitlab.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">GitLab</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/google-ads.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Ads</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/google-adsense.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google AdSense</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/google-analytics.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Analytics</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/google-cloud.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Cloud</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/google-drive.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Drive</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/google-fit.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Fit</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/google-home.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Home</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/google-maps.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Maps</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/google-meet.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Meet</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/google-photos.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Photos</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/google-play.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Play</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/google-shopping.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Shopping</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/google-teams.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Google Teams</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/google.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Google</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/instagram.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Instagram</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/klarna.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Klarna</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/linkedin.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">LinkedIn</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/mailchimp.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Mailchimp</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/medium.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Medium</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/messenger.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Messenger</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/meta.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Meta</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/monday.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Monday</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/netflix.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Netflix</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/notion.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Notion</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/office-365.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Office 365</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/opera.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Opera</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/paypal.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">PayPal</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/petreon.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Patreon</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/pinterest.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Pinterest</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/play-store.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Play Store</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/quora.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Quora</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/reddit.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Reddit</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/shopify.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Shopify</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/skype.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Skype</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/slack.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Slack</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/snapchat.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Snapchat</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/soundcloud.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">SoundCloud</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/spotify.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Spotify</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/stripe.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Stripe</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/telegram.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Telegram</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/tiktok.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">TikTok</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/tinder.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Tinder</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/trello.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Trello</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/truth.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Truth</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/tumblr.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Tumblr</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/twitch.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Twitch</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/twitter.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Twitter</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/vimeo.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Vimeo</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/vk.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">VK</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/watppad.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Wattpad</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/webflow.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Webflow</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/whatsapp.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">WhatsApp</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/wordpress.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">WordPress</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/xing.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Xing</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/yelp.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Yelp</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/youtube.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">YouTube</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/zapier.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Zapier</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/zendesk.svg"
                                                        class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                                                        alt="" />
                                                    <span class="h5">Zendesk</span>
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="#"
                                                    class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                                    <img src="./static/brands/zoom.svg" class="w-6 h-6 mx-auto mb-2"
                                                        width="24" height="24" alt="" />
                                                    <span class="h5">Zoom</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown"
                            aria-label="Open user menu">
                            <span class="avatar avatar-sm"
                                style="background-image: url({{ Auth::guard('admin')->user()->avatar }})">
                            </span>
                            <div class="d-none d-xl-block ps-2">
                                <div>{{ Auth::guard('admin')->user()->name }}</div>
                                <div class="mt-1 small text-secondary">
                                    {{ Auth::guard('admin')->user()->email }}
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">Profile</a>
                            <div class="dropdown-divider"></div>
                            <a href="" class="dropdown-item">Settings</a>
                            <a href="javascript:void(0)" class="dropdown-item" data-bs-toggle="modal"
                                data-bs-target="#modal-logout">Logout</a>
                        </div>
                    </div>

                    <div class="modal modal-blur fade rounded" id="modal-logout" tabindex="-1" role="dialog"
                        aria-hidden="true">
                        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                <div class="modal-status bg-danger"></div>
                                <div class="modal-body text-center py-4">
                                    <i class="ti ti-alert-triangle icon icon-lg text-danger"></i>
                                    <h3>{{ __('Are you sure?') }}</h3>
                                    <div class="text-secondary">{{ __('Do you really want to logout?') }}</div>
                                </div>
                                <div class="modal-footer">
                                    <div class="w-100">
                                        <div class="row">
                                            <div class="col">
                                                <a href="javascript:void(0)" class="btn btn-3 w-100"
                                                    data-bs-dismiss="modal">
                                                    {{ __('Cancel') }}
                                                </a>
                                            </div>
                                            <div class="col">
                                                <form action="{{ route('admin.logout') }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-4 w-100">
                                                        {{ __('Logout') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </header>
        <header class="navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="navbar">
                    <div class="container-xl">
                        <div class="row flex-column flex-md-row flex-fill align-items-center">
                            <div class="col">
                                <ul class="navbar-nav">
                                    @menu('admin', 'admin.layouts.sidemenu')
                                </ul>
                            </div>
                            <div class="col col-md-auto">
                                <ul class="navbar-nav">
                                    @menu('admin-right', 'admin.layouts.sidemenu')
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="page-wrapper">
            <div class="page-header d-print-none" aria-label="Page header">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            @if (isset($pretitle))
                                <div class="page-pretitle">{{ $pretitle }}</div>
                            @endif
                            @if (isset($title))
                                <h2 class="page-title">{{ $title }}</h2>
                            @endif
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="btn-list">
                                @yield('page-actions')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-body">
                <div class="container-xl">
                    {{ $slot }}
                </div>
            </div>
            @include('admin.layouts.app.partials.footer')
        </div>
    </div>

    @include('admin.layouts.partials.footer')
</body>

</html>
