<div class="nav-item dropdown d-none d-md-flex">
    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
        aria-label="{{ __('Show notifications') }}" data-bs-auto-close="outside" aria-expanded="false"
        id="notifications-trigger">
        <i class="icon icon-1 ti ti-bell"></i>
        <span class="badge bg-red text-white small" id="notifications-badge" style="display: none;">0</span>
    </a>
    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card" id="notifications-dropdown">
        <div class="card">
            <div class="card-header d-flex">
                <h3 class="card-title">{{ __('Notifications') }}</h3>
                <div class="btn-close ms-auto" data-bs-dismiss="dropdown"></div>
            </div>
            <div class="list-group list-group-flush list-group-hoverable" id="notifications-list">
                <div class="text-center py-5" id="notifications-loading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{ __('Loading...') }}</span>
                    </div>
                </div>
                <div class="text-center py-5" id="notifications-empty" style="display: none;">
                    <p class="text-muted">{{ __('No notifications') }}</p>
                </div>
            </div>
            <div class="card-body" id="notifications-actions" style="display: none;">
                <div class="row">
                    <div class="col">
                        <button type="button" class="btn btn-2 w-100"
                            id="mark-all-read">{{ __('Mark all as read') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function() {
            // Wait for DOM and Bootstrap to be ready
            function initNotifications() {
                // Get CSRF token
                const token = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = token ? token.getAttribute('content') : '';

                // Helper function for fetch with CSRF token
                function fetchWithCSRF(url, options = {}) {
                    const headers = {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        ...options.headers
                    };

                    if (csrfToken) {
                        headers['X-CSRF-TOKEN'] = csrfToken;
                    }

                    // Only add Content-Type if body is being sent
                    if (options.body && !headers['Content-Type']) {
                        headers['Content-Type'] = 'application/json';
                    }

                    return fetch(url, {
                        ...options,
                        headers: headers
                    });
                }

                const badge = document.getElementById('notifications-badge');
                const dropdown = document.getElementById('notifications-dropdown');
                const list = document.getElementById('notifications-list');
                const loading = document.getElementById('notifications-loading');
                const empty = document.getElementById('notifications-empty');
                const actions = document.getElementById('notifications-actions');
                const markAllReadBtn = document.getElementById('mark-all-read');
                const trigger = document.getElementById('notifications-trigger');

                if (!badge || !dropdown || !list || !loading || !empty || !actions || !trigger) {
                    console.error('Notification elements not found');
                    return;
                }

                let notifications = [];
                let isLoading = false;
                let hasLoadedOnce = false;

                // Load unread count on page load
                loadUnreadCount();

                // Load notifications and count when dropdown is shown
                if (trigger && dropdown) {
                    // Listen for Bootstrap dropdown show event on the trigger
                    trigger.addEventListener('shown.bs.dropdown', function() {
                        if (!hasLoadedOnce) {
                            hasLoadedOnce = true;
                            loadNotifications();
                        }
                    });

                    // Also handle click as fallback
                    trigger.addEventListener('click', function(e) {
                        // Small delay to ensure dropdown is showing
                        setTimeout(function() {
                            if (!hasLoadedOnce && dropdown.classList.contains('show')) {
                                hasLoadedOnce = true;
                                loadNotifications();
                            }
                        }, 100);
                    });
                }

                // Mark all as read handler
                if (markAllReadBtn) {
                    markAllReadBtn.addEventListener('click', function() {
                        markAllAsRead();
                    });
                }

                // Load unread notifications count
                function loadUnreadCount() {
                    const url = '{{ route('admin.notifications.unread.count') }}';

                    fetchWithCSRF(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            const unreadCount = data.count || 0;
                            updateBadge(unreadCount);
                        })
                        .catch(error => {
                            console.error('Failed to load unread count:', error);
                            // Hide badge on error
                            updateBadge(0);
                        });
                }

                function loadNotifications() {
                    if (isLoading) return;

                    isLoading = true;
                    loading.style.display = 'block';
                    empty.style.display = 'none';

                    // Clear existing content but keep loading spinner
                    const existingItems = list.querySelectorAll('.list-group-item');
                    existingItems.forEach(item => item.remove());
                    list.appendChild(loading);

                    const url = '{{ route('admin.notifications.index') }}';

                    fetchWithCSRF(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            notifications = data.notifications?.data || [];
                            isLoading = false;
                            loading.style.display = 'none';

                            if (notifications.length === 0) {
                                empty.style.display = 'block';
                                list.appendChild(empty);
                                actions.style.display = 'none';
                            } else {
                                renderNotifications();
                                actions.style.display = 'block';
                            }

                            // Update badge with server count
                            loadUnreadCount();
                        })
                        .catch(error => {
                            isLoading = false;
                            loading.style.display = 'none';
                            console.error('Failed to load notifications:', error);
                            list.innerHTML =
                                '<div class="text-center py-5"><p class="text-danger">{{ __('Failed to load notifications') }}</p></div>';
                        });
                }

                function renderNotifications() {
                    // Remove loading and empty states
                    loading.style.display = 'none';
                    empty.style.display = 'none';

                    // Clear existing items
                    list.innerHTML = '';

                    notifications.forEach(notification => {
                        const item = createNotificationItem(notification);
                        list.appendChild(item);
                    });
                }

                function createNotificationItem(notification) {
                    const item = document.createElement('div');
                    item.className = 'list-group-item';
                    item.setAttribute('data-notification-id', notification.id);

                    const statusDotClass = notification.read ?
                        'status-dot d-block' :
                        'status-dot status-dot-animated bg-red d-block';

                    const typeColor = getTypeColor(notification.type);
                    const statusDot = notification.read ?
                        `<span class="status-dot d-block"></span>` :
                        `<span class="status-dot status-dot-animated ${typeColor} d-block"></span>`;

                    item.innerHTML = `
            <div class="row align-items-center">
                <div class="col-auto">${statusDot}</div>
                <div class="col text-truncate">
                    <a href="#" class="text-body d-block notification-link" data-id="${notification.id}">${escapeHtml(notification.title)}</a>
                    <div class="d-block text-secondary text-truncate mt-n1">${escapeHtml(notification.message)}</div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-link text-danger p-0 notification-delete" 
                        data-id="${notification.id}" title="{{ __('Delete') }}">
                        <i class="icon icon-1 ti ti-x"></i>
                    </button>
                </div>
            </div>
        `;

                    // Handle click on notification
                    const link = item.querySelector('.notification-link');
                    if (link) {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            markAsRead(notification.id, notification.url);
                        });
                    }

                    // Handle delete
                    const deleteBtn = item.querySelector('.notification-delete');
                    if (deleteBtn) {
                        deleteBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            deleteNotification(notification.id);
                        });
                    }

                    return item;
                }

                function markAsRead(id, fallbackUrl) {
                    // Use the proper route for update - Laravel resource route expects {notification} parameter
                    const url = `{{ route('admin.notifications.index') }}/${id}`;
                    console.log('Marking notification as read:', {
                        id: id,
                        url: url
                    });

                    fetchWithCSRF(url, {
                            method: 'PUT',
                            body: JSON.stringify({})
                        })
                        .then(response => {
                            console.log('Mark as read response status:', response.status);
                            if (!response.ok) {
                                // Try to get error message from response
                                return response.json().then(err => {
                                    throw new Error(err.message ||
                                        `HTTP error! status: ${response.status}`);
                                }).catch(() => {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Mark as read success:', data);

                            // Update local state
                            const notification = notifications.find(n => n.id === id);
                            if (notification) {
                                notification.read = true;
                                // Update URL from response if available
                                if (data.url) {
                                    notification.url = data.url;
                                }
                            }

                            // Update UI before navigation
                            renderNotifications();

                            // Update badge with server count
                            loadUnreadCount();

                            // Navigate to URL from response (preferred) or fallback
                            const targetUrl = data.url || fallbackUrl;
                            if (targetUrl) {
                                // Small delay to ensure UI updates
                                setTimeout(() => {
                                    window.location.href = targetUrl;
                                }, 100);
                            } else {
                                // No URL to navigate to, just refresh notifications
                                console.log('No URL provided, not navigating');
                            }
                        })
                        .catch(error => {
                            console.error('Failed to mark notification as read:', error);
                            console.error('Error details:', {
                                id: id,
                                url: url,
                                fallbackUrl: fallbackUrl
                            });
                            // Still navigate if fallback URL exists (even on error)
                            if (fallbackUrl) {
                                window.location.href = fallbackUrl;
                            }
                        });
                }

                function deleteNotification(id) {
                    if (!confirm('{{ __('Are you sure you want to delete this notification?') }}')) {
                        return;
                    }

                    fetchWithCSRF(`{{ route('admin.notifications.index') }}/${id}`, {
                            method: 'DELETE'
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Remove from local array
                            notifications = notifications.filter(n => n.id !== id);

                            // Update UI
                            if (notifications.length === 0) {
                                empty.style.display = 'block';
                                list.innerHTML = '';
                                list.appendChild(empty);
                                actions.style.display = 'none';
                            } else {
                                renderNotifications();
                            }

                            // Update badge with server count
                            loadUnreadCount();
                        })
                        .catch(error => {
                            console.error('Failed to delete notification:', error);
                        });
                }

                function markAllAsRead() {
                    const unreadNotifications = notifications.filter(n => !n.read);

                    if (unreadNotifications.length === 0) {
                        return;
                    }

                    // Mark all as read optimistically
                    const promises = unreadNotifications.map(notification => {
                        return fetchWithCSRF(`{{ route('admin.notifications.index') }}/${notification.id}`, {
                                method: 'PUT',
                                body: JSON.stringify({})
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(() => {
                                notification.read = true;
                            });
                    });

                    Promise.all(promises)
                        .then(() => {
                            renderNotifications();
                            // Update badge with server count
                            loadUnreadCount();
                        })
                        .catch(error => {
                            console.error('Failed to mark all as read:', error);
                            // Reload to get accurate state
                            loadNotifications();
                        });
                }

                function updateBadge(count) {
                    if (!badge) return;

                    // Only show badge if there are unread notifications
                    if (count > 0) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                }

                function getTypeColor(type) {
                    const colors = {
                        'success': 'bg-green',
                        'error': 'bg-red',
                        'warning': 'bg-yellow',
                        'info': 'bg-blue',
                    };
                    return colors[type] || 'bg-red';
                }

                function escapeHtml(text) {
                    if (!text) return '';
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }

                // Expose refresh function for external use (e.g., after creating notifications)
                window.refreshNotifications = function() {
                    notifications = []; // Clear cached notifications
                    hasLoadedOnce = false;
                    // Reload unread count
                    loadUnreadCount();
                    // Reload if dropdown is open
                    if (dropdown && dropdown.classList.contains('show')) {
                        loadNotifications();
                    }
                };
            }

            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initNotifications);
            } else {
                // DOM is already ready
                initNotifications();
            }
        })();
    </script>
@endpush
