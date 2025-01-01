import toast, { Toaster } from 'solid-toast';

toast.options = {
    duration: 5000,
    position: 'top-right',
    // Add a delay before the toast is removed
    // This can be used to time the toast exit animation
    unmountDelay: 500,
    // Styling - Supports CSS Objects, classes, and inline styles
    // Will be applied to the toast container
    style: {
        'background-color': '#f00',
    },
    className: 'my-custom-class',
    // Custom Icon - Supports text as well as JSX Elements
    icon: '🍩',
    // Set accent colors for default icons that ship with Solid Toast
    iconTheme: {
        primary: '#fff',
        secondary: '#000',
    },
    // Aria Props - Supports all ARIA props
    aria: {
        role: 'status',
        'aria-live': 'polite',
    },
}

$(document).ready(function () {
    $('#button-register').click(() => {
        console.log(toast);

        toast.success('Toast launched successfully!');
    });
});