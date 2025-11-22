<div class="d-none d-md-flex">
    <div class="nav-item">
        <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip"
            data-bs-placement="bottom">
            <i class="icon icon-1 ti ti-moon"></i>
        </a>
        <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip"
            data-bs-placement="bottom">
            <i class="icon icon-1 ti ti-sun"></i>
        </a>
    </div>
    @include('admin.layouts.notifications')
    <div class="nav-item dropdown d-none d-md-flex me-3">
        <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="{{ __('Show app menu') }}"
            data-bs-auto-close="outside" aria-expanded="false">
            <i class="icon icon-1 ti ti-apps"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ __('My Apps') }}</div>
                    <div class="card-actions btn-actions">
                        <a href="#" class="btn-action">
                            <i class="icon icon-1 ti ti-settings"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body scroll-y p-2" style="max-height: 50vh">
                    <div class="row g-0">
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/amazon.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Amazon</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/android.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Android</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/app-store.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Apple App Store</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/apple-podcast.svg" class="w-6 h-6 mx-auto mb-2"
                                    width="24" height="24" alt="" />
                                <span class="h5">Apple Podcast</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/apple.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Apple</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/behance.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Behance</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/discord.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Discord</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/dribbble.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Dribbble</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/dropbox.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Dropbox</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/ever-green.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Ever Green</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/facebook.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Facebook</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/figma.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Figma</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/github.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">GitHub</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/gitlab.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">GitLab</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/google-ads.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Google Ads</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/google-adsense.svg" class="w-6 h-6 mx-auto mb-2"
                                    width="24" height="24" alt="" />
                                <span class="h5">Google AdSense</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/google-analytics.svg" class="w-6 h-6 mx-auto mb-2"
                                    width="24" height="24" alt="" />
                                <span class="h5">Google Analytics</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/google-cloud.svg" class="w-6 h-6 mx-auto mb-2"
                                    width="24" height="24" alt="" />
                                <span class="h5">Google Cloud</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/google-drive.svg" class="w-6 h-6 mx-auto mb-2"
                                    width="24" height="24" alt="" />
                                <span class="h5">Google Drive</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/google-fit.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Google Fit</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/google-home.svg" class="w-6 h-6 mx-auto mb-2"
                                    width="24" height="24" alt="" />
                                <span class="h5">Google Home</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/google-maps.svg" class="w-6 h-6 mx-auto mb-2"
                                    width="24" height="24" alt="" />
                                <span class="h5">Google Maps</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/google-meet.svg" class="w-6 h-6 mx-auto mb-2"
                                    width="24" height="24" alt="" />
                                <span class="h5">Google Meet</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/google-photos.svg" class="w-6 h-6 mx-auto mb-2"
                                    width="24" height="24" alt="" />
                                <span class="h5">Google Photos</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/google-play.svg" class="w-6 h-6 mx-auto mb-2"
                                    width="24" height="24" alt="" />
                                <span class="h5">Google Play</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/google-shopping.svg" class="w-6 h-6 mx-auto mb-2"
                                    width="24" height="24" alt="" />
                                <span class="h5">Google Shopping</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/google-teams.svg" class="w-6 h-6 mx-auto mb-2"
                                    width="24" height="24" alt="" />
                                <span class="h5">Google Teams</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/google.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Google</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/instagram.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Instagram</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/klarna.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Klarna</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/linkedin.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">LinkedIn</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/mailchimp.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Mailchimp</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/medium.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Medium</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/messenger.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Messenger</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/meta.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Meta</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/monday.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Monday</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/netflix.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Netflix</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/notion.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Notion</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/office-365.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Office 365</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/opera.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Opera</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/paypal.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">PayPal</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/petreon.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Patreon</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/pinterest.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Pinterest</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/play-store.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Play Store</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/quora.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Quora</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/reddit.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Reddit</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/shopify.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Shopify</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/skype.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Skype</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/slack.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Slack</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/snapchat.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Snapchat</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/soundcloud.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">SoundCloud</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/spotify.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Spotify</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/stripe.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Stripe</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/telegram.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Telegram</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/tiktok.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">TikTok</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/tinder.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Tinder</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/trello.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Trello</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/truth.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Truth</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/tumblr.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Tumblr</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/twitch.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Twitch</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/twitter.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Twitter</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/vimeo.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Vimeo</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/vk.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">VK</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/watppad.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Wattpad</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/webflow.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Webflow</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/whatsapp.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">WhatsApp</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/wordpress.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">WordPress</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/xing.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Xing</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/yelp.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Yelp</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/youtube.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">YouTube</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/zapier.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Zapier</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/zendesk.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
                                <span class="h5">Zendesk</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#"
                                class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                <img src="./static/brands/zoom.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                    height="24" alt="" />
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
    <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown" aria-label="{{ __('Open user menu') }}">
        <span class="avatar avatar-sm" style="background-image: url({{ Auth::guard('admin')->user()->avatar }})">
        </span>
        <div class="d-none d-xl-block ps-2">
            <div>
                {{ Auth::guard('admin')->user()->name }}
            </div>
            <div class="mt-1 small text-secondary">
                {{ Auth::guard('admin')->user()->email }}
            </div>
        </div>
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
        <a href="{{ route('status') }}" class="dropdown-item">{{ __('Status') }}</a>
        <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">{{ __('Profile') }}</a>
        <div class="dropdown-divider"></div>
        <a href="javascript:void(0)" class="dropdown-item" data-bs-toggle="modal"
            data-bs-target="#modal-logout">{{ __('Logout') }}</a>
    </div>
</div>

<div class="modal modal-blur fade rounded" id="modal-logout" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            <a href="javascript:void(0)" class="btn btn-3 w-100" data-bs-dismiss="modal">
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
