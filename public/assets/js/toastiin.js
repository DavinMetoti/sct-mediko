class Toast {
    constructor(message, type = 'info', duration = 3000) {
        this.message = message;
        this.type = type;
        this.duration = duration;
        this.createToast();
    }

    createToast() {
        const toast = document.createElement('div');
        toast.classList.add('toast', this.type);

        const icon = document.createElement('div');
        icon.classList.add('icon');
        icon.textContent = this.getIcon(this.type);

        const text = document.createElement('div');
        text.classList.add('message');
        text.textContent = this.message;

        toast.appendChild(icon);
        toast.appendChild(text);

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('fade-out');
            setTimeout(() => toast.remove(), 500);
        }, this.duration);
    }

    getIcon(type) {
        switch (type) {
            case 'success': return '✔';
            case 'error': return '✖';
            case 'info': return 'ℹ';
            case 'warning': return '⚠';
            default: return '';
        }
    }
}

// CSS styles
const style = document.createElement('style');
style.textContent = `
    .toast {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        padding: 15px;
        border-radius: 5px;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        min-width: 250px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        opacity: 1;
        transition: opacity 0.5s ease-in-out;
    }
    .toast.fade-out {
        opacity: 0;
    }
    .success { background: #2ecc71; }
    .warning { background: #f1c40f; }
    .error { background: #e74c3c; }
    .info { background: #3498db; }
    .icon {
        font-weight: bold;
        font-size: 20px;
    }
`;
document.head.appendChild(style);

// Example usage
new Toast('Success message!', 'success');
new Toast('Warning message!', 'warning');
new Toast('Error message!', 'error');
new Toast('Info message!', 'info');
