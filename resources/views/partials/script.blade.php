    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="{{ secure_asset('assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="//js.pusher.com/3.1/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/select/3.0.0/js/dataTables.select.js"></script>
    <script src="https://cdn.datatables.net/select/3.0.0/js/select.dataTables.js"></script>
    <script src="https://unpkg.com/sweet-scroll/sweet-scroll.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/turn.js/3/turn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
            // Pusher.logToConsole = true;

            // var pusher = new Pusher('d54d62cdcd51d9a71282', {
            //     cluster: 'ap1'
            // });

            // var channel = pusher.subscribe('quiz-channel');
            //     channel.bind('quiz-updated', function(data) {
            // });


        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        class ConfirmationModal {
            constructor() {
                this.modalElement = null;
            }

            open(options) {
                const { message, severity, onAccept, onReject } = options;

                const iconClass = this.getIconClass(severity);
                const buttonAccept = this.getButtonAccept(severity);

                const modalHTML = `
                <div class="modal fade" id="dynamicConfirmationModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="flex justify-content-between align-items-center p-3">
                            <h5 class="modal-title">Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex align-items-center">
                            <i class="${iconClass} me-2 fs-4"></i>
                            <span>${message}</span>
                        </div>
                        <div class="text-right p-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Reject</button>
                        <button type="button" class="btn ${buttonAccept}" id="accept-button" >Accept</button>
                        </div>
                    </div>
                    </div>
                </div>
                `;

                this.modalElement = document.createElement('div');
                this.modalElement.innerHTML = modalHTML;
                document.body.appendChild(this.modalElement);

                const modalInstance = new bootstrap.Modal(this.modalElement.querySelector('.modal'), {
                    backdrop: 'static',
                    keyboard: false,
                });

                const acceptButton = this.modalElement.querySelector('#accept-button');
                const rejectButton = this.modalElement.querySelector('.btn-secondary');

                acceptButton.addEventListener('click', () => {
                    if (onAccept) onAccept();
                    this.close();
                });

                rejectButton.addEventListener('click', () => {
                    if (onReject) onReject();
                    this.close();
                });

                modalInstance.show();
            }

            close() {
            if (this.modalElement) {
                const modalInstance = bootstrap.Modal.getInstance(this.modalElement.querySelector('.modal'));
                modalInstance.hide();
                this.modalElement.remove();
                this.modalElement = null;
            }
        }

        getIconClass(severity) {
            switch (severity) {
                case 'warn':
                    return 'bi bi-exclamation-triangle-fill text-warning';
                case 'success':
                    return 'bi bi-check-circle-fill text-success';
                case 'error':
                    return 'bi bi-x-circle-fill text-danger';
                default:
                    return '';
            }
        }

        getButtonAccept(severity) {
            switch (severity) {
                case 'warn':
                    return 'btn-warning';
                case 'success':
                    return 'btn-success';
                case 'error':
                    return 'btn-danger';
                default:
                    return '';
                }
            }
        }

        function formatDate(timestamp) {
            let date = new Date(timestamp);
            return new Intl.DateTimeFormat('id-ID', {
                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
            }).format(date);
        }


        $(document).ready(function () {
            const w = window.innerWidth;
            function adjustLayout() {

                if (w < 768) {
                    $('#sidebar').addClass('hide');
                    $('#navbar').addClass('full');
                    $('#content').addClass('expanded');
                } else {
                    $('#sidebar').removeClass('hide');
                    $('#navbar').removeClass('full');
                    $('#content').removeClass('expanded');
                }
            }

            function toggleSidebar() {
                $('#sidebar').toggleClass('hide');

                if ($('#sidebar').hasClass('hide')) {
                    $('#navbar').addClass('full');
                    $('#content').addClass('expanded');
                    $('.content-mediko-quiz').addClass('expanded');
                } else {
                    $('#navbar').removeClass('full');
                    $('#content').removeClass('expanded');
                    $('.content-mediko-quiz').removeClass('expanded');
                }
            }

            const element = document.querySelector('[data-f-id="pbf"]');
            if (element) {
                element.style.display = 'none';
            }
            $('#hamburger-button').click(function () {
                toggleSidebar();
            });

            adjustLayout();

            $(window).resize(function () {
                adjustLayout();
            });

            $('#dropdown-button').click(function (event) {

                event.stopPropagation();
                $('#dropdown-profile .dropdown-menu').toggleClass('show');
                $('#dropdown-notifications .dropdown-menu').removeClass('show');
            });

            $('#dropdown-notifications').click(function (event) {
                event.stopPropagation();
                $('#dropdown-notifications .dropdown-menu').toggleClass('show');
                $('#dropdown-profile .dropdown-menu').removeClass('show')
            });

            $(document).click(function (event) {
                if (!$(event.target).closest('.dropdown').length) {
                    $('.dropdown-menu').removeClass('show');
                }
            });
        });


        function toggleSidebar() {
            const sidebar = $('#sidebar');
            const navbar = $('#navbar');
            const content = $('#content');

            sidebar.toggleClass('hide');

            if (sidebar.hasClass('hide')) {
                navbar.addClass('full');
                content.addClass('expanded');
            } else {
                navbar.removeClass('full');
                content.removeClass('expanded');
            }
        }

        function toastSuccess(text) {
            if (!text) {
                console.error("No text provided for toast notification.");
                return;
            }

            const toast = Toastify({
                text: text,
                duration: 3000,
                close: false,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    padding: "1rem",
                    borderRadius: "8px",
                    background: "#17A34A",
                    minWidth: "300px",
                    color: "#fff",
                    boxShadow: "0px 4px 6px rgba(0, 0, 0, 0.1)",
                },
                onClick: function() {
                    toast.hideToast(); // Menutup toast saat diklik
                },
            });

            toast.showToast();
        }

        function toastError(text) {
            if (!text) {
                console.error("No text provided for toast notification.");
                return;
            }

            const toast = Toastify({
                text: text,
                duration: 3000,
                close: false,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    padding: "1rem",
                    borderRadius: "8px",
                    background: "#EF4444",
                    minWidth: "300px",
                    color: "#fff",
                    boxShadow: "0px 4px 6px rgba(0, 0, 0, 0.1)",
                },
                onClick: function() {
                    toast.hideToast(); // Menutup toast saat diklik
                },
            });

            toast.showToast();
        }

        function toastWarning(text) {
            if (!text) {
                console.error("No text provided for toast notification.");
                return;
            }

            const toast = Toastify({
                text: text,
                duration: 3000,
                close: false,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    padding: "1rem",
                    borderRadius: "8px",
                    background: "#EA570B",
                    minWidth: "300px",
                    color: "#fff",
                    boxShadow: "0px 4px 6px rgba(0, 0, 0, 0.1)",
                },
                onClick: function() {
                    toast.hideToast(); // Menutup toast saat diklik
                },
            });

            toast.showToast();
        }


    </script>