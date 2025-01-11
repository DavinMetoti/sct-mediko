<nav class="navbar" id="navbar">
    <div class="d-flex justify-content-between align-items-center w-full">
        <div>
            <button type="button" class="hamburger-button" id="hamburger-button">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div class="flex gap-4 align-items-center">
            <div class="button-notifications position-relative">
                <button type="button" class="btn btn-transparent position-relative p-0" id="notification-button">
                    <i id="bell-icon" class="fas fa-bell text-white" style="font-size: 20px"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-badge" style="display: none;">
                        0
                        <span class="visually-hidden">unread messages</span>
                    </span>
                    <span id="loading-spinner" class="spinner-border spinner-border-sm text-white" style="display: none;"></span>
                </button>

                <div class="notification-card" id="notification-card" style="display: none;">
                    <div class="notification-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Notifications</h5>
                        <i class="fas fa-times cursor-pointer" id="close-notification-card"></i>
                    </div>
                    <ul id="notification-list" class="list-unstyled mb-0">
                        <!-- Notifications will be added here -->
                    </ul>
                    <p id="no-notifications-message" class="text-center" style="display: none;">Tidak ada notifikasi</p>
                </div>
            </div>
            <!-- Profile Dropdown -->
            <div class="dropdown" id="dropdown-profile">
                <button class="dropdown-toggle" id="dropdown-button">
                    {{ Auth::user()->name }}
                </button>
                <div class="dropdown-menu" id="dropdown-menu">
                    <a href="{{ route('profile.index', ['id' => auth()->id()]) }}" class="dropdown-item">Profile</a>
                    <a href="#" id="logout-button" class="dropdown-item">Log Out</a>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="modal fade" id="notification-modal" tabindex="-1" aria-labelledby="notification-modal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="flex justify-content-between align-items-center bg-primary text-white p-2">
            <h1 class="modal-title fs-5">
                <i class="fa fa-info-circle me-2" ></i><span id="modal-title"></span>
              </h1>
            <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        <div class="modal-body">
            <p id="modal-message" class="text-muted"></p>
            <div class="flex justify-content-end">
                <p class="text-right text-muted text-sm" id="modal-created-at"></p>
            </div>
        </div>
      </div>
    </div>
  </div>


<script>
    const confirmationModal = new ConfirmationModal();

    document.getElementById('logout-button').addEventListener('click', () => {
        confirmationModal.open({
            message: 'Apakah anda yakin ingin logout?',
            severity: 'warn',
            onAccept: () => {
                $.ajax({
                    url: '{{ route('login.logout') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        window.location.href = '{{ route('login') }}';
                    },
                    error: function(error) {
                        console.error('Error logging out:', error);
                    }
                });
            },
            onReject: () => {
                console.log('Rejected!');
            },
        });
    });

    // Function to get the total number of notifications and update the badge
    function getTotalLength() {
        const userId = '{{ Auth::user()->id }}';

        fetch(`/broadcast/data/${userId}`)
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('#notification-button .badge');
                const notificationBadge = document.getElementById('notification-badge');

                // Update badge count and visibility
                if (data.length > 0) {
                    notificationBadge.style.display = 'inline-block';
                    notificationBadge.textContent = data.length;
                } else {
                    notificationBadge.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching total notifications:', error);
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
            getTotalLength();  // Call the function once on page load

            // Set an interval to call getTotalLength every 5 seconds
            setInterval(getTotalLength, 5000); // 5000ms = 5 seconds
        });

        document.getElementById('notification-button').addEventListener('click', () => {
        const notificationCard = document.getElementById('notification-card');
        const notificationList = document.getElementById('notification-list');
        const loadingSpinner = document.getElementById('loading-spinner');
        const bellIcon = document.getElementById('bell-icon');
        const notificationButton = document.getElementById('notification-button');
        const noNotificationsMessage = document.getElementById('no-notifications-message');
        const userId = '{{ Auth::user()->id }}';

        notificationButton.disabled = true;
        loadingSpinner.style.display = 'inline-block';
        bellIcon.style.display = 'none';

        setTimeout(() => {
            fetch(`/broadcast/data/${userId}`)
                .then(response => response.json())
                .then(notifications => {
                    notificationButton.disabled = false;
                    loadingSpinner.style.display = 'none';
                    bellIcon.style.display = 'inline-block';

                    if (notificationCard.style.display === 'none' || notificationCard.style.display === '') {
                        notificationCard.style.display = 'block';
                        notificationList.innerHTML = '';

                        if (notifications.length === 0) {
                            noNotificationsMessage.style.display = 'block';
                        } else {
                            noNotificationsMessage.style.display = 'none';

                            function getRelativeTime(createdAt) {
                                const now = new Date();
                                const createdTime = new Date(createdAt);
                                const diffInSeconds = Math.floor((now - createdTime) / 1000);

                                if (diffInSeconds < 60) {
                                    return 'Now';
                                } else if (diffInSeconds < 3600) {
                                    const minutes = Math.floor(diffInSeconds / 60);
                                    return `${minutes} Minute${minutes > 1 ? 's' : ''} Ago`;
                                } else if (diffInSeconds < 86400) {
                                    const hours = Math.floor(diffInSeconds / 3600);
                                    return `${hours} Hour${hours > 1 ? 's' : ''} Ago`;
                                } else {
                                    const days = Math.floor(diffInSeconds / 86400);
                                    return `${days} Day${days > 1 ? 's' : ''} Ago`;
                                }
                            }

                            notifications.forEach((notification) => {
                                const li = document.createElement('li');
                                const relativeTime = getRelativeTime(notification.notification.created_at);

                                li.innerHTML = `
                                    <i class="fas fa-envelope notification-icon"></i>
                                    <div class="w-full">
                                        <div class="flex justify-content-between align-items-center mb-1">
                                            <div class="notification-title">${notification.notification.title}</div>
                                            <div class="notification-time">${relativeTime}</div>
                                        </div>
                                        <a class="notification-message truncate cursor-pointer" data-bs-toggle="modal" data-bs-target="#notification-modal">${notification.notification.message}</a>
                                    </div>
                                `;
                                li.addEventListener('click', () => showNotificationModal(notification));
                                notificationList.appendChild(li);
                            });
                        }
                    } else {
                        notificationCard.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error fetching notifications:', error);

                    notificationButton.disabled = false;
                    loadingSpinner.style.display = 'none';
                    bellIcon.style.display = 'inline-block';
                });
        }, 3000);
    });

    function showNotificationModal(notification) {
        const modalTitle = document.getElementById('modal-title');
        const modalMessage = document.getElementById('modal-message');
        const modalCreatedAt = document.getElementById('modal-created-at');

        modalTitle.textContent = notification.notification.title;
        modalMessage.textContent = notification.notification.message;
        modalCreatedAt.textContent = `${new Date(notification.notification.created_at).toLocaleString()}`;

        fetch('/broadcast/read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: notification.id,
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Notification marked as read:', data);
        })
        .catch(error => {
            console.error('Error marking notification as read:', error);
        });
    }

    document.getElementById('close-notification-card').addEventListener('click', () => {
        const notificationCard = document.getElementById('notification-card');
        notificationCard.style.display = 'none';
    });
</script>
